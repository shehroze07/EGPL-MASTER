<?php 

if($_GET['contentManagerRequestpactch'] == "updateallpagestitles") {        
    require_once('../../../wp-load.php');
    
    updateallpagestitles(); 
    die();
   
  
}else if($_GET['contentManagerRequestpactch'] == "updateusermeta") {        
    require_once('../../../wp-load.php');
    
    updateusermeta($_REQUEST); 
    die();
   
  
}
function updateusermeta($requestData){
    
    try{
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Update Cvent Registration Status',"Admin Action",serialize($requestData),$user_ID,$user_info->user_email,"pre_action_data");
     
    update_user_option($user_ID,"confirmation_number",$requestData['cventregistrationcode']);
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($requestData));
    
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
    
}
function updateallpagestitles(){
    
    
                // $create_pages_list[0]['title'] = 'Home';
                // $create_pages_list[0]['name']  = 'home';
                // $create_pages_list[0]['temp']  = 'egplhome.php';
                // $create_pages_list[0]['catname']  = true;
                
                // $create_pages_list[1]['title'] = 'FAQs';
                // $create_pages_list[1]['name']  = 'faqs';
                // $create_pages_list[1]['temp']  = 'egplfaq.php';
                // $create_pages_list[1]['catname']  = true;
                
                // $create_pages_list[2]['title'] = 'Resources';
                // $create_pages_list[2]['name']  = 'resources';
                // $create_pages_list[2]['temp']  =  'egplresource.php';
                // $create_pages_list[2]['catname']  = true;
    
    
                // $create_pages_list[4]['title'] = 'My Sites';
                // $create_pages_list[4]['name']  = 'my-sites';
                // $create_pages_list[4]['temp']  =  'egpl_admin_landing_page_multisite_template.php';
                // $create_pages_list[4]['catname']  = true;
            
                        
                // $create_pages_list[7]['title'] = 'Floor Plan';
                // $create_pages_list[7]['name']  = 'floor-plan';
                // $create_pages_list[7]['temp']  =  'egplflooplantemplate.php';
                // $create_pages_list[7]['catname']  = true;
                
                
                // $create_pages_list[8]['title'] = 'LogOut';
                // $create_pages_list[8]['name']  = 'logout';
                // $create_pages_list[8]['temp']  = 'egpldefualttemplate.php';
                // $create_pages_list[8]['catname']  = true;
                
                // $create_pages_list[9]['title'] = 'Cart';
                // $create_pages_list[9]['name']  = 'cart';
                // $create_pages_list[9]['temp']  = 'egplcarttemplate.php';
                // $create_pages_list[9]['catname']  = true;
               
                // $create_pages_list[10]['title'] = 'User Change Password';
                // $create_pages_list[10]['name']  = 'change-password';
                // $create_pages_list[10]['temp']  = 'egplchangepassword.php';
                // $create_pages_list[10]['catname']  = true;

                // $create_pages_list[11]['title'] = 'User Change Password';
                // $create_pages_list[11]['name']  = 'change-password-2';
                // $create_pages_list[11]['temp']  = 'egplchangepassword.php';
                // $create_pages_list[11]['catname']  = true; 
                
                $create_pages_list[0]['title'] = 'Clone Features';
                $create_pages_list[0]['name']  = 'clone-features';
                $create_pages_list[0]['temp']  = 'temp/egpl_cloning_features_temp.php';
                $create_pages_list[0]['catname']  = false; 

                $create_pages_list[1]['title'] = 'Manage Order';
                $create_pages_list[1]['name']  = 'manage-order';
                $create_pages_list[1]['temp']  = 'temp/ordermanagment/create-new-order-template.php';
                $create_pages_list[1]['catname']  = false; 
              
                
                
    
    $blog_list = get_blog_list( 0, 'all' );
    foreach ($blog_list as $blog_id) {
       
        if($blog_id['blog_id'] != 1){
            
            switch_to_blog($blog_id['blog_id']);
            
            
            
            global $woocommerce;
            
            $orderReports = [];

            $orderReports['Balance Due'] = array('{"condition":"AND","rules":[{"id":"Order Status","field":"Order Status","type":"string","input":"text","operator":"equal","value":"Balance Due"}],"valid":true}', ' ["Action","Created Date","Order ID","Initial Order ID","Order Status","Company Name","Total Amount","Total Amount After Discount","Order Discount","Paid Amount","Balance Due","Payment Date","Product Details","Number of Products ","Products List","Payment Method","Order Note","Account Holder Email","Billing Company","Billing First Name","Billing Last Name","Billing Email","Billing Phone Number","Billing Address Line 1","Billing Address Line 2","Billing City","Billing Post Code / ZIP","Billing Country / Region","Billing State / County","Level","Booth","User IP Address","Order Currency","Stripe Fee","Net Revenue From Stripe","Transaction ID","Discount Code"]', 'desc', 'Order Date');
            $orderReports['Initial Deposit Paid'] =  array('{"condition":"AND","rules":[{"id":"Order Status","field":"Order Status","type":"string","input":"text","operator":"equal","value":"Initial Deposit Paid"}],"valid":true}', ' ["Action","Created Date","Order ID","Initial Order ID","Order Status","Company Name","Total Amount","Total Amount After Discount","Order Discount","Paid Amount","Balance Due","Payment Date","Product Details","Number of Products ","Products List","Payment Method","Order Note","Account Holder Email","Billing Company","Billing First Name","Billing Last Name","Billing Email","Billing Phone Number","Billing Address Line 1","Billing Address Line 2","Billing City","Billing Post Code / ZIP","Billing Country / Region","Billing State / County","Level","Booth","User IP Address","Order Currency","Stripe Fee","Net Revenue From Stripe","Transaction ID","Discount Code"]', 'desc', 'Order Date');
            $orderReports['Paid in Full'] = array('{"condition":"AND","rules":[{"id":"Order Status","field":"Order Status","type":"string","input":"text","operator":"equal","value":"Paid in Full"}],"valid":true}', ' ["Action","Created Date","Order ID","Initial Order ID","Order Status","Company Name","Total Amount","Total Amount After Discount","Order Discount","Paid Amount","Balance Due","Payment Date","Product Details","Number of Products ","Products List","Payment Method","Order Note","Account Holder Email","Billing Company","Billing First Name","Billing Last Name","Billing Email","Billing Phone Number","Billing Address Line 1","Billing Address Line 2","Billing City","Billing Post Code / ZIP","Billing Country / Region","Billing State / County","Level","Booth","User IP Address","Order Currency","Stripe Fee","Net Revenue From Stripe","Transaction ID","Discount Code"]', 'desc', 'Order Date');
            $orderReports['Refunded'] =  array('{"condition":"AND","rules":[{"id":"Order Status","field":"Order Status","type":"string","input":"text","operator":"equal","value":"Refunded"}],"valid":true}', ' ["Action","Created Date","Order ID","Initial Order ID","Order Status","Company Name","Total Amount","Total Amount After Discount","Order Discount","Paid Amount","Balance Due","Payment Date","Product Details","Number of Products ","Products List","Payment Method","Order Note","Account Holder Email","Billing Company","Billing First Name","Billing Last Name","Billing Email","Billing Phone Number","Billing Address Line 1","Billing Address Line 2","Billing City","Billing Post Code / ZIP","Billing Country / Region","Billing State / County","Level","Booth","User IP Address","Order Currency","Stripe Fee","Net Revenue From Stripe","Transaction ID","Discount Code"]', 'desc', 'Order Date');
            $orderReports['Cancelled'] = array('{"condition":"AND","rules":[{"id":"Order Status","field":"Order Status","type":"string","input":"text","operator":"equal","value":"Cancelled"}],"valid":true}', ' ["Action","Created Date","Order ID","Initial Order ID","Order Status","Company Name","Total Amount","Total Amount After Discount","Order Discount","Paid Amount","Balance Due","Payment Date","Product Details","Number of Products ","Products List","Payment Method","Order Note","Account Holder Email","Billing Company","Billing First Name","Billing Last Name","Billing Email","Billing Phone Number","Billing Address Line 1","Billing Address Line 2","Billing City","Billing Post Code / ZIP","Billing Country / Region","Billing State / County","Level","Booth","User IP Address","Order Currency","Stripe Fee","Net Revenue From Stripe","Transaction ID","Discount Code"]', 'desc', 'Order Date');
            $orderReports['Failed'] =  array('{"condition":"AND","rules":[{"id":"Order Status","field":"Order Status","type":"string","input":"text","operator":"equal","value":"Failed"}],"valid":true}', ' ["Action","Created Date","Order ID","Initial Order ID","Order Status","Company Name","Total Amount","Total Amount After Discount","Order Discount","Paid Amount","Balance Due","Payment Date","Product Details","Number of Products ","Products List","Payment Method","Order Note","Account Holder Email","Billing Company","Billing First Name","Billing Last Name","Billing Email","Billing Phone Number","Billing Address Line 1","Billing Address Line 2","Billing City","Billing Post Code / ZIP","Billing Country / Region","Billing State / County","Level","Booth","User IP Address","Order Currency","Stripe Fee","Net Revenue From Stripe","Transaction ID","Discount Code"]', 'desc', 'Order Date');
           




 
    
        $reportsarray = [];

  
        array_push($reportsarray,$orderReports);

     
        
    // echo "<pre>";
    // print_r($reportsarray);
        
        foreach ($reportsarray as  $reports){
            
            //  echo "<pre>";
            //  print_r($reports);
          update_option("ContenteManager_Orderreport_settings", $reports); 

        }
            
            
            // $term = term_exists('Content Manager Editor', 'category');
            // $cat_id_get = $term['term_id'];
            // $cat_name = array($cat_id_get);
            
            foreach ($create_pages_list as $key => $value) {

                   
                       
                    
                    $page_path = $create_pages_list[$key]['name'];
                    $page = get_page_by_path($page_path);
                    // if($create_pages_list[$key]['catname'] == true){
                    //         $cat_name = array($cat_id_get);//'content-manager-editor';
                    //     }else{
                            
                    //          $cat_name = '' ; //'content-manager-editor';
                    //     }
                    if (!$page) {
                        
                        
                        $my_post = array(
                            'post_title' => wp_strip_all_tags($create_pages_list[$key]['title']),
                            'post_status' => 'publish',
                            'post_author' => get_current_user_id(),
                            'post_content'=> wp_strip_all_tags($create_pages_list[$key]['content']),
                            'post_category' => '' ,//'content-manager-editor',
                            'post_type' => 'page',
                            'post_name' => $page_path
                        );

// Insert the post into the database
                        $returnpage_ID = wp_insert_post($my_post);
                        update_post_meta($returnpage_ID, '_wp_page_template', $create_pages_list[$key]['temp']);
                        
                    }else{
                    
                  
                        
                   //     $pageID = $page->ID;
                        //update_post_meta($pageID, '_wp_page_template', $create_pages_list[$key]['temp']);
                      //  wp_set_post_categories( $pageID, array( $cat_id_get ) );
                        
                        
//                       if($page_path == "task-page"){
//                            
//                            wp_set_post_categories( $pageID, array() );
//                        }else{
//                            
//                            
//                        }
                   
                }
        
                //  if($create_pages_list[$key]['name'] == "landing-page"){
                        
                //         update_option( 'page_on_front', $page->ID );
                //         update_option( 'show_on_front', 'page' );
                //     }
                
                }
            
            
          
            
          

           
                
                
            }
            echo 'Site '.$blog_id['blog_id'].' completed -----'.$blog_id['blog_name'].'<br>';
        }
    }
                
    
    




//function updateallpagestitles(){
//    
//    
//    
//    
//    
//    $create_pages_list_new[1]['title'] = 'Add New Package';
//    $create_pages_list_new[1]['name']  = 'add-new-package';
//    $create_pages_list_new[1]['temp']  = 'temp/add_new_product__package_template.php';
//    $create_pages_list_new[1]['catname']  = false;
//    
//    
//    $create_pages_list_new[2]['title'] = 'User Entry Settings';
//    $create_pages_list_new[2]['name']  = 'user-entry-settings';
//    $create_pages_list_new[2]['temp']  = 'temp/exhibitor-entry-settings-template.php';
//    $create_pages_list_new[2]['catname']  = false;
//    
//    $create_pages_list_new[3]['title'] = 'Entry Wizard ';
//    $create_pages_list_new[3]['name']  = 'entry-wizard';
//    $create_pages_list_new[3]['temp']  = 'exhibitor-entry-wizerd.php';
//    $create_pages_list_new[3]['catname']  = false;
//    
//    $create_pages_list_new[4]['title'] = 'Entry Wizard Intro Page';
//    $create_pages_list_new[4]['name']  = 'intro';
//    $create_pages_list_new[4]['temp']  = 'egpldefualttemplate.php';
//    $create_pages_list_new[4]['catname']  = true;
//    
//    $create_pages_list_new[5]['title'] = 'Checkout Page';
//    $create_pages_list_new[5]['name']  = 'checkout-content';
//    $create_pages_list_new[5]['temp']  = 'egpldefualttemplate.php';
//    $create_pages_list_new[5]['catname']  = true;
//    
//    $create_pages_list_new[6]['title'] = 'Order Confirmation Page';
//    $create_pages_list_new[6]['name']  = 'thank-you-content';
//    $create_pages_list_new[6]['temp']  = 'egpldefualttemplate.php';
//    $create_pages_list_new[6]['catname']  = true;
//    
//    
//    $create_pages_list_new[7]['title'] = "Public Landing Page";
//    $create_pages_list_new[7]['name'] = 'landing-page-content';
//    $create_pages_list_new[7]['temp'] = 'egpldefualttemplate.php';
//    $create_pages_list_new[7]['catname'] = true;
//
//    
//    $create_pages_list_new[8]['title'] = 'Login Page';
//    $create_pages_list_new[8]['name'] = 'login-page';
//    $create_pages_list_new[8]['temp'] = 'egpldefualttemplate.php';
//    $create_pages_list_new[8]['catname'] = true;
//
//
//    $create_pages_list_new[9]['title'] = 'Registration Page';
//    $create_pages_list_new[9]['name']  = 'registration-page-content';
//    $create_pages_list_new[9]['temp']  = 'egpldefualttemplate.php';
//    $create_pages_list_new[9]['catname']  = true;
//    
//   $create_pages_list_new[10]['title'] = 'Home Page - Intro';
//   $create_pages_list_new[10]['name']  = 'home-intro';
//   $create_pages_list_new[10]['temp']  = 'egpldefualttemplate.php';
//   $create_pages_list_new[10]['catname']  = true;
//
//   $create_pages_list_new[11]['title'] = 'Home Page - Event Overview';
//   $create_pages_list_new[11]['name']  = 'event-overview';
//   $create_pages_list_new[11]['temp']  = 'egpldefualttemplate.php';
//   $create_pages_list_new[11]['catname']  = true;
//
//   
//   $create_pages_list_new[12]['title'] = 'Home Page - Expo Hall Hours';
//   $create_pages_list_new[12]['name'] = 'agend';
//   $create_pages_list_new[12]['temp'] = 'egpldefualttemplate.php';
//   $create_pages_list_new[12]['catname'] = true;
//
//   $create_pages_list_new[13]['title'] = 'Task List Page';
//   $create_pages_list_new[13]['name']  = 'task-list';
//   $create_pages_list_new[13]['temp']  = 'metronictasks.php';
//   $create_pages_list_new[13]['catname']  = true;
//   
//   $create_pages_list_new[14]['title'] = 'Floor Plan Page';
//   $create_pages_list_new[14]['name'] = 'floor-plan-page';
//   $create_pages_list_new[14]['temp'] = 'egpldefualttemplate.php';
//   $create_pages_list_new[14]['catname'] = true;
//
//   $create_pages_list_new[15]['title'] = 'Resources Content';
//   $create_pages_list_new[15]['name'] = 'resources-content';
//   $create_pages_list_new[15]['temp'] = 'egpldefualttemplate.php';
//   $create_pages_list_new[15]['catname'] = true;
//
//   $create_pages_list_new[16]['title'] = 'FAQs Page';
//   $create_pages_list_new[16]['name'] = 'faqs-page';
//   $create_pages_list_new[16]['temp'] = 'egpldefualttemplate.php';
//   $create_pages_list_new[16]['catname'] = true;
//
//   $create_pages_list_new[17]['title'] = 'Terms & Conditions Page';
//   $create_pages_list_new[17]['name'] = 'terms-and-conditions';
//   $create_pages_list_new[17]['temp'] = 'egpldefualttemplate.php';
//   $create_pages_list_new[17]['catname'] = true;
//   
//   $create_pages_list_new[18]['title'] = 'Floor Plan Warning';
//   $create_pages_list_new[18]['name'] = 'floor-plan-warning';
//   $create_pages_list_new[18]['temp'] = 'temp/floorplan_warning_template.php';
//   $create_pages_list_new[18]['catname'] = false;
//   
//   
//   $create_pages_list_new[18]['title'] = 'View User Order';
//   $create_pages_list_new[18]['name'] = 'view-user-order';
//   $create_pages_list_new[18]['temp'] = 'temp/admin_view_orders.php';
//   $create_pages_list_new[18]['catname'] = false;
//   
//
//    $term = term_exists('Content Manager Editor', 'category');
//    $cat_id_get = $term['term_id'];
//    
//    $blog_list = get_blog_list( 0, 'all' );
//    foreach ($blog_list as $blog_id) {
//       
//        if($blog_id['blog_id'] != 1){
//            
//            switch_to_blog($blog_id['blog_id']);
//            
//            $term = term_exists('Content Manager Editor', 'category');
//            $cat_id_get = $term['term_id'];
//            
//            
//            foreach ($create_pages_list_new as $key => $value) {
//                
//                //echo $value['title'].'<br>';
//                
//                if($value['catname'] == 1){
//                    
//                        $cat_name = array($cat_id_get);//'content-manager-editor';
//                        
//                            
//                }else{
//                            
//                        $cat_name = '' ; //'content-manager-editor';
//                }
//                
//            $page = get_page_by_path($value['name']);
//            
//            if (!$page) {
//                    
//                       $my_post = array(
//                            'post_title' => wp_strip_all_tags($value['title']),
//                            'post_status' => 'publish',
//                            'post_author' => get_current_user_id(),
//                            
//                            'post_category' => $cat_name ,//'content-manager-editor',
//                            'post_type' => 'page',
//                            'post_name' => $value['name']
//                        );
//
//
//                        $returnpage_ID = wp_insert_post($my_post);
//                        update_post_meta($returnpage_ID, '_wp_page_template', $value['temp']);
//                    
//                    
//                    
//                    
//            }else{
//                    
//                    $my_post = array(
//                            'ID'  => $page->ID,
//                            'post_title'  => $value['title'],
//                           
//                    );
//                    wp_update_post( $my_post );
//                        
//            }
//                
//                
//            }
//            echo 'Site '.$blog_id['blog_id'].' completed -----'.$blog_id['blog_name'].'<br>';
//        }
//    }
//                
//    
//    
//}