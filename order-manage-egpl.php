<?php
if($_GET['orderManagerRequest'] == "createOrder") {  

     require_once('../../../wp-load.php');
     require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/Order-Management.php';  

     try {
          //code...
          $meta_array=$_POST;
          //$productArray =  json_decode(stripslashes($_POST['productArray']), true);
          $obj = new ordermanagment();
          $NewOrderStatus = $obj->createNewOrder($meta_array);
        
          die();

     } catch (Exception $e) {
          //throw $th;
          return $e;
     }   
}else if($_GET['orderManagerRequest'] == "getProducts") {  
     require_once('../../../wp-load.php');

     require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/Order-Product.php';  

     try {
          //code...
         
          $obj = new orderProduct();
          $product_array = $obj->getProduct();

          
          echo  json_encode($product_array);
          die();

     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
}else if($_GET['orderManagerRequest'] == "applyDiscount") {  
     
     require_once('../../../wp-load.php');
     //require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/Order-Product.php';  
     require_once('temp/lib/woocommerce-api.php');
       
     try {
         
          $dis_code=$_POST;
          global $woocommerce;
          $disc_coupon = new WC_Coupon($dis_code['code']);
          $coupon = array(  
               'amount' => $disc_coupon->amount,
               'code' => $disc_coupon->code,
               'discount_type' => $disc_coupon->discount_type,
               'product_ids' => $disc_coupon->product_ids,
               'excluded_product_ids' => $disc_coupon->excluded_product_ids,
               'product_categories' => $disc_coupon->product_categories,
               
          );
          echo json_encode($coupon);
          // echo ($disc_coupon->amount);

          die();

     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
} 
     
?>
