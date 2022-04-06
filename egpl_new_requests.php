<?php
	 if($_GET['contentManagerRequest'] == "addnewmenuitem") {        
	    require_once('../../../wp-load.php');
	    
	  add_new_menuItem($_POST);

}else if($_GET['contentManagerRequest'] == "addmenuitem") {        
    require_once('../../../wp-load.php');
    
    //echo "Hello";


 	 add_menu($_POST);

	}else if($_GET['contentManagerRequest'] == "editmenu") {        
	    require_once('../../../wp-load.php');
	    
	   edit_menu_name($_POST);


	}else if($_GET['contentManagerRequest'] == "setmenuitemorder") {        
	    require_once('../../../wp-load.php');

	  set_menu_order($_POST);


	}else if ($_GET['contentManagerRequest'] == 'removemenu') {
	    
	    require_once('../../../wp-load.php');
	    try{

	     $remove_menu_name =$_POST['menuname'];
	    
	    
	     $lastInsertId = contentmanagerlogging('Remove Menu',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
	     $user_ID = get_current_user_id();
	     $user_info = get_userdata($user_ID);
	     $result = wp_delete_post($remove_menu_name);
	    
             
             $menu_name = 'primary';
             $locations = get_nav_menu_locations();
             $menu = wp_get_nav_menu_object($locations[$menu_name]);
             $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
             foreach ($menuitems as $key => $item){
                 
                 if($item->menu_item_parent == $remove_menu_name){
                     
                     
                     update_post_meta($item->ID, 'menu_item_parent', "");
                     
                     
                     
                 }
                 
                 
             }
             
             
             
             
	     $msg['msg'] = 'Menu item Removed Successfuly.';
	     echo   json_encode($msg);
	     contentmanagerlogging_file_upload ($lastInsertId,serialize($result));

	        }catch (Exception $e) {
	       
	         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
	         return $e;
	 }
	     die();

	}


	else if ($_GET['contentManagerRequest'] == 'createpage') {
	    
	    require_once('../../../wp-load.php');

	  
	$pagetitle = $_POST["pagetitle"];

	 $contentbody = $_POST["contentbody"];

	$category = get_category_by_slug( 'Content Manager Editor' );
	$user_ID = get_current_user_id();
	$user_info = get_userdata($user_ID);
	     $new_page = array(
	      'post_title'    =>  $pagetitle,
	      'post_content'  =>  $contentbody,
	      'post_category' => array( $category->term_id ),
	      'post_status'   => 'publish',
	      'post_author'   => $user_ID,
	      'post_type' => 'page'
	       );



	// $template_path = get_childTheme_url();


	//     get_stylesheet_directory() .'/egpldefaulttemplate.php';

	   $post_id = wp_insert_post( $new_page );
	   add_post_meta( $post_id, '_wp_page_template', 'egpldefualttemplate.php' ); 
	  print_r($new_page);
	   //die();

	}


/////////////////////////////////////////////////////////////////////////////////////


function add_menu($request){

try{
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Add Menu Item',"Admin Action",serialize($request),''.$user_ID,$user_info->user_email,"pre_action_data");




         $args = array(
        'post_type' => 'page',
        'category_name'    => 'Content Manager Editor',
        'posts_per_page'   => -1,
        'order'            => 'ASC',
        
    );

    $pages = new WP_Query( $args );

     $responcearray = [];
     $index = 0;
      
 
      
     if(!empty($request['menuitemid'])){
         
         $responcearray['page_visibility'] = get_post_meta($request['menuitemid'], 'page_visibility', true);
         $responcearray['page_type'] = get_post_meta($request['menuitemid'], 'page_type', true);
         $responcearray['addon_enabled'] = get_post_meta($request['menuitemid'], 'addon_enabled', true);

         
         
     }
      


                                  	 while ( $pages->have_posts() ) : $pages->the_post();
                                  	 	$page_id = get_the_ID();
                                  	 	$page_title = get_the_title();

										   global $post;
										   $slug =  $post->post_name;

                                  	 	  $responcearray['pageslist'][$index]['pageId'] = $page_id;
        								  $responcearray['pageslist'][$index]['pagetitle'] = $page_title;
										  $responcearray['pageslist'][$index]['pageslug'] = $slug;
       									  $index++;
                                   
                                   endwhile;
                                    // echo "<pre>";
                                    // print_r($responcearray);

                            


         echo json_encode($responcearray);


         }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();


}






/////////////////////////////////////////////////////////////////////////////////////

	function add_new_menuItem($request){
	    
	     try{
	      
	         $menu_name = 'primary';
	         $locations = get_nav_menu_locations();
	         $menu = wp_get_nav_menu_object($locations[$menu_name]);
	         $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));

	         $main_menu_id = $menu->term_id;
	         
	        $user_ID = get_current_user_id();
	        $user_info = get_userdata($user_ID);  
	        $lastInsertId = contentmanagerlogging('Add New Item',"Admin Action",serialize($request),''.$user_ID,$user_info->user_email,"pre_action_data");
	       
	        $menunamenew = $request['menunewname'];
	        $itemslug = $request['slug'];
	        
                
                if($request['type'] == "customlink"){
                    
                    $menuurlnew = $request['menunewurl'];
                    
                }else{
                    
                    
                    $pageid = $request['pageiddropdown'];
                    
                }
	        
	        
	       $public_visibility = $request['public_visibility'];

	        $result_update = 'newvalue';
	        $result_updt = 'newvalue';

	        
	       

	        if ($request['type'] == 'page') {
	           
                        $post = get_post($pageid); 
	                  
	                $argu = array(
	                'menu-item-title' => $menunamenew,
	                'menu-item-object' => 'page',
	                'menu-item-object-id' => $pageid,
	                'menu-item-type' => 'post_type',
	                'menu-item-url' => home_url( '/'.$post->post_name.'/' ), 
	                'menu-item-position'  => 0,
	                'menu-item-status' => 'publish');

	                $results = wp_update_nav_menu_item($main_menu_id, 0, $argu);
	 
	                 //echo $results;

	                $result_status['msg']= 'update';
	       

	}elseif($request['type'] == 'customlink'){

	        
	             $results = wp_update_nav_menu_item($main_menu_id, 0, array(
	                'menu-item-title' => $menunamenew,
	                'menu-item-type' => 'custom',
	                'menu-item-url' => $menuurlnew, 
	                'menu-item-position'  => 0,
	                'menu-item-status' => 'publish'));
	            
	            $result_status['msg']= 'update';
	       
	}
	       
                 
	         
	         update_post_meta($results, 'page_visibility', $public_visibility);
             update_post_meta($results, 'page_type', $request['type']);
	         //echo $results;
	        //echo $public_visibility; 

	        

	        contentmanagerlogging_file_upload ($lastInsertId,serialize($result_status));
	        
	       echo json_encode($result_status);
	         
	    }catch (Exception $e) {
	       
	        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
	   
	      return $e;
	    }
	 
	 die();  
	    
	    
	}



	function edit_menu_name($request){
	    
	     try{
	      
	        
	         
	        $user_ID = get_current_user_id();
	        $user_info = get_userdata($user_ID);  
	        $lastInsertId = contentmanagerlogging('Edit Menu Name',"Admin Action",serialize($request),''.$user_ID,$user_info->user_email,"pre_action_data");
	       
	        $menunamenew = $request['menunewname'];
	        $menuurlnew = $request['menunewurl'];
	        $menu_item_id = $request['menuid'];
	        $itemslug = $request['slug'];
	        $main_menu_id =$request['main_menu_id'];
	        $pageid = $request['pageiddropdown'];
	        $menuorder = $request['menuorder'];

	        $type = $request['type'];
	        $customlinkurl = $request['customlinkurl'];
	        
	        $public_visibility = $request['public_visibility'];
            $parentID = $request['parentid'];


	        $result_update = 'newvalue';
	        $result_updt = 'newvalue';

	        $addons_enable = $request['addons_enable'];

	        //  echo $addons_enable;

	        if ($type == 'page') {
	           
	 

	                $post = get_post($pageid); 
	                  
	                $argu = array(
	                'menu-item-title' => $menunamenew,
	                'menu-item-object' => 'page',
	                'menu-item-object-id' => $pageid,
	                'menu-item-type' => 'post_type',
	                'menu-item-url' => home_url( '/'.$post->post_name.'/' ), 
	                'menu-item-position'  => $menuorder,
                    'menu-item-parent-id' => $parentID,
	                'menu-item-status' => 'publish');

	                $results = wp_update_nav_menu_item($main_menu_id, $menu_item_id, $argu);

	               



	            $result_status['msg']= 'update';
	        

	}elseif($type == 'customlink'){

	       
	             $results = wp_update_nav_menu_item($main_menu_id, $menu_item_id, array(
	                'menu-item-title' => $menunamenew,
	                'menu-item-type' => 'custom',
	                'menu-item-url' => $customlinkurl, 
	                'menu-item-position'  => $menuorder,
                         'menu-item-parent-id' => $parentID,
	                'menu-item-status' => 'publish'));
	            $result_status['msg']= 'update';
	        
	}
	       update_post_meta($results, 'page_visibility', $public_visibility);
               update_post_meta($results, 'page_type', $request['type']);
	       update_post_meta( $results, '_menu_item_menu_item_parent', $parentID);
               update_post_meta( $results, 'menu_item_parent', $parentID);
             update_post_meta($results, 'addon_enabled', $addons_enable);

	        contentmanagerlogging_file_upload ($lastInsertId,serialize($result_status));
	        
	        // echo json_encode($results);
	       echo json_encode($result_status);
	         
	    }catch (Exception $e) {
	       
	        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
	   
	      return $e;
	    }
	 
	 die();  
	    
	    
	}


	function set_menu_order($request){

	 try{
	         $menu_name = 'primary';
	         $locations = get_nav_menu_locations();
	         $menu = wp_get_nav_menu_object($locations[$menu_name]);
	         $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));

	         $main_menu_id = $menu->term_id;

	         //echo $main_menu_id;

	        $menuorder =  json_decode(stripslashes($request['orderlist']), true);
	            

	        $user_ID = get_current_user_id();
	        $user_info = get_userdata($user_ID);  
	        $lastInsertId = contentmanagerlogging('Update Menu Order',"Admin Action","",$user_ID,$user_info->user_email,$menuorder);
	        
	        $count1 = 1 ;
	        $count2 = 1;

	         foreach($menuorder as $key=>$order) {
	           //      echo '<br>';
	           // echo "inside loop";
	            $menuordernumber =  $order['ordernumber'];

	            $parentmenuordernumber = $menuordernumber + $count1;

	            // echo "<pre>";
	            // print_r($parentmenuordernumber);

	            $menuitemid    =  $order['menuitemid'];

	             // echo "<pre>";
	             // print_r($menuitemid);

	            $pagetitle = $order['pagetitle'];

	            $pageID = $order['pageID'];

	            $itemUrl = $order['itemurl'];

	            $itemObject = $order['itemObject'];

	            $menuitemparent = $order['menuitemparent'];

	            $childarray = json_decode(stripslashes($order['childearray']), true);

//	                 echo "<pre>";
//	                 print_r($childarray);

	   if (!empty($childarray)) {
	       
	       //  echo "Condition Working";

	        foreach ($childarray as $key => $childorder) {

	           //  echo '<br>';
	           // echo "inside loop";
	            
	           
	            
	            
	            $chmenuordernumber = $childorder['ordernumber']; 

	            $childmenuordernumber = $chmenuordernumber + $parentmenuordernumber + $count2 ;

	            // echo "<pre>";
	            // print_r($childmenuordernumber);

	             $childmenuitemid    =  $childorder['menuitemid'];

	            //   echo "<pre>";
	            // print_r($childmenuitemid);

	            $childpagetitle = $childorder['pagetitle'];

	            // echo "<pre>";
	            // print_r($childpagetitle);

	            $childpageID = $childorder['pageID'];

	            $childitemUrl = $childorder['itemurl'];

	            $childitemObject = $childorder['itemObject'];

	             // echo "<pre>";
	             // print_r($childitemObject);

	            $childmenuitemparent = $childorder['menuitemparent'];

	           

	                if($childitemObject == 'page'){
                            
                            
	                $childpost = get_post($childpageID); 
	                  
	                $argu = array(
	                'menu-item-title' => $childpagetitle,
	                'menu-item-object' => 'page',
	                'menu-item-object-id' => $childpageID,
	                'menu-item-type' => 'post_type',      
	                'menu-item-url' => home_url( '/'.$childpost->post_name.'/' ),  
	                'menu-item-position'  => $childmenuordernumber,
	                'menu-item-parent-id' => $childmenuitemparent,
	                'menu-item-status' => 'publish'
	            );
                        
//                    echo '<pre>';
//                    print_r($argu);
	            $results = wp_update_nav_menu_item($main_menu_id,  $childmenuitemid, $argu);
                    update_post_meta( $childmenuitemid, '_menu_item_menu_item_parent', $childmenuitemparent);
                    update_post_meta( $childmenuitemid, 'menu_item_parent', $childmenuitemparent);
                   
                   
	     }elseif($childitemObject == 'custom'){
                        
                       
	                $args =array(
	                'menu-item-title' => $childpagetitle,
	                'menu-item-type' => 'custom',
	                'menu-item-url' => $childitemUrl, 
	                'menu-item-position'  => $childmenuordernumber,
	                'menu-item-parent-id' => $childmenuitemparent,
	                'menu-item-status' => 'publish');

	                  

	             $results = wp_update_nav_menu_item($main_menu_id, $childmenuitemid, $args); 
                     update_post_meta( $childmenuitemid, '_menu_item_menu_item_parent', $childmenuitemparent);
                     update_post_meta( $childmenuitemid, 'menu_item_parent', $childmenuitemparent);
                        //$childpost2 = get_post($childpageID); 
	                // echo "<pre>";
	                // print_r($childpost2);
	        }

	        }
	        
	   }



	        if($itemObject == 'page'){

	                $post = get_post($pageID); 
	                  
	                $argu = array(
	                'menu-item-title' => $pagetitle,
	                'menu-item-object' => 'page',
	                'menu-item-object-id' => $pageID,
	                'menu-item-type' => 'post_type',   
	                'menu-item-url' => home_url( '/'.$post->post_name.'/' ),  
	                'menu-item-position'  => $parentmenuordernumber,
	                'menu-item-parent-id' => 0,
	                'menu-item-status' => 'publish'
	            );

	              

	            $results = wp_update_nav_menu_item($main_menu_id,  $menuitemid, $argu);
                    update_post_meta( $menuitemid, '_menu_item_menu_item_parent', 0);
                    update_post_meta( $menuitemid, 'menu_item_parent', 0);
	      
	     }elseif($itemObject == 'custom'){

	             $args =  array(
	                'menu-item-title' => $pagetitle,
	                'menu-item-type' => 'custom',
	                'menu-item-url' => $itemUrl , 
	                'menu-item-position'  => $parentmenuordernumber,
	                'menu-item-parent-id' => 0,
	                'menu-item-status' => 'publish');   

	               //echo "<pre>";
	                 //print_r($args);

	             $results = wp_update_nav_menu_item($main_menu_id, $menuitemid, $args);
	             update_post_meta( $menuitemid, '_menu_item_menu_item_parent', 0);
                    update_post_meta( $menuitemid, 'menu_item_parent', 0);
	        }

	    
	 }     


	        echo "success";
	        die();
	        
	        
	    }catch (Exception $e) {
	       
	        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
	   
	      return $e;
	    }
	 
	 die();   


	}

