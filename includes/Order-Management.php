<?php
require_once 'includes/Order-Complete.php';

class ordermanagment
{

    public function createNewOrder($order_array)
    {
        try{
           
            $user_ID = get_current_user_id();
            $user_info = get_userdata($user_ID);
            $firstName=get_user_meta($user_ID, "first_name", true);
            $lastName= get_user_meta($user_ID, "last_name", true);
            $fullname= $firstName." ".$lastName;
            $lastInsertId = contentmanagerlogging('New Order Created',"Admin Action",serialize($order_array),$user_ID,$user_info->user_email,"pre_action_data");

            $address = array(
                'first_name' => $order_array['first_name'],
            'last_name'  => $order_array['last_name'],
            'company'    => $order_array['company'],
            'email'      => $order_array['email'],
            'phone'      => $order_array['phone'],
            'address_1'  => $order_array['address_1'],
            'address_2'  => $order_array['address_2'],
            'city'       => $order_array['city'],
            'state'      => $order_array['state'],
            'postcode'   => $order_array['postcode'],
            'country'    => $order_array['region']
         
           );
        //$lastInsertId = contentmanagerlogging('Order Create Request',"Admin Action",serialize($order_array),''.$user_ID,$user_info->user_email,"pre_action_data");

        $args = array(
            'customer_id'   => $order_array['customer_id'],
        );

        $productArray =  json_decode(stripslashes($order_array["productArray"]), true);
        $order = wc_create_order($args);
        $historyName='OrderHistory';
        $my_post = array(
            'post_title' => $historyName,
            'post_content' => '',
            'post_date' => $order_array['timezone'],
            'post_status' => 'draft',
            'post_author' =>   $user_ID,
            'post_type'=>'EGPL_Order_History',
           
        );
        $product_Array = array();
        foreach ($productArray as $key => $values) { 

            $itemData = $values['id'];
            $quantity = $values['quantity'];
            $price = $values['price'];
            $Name = $values['Name'];
         
           
                $coupon = array(
                    'id' =>  $itemData,
                    'Name' =>  $Name,
                    'quantity' =>  $quantity,
                    'price' => $price,
               );
             
               array_push($product_Array, $coupon);  
          
            // if(in_array($itemData, $items))
            // $item_ids_arrays[] = $itemData;
            
        }
        $order_array['productArray']=$product_Array; 

        // Insert the post into the database.
            $id = wp_insert_post($my_post);
           
            
            $status_log='Order Created';       
            update_post_meta($id, 'status_log',  $status_log );
            update_post_meta($id, 'order_id', $order->id );
            update_post_meta($id, 'history_id', 1 );
            update_post_meta($id, 'custome_meta',$order_array );
          
            $noteArray =  json_decode(stripslashes($order_array["noteArray"]), true);
        foreach($noteArray as $key =>$value)
        {
            $note = $value['note'];
            update_post_meta( $order->id, '_order_custome_note',$note );
            break;
        }
        foreach ($productArray as $key => $value) {

            $itemData = $value['id'];
            $checks = $value['check'];

            $wc_deposit_enabled = get_post_meta($itemData, '_wc_deposit_enabled', true);
            $wc_deposit_amount = get_post_meta($itemData, '_wc_deposit_amount', true);
            $wc_deposit_type = get_post_meta($itemData, '_wc_deposit_type', true);
            $wc_amount = get_post_meta($itemData, '_price', true);
            $deferred_discount_amount = 0;
            // echo $wc_deposit_amount;
            // echo $wc_amount;
            if( $wc_deposit_type=='fixed')
            {
                $wc_deposit_amount= $wc_deposit_amount;
            }else{
                $wc_deposit_amount= ($wc_amount/100)*$wc_deposit_amount;
                // $wc_deposit_amount=$wc_amount-$wc_deposit_amount;
            }
            // echo $wc_deposit_amount;
            // echo $wc_amount;
            if ($value['partial_check'] > 0) {
                $item_id = $order->add_product(get_product($itemData), $value['quantity'], array(
                    'totals' => array(
                        'subtotal'     => $wc_deposit_amount, // cost before discount (for line quantity, not just unit)
                    )
                ));
                $item = $order->get_item($item_id, false);
                $grand_total = $value['quantity'] * $wc_deposit_amount * $checks;
                $grand_total_price = $value['quantity'] * $wc_amount * $checks;
                // echo 'Grand_Total=' .$grand_total;
                // echo 'Grand_Total_Price=' .$grand_total_price;
                $item->set_subtotal($grand_total);
                $item->set_total($grand_total);
                $item->add_meta_data('_is_deposit', 'yes');
                $item->add_meta_data('_deposit_full_amount', $grand_total_price);
                $item->add_meta_data('_deposit_full_amount_ex_tax', $grand_total_price);
                $item->add_meta_data('_deposit_deposit_amount_ex_tax', $grand_total);
                $item->save();
            } else {
                $item_id =$order->add_product(get_product($itemData), $value['quantity']);
                $item = $order->get_item($item_id, false);
                $grand_total = $value['quantity'] * $wc_amount * $checks;
                $item->set_subtotal($grand_total);
                $item->set_total($grand_total);
                $item->save();
            }
        }
        $order->apply_coupon($order_array['coupon_code_cart']);
        $order->apply_coupon($order_array['coupon_code_prdt']);
        $order->set_date_created( $order_array['orderDate'] );
        $order->set_date_paid( $order_array['payment_date'] );
        
        $order->set_address($address, 'billing');
        $order->calculate_totals();
        $note = $order_array['note'];
        $payment_gateways = WC()->payment_gateways->payment_gateways();
        $gateway=$order_array['payment_method'];
        $order->set_payment_method($payment_gateways[$gateway]);
        update_post_meta($order->id,'_transaction_id',$order_array['Transaction_ID']);
        if (!empty($order_array['order_status'])) {

                $order->update_status($order_array['order_status']);
        }

        $order->save();   
        foreach ($order->get_items() as $item) {
          
            $porduct_ids_array[] = $item['product_id'];
        }   
        $customer_id = $order_array['customer_id'];
        $objA = new orderComplete();
        if($order_array['order_status'] !== 'wc-completed')
        {
            $NewOrderStatusPartial = $objA->create_new_partial_order($order->id, $order_array['timezone'],$order_array);

        }
        if( $order_array['order_status'] !== 'wc-cancelled' && $order_array['order_status'] !== 'wc-refunded' &&   $order_array['order_status'] !== 'wc-failed')
        {
            
           
            $NewOrderStatusPartials = $objA->updateuser_role_on_purches($order->id, $porduct_ids_array, $customer_id, true);

        }
        if($order_array['emailinvoice']==1)
        {
            $emails = WC_Emails::instance();
            $emails->customer_invoice( wc_get_order( $order->id ) );

        }
        $order_date_payed = $order->get_date_paid();
        $order_date_pay=  explode("T", $order_date_payed );
        if(empty($order_date_pay))
        {
            $order_date_pay=  explode(" ", $order_date_payed );
        }
        $order_array['payment_date']= $order_date_pay[0];
        update_post_meta($id, 'custome_meta', $order_array );
      
          
        contentmanagerlogging_file_upload ($lastInsertId,serialize($order));
        if ($order) {
            // return "end";
            return "success";
            die();
        }
    }catch (Exception $e) {
       
     contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
        //$NewOrderStatusPartial = $objA->new_order_on_hold_notification($order->id);

    }

    public function updateOrder($order_array)
    {
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Order Updated',"Admin Action",serialize($order_array),$user_ID,$user_info->user_email,"post_action_data");
        $customer_id = $order_array['customer_id'];
        $orderId = $order_array['order_id'];
        $order = wc_get_order($orderId);
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'EGPL_Order_History',
            'post_status'      => 'draft',
            
            );
        $productArray =  json_decode(stripslashes($order_array["productArray"]), true);
        $listOFOrderHistory = get_posts( $args );
        $history_id=0;
        foreach ($listOFOrderHistory as $key => $value) { 
            $id= get_post_meta($value->ID,"order_id",true);
            if($id==$orderId)
            {
                $history_id= get_post_meta($value->ID,"history_id",true);
                break;

            }
        }
        $historyName='OrderHistoryUpdate';
        $my_post = array(
            'post_title' => $historyName,
            'post_content' => '',
            'post_date' => $order_array['timezone'],
            'post_status' => 'draft',
            'post_author' =>   $user_ID,
            'post_type'=>'EGPL_Order_History',
           
        );

        // Insert the post into the database.
        $id = wp_insert_post($my_post);
        $product_Array = array();
        foreach ($productArray as $key => $values) { 

            $itemData = $values['id'];
            $quantity = $values['quantity'];
            $price = $values['price'];
            $Name = $values['Name'];
         
           
                $coupon = array(
                    'id' =>  $itemData,
                    'Name' =>  $Name,
                    'quantity' =>  $quantity,
                    'price' => $price,
               );
             
               array_push($product_Array, $coupon);  
          
            // if(in_array($itemData, $items))
            // $item_ids_arrays[] = $itemData;
            
        }
        $order_array['productArray']=$product_Array; 
        $status_log='Order Updated'; 
        $history_id++;      
        update_post_meta($id, 'status_log',  $status_log );
        update_post_meta($id, 'order_id',  $orderId );
        update_post_meta($id, 'history_id', $history_id);
        update_post_meta($id, 'custome_meta',$order_array );
           
        // echo '<pre>';
        // print_r($order);
        // exit;
        $address = array(
            'first_name' => $order_array['first_name'],
            'last_name'  => $order_array['last_name'],
            'company'    => $order_array['company'],
            'email'      => $order_array['email'],
            'phone'      => $order_array['phone'],
            'address_1'  => $order_array['address_1'],
            'address_2'  => $order_array['address_2'],
            'city'       => $order_array['city'],
            'state'      => $order_array['state'],
            'postcode'   => $order_array['postcode'],
            'country'    => $order_array['region']
        );
        $order_items = $order->get_items();
       
        $order_data = $order->get_data();
        $order_status = $order_data['status'];
        $payment_gateways = WC()->payment_gateways->payment_gateways();
        $gateway=$order_array['payment_method'];
    
        $order->set_payment_method($payment_gateways[$gateway]);
        $noteArray =  json_decode(stripslashes($order_array["noteArray"]), true);
        // This is an existing SIMPLE product
        foreach($noteArray as $key =>$value)
        {
            $note = $value['note'];
            update_post_meta( $orderId, '_order_custome_note',$note );
            break;
        }
        update_post_meta($orderId, '_customer_user', $customer_id);
        $order->set_date_created( $order_array['orderDate'] );
        $order->set_date_paid( $order_array['payment_date'] );
        $order_date_payed = $order->get_date_paid();
        $order_date_pay=  explode("T", $order_date_payed );
        if(empty($order_date_pay))
        {
            $order_date_pay=  explode(" ", $order_date_payed );
        }
        $order_array['payment_date']= $order_date_pay[0];
        update_post_meta($id, 'custome_meta', $order_array );
        // $order->apply_coupon($order_array['coupon_code_cart']);
        // $order->apply_coupon($order_array['coupon_code_prdt']);
        // if(!empty($order_array['cartdic']))
        // {
        //      echo $order_array['cartdic'];
            
        //     $a=$order_array['cartdic'];

        //     echo $orderId.'-------------------'.$a;


        //     update_post_meta($orderId,'_cart_discount',$a);
        

        //     echo $orderId.'-------------------'.get_post_meta($orderId,'_cart_discount',true);


        // }else if(!empty($order_array['prodic'])){
        //      echo $order_array['prodic'];
        //     $a=$order_array['prodic'];
        //     update_post_meta($orderId,'_cart_discount',$a);
        // }
        $order->set_address($address, 'billing');
        update_post_meta($orderId,'_transaction_id',$order_array['Transaction_ID']);
        if(empty($order_items))
        {
            $order->update_status($order_array['order_status']);
            foreach ($productArray as $key => $values) { 

                $itemData = $values['id'];
                $item_ids_arrays[] = $itemData;
                $quantity = $values['quantity'];
                $price = $values['price'];
                $product_status = get_post_meta($itemData, '_stock_status', true);
                $total_sales=get_post_meta($itemData, 'total_sales', true);
                $stock = get_post_meta($itemData, '_stock', true);
            
                if ($values['partial_check'] > 0) {

                        
                    $wc_deposit_enabled = get_post_meta($itemData, '_wc_deposit_enabled', true);
                    $wc_deposit_amount = get_post_meta($itemData, '_wc_deposit_amount', true);
                    $wc_deposit_type = get_post_meta($itemData, '_wc_deposit_type', true);
                    $wc_amount = get_post_meta($itemData, '_price', true);
                    $deferred_discount_amount = 0;
                    if( $wc_deposit_type=='fixed')
                    {
                        $wc_deposit_amount= $wc_deposit_amount;
                    }else{
                        $wc_deposit_amount= ($wc_amount/100)*$wc_deposit_amount;
                        $wc_deposit_amount=$wc_amount-$wc_deposit_amount;
                    }
                    $item_id = $order->add_product(get_product($itemData), $values['quantity'], array(
                        'totals' => array(
                            'subtotal'     => $wc_deposit_amount, // cost before discount (for line quantity, not just unit)
                        )
                    ));
                    $item = $order->get_item($item_id, false);
                    $grand_total = $values['quantity'] * $wc_deposit_amount;
                    $grand_total_price = $values['quantity'] * $wc_amount;
                    $item->set_subtotal($grand_total);
                    $item->set_total($grand_total);
                    $item->add_meta_data('_is_deposit', 'yes');
                    $item->add_meta_data('_deposit_full_amount', $grand_total_price);
                    $item->add_meta_data('_deposit_full_amount_ex_tax', $grand_total_price);
                    $item->add_meta_data('_deposit_deposit_amount_ex_tax', $grand_total);
                    $item->save();
                } else {
                    $wc_amount = get_post_meta($itemData, '_price', true);
                    $item_id =$order->add_product(get_product($itemData), $quantity);
                    $item = $order->get_item($item_id, false);
                    $grand_total =  $quantity *  $wc_amount;
                    $item->set_subtotal($grand_total);
                    $item->set_total($grand_total);
                    $item->save();
                }
                $item_quantity = $quantity;

                if ($item_quantity < 0) {
                    // echo '----337-------';
                    $item_quantity = abs($item_quantity);
                    $new_stock = $stock - $item_quantity;
                } else {
                    // echo '----341-------';
                    $new_stock = $stock - $item_quantity;
                }
                update_post_meta($itemData, '_stock', $new_stock);
                $total_sales=$total_sales +$item_quantity;
               // echo '-----373------'.  $total_sales;
                update_post_meta($itemData,'total_sales',$total_sales);
                $stock = get_post_meta($itemData, '_stock', true);
                if ( $stock <=  0) {
                    $out_of_stock_staus = 'outofstock';
                    update_post_meta($itemData, '_stock_status', wc_clean($out_of_stock_staus));
                } else {
                    $out_of_stock_staus = 'instock';
                    update_post_meta($itemData, '_stock_status', wc_clean($out_of_stock_staus));
                }
            }
            $order->apply_coupon($order_array['coupon_code_cart']);
            $order->apply_coupon($order_array['coupon_code_prdt']);
            $order->calculate_totals();
            $order->save();
            $initial_id='';

            $orders = wc_get_orders( array('numberposts' => -1) );

            foreach( $orders as $ordR ){

                $orderID= $ordR->get_id() ; // The order ID
                $ORDER = wc_get_order($orderID);    
                $order_items = $ORDER->get_items();
                foreach ($order_items as $item_id => $item) {
                    $initial_id = wc_get_order_item_meta( $item_id, '_remaining_balance_order_id', true );
                    $porduct_ids_arrays[] = $initial_id;
                }

            }
        
            $index = array_search( $orderId, $porduct_ids_arrays);
            $objA = new orderComplete();
            if($order_array['order_status'] == 'wc-partial-payment' && $index ==false)
            { 
            
                $NewOrderStatusPartial = $objA->create_new_partial_order($orderId, $order_array['timezone'],$order_array);
            } 
            foreach ($order->get_items() as $item) {
                $porduct_ids_array[] = $item['product_id'];
            }
            $customer_id = $order_array['customer_id'];

            $NewOrderStatusPartial = $objA->updateuser_role_on_purches($orderId, $porduct_ids_array, $customer_id, false);
            return "success";
            die();
        }else{

            
         
            foreach ($order_items as $item_id => $item) {
                $restored_stock = wc_get_order_item_meta( $item_id, '_restock_checked', true );
                if(!empty($restored_stock))
                {
                    break;
                }
            }
                foreach ( $order->get_items() as $item_id => $item ) {
                    $custom_field = wc_get_order_item_meta( $item_id, '_remaining_balance_order_id', true );   
                }
            if($order_status=='cancelled' && $order_array['order_status'] != 'wc-cancelled' && $order_array['order_status'] != 'wc-refunded' && $order_array['order_status'] != 'wc-partial-payment' )
            {  
                $product_Array = array();
                if($order_array['order_status']=='wc-completed')
                {  
                        foreach ($order->get_items() as $item) {

                            $product_id = $item["product_id"];
                            $item_quantity = $item['quantity'];
                            $stock_quantity = get_post_meta($product_id, '_stock', true);
                            $total_sales=get_post_meta($product_id, 'total_sales', true);
                            $coupon = array(
                                'id' => $product_id,
                                'item_quantity' => $item_quantity,
                                'stock_quantity'=> $stock_quantity,
                                'sales' => $total_sales, );
                        array_push($product_Array, $coupon);
                
                        }
                    
                    // echo 'pre';
                    // print_r($product_Array);
                    $order->update_status($order_array['order_status']);
                    foreach ($order->get_items() as $item) {

                        $product_id = $item["product_id"];
                        $item_quantity = $item['quantity'];
                        foreach ($product_Array as $key => $value) {
                            //  echo $item_quantity;
                            //  echo $product_id;
                            //  echo 'pre';
                            //  print_r($value);
                            if($value['id']==$product_id)
                            {
                            $out_of_stock_staus = 'instock';
                            $sales=$value['sales'];
                            $stock=$value['stock_quantity'];
                            $item_SALE=$item_quantity+  $sales;
                            $item_stock=  $stock-$item_quantity;
                            update_post_meta($product_id,'total_sales',$item_SALE);
                            update_post_meta($product_id, '_stock', $item_stock);
                            if ($value->stock_quantity > 0) {
                                update_post_meta($product_id, '_stock_status', wc_clean($out_of_stock_staus));
                            }
                            }
                        }
                    }
                    $order->calculate_totals();
                    $order->save();
                    if(!empty($order_array['coupon_code_cart']))
                    {

                        $order->apply_coupon($order_array['coupon_code_cart']);
                    }
                    if(!empty($order_array['coupon_code_prdt']))
                    {
                        $order->apply_coupon($order_array['coupon_code_prdt']);

                    }
                    return "success";
                }
                foreach ($order->get_items() as $item) {

                    $product_id = $item["product_id"];
                    $item_quantity = $item['quantity'];
                    $stock_quantity = get_post_meta($product_id, '_stock', true);
                    $total_sales=get_post_meta($product_id, 'total_sales', true);
                    
                    $product_status = get_post_meta($product_id, '_stock_status', true);
                    $out_of_stock_staus = 'instock';
                    $restored_stock = $stock_quantity - $item_quantity;
                    $total_sales=$total_sales -$item_quantity;
                    update_post_meta($product_id,'total_sales',$total_sales);
                    update_post_meta($product_id, '_stock', $restored_stock);
                    //echo  '188';
                    if ($restored_stock > 0) {
                        update_post_meta($product_id, '_stock_status', wc_clean($out_of_stock_staus));
                    }
                }
                $order->update_status($order_array['order_status']);
                    $order->calculate_totals();
                    $order->save();
                    if(!empty($order_array['coupon_code_cart']))
                    {

                        $order->apply_coupon($order_array['coupon_code_cart']);
                    }
                    if(!empty($order_array['coupon_code_prdt']))
                    {
                        $order->apply_coupon($order_array['coupon_code_prdt']);

                    }
                return "success";
            }
            if (!empty($order_array['order_status'])) {
            
                $order->update_status($order_array['order_status']);
                if($order_array['stock']=='restore')  
                {
                        foreach ( $order->get_items() as $item_id => $item ) {
                            wc_add_order_item_meta($item_id, '_restock_checked', 'restock' );   
                        }
                }
                if($order_array['stock']=='restore')  
                {
                    foreach ($order->get_items() as $item) {

                        $product_id = $item["product_id"];
                        $item_quantity = $item['quantity'];
                        $out_of_stock_staus = 'instock';
                        $stock_quantity = get_post_meta($product_id, '_stock', true);
                        $total_sales=get_post_meta($product_id, 'total_sales', true);
                        $product_status = get_post_meta($product_id, '_stock_status', true);
                    
                    
                        // echo 'SalesBefore='. $total_sales;
                        // echo '+='. $stock_quantity;
                        
                        if($order_array['order_status']=='wc-cancelled')
                        {   
                            //$restored_stock = $stock_quantity + $item_quantity;
                            $total_sales=$total_sales-$item_quantity;
                            update_post_meta($product_id,'total_sales',$total_sales);
                            //update_post_meta($product_id, '_stock', $restored_stock);
                        }else if($order_array['order_status']=='wc-completed'){

                            // $restored_stock = $stock_quantity + $item_quantity;
                            // $total_sales=$total_sales -$item_quantity;
                            // update_post_meta($product_id, '_stock', $restored_stock);
                            // update_post_meta($product_id,'total_sales',$total_sales);
                        
                        }else{

                            $restored_stock = $stock_quantity + $item_quantity;
                            $total_sales=$total_sales -$item_quantity;
                            update_post_meta($product_id, '_stock', $restored_stock);
                            update_post_meta($product_id,'total_sales',$total_sales);
                        }
                            // echo 'SalesAftar='.  $total_sales;
                            // echo 'StockAfter='. $stock_quantity;  
                        if ($restored_stock > 0) {
                            update_post_meta($product_id, '_stock_status', wc_clean($out_of_stock_staus));
                        }
                    }
                    $order->calculate_totals();
                    $order->save();
                    if(!empty($order_array['coupon_code_cart']))
                    {

                        $order->apply_coupon($order_array['coupon_code_cart']);
                    }
                    if(!empty($order_array['coupon_code_prdt']))
                    {
                        $order->apply_coupon($order_array['coupon_code_prdt']);

                    }
                    return "success";
                }else if($order_array['order_status']=='wc-refunded'){
                    $order->calculate_totals();
                    $order->save();

                    return "success";
                }else if($order_array['order_status']=='wc-cancelled'){

                    // echo '4088';
                    if($order_array['orderStatus']=='wc-completed')
                    {
                            foreach ($order->get_items() as $item) {

                                $product_id = $item["product_id"];
                                $item_quantity = $item['quantity'];
                                $stock_quantity = get_post_meta($product_id, '_stock', true);
                                $total_sales=get_post_meta($product_id, 'total_sales', true);
                                
                                $product_status = get_post_meta($product_id, '_stock_status', true);
                                $out_of_stock_staus = 'instock';
                                $restored_stock = $stock_quantity - $item_quantity;
                                // $total_sales=$total_sales -$item_quantity;
                                // update_post_meta($product_id,'total_sales',$total_sales);
                                update_post_meta($product_id, '_stock', $restored_stock);
                                //echo  '188';
                                if ($restored_stock > 0) {
                                    update_post_meta($product_id, '_stock_status', wc_clean($out_of_stock_staus));
                                }
                            }
                    }
                    $order->calculate_totals();
                    $order->save();
                    return "success";
                }
            }
            foreach ($productArray as $key => $values) {

                $itemData = $values['id'];
                $item_ids_arrays[] = $itemData;
            }
            //when item is deleted from order//
            foreach ($order->get_items() as $item) {
                $item_id = $item['product_id'];
                $porduct_ids_arrays[] = $item_id;
                if (array_search($item_id, $item_ids_arrays) === false) {

                    $stock = get_post_meta($item_id, '_stock', true);
                    $total_sales=get_post_meta($item_id, 'total_sales', true);
                
                    // echo '--AD-------'. $stock;
                    // echo 'SalesBefore='. $total_sales;
                    // echo 'StockBefore='. $stock;
                    $product_value = $item->get_data();
                    $order_item_id = $product_value['id'];
                    $order_item_quantity = $product_value['quantity'];
                    $item_quantity = $order_item_quantity + $stock;
                    $total_sales=$total_sales -$order_item_quantity;
                    // $item = $order->get_item(  $order_item_id, false );
                    $order->remove_item($order_item_id);
                    update_post_meta($item_id, '_stock', $item_quantity);
                    update_post_meta($item_id,'total_sales',$total_sales);
                    if ($item_quantity > 0) {
                        $out_of_stock_staus = 'instock';
                        update_post_meta($item_id, '_stock_status', wc_clean($out_of_stock_staus));
                    }
                    // $item->save();		  	
                }
            }
        
            // echo $custom_field;
            //when item is added to order//
            foreach ($productArray as $key => $values) { 

                $itemData = $values['id'];
                $item_ids_arrays[] = $itemData;
                $quantity = $values['quantity'];
                $price = $values['price'];
                $product_status = get_post_meta($itemData, '_stock_status', true);
                $total_sales=get_post_meta($itemData, 'total_sales', true);
                $stock = get_post_meta($itemData, '_stock', true);
                // echo 'SalesBefore='. $total_sales;
                // echo 'StockBefore='. $stock;
                if($order_array['order_status']=='wc-completed' && $order_array['orderStatus']=='wc-pending-deposit' && empty($custom_field) )
                {
                    // echo 'A';
                    $stock=$stock+ $quantity;
                    $total_sales=$total_sales-$quantity;
                } elseif($order_array['order_status']=='wc-completed' && $order_array['orderStatus']=='wc-partial-payment' && empty($custom_field))
                {
                    $stock=$stock+ $quantity;
                    $total_sales=$total_sales-$quantity;
                }else if($order_array['order_status']=='wc-completed' && $order_array['orderStatus']=='wc-pending-deposit'  && !empty($custom_field))
                {
                    // echo "B";
                    //$stock=$stock+ $quantity;
                    $total_sales=$total_sales-$quantity;
                }else if($order_array['order_status']=='wc-partial-payment' && $order_array['orderStatus']=='wc-cancelled')
                {
                    $stock=$stock- $quantity;
                    $total_sales=$total_sales+$quantity;
                }else if($order_array['order_status']=='wc-completed' && $order_array['orderStatus']=='wc-cancelled'  && !empty($custom_field)){
                    $total_sales=$total_sales-$quantity;
                }else if($order_array['order_status']=='wc-completed' && $order_array['orderStatus']=='wc-partial-payment'  && !empty($custom_field)){
                    $total_sales=$total_sales-$quantity;
                    //$stock=$stock- $quantity;
                } 
                $item_quantity = 0;
                $product_value = 'p';
                $index = array_search($itemData, $porduct_ids_arrays);
                if ($index !== false) {
                    foreach ($order_items as $item) {
                        if ($item['product_id'] == $itemData) {
                            $product_value = $item->get_data();
                        }
                    }
                    if ($values['partial_check'] > 0) {
                        //echo '-----MOM------';
                        $wc_deposit_enabled = get_post_meta($itemData, '_wc_deposit_enabled', true);
                        $wc_deposit_amount = get_post_meta($itemData, '_wc_deposit_amount', true);
                        $wc_deposit_type = get_post_meta($itemData, '_wc_deposit_type', true);
                        $wc_amount = get_post_meta($itemData, '_price', true);
                        $deferred_discount_amount = 0;
                        $order_item_id = $product_value['id'];
                        $order_item_quantity = $product_value['quantity'];
                        // if($order_item_quantity==0)
                        // {
                        //     $quantity=0; 
                        // }
                        if( $wc_deposit_type=='fixed')
                        {
                            $wc_deposit_amount= $wc_deposit_amount;
                        }else{
                            $wc_deposit_amount= ($wc_amount/100)*$wc_deposit_amount;
                            $wc_deposit_amount=$wc_amount-$wc_deposit_amount;
                        }
                        $item_quantity = $order_item_quantity - $quantity;
                        $item = $order->get_item($order_item_id, false);
                        $grand_total = $quantity * $wc_deposit_amount;
                        $grand_total_price = $quantity * $wc_amount;
                        $qty = (int) $quantity;
                        $item->set_quantity($qty);
                        $item->set_subtotal($grand_total);
                        $item->set_total($grand_total);
                        $item->add_meta_data('_is_deposit', 'yes');
                        $item->add_meta_data('_deposit_full_amount', $grand_total_price);
                        $item->add_meta_data('_deposit_full_amount_ex_tax', $grand_total_price);
                        $item->add_meta_data('_deposit_deposit_amount_ex_tax', $grand_total);
                        $item->save();
                        // if($order_item_quantity==0)
                        // {
                        //     $quantity=0; 
                        // }
                    } else {

                        // echo '-----DAD------';
                        $qty = (int) $quantity;
                        $order_item_quantity = $product_value['quantity'];
                        if($order_item_quantity!=$qty)
                        {
                        $product = wc_get_product($itemData);
                        $price = (int) $quantity * $price;
                        $order_item_id = $product_value['id'];
                    
                        $item_quantity = $order_item_quantity - $quantity;
                    
                        $item = $order->get_item($order_item_id, false);
                        $item->set_quantity($qty);
                        $item->set_subtotal($price);
                        $item->set_total($price);
                        $item->save();
                        }
                    }

                    //   echo '-----A------'. $order_item_quantity;
                    //   echo '-------B----'. $item_quantity;
                    //   echo '-----C------'.  $total_sales;
                    //   echo '-----D------'.  $stock;
                    
                    if ($item_quantity < 0) {
                        //  echo '----3-------';
                        $item_quantity = abs($item_quantity);
                        $total_sales=$total_sales +$item_quantity;
                        if($order_item_quantity==0)
                        {
                            $item_quantity=0; 
                        }
                        $new_stock = $stock - $item_quantity;
                    } else {
                    //  echo '---4--------'. $item_quantity;
                    $total_sales=$total_sales -$item_quantity;
                    if($order_item_quantity==0)
                        {
                            $item_quantity=0; 
                        }
                        $new_stock = $stock + $item_quantity;
                    
                    }
                    update_post_meta($itemData, '_stock', $new_stock);
                    update_post_meta($itemData,'total_sales',$total_sales);
                    if ($new_stock <= 0) {
                        $out_of_stock_staus = 'outofstock';
                        update_post_meta($itemData, '_stock_status', wc_clean($out_of_stock_staus));
                    } else {
                        $out_of_stock_staus = 'instock';
                        update_post_meta($itemData, '_stock_status', wc_clean($out_of_stock_staus));
                    }
                } else {

                    if ($values['partial_check'] > 0) {

                    
                        $wc_deposit_enabled = get_post_meta($itemData, '_wc_deposit_enabled', true);
                        $wc_deposit_amount = get_post_meta($itemData, '_wc_deposit_amount', true);
                        $wc_deposit_type = get_post_meta($itemData, '_wc_deposit_type', true);
                        $wc_amount = get_post_meta($itemData, '_price', true);
                        $deferred_discount_amount = 0;
                        if( $wc_deposit_type=='fixed')
                        {
                            $wc_deposit_amount= $wc_deposit_amount;
                        }else{
                            $wc_deposit_amount= ($wc_amount/100)*$wc_deposit_amount;
                            $wc_deposit_amount=$wc_amount-$wc_deposit_amount;
                        }
                        $item_id = $order->add_product(get_product($itemData), $values['quantity'], array(
                            'totals' => array(
                                'subtotal'     => $wc_deposit_amount, // cost before discount (for line quantity, not just unit)
                            )
                        ));
                        $item = $order->get_item($item_id, false);
                        $grand_total = $values['quantity'] * $wc_deposit_amount;
                        $grand_total_price = $values['quantity'] * $wc_amount;
                        $item->set_subtotal($grand_total);
                        $item->set_total($grand_total);
                        $item->add_meta_data('_is_deposit', 'yes');
                        $item->add_meta_data('_deposit_full_amount', $grand_total_price);
                        $item->add_meta_data('_deposit_full_amount_ex_tax', $grand_total_price);
                        $item->add_meta_data('_deposit_deposit_amount_ex_tax', $grand_total);
                        $item->save();
                    } else {
                        $item_id =$order->add_product(get_product($itemData), $quantity);
                        $item = $order->get_item($item_id, false);
                        $item->save();
                    }
                    $item_quantity = $quantity;

                    if ($item_quantity < 0) {
                        // echo '----337-------';
                        $item_quantity = abs($item_quantity);
                        $new_stock = $stock - $item_quantity;
                    } else {
                        // echo '----341-------';
                        $new_stock = $stock - $item_quantity;
                    }
                    update_post_meta($itemData, '_stock', $new_stock);
                    $total_sales=$total_sales +$item_quantity;
                // echo '-----373------'.  $total_sales;
                    update_post_meta($itemData,'total_sales',$total_sales);
                    if ($new_stock <= 0) {
                        $out_of_stock_staus = 'outofstock';
                        update_post_meta($itemData, '_stock_status', wc_clean($out_of_stock_staus));
                    } else {
                        $out_of_stock_staus = 'instock';
                        update_post_meta($itemData, '_stock_status', wc_clean($out_of_stock_staus));
                    }
                }
            }
        
                    

                    

                    

                
            // $order->save();
            if (isset($_GET['orderid'])) {
                $ID =  $_GET['orderid'];
            }else{
                $ID =   $orderId;

            }
            $order->apply_coupon($order_array['coupon_code_cart']);
            $order->apply_coupon($order_array['coupon_code_prdt']);
            $order->calculate_totals();
            $order->save();
            $initial_id='';

            $orders = wc_get_orders( array('numberposts' => -1) );

            foreach( $orders as $ordR ){

                $orderID= $ordR->get_id() ; // The order ID
                $ORDER = wc_get_order($orderID);    
                $order_items = $ORDER->get_items();
                foreach ($order_items as $item_id => $item) {
                    $initial_id = wc_get_order_item_meta( $item_id, '_remaining_balance_order_id', true );
                    $porduct_ids_arrays[] = $initial_id;
                }

            }
        
            $index = array_search( $orderId, $porduct_ids_arrays);
            $objA = new orderComplete();
            if($order_array['order_status'] == 'wc-partial-payment' && $index ==false)
            { 
            
                $NewOrderStatusPartial = $objA->create_new_partial_order($orderId, $order_array['timezone'],$order_array);
            } 
            foreach ($order->get_items() as $item) {
                $porduct_ids_array[] = $item['product_id'];
            }
            $customer_id = $order_array['customer_id'];

            $NewOrderStatusPartial = $objA->updateuser_role_on_purches($orderId, $porduct_ids_array, $customer_id, false);
        }

      
        return "success";
        die();
    }

    public function deleteOrder($order_array)
    {

        $lastInsertId = contentmanagerlogging('Order Deleted',"Admin Action",serialize($order_array),'','',"pre_action_data");

        $order_id = $order_array['order_id'];
        $stock_restore = $order_array['stock'];
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        $user_ID = get_current_user_id();
        $historyName='OrderHistory';
        $my_post = array(
            'post_title' => $historyName,
            'post_content' => '',
            'post_date' => $order_array['orderDate'],
            'post_status' => 'draft',
            'post_author' =>   $user_ID,
            'post_type'=>'EGPL_Order_History',
           
        );

        // Insert the post into the database.
            $id = wp_insert_post($my_post);
           
            
            $status_log='Order Deleted';       
            update_post_meta($id, 'status_log',  $status_log );
            update_post_meta($id, 'order_id', $order_id );
            update_post_meta($id, 'custome_meta',$order_array );
            if($stock_restore == 'restore')  
            {
                  foreach ( $order->get_items() as $item_id => $item ) {
                      wc_add_order_item_meta($item_id, '_restock_checked', 'restock' );   
                  }
            }
        foreach ($order->get_items() as $item) {

            if ($stock_restore == 'restore') {

                $product_id = $item["product_id"];
                $item_quantity = $item['quantity'];
                $stock_quantity = get_post_meta($product_id, '_stock', true);
                $total_sales=get_post_meta($product_id, 'total_sales', true);
                $product_status = get_post_meta($product_id, '_stock_status', true);
                $out_of_stock_staus = 'instock';
                $restored_stock = $stock_quantity + $item_quantity;
                $newTotalSales=$total_sales- $item_quantity;
                update_post_meta($product_id, '_stock', $restored_stock);
                update_post_meta($product_id,'total_sales',$newTotalSales);
                if ($restored_stock > 0) {
                    update_post_meta($product_id, '_stock_status', wc_clean($out_of_stock_staus));
                }
            }
        }

        wp_delete_post($order_id, true);
    }



    public function getDetails($sponsor_ids){

        require_once plugin_dir_path( __DIR__ ) . '/includes/egpl-custome-functions.php';
        $sponsor_id = $sponsor_ids['customer_id'];
        $query = new WP_Query( array( 'post_type' => 'shop_order' ,'post_status'=>array('wc-pending-deposit','wc-scheduled-payment','wc-partial-payment','wc-failed','wc-refunded','wc-processing','wc-pending','wc-cancelled','wc-completed','wc-on-hold','wc-pending'),'posts_per_page' => -1) );
        $all_posts = $query->posts;
        $billing_details_array=[];
        $billing_details=[];
        foreach ($all_posts as $single_post) {

            $header_array = get_object_vars($single_post);
            $post_meta = get_post_meta($header_array['ID']);
            $order = wc_get_order( $header_array['ID'] );
            $user_id = get_post_meta( $header_array['ID'], '_customer_user', true );
            if($sponsor_id==$user_id)
            {
                $order_data = $order->get_data();
                $billing_details = $order_data['billing'];
                foreach ($billing_details as $key=>$value){ 
                    
                    $details=array(
                        'field'=> $key,
                        'value'=> $value,
                    );

                    array_push($billing_details_array, $details);
                }
                return  $billing_details_array;
            }
        }
        if (is_multisite()) {
            $blog_id = get_current_blog_id();
            $get_all_roles_array = 'wp_' . $blog_id . '_user_roles';
            $site_prefix = 'wp_' . $blog_id . '_';
        } else {
            $get_all_roles_array = 'wp_user_roles';
        }
       
        $all_meta_for_user = get_user_meta($sponsor_id);
           
        $GetAllcustomefields = new EGPLCustomeFunctions();
       
        $additional_fields = $GetAllcustomefields->getAllcustomefields(); 
        function sortByOrder($a, $b) {
            return $a['fieldIndex'] - $b['fieldIndex'];
        }
       
        usort($additional_fields, 'sortByOrder');
        foreach ($additional_fields as $key=>$value){ 
            if(($additional_fields[$key]['fieldType'] == 'text' || $additional_fields[$key]['fieldType'] == 'email' || $additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number')&&($additional_fields[$key]['BoothSettingsField'] != 'checked')){ 

                  $details=array(
                    'field'=> $additional_fields[$key]['fielduniquekey'],
                    'value'=> $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0],
                );
                array_push($billing_details, $details);
            }
          
        }
        return  $billing_details;


    }



    public function refundOrder($order_array)
    {
       
        $order_id = $order_array['ID'];
        $refund_amount = $order_array['amount'];
        $reason = $order_array['reason'];
        $restore = $order_array['check'];
        $order  = wc_get_order($order_id);
        $historyName='OrderHistory';
        $my_post = array(
            'post_title' => $historyName,
            'post_content' => '',
            'post_date' => $order_array['orderDate'],
            'post_status' => 'draft',
            'post_author' =>   $user_ID,
            'post_type'=>'EGPL_Order_History',
           
        );

        // Insert the post into the database.
            $id = wp_insert_post($my_post);
           
            
            $status_log='Order Refunded';       
            update_post_meta($id, 'status_log',  $status_log );
            update_post_meta($id, 'order_id', $order->id );
            update_post_meta($id, 'custome_meta',$order_array );
        if ('refunded' == $order->get_status()) {
            return 'Order has been already refunded';
        }
        // Get Items
        $order_items   = $order->get_items();
        if($restore==0)  
        {
              foreach ( $order->get_items() as $item_id => $item ) {
                  wc_add_order_item_meta($item_id, '_restock_checked', 'restock' );   
              }
        }
        $line_items = array();
        if ($order_items) {
        
            foreach ($order_items as $item_id => $item) {

                if ($restore == 0) {

                    $product_id = $item["product_id"];
                    $item_quantity = $order->get_item_meta($item_id, '_qty', true);
                    
                    $stock = get_post_meta($product_id, '_stock', true);
                    $total_sales=get_post_meta($product_id, 'total_sales', true);
                    $total_sales=$total_sales -$item_quantity;
                    update_post_meta($product_id,'total_sales',$total_sales);
                    $item_quantity_new = $item_quantity + $stock;
                    update_post_meta($product_id, '_stock', $item_quantity_new);

                    if ($item_quantity_new > 0) {
                        $out_of_stock_staus = 'instock';
                        update_post_meta($product_id, '_stock_status', wc_clean($out_of_stock_staus));
                    }
                }

                $tax_data = $item_meta['_line_tax_data'];
                $refund_tax = 0;
                if (is_array($tax_data[0])) {
                    $refund_tax = array_map('wc_format_decimal', $tax_data[0]);
                }
                $refund_amount = wc_format_decimal($refund_amount) + wc_format_decimal($item_meta['_line_total'][0]);

                $line_items[$item_id] = array(
                    'qty' => $item_meta['_qty'][0],
                    'refund_total' => wc_format_decimal($item_meta['_line_total'][0]),
                    'refund_tax' =>  $refund_tax
                );
            }

            $refund = wc_create_refund(array(
                'amount'         => $refund_amount,
                'reason'         => $reason,
                'order_id'       => $order_id,
                'line_items'     => $line_items,
                'refund_payment' => false,
            ));
           
            if ($refund) {
                return "success";
            }
            die();
        }
    }
    public function getOrderHistory($id)
    {
       
        $order_hisotry_id = $id['order_hisotry_id'];
        $history_log_id = $id['history_log_id'];
        $orderid = $id['order_id'];
        $history_log_id--;
        $History_log_array = array();
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'EGPL_Order_History',
            'post_status'      => 'draft',
            'ID'      => $order_hisotry_id,
        );

        $listOFOrderHistory = get_posts( $args );
        foreach ($listOFOrderHistory as $key => $value) {

            if($value->ID==$order_hisotry_id)
            {
              $custome_meta= get_post_meta($value->ID,"custome_meta",true);
            //   return  $custome_meta;
            array_push($History_log_array, $custome_meta);
              break;
            }

        }
        // echo $orderid;
        // echo $history_log_id;
        // echo '<br>';
        foreach ($listOFOrderHistory as $key => $value) {
            $history_id= get_post_meta($value->ID,"history_id",true);
            $order_id= get_post_meta($value->ID,"order_id",true);
            if( $order_id==$orderid && $history_log_id==$history_id )
            {
              $custome_meta= get_post_meta($value->ID,"custome_meta",true);
              array_push($History_log_array, $custome_meta);
              break;
            }

        }
        return $History_log_array; 


    }
}