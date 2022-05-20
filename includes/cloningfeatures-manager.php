<?php
class ClonefeatureManager {
    
    
    
    public $clonesiteID; 

 
    
    public function __construct($siteID) {
    

       
        $this->$clonesiteID = $siteID;
        //$this->$currentsiteID = get_current_blog_id();
    }


    public function cloneeventSettings($datastatus){

        
        switch_to_blog($this->$clonesiteID);
        $clonedEventSiteSettings = get_option("ContenteManager_Settings");
        $clonedEntryWizardSettings = get_option("custome_exhibitor_flow_settings_data");
        $clonedFloorplanQueueSettings = get_option("floorPlanSettings");
        $wizardsettings = get_option("exhibitorentryflowstatus");
        $boothQueueSettings = get_option("boothQueueSettings");
        
        
        restore_current_blog();

        $loginpageID = get_page_by_path('login');
        $landingpageID = get_page_by_path('landing-page');
        $applicationflowstatus = $wizardsettings['status'];

       

        if ($applicationflowstatus == "checked") {

           

           if (!empty($landingpageID)) {
            
             
               update_option('page_on_front', $landingpageID->ID);
               update_option('show_on_front', 'page');
           }
       } else {

     
           if (!empty($loginpageID)) {
         
               update_option('page_on_front', $loginpageID->ID);
               update_option('show_on_front', 'page');
           }
       }


       

        if(!empty($clonedEventSiteSettings)){
            update_option("ContenteManager_Settings", $clonedEventSiteSettings);
        }
        
        if(!empty($boothQueueSettings)){

            update_option("boothQueueSettings", $boothQueueSettings);
           
        }
        

        


        if(!empty($clonedEntryWizardSettings)){
            update_option("custome_exhibitor_flow_settings_data", $clonedEntryWizardSettings);
        }

        if(!empty($clonedFloorplanQueueSettings)){
            update_option("floorPlanSettings", $clonedFloorplanQueueSettings);
        }

        if(!empty($wizardsettings)){
            update_option("exhibitorentryflowstatus", $wizardsettings);
        }

        return 'done';


    }

    public function cloneReports($datastatus){

        
        
        
        
        switch_to_blog($this->$clonesiteID);

        $clonedUsersTaskReports = get_option("ContenteManager_userstasksreport_settings");
        $clonedUsersReports = get_option("ContenteManager_usersreport_settings");
        $clonedOrdersReports = get_option("ContenteManager_Orderreport_settings");
        
        
        
        


        restore_current_blog();

        $clonID = "_".$this->$clonesiteID."_";
        $clonedID= "_".get_current_blog_id()."_";


        if(!empty($clonedUsersTaskReports)){

            foreach($clonedUsersTaskReports as $reportnametasks=>$reportData){

                $clonedUsersTaskReports[$reportnametasks][0] = str_replace($clonID,$clonedID, $clonedUsersTaskReports[$reportnametasks][0]);

            }
           
            
            update_option("ContenteManager_userstasksreport_settings", $clonedUsersTaskReports);
        }
        
        if(!empty($clonedUsersReports)){
           
            foreach($clonedUsersReports as $reportname=>$reportData){

                $clonedUsersReports[$reportname][0] = str_replace($clonID,$clonedID, $clonedUsersReports[$reportname][0]);

            }

            
            update_option("ContenteManager_usersreport_settings", $clonedUsersReports);
        
        }

        if(!empty($clonedOrdersReports)){

            foreach($clonedOrdersReports as $orderreport=>$reportData){

                $clonedUsersTaskReports[$orderreport][0] = str_replace($clonID,$clonedID, $clonedOrdersReports[$orderreport][0]);

            }
            update_option("ContenteManager_Orderreport_settings", $clonedOrdersReports);
        }

        return 'done';


    }

    public function cloneMenupages($datastatus){

        switch_to_blog($this->$clonesiteID);
        $getpagesData = $this->getAllpages($this->$clonesiteID);
        $getmenuitemsData = $this->getAllmenuitems($this->$clonesiteID);

        
        restore_current_blog();
        
        $this->removeAllmenu(get_current_blog_id());
        $this->createAllpages(get_current_blog_id(),$getpagesData);
        $this->createAllmenuitems(get_current_blog_id(),$getmenuitemsData);

        return 'done';


    }

    public function cloneUsers($datastatus){

        switch_to_blog($this->$clonesiteID);
        $usersData = $this->getAllUsers($this->$clonesiteID);


        
        restore_current_blog();

        if($datastatus == "checked"){

            $this->removeallusers(get_current_blog_id());

        }

        $this->createAllusers(get_current_blog_id(),$usersData);

        return 'done';


    }

    public function cloneLevel($datastatus){

        switch_to_blog($this->$clonesiteID);

        $get_all_roles_array = 'wp_'.$this->$clonesiteID.'_user_roles';
        $get_all_roles = get_option($get_all_roles_array);

        restore_current_blog();
        
      

        if($datastatus == "checked"){

            $get_all_roles_array2 = 'wp_'.get_current_blog_id().'_user_roles';
            update_option ($get_all_roles_array2, $get_all_roles);

        }else if($datastatus == "checked-add"){


            $listofalllevels = $this->listofcurretnsitelevels();
            $get_all_roles_array2 = 'wp_'.get_current_blog_id().'_user_roles';
            $get_all_roles2 = get_option($get_all_roles_array2);
          
            foreach ($get_all_roles as $key => $item) {

                if($item['name'] != "Administrator" && $item['name'] != "Content Manager"){
                    if (!in_array($key, $listofalllevels)){

                        
                        $role_key=strtolower($item['name']);
                        $remove_space_role_kye=str_replace(" ","_",$role_key);

                        $get_all_roles2[$remove_space_role_kye]['name'] =  ucfirst($item['name']);
                        $get_all_roles2[$remove_space_role_kye]['capabilities']['unfiltered_upload'] =  1;//ucfirst($newrolename);
                        $get_all_roles2[$remove_space_role_kye]['capabilities']['upload_files'] =  1;//ucfirst($newrolename);
                        $get_all_roles2[$remove_space_role_kye]['capabilities']['level_0'] =  1;
                        $get_all_roles2[$remove_space_role_kye]['capabilities']['read'] =  1;
                        $get_all_roles2[$remove_space_role_kye]['priorityNum'] = $item['priorityNum'];

                        update_option ($get_all_roles_array2, $get_all_roles2);
    
                    }
                }

            }



        }


        
        
        return 'done';


    }

    public function clonetasks($datastatus){

        switch_to_blog($this->$clonesiteID);

        $getAllclonedTaskData = $this->getAlltasks($this->$clonesiteID);

        restore_current_blog();
        
        $this->removeAllTasks(get_current_blog_id());
        $this->createAllTasks(get_current_blog_id(),$getAllclonedTaskData);
        


        return 'done';


    }


    public function cloneuserfields($datastatus){

        switch_to_blog($this->$clonesiteID);

        $getAllclonedTaskData = $this->getAlluserfields($this->$clonesiteID);

        restore_current_blog();
        
        $this->removeAlluserfields(get_current_blog_id());
        $this->createAllfields(get_current_blog_id(),$getAllclonedTaskData);
        


        return 'done';


    }

    public function cloneResources($datastatus){

        


        switch_to_blog($this->$clonesiteID);
        $getAllclonedResourcesdata = $this->getAllResources($this->$clonesiteID);


       
        restore_current_blog();

        
       
        $this->removeAllResources(get_current_blog_id());
        $this->createAllResources(get_current_blog_id(),$getAllclonedResourcesdata);
         

        return 'done';



    }

    public function cloneShop($datastatus){


        switch_to_blog($this->$clonesiteID);
        $getAllclonedproductData = $this->getAllproducts($this->$clonesiteID);

        
        restore_current_blog();

        
        
        $this->removeAllProducts(get_current_blog_id());
        $this->createAllproducts(get_current_blog_id(),$getAllclonedproductData);

        return 'done';


    }

    public function cloneFlorrplan($datastatus){

        

        switch_to_blog($this->$clonesiteID);
        
        $floorPlanSettings = get_option("floorPlanSettings");
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
        $ClonedFloorplanID = $contentmanager_settings['ContentManager']['floorplanactiveid'];

        $boothTypes        = get_post_meta( $ClonedFloorplanID, 'booth_types', true );
        $FloorBackground   = get_post_meta( $ClonedFloorplanID, 'floor_background', true );
        $FloorplanXml[0]   = get_post_meta( $ClonedFloorplanID, 'floorplan_xml', true );
        $FloorplanLegends  = get_post_meta( $ClonedFloorplanID, 'legendlabels', true );
        $FloorplanTags     = get_post_meta( $ClonedFloorplanID, 'boothtags', true );
        $mxPriceTegsObject = get_post_meta( $ClonedFloorplanID, 'pricetegs', true );
        $sellboothsjson    = get_post_meta( $ClonedFloorplanID, 'sellboothsjson', true );
        $floorplanstatuslockunlock = get_post_meta( $ClonedFloorplanID, 'updateboothpurchasestatus', true );

        restore_current_blog();

        $my_post = array(
            'post_title' => 'New Cloned Floorplan',
            'post_content' => '',
            'post_status' => '',
            'post_author' => 1,
            'post_type'=>'floor_plan',
           
        );
        $newboothjson = str_replace('"boothstatus":"updated"','"boothstatus":"newBooth"', $sellboothsjson);
        $contentmanager_settings_new = get_option( 'ContenteManager_Settings' );
        $id = wp_insert_post($my_post);
        $contentmanager_settings_new['ContentManager']['floorplanactiveid'] = $id;
        update_option("ContenteManager_Settings", $contentmanager_settings_new);
        update_option("floorPlanSettings", $floorPlanSettings);

        update_post_meta( $id, 'booth_types', $boothTypes );
        update_post_meta( $id, 'floor_background', $FloorBackground);
        update_post_meta( $id, 'floorplan_xml', $FloorplanXml[0] );
        update_post_meta( $id, 'legendlabels', $FloorplanLegends );
        update_post_meta( $id, 'floorplantitle', 'Clone Floor Plan' );
        update_post_meta( $id, 'boothtags', $FloorplanTags );
       
        update_post_meta( $id, 'pricetegs', $mxPriceTegsObject  );
        update_post_meta( $id, 'sellboothsjson', $newboothjson  );
        update_post_meta( $id, 'updateboothpurchasestatus', $floorplanstatuslockunlock );

      
        
        require_once 'floorplan-manager.php';
        $demo = new FloorPlanManager();
        $defaultImage = get_site_url()."/wp-content/plugins/floorplan/icon01.png";
        $productpicID = $this->uploadImage($defaultImage);
        $responce = $demo->createAllBoothPorducts($id,$newboothjson,$FloorplanXml[0],$productpicID);


        $cleanfloorplan = $this->cleanfloorplanxml();

        return 'done';


    }

    

    public function getAlluserfields($siteId){


        switch_to_blog($siteId);

        require_once 'egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $listOFcustomfieldsArray = $GetAllcustomefields->getAllcustomefields();
        
        restore_current_blog();
        return $listOFcustomfieldsArray;
    }


    public function removeAlluserfields($siteID){

        switch_to_blog($siteID);

        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_fields',
            'post_status'      => 'draft',
	      );
        $listOFcustomfieldsArray = get_posts( $args );
        
      
        foreach ($listOFcustomfieldsArray as $fieldskey => $fieldsObject) {

            wp_delete_post( $fieldsObject->ID, true );

        }

        restore_current_blog();
        return 'success';


    }

    public function createAllfields($siteID,$listofcloneduserfields){

        switch_to_blog($siteID);

        $user_ID = get_current_user_id();
        

        foreach ($listofcloneduserfields as $fieldskey => $userfieldmeta) {

            
            $taskaObjectData = array(
                'post_title'    => wp_strip_all_tags( $userfieldmeta['fieldID'] ),
                'post_content'  => "",
                'post_status'   => 'draft',
                'post_author'   => $user_ID,
                'post_type'=>'egpl_custome_fields'
            );
            $tasksID = wp_insert_post( $taskaObjectData );


            update_post_meta( $tasksID, 'label', $userfieldmeta['fieldName'] );
            update_post_meta( $tasksID, '_egpl_field_type', $userfieldmeta['fieldType'] );
            update_post_meta( $tasksID, '_egpl_link_url', $userfieldmeta['fieldTypeLinkurl'] );
            update_post_meta( $tasksID, '_egpl_link_name', $userfieldmeta['fieldTypeLinkname'] );
            update_post_meta( $tasksID, '_egpl_field_tooltip_text', $userfieldmeta['fieldtooltiptext'] );
            update_post_meta( $tasksID, '_egpl_field_requried_status', $userfieldmeta['fieldrequriedstatus'] );
            update_post_meta( $tasksID, '_egpl_field_system_task', $userfieldmeta['fieldsystemtask'] );
            update_post_meta( $tasksID, '_egpl_field_code', $userfieldmeta['fieldcode'] );
            update_post_meta( $tasksID, '_egpl_field_display_on_application_form', $userfieldmeta['displayonapplicationform'] );
            update_post_meta( $tasksID, '_egpl_field_placeholder', $userfieldmeta['fieldplaceholder'] );
            update_post_meta( $tasksID, 'Indexfield', $userfieldmeta['fieldIndex'] );
            update_post_meta( $tasksID, '_egpl_field_description', $userfieldmeta['fielddescription'] );
            update_post_meta( $tasksID, '_egpl_field_unique_key', $userfieldmeta['fielduniquekey'] );
            update_post_meta( $tasksID, '_egpl_field_allow_multi', $userfieldmeta['fieldallwomultipul'] );
            update_post_meta( $tasksID, '_egpl_field_attribute', $userfieldmeta['attribute'] );
            update_post_meta( $tasksID, '_egpl_field_unique_key', $userfieldmeta['fielduniquekey'] );
            update_post_meta( $tasksID, '_egpl_field_internal_status', $userfieldmeta['SystemfieldInternal'] );
            update_post_meta( $tasksID, 'multiselect', $userfieldmeta['multiselect'] );
            update_post_meta( $tasksID, 'BoothSettingsField', $userfieldmeta['BoothSettingsField'] );
           
            update_post_meta( $tasksID, 'options', $userfieldmeta['options']);
            update_post_meta( $tasksID, '_egpl_field_drop_down_values' , $userfieldmeta['dropdownvalues']);
            

        }

        restore_current_blog();
        return 'success';


    }

    public function getAlltasks($siteId){


        switch_to_blog($siteId);

        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',
            
            );
        $clonedsiteTaskData = get_posts( $args );
        
        $AlltasksArray = [];
        foreach ($clonedsiteTaskData as $taskskey => $tasksObject) {

            $task_code = $tasksObject->ID;
            $tasksID = $tasksObject->ID;
                                    
            $taskmeta = [];
            $taskmeta['value'] = get_post_meta( $tasksID, 'value' , true);
            $taskmeta['unique'] = get_post_meta( $tasksID, 'unique' , true);
            $taskmeta['class'] = get_post_meta( $tasksID, 'class' , true);
            $taskmeta['after'] = get_post_meta( $tasksID, 'after', true);
            $taskmeta['required'] = get_post_meta( $tasksID, 'required' , true);
            $taskmeta['allow_tags'] = get_post_meta( $tasksID, 'allow_tags' , true);
            $taskmeta['add_to_profile'] = get_post_meta( $tasksID, 'add_to_profile' , true);
            $taskmeta['allow_multi'] = get_post_meta( $tasksID, 'allow_multi', true);
            $taskmeta['label'] = get_post_meta( $tasksID, 'label' , true);
            $taskmeta['type'] = get_post_meta( $tasksID, 'type' , true);
            $taskmeta['link_url'] = get_post_meta( $tasksID, 'link_url' , true);
            $taskmeta['linkname'] = get_post_meta( $tasksID, 'linkname', true);
            $taskmeta['duedate'] = get_post_meta( $tasksID, 'duedate', true);
                                    
                                    
            $taskmeta['taskattrs'] = get_post_meta( $tasksID, 'taskattrs', true);
            $taskmeta['taskMWC'] = get_post_meta( $tasksID, 'taskMWC' , true);
            $taskmeta['taskMWDDP'] = get_post_meta( $tasksID, 'taskMWDDP' , true);
            $taskmeta['roles'] = get_post_meta( $tasksID, 'roles' , true);
            $taskmeta['usersids'] = get_post_meta( $tasksID, 'usersids' , true);
            $taskmeta['descrpition'] = get_post_meta( $tasksID, 'descrpition', true);
            $taskmeta['key'] = get_post_meta( $tasksID, 'key', true);
                                    
            $taskmeta['taskCode'] = get_post_meta( $tasksID, 'taskCode', true);
            $taskmeta['SystemTask'] = get_post_meta( $tasksID, 'SystemTask', true);
            $taskmeta['emailnotification'] = get_post_meta( $tasksID, 'emailnotification', true);
            $taskmeta['emailnotificationaddress'] = get_post_meta( $tasksID, 'emailnotificationaddress', true);

                                    //my code Shehroze

            $taskmeta['TaskPosition'] = get_post_meta($tasksID, 'TaskPosition', true);
                                   
            $taskmeta['multiselectstatus'] = get_post_meta( $tasksID, 'multiselectstatus', true);
            $taskmeta['multivaluetasklimit'] = get_post_meta( $tasksID, 'multivaluetasklimit', true);
                                    
                                    
                                    

                                    //my code Shehroze
                                    
                                    $taskmeta['taskposition'] = (int)$value_position;


                                    
                                    if($taskmeta['type'] == "select-2" || $taskmeta['type'] == "multiselect"){
                                        
                                            $getarraysValue = get_post_meta( $tasksID, 'options', false);
                                            
                                            if(!empty($getarraysValue[0])){

                                                
                                                 $taskmeta['options'] =$getarraysValue[0];
                                                 
                                             }
                                   }
            
                                   $AlltasksArray[]= $taskmeta;                    

        }

        restore_current_blog();
        return $AlltasksArray;
    }


    public function removeAllTasks($siteID){

        switch_to_blog($siteID);

        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',
            
            );
        $removedtasklist = get_posts( $args );

        foreach ($removedtasklist as $taskskey => $tasksObject) {

            
            $tasksID = $tasksObject->ID;
            wp_delete_post( $tasksID, true );

        }

        restore_current_blog();
        return 'success';


    }

    public function createAllTasks($siteID,$listofclonedtasks){

        switch_to_blog($siteID);

        $user_ID = get_current_user_id();
        

        foreach ($listofclonedtasks as $taskskey => $tasksmeta) {

            
            $taskaObjectData = array(
                'post_title'    => wp_strip_all_tags( $tasksmeta->label ),
                'post_content'  => "",
                'post_status'   => 'draft',
                'post_author'   => $user_ID,
                'post_type'=>'egpl_custome_tasks'
            );
            $tasksID = wp_insert_post( $taskaObjectData );


            foreach($tasksmeta as $taskskey=>$taskValue){


                update_post_meta( $tasksID, $taskskey, $taskValue );

            }
           

        }

        restore_current_blog();
        return 'success';


    }


    public function getAllResources($siteId){


        switch_to_blog($siteId);

        $args = array(
            'numberposts' => -1,
            'post_type'   => 'avada_portfolio'
          );
           
        $get_all_resources = get_posts( $args );


        
        $AllResourcesArray = [];
        foreach ($get_all_resources as $resourcesIndex => $resourcesValue){



            $resources_download_file_url = get_post_meta( $resourcesValue->ID, 'excerpt', true );
            $resourceTitle = $resourcesValue->post_title;

           
                                    
            $taskmeta = [];
            $taskmeta['title'] = $resourceTitle;
            $taskmeta['resourceUrl'] = $resources_download_file_url;
            
            
            $AllResourcesArray[]= $taskmeta;                    

        }

        restore_current_blog();
        return $AllResourcesArray;
    }


    public function removeAllResources($siteID){

        switch_to_blog($siteID);

        $args = array(
            'numberposts' => -1,
            'post_type'   => 'avada_portfolio'
          );
           
        $get_all_resources = get_posts( $args );


       

        foreach ($get_all_resources as $resourcesIndex => $resourcesValue){

            
            wp_trash_post($resourcesValue->ID);
          

        }

        restore_current_blog();
        return 'success';


    }



    public function createAllResources($siteID,$listofclonedResources){

        //switch_to_blog($siteID);

        $user_ID = get_current_user_id();
        
       

        foreach ($listofclonedResources as $resourcekey => $resourcemeta) {

            
           
            $my_post = array(
                'post_title' => $resourcemeta['title'],
                'post_date' => '',
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'avada_portfolio',
                  
             );
             $post_id = wp_insert_post( $my_post );
             



               if ($post_id) {
                   // insert post meta
                   $result = add_post_meta($post_id, 'excerpt', $resourcemeta['resourceUrl']);
                   //return $result;
               }
             

        }

        //restore_current_blog();
        return 'success';


    }







    public function getAllproducts($siteId){

       
        switch_to_blog($siteId);
        global $wpdb;


        require_once('temp/lib/woocommerce-api.php');

        $options = array(
            'debug' => true,
            'return_as_array' => false,
            'validate_url' => false,
            'timeout' => 30,
            'ssl_verify' => false,
        );

        $url = get_site_url();
        $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
        $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
        $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
        $woocommerce_object = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
        $all_products= $woocommerce_object->products->get( '', ['filter[limit]' => -1,'filter[post_status]' => 'any']);
        $product_cat_list = $woocommerce_object->products->get_categories() ;
        
        $AllproductArray = [];


        

        foreach ($all_products->products as $single_product) {


            
            $product_id = $single_product->id;
            
            $update_product = wc_get_product( $product_id );
            $getproduct_detail = $woocommerce_object->products->get( $product_id );
            
           

            if($getproduct_detail->product->categories[0] == 'Packages' || $getproduct_detail->product->categories[0] == 'Add-ons' || $getproduct_detail->product->categories[0] == 'Package' || $getproduct_detail->product->categories[0] == 'Add-on'){
                
                $prodcutmeta = [];

                $url = wp_get_attachment_thumb_url($update_product->image_id);
                $prodcutmeta['productlevel'] = get_post_meta($product_id, "productlevel",true);
                $prodcutmeta['wc_deposit_type'] = get_post_meta($product_id, "_wc_deposit_type",true);
                $prodcutmeta['wc_deposit_amount'] = get_post_meta($product_id, "_wc_deposit_amount",true);
                $prodcutmeta['alg_wc_pvbur_visible'] = get_post_meta($product_id, "_alg_wc_pvbur_visible",true);
                $prodcutmeta['alg_wc_pvbur_uservisible'] = get_post_meta($product_id, "_alg_wc_pvbur_uservisible",true);
                $prodcutmeta['wc_deposit_enabled'] = get_post_meta($product_id, "_wc_deposit_enabled",true);
                $prodcutmeta['list_of_selected_booth'] = get_post_meta( $product_id, '_list_of_selected_booth', true);
                
                $prodcutmeta['regular_price'] = $update_product->regular_price;
                $prodcutmeta['status'] = $update_product->status;
                $prodcutmeta['stock_status'] = $update_product->stock_status;
                $prodcutmeta['pstock_quantity'] = $update_product->stock_quantity;
                $prodcutmeta['imageurl'] = $url;
                $prodcutmeta['menu_order'] = $update_product->menu_order;
                $prodcutmeta['short_description'] = $update_product->short_description;
                $prodcutmeta['description'] = $update_product->description;
                 
                $prodcutmeta['seletedtaskKeys'] = get_post_meta($product_id, "seletedtaskKeys",true);
                $selectedtaskids = get_post_meta($product_id, "seletedtaskKeys",true);
                $prodcutmeta['listoftasksnames'] = "";

               
                if(!empty($selectedtaskids)){


                    foreach($selectedtaskids['selectedtasks'] as $index=>$taskid){

                        $label =  get_post_meta( $taskid, 'label' , true);
                        $listoflabels[$index] = $label ;
                    }

                    $prodcutmeta['listoftasksnames'] = $listoflabels;
                }


                $prodcutmeta['title'] = $update_product->name;
               

                foreach ($product_cat_list->product_categories as $cat_key=>$cat_value){
           
                    if($cat_value->name == $getproduct_detail->product->categories[0]){
                        
                        $prodcutmeta['catgories']  = $cat_value->id;
                        
                    }
                }
                
                $AllproductArray[]= $prodcutmeta;   
            }                 

        }

        restore_current_blog();
        return $AllproductArray;
    }


    public function removeAllProducts($siteID){

        switch_to_blog($siteID);
        global $wpdb;


        require_once('temp/lib/woocommerce-api.php');

        $options = array(
            'debug' => true,
            'return_as_array' => false,
            'validate_url' => false,
            'timeout' => 30,
            'ssl_verify' => false,
        );

        $url = get_site_url();
        $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
        $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
        $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
        $woocommerce_object = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
        $all_products= $woocommerce_object->products->get( '', ['filter[limit]' => -1,'filter[post_status]' => 'any']);
        

        

        foreach ($all_products->products as $single_product) {


           
            $product_id = $single_product->id;
            $update_product = wc_get_product( $product_id );
            $getproduct_detail = $woocommerce_object->products->get( $product_id );

            if($getproduct_detail->product->categories[0] == 'Packages' || $getproduct_detail->product->categories[0] == 'Add-ons' || $getproduct_detail->product->categories[0] == 'Package' || $getproduct_detail->product->categories[0] == 'Add-on'){

                wp_trash_post($product_id);
                
                
            }
        }

        restore_current_blog();
        return 'success';


    }

    public function createAllProducts($siteID,$listofclonedproducts){

       
        switch_to_blog($siteID);

        $user_ID = get_current_user_id();
        
        
        foreach ($listofclonedproducts as $productkey => $productmeta) {



            if($productmeta['stock_status'] == 'instock'){
                $instock = true;
            }else{
                $instock=false;
            }

           

            $objProduct = new WC_Product();
            $objProduct->set_name($productmeta['title']);
            $objProduct->set_short_description($productmeta['short_description']);
            $objProduct->set_stock_quantity($productmeta['pstock_quantity']);
            $objProduct->set_stock_status($instock);
            $objProduct->set_menu_order($productmeta['menu_order']);

            $objProduct->set_status($productmeta['status']); 
            $objProduct->set_featured(TRUE);
            $objProduct->set_catalog_visibility('visible');
            $objProduct->set_description($productmeta['description']);
        
            $objProduct->set_price($productmeta['regular_price']);
            $objProduct->set_regular_price($productmeta['regular_price']);
      
            $objProduct->set_manage_stock(TRUE);
            $objProduct->set_backorders('no');
            $objProduct->set_sold_individually(FALSE);
           
            if(!empty($productmeta['wc_deposit_type']) && !empty($productmeta['wc_deposit_amount'])){
            
              
             
            
                $objProduct->update_meta_data('_wc_deposit_type', $productmeta['wc_deposit_type']);
                $objProduct->update_meta_data('_wc_deposit_amount', $productmeta['wc_deposit_amount']);
                $objProduct->update_meta_data('_wc_deposit_enabled', $productmeta['wc_deposit_enabled']);
                
            }
        
            if(!empty($productmeta['productlevel'])){
            
          
                $objProduct->update_meta_data('productlevel', $productmeta['productlevel']);
       
            }else{
               
               $objProduct->update_meta_data('productlevel', "");
            }
   
            if(!empty($productmeta['alg_wc_pvbur_visible'])){


                $objProduct->update_meta_data('_alg_wc_pvbur_visible', $productmeta['alg_wc_pvbur_visible']);

            }else{
                
                $objProduct->update_meta_data('_alg_wc_pvbur_visible', "");
            }
    
            if(!empty($productmeta['alg_wc_pvbur_uservisible'])){


                $objProduct->update_meta_data('_alg_wc_pvbur_uservisible', $productmeta['alg_wc_pvbur_uservisible']);

            }else{
        
                $objProduct->update_meta_data('_alg_wc_pvbur_uservisible', "");
            }
   
            $objProduct->set_reviews_allowed(TRUE); 

            $term_ids =[$productmeta['catgories']];
            $objProduct->set_category_ids($term_ids); //Set the product categories.                   | array $term_ids List of terms IDs.
            $objProduct->set_tag_ids($term_ids); //Set the product tags.                              | array $term_ids List of terms IDs.
            
            if ( !function_exists( 'download_url' ) ) { 
                require_once ABSPATH . '/wp-admin/includes/file.php'; 
            } 
              
          
            
            $uploadimgID = $this->upload_file_wp($productmeta['imageurl']);

           
            $objProduct->set_image_id($uploadimgID); //Set main image ID.                                         | int|string $image_id Product image id.
        
            $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.
            update_post_meta( $new_product_id, '_list_of_selected_booth', $productmeta['list_of_selected_booth'] );
            update_post_meta( $new_product_id, 'min_quantity', 1 );
            update_post_meta( $new_product_id, 'max_quantity', 1 );
        

            $productmeta['seletedtaskKeys']['selectedtasks'] = [];
        
            if(!empty($productmeta['listoftasksnames'])){

                foreach($productmeta['listoftasksnames'] as $tasksindex=>$taskslabel){


                    $taskid = $this->getcurrenttaskid($taskslabel);

                    if($taskid != "notexist" ){

                        $productmeta['seletedtaskKeys']['selectedtasks'][] = $taskid;
                    }

                }

                
                

                update_post_meta( $new_product_id, 'seletedtaskKeys', $productmeta['seletedtaskKeys']);
            }

           

        }

        restore_current_blog();
        return 'success';


    }


    public function removeallusers($siteId){


        switch_to_blog($siteId);

        $args['role__not_in']= 'Administrator';
        $user_query = new WP_User_Query( $args );
        $authors = $user_query->get_results();


        foreach ($authors as $userobjectId) {

            $userId =  $userobjectId->ID;
            
           
            remove_user_from_blog($userId, $siteId,1); 

        }


        restore_current_blog();
        return 'success';
    }


    public function getAllUsers($siteId){


        switch_to_blog($siteId);

        $args['role__not_in']= 'Administrator';
        $user_query = new WP_User_Query( $args );
        $authors = $user_query->get_results();

        $blog_id = get_current_blog_id();
        $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
        $all_roles = get_option($get_all_roles_array);

        
        $AllUserData = [];
        foreach ($authors as $userobjectId) {

            $userId =  $userobjectId->ID;
                                    
            $usermeta = [];
            $user_data = get_userdata($userId);

            $usermeta['ID'] = $userId;
            $usermeta['first_name'] = get_user_option( 'first_name' , $userId);
            $usermeta['last_name'] = get_user_option('last_name' , $userId);
            $usermeta['company_name'] = get_user_option( 'company_name' , $userId);
            $usermeta['Semail'] = get_user_option('Semail', $userId);
            $usermeta['level'] = $all_roles[$user_data->roles[0]]['name'];
            $usermeta['levelkey'] = $user_data->roles[0];

            $AllUserData[]= $usermeta;                    

        }



        
        restore_current_blog();
        return $AllUserData;
    }


    public function createAllusers($siteID,$userdata){

        switch_to_blog($siteID);

        $user_ID = get_current_user_id();
        
        $listofalllevels = $this->listofcurretnsitelevels();

        
       
       
        foreach ($userdata as $userkey => $usermeta) {

            
            //$responce = $this->checkuserlevel($siteID,$usermeta['level']);
           
            

            
            if (!in_array($usermeta['levelkey'], $listofalllevels)){

                $usermeta['levelkey'] = "subscriber";

            }

           

            if (add_user_to_blog($siteID, $usermeta['ID'], $usermeta['levelkey'])) {
                
                $leavel =[];
                $leavel[strtolower($usermeta['levelkey'])] = 1;
                update_user_option($usermeta['ID'], 'first_name', $usermeta['first_name']);
                update_user_option($usermeta['ID'], 'last_name', $usermeta['last_name']);
                update_user_option($usermeta['ID'], 'company_name', $usermeta['company_name']);
                update_user_option($usermeta['ID'], 'Semail', $usermeta['Semail']);
                update_user_option($usermeta['ID'], 'capabilities',  $leavel);

            }
            



            //$user_data = get_userdata($usermeta['ID']);

           


        }

        restore_current_blog();
        return 'success';


    }


   


    function uploadImage($url) {

        // Gives us access to the download_url() and wp_handle_sideload() functions
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
      
        // Download file to temp dir
        $timeout_seconds = 10;
        $temp_file = download_url( $url, $timeout_seconds );
      
        if ( !is_wp_error( $temp_file ) ) {
      
            // Array based on $_FILE as seen in PHP file uploads
            $file = array(
                'name'     => basename($url), // ex: wp-header-logo.png
                'type'     => 'image/png',
                'tmp_name' => $temp_file,
                'error'    => 0,
                'size'     => filesize($temp_file),
            );
      
            $overrides = array(
                // Tells WordPress to not look for the POST form
                // fields that would normally be present as
                // we downloaded the file from a remote server, so there
                // will be no form fields
                // Default is true
                'test_form' => false,
      
                // Setting this to false lets WordPress allow empty files, not recommended
                // Default is true
                'test_size' => true,
            );
      
            // Move the temporary file into the uploads directory
            $results = wp_handle_sideload( $file, $overrides );
            
            
            if ( !empty( $results['error'] ) ) {
                // Insert any error handling here
            } else {
      
               
                $url = $results['url'];
                $type = $results['type'];
                $file = $results['file'];
                $title = sanitize_text_field( $name );
                $content = '';
                $excerpt = '';
                
                $attachment = array(
                      'post_mime_type' => $type,
                      'guid' => $url,
                      'post_parent' => '',
                      'post_title' => $title,
                      'post_content' => $content,
                      'post_excerpt' => $excerpt,
                  );
          
          
        
       
         
          // Save the data
          $id = wp_insert_attachment( $attachment, $file, '', true );
          require_once( ABSPATH . 'wp-admin/includes/image.php' );
      
      // Generate the metadata for the attachment, and update the database record.
          $attach_data = wp_generate_attachment_metadata( $id, $file );
          wp_update_attachment_metadata( $id, $attach_data );
                
                
                
      
                return $id;
            }
        }
      }    
      
    
     public function getAllpages($siteID){

        switch_to_blog($siteID);

        $args = array(
            'post_type' => 'page',
            'category_name'    => 'Content Manager Editor',
            'posts_per_page'   => -1,
            'order'            => 'ASC',
            
        );
        $loop = new WP_Query( $args );

       
        $AllpagesData = [];

        while ( $loop->have_posts() ) : $loop->the_post();

            global $post;
            $slug =  $post->post_name;
            $postid = get_the_ID();
            $title = get_the_title();
            $page_data = get_page($postid);
            $content = $page_data->post_content;
            $pagetemplate = get_post_meta($postid, '_wp_page_template');
            if($slug == "user-report" || $slug == "edit-user" || $slug == "edit-task" || $slug == "all-resources" || $slug == "create-user" || $slug == "create-resource" || $slug == "home" || $slug == "faqs" || $slug == "floor-plan" || $slug == "resources" || $slug == "cart" || $slug == "my-sites" || $slug == "my-sites-2" || $slug == "logout" || $slug == "change-password" || $slug == "change-password-2") {
            }else{    
                $pagemeta = [];
                $pagemeta['ID'] = $postid;
                $pagemeta['title'] = $title;
                $pagemeta['content'] = $content;
                $pagemeta['slug'] = $slug;
                $pagemeta['pagetemplate'] = $pagetemplate;
                $AllpagesData[]= $pagemeta;
            }   
        
        endwhile;
        

        restore_current_blog();

        return $AllpagesData;

     }

     public function getAllmenuitems($siteID){

        switch_to_blog($siteID);

        $menu_name = 'primary';
        $locations = get_nav_menu_locations();
        $menu = wp_get_nav_menu_object($locations[$menu_name]);
        $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
        $AllmenupagesData = [];
        


        foreach ($menuitems as $item){


            $menumeta = [];
            $link = $item->url;
            $title = $item->title;
            $menu_item_id = $item->ID;
            
            $pagevisibility = get_post_meta($item->ID, 'page_visibility', true);
            $itemtype = get_post_meta($item->ID, 'page_type', true); 
            $addons_enabled = get_post_meta($item->ID, 'addon_enabled', true); 

           
            $menumeta['link'] = $link;
            $menumeta['title'] = $title;
            $menumeta['itemID'] = $menu_item_id;
            $menumeta['page_visibility'] = $pagevisibility;

            
           

            if($itemtype == 'customlink'){
               
                $menumeta['page_type'] = 'customlink';

            }else{

                
                $menumeta['page_type'] = $itemtype;
            }
            
            $menumeta['addon_enabled'] = $addons_enabled;
            $menumeta['menu_item_parent'] = $item->menu_item_parent;
            $menumeta['menu_order'] = $item->menu_order;

            if($menumeta['page_type'] != "customlink" ){

               
                $pageslug = basename(parse_url($link, PHP_URL_PATH));
                $menumeta['linkedpageslug'] = $pageslug;

            }else{

                $menumeta['linkedpageslug'] = '';
            }

            if($link == "#"){

               
                

                $childmenuitem = [];
                foreach ($menuitems as $items){
                   

                    if($item->ID == $items->menu_item_parent){

                        
                        
                        $pagevisibilitys = get_post_meta($items->ID, 'page_visibility', true);
                        $itemtypes = get_post_meta($items->ID, 'page_type', true); 
                        $addons_enableds = get_post_meta($items->ID, 'addon_enabled', true); 

                        $childmenuitem['link'] = $items->url;
                        $childmenuitem['title'] = $items->title;
                        $childmenuitem['itemID'] = $items->ID;
                        $childmenuitem['page_visibility'] = $pagevisibilitys;
                        $childmenuitem['menu_item_parent'] = $items->menu_item_parent;
                        $childmenuitem['menu_order'] = $items->menu_order;
                        $childmenuitem['addon_enabled'] = $addons_enableds;
                       
                        if($itemtypes == "page" && $itemtypes != ""){

               
                            $pageslugs = basename(parse_url($items->url, PHP_URL_PATH));
                            $childmenuitem['linkedpageslug'] = $pageslugs;
                            $childmenuitem['page_type'] = $itemtype;
            
                        }else{
            
                            $childmenuitem['linkedpageslug'] = '';
                            $childmenuitem['page_type'] = 'customlink';
                        }
                        $menumeta['children'][] = $childmenuitem;
                    }
                   
                }

                
            }

            //echo '<pre>';
            //print_r($menumeta);

            if(empty($menumeta['menu_item_parent']) || $menumeta['menu_item_parent'] == 'undefined'){
                $AllmenupagesData[] = $menumeta;
            }

        }



        restore_current_blog();

        return $AllmenupagesData;

    }

    public function removeAllmenu($siteID){

        switch_to_blog($siteID);

       

        $menu_name = 'primary';
        $locations = get_nav_menu_locations();
        $menu = wp_get_nav_menu_object($locations[$menu_name]);
        $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));

        foreach ($menuitems as $item){

           
            $menu_item_id = $item->ID;
            wp_delete_post( $menu_item_id, true );

        }

        restore_current_blog();
        return 'success';


    }



    public function createAllpages($siteID,$listofallpages){

        switch_to_blog($siteID);

        
        $term = term_exists('Content Manager Editor', 'category');
        $cat_id_get = $term['term_id'];
        $cat_name = array($cat_id_get);
        


        foreach ($listofallpages as $pageskey => $pagesmeta) {

            $page_path = $pagesmeta['slug'];
            $page = get_page_by_path($page_path);
            $cat_name = array($cat_id_get);

            
            
            if (empty($page)) {

                $my_post = array(
                    'post_title' => wp_strip_all_tags($pagesmeta['title']),
                    'post_status' => 'publish',
                    'post_author' => get_current_user_id(),
                    'post_content'=> wp_strip_all_tags($pagesmeta['content']),
                    'post_category' => $cat_name ,//'content-manager-editor',
                    'post_type' => 'page',
                    'post_name' => $page_path
                );

                $returnpage_ID = wp_insert_post($my_post);
               

               

                update_post_meta($returnpage_ID, '_wp_page_template', $pagesmeta['pagetemplate'][0]);

               

            }else{


                $my_post = array(
                    'ID'           => $page->ID,
                    'post_title'   => wp_strip_all_tags($pagesmeta['title']),
                    'post_content' => wp_strip_all_tags($pagesmeta['content']),
                );
                  
                wp_update_post( $my_post, true );	

                
            }
        
        }
        

        restore_current_blog();

        return 'success';

     }

    public function createAllmenuitems($siteID,$listofallmenuitems){

        switch_to_blog($siteID);

        $menu_name = 'primary';
	    $locations = get_nav_menu_locations();
	    $menu = wp_get_nav_menu_object($locations[$menu_name]);
	    $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
        $main_menu_id = $menu->term_id;


       //echo '<pre>';
       //print_r($listofallmenuitems);




        foreach ($listofallmenuitems as $menukey => $menumeta) {

           
                $parentID = "";
                $parentID = $this->createSingleMenu($menumeta,$parentID,$main_menu_id);

                if(!empty($menumeta['children'])){

                    foreach($menumeta['children'] as $childkey=>$childmenudata){


                        $this->createSingleMenu($childmenudata,$parentID,$main_menu_id);

                    }

                }

                

        }

        restore_current_blog();

        return 'success';
        

    }
     

    public function createSingleMenu($menuarray,$parentID,$main_menu_id){

        if(empty($parentID)){

            $parentID = $menuarray['menu_item_parent'];
        }

        
        

      

        if($menuarray['page_type'] == 'customlink'){

            $createdmenuid = wp_update_nav_menu_item($main_menu_id, 0, array(
                'menu-item-title' => $menuarray['title'],
                'menu-item-type' => 'custom',
                'menu-item-url' => $menuarray['link'], 
                'menu-item-position'  => $menuarray['menu_order'],
                'menu-item-status' => 'publish'));

           

        }else{

          
            $page = get_page_by_path($menuarray['linkedpageslug']);

            $argu = array(
                'menu-item-title' => $menuarray['title'],
                'menu-item-object' => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type' => 'post_type',
                'menu-item-url' => home_url( '/'.$page->post_name.'/' ), 
                'menu-item-position'  => $menuarray['menu_order'],
                'menu-item-parent-id' => $parentID,
                'menu-item-status' => 'publish');

              
           

            $createdmenuid = wp_update_nav_menu_item($main_menu_id, 0, $argu);
            
        }

                update_post_meta($createdmenuid, 'page_visibility', $menuarray['page_visibility']);
                update_post_meta($createdmenuid, 'page_type', $menuarray['menu_order']);
                update_post_meta( $createdmenuid, '_menu_item_menu_item_parent', $parentID);
                update_post_meta( $createdmenuid, 'menu_item_parent', $parentID);
                update_post_meta($createdmenuid, 'addon_enabled', $menuarray['addon_enabled']);

        return $createdmenuid;
    }

    public function validateusers($validationrules){

        $siteID = $this->$clonesiteID;
        $validateresult = $this->getAllUsers($siteID);

        $get_all_roles_array = 'wp_'.get_current_blog_id().'_user_roles';
        $get_all_roles = get_option($get_all_roles_array);



        $message = "Users have dependencies on Levels which are not part of the current selection.";
        foreach($validateresult as $userkey=>$userdata){

           
            foreach ($get_all_roles as $key => $item) {

                if ($item['name'] == $userdata['level']) {
                   
                    $message = "success";

                }

            }




        }
        
       
        return $message;


    }

   

    

    public function validatetasks($validationrules){

        $gettasksList = $this->getAlltasks($this->$clonesiteID);
        $listofalllevels = $this->listofcurretnsitelevels();
        $currentsiteID = get_current_blog_id();

        $userresult  = [];


        foreach ($gettasksList as $taskkey => $taskmeta) {

            $taskroleslist = $taskmeta['roles'];
            $taskuserslist = $taskmeta['usersids'];

            
            if(!empty($taskroleslist) && ($validationrules['levels'] != 'checked' && $validationrules['levels'] != 'checked-add')){
            foreach($taskroleslist as $roleindex=>$rolekey){
                if($rolekey != 'all' && $rolekey != 'contentmanager'){
                        
                    if (!in_array($rolekey, $listofalllevels)){

                        $userresult['level'] = "missinglevels";

                    }
                }
            }}
            if(!empty($taskuserslist) && ($validationrules['users'] != 'checked' && $validationrules['users'] != 'checked-add')){

                foreach($taskuserslist as $userindex=>$userID){

                    $sites   = get_blogs_of_user($userID);
                    $userresult['users'] = "missingusers.";
                    foreach($sites as $sitesIndex=>$sitesdata){

                        if($currentsiteID == $sitesdata->userblog_id){

                            $userresult['users'] = "success";

                        }

                         

                    }
                }
            }


        }
        if(empty($userresult)){

            $userresult = 'success';
        }
        return $userresult;
        
    }

    public function validateshop($validationrules){

        $siteID = $this->$clonesiteID;
        $allproductslist = $this->getAllproducts($siteID);
        $currentsiteID = get_current_blog_id();
        $listofalllevels = $this->listofcurretnsitelevels();

        foreach ($allproductslist as $productkey => $productmeta) {


            $listoflevele = $productmeta['productlevel'];
            $userslistofvisable = $productmeta['alg_wc_pvbur_visible'];
            $visableuserslist = $productmeta['alg_wc_pvbur_uservisible'];
            $selectedboothlist = $productmeta['list_of_selected_booth'];
            $taskslist = $productmeta['seletedtaskKeys'];
            $message = "Some Levels are missing associate with prodcuts.";

            if(!empty($listoflevele) && ($validationrules['levels'] != 'checked' && $validationrules['levels'] != 'checked-add')){

                if (in_array($listoflevele, $listofalllevels)){


                }else{

                    $userresult['level'] = "missinglevels";
                }

            }
            
           

           
            if(!empty($visableuserslist) && ($validationrules['users'] != 'checked-add' && $validationrules['users'] != 'checked')){

               


                foreach($visableuserslist as $userkey=>$userID){
                   
                    $sites   = get_blogs_of_user($userID);

                    //$userresult['users'][$userkey]['msg'] = "missingusers";
                    foreach($sites as $sitesIndex=>$sitesdata){

                        if($currentsiteID == $sitesdata->userblog_id){

                            $userresult['users'][$userkey] = "success";

                        }else{

                            $userresult['users'] = "missingusers";
                        }

                         

                    }

                }

            }
            if(!empty($taskslist['selectedtasks']) && $validationrules['tasks'] != 'checked'){

                foreach($taskslist['selectedtasks'] as $taskkey=>$taskID){

                    $result = get_post_meta( $taskID, 'value' , true);
                   
                    if(empty($result)){

                        $userresult['tasks'] = "missingtasks";

                    }

                }

            }
            if(!empty($userslistofvisable) && ($validationrules['levels'] != 'checked' && $validationrules['levels'] != 'checked-add')){
                
                foreach($userslistofvisable as $levelindex=>$levelkey){
                    
                    if($levelkey != 'all' && $levelkey != 'contentmanager'){
                    
                        if (!in_array($levelkey, $listofalllevels)){

                            $userresult['level'] = "missinglevels";

                        }
                    }

                }

            }

            if(!empty($selectedboothlist) && $validationrules['florrplan'] != 'checked'){
                
               
                $userresult['floorplan'] = "missingbooths";

            }

            


        }

        if(empty($userresult)){

            $userresult = 'success';
        }

        return $userresult;

        

        
    }

    public function validatefloorplan($validationrules){

        switch_to_blog($this->$clonesiteID);
        $floorPlanSettings = get_option("floorPlanSettings");
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
        $ClonedFloorplanID = $contentmanager_settings['ContentManager']['floorplanactiveid'];
        $sellboothsjson    = json_decode(get_post_meta( $ClonedFloorplanID, 'sellboothsjson', true ));

        restore_current_blog();

        $listofalllevels = $this->listofcurretnsitelevels();

      

        foreach($sellboothsjson as $productindex=>$productmeta){

         
            if(!empty($productmeta->boothlevel) && ($validationrules['levels'] != 'checked' && $validationrules['levels'] != 'checked-add')){
                
              
                if (!in_array($productmeta->boothlevel, $listofalllevels)){

                    $userresult = "Floorplan booths have dependencies on Levels which are not part of the current selection.";
                    break;
                }
            }


        }

        if(empty($userresult)){

            $userresult = 'success';
        }

        return $userresult;

    }

    public function validatereports($validationrules){


        if($validationrules['userfields'] != 'checked' && $validationrules['tasks'] != 'checked'){

            $userresult = "Reports have dependencies on Users Fields and Users Tasks which are not part of the current selection.";

        }else if($validationrules['userfields'] != 'checked' ){


            $userresult = "Reports have dependencies on Users Fields which are not part of the current selection.";


        }else if($validationrules['tasks'] != 'checked'){


            $userresult = "Reports have dependencies on Users Tasks which are not part of the current selection.";


        }

        if(empty($userresult)){

            $userresult = 'success';
        }

        return $userresult;

    }

    public function listofcurretnsitelevels(){

        //switch_to_blog($this->$currentsiteID);
        $get_all_roles_array = 'wp_'.get_current_blog_id().'_user_roles';
        $get_all_roles = get_option($get_all_roles_array);
        $listofArray = [];
        foreach ($get_all_roles as $key => $item) {

            $listofArray[] = $key;

        }

        return $listofArray;

    }
    

    public function cleanfloorplanxml(){

        $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
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

          
           

            $att = "boothOwner";
            $styleatt = 'style';
                
            $owenerid = $xml->root->MyNode[$currentIndex]->attributes()->$att;
           
            if($owenerid != 'none'){

                $getCellStyle = $getCellStylevalue['style'];
                $xml->root->MyNode[$currentIndex]->attributes()->$att = 'none';
                if(isset($cellboothlabelvalue['legendlabels']) && !empty($cellboothlabelvalue['legendlabels'])){

                    
                    $getlabelID = ''.$cellboothlabelvalue['legendlabels'];
                    
                    foreach ($boothTypesLegend as $boothlabelIndex=>$boothlabelValue){
                        if($boothlabelValue->ID ==  $getlabelID){

                            $createdproductPrice = $boothlabelValue->colorcode;
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

                            }else{

                                    $getCellStyleArray = explode(';',$getCellStyle);
                                    foreach ($getCellStyleArray as $styleIndex=>$styleValue){


                                        if($styleValue != 'DefaultStyle1'){

                                            $styledeepCheck = explode('=',$styleValue);

                                            if($styledeepCheck[0] == 'uno'){

                                                $NewfillColor = $styledeepCheck[1];

                                            }else if($styledeepCheck[0] == 'fillColor'){

                                                $oldfillcolortext = $styleValue;
                                            }


                                        }


                                    }
                            }
                        }
                       
                    }
        
                }else{
            
                       
                        $getCellStyleArray = explode(';',$getCellStyle);

                        

                        foreach ($getCellStyleArray as $styleIndex=>$styleValue){
                            if($styleValue != 'DefaultStyle1'){
                                $styledeepCheck = explode('=',$styleValue);
                                if($styledeepCheck[0] == 'uno'){
                                    $NewfillColor = $styledeepCheck[1];
                                }else if($styledeepCheck[0] == 'fillColor'){
                                    $oldfillcolortext = $styleValue;
                                }
                            }
                        }

                       
                }

               
                $getCellStyle = str_replace($oldfillcolortext,'fillColor='.$NewfillColor,$getCellStyle);
                $xml->root->MyNode[$currentIndex]->mxCell->attributes()->$styleatt = $getCellStyle;
                
            }
            $currentIndex++;

            
        }

        $getresultforupdat = str_replace('<?xml version="1.0"?>',"",$xml->asXML());
        update_post_meta( $foolrplanID, 'floorplan_xml', json_encode($getresultforupdat));
          
    }

    
    function upload_file_wp($file_url) {

        if (!filter_var($file_url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $contents = @file_get_contents($file_url);
        if ($contents === false) {
            return false;
        }
        $upload = wp_upload_bits(basename($file_url), null, $contents);
        if (isset($upload['error']) && $upload['error']) {
            return false;
        }
        $type = '';
        if (!empty($upload['type'])) {
            $type = $upload['type'];
        } else {
            $mime = wp_check_filetype($upload['file']);
            if ($mime) {
                $type = $mime['type'];
            }
        }
        $attachment = array('post_title' => basename($upload['file']), 'post_content' => '', 'post_type' => 'attachment', 'post_mime_type' => $type, 'guid' => $upload['url']);
        $id = wp_insert_attachment($attachment, $upload['file']);
        //wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $upload['file']));
        return $id;
    
    }
        
    
    public function getcurrenttaskid($taskslable){


        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',
            
            );
        $clonedsiteTaskData = get_posts( $args );
        
      
        foreach ($clonedsiteTaskData as $taskskey => $tasksObject) {

            
            $tasksID = $tasksObject->ID;
                                    
           
            $tasktitle = get_post_meta( $tasksID, 'label' , true);
            if($tasktitle == $taskslable){


                return $tasksID;
            }


        }

        return 'notexist';



    }
}