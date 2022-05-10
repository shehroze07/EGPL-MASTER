<?php


if ($_GET['orderManagerRequest'] == "createOrder") {

     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Management.php';

     try {
          //code...
          $meta_array = $_POST;
          //$productArray =  json_decode(stripslashes($_POST['productArray']), true);
          $obj = new ordermanagment();
          $NewOrderStatus = $obj->createNewOrder($meta_array);

          echo $NewOrderStatus;

          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
} else if ($_GET['orderManagerRequest'] == "updateOrder") {

     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Management.php';
     require_once('temp/lib/woocommerce-api.php');

     try {
          $meta_array = $_POST;
          $obj = new ordermanagment();
          $NewOrderStatus = $obj->updateOrder($meta_array);
          echo $NewOrderStatus;


          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
} else if ($_GET['orderManagerRequest'] == "getProducts") {
     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Product.php';
     require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/floorplan-manager.php';
     try {
          //code...

          // $obj = new orderProduct();
          // $product_array = $obj->getProduct();
          require_once('../../../wp-load.php');
          require_once('temp/lib/woocommerce-api.php');


          $product_Array = array();
          $url = get_site_url();
          $options = array(
               'debug' => true,
               'return_as_array' => false,
               'validate_url' => false,
               'timeout' => 30,
               'ssl_verify' => false,
          );
          $woocommerce_rest_api_keys = get_option('ContenteManager_Settings');
          $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
          $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
          $woocommerce = new WC_API_Client($url, $wooconsumerkey, $wooseceretkey, $options);
          $demo = new FloorPlanManager();
          $products = wc_get_products(array('status' => 'publish', 'limit' => -1));
          foreach ($products as $key => $value) {
               // echo "<pre>";
               // print_r($value);
               $wc_deposit_enabled = get_post_meta($value->id, '_wc_deposit_enabled', true);
               $wc_deposit_amount = get_post_meta($value->id, '_wc_deposit_amount', true);
               $wc_deposit_type = get_post_meta($value->id, '_wc_deposit_type', true);
               $product_status = get_post_meta($value->id, '_stock_status', true);
               $stock = get_post_meta($value->id, '_stock', true);
               $listofboothsID = get_post_meta( $value->id, '_list_of_selected_booth', true);
               $getproduct_detail = $woocommerce->products->get($value->id);
               // $getproduct_sales = $woocommerce->total->get($value->id);
               $get_BoothLevel_amount = get_post_meta($value->id, "LevelOfBooth",true);
               $get_Booth_Owner = get_post_meta($value->id, "BoothForUser",true);
               if(!empty($listofboothsID))
               {

                    foreach($listofboothsID as $boothKey=>$boothID){
                                   
                         $getthisboothproductID[] = $demo->getproductID($boothID);
                    }
               }
               $coupon = array(
                    'id' => $value->id,
                    'title' => $value->name,
                    'price' => $value->price,
                    'status' => $product_status,
                    'stock' =>  $stock,
                    'type' =>  $wc_deposit_type,
                    'boothList'=> $getthisboothproductID ,
                    'boothLevel'=> $get_BoothLevel_amount,
                    'boothOwner'=> $get_Booth_Owner,
                    'deposit' => $wc_deposit_enabled,
                    'deposit_amount' => $wc_deposit_amount,
                    'catagory' => $getproduct_detail->product->categories[0]
               );
               $getthisboothproductID=[];
               array_push($product_Array, $coupon);
          }
          //return $product_Array ;

          echo  json_encode($product_Array);
          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
} else if ($_GET['orderManagerRequest'] == "applyDiscount") {

     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Product.php';
     require_once('temp/lib/woocommerce-api.php');

     try {

          $dis_code = $_POST;
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
} else if ($_GET['orderManagerRequest'] == "deleteOrder") {

     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Management.php';

     try {
          $meta_array = $_POST;
          $obj = new ordermanagment();
          $product_array = $obj->deleteOrder($meta_array);


          // wp_delete_post($order_id,true);

          echo "success";

          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
} else if ($_GET['orderManagerRequest'] == "getbilingDetials") {

     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Management.php';

     try {
          $meta_array = $_POST;
          $obj = new ordermanagment();
          $product_array = $obj->getDetails($meta_array);
          echo json_encode($product_array);
          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
} else if ($_GET['orderManagerRequest'] == "refundOrder") {

     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Management.php';

     try {
          $meta_array = $_POST;
          $obj = new ordermanagment();
          $product_array = $obj->refundOrder($meta_array);
          echo $product_array;
          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
}