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

          
          $request_body = file_get_contents('php://input');
          $data = json_decode($request_body);
          // print_r($data);
          $search= $data->term;
          $product_categories= $data->cat;
          $argsb = array(
               'post_type'      => 'product',
               'post_status'    => 'publish',
               'posts_per_page' => -1,
               'product_cat'    => 'Uncategorized',
              
          );
          $argsp = array(
               'post_type'      => 'product',
               'post_status'    => 'publish',
               'posts_per_page' => -1,
               'product_cat'    => 'Packages',
              
          );
          $argsA = array(
               'post_type'      => 'product',
               'post_status'    => 'publish',
               'posts_per_page' => -1,
               'product_cat'    => 'Add-ons',
              
          );
           $products = get_posts( $argsb );
           $productsA = get_posts( $argsA );
           $productsP = get_posts( $argsp );
    
           $product_Array = array();
            $demo = new FloorPlanManager();
            $booths=$demo->getAllbooths();
          //   echo '<pre>';
          //   print_r($booths);
          //   exit;
            foreach ($booths as $key => $value) {
               if($value['bootheOwnerID'] == 'none')
               {
                    $product_id=$demo->getproductID($value['bootheID']);
                    $wc_deposit_enabled = get_post_meta($product_id, '_wc_deposit_enabled', true);
                    $wc_deposit_amount = get_post_meta($product_id, '_wc_deposit_amount', true);
                    $price = get_post_meta($product_id, '_regular_price', true);
                    $wc_deposit_type = get_post_meta($product_id, '_wc_deposit_type', true);
                    $product_status = get_post_meta($product_id, '_stock_status', true);
                    $stock = get_post_meta($product_id, '_stock', true);
                    $get_BoothLevel_amount = get_post_meta($product_id, "LevelOfBooth",true);
                    $get_Booth_Owner = get_post_meta($product_id, "BoothForUser",true);
                   
                    $coupon = array(
                         'id' => $product_id,
                         'title' => $value['boothNumber'],
                         'price' => $price,
                         'status' => $product_status,
                         'stock' =>  $stock,
                         'type' =>  $wc_deposit_type,
                         'boothLevel'=> $get_BoothLevel_amount,
                         'boothOwner'=> $get_Booth_Owner,
                         'deposit' => $wc_deposit_enabled,
                         'deposit_amount' => $wc_deposit_amount,
                         'catagory' => 'Uncategorized'
                    );
                  
                    array_push($product_Array, $coupon);
               }
               
          }
            foreach ($productsA as $key => $value) {
               
               $_product = wc_get_product( $value->ID );
               $wc_deposit_enabled = get_post_meta($value->ID, '_wc_deposit_enabled', true);
               $wc_deposit_amount = get_post_meta($value->ID, '_wc_deposit_amount', true);
               $price = get_post_meta($value->ID, '_regular_price', true);
               $wc_deposit_type = get_post_meta($value->ID, '_wc_deposit_type', true);
               $product_status = get_post_meta($value->ID, '_stock_status', true);
               $stock = get_post_meta($value->ID, '_stock', true);
               $get_BoothLevel_amount = get_post_meta($value->ID, "LevelOfBooth",true);
               $get_Booth_Owner = get_post_meta($value->ID, "BoothForUser",true);
             
               $coupon = array(
                    'id' => $value->ID,
                    'title' => $value->post_title,
                    'price' =>   $price,
                    'status' => $product_status,
                    'stock' =>  $stock,
                    'type' =>  $wc_deposit_type,
              
                    'boothLevel'=> $get_BoothLevel_amount,
                    'boothOwner'=> $get_Booth_Owner,
                    'deposit' => $wc_deposit_enabled,
                    'deposit_amount' => $wc_deposit_amount,
                    'catagory' => 'Add-ons'
               );
             
               array_push($product_Array, $coupon);
          }
            foreach ($productsP  as $key => $value) {
             
               $wc_deposit_enabled = get_post_meta($value->ID, '_wc_deposit_enabled', true);
               $wc_deposit_amount = get_post_meta($value->ID, '_wc_deposit_amount', true);
               $price = get_post_meta($value->ID, '_regular_price', true);
               $wc_deposit_type = get_post_meta($value->ID, '_wc_deposit_type', true);
               $product_status = get_post_meta($value->ID, '_stock_status', true);
               $stock = get_post_meta($value->ID, '_stock', true);
               $listofboothsID = get_post_meta( $value->ID, '_list_of_selected_booth', true);
              
               $get_BoothLevel_amount = get_post_meta($value->ID, "LevelOfBooth",true);
               $get_Booth_Owner = get_post_meta($value->ID, "BoothForUser",true);
               if(!empty($listofboothsID))
               {

                    foreach($listofboothsID as $boothKey=>$boothID){
                                   
                         $getthisboothproductID[] = $demo->getproductID($boothID);
                    }
               }
               $coupon = array(
                    'id' => $value->ID,
                    'title' => $value->post_title,
                    'price' =>  $price,
                    'status' => $product_status,
                    'stock' =>  $stock,
                    'type' =>  $wc_deposit_type,
                    'boothList'=> $getthisboothproductID ,
                    'boothLevel'=> $get_BoothLevel_amount,
                    'boothOwner'=> $get_Booth_Owner,
                    'deposit' => $wc_deposit_enabled,
                    'deposit_amount' => $wc_deposit_amount,
                    'catagory' => 'Packages'
               );
               $getthisboothproductID=[];
               array_push($product_Array, $coupon);
          }

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
               'date_expires'=>$disc_coupon->get_date_expires(),
               'count'=>$disc_coupon->get_usage_count(),
               'usage_limit'=>$disc_coupon->get_usage_limit(),

          );
          // print_r($coupon);
          // echo '<pre>';
          // print_r($disc_coupon);
          echo json_encode($coupon);
          // echo ($disc_coupon->amount);

          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
} else if ($_GET['orderManagerRequest'] == "getCoupons") {

     require_once('../../../wp-load.php');
     require_once('temp/lib/woocommerce-api.php');

     try {

          $coupon_posts = get_posts( array(
               'posts_per_page'   => -1,
               'orderby'          => 'name',
               'order'            => 'asc',
               'post_type'        => 'shop_coupon',
               'post_status'      => 'publish',
           ) );
          //  echo '<pre>';
          //  print_r($coupon_posts);
          $product_Array=[];
          foreach ($coupon_posts as $key => $value) {
               $disc_coupon = new WC_Coupon($value->post_title);
               $coupon = array(
                    'amount' => $disc_coupon->amount,
                    'code' => $disc_coupon->code,
                    'discount_type' => $disc_coupon->discount_type,
                    'product_ids' => $disc_coupon->product_ids,
                    'excluded_product_ids' => $disc_coupon->excluded_product_ids,
                    'product_categories' => $disc_coupon->product_categories,
                  
     
               );
               array_push($product_Array, $coupon);
          }


          echo json_encode($product_Array);
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
}else if ($_GET['orderManagerRequest'] == "sendEmail") {

     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Management.php';

     try {
          $meta_array = $_POST;
          $orderid=$_POST['order_id'];
          $emails = WC_Emails::instance();
          $emails->customer_invoice( wc_get_order( $orderid ) );
          echo 'success';
          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
}else if ($_GET['orderManagerRequest'] == "order_hisotry_id") {

     require_once('../../../wp-load.php');
     require_once plugin_dir_path(__DIR__) . 'EGPL/includes/Order-Management.php';

     try {
          $meta_array = $_POST;
          $obj = new ordermanagment();
          $product_array = $obj->getOrderHistory($meta_array);
          echo json_encode($product_array);
          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
}
else if ($_GET['orderManagerRequest'] == "getFloorplanstatus") {

     require_once('../../../wp-load.php');

     try {
          $contentmanager_settings = get_option( 'ContenteManager_Settings' );
          $id = $contentmanager_settings['ContentManager']['floorplanactiveid'];
          $floorplanstatuslockunlock = get_post_meta( $id, 'updateboothpurchasestatus', true );
          echo $floorplanstatuslockunlock;             
          die();
     } catch (Exception $e) {
          //throw $th;
          return $e;
     }
}