<?php
class ordermanagment{

    public function createNewOrder($order_array){
        // print_r($order_array['First_name'])
        // echo "<pre>";
        // print_r($order_array);
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
            'country'    => $order_array['country']
        );
        
        $args = array(
            'customer_id'   => $order_array['customer_id'],
            'status'        => $order_array['order_status'],
        );
        
        $order = wc_create_order($args);
        $productArray =  json_decode(stripslashes($order_array["productArray"]), true);
        // This is an existing SIMPLE product
        foreach ($productArray as $key => $value) {
            
            $itemData=$value['id'];

            $wc_deposit_enabled = get_post_meta( $itemData, '_wc_deposit_enabled', true );
            $wc_deposit_amount = get_post_meta( $itemData, '_wc_deposit_amount', true );
            $wc_amount = get_post_meta( $itemData,'_price',true );
            $deferred_discount_amount=0;
        
            
            if ($wc_deposit_enabled!="") {
                    $item_id = $order->add_product( get_product($itemData), $value['quantity'], array(
                    'totals' => array(
                    'subtotal'     => $wc_deposit_amount, // cost before discount (for line quantity, not just unit)
                    )));
                    $item = $order->get_item( $item_id, false );
                    echo $order->id;
                    $item->set_subtotal( $wc_deposit_amount ); 
			        $item->set_total( $wc_deposit_amount );
                    $item->add_meta_data( '_is_deposit', 'yes' );
                    $item->add_meta_data( '_deposit_full_amount', $wc_amount );
                    $item->add_meta_data( '_deposit_full_amount_ex_tax', $wc_amount );
                    $item->add_meta_data( '_deposit_deposit_amount_ex_tax', $wc_deposit_amount );
                    $item->save();
               
            }else{
                $order->add_product( get_product($itemData), $value['quantity']);

            }
            
            
        } 
        // echo "<pre>";
        // print_r($order);
        // exit;
        $order->apply_coupon($order_array['coupon_code_cart']);
        $order->apply_coupon($order_array['coupon_code_prdt']);
        // $order->set_address( $address, 'billing' );
         if(!empty($order_array['order_status']))
         {
             $order->update_status($order_array['order_status']);  

         }
         $order->set_address( $address, 'billing' );
        //  if(!empty($order_array['coupon_code']))
        //  {
        //      $order->update_status($order_array['coupon_code']) ;

        //  }
         
        
         $order->calculate_totals();
         $order->save();
         $order = wc_get_order($order->id);
            foreach( $order->get_items() as $item ) {

                if ( 'line_item' === $item['type'] && ! empty( $item['is_deposit'] ) ) {
                    $deposit_full_amount       = (float) $item['_deposit_full_amount_ex_tax'];
                    $deposit_deposit_amount    = (float) $item['_deposit_deposit_amount_ex_tax'];
                    $deposit_deferred_discount = (float) $item['_deposit_deferred_discount'];
                    if ( ( $deposit_full_amount - $deposit_deposit_amount ) > $deposit_deferred_discount ) {
                                                $productremaningProductsID[] = $item['product_id'];
                        $remmaningamount =  $deposit_full_amount - $deposit_deposit_amount;
                    }
                }
            }
            echo $remmaningamount;
            if($remmaningamount !=0){
            
                $original_order = wc_get_order( $order->id );
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
                    $new_order      = wc_create_order( array(
                    'status'        => $status,
                    'customer_id'   => $postid,
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
    
                                                          // Handle items
                
                            foreach($items as $itemKey=>$itemData){
                                    
                                        if ( ! $itemData || empty( $itemData['is_deposit'] ) ) {
                                            return;
                                        }
                                        $full_amount_excl_tax = floatval( $itemData['deposit_full_amount_ex_tax'] );
    
                                            // Next, get the initial deposit already paid, excluding tax
                                        $amount_already_paid = floatval( $itemData['deposit_deposit_amount_ex_tax'] );
                                                // Then, set the item subtotal that will be used in create order to the full amount less the amount already paid
                                        $subtotal = $full_amount_excl_tax - $amount_already_paid;
                                        
                                        if( version_compare( WC_VERSION, '3.2', '>=' ) ){
                                            // Lastly, subtract the deferred discount from the subtotal to get the total to be used to create the order
                                            $discount_excl_tax = isset($items['deposit_deferred_discount_ex_tax']) ? floatval( $items['deposit_deferred_discount_ex_tax'] ) : 0;
                                            $total = $subtotal - $discount_excl_tax;
                                        } else {
                                            $discount = floatval( $items['deposit_deferred_discount'] );
                                            $total = empty( $discount ) ? $subtotal : $subtotal - $discount;
                                        }
                                    
                                    
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
                                    
                                        wc_add_order_item_meta( $item_id, '_original_order_id', $order->id );
    
                                    /* translators: Payment number for product's title */
                                        wc_update_order_item( $item_id, array( 'order_item_name' => sprintf( __( 'Payment #%d for %s', 'woocommerce-deposits' ), 2, $item['product']->get_title() ) ) );
                                        
                                
                            }
                            
                                        
                            // (WC_Abstract_Order) Calculate totals by looking at the contents of the order. Stores the totals and returns the orders final total.
                            $new_order->calculate_totals( wc_tax_enabled() );
    
                            // Set future date and parent
                            $new_order_post = array(
                                'ID'          => $new_order->id,
                                'post_date'   => date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ),
                                'post_parent' => $order->id,
                            );
                            wp_update_post( $new_order_post );                                   
                            do_action( 'woocommerce_deposits_create_order', $new_order->id );
                            $new_order->update_status('wc-pending-deposit');
    
                            foreach ( $new_order->get_items() as $order_item_id => $order_item ) {
                             $order_item_pro_id = wc_get_order_item_meta($order_item_id, '_product_id', true);
                                if (in_array($order_item_pro_id, $productremaningProductsID)) {
                                        $order_item_id_update = $order_item_id;                                 
                                        wc_add_order_item_meta( $order_item_id_update, '_remaining_balance_order_id', $order->id );                                   
                                }
                            }
                            $new_order_ID =  $new_order->id;
                        }
                
            }

    }
  
    public function editOrder(){

       
    }
    public function deleteOrder(){

    }
}
?>