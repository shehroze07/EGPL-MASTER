<?php
class orderProduct{

    public function getProduct(){

        require_once('../../../wp-load.php');
        require_once('temp/lib/woocommerce-api.php');


        $product_Array=array();
        $url = get_site_url();
        $options = array(
            'debug' => true,
            'return_as_array' => false,
            'validate_url' => false,
            'timeout' => 30,
            'ssl_verify' => false,
        );
        $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
        $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
        $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
        $woocommerce = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );

        $products = wc_get_products( array( 'status' => 'publish', 'limit' => -1 ) );
        foreach ($products as $key => $value) {
               
            $wc_deposit_enabled = get_post_meta( $value->id, '_wc_deposit_enabled', true );
            $wc_deposit_amount = get_post_meta( $value->id, '_wc_deposit_amount', true );
            $getproduct_detail = $woocommerce->products->get( $value->id );
     
            $coupon = array(  
                 'id'=> $value->id,
                 'title' => $value->name,
                 'price' => $value->price,
                 'status' => $value->stock_status,
                 'deposit' => $wc_deposit_enabled,
                 'deposit_amount' => $wc_deposit_amount,
                 'catagory'=>$getproduct_detail->product->categories[0]
            );
           array_push($product_Array, $coupon );
       } 
        return $product_Array ;
    }
   
}
?>