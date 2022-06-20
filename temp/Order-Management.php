<?php
require_once 'includes/Order-Complete.php';

class ordermanagment
{

    public function createNewOrder($order_array)
    {
        try{
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
            'status'        => $order_array['order_status'],
        );

        $order = wc_create_order($args);
      
      
        $productArray =  json_decode(stripslashes($order_array["productArray"]), true);
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
            $wc_amount = get_post_meta($itemData, '_price', true);
            $deferred_discount_amount = 0;

            if ($value['partial_check'] > 0) {
                $item_id = $order->add_product(get_product($itemData), $value['quantity'], array(
                    'totals' => array(
                        'subtotal'     => $wc_deposit_amount, // cost before discount (for line quantity, not just unit)
                    )
                ));
                $item = $order->get_item($item_id, false);
                $grand_total = $value['quantity'] * $wc_deposit_amount * $checks;
                $grand_total_price = $value['quantity'] * $wc_amount * $checks;
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
            $NewOrderStatusPartial = $objA->create_new_partial_order($order->id);

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
            // $mailer = WC()->mailer();
            // $mails = $mailer->get_emails();

            // if (!empty($mails)) {
            //     foreach ($mails as $mail) {
            //         if ($mail->id == 'customer_completed_order') {
            //             echo '--';
            //             $mail->trigger($order_id,$order);
            //             //break;
            //         }
            //     }
            // }
            $note = wc_get_order_notes($order->id);
            $xs =  get_comments($order->id);
            $user_ID = get_current_user_id();
            $firstName=get_user_meta($user_ID, "first_name", true);
            $lastName= get_user_meta($user_ID, "last_name", true);
            $fullname= $firstName .$lastName;
            $date='';
            // echo 's';
            $commentarr = array();
            foreach ($xs as $key => $notes) {
                $comment_post_ID = $notes->comment_post_ID;
                if ($order->id == $comment_post_ID) {
                    // echo '<pre>';
                    // print_r($notes);
                    $id=$notes->comment_ID;
                    $admin=$fullname;
                    $date=$notes->comment_date;
                    $commentarr['comment_ID'] = $id;
                    $commentarr['comment_author'] = $admin;
                    $commentarr['comment_date'] = 0;
                    $update_success = wp_update_comment($commentarr);
                }
    
            }
            $dateString= explode(" ",$date );
            //echo $dateString[0];
           // $dates=explode("-",$date );
            // echo '<pre>';
            // print_r($dates);
            $newdate_created= date("F j, Y", strtotime($date));
             $time= date("g:i A", strtotime($dateString[1]));
            // echo $newDateTime;
            // exit;
            
            // date("F j, Y, g:i a");
            $data = array(
                'comment_post_ID' => $order->id,
                'comment_author' => $fullname,
                'comment_author_email' => 'dave@domain.com',
                'comment_author_url' => 'http://www.someiste.com',
                'comment_content' => 'Order created '. $newdate_created. ' at '.  $time .' by '.$fullname,
                'comment_author_IP' => '127.3.1.1',
                'comment_agent' => 'WooCommerce',
                'comment_date' => date('Y-m-d H:i:s'),
                'comment_date_gmt' => date('Y-m-d H:i:s'),
                'comment_approved' => 1,
            );
            
            $comment_id = wp_insert_comment($data);

        if ($order) {
            // return "end";
            return "success";
            die();
        }
    }catch (Exception $e) {
       
        //  contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
        //$NewOrderStatusPartial = $objA->new_order_on_hold_notification($order->id);

    }

    public function updateOrder($order_array)
    {
        $customer_id = $order_array['customer_id'];
        $orderId = $order_array['order_id'];
        $order = wc_get_order($orderId);
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
        $productArray =  json_decode(stripslashes($order_array["productArray"]), true);
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
        $order->apply_coupon($order_array['coupon_code_cart']);
        $order->apply_coupon($order_array['coupon_code_prdt']);
        $order->set_address($address, 'billing');
        $order->calculate_totals();
        update_post_meta($orderId,'_transaction_id',$order_array['Transaction_ID']);
        $order->save();
        // echo $order_status;
        
        
        if($order_status=='cancelled' && $order_array['order_status'] != 'wc-cancelled' && $order_array['order_status'] != 'wc-refunded' && $order_array['order_status'] != 'wc-partial-payment' )
        {
            if($order_array['order_status']=='wc-completed')
            {
                $order->update_status($order_array['order_status']);
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
            return "success";
        }
        if (!empty($order_array['order_status'])) {
              $order->update_status($order_array['order_status']);
            //   echo $order_array['order_status'];
            //  $A= update_post_meta($orderId,'_status','wc-pending-deposit');
            //  echo '<pre>';
            //  print_r($A);
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
                        $restored_stock = $stock_quantity + $item_quantity;
                        $total_sales=$total_sales-$item_quantity;
                        update_post_meta($product_id,'total_sales',$total_sales);
                        update_post_meta($product_id, '_stock', $restored_stock);
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
                return "success";
            }else if($order_array['order_status']=='wc-refunded'){
                 return "success";
            }else if($order_array['order_status']=='wc-cancelled'){
                // foreach ($order->get_items() as $item) {

                //     $product_id = $item["product_id"];
                //     $item_quantity = $item['quantity'];
                //     $out_of_stock_staus = 'instock';
                //     $stock_quantity = get_post_meta($product_id, '_stock', true);
                //     // echo 'StockBefore='. $stock_quantity;
                //         $stock_quantity=$stock_quantity-$item_quantity;
                //         update_post_meta($product_id, '_stock', $stock_quantity);
                // }
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
        foreach ( $order->get_items() as $item_id => $item ) {
            $custom_field = wc_get_order_item_meta( $item_id, '_remaining_balance_order_id', true );   
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
            } elseif($order_array['order_status']=='wc-completed' && $order_array['orderStatus']=='wc-partial-payment')
            {
                $stock=$stock+ $quantity;
                $total_sales=$total_sales-$quantity;
            }else if($order_array['order_status']=='wc-completed' && $order_array['orderStatus']=='wc-pending-deposit'  && !empty($custom_field))
            {
                // echo "B";
                //$stock=$stock+ $quantity;
                $total_sales=$total_sales-$quantity;
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
                    $wc_amount = get_post_meta($itemData, '_price', true);
                    $deferred_discount_amount = 0;
                    $order_item_id = $product_value['id'];
                    $order_item_quantity = $product_value['quantity'];
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
                } else {

                   // echo '-----DAD------';
                    $product = wc_get_product($itemData);
                    $price = (int) $quantity * $price;
                    $order_item_id = $product_value['id'];
                    $order_item_quantity = $product_value['quantity'];
                    $item_quantity = $order_item_quantity - $quantity;
                    $qty = (int) $quantity;
                    $item = $order->get_item($order_item_id, false);
                    $item->set_quantity($qty);
                    $item->set_subtotal($price);
                    $item->set_total($price);
                }

                //   echo '-----A------'. $order_item_quantity;
                //   echo '-------B----'. $item_quantity;
                //   echo '-----C------'.  $total_sales;
                //   echo '-----D------'.  $stock;
                
                if ($item_quantity < 0) {
                    //  echo '----3-------';
                    $item_quantity = abs($item_quantity);
                    $new_stock = $stock - $item_quantity;
                    $total_sales=$total_sales +$item_quantity;
                } else {
                   //  echo '---4--------'. $item_quantity;
                    $new_stock = $stock + $item_quantity;
                    $total_sales=$total_sales -$item_quantity;
                   
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
                    $wc_amount = get_post_meta($itemData, '_price', true);
                    $deferred_discount_amount = 0;
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
                    $order->add_product(get_product($itemData), $quantity);
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
        if (isset($_GET['orderid'])) {
            $ID =  $_GET['orderid'];
        }else{
            $ID =   $orderId;

        }
        
        $note = wc_get_order_notes(intval($ID));
        $xs =  get_comments($orderId);
        $objA = new orderComplete();
        if($order_array['order_status'] == 'wc-partial-payment')
        { 
        
            $NewOrderStatusPartial = $objA->create_new_partial_order($orderId);
        } 
        foreach ($order->get_items() as $item) {
            $porduct_ids_array[] = $item['product_id'];
        }
        $customer_id = $order_array['customer_id'];

        $NewOrderStatusPartial = $objA->updateuser_role_on_purches($orderId, $porduct_ids_array, $customer_id, false);

      
        $user_ID = get_current_user_id();
        $firstName=get_user_meta($user_ID, "first_name", true);
        $lastName= get_user_meta($user_ID, "last_name", true);
        $fullname= $firstName .$lastName;
        $date='';
        // echo 's';
        $commentarr = array();
        foreach ($xs as $key => $notes) {
            // echo $notes->comment_post_ID;
            $comment_post_ID = $notes->comment_post_ID;
            if ($orderId == $comment_post_ID) {
                // echo '<pre>';
                // print_r($notes);
                $id=$notes->comment_ID;
                $admin=$fullname;
                $date=$notes->comment_date;
                $commentarr['comment_ID'] = $id;
                $commentarr['comment_author'] = $admin;
                $update_success = wp_update_comment($commentarr);
                break;
            }

        }
        return "success";
        die();
    }



    public function deleteOrder($order_array)
    {
        $order_id = $order_array['order_id'];
        $stock_restore = $order_array['stock'];
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();

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
                    'field'=> $additional_fields[$key]['fieldName'],
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

        if ('refunded' == $order->get_status()) {
            return 'Order has been already refunded';
        }
        // Get Items
        $order_items   = $order->get_items();

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
}