<?php
class orderComplete{

public function create_new_partial_order($orderid,$orderTime,$order_array){
    
    $order = wc_get_order($orderid);
    foreach( $order->get_items() as $item ) {
       
        if ( 'line_item' === $item['type'] && !empty( $item['is_deposit'] ) ) {
            $itemData= $item['product_id'];
            // echo '<pre>';
            // print_r($item);
            $deposit_full_amount       = (float) $item['_deposit_full_amount_ex_tax'];
            $deposit_deposit_amount    = (float) $item['_deposit_deposit_amount_ex_tax'];
            $deposit_deferred_discount = (float) $item['_deposit_deferred_discount'];
            if ( ( $deposit_full_amount - $deposit_deposit_amount ) > $deposit_deferred_discount ) {
                $productremaningProductsID[] = $item['product_id'];
                $wc_deposit_type = get_post_meta($itemData, '_wc_deposit_type', true);   
                $wc_amount = get_post_meta($itemData, '_price', true);
                if( $wc_deposit_type =='fixed')
                {
                    $remmaningamount =  $deposit_full_amount - $deposit_deposit_amount;
                }else{
                    $remmaningamount= ($deposit_full_amount/100)* $deposit_deposit_amount;
                    // $remmaningamount=$wc_amount-$remmaningamount;
                }       
            }
        }
    }
    $user_id = $order->get_user_id(); 
    //  echo "A".$remmaningamount;
    if($remmaningamount !=0){
        $user_ID = get_current_user_id();
        $historyName='OrderHistory';
        $my_post = array(
            'post_title' => $historyName,
            'post_content' => '',
            'post_date' => $orderTime,
            'post_status' => 'draft',
            'post_author' =>   $user_ID,
            'post_type'=>'EGPL_Order_History',
           
        );

        // Insert the post into the database.
            $tasksID = wp_insert_post($my_post);
            $original_order = wc_get_order( $orderid );
            $items     = false;
            $status = "";
                foreach ( $original_order->get_items() as $order_item_id => $order_item ) {
                            
                    $order_item_pro_id = wc_get_order_item_meta($order_item_id, '_product_id', true);                       
                    if (in_array($order_item_pro_id, $productremaningProductsID)) {            
                        $order_item_id_update = $order_item_id;
                        $items[] = $order_item;
                        $itemscheck = $order_item;
                    }                
                }
            $product_Array = array();
            $status_log='Order Created';  
            $order_array['payment_date']='';    
            $order_array['payment_method']='';    
            $order_array['Transaction_ID']='';  
            // echo '<pre>';
            // print_r($order_array["productArray"]);
            $productArray =  $order_array["productArray"];
            foreach ($productArray as $key => $values) { 

                $itemData = $values['id'];
                $quantity = $values['quantity'];
                $price = $values['price'];
                $Name = $values['Name'];
                // echo $itemData;
                $index =array_search($itemData,$productremaningProductsID);
                if ($index !== false) {
                    // echo 'False';
                    $coupon = array(
                        'id' =>  $itemData,
                        'Name' =>  $Name,
                        'quantity' =>  $quantity,
                        'price' => $price,
                   );
                 
                   array_push($product_Array, $coupon);  
                }
                // if(in_array($itemData, $items))
                // $item_ids_arrays[] = $itemData;
                
            }
            $order_array['productArray']=$product_Array;   
            $order_array['order_status']='wc-pending-deposit';   

            update_post_meta($tasksID, 'status_log',  $status_log );
            update_post_meta($tasksID, 'history_id', 1 );
            update_post_meta($tasksID, 'custome_meta',$order_array );
        
            $new_order      = wc_create_order( array(
            'status'        => $status,
            'customer_id'   => $user_id,
            'customer_note' => $original_order->customer_note,
            'created_via'   => 'wc_deposits',
            )); 
            if ( is_wp_error( $new_order ) ) {   
            
              $original_order->add_order_note( sprintf( __( 'Error: Unable to create follow up payment (%s)', 'woocommerce-deposits' ), $scheduled_order->get_error_message() ) );   
            } 
            else {
                    //echo 'checkoutstatus';
                    $new_order->set_address( array(
                    'first_name' => $original_order->billing_first_name,
                    'last_name'  => $original_order->billing_last_name,
                    'company'    => $original_order->billing_company,
                    'address_1'  => $original_order->billing_address_1,
                    'address_2'  => $original_order->billing_address_2,
                    'city'       => $original_order->billing_city,
                    'state'      => $original_order->billing_state,
                    'postcode'   => $original_order->billing_postcode,
                    'country'    => $original_order->billing_country,
                    'email'      => $original_order->billing_email,
                    'phone'      => $original_order->billing_phone,
                    ),
                     'billing' 
                    );
                    
                    $new_order->set_address( array(
                        'first_name' => $original_order->shipping_first_name,
                        'last_name'  => $original_order->shipping_last_name,
                        'company'    => $original_order->shipping_company,
                        'address_1'  => $original_order->shipping_address_1,
                        'address_2'  => $original_order->shipping_address_2,
                        'city'       => $original_order->shipping_city,
                        'state'      => $original_order->shipping_state,
                        'postcode'   => $original_order->shipping_postcode,
                        'country'    => $original_order->shipping_country,
                    ), 'shipping' );
                    $new_order->set_date_created( $orderTime );

                                                  // Handle items
        
                    foreach($items as $itemKey=>$itemData){
                            
                    
                                if ( ! $itemData || empty( $itemData['is_deposit'] ) ) {
                                    return;
                                }
                                // echo '<pre>';
                                // print_r($itemData);
                                $full_amount_excl_tax = floatval( $itemData['deposit_full_amount_ex_tax'] );

                                    // Next, get the initial deposit already paid, excluding tax
                                $amount_already_paid = floatval( $itemData['deposit_deposit_amount_ex_tax'] );
                                        // Then, set the item subtotal that will be used in create order to the full amount less the amount already paid
                                $subtotal = $full_amount_excl_tax - $amount_already_paid;
                                // echo 'Subtotal='. $subtotal;
                                // echo 'Amount_already_Paid='. $amount_already_paid;
                                // echo 'Amount_Full='. $full_amount_excl_tax;
                                
                                if( version_compare( WC_VERSION, '3.2', '>=' ) ){
                                    // Lastly, subtract the deferred discount from the subtotal to get the total to be used to create the order
                                   
                                    $discount_excl_tax = isset($items['deposit_deferred_discount_ex_tax']) ? floatval( $items['deposit_deferred_discount_ex_tax'] ) : 0;
                                    $total = $subtotal - $discount_excl_tax;
                                } else {
                                  
                                    $discount = floatval( $items['deposit_deferred_discount'] );
                                    $total = empty( $discount ) ? $subtotal : $subtotal - $discount;
                                }
                            
                                // echo 'Amount_already_Paid='. $amount_already_paid;
                                // echo 'Amount_Full='. $full_amount_excl_tax;
                                //   echo 'Subtotal='. $subtotal;
                                //   echo 'Total='. $total;
                                
                            
                                    $item = array(
                                    'product'   => $original_order->get_product_from_item( $itemData ),
                                    'qty'       => 1,
                                    'subtotal'  => $subtotal,
                                    'total'     => $total
                                    );
                            
                                $item_id = $new_order->add_product( $item['product'], $item['qty'], array(
                                'totals' => array(
                                'subtotal'     => $item['subtotal'], // cost before discount (for line quantity, not just unit)
                                'total'        => $item['total'], // item cost (after discount) (for line quantity, not just unit)
                                'subtotal_tax' => 0, // calculated within (WC_Abstract_Order) $new_order->calculate_totals
                                'tax'          => 0, // calculated within (WC_Abstract_Order) $new_order->calculate_totals
                                )
                                        ) );
                            
                                wc_add_order_item_meta( $item_id, '_original_order_id', $orderid );

                            /* translators: Payment number for product's title */
                                wc_update_order_item( $item_id, array( 'order_item_name' => sprintf( __( 'Payment #%d for %s', 'woocommerce-deposits' ), 2, $item['product']->get_title() ) ) );
                                
                        
                    }
                    
                                
                    // (WC_Abstract_Order) Calculate totals by looking at the contents of the order. Stores the totals and returns the orders final total.
                    $new_order->calculate_totals( wc_tax_enabled() );

                    // Set future date and parent
                    $new_order_post = array(
                        'ID'          => $new_order->id,
                        'post_date'   => date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ),
                        'post_parent' => $orderid,
                    );
                    wp_update_post( $new_order_post );                                   
                    do_action( 'woocommerce_deposits_create_order', $new_order->id );
                    $new_order->update_status('wc-pending-deposit');

                    foreach ( $new_order->get_items() as $order_item_id => $order_item ) {
                     $order_item_pro_id = wc_get_order_item_meta($order_item_id, '_product_id', true);
                        if (in_array($order_item_pro_id, $productremaningProductsID)) {
                                $order_item_id_update = $order_item_id;                                 
                                wc_add_order_item_meta( $order_item_id_update, '_remaining_balance_order_id', $orderid );                                   
                        }
                    }
                    $new_order_ID =  $new_order->id;
                   
                   
                }
                update_post_meta($tasksID, 'order_id',   $new_order_ID);
        
    }
    // die();
}

public function updateuser_role_on_purches($order,$porduct_ids_array,$customer_id,$update_check){
        
      
    // echo '<pre>';
    // print_r($porduct_ids_array);exit;

    $orders = wc_get_order($order);
    if(is_array($order)){   
        $order_ID = $order->id;       
    }else{       
        $order_ID = $order;
    }   
    if(empty($customer_id)){
  
        $customer_id = get_post_meta($order_ID,'_customer_user',true);     
        if(empty($customer_id)){
            
           $customer_id = $_SESSION['userID'];
           $useremail = $_SESSION['useremail'];
           update_post_meta($order_ID,'_customer_user',$customer_id);
           
        }
    }
     $lastInsertId = contentmanagerlogging('New Order Placed',"User Action",serialize($order_ID),$customer_id,'',"pre_action_data");

   // $lastInsertId = contentmanagerlogging('Purches MPOs',"User Action",serialize($order),''.$customer_id->id,$customer_id->user_email,"pre_action_data");
    require_once( 'temp/lib/woocommerce-api.php' );
    
    $args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => 'egpl_custome_tasks',
        'post_status'      => 'draft',

    );
    $taskkeyContent = get_posts( $args );
    
    
    
    $url = get_site_url();//'https://'.$_SERVER['SERVER_NAME'];
    $options = array(
        'debug' => true,
        'return_as_array' => false,
        'validate_url' => false,
        'timeout' => 30,
        'ssl_verify' => false,
    );
    
    $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
    $boothpurchaseenablestatus = $woocommerce_rest_api_keys['ContentManager']['boothpurchasestatus'];
    $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
    $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
    $woocommerce = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );

                if (count($porduct_ids_array) > 0) {
                    $getpackagelevel = [];
                    $order_data = $orders->get_data();
                    $order_status = $order_data['status'];
                    foreach ($porduct_ids_array as $item=>$ids) {
                       
                        $productID =  $ids;
                        $out_of_stock_staus = 'outofstock';
                        if($update_check==true)
                        {
                           
                            $product_status = get_post_meta( $productID, '_stock_status',true );
                            $stock = get_post_meta( $productID, '_stock',true );
                            $item_quantity = 0;
                            foreach( $orders->get_items() as $item ) {    
                                if ($item['product_id']==$productID) {
                                    $item_quantity=$item['quantity'];
                                }			
                            }

                            $total_sales=get_post_meta($productID, 'total_sales', true);
                            //  echo 'SalesBefore='. $total_sales;
                            // echo 'StockBefore='. $stock;
                            $total_sales=$total_sales +$item_quantity;
                            $new_stock= $stock-$item_quantity;
                            if( $order_status=='partial-payment' ||  $order_status=='pending-deposit')
                            {
                                update_post_meta($productID,'total_sales',$total_sales);
                                update_post_meta($productID, '_stock', $new_stock);

                            }
                            if($new_stock==0)
                            {
                                update_post_meta( $productID, '_stock_status', wc_clean( $out_of_stock_staus ) );
    
                            }
                        }

                        $getproduct_detail = $woocommerce->products->get( $ids );
                        if($getproduct_detail->product->categories[0] != 'Package' && $getproduct_detail->product->categories[0] != 'Add-ons'){

                            $id = wp_insert_post(array('post_title'=>'Booth Purchase Review_'.$order_ID, 'post_type'=>'booth_review', 'post_content'=>''));
                            update_post_meta( $id, 'porductID', $ids );
                            update_post_meta( $id, 'orderID', $order_ID );
                            update_post_meta( $id, 'OrderUserID', $customer_id);
                            // $status='outofstock';
                            // update_post_meta( $productID, 'wc_stock_status', $status);

                            if(!empty($boothpurchaseenablestatus) && $boothpurchaseenablestatus == "enabled"){

                                $OrderUserID = $customer_id;
                                $foolrplanID = $woocommerce_rest_api_keys['ContentManager']['floorplanactiveid'];
                                $boothTypesLegend = json_decode(get_post_meta($foolrplanID, 'legendlabels', true )); 
                                $FloorplanXml = get_post_meta( $foolrplanID, 'floorplan_xml', true );    
                                $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
                                $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
                                $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
                                $currentIndex = 0;

                                foreach ($xml->root->MyNode as $cellIndex=>$CellValue){
                                    
                                    $cellboothlabelvalue = $CellValue->attributes();
                                    $getCellStylevalue = $xml->root->MyNode[$currentIndex]->mxCell->attributes();

                                     if($cellboothlabelvalue['boothproductid'] == $productID){

                                        $att = "boothOwner";
                                        $styleatt = 'style';
                                        $xml->root->MyNode[$currentIndex]->attributes()->$att = $OrderUserID;
                                        $loggin_data['boothnumberindex'][] = ''.$cellboothlabelvalue['mylabel'];
                                        $loggin_data['ownerID'][] = $OrderUserID;
                                        $getCellStyle = $getCellStylevalue['style'];
                                        $getCellStyle = str_replace($oldfillcolortext,'fillColor='.$NewfillColor,$getCellStyle);
                                        $xml->root->MyNode[$currentIndex]->mxCell->attributes()->$styleatt = $getCellStyle;
                                       
                                        if(isset($cellboothlabelvalue['legendlabels']) && !empty($cellboothlabelvalue['legendlabels'])){

                                            $orderlogginsData['legendlabels'][]='enabled';
                                            $getlabelID = ''.$cellboothlabelvalue['legendlabels'];
                                            foreach ($boothTypesLegend as $boothlabelIndex=>$boothlabelValue){
                                                if($boothlabelValue->ID ==  $getlabelID){

                                                    $createdproductPrice = $boothlabelValue->colorcodeOcc;
                                                    if($createdproductPrice != "none"){

                                                        $NewfillColor = $createdproductPrice;
                                                        $getCellStyleArray = explode(';',$getCellStyle);
                                                            foreach ($getCellStyleArray as $styleIndex=>$styleValue){
                                                                if($styleValue != 'DefaultStyle1'){
                                                                    $styledeepCheck = explode('=',$styleValue);
                                                                    if($styledeepCheck[0] == 'fillColor'){
                                                                        $oldfillcolortext = $styleValue;
                                                                    }
                                                                }
                                                            }
                                                       }
                                                        else{
                                                            $getCellStyleArray = explode(';',$getCellStyle);
                                                            foreach ($getCellStyleArray as $styleIndex=>$styleValue){


                                                                if($styleValue != 'DefaultStyle1'){

                                                                    $styledeepCheck = explode('=',$styleValue);

                                                                    if($styledeepCheck[0] == 'occ'){

                                                                        $NewfillColor = $styledeepCheck[1];

                                                                    }else if($styledeepCheck[0] == 'fillColor'){

                                                                        $oldfillcolortext = $styleValue;
                                                                    }


                                                                }


                                                            }

                                                        }
                                                }
                                               
                                                }
                                
                                        }
                                        else{
                                    
                                                $orderlogginsData['legendlabels'][]='disabled';
                                                $getCellStyleArray = explode(';',$getCellStyle);
                                                foreach ($getCellStyleArray as $styleIndex=>$styleValue){
                                                    if($styleValue != 'DefaultStyle1'){
                                                        $styledeepCheck = explode('=',$styleValue);
                                                        if($styledeepCheck[0] == 'occ'){
                                                            $NewfillColor = $styledeepCheck[1];
                                                        }else if($styledeepCheck[0] == 'fillColor'){
                                                            $oldfillcolortext = $styleValue;
                                                        }
                                                    }
                                                }
                                        }

                                            $orderlogginsData['assigendcolor'][]=$NewfillColor;
                                            $orderlogginsData['assigendoldcolor'][]=$oldfillcolortext;
                                            $getCellStyle = str_replace($oldfillcolortext,'fillColor='.$NewfillColor,$getCellStyle);
                                            $xml->root->MyNode[$currentIndex]->mxCell->attributes()->$styleatt = $getCellStyle;

                                    }
                                    $currentIndex++;

                                }
                                    
                                    $getresultforupdat = str_replace('<?xml version="1.0"?>',"",$xml->asXML());
                                    update_post_meta( $foolrplanID, 'floorplan_xml', json_encode($getresultforupdat));
                                    update_post_meta( $id, 'boothStatus', 'Completed' );
                                    $loggin_data['boothstatus'][] = 'Completed';


                            }else{

                            update_post_meta( $id, 'boothStatus', 'Pending' );
                            $loggin_data['boothstatus'][] = 'Pending';
                            }
                            }

                                $get_productlevel = get_post_meta( $productID, 'productlevel', true );
                                $term_obj_list = get_the_terms( $productID, 'product_cat' );
                                //   echo '--';
                                //  echo $get_productlevel;
                                 // print_r( $term_obj_list);
                                if(!empty($get_productlevel)){

                                    $seletedroleValue = $get_productlevel;
                                if(!empty($seletedroleValue)){
                                    $assign_role[] = $seletedroleValue;
                                }
                                }

                                $selectedTaskListData = get_post_meta( $ids);
                                $selectedTaskList = unserialize($selectedTaskListData['seletedtaskKeys'][0]);

                                if(!empty($selectedTaskList['selectedtasks'])){
                                      $latestProductsValue = $selectedTaskList;
                                }
                                if ($term_obj_list[0]->slug == 'packages') {
                                    if(!empty($get_productlevel)){
                                    $getpackagelevel[] = $get_productlevel;
                                     }
                                }else if($term_obj_list[0]->slug == 'add-ons'){
                                    if(!empty($get_productlevel)){
                                        $getpackagelevel[] = $get_productlevel;
                                         }
                                }
                    }


                }               
                                //  echo 'dsds';
                                //  print_r($getpackagelevel);
                                //  echo 'dsdsssss';
                                //  print_r($assign_role);   

                                $user_info = get_userdata($customer_id);
                                $user_info_to_update = get_userdata($customer_id);
                               

                                if(!empty($getpackagelevel)){

                                            $counter=0;
                                            foreach($getpackagelevel as $key1=>$roleName){
                                                $productroleOrder = getroleorder($roleName);
                                                // echo "products";
                                                // echo $roleName;
                                                // echo $counter;
                                                if ($user_info->roles[0] != 'administrator' && $user_info->roles[0] != 'contentmanager') {

                                                $u = new WP_User($customer_id);
                                                $currentroleName = $u->get_role();

                                                if($key1 == 0){

                                                $currentroleOrder = getroleorder($user_info->roles[0]);

                                                }else{

                                                $currentroleOrder = getroleorder($getpackagelevel[$key1-1]);
                                                }


                                                if($productroleOrder < $currentroleOrder) {
                                                    $u->set_role($roleName);
                                                    $responce['assignrole'] = $roleName;
                                                    $loggin_data['rolename'][] = $roleName;

                                                    } else{

                                                    $responce['assignrole'] = $currentroleName['name'];
                                                    $loggin_data['rolename'][] = $currentroleName['name'];
                                                    }
                                                    }else{
                                                    // echo "UserRole Remain Same";
                                                    }
                                                    $counter++;
                                            }


                                }else{
                                     $counter=0;
                                        foreach($assign_role as $key=>$roleName){
                                            //  echo "assign";
                                            // echo $roleName;
                                            //     echo $counter;
                                            $productroleOrder = getroleorder($roleName);
                                            $get_Ovveride_check = get_post_meta( $porduct_ids_array[$counter],"overrideCheck",true );
                                            if ($user_info->roles[0] != 'administrator' && $user_info->roles[0] != 'contentmanager' && $get_Ovveride_check=='0')
                                            {

                                                        $u = new WP_User($customer_id);
                                                        $currentroleName = $u->get_role();

                                                        if($key == 0){
                                                        $currentroleOrder = getroleorder($user_info->roles[0]);
                                                        }else{
                                                        $currentroleOrder = getroleorder($assign_role[$key-1]);
                                                        }

                                                        if ($productroleOrder < $currentroleOrder) {
                                                            
                                                            $u->set_role($roleName);
                                                            $responce['assignrole'] = $roleName;
                                                            $loggin_data['rolename'][] = $roleName;

                                                            } else {

                                                            $responce['assignrole'] = $currentroleName['name'];
                                                            $loggin_data['rolename'][] = $currentroleName['name'];
                                                            }
                                            } else{
                                                //  echo "UserRole Remain Same";
                                            }
                                                $counter++;
                                        }

                                }
                                //  echo "End";

          
}

}?>