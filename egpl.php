<?php


/**
 * Plugin Name:       EGPL
 * Plugin URI:        https://github.com/QasimRiaz/EGPL
 * Description:       EGPL
 * Version:           6.09
 * Author:            EG
 * License:           GNU General Public License v2
 * Text Domain:       EGPL
 * Network:           true
 * GitHub Plugin URI: https://github.com/QasimRiaz/EGPL
 * Requires WP:       5.0.3
 * Requires PHP:      7.2
 * Date 11/02/2019
 */

//get all the plugin settings
//get all the plugin settings
if($_GET['contentManagerRequest'] == "bulkimportmappingcreaterequest") {        
    require_once('../../../wp-load.php');
    
    $importfileurl = $_POST['uploadedsheeturl'];
    $col_mapping_datarray = json_decode(stripslashes($_POST['mappingfielddata']), true);
    $welcome_email_status = $_POST['welcomeemailstatus'];
    $welcome_email_template_name = $_POST['seletwelcomeemailtemplate'];
    
    $responce_createdusers = createuserlist_after_mapping($importfileurl,$col_mapping_datarray,$welcome_email_status,$welcome_email_template_name);
     echo json_encode($responce_createdusers);
    die();
   
  
}else if($_GET['contentManagerRequest'] == "getuseremailids") {        
    require_once('../../../wp-load.php');
    $fields = array( 'ID','user_email' );
    $args = array(
        'role__not_in' => array('administrator'),
        'fields' => $fields,
    );
     
    
     $get_all_ids = get_users($args);
    
    $indexplus = 0;
    
    foreach ($get_all_ids as $user) {
        
            $getuserresult[$indexplus]['id'] = $user->ID;
            $getuserresult[$indexplus]['text'] = $user->user_email;
            $indexplus++;
    }
    echo json_encode($getuserresult);
    die();
   
  
}else if($_GET['contentManagerRequest'] == "checkwelcomealreadysend") {        
    require_once('../../../wp-load.php');
    
    checkwelcomealreadysend($_POST);
   
  
}else if($_GET['contentManagerRequest'] == "changeuseremailaddress") {        
    require_once('../../../wp-load.php');
    
    changeuseremailaddress($_POST);
   
  
}else if($_GET['contentManagerRequest'] == "editrolekey") {        
    require_once('../../../wp-load.php');
    
    editrolename($_POST);
   
  
}else if($_GET['contentManagerRequest'] == "roleassignnewtasks") {        

    require_once('../../../wp-load.php');
    
    roleassignnewtasks($_POST);
   
  
}else if ($_GET['contentManagerRequest'] == 'insertmapdynamicsuser') {
    
     require_once('../../../wp-load.php');
     try{
        
     
     
      
        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Insert Map Dynamics User',"Admin Action","",$user_ID,$user_info->user_email,"pre_action_data");
        $site_prefix = $wpdb->get_blog_prefix();
        $userid = $_POST['userid'];
        $requestcount = $_POST['requestcount'];
        $userdata = get_userdata($userid);
        $all_meta_for_user = get_user_meta($userid);
       
        
        
        
        
        if(!empty($all_meta_for_user[$site_prefix.'exhibitor_map_dynamics_ID'][0])){
            
            $data_array=array(
            'company'=>$all_meta_for_user[$site_prefix.'company_name'][0],
            'email'=>$userdata->user_email,
            'first_name'=>$all_meta_for_user[$site_prefix.'first_name'][0],
            'last_name'=>$all_meta_for_user[$site_prefix.'last_name'][0],
            'image'=>$all_meta_for_user[$site_prefix.'user_profile_url'][0],
            'exhibitor_id'=>$all_meta_for_user[$site_prefix.'exhibitor_map_dynamics_ID'][0]  
            ) ;
            $result = update_exhibitor_map_dynamics($data_array);
            if($result->status == 'success'){
            
             $data_array['status'] = $result->status;
             $data_array['result'] = '';
             $data_array['Exhibitor_ID'] = $result->results->Exhibitor_ID;
             
            
         
            }else{
                $data_array['status'] = $result->status;
                $data_array['result'] = $result->status_details;
                $data_array['Exhibitor_ID'] = '';
            }
            
        
        }else{
            
           $data_array=array(
            'company'=>$all_meta_for_user[$site_prefix.'company_name'][0],
            'email'=>$userdata->user_email,
            'first_name'=>$all_meta_for_user[$site_prefix.'first_name'][0],
            'last_name'=>$all_meta_for_user[$site_prefix.'last_name'][0],
            'image'=>$all_meta_for_user[$site_prefix.'user_profile_url'][0],
            
          ) ;
           $result = insert_exhibitor_map_dynamics($data_array);
           
           if($result->status == 'success'){
            
             $data_array['status'] = $result->status;
             $data_array['result'] = '';
             $data_array['Exhibitor_ID'] = $result->results->Exhibitor_ID;
             
             update_user_option($userdata->ID, 'exhibitor_map_dynamics_ID', $result->results->Exhibitor_ID);
         
            }else{
                
                $data_array['status'] = $result->status;
                $data_array['result'] = $result->status_details;
                $data_array['Exhibitor_ID'] = '';
            }
        }
        
      $data_array['requestcount'] =  $requestcount;
      
      contentmanagerlogging_file_upload ($lastInsertId,serialize($result)); 
      echo json_encode($data_array);
      die();
        
        
    }catch (Exception $e) {
       
      contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();   
    
}else if ($_GET['contentManagerRequest'] == 'GetMapdynamicsApiKeys') {
    
    require_once('../../../wp-load.php');
    
    
    try{
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Check Map Dynamics keys',"Admin Action","",$user_ID,$user_info->user_email,"pre_action_data");
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $mapapikey = $oldvalues['ContentManager']['mapapikey'];
        $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
        
        if(!empty($mapapikey)&&!empty($mapsecretkey)){
            echo 'connected';
        }else{
            echo 'notconnected';
        }
      
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();   
    
}else if ($_GET['contentManagerRequest'] == 'setlevelspriorities') {
    
    require_once('../../../wp-load.php');
    
    
    try{
        
        $dataRequeste =  json_decode(stripslashes($_POST['leveleslist']), true);
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Update Levels Priorities',"Admin Action","",$user_ID,$user_info->user_email,$dataRequeste);
        
        
      
        
        if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
        $get_all_roles = get_option($get_all_roles_array);
        
        foreach($dataRequeste as $index=>$levelkey){
            
           $rolekey     =  $levelkey['rolename'];
           $ordernumber =  $levelkey['prioritnum'];
           
          
           
           foreach ($get_all_roles as $key => $name) {
           
               
               if($key == $rolekey){
                  
                   $get_all_roles[$key]['priorityNum'] = $ordernumber;
                   
               }
               
               
           }
           
            
        }
        
       
        
        
        
        update_option($get_all_roles_array, $get_all_roles);
        contentmanagerlogging_file_upload ($lastInsertId,serialize($get_all_roles));
        echo "success";
        die();
        
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();   
    
}else if ($_GET['contentManagerRequest'] == 'setUserPirority') {
    
    require_once('../../../wp-load.php');
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/floorplan-manager.php';
    
    try{
        
        $demo = new FloorPlanManager();
        $AllBoothsList = $demo->getAllbooths();
        
        $dataRequeste =  json_decode(stripslashes($_POST['leveleslist']), true);
        $dataRequestedFloor =  json_decode(stripslashes($_POST['boothQueueSettings']), true);
        
        $multiturnArray =  json_decode(stripslashes($_POST['multiturnArray']), true);
        $RemoveDataArray =  json_decode(stripslashes($_POST['RemoveDataArray']), true);
        $newlistofuseradd =  json_decode(stripslashes($_POST['newlistofuseradd']), true);
        $turnIDs = [];
         foreach($RemoveDataArray as $index=>$levelkey){
                    
             //print_r($levelkey[$index]);
                        $rolekey     =  $levelkey['ID'];  
                        $status="-";                      
                        update_user_option( $rolekey, 'RemoveFromQueue', 'checked');
                        update_user_option( $rolekey, 'priorityNum', $status);
                        update_user_option($rolekey , 'myTurn','');

        }
        
        foreach($multiturnArray as $index=>$levelkey){
                    
            $rolekey     =  $levelkey['ID'];
            $turnIDs[]=$rolekey;
        } 
        
        
        // print_r($dataRequestedFloor['select1']);
        $floor_Plan_Settings='floorPlanSettings';
        $arr = array();
        $arrN = array();
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Update User Priorities',"Admin Action","",$user_ID,$user_info->user_email,$dataRequeste);
        $blog_id = get_current_blog_id();
        if (is_multisite()) {
            $blog_id = get_current_blog_id();
            $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
        }else{
            $get_all_roles_array = 'wp_user_roles';
        }

        //$get_all_roles = get_option($get_all_roles_array);

        $user_query =new WP_User_Query( array( 'role__not_in' => 'Administrator' ) );
        $lisstofuser = $user_query->get_results();
        
        foreach($dataRequeste as $index=>$levelkey){
            
            $rolekey     =  $levelkey['ID'];
            $ordernumber =  $levelkey['priorityNum'];
            $toogleCheck =  $levelkey['toggle'];
            $userStatus =  $levelkey['userStatus'];
            if ($toogleCheck=='checked') {

                if ( $ordernumber != '-' ) {
                    array_push($arr,(object)[
                        'Id' => $rolekey,
                        'Status'=> $userStatus,
                        'PriorityNum'=>$ordernumber
                        ]);
                }elseif($userStatus!='checked')
                {
                    
                    $myturn='';
                    update_user_option($rolekey , 'myTurn', $myturn);  
                    update_user_option($rolekey, 'userBoothStatus', $userStatus);
                }
                
                if ($dataRequestedFloor[0]['select1']=="Nothing") {
                    if ( $userStatus=='checked' ) {
                        array_push($arrN,(object)[
                            'Id' => $rolekey,
                            'Status'=> $userStatus,
                            'PriorityNum'=>$ordernumber
                            ]);
                    }else{
                        $myturn='';
                        update_user_option($rolekey , 'myTurn', $myturn);  
                    }
                    update_user_option($rolekey, 'priorityNum', $ordernumber);
                    update_user_option($rolekey, 'userBoothStatus', $userStatus);
                }   
                
                update_user_option( $rolekey, 'RemoveFromQueue', "");
                
               
                


                


                
            }
            else {
                echo "success";
            }




        }

       

        if($dataRequestedFloor[0]['select1']=="Nothing") {
            $remove = array_shift($arrN); 
            $myturns='Checked';
            update_user_option($remove->Id, 'myTurn', $myturns);
            foreach($arrN as $key=> $a_value) {  
                $myturn='';
                update_user_option($a_value->Id, 'myTurn', $myturn);
            }
        }else{
                       
            $checks="true";
            $checkA="true";
            $count = 0;
            
            $array_Pr=array();
            // print_r($arr);
            foreach($arr as $key=> $a_value) {
                     
                $user_option=get_user_meta($a_value->Id,'wp_'.$blog_id.'_myTurn');
                if( $user_option[0]=='Checked')
                {
                    $currentPriroityNumber=get_user_meta($a_value->Id,'wp_'.$blog_id.'_priorityNum');
                    array_push($array_Pr,$currentPriroityNumber);
                    $checkA="false";
                    $count++;
                }
            }
            $value = max($array_Pr);
            //echo "<pre>";
            //print_r($value);
            if($checkA=="false")
            {
                foreach($arr as $key=> $a_value) {
                   
                    if($checks=='true')
                    {
                        if($a_value->PriorityNum != '-' && ( $value[0]==$a_value->PriorityNum))
                        {
                          
                                $myturn='Checked';
                                update_user_option($a_value->Id, 'myTurn', $myturn);
                                $checks="false";
                        }else if($count <= 1){
                            
                            $myturn='';
                            update_user_option($a_value->Id, 'myTurn', $myturn);
                        }
                    }else{
                         
                            $myturn='';
                            update_user_option($a_value->Id, 'myTurn', $myturn);
                    }       
                        
                } 
                    
                    
            }else{        
                    $myturn='Checked';
                    update_user_option($arr[0]->Id, 'myTurn', $myturn);

            }
            foreach($dataRequeste as $index=>$levelkey){
            
                $rolekey     =  $levelkey['ID'];
                $ordernumber =  $levelkey['priorityNum'];
                $userStatus =  $levelkey['userStatus'];

                update_user_option($rolekey, 'priorityNum', $ordernumber);
                update_user_option($rolekey, 'userBoothStatus', $userStatus);
            }
        }



        foreach($multiturnArray as $index=>$levelkey){
                    
            //print_r($levelkey[$in    dex]);
                       $rolekey     =  $levelkey['ID'];  
                       $userStatus='checked';
                       update_user_option( $rolekey, 'userBoothStatus', $userStatus);
                       $myturn='Checked';
                       update_user_option($rolekey, 'myTurn', $myturn);
                       
                      
        
        } 
        
       
        
         if(!empty($turnIDs)){
                    
                foreach($dataRequeste as $index=>$levelkey){
                      $rolekey     =  $levelkey['ID'];  
                    if(!in_array($rolekey, $turnIDs)) {
                        
                        update_user_option($rolekey , 'myTurn', '');  
                    }
                }
            }








        $exhibitorflowstatus['status'] = "";
        $exhibitorflowstatusKey = "exhibitorentryflowstatus";
        update_option($exhibitorflowstatusKey, $exhibitorflowstatus);
        $floor_Plan_Settings='floorPlanSettings';
        $get= get_option($floor_Plan_Settings);

        $get['tableSort']='checked';
        update_option($floor_Plan_Settings,$get);
       
        
        foreach($dataRequestedFloor  as $index=> $levelkey){
            $Booth_Queue = array(
                'Open_users' =>  $levelkey['select1'],
                'Email_Selection' =>  $levelkey['select2'],
                'Deafult_status' =>  $levelkey['select3'],
            );
        }
        $Booth_Queue_Settings='boothQueueSettings';
        update_option($Booth_Queue_Settings, $Booth_Queue);
            
        $get_booth_settings= get_option($Booth_Queue_Settings);
        echo "success";
        die();
        
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();   
      
    
}else if ($_GET['contentManagerRequest'] == 'setFloorPlanSettings') {
    
    require_once('../../../wp-load.php');
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/floorplan-manager.php';
    
    try{
        
        $demo = new FloorPlanManager();
        $AllBoothsList = $demo->getAllbooths();
        $dataRequestedUser =  json_decode(stripslashes($_POST['leveleslist']), true);
        $dataRequestedFloor =  json_decode(stripslashes($_POST['floorPlanSetting']), true);
        $user_query =new WP_User_Query( array( 'role__not_in' => 'Administrator' ) );
        $lisstofuser = $user_query->get_results();
        $arr = array();
        foreach($dataRequestedFloor as $index=>$levelkey){
                    $Floor_Plan = array(
                        'Hide_exhibitor_Details' =>  $levelkey['select4'],
                        'Hide_reserved_Details' =>  $levelkey['select5'],
                        'Hide_Price' =>  $levelkey['select6'],
                        'Hide_Company_Name' =>  $levelkey['select7'],
                        'usersNum' =>  $levelkey['select8'],
                        'PrePaidChk' =>  $levelkey['select9'],
                        'zoom' =>  $levelkey['select10'],
                        'tableSort' => $levelkey['tableValue'],
                    );
        }
        $floor_Plan_Settings='floorPlanSettings';
           update_option($floor_Plan_Settings, $Floor_Plan);
           
        $get= get_option($floor_Plan_Settings);
        //echo "<pre>";
        //print_r($get);

        //   if (empty($dataRequestedUser)) {
        //      echo "Table Not Included";
        //   }
        //   else {
        //             foreach($dataRequestedUser as $index=>$levelkey){
                    
        //                 $rolekey     =  $levelkey['ID'];
        //                 $ordernumber =  $levelkey['priorityNum'];
        //                 $toogleCheck =  $levelkey['toggle'];
        //                 $userStatus =  $levelkey['userStatus'];
        //                 if ($toogleCheck=='checked') {
        //                     $exhibitorflowstatus['status'] = "";
        //                     $exhibitorflowstatusKey = "exhibitorentryflowstatus";
        //                     update_option($exhibitorflowstatusKey, $exhibitorflowstatus);
        //                             if($userStatus=='checked')
        //                             {
        //                                 $userStatus='checked';
        //                             }
        //                             else {
        //                                 $userStatus='';
        //                                 // update_user_option($name->ID, 'myTurn', $userStatus);
        //                             }
                                    
        //                             update_user_option($rolekey , 'priorityNum', $ordernumber);
        //                             update_user_option($rolekey , 'userBoothStatus', $userStatus);
                                   
                                   
        //                             if($Floor_Plan['Open_users']=="Auto and Email"||$Floor_Plan['Open_users']=="Auto and No Email"){

        //                                     if ($userStatus=='checked') {
        //                                         array_push($arr,(object)[
        //                                             'Id' => $rolekey,
        //                                             'Status'=> $userStatus,
        //                                             'PriorityNum'=>$ordernumber
        //                                             ]);
        //                                     } elseif($userStatus!='checked')
        //                                     {
        //                                         $myturn='';
        //                                         update_user_option($rolekey , 'myTurn', $myturn);  
        //                                     }       
        //                             }elseif ($Floor_Plan['Open_users']=="Nothing") {
        
        //                                 if ($userStatus=='checked' && $ordernumber != '-') {
        //                                     array_push($arr,(object)[
        //                                         'Id' => $rolekey,
        //                                         'Status'=> $userStatus,
        //                                         'PriorityNum'=>$ordernumber
        //                                         ]);
        //                                 }elseif($userStatus!='checked')
        //                                 {
        //                                     $myturn='';
        //                                     update_user_option($rolekey , 'myTurn', $myturn);  
        //                                 }
                                        
        //                             }
                            
        //                 }
        //                 else {
        //                     echo "success";
        //                 }
        //             }
        //             if($Floor_Plan['Open_users']=="Nothing") {
        //                 $remove = array_shift($arr);                        
        //                     $myturns='Checked';
        //                     update_user_option($remove->Id, 'myTurn', $myturns);

        //                 foreach($arr as $key=> $a_value) {
                            
        //                     $myturn='';
        //                     update_user_option($a_value->Id, 'myTurn', $myturn);
                           

        //                 }
        //             }else{
                       
        //                 $checks="true";
        //                 echo "<pre>";
        //                 print_r($arr);
        //                 foreach($arr as $key=> $a_value) {
        //                     $check="true";
        //                     if($checks=='true')
        //                     {
        //                         if($a_value->PriorityNum != '-'){
        //                                 $myturn='Checked';
        //                                 update_user_option($a_value->Id, 'myTurn', $myturn);
        //                                 $checks="false";
        //                         }
        //                     }  else{
        //                         $myturn='';
        //                         update_user_option($a_value->Id, 'myTurn', $myturn);
        //                     }       

        //                 } 
        //             }
              
            
        //     }
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();   
    
}else if ($_GET['contentManagerRequest'] == 'removeUserFromQueue'){
    require_once('../../../wp-load.php');
    
    
    try{
        
        $dataRequestedUser =  json_decode(stripslashes($_POST['userListRemove']), true);
        $dataRequestedUserForPriority =  json_decode(stripslashes($_POST['userListPriority']), true);
        $user_query =new WP_User_Query( array( 'role__not_in' => 'Administrator' ) );
        $lisstofuser = $user_query->get_results();
        
        foreach($dataRequestedUser as $index=>$levelkey){
                    
             //print_r($levelkey[$index]);
                        $rolekey     =  $levelkey['ID'];  
                        $status="-";                      
                        update_user_option( $rolekey, 'RemoveFromQueue', 'checked');
                        update_user_option( $rolekey, 'priorityNum', $status);
                        update_user_option($rolekey , 'myTurn','');

        }
        foreach($dataRequestedUserForPriority as $index=>$levelkey){
                    
            $rolekey     =  $levelkey['ID'];
            $ordernumber =  $levelkey['priorityNum'];
            $userStatus =  $levelkey['userStatus'];
                    
                   echo "<pre>";
                   print_r($levelkey);
                        if($userStatus=='checked')
                        {
                            $userStatus='checked';
                        }
                        else {
                            $userStatus='';
                        } 
                        update_user_option($rolekey , 'priorityNum', $ordernumber);
                        update_user_option($rolekey , 'userBoothStatus', $userStatus);
            
        }
        $floor_Plan_Settings='floorPlanSettings';
        $get= get_option($floor_Plan_Settings);

        $get['tableSort']='checked';
        update_option($floor_Plan_Settings,$get);

        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
    
}else if ($_GET['contentManagerRequest'] == 'activeUserFromQueue'){
    require_once('../../../wp-load.php');
    
    
    try{
        
        $dataRequestedUser =  json_decode(stripslashes($_POST['userListRemove']), true);
        $dataRequestedUserForPriority =  json_decode(stripslashes($_POST['userListSort']), true);
        $arr = array();
        $blog_id = get_current_blog_id();
        foreach($dataRequestedUser as $index=>$levelkey){
                    
             //print_r($levelkey[$index]);
                        $rolekey     =  $levelkey['ID'];  
                        $userStatus='checked';
                        update_user_option( $rolekey, 'userBoothStatus', $userStatus);
         
        } 
        foreach($dataRequestedUserForPriority as $index=>$levelkey){  
                                    
            $rolekey     =  $levelkey['ID'];
            $ordernumber =  $levelkey['priorityNum'];
        
                    // echo $name->ID;
                    // echo $rolekey;
                
                        if ( $ordernumber != '-' ) {
                            array_push($arr,(object)[
                                'Id' => $rolekey,
                                'PriorityNum'=>$ordernumber
                                ]);
                        }
                            
            // update_user_option($rolekey , 'priorityNum', $ordernumber);
        }
            $array_Pr=array();
            // print_r($arr);
            foreach($arr as $key=> $a_value) {
                     
                $user_option=get_user_meta($a_value->Id,'wp_'.$blog_id.'_myTurn');
                if( $user_option[0]=='Checked')
                {
                    $currentPriroityNumber=get_user_meta($a_value->Id,'wp_'.$blog_id.'_priorityNum');
                    array_push($array_Pr,$currentPriroityNumber);
                    // $checkA="false";
                }
            }
            $value = max($array_Pr);
            foreach($arr as $key=> $a_value) {

                if($a_value->PriorityNum != '-' && ( $value[0]==$a_value->PriorityNum))
                {
                        $myturn='Checked';
                        update_user_option($a_value->Id, 'myTurn', $myturn);
                }else{
                    $myturn='';
                    update_user_option($a_value->Id, 'myTurn', $myturn);
                }      
                    
            }
            foreach($dataRequestedUserForPriority as $index=>$levelkey){  
                                    
                $rolekey     =  $levelkey['ID'];
                $ordernumber =  $levelkey['priorityNum'];           
                 update_user_option($rolekey , 'priorityNum', $ordernumber);
            } 
        $floor_Plan_Settings='floorPlanSettings';
        $get= get_option($floor_Plan_Settings);

        $get['tableSort']='checked';
        update_option($floor_Plan_Settings,$get);
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
    
}else if ($_GET['contentManagerRequest'] == 'turnMultiple'){
    require_once('../../../wp-load.php');
    
    
    try{
        
        $dataRequestedUser =  json_decode(stripslashes($_POST['userListRemove']), true);
        $dataRequestedUserForPriority =  json_decode(stripslashes($_POST['userListSort']), true);
        foreach($dataRequestedUserForPriority as $index=>$levelkey){
                    
            $rolekey     =  $levelkey['ID'];
            $ordernumber =  $levelkey['priorityNum'];
          
                    // echo $name->ID;
                    // echo $rolekey;
                       
                        update_user_option($rolekey , 'priorityNum', $ordernumber);
                        $myturn='';
                        update_user_option($rolekey, 'myTurn', $myturn);
                       
            
        }
        foreach($dataRequestedUser as $index=>$levelkey){
                    
             //print_r($levelkey[$in    dex]);
                        $rolekey     =  $levelkey['ID'];  
                        $userStatus='checked';
                        update_user_option( $rolekey, 'userBoothStatus', $userStatus);
                        $myturn='Checked';
                        update_user_option($rolekey, 'myTurn', $myturn);
         
        } 
      
        $floor_Plan_Settings='floorPlanSettings';
        $get= get_option($floor_Plan_Settings);

        $get['tableSort']='checked';
        update_option($floor_Plan_Settings,$get);
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }

}else if ($_GET['contentManagerRequest'] == 'closeUserFromQueue'){
    require_once('../../../wp-load.php');
    
    
    try{
        
        $dataRequestedUser =  json_decode(stripslashes($_POST['userListRemove']), true);
        $dataRequestedUserForPriority =  json_decode(stripslashes($_POST['userListSort']), true);
        $arr = array();
        $blog_id = get_current_blog_id();
        foreach($dataRequestedUser as $index=>$levelkey){
                    
            //print_r($levelkey[$index]);
                       $rolekey     =  $levelkey['ID'];  
                       $userStatus='';
                       update_user_option( $rolekey, 'userBoothStatus', $userStatus);
                       //update_user_option($rolekey, 'myTurn', $userStatus);
        } 
        foreach($dataRequestedUserForPriority as $index=>$levelkey){  
                                    
            $rolekey     =  $levelkey['ID'];
            $ordernumber =  $levelkey['priorityNum'];
        
                    // echo $name->ID;
                    // echo $rolekey;
                
                        if ( $ordernumber != '-' ) {
                            array_push($arr,(object)[
                                'Id' => $rolekey,
                                'PriorityNum'=>$ordernumber
                                ]);
                        }
                            
            // update_user_option($rolekey , 'priorityNum', $ordernumber);
        }
            $array_Pr=array();
            // print_r($arr);
            foreach($arr as $key=> $a_value) {
                     
                $user_option=get_user_meta($a_value->Id,'wp_'.$blog_id.'_myTurn');
                if( $user_option[0]=='Checked')
                {
                    $currentPriroityNumber=get_user_meta($a_value->Id,'wp_'.$blog_id.'_priorityNum');
                    array_push($array_Pr,$currentPriroityNumber);
                    // $checkA="false";
                }
            }
            $value = max($array_Pr);
            foreach($arr as $key=> $a_value) {

                if($a_value->PriorityNum != '-' && ( $value[0]==$a_value->PriorityNum))
                {
                        $myturn='Checked';
                        update_user_option($a_value->Id, 'myTurn', $myturn);
                }else{
                    $myturn='';
                    update_user_option($a_value->Id, 'myTurn', $myturn);
                }      
                    
            }
            foreach($dataRequestedUserForPriority as $index=>$levelkey){  
                                    
                $rolekey     =  $levelkey['ID'];
                $ordernumber =  $levelkey['priorityNum'];           
                 update_user_option($rolekey , 'priorityNum', $ordernumber);
            } 

       $floor_Plan_Settings='floorPlanSettings';
       $get= get_option($floor_Plan_Settings);

       $get['tableSort']='checked';
       update_option($floor_Plan_Settings,$get);
    }catch (Exception $e) {
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
        
      return $e;
    }
    
}else if ($_GET['contentManagerRequest'] == 'addUserIntoQueue'){
    require_once('../../../wp-load.php');
    
    
    try{
        
        $dataRequestedUser =  json_decode(stripslashes($_POST['userListAdd']), true);
        $Booth_Queue_Settings='boothQueueSettings';
        $get=  get_option($Booth_Queue_Settings);
        
        foreach($dataRequestedUser as $index=>$levelkey){
                    
            //print_r($levelkey[$index]);
                       $rolekey     =  $levelkey['ID'];  
                    //    $userStatus='';
                       update_user_option( $rolekey, 'RemoveFromQueue', "");
                       update_user_option( $rolekey, 'priorityNum', "-");
                       if( $get['Deafult_status']== 'Open')
                       {   
                          update_user_option( $rolekey, 'userBoothStatus', 'checked');
                        }else{
                           update_user_option( $rolekey, 'userBoothStatus', '');

                       }
        
       } 
       $floor_Plan_Settings='floorPlanSettings';
       $get= get_option($floor_Plan_Settings);

       $get['tableSort']='checked';
       update_option($floor_Plan_Settings,$get);
        
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
    
}else if ($_GET['contentManagerRequest'] == 'addnewadminuser') {
    require_once('../../../wp-load.php');
    
    
    try{
    $t=time();
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);  
    $lastInsertId = contentmanagerlogging('New Admin User',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
    $username = str_replace("+","",$_POST['username']);
    
    $email = $_POST['email'];
    $role =$_POST['sponsorlevel'];
    $welcomeemailtemplatename = $_POST['welcomeemailtempname'];
    $loggin_data=$_POST;
    
    
    unset($_POST['username']);
    unset($_POST['email']);
    unset($_POST['sponsorlevel']);
    unset($_POST['welcomeemailtempname']);
    
  //  print_r($_POST);
  
    $welcomeemail_status = $_POST['welcomeemailstatus'];
    $user_id = username_exists($username);
    
    $message['username'] = $username;
    $meta_array=$_POST;
   
    if (!$user_id and email_exists($email) == false) {
        
       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
       $user_id = myregisterrequest_new_user($username, $email);//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
    
       
       
    if ( ! is_wp_error( $user_id ) ) {
       $result=$user_id;
       $loggin_data['created_id']=$result;
       $message['user_id'] = $user_id;
       $message['msg'] = 'User created';
       $message['userrole'] = $role;
       
       add_user_to_blog(1, $user_id, $role);
       add_new_sponsor_metafields($user_id,$meta_array,$role);
     
            $useremail='';
           
            if($welcomeemail_status == 'send'){
                    $useremail='';
                    custome_email_send($user_id,$useremail,$welcomeemailtemplatename);
                    $t=time();
                    update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
            }  
            
       }else{
		  
           $userregister_responce = (array)$user_id;
		  
		   if(empty($userregister_responce['errors']['invalid_username'][0])){
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_email'][0];
		   }else{
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_username'][0];
		   }
           //$user_id->errors['invalid_username'][0];
       } 
       
    } else {
        
        $blogid = get_current_blog_id() ;
        if (add_user_to_blog($blogid, $user_id, $role)) {
                
                switch_to_blog($blogid);
                add_new_sponsor_metafields($user_id,$meta_array,$role);
                add_user_to_blog(1, $user_id, $role);
                
                
                
               // custome_email_send($user_id,$email);
                 if($welcomeemail_status == 'send'){
                    $useremail='';
                    custome_email_send($user_id,$email,$welcomeemailtemplatename);
                    $t=time();
                    update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
                }  
                
                
               
                $message['msg'] = 'User added to this blog.';
            } else {
                $message['msg'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
            }
        
    }
    
    $loggin_data['msg']=$message['msg'];
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));
    echo json_encode($message);
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();

    //
}else if ($_GET['contentManagerRequest'] == 'getavailablemergefields') {
    
    require_once('../../../wp-load.php');
    
    //$test = 'custome_task_manager_data';
    //$result = get_option($test);
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
    $GetAllcustomefields = new EGPLCustomeFunctions();
    $additional_fields = $GetAllcustomefields->getAllcustomefields();
    
     function sortByOrder($a, $b) {
            return $a['fieldIndex'] - $b['fieldIndex'];
        }

    usort($additional_fields, 'sortByOrder');
    
    $keys_string[]= 'date';
    $keys_string[]= 'time';
    $keys_string[]= 'user_pass';
    $keys_string[]= 'site_url';
    $keys_string[]= 'site_title';
  
    $keys_string[]= 'user_login';
    
    
    
    foreach ($additional_fields as $key=>$value){ 
            
                        if($value['fieldType']!="display" && $value['fieldName'] != "Action" && $value['fieldType'] != "file" && $value['fieldType'] != "checkbox" ){
                        

                            $keys_string[] = str_replace(' ', '_', strtolower($value['fieldName']));
                         
                        
                    }}
    
    $bodytext_id = 'welcomebodytext';
//    if(!empty($result['custom_meta'])){
//    foreach($result['custom_meta'] as $key=>$value){
//      
//      if (preg_match('/task/',$key)){
//          
//      }else{
//     
//        $keys_string[]= $key; 
//      }
//      
//    }
//   }
    
 // echo '<pre>';
 // print_r( $result['sort_order'] );
    
    
    echo  json_encode($keys_string);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'get_all_file_urls') {
    
    require_once('../../../wp-load.php');
    global $wpdb;
    $zip_folder_name=$_POST['colvalue'];
    
    $users = $wpdb->get_results( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '".$zip_folder_name."'" );
    
    
    foreach ( $users as $user ) {
        $file_url = get_user_meta($user->user_id, $zip_folder_name);
        $user_company_name = get_user_option('company_name',$user->user_id);
        
        if(!empty($file_url[0]['file'])){
            
            $user_file_list[] = $user_company_name.'*'.$file_url[0]['file'];
           
        }
        

        
    }
    
    
    echo   json_encode($user_file_list);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'getpageContent') {
    
    require_once('../../../wp-load.php');
    
    $content_ID=$_POST['pageID'];
    $page_data = get_page($content_ID);
    $data_array['pagecontent'] = $page_data->post_content;
    $data_array['pagetitle'] = $page_data->post_title;
    
    
    echo   json_encode($data_array);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'updatresource') {
    
    require_once('../../../wp-load.php');
    
    $resource_id=$_POST['idresource'];
   
    $resource_title = $_POST['resourcetitle'];
    $replacefileurl=$_FILES['replacefile'];
    
        
       $current_item = array(
        'ID'           => $resource_id,
        'post_title'   => $resource_title
     
    ); 
        
    $error = "ok";
    $post_id = wp_update_post( $current_item, true );
    if(!empty($replacefileurl)){
        
      
      $newupdatedfileurl = resource_file_upload($replacefileurl);
     
      $result = update_post_meta($post_id, 'excerpt', $newupdatedfileurl);  
        
    }
    
    if (is_wp_error($post_id)) {
	$errors = $post_id->get_error_messages();
	foreach ($errors as $error) {
		$error = $error;
	}
    }
    
    
    echo   json_encode($error);
    
 
   die();

}else if ($_GET['contentManagerRequest'] == 'updatepagecontent') {
    
    require_once('../../../wp-load.php');
    
    $content_ID=$_POST['contentbodyID'];
    $content_Title=$_POST['contenttitle'];
    $content_body_message=$_POST['contentbody'];
    $my_post = array(
      'ID'           => $content_ID,
      'post_title'   => $content_Title,
      'post_content' => $content_body_message,
  );
    
 $post_id = wp_update_post( $my_post, true );						  
 if (is_wp_error($post_id)) {
	$errors = $post_id->get_error_messages();
	foreach ($errors as $error) {
		echo $error;
	}
}
$user_ID = get_current_user_id();
$user_info = get_userdata($user_ID);
}

if ($_GET['contentManagerRequest'] == 'changepassword') {
    
    require_once('../../../wp-load.php');
   
     
    
    $newpassword = $_POST['newpassword'];
    
    setpasswordcustome($newpassword);
    
     
   die();

}else if ($_GET['contentManagerRequest'] == 'plugin_settings') {
    
    require_once('../../../wp-load.php');
    
     plugin_settings();
     
   die();

}else if ($_GET['contentManagerRequest'] == 'remove_post_resource') {
    
      require_once('../../../wp-load.php');
      
    try{
        
     $post_id = $_POST['id'];
     $large_image_url = get_post_meta($post_id, 'port-descr', 1);
     
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Delete Resource',"Admin Action",serialize($large_image_url),$postid,$user_info->user_email,"pre_action_data");
     $result = remove_post_resource($post_id);
     contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();
}else if ($_GET['contentManagerRequest'] == 'remove_sponsor_metas') {
    
    require_once('../../../wp-load.php');
    
     $user_id = $_POST['id'];
  
     remove_sponsor_metas($user_id);
     
    
}else if ($_GET['contentManagerRequest'] == 'update_new_sponsor_metafields') {
     require_once('../../../wp-load.php');
   
  try{
       
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID); 
     $lastInsertId = contentmanagerlogging('Admin Edits User',"User Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
    //  echo "-----yoyo--------";
    //   print_r($_POST);
    //  print_r(json_decode($_POST['customefield_booth_numbers_87hg5']), true);
    $arr=$_POST['customefield_booth_numbers_87hg5'];
    $array=explode(",",$arr);
    // echo "----";
    // print_r($array);
    global  $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
    $userid=$_POST['sponsorid'];
    $file=$_POST['file'];
    $key=$_POST['action'];
    
    //echo '<pre>';
    //print_r($_FILES);exit;
    
    
    
//    if(!empty($_FILES)){
//        //echo "===============";      
//        $updatevalue=$_FILES['file']; 
//        //echo $updatevalue;
//        $updatevalue['post_request']=$_POST;
//        $company_name = get_user_meta($userid, $site_prefix.'company_name', true);
//        $updatevalue['name']=$company_name.'_'.$updatevalue['name'];
//        user_file_upload_AD($key,$updatevalue,$userid);
//     }else{
//         
//        $filestatus = "emptyfile"; 
//     }
    $password=$_POST['password'];
    $role =$_POST['sponsorlevel'];
    $BoothNumbers =$_POST['OverrideNumberOfBooths'];
    $Override_Check =$_POST['Override_Check'];
    $prePaid_checkbox =$_POST['prePaid_checkbox'];
    $loggin_data=$_POST;
    unset($_POST['sponsorlevel']);
    unset($_POST['sponsorid']);
    unset($_POST['password']);
    unset($_POST['OverrideNumberOfBooths']);
    unset($_POST['Override_Check']);
    unset($_POST['prePaid_checkbox']);
    $email = $_POST['Semail'];
    $meta_array=$_POST;
    // echo $file;
     
    $TaskArray=json_decode(stripslashes($_POST['TaskArray']), true);
    
    foreach ($TaskArray as $key => $tasksObject) {
        update_user_meta($userid, $tasksObject['Key'], $tasksObject['Value']);
        
    }
    $args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => 'egpl_custome_tasks',
        'post_status'      => 'draft',
        
        );
    $listOFtaskArray = get_posts( $args );
    foreach ($listOFtaskArray as $key => $tasksObject) {
        
        $tasksID=$tasksObject->ID;
        $value_type = get_post_meta( $tasksID, 'type' , true);
        $value_key = get_post_meta( $tasksID, 'key', true);
        $TaskCode = get_post_meta( $tasksID, 'taskCode', true);
        if($value_type == 'color' && $TaskCode!=""){
            
            
            $logofielupload = $_FILES[$value_key];
          
            
            if(!empty($logofielupload)){
                    
                  
                    $uploadedfileurl = resource_file_upload($logofielupload);
                    $fileurl['file'] ="" ;
                    $fileurl['type'] = "";
                    $fileurl['user_id'] = $userid;
                    $fileurl['url'] = $uploadedfileurl;
                    update_user_meta($userid, $value_key, $fileurl);
                    $date = new DateTime();
                    $datetime = $date->format('d-M-Y H:i:A');
                    update_user_meta($userid, $value_key.'_status', "Complete");
                    update_user_meta($userid, $value_key.'_datetime', $datetime);
            }
        }
        
    }
    
    
       $oldvalues = get_option( 'ContenteManager_Settings' );
  
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $listOFcustomfieldsArray = $GetAllcustomefields->getAllcustomefields();
    
        foreach($listOFcustomfieldsArray as $fieldsKey=>$fieldsObject){
    
            $fieldTYpe = $fieldsObject['fieldType'];
            
            if($fieldTYpe == "file"){

               
                $fieldKey = $fieldsObject['fielduniquekey'];
                $uploadFilesubmit = $_FILES[$fieldKey];
               
                if(!empty($uploadFilesubmit)){

                    $uploadedFileURL = resource_file_upload($uploadFilesubmit);
                    
                    $meta_array[$fieldKey]=$uploadedFileURL;
                }
            }
        }    
               
           

    
    
    if(!empty($password)){ wp_set_password( $password, $userid );}
    
       //update_user_option($userid, 'user_profile_url', $picprofileurl);
       
       $mapapikey = $oldvalues['ContentManager']['mapapikey'];
       $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
       $userexhibitor_id = get_user_option('exhibitor_map_dynamics_ID',  $userid); 
       if(!empty($mapapikey) && !empty($mapsecretkey)){
          
        $request_for_sync_map_dynamics = contentmanagerlogging('Sync to map dynamics update',"Admin Action",serialize($data_array),$user_ID,$user_info->user_email,"pre_action_data");
        
        if(!empty($userexhibitor_id)){
            $data_array=array(
            'company'=>$meta_array['company_name'],
            'email'=>$email,
            'first_name'=>$meta_array['first_name'],
            'last_name'=>$meta_array['last_name'],
           
            'exhibitor_id'=>intval($userexhibitor_id)
              
          ) ;
            $result = update_exhibitor_map_dynamics($data_array) ;
           
        }else{
            $data_array=array(
            'company'=>$meta_array['company_name'],
            'email'=>$email,
            'first_name'=>$meta_array['first_name'],
            'last_name'=>$meta_array['last_name'],
            
            
              
          ) ; 
            $result = insert_exhibitor_map_dynamics($data_array) ;
            
        }
        contentmanagerlogging_file_upload ($request_for_sync_map_dynamics,serialize($result));
       
        
        
        if($result->status == 'success'){
            
             update_user_option($userid, 'exhibitor_map_dynamics_ID', $result->results->Exhibitor_ID);
         
             $mapdynamicsstatus = '';
            
        }else{
            
            $sync_map_dynamics_message = $result->status_details;
            $mapdynamicsstatus = '';
        }
        
        
       
       }else{
           
           $mapdynamicsstatus = '';
           
       }
       update_user_option($userid, 'user_entry_wizerd', "completeflow");
       update_user_option($userid, 'OverrideNumberOfBooths', $BoothNumbers);
       update_user_option($userid, 'Override_Check', $Override_Check);
       update_user_option($userid, 'prePaid_checkbox', $prePaid_checkbox);
       
       $result =  add_new_sponsor_metafields($userid,$meta_array,$role);
       $message['mapdynamicsstatus'] = $mapdynamicsstatus;
       contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
       echo json_encode($message);
   }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
   die();
    
}else if ($_GET['contentManagerRequest'] == 'add_new_sponsor_metafields') {
    require_once('../../../wp-load.php');
    
    try{
        
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('New User',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
        $message = [];
        // print_r($_POST);exit;
        $username = str_replace("+","",$_POST['username']);
        $email = $_POST['email'];
        $role =$_POST['sponsorlevel'];
        $welcomeemailtemplatename = $_POST['welcomeemailtempname'];
        $loggin_data=$_POST;
        $BoothNumbers =$_POST['OverrideNumberOfBooths'];
        $Override_Check =$_POST['Override_Check'];
        $prePaid_checkbox =$_POST['prePaid_checkbox'];
        $arr=$_POST['customefield_booth_numbers_87hg5'];
        $array=explode(",",$arr);
        unset($_POST['username']);
        unset($_POST['email']);
        unset($_POST['sponsorlevel']);
        unset($_POST['welcomeemailtempname']);
        unset($_POST['OverrideNumberOfBooths']);
        unset($_POST['Override_Check']);
        unset($_POST['prePaid_checkbox']);
        $TaskArray=json_decode(stripslashes($_POST['TaskArray']), true);
        // print_r($_POST);
           
        
        $welcomeemail_status = $_POST['welcomeemailstatus'];
        $user_id = username_exists($username);
        $message['username'] = $username;
        $meta_array=$_POST;
        
    
        $oldvalues = get_option( 'ContenteManager_Settings' );
    
         require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
         $GetAllcustomefields = new EGPLCustomeFunctions();
         $listOFcustomfieldsArray = $GetAllcustomefields->getAllcustomefields();
    
         foreach($listOFcustomfieldsArray as $fieldsKey=>$fieldsObject){
    
             $fieldTYpe = $fieldsObject['fieldType'];
             $fieldKey = $fieldsObject['fielduniquekey'];
             if($fieldKey =='customefield_booth_numbers_87hg5')
             { 
                
                 $meta_array['customefield_booth_numbers_87hg5']=$array;
             }
             if($fieldTYpe == "file"){

               
               
                 $uploadFilesubmit = $_FILES[$fieldKey];
               
                 if(!empty($uploadFilesubmit)){

                     $uploadedFileURL = resource_file_upload($uploadFilesubmit);
                    
                     $meta_array[$fieldKey]=$uploadedFileURL;
                 }
             }
         }    
    
       $args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => 'egpl_custome_tasks',
        'post_status'      => 'draft',
        
        );
    $listOFtaskArray = get_posts( $args );
    foreach ($listOFtaskArray as $key => $tasksObject) {
        
        $tasksID=$tasksObject->ID;
        $value_type = get_post_meta( $tasksID, 'type' , true);
        $value_key = get_post_meta( $tasksID, 'key', true);
        $TaskCode = get_post_meta( $tasksID, 'taskCode', true);
        if($value_type == 'color' && $TaskCode!=""){
            
            
            $logofielupload = $_FILES[$value_key];
          
            
            if(!empty($logofielupload)){
                    
                  
                    $uploadedfileurl = resource_file_upload($logofielupload);
                    //$fileurl['file'] ="" ;
                    //$fileurl['type'] = "";
                    
                    $taskuploadedfiles[$value_key]['url'] = $uploadedfileurl;
                   // update_user_meta($userid, $value_key, $fileurl);
            }
        }
        
    }
    
    if (!$user_id and email_exists($email) == false) {
        
       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
       $user_id = myregisterrequest_new_user($username, $email) ;//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
       if ( ! is_wp_error( $user_id ) ) {
       
       $result=$user_id;
       $loggin_data['created_id']=$result;
       $message['user_id'] = $user_id;
       $message['msg'] = 'User created';
       $message['userrole'] = $role;
       $key=$_POST['action'];
       
       
        foreach ($TaskArray as $key => $tasksObject) {
            update_user_meta($user_id, $tasksObject['Key'], $tasksObject['Value']);     
        }
        foreach ($taskuploadedfiles as $taskkey => $fileurl) {
                     
                    $fileurll['file'] ="" ;
                    $fileurll['type'] = "";
                    $fileurll['userid'] = $user_id;
                    $fileurll['url'] = $fileurl['url'];
                    
                    update_user_meta($user_id, $taskkey, $fileurll);
                    $date = new DateTime();
                 
                    $datetime = $date->format('d-M-Y H:i:A');
                    update_user_meta($user_id, $taskkey.'_status', "Complete");
                    update_user_meta($user_id, $taskkey.'_datetime', $datetime);
                    
            
        }
        
       $site_prefix = $wpdb->get_blog_prefix();
       update_user_option($user_id, 'user_profile_url', $picprofileurl);
       update_user_option($user_id, 'OverrideNumberOfBooths', $BoothNumbers);
       update_user_option($user_id, 'Override_Check', $Override_Check);
         //Code by AD//
         $Booth_Queue_Settings='boothQueueSettings';
         $get_booth_settings= get_option($Booth_Queue_Settings);
          $value= $get_booth_settings['Deafult_status'];
          
        
           if($value=='Open')
           {
            
             update_user_option($user_id,'userBoothStatus','checked');
         }else if($value=='Close'){
             
             update_user_option($user_id,'userBoothStatus','');
         }
         else {
             
         } 
         //Code by AD//
       update_user_option($user_id, 'prePaid_checkbox', $prePaid_checkbox);
       $mapapikey = $oldvalues['ContentManager']['mapapikey'];
       $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
        add_user_to_blog(1, $user_id, $role);
      
       if(!empty($mapapikey) && !empty($mapsecretkey)){
          
          $data_array=array(
            'company'=>$meta_array['company_name'],
            'email'=>$email,
            'first_name'=>$meta_array['first_name'],
            'last_name'=>$meta_array['last_name'],
           
              
          ) ;
          
        $request_for_sync_map_dynamics = contentmanagerlogging('Sync to map dynamics',"Admin Action",serialize($data_array),$user_ID,$user_info->user_email,"pre_action_data");
        $result = insert_exhibitor_map_dynamics($data_array) ;
        contentmanagerlogging_file_upload ($request_for_sync_map_dynamics,serialize($result));
       
        if($result->status == 'success'){
            
             update_user_option($user_id, 'exhibitor_map_dynamics_ID', $result->results->Exhibitor_ID);
             $mapdynamicsstatus = '';
            
        }else{
            
            $sync_map_dynamics_message = $result->status_details;
            $mapdynamicsstatus = '';
        }
        
       }else{
           
           $mapdynamicsstatus = '';
           
       }
       
       add_new_sponsor_metafields($user_id,$meta_array,$role);
       if($welcomeemail_status == 'send'){
            $useremail='';
            custome_email_send($user_id,$useremail,$welcomeemailtemplatename);
            $t=time();
            update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
       }      
    }else{
        
        $userregister_responce = (array)$user_id;
		  
		   if(empty($userregister_responce['errors']['invalid_username'][0])){
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_email'][0];
		   }else{
			   
			   $message['msg'] = $userregister_responce['errors']['invalid_username'][0];
		   }
        
    } 
    } else {
        
        
        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        $currentblogid = get_current_blog_id() ;
        $user_blogs = get_blogs_of_user( $user_id );
        $user_status_for_this_site = 'not_exist';
        foreach ($user_blogs as $blog_id) { 
               
               if($blog_id->userblog_id == $currentblogid ){
                   
                   $user_status_for_this_site = 'alreadyexist';
                   break;
               }
               
        }
    if($user_status_for_this_site == 'alreadyexist'){
        
        $message['msg'] =  'User already exists for this site.';
        
    }else{    
           
        if (add_user_to_blog($blogid, $user_id, $role)) {
                 add_user_to_blog(1, $user_id, $role);
                $message['user_id'] = $user_id;
                $message['msg'] = 'User created';
                $message['userrole'] = $role;
                $meta_array=$_POST;
                $mapapikey = $oldvalues['ContentManager']['mapapikey'];
                $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
                foreach ($TaskArray as $key => $tasksObject) {
                    update_user_meta($user_id, $tasksObject['Key'], $tasksObject['Value']);
                    
                }
                
                foreach ($taskuploadedfiles as $taskkey => $fileurl) {
                     
                    $fileurll['file'] ="" ;
                    $fileurll['type'] = "";
                    $fileurll['userid'] = $user_id;
                    $fileurll['url'] = $fileurl['url'];
                    update_user_meta($user_id, $taskkey, $fileurll);
                    $date = new DateTime();
                    $datetime = $date->format('d-M-Y H:i:A');
                    update_user_meta($user_id, $taskkey.'_status', "Complete");
                    update_user_meta($user_id, $taskkey.'_datetime', $datetime);
                    
            
                }
                
                 $file=$_POST['file'];
                 $key=$_POST['action'];
                 
                //update_user_option($user_id, 'user_profile_url', $picprofileurl);
               
                if(!empty($mapapikey) && !empty($mapsecretkey)){
          
                        $data_array=array(
                          'company'=>$meta_array['company_name'],
                          'email'=>$email,
                          'first_name'=>$meta_array['first_name'],
                          'last_name'=>$meta_array['last_name'],
                       

                        ) ;
          
                    $request_for_sync_map_dynamics = contentmanagerlogging('Sync to map dynamics',"Admin Action",serialize($data_array),$user_ID,$user_info->user_email,"pre_action_data");
                    $result = insert_exhibitor_map_dynamics($data_array) ;
                    contentmanagerlogging_file_upload ($request_for_sync_map_dynamics,serialize($result));
       
                    if($result->status == 'success'){

                         update_user_option($user_id, 'exhibitor_map_dynamics_ID', $result->results->Exhibitor_ID);
                         $mapdynamicsstatus = '';

                    }else{

                        $sync_map_dynamics_message = $result->status_details;
                        $mapdynamicsstatus = '';
                    }
        
                }else{

                    $mapdynamicsstatus = '';

                }
                
                add_new_sponsor_metafields($user_id,$meta_array,$role);
                if($welcomeemail_status == 'send'){
                    $useremail='';
                    custome_email_send($user_id,$email,$welcomeemailtemplatename);
                    $t=time();
                    update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
                }      
                
                $message['msg'] =  'User added to this blog.';
            
            } else {
                
                $message['msg'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
            }
        }
    }
   
    $loggin_data['msg']=$message['msg'];
    $message['mapdynamicsstatus'] = $mapdynamicsstatus;
    contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));
    echo json_encode($message);
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();

    //
}else if ($_GET['contentManagerRequest'] == 'resource_new_post') {

    require_once('../../../wp-load.php');
  try{
      
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('New Resource',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
    $title=$_POST['title'];
    $file=$_FILES['file'];
    $resourceurl = resource_file_upload($file);
    
    $loggin_data['title']=$title;
    $loggin_data['fileurl']=$resourceurl;
   
    
    if($resourceurl != null){    
     $result = resource_new_post($title,$resourceurl);
    }
    echo   json_encode($resourceurl);
    contentmanagerlogging('New Resource',"Admin Action",serialize($loggin_data),$user_ID,$user_info->user_email,$result);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));   
  }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}else if($_GET['contentManagerRequest'] == 'getReportsdatanew'){ 
    require_once('../../../wp-load.php');
     try{
      
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Load Report',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
    $report_name=$_POST['reportName'];
    $usertimezone=intval($_POST['usertimezone']);
    getReportsdatanew($report_name,$usertimezone); 
    $result='Report Loaded';
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
}else if($_GET['contentManagerRequest'] == 'updatecmanagersettings'){ 
    require_once('../../../wp-load.php');
    
    $adminsitelogo=$_FILES['adminsitelogo'];
    
    if(empty($adminsitelogo)){
        
        
    }else{
      
      $adminstielogourl = resource_file_upload($adminsitelogo);
     
      
      $_POST['adminsitelogourl'] = $adminstielogourl;
      
    }
    
    
    
    updatecmanagersettings($_POST); 
   
    
    
    
}else if ($_GET['contentManagerRequest'] == 'update_admin_report') {
    
    require_once('../../../wp-load.php');
    
    
    $report_name =$_POST['reportName'];
    unset($_POST['reportName']);
    updateadminreport($_POST,$report_name);
     
     die();

}else if ($_GET['contentManagerRequest'] == 'getsavedReportvalues') {
    
    require_once('../../../wp-load.php');
    
    
    $report_name =$_POST['reportName'];
   
    getthereportsavalues($report_name);
     
     die();

}else if ($_GET['contentManagerRequest'] == 'sendcustomewelcomeemail') {
    
    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
   
try { 
    
    global $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mandrill = $oldvalues['ContentManager']['mandrill'];
    $mandrill = new Mandrill($mandrill);
    
   
    
    $sendcustomewelcomeemail = $_POST['selectedtemplateemailname'];
    
    
    $settitng_key='AR_Contentmanager_Email_Template_welcome';
    $sponsor_info = get_option($settitng_key);
    
    
    $subject = $sponsor_info[$sendcustomewelcomeemail]['welcomesubject'];
    $body=stripslashes ($sponsor_info[$sendcustomewelcomeemail]['welcomeboday']);
    $emailAddress=$_POST['emailAddress'];
    $emailaddress_array=explode(",", $emailAddress);
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $attendeefields_data=json_decode(stripslashes($_POST['attendeeallfields']), true);
    $colsdatatype=json_decode(stripslashes($_POST['datacollist']), true);
    $field_key_string = getInbetweenStrings('{', '}', $body);
    $field_key_subject = getInbetweenStrings('{', '}', $subject);
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    $fromname = stripslashes ($sponsor_info[$sendcustomewelcomeemail]['fromname']);
   
    
    
    if(empty($formemail)){
        
        $formemail = 'noreply@expo-genie.com';
        
    }
   $bcc =  $sponsor_info[$sendcustomewelcomeemail]['BCC'];
   //$cc =  $sponsor_info[$sendcustomewelcomeemail]['CC'];
   //$cc_array = explode(',',$cc);
   $bcc_array = $bcc;
   
   
   
   
   
   
  // $fromname = $_POST['fromname'];
  
//print_r($attendeefields_data);;
    
    
    $site_url = get_option('siteurl' );
    
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $sitetitle = get_bloginfo( 'name' );
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
   // $body = str_replace('[site_url]', $site_url, $body);
   // $body = str_replace('[login_url]', $site_url, $body);
   // $body = str_replace('[admin_email]', $admin_email, $body);
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url),
        array('name'=>'site_title','content'=>$sitetitle)
        );
    
   // foreach($emailaddress_array as $email=>$to){
       
       $body_message =    $body ;
      // $user = get_user_by( 'email', $to );
      // $firstname=$user->first_name;
      // $lastname=$user->last_name;
      // $user_email=$to;
       
       
      
       
        foreach($attendeefields_data as $key=>$Onerowvalue){
        
            $data_field_array= array();
            $result_email_index = multidimensional_search($Onerowvalue, array('colkey' => 'Semail')); // 1 
            $result_firstName_index = multidimensional_search($Onerowvalue, array('colkey' => $site_prefix.'first_name')); // 1 
            
            
            $userdata = get_user_by_email($Onerowvalue[$result_email_index]['colvalue']);
            $t=time();
            update_user_option($userdata->ID, 'convo_welcomeemail_datetime', $t*1000);
            $email_address = $Onerowvalue[$result_email_index]['colvalue'];
            $first_name = $Onerowvalue[$result_firstName_index]['colvalue'];
            $all_meta_for_user = get_user_meta($userdata->ID);
            
          
              
              
              
             
           
             foreach($field_key_subject as $index_subject=>$keyvalue_subject){
                  
                      if($keyvalue_subject == 'Role' || $keyvalue_subject == 'site_title' || $keyvalue_subject == 'date' || $keyvalue_subject == 'time' || $keyvalue_subject == 'site_url' || $keyvalue_subject == 'user_pass'|| $keyvalue_subject == 'user_login'){
                      
                       
                      if($keyvalue_subject == 'user_pass'){
                          
                            
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue_subject == 'user_login'){
                          
                         
                          
                          
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$userdata->user_login);  
                      }elseif($keyvalue_subject == 'Role'){
                          
                          $user_id = $userdata->ID;
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {
                              
                              if(implode(', ', $getcurrentuserdata->roles) == $key){
                                  
                                  $currentuserRole = $name['name'];
                                  
                                  
                              }
                              
                              
                              
                          }
                          
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$currentuserRole); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                   
                        
                       if (!empty($all_meta_for_user[$keyvalue_subject][0])) {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue_subject)); // 1 
                            $getfieldType = getcustomefieldKeyValue($keyvalue_subject,"fieldType");
                        if($getfieldType == 'date') {
                            
                            
                          
                          $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0]/1000));
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                 
                                $data_field_array[] = array('name'=>$index_subject,'content'=>$all_meta_for_user[$keyvalue_subject][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index_subject,'content'=>''); 
                          
                       }
                   
                      
                      
                      
                      
                  }
                 
                 
                 
             }
            foreach($field_key_string as $index=>$keyvalue){
                
                        
                     
                      
                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Semail' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){
                      
                          
                      if($keyvalue == 'user_pass'){
                          
                           
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue == 'user_login'){
                          
                       
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->user_login);  
                          
                         
                          
                      }elseif($keyvalue == 'Role'){
                          
                        
                          $user_id = $userdata->ID;
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {
                              
                              if(implode(', ', $getcurrentuserdata->roles) == $key){
                                  
                                  $currentuserRole = $name['name'];
                              }
                          }
                          $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole); 
                      }elseif($keyvalue == 'Semail'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$email_address); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                        
                       if (!empty($all_meta_for_user[$keyvalue][0])) {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue)); // 1 
                           
                          $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");
                          
                          
                         
                        if($getfieldType == 'date') {
                          
                          $date_value =   date('d-m-Y', intval($all_meta_for_user[$keyvalue][0])/1000);
                          $data_field_array[] = array('name'=>$index,'content'=>$date_value);
                         
                          
                          
                        } else{
                             
                                 
                                $data_field_array[] = array('name'=>$index,'content'=> $all_meta_for_user[$keyvalue][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index,'content'=>''); 
                          
                       }
                  
                      
                      
                      
                      
                  }
                 
                 
                 
             }
              
          
              
                
           $to_message_array[]=array('email'=>$email_address,'name'=>$first_name,'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$email_address,
                'vars'=>$data_field_array
           );
 
        }
       
       
        $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
        $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
        $logourl = '';
        
        if(!empty($mainheaderlogo)){
            
            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderlogo.'" alt="" width="250" />';
        
        }else if(!empty($mainheaderbackground)){
            
            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderbackground.'" alt="" height="100" />';
        
            
        }
        
        $html_body_message = '<table width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
<tbody>
<tr>
<td align="left">
<div style="border: solid 1px #d9d9d9;">
<table id="header" style="line-height: 1.6;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="text-align: center;">'.$logourl.'</td>
</tr>
</tbody>
</table>
<table id="content" style="padding-right: 30px;padding-left: 30px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="border-top: solid 1px #d9d9d9;" colspan="2">
<div style="padding: 1em 0;">
'.$body.'
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>'; 
  
   
   $get_currentsiteURl = get_site_url();
   $message = array(
        
        'html' => $html_body_message,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $sponsor_info[$sendcustomewelcomeemail]['replaytoemailadd']),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
       
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
        "tags" => [$get_currentsiteURl],
        
    );
   
    // exit;
       
    $lastInsertId = contentmanagerlogging('Welcome Email',"Admin Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
    $async = false;
    $ip_pool = 'Main Pool';
   // $send_at = 'example send_at';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    
   
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    echo json_encode('successfully send');
   
    
}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    
 
    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}
 die();
}else if ($_GET['contentManagerRequest'] == 'sendbulkemail') {
    
    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
   
try { 
    
    
     $oldvalues = get_option( 'ContenteManager_Settings' );
     $mandrill = $oldvalues['ContentManager']['mandrill'];
    
    $mandrill = new Mandrill($mandrill);
    
    
    $subject =$_POST['emailSubject'];
    $body=stripslashes ($_POST['emailBody']);
    $emailAddress=$_POST['emailAddress'];
    $emailaddress_array=explode(",", $emailAddress);
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $attendeefields_data=json_decode(stripslashes($_POST['attendeeallfields']), true);
    $colsdatatype=json_decode(stripslashes($_POST['datacollist']), true);
    
   
    
    $field_key_string = getInbetweenStrings('{', '}', $body);
    $field_key_string_subject = getInbetweenStrings('{', '}', $subject);
    
   
    
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@expo-genie.com';
        
    }
    
   $bcc = $_POST['BCC'];
   $cc  = $_POST['CC'];
  
   
   
   
   $replytoEmail = $_POST['RTO'];
   $fromname = $_POST['fromname'];
  

    
    
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $sitetitle = get_bloginfo( 'name' );
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
   // $body = str_replace('[site_url]', $site_url, $body);
   // $body = str_replace('[login_url]', $site_url, $body);
   // $body = str_replace('[admin_email]', $admin_email, $body);
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url),
        array('name'=>'site_title','content'=>$sitetitle)
        );
    $body_message =    $body ;
     
       foreach($attendeefields_data as $key=>$Onerowvalue){
        
            $data_field_array= array();
            $result_email_index = multidimensional_search($Onerowvalue, array('colkey' => 'Semail')); // 1 
            $result_firstName_index = multidimensional_search($Onerowvalue, array('colkey' => $site_prefix.'first_name')); // 1 
            
            
            $userdata = get_user_by_email($Onerowvalue[$result_email_index]['colvalue']);
            $t=time();
            update_user_option($userdata->ID, 'convo_welcomeemail_datetime', $t*1000);
            $email_address = $Onerowvalue[$result_email_index]['colvalue'];
            $first_name = $Onerowvalue[$result_firstName_index]['colvalue'];
            $all_meta_for_user = get_user_meta($userdata->ID);
            
          
              
              
              
             
           
             foreach($field_key_subject as $index_subject=>$keyvalue_subject){
                  
                      if($keyvalue_subject == 'Role' || $keyvalue_subject == 'site_title' || $keyvalue_subject == 'date' || $keyvalue_subject == 'time' || $keyvalue_subject == 'site_url' || $keyvalue_subject == 'user_pass'|| $keyvalue_subject == 'user_login'){
                      
                       
                      if($keyvalue_subject == 'user_pass'){
                          
                            
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue_subject == 'user_login'){
                          
                         
                          
                          
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$userdata->user_login);  
                      }elseif($keyvalue_subject == 'Role'){
                          
                          $user_id = $userdata->ID;
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {
                              
                              if(implode(', ', $getcurrentuserdata->roles) == $key){
                                  
                                  $currentuserRole = $name['name'];
                                  
                                  
                              }
                              
                              
                              
                          }
                          
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$currentuserRole); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                   
                        
                       if ($all_meta_for_user[$keyvalue_subject][0] !="") {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue_subject)); // 1 
                           $getfieldType = getcustomefieldKeyValue($keyvalue_subject,"fieldType");
                        if($getfieldType == 'date') {
                            
                            
                          
                          $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0])/1000);
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                 
                                $data_field_array[] = array('name'=>$index_subject,'content'=>$all_meta_for_user[$keyvalue_subject][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index_subject,'content'=>''); 
                          
                       }
                   
                      
                      
                      
                      
                  }
                 
                 
                 
             }
            foreach($field_key_string as $index=>$keyvalue){
                
                        
                     
                      
                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Semail' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){
                      
                          
                      if($keyvalue == 'user_pass'){
                          
                           
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue == 'user_login'){
                          
                       
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->user_login);  
                          
                         
                          
                      }elseif($keyvalue == 'Role'){
                          
                        
                          $user_id = $userdata->ID;
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {
                              
                              if(implode(', ', $getcurrentuserdata->roles) == $key){
                                  
                                  $currentuserRole = $name['name'];
                              }
                          }
                          $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole); 
                      }elseif($keyvalue == 'Semail'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$email_address); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                        
                       if ($all_meta_for_user[$keyvalue][0] !="") {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue)); // 1 
                           $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");
                          
                        if($getfieldType == 'date') {
                            
                          $date_value =   date('d-m-Y', intval($all_meta_for_user[$keyvalue][0]/1000));
                          $data_field_array[] = array('name'=>$index,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                $data_field_array[] = array('name'=>$index,'content'=> $all_meta_for_user[$keyvalue][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index,'content'=>''); 
                          
                       }
                  
                      
                      
                      
                      
                  }
                 
                 
                 
             }
              
          
              
                
           $to_message_array[]=array('email'=>$email_address,'name'=>$first_name,'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$email_address,
                'vars'=>$data_field_array
           );
 
        }
       
      
   $bcc_array = $bcc;
   
   $get_currentsiteURl = get_site_url();
   $message = array(
        
        'html' => $body,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replytoEmail),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
         "tags" => [$get_currentsiteURl]
        
    );
   
    // exit;
       
    $lastInsertId = contentmanagerlogging('Bulk Email',"Admin Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
    $async = false;
    $ip_pool = 'Main Pool';
   // $send_at = 'example send_at';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    echo 'successfully send';
   
    
}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    
 
    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}
 die();
}else if ($_GET['contentManagerRequest'] == 'sendbulkemailtasksreport') {
    
    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
   
try { 
    
    
     $oldvalues = get_option( 'ContenteManager_Settings' );
     $mandrill = $oldvalues['ContentManager']['mandrill'];
    
    $mandrill = new Mandrill($mandrill);
    
    
    $subject =$_POST['emailSubject'];
    $body=stripslashes ($_POST['emailBody']);
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $attendeefields_data=json_decode(stripslashes($_POST['attendeeallfields']), true);
    
    
   
    
    $field_key_string = getInbetweenStrings('{', '}', $body);
    $field_key_string_subject = getInbetweenStrings('{', '}', $subject);
    
   
    
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@expo-genie.com';
        
    }
    
   $bcc = $_POST['BCC'];
   $cc  = $_POST['CC'];
  
   
   
   
   $replytoEmail = $_POST['RTO'];
   $fromname = $_POST['fromname'];
  

    
    
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $sitetitle = get_bloginfo( 'name' );
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
   // $body = str_replace('[site_url]', $site_url, $body);
   // $body = str_replace('[login_url]', $site_url, $body);
   // $body = str_replace('[admin_email]', $admin_email, $body);
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url),
        array('name'=>'site_title','content'=>$sitetitle)
        );
    $body_message =    $body ;
    global $wpdb;
    $site_prefix = $wpdb->get_blog_prefix();
     
       foreach($attendeefields_data as $key=>$Onerowvalue){
        
            $data_field_array= array();
            
            
            $userdata = get_user_by_email($Onerowvalue);
            $email_address = $Onerowvalue;
            
            $all_meta_for_user = get_user_meta($userdata->ID);
            $first_name = $all_meta_for_user[$site_prefix.'first_name'][0];
            
            
            foreach($field_key_subject as $index_subject=>$keyvalue_subject){
                  
                      if($keyvalue_subject == 'Role' || $keyvalue_subject == 'site_title' || $keyvalue_subject == 'date' || $keyvalue_subject == 'time' || $keyvalue_subject == 'site_url' || $keyvalue_subject == 'user_pass'|| $keyvalue_subject == 'user_login'){
                      
                       
                      if($keyvalue_subject == 'user_pass'){
                          
                            
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue_subject == 'user_login'){
                          
                         $data_field_array[] = array('name'=>$index_subject,'content'=>$userdata->user_login); 
                         
                      }elseif($keyvalue_subject == 'Role'){
                          
                          $user_id = $userdata->ID;
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {
                              
                              if(implode(', ', $getcurrentuserdata->roles) == $key){
                                  
                                  $currentuserRole = $name['name'];
                              }
                           }
                          
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$currentuserRole); 
                      }
                      
                      
                      
                   }else{
                       
                       if ($all_meta_for_user[$keyvalue_subject][0]!="") {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue_subject)); // 1 
                           $getfieldType = getcustomefieldKeyValue($keyvalue_subject,"fieldType");
                           
                        if($getfieldType == 'date') {
                            
                            
                          
                          $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0])/1000);
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                 
                                $data_field_array[] = array('name'=>$index_subject,'content'=>$all_meta_for_user[$keyvalue_subject][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index_subject,'content'=>''); 
                          
                       }
                   
                      
                      
                      
                      
                  }
                 
                 
                 
             }
            foreach($field_key_string as $index=>$keyvalue){
                
                        
                     
                      
                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Semail' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){
                      
                          
                      if($keyvalue == 'user_pass'){
                          
                           
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue == 'user_login'){
                          
                       
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->user_login);  
                          
                         
                          
                      }elseif($keyvalue == 'Role'){
                          
                        
                          $user_id = $userdata->ID;
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {
                              
                              if(implode(', ', $getcurrentuserdata->roles) == $key){
                                  
                                  $currentuserRole = $name['name'];
                              }
                          }
                          $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole); 
                      }elseif($keyvalue == 'Semail'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$email_address); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                        
                       if ($all_meta_for_user[$keyvalue][0]!="") {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue)); // 1 
                           $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");
                          
                        if($getfieldType == 'date') {
                            
                          $date_value =   date('d-m-Y', intval($all_meta_for_user[$keyvalue][0]/1000));
                          $data_field_array[] = array('name'=>$index,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                $data_field_array[] = array('name'=>$index,'content'=> $all_meta_for_user[$keyvalue][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index,'content'=>''); 
                          
                       }
                  
                      
                      
                      
                      
                  }
                 
                 
                 
             }
              
          
              
                
           $to_message_array[]=array('email'=>$email_address,'name'=>$first_name,'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$email_address,
                'vars'=>$data_field_array
           );
 
        }
       
      
   $bcc_array = $bcc;
   
   $get_currentsiteURl = get_site_url();
   $message = array(
        
        'html' => $body,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replytoEmail),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
         "tags" => [$get_currentsiteURl]
        
    );
   
    // exit;
       
    $lastInsertId = contentmanagerlogging('Tasks Bulk Email',"Admin Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
    $async = false;
    $ip_pool = 'Main Pool';
   // $send_at = 'example send_at';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    echo 'successfully send';
   
    
}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    
 
    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}
 die();
}else if ($_GET['contentManagerRequest'] == 'sendadmintestemail') {
    
    require_once('../../../wp-load.php');
    
    try{
        
          global $wpdb;  
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Admin Test Email',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
    $site_prefix = $wpdb->get_blog_prefix();
     
        
    $subject =$_POST['emailSubject'];
    $body=stripslashes ($_POST['emailBody']);
    
   
    
    
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $site_title=get_option( 'blogname' );
    
    
    
    $body = str_replace('[site_url]', $site_url, $body);
    $body = str_replace('[login_url]', $site_url, $body);
    $body = str_replace('[admin_email]', $admin_email, $body);
    $body = str_replace('[date]', $data, $body);
    $body = str_replace('[time]', $time, $body);
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail='noreply@expo-genie.com';
    }
       
      $body_message =    $body ;
      $subject_body =$subject;
      $site_url = get_option('siteurl' );
      $data=  date("Y-m-d");
      $time=  date('H:i:s');
      $user = get_user_by( 'email', $user_info->user_email );
      $all_meta_for_user = get_user_meta($user->ID);
      
      $firstname=$all_meta_for_user[$site_prefix.'first_name'][0];
      $lastname=$all_meta_for_user[$site_prefix.'last_name'][0];
      $headers = 'From: '.$site_title.' <'.$formemail.'>' . "\r\n";
       $body_message = str_replace('[user_email]', $user_email, $body_message);
       $body_message = str_replace('[first_name]', $firstname, $body_message);
       $body_message = str_replace('[last_name]', $lastname, $body_message);
       $body_message = str_replace('[site_title]', $site_title, $body_message);
       $body_message = str_replace('[date]', $data, $body_message);
       $body_message = str_replace('[time]', $time, $body_message);
       $body_message = str_replace('[site_url]', $site_url, $body_message);
       
       $subject_body = str_replace('[user_email]', $user_email, $subject_body);
       $subject_body = str_replace('[first_name]', $firstname, $subject_body);
       $subject_body = str_replace('[last_name]', $lastname, $subject_body);
       $subject_body = str_replace('[site_title]', $site_title, $subject_body);
       $subject_body = str_replace('[user_pass]', $plaintext_pass, $subject_body);
         $subject_body = str_replace('[date]', $data, $subject_body);
         $subject_body = str_replace('[time]', $time, $subject_body);
         $subject_body = str_replace('[site_url]', $site_url, $subject_body);
       
       
       
       $result = send_email($user_info->user_email,$subject_body,$body_message,$headers);

    
   
     //contentmanagerlogging('Admin Test Email',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,$result);
      contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    }
    catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
 die();

}else if ($_GET['contentManagerRequest'] == 'sendadmintestemailwelcome') {
    
    require_once('../../../wp-load.php');
    
    try{
    $subject =$_POST['emailSubject'];
    $body=stripslashes($_POST['emailBody']);
    $welcomeemailfromname = $_POST['welcomeemailfromname'];
    $replaytoemailadd = $_POST['replaytoemailadd'];
    
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
   $lastInsertId = contentmanagerlogging('Admin Test Email Welcome',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
    
    
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $site_title=get_option( 'blogname' );
    
    
    
    $body = str_replace('[site_url]', $site_url, $body);
    $body = str_replace('[login_url]', $site_url, $body);
    $body = str_replace('[admin_email]', $admin_email, $body);
    $body = str_replace('[date]', $data, $body);
    $body = str_replace('[time]', $time, $body);
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@expo-genie.com';
        
    }
   
      
      $body_message =    $body ;
      $subject_body =$subject;
      $site_url = get_option('siteurl' );
      $data=  date("Y-m-d");
      $time=  date('H:i:s');
      $user = get_user_by( 'email', $user_info->user_email );
      $all_meta_for_user = get_user_meta($user_info->ID);
      $firstname=$all_meta_for_user[$site_prefix.'first_name'][0];
      $lastname=$all_meta_for_user[$site_prefix.'last_name'][0];
      $headers = 'From: '.$welcomeemailfromname.' <'.$formemail.'>' . "\r\n";
      $headers .= 'Reply-To: '.$replaytoemailadd;
      
      $body_message = str_replace('[user_email]', $user_email, $body_message);
      $body_message = str_replace('[first_name]', $firstname, $body_message);
      $body_message = str_replace('[last_name]', $lastname, $body_message);
      $body_message = str_replace('[site_title]', $site_title, $body_message);
      $body_message = str_replace('[date]', $data, $body_message);
      $body_message = str_replace('[time]', $time, $body_message);
      $body_message = str_replace('[site_url]', $site_url, $body_message);
       
      $subject_body = str_replace('[user_email]', $user_email, $subject_body);
      $subject_body = str_replace('[first_name]', $firstname, $subject_body);
      $subject_body = str_replace('[last_name]', $lastname, $subject_body);
      $subject_body = str_replace('[site_title]', $site_title, $subject_body);
      $subject_body = str_replace('[user_pass]', $plaintext_pass, $subject_body);
      $subject_body = str_replace('[date]', $data, $subject_body);
      $subject_body = str_replace('[time]', $time, $subject_body);
      $subject_body = str_replace('[site_url]', $site_url, $subject_body);
       
       $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
        $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
        $logourl = '';
        
        if(!empty($mainheaderlogo)){
            
            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderlogo.'" alt="" width="250" />';
        
        }else if(!empty($mainheaderbackground)){
            
            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderbackground.'" alt="" height="100" />';
        
            
        }
        
        $html_body_message = '<table width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
<tbody>
<tr>
<td align="left">
<div style="border: solid 1px #d9d9d9;">
<table id="header" style="line-height: 1.6;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="text-align: center;">'.$logourl.'</td>
</tr>
</tbody>
</table>
<table id="content" style="padding-right: 30px;padding-left: 30px;"  border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="border-top: solid 1px #d9d9d9;" colspan="2">
<div style="padding: 1em 0;">
'.$body_message.'
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>'; 
       
       $result = send_email($user_info->user_email,$subject_body,$html_body_message,$headers);

    
   
      contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    }
    catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();

}else if ($_GET['contentManagerRequest'] == 'update_admin_email_template') {
    
    require_once('../../../wp-load.php');
    
    
    $report_name =$_POST['emailtemplatename'];
    unset($_POST['emailtemplatename']);
    updateadminemailtemplate($_POST,$report_name);
     
     die();

}else if ($_GET['contentManagerRequest'] == 'get_email_template') {
    
    require_once('../../../wp-load.php');
    
    
    $report_name = $_POST['emailtemplatename'];
    $settitng_key='AR_Contentmanager_Email_Template';
    $get_email_template_date = get_option($settitng_key);
    
   
    $template_data['emailsubject'] = $get_email_template_date[$report_name]['emailsubject'];
    $template_data['emailboday'] = $get_email_template_date[$report_name]['emailboday'];
    $template_data['BCC'] = $get_email_template_date[$report_name]['BCC'];
    $template_data['fromname'] = $get_email_template_date[$report_name]['fromname'];
    //$template_data['CC'] = $get_email_template_date[$report_name]['CC'];
    $template_data['RTO'] = $get_email_template_date[$report_name]['RTO'];
   
     
    echo   json_encode($template_data);
     
     die();
     

}else if ($_GET['contentManagerRequest'] == 'remove_email_template') {
    
    require_once('../../../wp-load.php');
    
    try{
       $user_ID = get_current_user_id();
          $user_info = get_userdata($user_ID); 
       $lastInsertId = contentmanagerlogging('Remove Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    $report_name =$_POST['emailtemplatename'];
    $settitng_key='AR_Contentmanager_Email_Template';
    $get_email_template_date = get_option($settitng_key);
    
    unset($get_email_template_date[$report_name]);
    update_option($settitng_key, $get_email_template_date);
    $report_info = get_option($settitng_key);
      
      $i=0;
     foreach ($report_info as $key=>$value){
        
              
              $lis[$i] = $key;
              $i++;
         
          
      }
      
      
    echo   json_encode($lis);
    $update_list['new_update_list_after_remove']=$lis;
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($update_list));
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();
     

}else if ($_GET['contentManagerRequest'] == 'updatewelocmecontent') {
    
    require_once('../../../wp-load.php');
    
   try{ 
       $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
       $lastInsertId = contentmanagerlogging('Welcome Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    $welcome_subject =$_POST['welcomeemailSubject'];
    $welcome_body =$_POST['welcomeemailBody'];
    $replaytoemailadd =$_POST['replaytoemailadd'];
    $welcomeemailfromname =$_POST['welcomeemailfromname'];
    $settitng_key='AR_Contentmanager_Email_Template_welcome';
    $sponsor_info = get_option($settitng_key);
    
    $result='';
      
    
    $sponsor_info['welcome_email_template']['welcomesubject'] = $welcome_subject;
    $sponsor_info['welcome_email_template']['fromname'] = $welcomeemailfromname;
    $sponsor_info['welcome_email_template']['replaytoemailadd'] = $replaytoemailadd;
    $sponsor_info['welcome_email_template']['welcomeboday'] = stripslashes($welcome_body);
     $sponsor_info['welcome_email_template']['BCC'] = $_POST['BCC'];
     
     //contentmanagerlogging('Welcome Email Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,$result);
    
    $result= update_option($settitng_key, $sponsor_info);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
   } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
     die();
	 
}else if ($_GET['contentManagerRequest'] == 'remove_save_report_template') {
    
    require_once('../../../wp-load.php');
    
    try{
    $savereport_name =$_POST['savereportname'];
    $report_seetingkey='AR_Contentmanager_Reports_Filter';
    $report_data = get_option($report_seetingkey);
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
      
     $lastInsertId = contentmanagerlogging('Remove Report Template',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
       
    unset($report_data[$savereport_name]);
    
    $result = update_option( $report_seetingkey, $report_data );
    
    $get_new_report_data = get_option($report_seetingkey);
    echo   json_encode($get_new_report_data);

   // $result['new_report_data']=$get_new_report_data;
    contentmanagerlogging_file_upload ($lastInsertId,serialize($get_new_report_data));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die(); 

}else if ($_GET['contentManagerRequest'] == 'addnewrole') {
    
    require_once('../../../wp-load.php');
    
    $blog_id =get_current_blog_id();
    //switch_to_blog($blog_id);
    
    try{
    
   
    $newrolename =$_POST['rolename'];
    
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Add New Role',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
     $role_key=strtolower($newrolename);
     $remove_space_role_kye=str_replace(" ","_",$role_key);
     
     
     if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $get_all_roles = get_option($get_all_roles_array);
     $result_update = 'newvalue';
     foreach ($get_all_roles as $key => $item) {
            
            if($role_key == strtolower($item['name']) || $key == $remove_space_role_kye ){
                $result_update = 'already';
                break;
            }
            
    }
     
     
     
    
     if($result_update == 'newvalue'){
        //$result = add_role( $remove_space_role_kye, ucfirst($newrolename), array( 'read' => true,'unfiltered_upload'=>true,'upload_files'=>true ) );
         $get_all_roles[$remove_space_role_kye]['name'] =  ucfirst($newrolename);
         $get_all_roles[$remove_space_role_kye]['capabilities']['unfiltered_upload'] =  1;//ucfirst($newrolename);
         $get_all_roles[$remove_space_role_kye]['capabilities']['upload_files'] =  1;//ucfirst($newrolename);
         $get_all_roles[$remove_space_role_kye]['capabilities']['level_0'] =  1;
         $get_all_roles[$remove_space_role_kye]['capabilities']['read'] =  1;
          
         update_option ($get_all_roles_array, $get_all_roles); 
        
            $msg['msg'] = '<strong>'.ucfirst($newrolename).'</strong> New Level created';
            $msg['status'] = 'success';
            $msg['title'] = 'Success';
       
     }else {
        
        $msg['msg'] = '<strong>'.ucfirst($newrolename).'</strong> Level already exists.';
        $msg['status'] = 'warning';
        $msg['title'] = 'Warning';
        
       }
    echo   json_encode($msg);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
         return $e;
 }
 
    die(); 

}else if ($_GET['contentManagerRequest'] == 'createlevelclone') {
    
    require_once('../../../wp-load.php');
    
    try{
    $newrolename =$_POST['rolename'];
    $clonelevelkey =$_POST['clonerolekey'];
    
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Create new Clone',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
     if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $get_all_roles = get_option($get_all_roles_array);
     
     
     
     $new_role_key=strtolower($newrolename);
     $new_remove_space_role_kye=str_replace(" ","_",$new_role_key);
     $result = add_role($new_remove_space_role_kye, ucfirst($newrolename), array( 'read' => true, ) );
    // $get_all_roles[$new_remove_space_role_kye]['name'] =  ucfirst($newrolename);
    // $result  =    update_option ($get_all_roles_array, $get_all_roles);
     
     
     
     
     if (!empty($result)) {
        $msg['msg'] = 'New Level created';
        
        
        $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
        );
        $assign_new_role = get_posts( $args );
        
        
       
     
           foreach($assign_new_role as $taskIndex => $tasksObject) {
               
                   $tasksID = $tasksObject->ID;
                   $value_roles = get_post_meta( $tasksID, 'roles' , false);
                   if(in_array($clonelevelkey,$value_roles[0])){
                        array_push($value_roles[0],$new_remove_space_role_kye);
                        update_post_meta( $tasksID, 'roles' , $value_roles[0]);
                   }
             
               
           } 
            //echo $key;
            
       
     // $taskarray_update = update_option($test, $assign_new_role);
     }
      else {
        
        $msg['msg'] = ucfirst($newrolename).' Level already exists.';
       }
     echo   json_encode($msg);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
         return $e;
 }
    die(); 

}
else if ($_GET['contentManagerRequest'] == 'removerole') {
    
    require_once('../../../wp-load.php');
    
    try{
     
     $remove_role_name =$_POST['rolename'];
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);
     $lastInsertId = contentmanagerlogging('Remove Level',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
     if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $get_all_roles = get_option($get_all_roles_array);
     echo $remove_role_name;
     unset($get_all_roles[$remove_role_name]);
     update_option ($get_all_roles_array, $get_all_roles); 
     
     //$result = remove_role($remove_role_name);
     
    $msg['msg'] = 'Level Removed Successfuly.';
     
     echo   json_encode($msg);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
         return $e;
 }
    die(); 

}else if ($_GET['contentManagerRequest'] == 'portalsettingsupdate') {
    
    require_once('../../../wp-load.php');
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $applicationmoderationstatus = $object_data['applicationmoderationstatus'];
    //$oldvalues['ContentManager']['applicationmoderationstatus']=$applicationmoderationstatus;
    
    if(!empty(isset($_REQUEST['getheaderlogo']))){
        
        $oldvalues['ContentManager']['headerlogo'] = $_REQUEST['getheaderlogo'];
        
    }
    
    if(!empty(isset($_REQUEST['getheaderfavicon']))){
        
        $oldvalues['ContentManager']['sitefavicon'] = $_REQUEST['getheaderfavicon'];
        
    }
    
    if(!empty(isset($_REQUEST['getheaderimage']))){
        
        $oldvalues['ContentManager']['mainheader'] = $_REQUEST['getheaderimage'];
        
    }
    
     if(isset($_REQUEST['aptycode'])){
        
        $oldvalues['ContentManager']['aptycode'] = $_REQUEST['aptycode'];
        
    }
    
   
    
    foreach($_REQUEST as $requestIndex=>$requestData){
        
        if($requestIndex !='getheaderimage' && $requestIndex !='getheaderimageType' && $requestIndex !='sitefavicon'&& $requestIndex !='sitefaviconURL'&& $requestIndex !='headerlogoURL'&& $requestIndex !='headerlogo'){
            
            $oldvalues['ContentManager'][$requestIndex] = $requestData;
            
        }
        
    }
    
    update_option('ContenteManager_Settings', $oldvalues);
        
    echo   json_encode('Successfullyupdated');
    
    die();
   

}else if ($_GET['contentManagerRequest'] == 'uploadbase64image') {
    
    require_once('../../../wp-load.php');
    
    
    
    if(!empty($_REQUEST['imagetype']) && !empty($_REQUEST['imagedata'])){
        
        $filedata =  $_REQUEST['imagedata'];
        $filedataType =  $_REQUEST['imagetype'];
        
        $filedataurl = portalsettingheaderimage($filedata,'default-header-banner',$filedataType);
        
    }
    
    echo   json_encode($filedataurl); 
    
    die();
   

}else if ($_GET['contentManagerRequest'] == 'adminsettings') {
    
    require_once('../../../wp-load.php');
    
    $filedataurl = $_POST['oldheaderbannerurl'];
    $headerlogourl = $_POST['oldheaderlogourl'];
    $registration_notificationemails = $_POST['registration_notificationemails'];
    
    if(empty($_POST['oldheaderbannerurl'])){
        
        $filedata =  $_FILES['uploadedfile'];
        $filedataurl = resource_file_upload($filedata);
        
    }
    
    
    updateadmin_frontend_settings($_POST,$filedataurl);

}else if ($_GET['contentManagerRequest'] == 'bulkimportuser') {

    require_once('../../../wp-load.php');
  try{
      
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Bulk Import User',"Admin Action",serialize($_POST),$user_ID,$user_info->user_email,"pre_action_data");
      
   
    $file=$_FILES['file'];
    
    
    
    add_filter( 'upload_dir', 'wpse_183245_upload_dir' );
    $resourceurl = bulk_import_user_file($file);
    
    $loggin_data['fileurl']=$resourceurl;
    remove_filter( 'upload_dir', 'wpse_183245_upload_dir' );
   
   // echo '<pre>';
  //  print_r($loggin_data);exit;
    
    
    $responce="";
    if(!empty($resourceurl)){
    
      $filename_import = basename($resourceurl);      
      $responce  =  bulkimport_mappingdata($filename_import);
       
    }else{
       
         $responce = 'faild'; 
    }
    
    
    echo   json_encode($responce);
    
    
    contentmanagerlogging('Bulk Import User',"Admin Action",serialize($loggin_data),$user_ID,$user_info->user_email,$result);
    contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));
    
  }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}


function updateadminemailtemplate($data_array,$email_template_name){
    
      try{
          
          
         
          $email_template_name = preg_replace("/[^a-zA-Z0-9-\s]+/", "", html_entity_decode($email_template_name, ENT_QUOTES));
          
          $user_ID = get_current_user_id();
          $user_info = get_userdata($user_ID);
    
    $data_submit['data_array']=$data_array;
    $data_submit['template_name']=$email_template_name;
    $lastInsertId = contentmanagerlogging('Updated Report Template',"Admin Action",serialize($data_submit),$user_ID,$user_info->user_email,"pre_action_data");
       
      $settitng_key='AR_Contentmanager_Email_Template';
      $sponsor_info = get_option($settitng_key);
    
      
    
      $sponsor_info[$email_template_name]['emailsubject'] = $data_array['emailsubject'];
      $sponsor_info[$email_template_name]['emailboday'] = stripslashes($data_array['emailboday']);
      $sponsor_info[$email_template_name]['BCC'] = $data_array['BCC'];
     // $sponsor_info[$email_template_name]['CC'] = $data_array['CC'];
      $sponsor_info[$email_template_name]['RTO'] = $data_array['RTO'];
      $sponsor_info[$email_template_name]['fromname'] = $data_array['fromname'];
   
      update_option($settitng_key, $sponsor_info);
    
      
     
      $report_info = get_option($settitng_key);
      
      $i=0;
     foreach ($report_info as $key=>$value){
        
              
              $lis[$i] = $key;
              $i++;
         
          
      }
      
      
    echo   json_encode($lis);
    $updated_list['updated_list']=$lis;
      contentmanagerlogging_file_upload ($lastInsertId,serialize($updated_list));
    //  print_r($report_info);
} catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    
    
    
}


function roleassignnewtasks($request){
    
     try{
         
         
        
         
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Role Assigned New Tasks',"Admin Action",$request,$user_ID,$user_info->user_email,"pre_action_data");
        $role_name = $request['rolename'];
       // $test = 'custome_task_manager_data';
       // $result_old = get_option($test);
        $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
        );
        $assign_new_role = get_posts( $args );
        
        
        $tasksdatalist=json_decode(stripslashes($request['roleassigntaskdatalist']));
        
       
        
        
        $removetasklist = json_decode(stripslashes($request['removetasklist'])); 
        if(!empty($tasksdatalist)) {
        foreach($tasksdatalist as $key=>$taskKey){
          
               foreach($assign_new_role as $taskIndex => $tasksObject) {
                   
               $tasksID = $tasksObject->ID;
               $profile_field_name = get_post_meta( $tasksID, 'key' , false);
               $value_roles = get_post_meta( $tasksID, 'roles' , false);
               if($taskKey == $tasksID){
                   if(!in_array($role_name,$value_roles[0])){
                        array_push($value_roles[0],$role_name);
                   }
               }
               update_post_meta( $tasksID, 'roles' , $value_roles[0]);
           } 
            //echo $key;
            
        }
        }
        
       if(!empty($removetasklist)) {
        foreach($removetasklist as $key=>$taskKey){
           foreach($assign_new_role as $taskIndex => $tasksObject) {
               $tasksID = $tasksObject->ID;
               $value_roles = get_post_meta( $tasksID, 'roles' , false);
               $profile_field_name = get_post_meta( $tasksID, 'key' , false);
               if($taskKey == $tasksID){
                   foreach (array_keys($value_roles[0], $role_name) as $key1) {
                    unset($value_roles[0][$key1]);
                  } 
               }
               update_post_meta( $tasksID, 'roles' , $value_roles[0]);
               
           } 
            //echo $key;
            
        }
       }
       
        contentmanagerlogging_file_upload ($lastInsertId,$result);
        
       
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,$e);
   
      return $e;
    }
 
 die();  
    
    
}

function editrolename($request){
    
     try{
      
        
         
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Edit Level Name',"Admin Action",serialize($request),''.$user_ID,$user_info->user_email,"pre_action_data");
       
        $levelnamenew = $request['rolenewname'];
        $levelkey = $request['rolekey'];
        
        if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
        $get_all_roles = get_option($get_all_roles_array);
        $result_update = 'newvalue';
        foreach ($get_all_roles as $key => $item) {
            
            if(in_array($levelnamenew,$item)){
                $result_update = 'already';
                break;
            }
        }
        if($result_update == 'newvalue'){
            $get_all_roles[$levelkey]['name'] = $levelnamenew;
            $restults = update_option($get_all_roles_array, $get_all_roles);
            $result_status['msg']= 'update';
        }else{
           
            $result_status['msg']= 'already exists';
        }
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($result_status));
        
       echo json_encode($result_status);
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
    
    
}

function setpasswordcustome($password){
      
    
    
      try{
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Change Passowrd',"User Action",serialize($password),$user_ID,$user_info->user_email,"pre_action_data");
       
    $result = wp_set_password( $password, $user_ID );
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
    
      }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
}



function send_email($to,$subject,$body,$headers){
    
   $result = wp_mail($to, $subject, $body,$headers);
    return $result;
    
}





function getthereportsavalues($report_name){
    
    $settitng_key='AR_Contentmanager_Reports_Filter';
    $sponsor_info = get_option($settitng_key);
     echo   json_encode($sponsor_info[$report_name]);
    
}
function updateadminreport($data_array,$report_name){
    
      try{
          
    $new_data_array['report_name']=$report_name;
    $new_data_array['report_filter_value']=$data_array;
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $lastInsertId = contentmanagerlogging('Save Filter Report',"Admin Action",serialize($new_data_array),$user_ID,$user_info->user_email,"pre_action_data");
      
      $settitng_key='AR_Contentmanager_Reports_Filter';
      $sponsor_info = get_option($settitng_key);
    
      
    
      $sponsor_info[$report_name] = $data_array;
      update_option($settitng_key, $sponsor_info);
    
      
     
      $report_info = get_option($settitng_key);
      
      $i=0;
     foreach ($report_info as $key=>$value){
        
              
              $lis[$i] = $key;
              $i++;
         
          
      }
      
      
    echo   json_encode($lis);
    $new_list['new_updated_list']=$lis;
    contentmanagerlogging_file_upload ($lastInsertId,serialize($new_list));
    
      }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    //  print_r($report_info);
    
    
    
    
}



function plugin_settings(){
    
    $settitng_key='ContenteManager_Settings';
    $sponsor_info = get_option($settitng_key);
    echo   json_encode($sponsor_info);
    
}
// start remove sponsor resource

function remove_post_resource($post_id){
   
     
     $user_ID = get_current_user_id();
     $user_info = get_userdata($user_ID);  
     $lastInsertId = contentmanagerlogging('Trashed Resource',"Admin Action",$post_id,$user_ID,$user_info->user_email,"pre_action_data");
     $Responce = wp_trash_post($post_id);
     contentmanagerlogging_file_upload ($lastInsertId,serialize($Responce));
    
     return $Responce;
    //$responce = wp_delete_post($post_id);
   
    //print_r($responce);
    
}


// start create sponsor remove


function remove_sponsor_metas($user_id){
    //You should check nonces and user permissions at this point.
    //echo  $user_id;exit;
    

   
   $path =  dirname(__FILE__);
   $hom_path = str_replace("/wp-content/plugins/EGPL","",$path);
   
    
   if(!function_exists('wpmu_delete_user')) {
          
        include($hom_path."/wp-admin/includes/ms.php");
        require_once($hom_path.'/wp-admin/includes/user.php');
	
    }
  
    try{
    
    $all_meta_for_user = get_user_meta( $user_id );
    $all_meta_for_user['user_info'] = get_userdata( $user_id ); 
    $user_meta = get_userdata($user_id);

    $user_roles = $user_meta->roles;
    // echo '<pre>';
    // print_r($user_roles[0]);

   
 
    // echo '<pre>';
    // print_r('____'.$user_roles);
    //exit;
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);

    $lastInsertId = contentmanagerlogging('Delete User',"Admin Action",serialize($all_meta_for_user),$user_ID,$user_info->user_email,"pre_action_data");
    
    $user_blogs = get_blogs_of_user( $user_id );
    $blog_id = get_current_blog_id();

  
    
    if(count($user_blogs) > 2){
        
   
    
        remove_user_from_blog($user_id, $blog_id,1);
        $msg = "This user removes from this blog successfully";

        
    }else{
        
        remove_user_from_blog($user_id, $blog_id,1); 
        remove_user_from_blog($user_id, 1,1); 
       //$responce = wpmu_delete_user($user_id,1);
       $msg = "This user removes from this blog successfully";
    }
    
 
    contentmanagerlogging_file_upload ($lastInsertId,serialize($responce));
    
        echo $msg;
    //print_r($responce);
    }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
      
 }
  die();   
}

// start create sponsor update



function add_new_sponsor_metafields($user_id,$meta_array,$role){
    
    
    
    foreach ($meta_array as $key =>$value){
        
        update_user_option($user_id, $key, $value);
    }
    
    $leavel[strtolower($role)] = 1;
    $blog_id =get_current_blog_id();
   
    
    $result = update_user_option($user_id, 'capabilities',  $leavel);
    $t=time();
    
    $result = update_user_option($user_id, 'profile_updated',  $t*1000);
    
  
    
    return $result;
}

// start create resourse file upload






function resource_new_post($title,$resourceurl){
    
    
 
    $my_post = array(
     'post_title' => $title,
     'post_date' => '',
     'post_content' => '',
     'post_status' => 'publish',
     'post_type' => 'avada_portfolio',
       
  );
  $post_id = wp_insert_post( $my_post );
  
    if ($post_id) {
        // insert post meta
        $result = add_post_meta($post_id, 'excerpt', $resourceurl);
        return $result;
    }
  
}

function resource_file_upload($updatevalue){
   
    if(!empty($updatevalue)){
        if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
            //$upload_overrides = array( 'test_form' => false, 'mimes' => array('zip'=>'application/zip','eps'=>'application/postscript','ai' => 'application/postscript','jpg|jpeg|jpe' => 'image/jpeg','gif' => 'image/gif','png' => 'image/png','bmp' => 'image/bmp','pdf'=>'text/pdf','doc'=>'application/msword','docx'=>'application/msword','xlsx'=>'application/msexcel') );
        $mime_type = array(
	// Image formats
	'jpg|jpeg|jpe'                 => 'image/jpeg',
	'gif'                          => 'image/gif',
	'png'                          => 'image/png',
	'bmp'                          => 'image/bmp',
	'tif|tiff'                     => 'image/tiff',
	'ico'                          => 'image/x-icon',
        'eps'                          => 'application/postscript',
        'ai'                           =>  'application/postscript',
	// Video formats
	'asf|asx'                      => 'video/x-ms-asf',
	'wmv'                          => 'video/x-ms-wmv',
	'wmx'                          => 'video/x-ms-wmx',
	'wm'                           => 'video/x-ms-wm',
	'avi'                          => 'video/avi',
	'divx'                         => 'video/divx',
	'flv'                          => 'video/x-flv',
	'mov|qt'                       => 'video/quicktime',
	'mpeg|mpg|mpe'                 => 'video/mpeg',
	'mp4|m4v'                      => 'video/mp4',
	'ogv'                          => 'video/ogg',
	'webm'                         => 'video/webm',
	'mkv'                          => 'video/x-matroska',
	
	// Text formats
	'txt|asc|c|cc|h'               => 'text/plain',
	'csv'                          => 'text/csv',
	'tsv'                          => 'text/tab-separated-values',
	'ics'                          => 'text/calendar',
	'rtx'                          => 'text/richtext',
	'css'                          => 'text/css',
	'htm|html'                     => 'text/html',
	
	// Audio formats
	'mp3|m4a|m4b'                  => 'audio/mpeg',
	'ra|ram'                       => 'audio/x-realaudio',
	'wav'                          => 'audio/wav',
	'ogg|oga'                      => 'audio/ogg',
	'mid|midi'                     => 'audio/midi',
	'wma'                          => 'audio/x-ms-wma',
	'wax'                          => 'audio/x-ms-wax',
	'mka'                          => 'audio/x-matroska',
	
	// Misc application formats
	'rtf'                          => 'application/rtf',
	'js'                           => 'application/javascript',
	'pdf'                          => 'application/pdf',
	'swf'                          => 'application/x-shockwave-flash',
	'class'                        => 'application/java',
	'tar'                          => 'application/x-tar',
	'zip'                          => 'application/zip',
	'gz|gzip'                      => 'application/x-gzip',
	'rar'                          => 'application/rar',
	'7z'                           => 'application/x-7z-compressed',
	'exe'                          => 'application/x-msdownload',
	
	// MS Office formats
	'doc'                          => 'application/msword',
	'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
	'wri'                          => 'application/vnd.ms-write',
	'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
	'mdb'                          => 'application/vnd.ms-access',
	'mpp'                          => 'application/vnd.ms-project',
	'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
	'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
	'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
	'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
	'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
	'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
	'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
	'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
	
	// OpenOffice formats
	'odt'                          => 'application/vnd.oasis.opendocument.text',
	'odp'                          => 'application/vnd.oasis.opendocument.presentation',
	'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
	'odg'                          => 'application/vnd.oasis.opendocument.graphics',
	'odc'                          => 'application/vnd.oasis.opendocument.chart',
	'odb'                          => 'application/vnd.oasis.opendocument.database',
	'odf'                          => 'application/vnd.oasis.opendocument.formula',
	
	// WordPerfect formats
	'wp|wpd'                       => 'application/wordperfect',
	
	// iWork formats
	'key'                          => 'application/vnd.apple.keynote',
	'numbers'                      => 'application/vnd.apple.numbers',
	'pages'                        => 'application/vnd.apple.pages',
);    
        $upload_overrides = array( 'test_form' => false,$mime_type);
        $movefile = wp_handle_upload( $updatevalue, $upload_overrides );
        if(!empty($movefile['file'])){
          
            return $movefile['url'];
            
        }
  }
    
}

function bulk_import_user_file($updatevalue){
   
    if(!empty($updatevalue)){
        if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    $mime_type = array(
	// Image formats
	'jpg|jpeg|jpe'                 => 'image/jpeg',
	'gif'                          => 'image/gif',
	'png'                          => 'image/png',
	'bmp'                          => 'image/bmp',
	'tif|tiff'                     => 'image/tiff',
	'ico'                          => 'image/x-icon',
        'eps'                          => 'application/postscript',
        'ai'                           =>  'application/postscript',
	// Video formats
	'asf|asx'                      => 'video/x-ms-asf',
	'wmv'                          => 'video/x-ms-wmv',
	'wmx'                          => 'video/x-ms-wmx',
	'wm'                           => 'video/x-ms-wm',
	'avi'                          => 'video/avi',
	'divx'                         => 'video/divx',
	'flv'                          => 'video/x-flv',
	'mov|qt'                       => 'video/quicktime',
	'mpeg|mpg|mpe'                 => 'video/mpeg',
	'mp4|m4v'                      => 'video/mp4',
	'ogv'                          => 'video/ogg',
	'webm'                         => 'video/webm',
	'mkv'                          => 'video/x-matroska',
	
	// Text formats
	'txt|asc|c|cc|h'               => 'text/plain',
	'csv'                          => 'text/csv',
	'tsv'                          => 'text/tab-separated-values',
	'ics'                          => 'text/calendar',
	'rtx'                          => 'text/richtext',
	'css'                          => 'text/css',
	'htm|html'                     => 'text/html',
	
	// Audio formats
	'mp3|m4a|m4b'                  => 'audio/mpeg',
	'ra|ram'                       => 'audio/x-realaudio',
	'wav'                          => 'audio/wav',
	'ogg|oga'                      => 'audio/ogg',
	'mid|midi'                     => 'audio/midi',
	'wma'                          => 'audio/x-ms-wma',
	'wax'                          => 'audio/x-ms-wax',
	'mka'                          => 'audio/x-matroska',
	
	// Misc application formats
	'rtf'                          => 'application/rtf',
	'js'                           => 'application/javascript',
	'pdf'                          => 'application/pdf',
	'swf'                          => 'application/x-shockwave-flash',
	'class'                        => 'application/java',
	'tar'                          => 'application/x-tar',
	'zip'                          => 'application/zip',
	'gz|gzip'                      => 'application/x-gzip',
	'rar'                          => 'application/rar',
	'7z'                           => 'application/x-7z-compressed',
	'exe'                          => 'application/x-msdownload',
	
	// MS Office formats
	'doc'                          => 'application/msword',
	'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
	'wri'                          => 'application/vnd.ms-write',
	'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
	'mdb'                          => 'application/vnd.ms-access',
	'mpp'                          => 'application/vnd.ms-project',
	'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
	'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
	'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
	'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
	'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
	'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
	'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
	'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
	
	// OpenOffice formats
	'odt'                          => 'application/vnd.oasis.opendocument.text',
	'odp'                          => 'application/vnd.oasis.opendocument.presentation',
	'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
	'odg'                          => 'application/vnd.oasis.opendocument.graphics',
	'odc'                          => 'application/vnd.oasis.opendocument.chart',
	'odb'                          => 'application/vnd.oasis.opendocument.database',
	'odf'                          => 'application/vnd.oasis.opendocument.formula',
	
	// WordPerfect formats
	'wp|wpd'                       => 'application/wordperfect',
	
	// iWork formats
	'key'                          => 'application/vnd.apple.keynote',
	'numbers'                      => 'application/vnd.apple.numbers',
	'pages'                        => 'application/vnd.apple.pages',
);
       $upload_overrides = array( 'test_form' => false,$mime_type); 
            $movefile = wp_handle_upload( $updatevalue, $upload_overrides );
           
        if(!empty($movefile['file'])){
          
            return $movefile['url'];
            
        }
  }
    
}


// start load report



function getReportsdatanew($report_name,$usertimezone){
    
  
    if($report_name != "defult"){
       
    $settitng='AR_Contentmanager_Reports_Filter';
    $sponsor_report_data = get_option($settitng);
   
            
   }
    //$test = 'custome_task_manager_data';
    //$result_task_array_list = get_option($test);
    $settitng_key = 'ContenteManager_Settings';
    $sponsor_info = get_option($settitng_key);
    $sponsor_name = $sponsor_info['ContentManager']['sponsor_name'];
    //  echo '<pre>';
    // print_r($result);
    $idx = 0;
    $labelArray = null;
   

    global $wpdb;
    global $wp_roles;
    $tasklable = $_POST['tasklabel'];
    $taskestatus = $_POST['taskestatus'];
    $sponsorrole = $_POST['sponsorrole'];
    $all_roles = $wp_roles->get_names();
   
    $query = "SELECT DISTINCT ID as user_id
    FROM " . $wpdb->users;

    $query_th = "SELECT meta_key
     FROM " . $wpdb->usermeta . " WHERE  `user_id` = 1 AND  `meta_key` LIKE  'task_%'";

    $table_head = $wpdb->get_results($query_th);
   

    $additional_settings = get_option( 'EGPL_Settings_Additionalfield' );
     if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $get_all_roles = get_option($get_all_roles_array);
    
    $k = 14;
    $unique_id=0;
    $showhideMYFieldsArray = array();
     $Rname = "";
     $Fname = "";
     $Lname = "";
     $Remail = "";
     $Remail = "";
     $Rtype = "";
     $Rlastlogin = "";
     $Rdate = "";
     $RRole = "";
     $welcomeemail="";
     $userID="";
     $companylogourl="";
     $mapdynamicsid="";
     $status="";
     $companylogourl_show=true;
     $mapdynamicsid_show=true;
     $userID_show=true;
     $shoerolefiltervalue=true;
     $Rname_show = true;
     $CompanyName_show = false;
     $Remail_show = false;
     $Rlastlogin_show = false;
     $Fname_show=false;
     $Rdate_show = false;
     $RRole_show=false;
     $Lname_show=false;
     $welcomeemail_show=true;
     $status_show = true;
     
     
      if($report_name != "defult"){
    
         if (array_key_exists("sponsor_name", $sponsor_report_data[$report_name])){
                $Rname = $sponsor_report_data[$report_name]['sponsor_name'];
                $Rname_show = false;
          }else{
             $Rname_show = true; 
          }
          if (array_key_exists("Email", $sponsor_report_data[$report_name])){
                
                  $Remail = $sponsor_report_data[$report_name]['Email'];
                  $Remail_show = false;
          }else{
             $Remail_show = true; 
          }  
          if (array_key_exists("company_name", $sponsor_report_data[$report_name])){
                $CompanyName = $sponsor_report_data[$report_name]['company_name'];
                $CompanyName_show = false;
          }else{
             $CompanyName_show = true; 
          } 
           
          if (array_key_exists("last_login", $sponsor_report_data[$report_name])){
                $Rlastlogin = $sponsor_report_data[$report_name]['last_login'];
                $Rlastlogin_show = false;
          }else{
             $Rlastlogin_show = true; 
          } 
          if (array_key_exists("user_register_date", $sponsor_report_data[$report_name])){
                $Rdate = $sponsor_report_data[$report_name]['user_register_date'];
                $Rdate_show = false;
                
          }else{
             $Rdate_show = true; 
          }
          if (array_key_exists("Role", $sponsor_report_data[$report_name])){
                $RRole = $sponsor_report_data[$report_name]['Role'];
                $RRole_show=false;
          }else{
             $RRole_show = true; 
          }
           if (array_key_exists("first_name", $sponsor_report_data[$report_name])){
                $Fname = $sponsor_report_data[$report_name]['first_name'];
                $Fname_show=false;
          }else{
             $Fname_show = true; 
          }
          if (array_key_exists("last_name", $sponsor_report_data[$report_name])){
                $Lname = $sponsor_report_data[$report_name]['last_name'];
                $Lname_show=false;
          }else{
             $Lname_show = true; 
          }
          
          if (array_key_exists("convo_welcomeemail_datetime", $sponsor_report_data[$report_name])){
                $welcomeemail = $sponsor_report_data[$report_name]['convo_welcomeemail_datetime'];
                $welcomeemail_show=false;
          }else{
             $welcomeemail_show = true; 
          }
          
          if (array_key_exists("exhibitor_map_dynamics_ID", $sponsor_report_data[$report_name])){
                $mapdynamicsid = $sponsor_report_data[$report_name]['exhibitor_map_dynamics_ID'];
                $mapdynamicsid_show=false;
          }else{
             $mapdynamicsid_show = true; 
          }
          
          if (array_key_exists("user_profile_url", $sponsor_report_data[$report_name])){
                $companylogourl = $sponsor_report_data[$report_name]['user_profile_url'];
                $companylogourl_show=false;
          }else{
             $companylogourl_show = true; 
          }
          
          if (array_key_exists("wp_user_id", $sponsor_report_data[$report_name])){
                $userID = $sponsor_report_data[$report_name]['wp_user_id'];
                $userID_show=false;
          }else{
             $userID_show = true; 
          }
          
         if (array_key_exists("wp_user_id", $sponsor_report_data[$report_name])){
                $userID = $sponsor_report_data[$report_name]['wp_user_id'];
                $userID_show=false;
          }else{
             $userID_show = true; 
          }
          if (array_key_exists("selfsignupstatus", $sponsor_report_data[$report_name])){
                $status = $sponsor_report_data[$report_name]['selfsignupstatus'];
                $status_show=false;
          }else{
             $status_show = true; 
          }
       
          
        
   }
   
    $showhideMYFieldsArray['action_edit_delete'] = array('index' => 1, 'type' => 'string','unique' => true, 'hidden' => false, 'friendly'=> "Action" ,'filter'=>false);
    $showhideMYFieldsArray['company_name'] = array('index' => 2, 'type' => 'string','unique' => true, 'sortOrder'=>"asc", 'hidden' => $CompanyName_show,'friendly'=> "Company Name",'filter'=>$CompanyName);
    $showhideMYFieldsArray['Role'] = array('index' => 3, 'type' => 'string','unique' => true, 'hidden' => $RRole_show,'friendly'=> "Level",'filter'=>$RRole);
    $showhideMYFieldsArray['last_login'] = array('index' => 4, 'type' => 'date','unique' => true, 'hidden' => $Rlastlogin_show,'friendly'=> "Last login",'filter'=>$Rlastlogin);
    
    $showhideMYFieldsArray['first_name'] = array('index' => 5, 'type' => 'string','unique' => true, 'hidden' => $Fname_show, 'friendly'=> "First Name",'filter'=>$Fname);
    $showhideMYFieldsArray['last_name'] = array('index' => 6, 'type' => 'string','unique' => true, 'hidden' => $Lname_show, 'friendly'=> "Last Name",'filter'=>$Lname);
    
    $showhideMYFieldsArray['user_name'] = array('index' => 7, 'type' => 'string','unique' => true, 'hidden' => $Rname_show, 'friendly'=> $sponsor_name." Name",'filter'=>$Rname);
    
    $showhideMYFieldsArray['Email'] = array('index' => 8, 'type' => 'string','unique' => true, 'hidden' => $Remail_show,'friendly'=> "Email",'filter'=>$Remail);
    $showhideMYFieldsArray['convo_welcomeemail_datetime'] = array('index' => 9, 'type' => 'date','unique' => true, 'hidden' => $welcomeemail_show,'friendly'=> "Welcome Email Sent On",'filter'=>$welcomeemail);
    
    $showhideMYFieldsArray['exhibitor_map_dynamics_ID'] = array('index' => 10, 'type' => 'string','unique' => true, 'hidden' => $mapdynamicsid_show,'friendly'=> "Floorplan ID",'filter'=>$mapdynamicsid);
    $showhideMYFieldsArray['user_profile_url'] = array('index' => 11, 'type' => 'string','unique' => true, 'hidden' => $companylogourl_show,'friendly'=> "User Company Logo Url",'filter'=>$companylogourl);
    $showhideMYFieldsArray['wp_user_id'] = array('index' => 12, 'type' => 'string','unique' => true, 'hidden' => $userID_show,'friendly'=> "User ID",'filter'=>$userID);
    $showhideMYFieldsArray['selfsignupstatus'] = array('index' => 13, 'type' => 'string','unique' => true, 'hidden' => $status_show,'friendly'=> "Status",'filter'=>$status);
    
    
    if(!empty($additional_settings)){
        $index_count = $k;
        foreach ($additional_settings as $key=>$valuename){
            $report_key_value = "";
            $showhidevalue = true;

            if ($report_name != "defult") {
                if (array_key_exists($additional_settings[$key]['key'], $sponsor_report_data[$report_name])) {

                    $report_key_value = $sponsor_report_data[$report_name][$additional_settings[$key]['key']];
                    $showhidevalue = false;
                }
            }
            
            $showhideMYFieldsArray[$additional_settings[$key]['key']] = array('index' => $index_count, 'type' => 'string','unique' => true, 'hidden' => $showhidevalue,'friendly'=> $additional_settings[$key]['name'],'filter'=>$report_key_value);
            $index_count++;  
            
        }
        
        $k=$index_count+1;
    }
  
    
         
    
    
   // uasort($get_keys_array_result['profile_fields'], "cmp2");
    if(!empty($result_task_array_list)){
        foreach ($result_task_array_list['profile_fields'] as $profile_field_name => $profile_field_settings) {
        $report_key_value = "";
        $showhidevalue = true;

        if ($report_name != "defult") {
            if (array_key_exists($profile_field_name, $sponsor_report_data[$report_name])) {

                $report_key_value = $sponsor_report_data[$report_name][$profile_field_name];
                $showhidevalue = false;
            }
        }
        

            if ($profile_field_settings['type'] == 'datetime') {
                
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'date', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_datetime'] = array('index' => $k, 'type' => 'date', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Datetime','filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_status'] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Status','filter'=>$report_key_value);
               
                $k++;
                
            } else if ($profile_field_settings['type'] == 'color') {
                
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_datetime'] = array('index' => $k, 'type' => 'date', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Datetime','filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_status'] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Status','filter'=>$report_key_value);
               $k++;
            
                
            } else if ($profile_field_settings['type'] == 'text') {
                
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_datetime'] = array('index' => $k, 'type' => 'date', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Datetime','filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_status'] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Status','filter'=>$report_key_value);
               
                $k++;
                
            } else if ($profile_field_settings['type'] == 'textarea') {
                
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_datetime'] = array('index' => $k, 'type' => 'date', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Datetime','filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_status'] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Status','filter'=>$report_key_value);
               
                $k++;
                
            } else {
                
                $showhideMYFieldsArray[$profile_field_name] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'],'filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_datetime'] = array('index' => $k, 'type' => 'date', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Datetime','filter'=>$report_key_value);
                $k++;
                
                $showhideMYFieldsArray[$profile_field_name.'_status'] = array('index' => $k, 'type' => 'string', 'unique' => true, 'hidden' => $showhidevalue,'friendly'=> $profile_field_settings['label'].' Status','filter'=>$report_key_value);
               
                $k++;
            }
        
            
            
    }
    }
   // echo '<pre>';
          //  print_r($showhideMYFieldsArray);exit;
        $column_name_uppercase = $showhideMYFieldsArray;//array_change_key_case($showhideMYFieldsArray, CASE_UPPER);
        $newStr = strtoupper($showhidefields);
        //print_r ($newStr);
        $base_url = "http://" . $_SERVER['SERVER_NAME'];
        $result_user_id = $wpdb->get_results($query);
        $allMetaForAllUsers = array();
        $myNewArray = array();
        $site_prefix = $wpdb->get_blog_prefix();
        $zee = 0;
        $new = 0;

        
        
       
          
  foreach ($result_user_id as $aid) {


        //$user_data = get_userdata($aid->user_id);
       
      //echo  $aid['wp_user_login_date_time'].'<br>';
      $user_data = get_userdata($aid->user_id);
      $all_meta_for_user = get_user_meta($aid->user_id);
      
  
      
 if(!empty($all_meta_for_user) && !in_array("administrator", $user_data->roles)){ 
     
     
           //echo '<pre>';
     //print_r($all_meta_for_user);exit;
     
   if (!empty($all_meta_for_user['wp_user_login_date_time'][0])) {

       
            $login_date = date('d-M-Y H:i:s', $all_meta_for_user['wp_user_login_date_time'][0]);
           // echo strtotime($login_date_time);exit;
            if($usertimezone > 0){
                $login_date_time = (new DateTime($login_date))->sub(new DateInterval('PT'.abs($usertimezone).'H'))->format('d-M-Y H:i:s');
            }else{
                $login_date_time = (new DateTime($login_date))->add(new DateInterval('PT'.abs($usertimezone).'H'))->format('d-M-Y H:i:s');
                
            }
            $timestamp = strtotime($login_date_time) *1000 ;
           // echo $timestamp; 
           // echo date('m/d/Y H:i:s', $timestamp);exit;
            
        } else {
            $timestamp = "";
        }
      if (!empty($all_meta_for_user[$site_prefix.'convo_welcomeemail_datetime'][0])) {

       
            $last_send_welcome_email = date('d-M-Y H:i:s', $all_meta_for_user[$site_prefix.'convo_welcomeemail_datetime'][0]/1000);
           
            if($usertimezone > 0){
                $last_send_welcome_date_time = (new DateTime($last_send_welcome_email))->sub(new DateInterval('PT'.abs($usertimezone).'H'))->format('d-M-Y H:i:s');
            }else{
                $last_send_welcome_date_time = (new DateTime($last_send_welcome_email))->add(new DateInterval('PT'.abs($usertimezone).'H'))->format('d-M-Y H:i:s');
                
            }
            $last_send_welcome_timestamp = strtotime($last_send_welcome_date_time) *1000 ;
           // echo $timestamp; 
           // echo date('m/d/Y H:i:s', $timestamp);exit;
            
        } else {
            $last_send_welcome_timestamp = "";
        }
       $company_name = $all_meta_for_user[$site_prefix.'company_name'][0];
       $myNewArray['action_edit_delete'] = '<p style="width:83px !important;"><a href="/edit-user/?sponsorid='.$aid->user_id.'" target="_blank" title="Edit User Profile"><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-pencil-square-o" style="color:#262626;"></i></span></a><a style="margin-left: 10px;" target="_blank" href="/edit-sponsor-task/?sponsorid='.$aid->user_id.'" title="User Tasks"><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-th-list" style="color:#262626;"></i></span></a><a onclick="view_profile(this)" id="'.$unique_id.'" name="viewprofile"  style="cursor: pointer;color:red;margin-left: 10px;" title="View Profile" ><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-eye" style="color:#262626;"></i></a><a onclick="delete_sponsor_meta(this)" id="'.$aid->user_id.'" name="delete-sponsor"  style="cursor: pointer;color:red;margin-left: 10px;" title="Remove User" ><span class="icon-wrapper circle-no"><i class="fusion-li-icon fa fa-times-circle" style="color:#262626;"></i></a></p>';

        $unique_id++;
	   	
    
        $myNewArray['company_name'] = $company_name;
        $myNewArray['Role'] = $get_all_roles[$user_data->roles[0]]['name'];
        $myNewArray['last_login'] = $timestamp;
     
        $myNewArray['first_name'] = $all_meta_for_user[$site_prefix.'first_name'][0];//$user_data->first_name;
        $myNewArray['last_name'] = $all_meta_for_user[$site_prefix.'last_name'][0];//$user_data->last_name;
        $myNewArray['user_name'] = $user_data->display_name;
        $myNewArray['Email'] = $user_data->user_email;
        $myNewArray['convo_welcomeemail_datetime'] =  $last_send_welcome_timestamp;
        $myNewArray['exhibitor_map_dynamics_ID'] = $all_meta_for_user[$site_prefix.'exhibitor_map_dynamics_ID'][0];
        $myNewArray['user_profile_url'] = $all_meta_for_user[$site_prefix.'user_profile_url'][0];
        $myNewArray['wp_user_id'] = $aid->user_id;
        $myNewArray['selfsignupstatus'] = $all_meta_for_user[$site_prefix.'selfsignupstatus'][0];
        
       
        if(!empty($additional_settings)){
       
            foreach ($additional_settings as $key=>$valuename){
                $addition_field = $additional_settings[$key]['key'];
             $myNewArray[$additional_settings[$key]['key']] = $all_meta_for_user[$site_prefix.$addition_field][0];
             
            }
        }
        
       
       
        
        
  //uasort($get_keys_array_result['profile_fields'], "cmp2");
        foreach ($result_task_array_list['profile_fields'] as $profile_field_name => $profile_field_settings) {
        
         
               
                if ($profile_field_settings['type'] == 'color') {
                    $file_info = unserialize($all_meta_for_user[$profile_field_name][0]);
                   
                   
                    if (!empty($file_info)) {
                        $myNewArray[$profile_field_name] = '<a href="'.$base_url.'/wp-content/plugins/EGPL/download-lib.php?userid=' . $aid->user_id . '&fieldname=' . $profile_field_name . '" >Download</a>';
                    
                        
                        
                    } else {
                        $myNewArray[$profile_field_name] = '';
                    }
                    if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                    $myNewArray[$profile_field_name.'_datetime'] =$datemy;
                    $myNewArray[$profile_field_name.'_status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                    
                    if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Pending") {
                        $myNewArray[$profile_field_name . '_statusCls'] = "red";
                    } else if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Complete") {
                        $myNewArray[$profile_field_name . '_statusCls'] = "green";
                    } else {
                        $myNewArray[$profile_field_name.'_statusCls'] = "blue";
                    }
                    
                    
                } else {

                 
                      if ($profile_field_settings['type'] == 'text') {
                             

                        $myNewArray[$profile_field_name] = $all_meta_for_user[$profile_field_name][0];
                        if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                            if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                            $myNewArray[$profile_field_name . '_datetime'] = $datemy;
                            $myNewArray[$profile_field_name . '_status'] = $all_meta_for_user[$profile_field_name . '_status'][0];
                            
                            
                        if ($all_meta_for_user[$profile_field_name . '_status'][0] == "Pending") {
                            $myNewArray[$profile_field_name . '_statusCls'] = "red";
                        } else if ($all_meta_for_user[$profile_field_name . '_status'][0] == "Complete") {
                            $myNewArray[$profile_field_name . '_statusCls'] = "green";
                        } else {
                            $myNewArray[$profile_field_name . '_statusCls'] = "blue";
                        }

                       
                    } 
                        else if ($profile_field_settings['type'] == 'textarea') {

                            $myNewArray[$profile_field_name] =  $all_meta_for_user[$profile_field_name][0];
                            if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                            $myNewArray[$profile_field_name.'_datetime'] =$datemy;
                            $myNewArray[$profile_field_name.'_status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                            if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Pending") {
                                $myNewArray[$profile_field_name . '_statusCls'] = "red";
                            } else if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Complete") {
                                $myNewArray[$profile_field_name . '_statusCls'] = "green";
                            } else {
                                $myNewArray[$profile_field_name.'_statusCls'] = "blue";
                            }
                    
                            
                            
                            //$newarray[$new]=$all_meta_for_user[$profile_field_name][0];
                            // $new++;
                        }
                        else if ($profile_field_settings['type'] == 'select') {

                            $myNewArray[$profile_field_name] =  $all_meta_for_user[$profile_field_name][0];
                          
                           if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                            $myNewArray[$profile_field_name.'_datetime'] =$datemy;
                            $myNewArray[$profile_field_name.'_status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                            if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Pending") {
                                $myNewArray[$profile_field_name . '_statusCls'] = "red";
                            } else if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Complete") {
                                $myNewArray[$profile_field_name . '_statusCls'] = "green";
                            } else {
                                $myNewArray[$profile_field_name.'_statusCls'] = "blue";
                            }
                          
                            //$newarray[$new]=$all_meta_for_user[$profile_field_name][0];
                            // $new++;
                        }  
                        else if ($profile_field_settings['type'] == 'select-2') {
                            $myNewArray[$profile_field_name] =  $all_meta_for_user[$profile_field_name][0];
                            if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                            $myNewArray[$profile_field_name.'_datetime'] =$datemy;
                            $myNewArray[$profile_field_name.'_status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                            if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Pending") {
                                $myNewArray[$profile_field_name . '_statusCls'] = "red";
                            } else if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Complete") {
                                $myNewArray[$profile_field_name . '_statusCls'] = "green";
                            } else {
                                $myNewArray[$profile_field_name.'_statusCls'] = "blue";
                            }
                          
                        }
                        else {
                           

                            $myNewArray[$profile_field_name] = $all_meta_for_user[$profile_field_name][0];
                            if (!empty($all_meta_for_user[$profile_field_name . '_datetime'][0])) {
                        if (strpos($all_meta_for_user[$profile_field_name . '_datetime'][0], 'AM') !== false) {
                            

                            $datevalue = str_replace(":AM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                            
                            
                        } else {
                            $datevalue = str_replace(":PM", "", $all_meta_for_user[$profile_field_name . '_datetime'][0]);
                            $register_date = date('d-M-Y H:i:s', strtotime($datevalue));
                            if ($usertimezone > 0) {
                                $login_date_time = (new DateTime($register_date))->sub(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            } else {
                                $login_date_time = (new DateTime($register_date))->add(new DateInterval('PT' . abs($usertimezone) . 'H'))->format('d-M-Y H:i:s');
                            }
                            
                            $datemy = strtotime($login_date_time) * 1000;
                        }
                    } else {
                        $datemy = "";
                    }
                            $myNewArray[$profile_field_name.'_datetime'] =$datemy;
                             $myNewArray[$profile_field_name.'_status'] = $all_meta_for_user[$profile_field_name.'_status'][0];
                            if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Pending") {
                                $myNewArray[$profile_field_name . '_statusCls'] = "red";
                            } else if ($all_meta_for_user[$profile_field_name.'_status'][0] == "Complete") {
                                $myNewArray[$profile_field_name . '_statusCls'] = "green";
                            } else {
                                $myNewArray[$profile_field_name.'_statusCls'] = "blue";
                            }
                    
                            
                        }
                    } 
              //  echo '<pre>';
              //  print_r($myNewArray);exit;
            }
        
       // $row_name_uppercase = array_change_key_case($myNewArray, CASE_UPPER);
        $allMetaForAllUsers[$zee] = $myNewArray;
       // echo $zee.'<br>';
        $zee++;
   
   }else{
    
       contentmanagerlogging('Load Report Data',"Admin Action",serialize($aid->user_id),$user_ID,$user_info->user_email,$aid->user_id );
     
 }    
}



    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $current_admin_email =$aid->user_email;
    $oldvalues = get_option( 'ContenteManager_Settings' );
     
    $attendytype=$oldvalues['ContentManager']['attendytype_key'];
    $eventdate = $oldvalues['ContentManager']['eventdate'];
    $sitename=get_bloginfo();
    $settings['attendytype_key'] =$attendytype;
    $settings['Currentadminemail'] =$current_admin_email;
    $settings['sitename'] =$sitename;
    $settings['eventdate'] =$eventdate;
    
    
     
  //  echo '<pre>';
   // print_r($allMetaForAllUsers);
    
    
    
     echo json_encode($column_name_uppercase) . '//' . json_encode($allMetaForAllUsers) .'//'.json_encode($settings) ;
     
     
     die();
}

add_action('wp_enqueue_scripts', 'add_contentmanager_js');
function add_contentmanager_js(){
      wp_enqueue_script('safari4', plugins_url().'/EGPL/js/my_task_update.js', array('jquery'),'5.2.8', true);
    
     wp_enqueue_script( 'jquery.alerts', plugins_url() . '/EGPL/js/jquery.alerts.js', array(), '1.1.0', true );
     wp_enqueue_script( 'boot-date-picker', plugins_url() . '/EGPL/js/bootstrap-datepicker.js', array(), '1.2.0', true );
     //wp_enqueue_script( 'jquerydatatable', plugins_url() . '/EGPL/js/jquery.dataTables.js', array(), '1.2.0', true );
     //wp_enqueue_script( 'shCore', plugins_url() . '/EGPL/js/shCore.js', array(), '1.2.0', true );
     //wp_enqueue_script( 'demo', plugins_url() . '/EGPL/js/demo.js', array(), '1.2.0', true );
     //wp_enqueue_script( 'bootstrap.min', plugins_url() . '/EGPL/js/bootstrap.min.js', array(), '1.2.0', true );
    
     //wp_enqueue_script('safari1', plugins_url('/js/modernizr.custom.js', __FILE__), array('jquery'));
     //wp_enqueue_script('safari2', plugins_url('/js/classie.js', __FILE__), array('jquery'));
     //wp_enqueue_script('safari3', plugins_url('/js/progressButton.js', __FILE__), array('jquery'));
   
    // wp_enqueue_script('bulk-email', plugins_url('/js/bulk-email.js', __FILE__), array('jquery'));
     //wp_enqueue_script('sweetalert', plugins_url('/EGPL/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.min.js'), array('jquery'));
     wp_enqueue_script('password_strength_cal', plugins_url('/js/passwordstrength.js', __FILE__), array('jquery'));
     
     wp_enqueue_script( 'selfsignupjs', plugins_url('/EGPL/js/selfsignupjs.js'), array(), '3.1', true );
     wp_enqueue_script( 'jquery-confirm', plugins_url('/EGPL/js/jquery-confirm.js'), array(), '1.2.7', true );
      
     //wp_enqueue_script('select2', plugins_url('/cmtemplate/js/lib/select2/select2.full.js', __FILE__), array('jquery'));
    
     wp_enqueue_script( 'order-history', plugins_url('/EGPL/js/orderhistory.js'), array(), '1.6.0', true );
     wp_enqueue_script( 'Egpl-filters', plugins_url('/EGPL/js/egplfilters.js'), array(), '1.2.6', true );
     
   
}

add_action('wp_enqueue_scripts', 'my_contentmanager_style');

function my_contentmanager_style() {
    //wp_enqueue_style('my-mincss', plugins_url() .'/EGPL/css/bootstrap.min.css');
    //wp_enqueue_style('my-sweetalert', plugins_url() .'/EGPL/cmtemplate/css/lib/bootstrap-sweetalert/sweetalert.css');
    //wp_enqueue_style('my-datepicker', plugins_url().'/EGPL/css/datepicker.css');
    //wp_enqueue_style('jquery.dataTables', plugins_url().'/EGPL/css/jquery.dataTables.css');
    //wp_enqueue_style('shCore', plugins_url().'/EGPL/css/shCore.css');

   // wp_enqueue_style('jquery-confirm-css', plugins_url() .'/EGPL/css/jquery-confirm.css',array(), '1.2', 'all');
   
  
    //wp_enqueue_style('my-datatable-tools', plugins_url().'/EGPL/css/dataTables.tableTools.css');
   // wp_enqueue_style('cleditor-css', plugins_url() .'/EGPL/css/jquery.cleditor.css');
   // wp_enqueue_style('contentmanager-css', plugins_url() .'/EGPL/css/forntend.css');
    //wp_enqueue_style('my-admin-theme1', plugins_url() .'/EGPL/css/component.css',array(), '2.58', 'all');
    //wp_enqueue_style('my-admin-theme', plugins_url('css/normalize.css', __FILE__));
  
   
}

function my_plugin_activate() {
    
    global $wpdb;
    include 'defult-content.php';
    
                
// check if it is a multisite network


//$blog_id = get_current_blog_id();

// check if the plugin has been activated on the network or on a single site
// get ids of all sites

           $blog_list = get_blog_list( 0, 'all' );
           
   
            foreach ($blog_list as $blog_id) {
                if($blog_id['blog_id'] != 1){
                    
                    
                    
                switch_to_blog($blog_id['blog_id']);
                
               
                
                

                $labels = array(
                    'name'                =>  'ExpoGenie Log',
                    'singular_name'       =>  'ExpoGenie Log',
                    'add_new'             =>  'Add New',
                    'add_new_item'        =>  'Add New Log',
                    'edit_item'           =>  'Edit Log',
                    'new_item'            =>  'New Log', 
                    'all_items'           =>  'All Logs',
                    'view_item'           =>  'View Log',
                    'search_items'        =>  'Search Log',
                    'not_found'           =>  'No Log found',
                    'not_found_in_trash'  =>  'No Log found in Trash',
                    'menu_name'           =>  'Log',
                  );

              $supports = array( 'title', 'editor' );

              $slug = get_theme_mod( 'event_permalink' );
              $slug = ( empty( $slug ) ) ? 'event' : $slug;

              $args = array(
                'labels'              => $labels,
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'query_var'           => true,
                'rewrite'             => array( 'slug' => $slug ),
                'capability_type'     => 'post',
                'has_archive'         => true,
                'hierarchical'        => false,
                'menu_position'       => null,
                'supports'            => $supports,
              );

           $getError =    register_post_type( 'expo_genie_log', $args );
                
        
                
                // create tables for each site
                $get_all_roles_array = 'wp_'.$blog_id['blog_id'].'_user_roles';
                $get_all_roles = get_option($get_all_roles_array);
                if (!empty($get_all_roles)) {
                    foreach ($get_all_roles as $key => $item) {

                        if ($item['name'] != 'Administrator') {

                            if (!array_key_exists('unfiltered_upload', $get_all_roles[$key]['capabilities'])) {
                                $get_all_roles[$key]['capabilities']['unfiltered_upload'] = 1;
                                $get_all_roles[$key]['capabilities']['upload_files'] = 1;
                            }
                        }
                       
                    }
                    $get_all_roles['subscriber']['name'] = 'Unassigned';
                    $get_all_roles['contentmanager']['name'] = 'Content Manager';
                    update_option($get_all_roles_array, $get_all_roles);
                }
                 
           
             
                    
                  $oldvalues = get_option( 'ContenteManager_Settings' );
                  if($object_data['customfieldstatus'] == 'checked'){
       
                        include 'defult-content.php';
                        $result = update_option('EGPL_Settings_Additionalfield', $user_additional_field);
       
       
                   }else{

                        include 'defult-content.php';
                        $result = update_option('EGPL_Settings_Additionalfield', $user_additional_field_default);

                    }
             
          

                
               
                $term = term_exists('Content Manager Editor', 'category');
                if ($term !== 0 && $term !== null) {
                    $cat_id_get = $term['term_id'];
                }else{
                    
                    $cat_id_get = wp_insert_category(
                    array(
                    'cat_name' 				=> 'Content Manager Editor',
		    'category_description'	=> '',
		    'category_nicename' 		=> 'content-manager-editor',
		    'taxonomy' 				=> 'category'
                    )
                );
                
                
                    
                }
                

                foreach ($create_pages_list as $key => $value) {


                    $page_path = $create_pages_list[$key]['name'];
                    $page = get_page_by_path($page_path);
                    if($create_pages_list[$key]['catname'] == true){
                            $cat_name = array($cat_id_get);//'content-manager-editor';
                        }else{
                            
                             $cat_name = '' ; //'content-manager-editor';
                        }
                    if (!$page) {
                        
                        
                        $my_post = array(
                            'post_title' => wp_strip_all_tags($create_pages_list[$key]['title']),
                            'post_status' => 'publish',
                            'post_author' => get_current_user_id(),
                            'post_content'=> wp_strip_all_tags($all_pages_defult_content[$create_pages_list[$key]['name']]),
                            'post_category' => $cat_name ,//'content-manager-editor',
                            'post_type' => 'page',
                            'post_name' => $page_path
                        );

// Insert the post into the database
                        $returnpage_ID = wp_insert_post($my_post);
                        update_post_meta($returnpage_ID, '_wp_page_template', $create_pages_list[$key]['temp']);
                        
                    }else{
                    
                  
                        
                        $pageID = $page->ID;
                        update_post_meta($pageID, '_wp_page_template', $create_pages_list[$key]['temp']);
                        if($page_path == "task-page"){
                            
                            wp_set_post_categories( $pageID, array() );
                        }else{
                            
                            wp_set_post_categories( $pageID, array( $cat_id_get ) );
                        }
                   
                }
                }

               
                global $wpdb;

                $charset_collate = $wpdb->get_charset_collate();

                $sql = "CREATE TABLE contentmanager_" . $blog_id['blog_id'] . "_log (
                        id bigint(20) NOT NULL AUTO_INCREMENT,
                        action_name varchar(60) NOT NULL,
                        action_type varchar(60) NOT NULL,
                        pre_action_data longtext NOT NULL,
                        user_email varchar(60) NOT NULL,
                        user_id varchar(60) NOT NULL,
                        result longtext NOT NULL,
                        action_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (id)
                        ) ENGINE=MyISAM;";

                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta($sql);
                restore_current_blog();
            }
        }
        
        
    

    
  
    
  

}
register_activation_hook( __FILE__, 'my_plugin_activate' );

add_action( 'init', 'add_contentmanager_settings' );

function add_contentmanager_settings() {
    
    wp_register_script('adminjs', plugins_url('js/admin-cmanager.js?v=2.41', __FILE__), array('jquery'));
    wp_enqueue_script('adminjs');
    //$settings_array['ContentManager']['sponsor-name']='Exhibitor';
    //update_option( 'ContenteManager_Settings', $settings_array);
    
}
function register_contentmanger_menu() {
    //add_menu_page('Exclude Sponsor Meta Fields', 'Content Manager Settings', 'manage_options', 'cmanager-settings', 'excludes_sponsor_meta');
    add_menu_page(__('exclude-sponsor-meta-fields'), __('Content Manager Settings'), 'edit_themes', 'excludes_sponsor_meta', 'excludes_sponsor_meta', '', 7); 
 
}
function register_contentmanager_sub_menu() {
   // add_submenu_page('cmanager-settings', 'Exclude Sponsor Meta Fields', 'Exclude Sponsor Meta Fields', 'manage_options', 'excludes-sponsor-meta', 'excludes_sponsor_meta');
    add_submenu_page('my_new_menu', __('My SubMenu Page'), __('My SubMenu'), 'edit_themes', 'my_new_submenu', 'my_submenu_render');
    add_submenu_page('my_new_menu', __('Manage Menu Page'), __('Manage New Menu'), 'edit_themes', 'my_new_menu', 'my_menu_render');
    //add_submenu_page_3 ... and so on
}
add_action('admin_menu', 'register_contentmanger_menu');
add_action('wp_ajax_give_update_content_settings', 'updatecmanagersettings');
//add_action('admin_menu', 'register_contentmanager_sub_menu');



function updatecmanagersettings($object_data){
    
   try{
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);     
    $lastInsertId = contentmanagerlogging('Update Contentmanager Settings',"Admin Action",serialize($object_data),$user_ID,$user_info->user_email,"pre_action_data");
    
    
   
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $sponsor_name=$oldvalues['ContentManager']['sponsor_name'];
    $values_create=$object_data['excludemetakeyscreate'];
    $sponsor_name=$object_data['sponsorname'];
    $attendytypeKey=$object_data['attendyTypeKey'];
    $eventdate = $object_data['eventdate'];
    $formemail = $object_data['formemail'];
    $mandrill = $object_data['mandrill'];
    $mapapikey = $object_data['mapapikey'];
    $mapsecretkey = $object_data['mapsecretkey'];
    $wooseceretkey = $object_data['wooseceretkey'];
    $wooconsumerkey = $object_data['wooconsumerkey'];
    $selfsignstatus = $object_data['selfsignstatus'];
    $userreportcontent =   $object_data['userreportcontent']; 
    $expogeniefloorplan = $object_data['expogeniefloorplan']; 
    $sitebuttonslables = $object_data['sitebuttonslables']; 
    
    //$oldregistrationstatus = $object_data['oldregistrationstatus']; 
    //$aptycode = $object_data['aptycode']; 
    
    
    $addresspoints = $object_data['addresspoints'];
    
    $values_edit=$object_data['excludemetakeysedit'];
    $remove_spaces_create = preg_replace('/\s+/', '', $values_create);
    $remove_spaces_edit = preg_replace('/\s+/', '', $values_edit);
    $meta_create = explode(",", $remove_spaces_create);
    $meta_edit = explode(",", $remove_spaces_edit);
   
    foreach ($meta_create as $metas=>$keys){
        
       $oldvalues['ContentManager']['exclude_sponsor_meta_create'][$metas]= $keys;
      
    }
     foreach ($meta_edit as $metas=>$keys){
        
       $oldvalues['ContentManager']['exclude_sponsor_meta_edit'][$metas]= $keys;
      
    }


    
    $oldvalues['ContentManager']['sponsor_name']=$sponsor_name;
    $oldvalues['ContentManager']['attendytype_key']=$attendytypeKey;
    $oldvalues['ContentManager']['eventdate']=$eventdate;
    $oldvalues['ContentManager']['formemail']=$formemail;
    $oldvalues['ContentManager']['mandrill']=$mandrill;
    $oldvalues['ContentManager']['addresspoints']=$addresspoints;
    $oldvalues['ContentManager']['adminsitelogo']=$object_data['adminsitelogourl'];
    $oldvalues['ContentManager']['mapapikey']=$mapapikey;
    $oldvalues['ContentManager']['mapsecretkey']=$mapsecretkey;
    $oldvalues['ContentManager']['userreportcontent']=stripslashes($userreportcontent);
   
    
    $oldvalues['ContentManager']['defaultboothprice']=$object_data['defaultboothprice'];
    $oldvalues['ContentManager']['cventaccountname']=$object_data['cventaccountname'];
    $oldvalues['ContentManager']['cventusername']=$object_data['cventusername'];;
    $oldvalues['ContentManager']['cventapipassword']=$object_data['cventapipassword'];;
    $oldvalues['ContentManager']['customfieldstatus']=$object_data['customfieldstatus'];
    
    $oldvalues['ContentManager']['boothpurchasestatus']=$object_data['boothpurchasestatus'];
    $oldvalues['ContentManager']['redirectcatname']=$object_data['redirectcatname'];
    $oldvalues['ContentManager']['sitebuttonslables']=stripslashes($sitebuttonslables);
    
    
    $oldvalues['ContentManager']['oldregistrationstatus']=$object_data['oldregistrationstatus'];
    $oldvalues['ContentManager']['aptycode']=$object_data['aptycode'];//stripslashes($sitebuttonslables);
   
    
    if (!array_key_exists('taskmanager', $oldvalues['ContentManager'])) {
    
        
        
        $updatetasktypes[0]['lable'] = "None";
        $updatetasktypes[0]['type'] = "none";
        
        $updatetasktypes[1]['lable'] = "Text";
        $updatetasktypes[1]['type'] = "text";
        $updatetasktypes[2]['lable'] = "Display Link";
        $updatetasktypes[2]['type'] = "link";
        $updatetasktypes[3]['lable'] = "Date";
        $updatetasktypes[3]['type'] = "date";
        $updatetasktypes[4]['lable'] = "URL";
        $updatetasktypes[4]['type'] = "url";
        $updatetasktypes[5]['lable'] = "Email";
        $updatetasktypes[5]['type'] = "email";
        $updatetasktypes[6]['lable'] = "Drop Down";
        $updatetasktypes[6]['type'] = "select-2";
        $updatetasktypes[7]['lable'] = "Number";
        $updatetasktypes[7]['type'] = "number";
        $updatetasktypes[8]['lable'] = "File Upload";
        $updatetasktypes[8]['type'] = "color";
        $updatetasktypes[9]['lable'] = "Text Area";
        $updatetasktypes[9]['type'] = "textarea";
        $updatetasktypes[10]['lable'] = "Display Coming soon";
        $updatetasktypes[10]['type'] = "comingsoon";
        
        
        
       
        $oldvalues['ContentManager']['taskmanager']['input_type']=$updatetasktypes;
        
    }
    
    
    
   if($object_data['customfieldstatus'] == 'checked'){
       
        include 'defult-content.php';
        $result = update_option('EGPL_Settings_Additionalfield', $user_additional_field);
       
       
   }else{
      
       include 'defult-content.php';
       $result = update_option('EGPL_Settings_Additionalfield', $user_additional_field_default);
       
   }
    
    
    $oldvalues['ContentManager']['wooseceretkey']=$wooseceretkey;
    $oldvalues['ContentManager']['wooconsumerkey']=$wooconsumerkey;
    $oldvalues['ContentManager']['selfsignstatus']=$selfsignstatus;
    $oldvalues['ContentManager']['expogeniefloorplan']=$expogeniefloorplan;
    
    $result=update_option('ContenteManager_Settings', $oldvalues);
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
   }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}

function updateadmin_frontend_settings($object_data,$filedataurl){
    
   try{
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID); 
    $object_data['headerbannerimage'] = $filedataurl;
   
    $registration_notificationemails = $object_data['registration_notificationemails'];
    
    $lastInsertId = contentmanagerlogging('Update Contentmanager Settings Front End',"Admin Action",serialize($object_data),$user_ID,$user_info->user_email,"pre_action_data");
      
    
    $eventdate = $object_data['eventdate'];
    $applicationmoderationstatus = $object_data['applicationmoderationstatus'];
    $oldvalues = get_option( 'ContenteManager_Settings' );
    
    $oldvalues['ContentManager']['eventdate']=$eventdate;
    $oldvalues['ContentManager']['mainheader']=$filedataurl;
    $oldvalues['ContentManager']['mainheaderlogo']='';
    $oldvalues['ContentManager']['applicationmoderationstatus']=$applicationmoderationstatus;
    $oldvalues['ContentManager']['registration_notificationemails']=$registration_notificationemails;
    
    
    $result=update_option('ContenteManager_Settings', $oldvalues);
    
    contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
   }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
 }
    die();
}
function excludes_sponsor_meta(){
    
    
     $oldvalues = get_option( 'ContenteManager_Settings' );
 
     $sponsor_name      =   $oldvalues['ContentManager']['sponsor_name'];
     $attendytype       =   $oldvalues['ContentManager']['attendytype_key'];
     $eventdate         =   $oldvalues['ContentManager']['eventdate'];
     $formemail         =   $oldvalues['ContentManager']['formemail'];
     $mandrill          =   $oldvalues['ContentManager']['mandrill'];
     $mapapikey         =   $oldvalues['ContentManager']['mapapikey'];
     $mapsecretkey      =   $oldvalues['ContentManager']['mapsecretkey'];
     $adminsitelogo     =   $oldvalues['ContentManager']['adminsitelogo'];
     $wooconsumerkey    =   $oldvalues['ContentManager']['wooconsumerkey'];
     $wooseceretkey     =   $oldvalues['ContentManager']['wooseceretkey'];
     $selfsignstatus    =   $oldvalues['ContentManager']['selfsignstatus'];
     
     $cventaccountname     =   $oldvalues['ContentManager']['cventaccountname'];
     $cventusername        =   $oldvalues['ContentManager']['cventusername'];
     $cventapipassword     =   $oldvalues['ContentManager']['cventapipassword'];
     $customfieldstatus    =   $oldvalues['ContentManager']['customfieldstatus'];
     
     $defaultboothprice    =   $oldvalues['ContentManager']['defaultboothprice'];
     $boothpurchasestatus   =   $oldvalues['ContentManager']['boothpurchasestatus'];
     $redirectcatname   =   $oldvalues['ContentManager']['redirectcatname'];
     
     $userreportcontent =   stripslashes($oldvalues['ContentManager']['userreportcontent']);
     $expogeniefloorplan    =   $oldvalues['ContentManager']['expogeniefloorplan'];
     
     $oldregistrationstatus = $oldvalues['ContentManager']['oldregistrationstatus'];
     
     $frontendbuttonslables = $oldvalues['ContentManager']['sitebuttonslables'];
     $aptycode = $oldvalues['ContentManager']['aptycode'];
     
     if(empty($frontendbuttonslables)){
         
         
        $frontendbuttonslables =  '{"registertabcontent" :  "Not yet registered","registertabbuttonlabel" :  "REGISTER" , "logintabcontent" : "Already registered","logintabbutton" : "LOGIN"}';
         
         
     }
     



     
     
     //echo'<pre>';
    // print_r($oldvalues);
     if(!empty($oldvalues['ContentManager']['exclude_sponsor_meta_create'])){
         foreach($oldvalues['ContentManager']['exclude_sponsor_meta_create'] as $keys => $key){
             $string_value.= $key.',';
         }
     }
     if(!empty($oldvalues['ContentManager']['exclude_sponsor_meta_edit'])){
         foreach($oldvalues['ContentManager']['exclude_sponsor_meta_edit'] as $keys => $key){
             $string_value_edit.= $key.',';
         }
     }
     $bodayContent;
     $header = '<p id="successmsg" style="display:none;background-color: #00F732;padding: 11px;margin-top: 20px;width: 300px;font-size: 18px;"></p><h4></h4>';
     $bodayContent.=$header;
     
     $maincontent.='<table style="">
      
       <tr>
       <td><h4>Exclude Meta Fields For Create Sponsor Screen</h4></td>
        <td><textarea name="listofmeta"  id="listofmeta" rows="5" cols="40">'.rtrim($string_value, ",").'</textarea><p>Add meta keys with spreated comma</p></td>
       </tr>
       <tr>
            <td><h4>Exclude Meta Fields For Edit Sponsor Screen</h4></td>
            
       
        <td><textarea name="listofmetaedit"  id="listofmetaedit" rows="5" cols="40">'.rtrim($string_value_edit, ",").'</textarea><p>Add meta keys with spreated comma</p></td>
       </tr>
       <tr><td><h4>Add Sponsor Name</h4></td>
       
        <td><input type="text" name="spnsorname"  id="spnsorname" value='.$sponsor_name.'></td>
       </tr>
  <tr><td><h4>Add Key For Attendee Type (Graph)</h4></td>
 
        <td><input type="text" name="attendytype"  id="attendytype" value='.$attendytype.'></td>
       </tr>
       
<tr><td><h4>Event Date</h4></td>
 
        <td><input type="date" name="eventdate"  id="eventdate" value='.$eventdate.'></td>
       </tr>
       <tr><td><h4>Form Email Address</h4></td>
 
        <td><input type="text" name="formemail"  id="formemail" value='.$formemail.'></td>
       </tr>
        <tr><td><h4>Mandrill API key</h4></td>
 
        <td><input type="text" name="mandrill"  id="mandrill" value='.$mandrill.'></td>
       </tr>
        <tr><td><h4>Admin Site Logo</h4></td>
 
        <td><input type="file"  onclick="clearfilepath()" name="adminsitelogo" id="adminsitelogo"></br><img src="'.$adminsitelogo.'" id="uploadlogourl" width="200" height="70"></td>
        <td></td>
       </tr>
        <tr><td><h4>Self-signup Settings</h4></td>
        <td><input type="text" name="selfsignstatus"  id="selfsignstatus" value='.$selfsignstatus.'></td>
        </tr>
         <tr><td><h4>Redirect Active Shop Catgory Name</h4></td>

        <td>
        <input type="text" title="hint:boothpurchase" name="redirectcatname"  id="redirectcatname" value='.$redirectcatname.'>
        </td>
       </tr>

        <tr><td><h4>ExpoGenie Floor Plan</h4></td>
        <td><input type="text" name="expogeniefloorplan"  id="expogeniefloorplan" value='.$expogeniefloorplan.'></td>
        </tr>
        <tr><td><h4>Map Dynamics API Key</h4></td>
 
        <td><input type="text" name="mapapikey"  id="mapapikey" value='.$mapapikey.'></td>
       </tr>
        <tr><td><h4>Map Dynamics Secret Key</h4></td>

        <td>
        <input type="text" name="mapsecretkey"  id="mapsecretkey" value='.$mapsecretkey.'>
       
</td>
       </tr>
       
       <tr><td><h4>Woocommerce Api Consumer Key</h4></td>
 
        <td><input type="text" name="wooconsumerkey"  id="wooconsumerkey" value='.$wooconsumerkey.'></td>
       </tr>
        <tr><td><h4>Woocommerce Api Secret Key</h4></td>

        <td>
        <input type="text" name="wooseceretkey"  id="wooseceretkey" value='.$wooseceretkey.'>
        </td>
       </tr>
       

       <tr><td><h4>Auto Booth Assignment</h4></td>
 
        <td><input type="text" name="boothpurchasestatus"  id="boothpurchasestatus" value='.$boothpurchasestatus.'></td>
       </tr>
       
       <tr><td><h4>Default Booth Price</h4></td>
 
        <td><input type="text" name="defaultboothprice"  id="defaultboothprice" value='.$defaultboothprice.'></td>
       </tr>
       
       <tr><td><h4>Cvent Account Name</h4></td>
 
        <td><input type="text" name="cventaccountname"  id="cventaccountname" value='.$cventaccountname.'></td>
       </tr>
       
        <tr><td><h4>Cvent Username</h4></td>

        <td>
        <input type="text" name="cventusername"  id="cventusername" value='.$cventusername.'>
        </td>
       </tr>
       <tr><td><h4>Cvent Api Password</h4></td>
 
        <td><input type="text" name="cventapipassword"  id="cventapipassword" value='.$cventapipassword.'></td>
       </tr>
        <tr><td><h4>Additional Custome Field Status</h4></td>

        <td>';
        if($customfieldstatus == 'checked'){
            
            $maincontent.='<input type="text"  id="customfieldstatus" name="vehicle" value="enabled">';
            
        }else{
            
            $maincontent.='<input type="text"  id="customfieldstatus" name="vehicle" value="disabled">'; 
            
        } 
        
        

        $maincontent.='</td>
       </tr>



       <tr><td><h4>User Report bottom content</h4></td>
 
        <td><textarea style="width:300px;height:100px" id="userreportcontent" >'.$userreportcontent.'</textarea></td>
       </tr>
       <tr><td><h4>Button Lables</h4></td>
 
        <td><textarea style="width:300px;height:100px" id="sitebuttonslables" >'.stripslashes($frontendbuttonslables).'</textarea></td>
       </tr>
       
       <tr><td><h4>Apty Code</h4></td>
 
        <td><textarea style="width:300px;height:100px" id="aptycode" >'.stripslashes($aptycode).'</textarea></td>
       </tr>
       
       <tr><td><h4>old Registration</h4></td>
 
        <td><input type="text"  title="checked/unchecked" id="oldregistrationstatus" name="vehicle" value="'.$oldregistrationstatus.'"></td>
       </tr>


       <tr>
       <td style="text-align: center;"><a style="margin-top: 20px;
" onclick="updatecontentsettings()" class="button">Save</a></td>
     </tr>
     </table>';
     
     $bodayContent.=$maincontent;
     
     
     echo $bodayContent;
}

class PageTemplater {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	/**
	 * Returns an instance of this class. 
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new PageTemplater();
		} 

		return self::$instance;

	} 

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		$this->templates = array();


		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);

		} else {

			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);

		}

		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data', 
			array( $this, 'register_project_templates' ) 
		);


		// Add a filter to the template include to determine if the page has our 
		// template assigned and return it's path
		add_filter(
			'template_include', 
			array( $this, 'view_project_template') 
		);


		// Add your templates to this array.
		$this->templates = array(
                        'temp/addsponsor-template.php'     => 'Add new sponsor',
                        'temp/create-resource-template.php'     => 'Create resource',
                        'temp/sponsor-reports-template.php'     => 'Sponsor Reports',
                        'temp/edit_sponsor-template.php'     => 'Edit Sponsor', 
                        'temp/edit_sponsor_task_template.php'     => 'Edit Sponsor Task',
                        'temp/view_resource-template.php'     => 'Resource list view',
                        'temp/createponsor-task-template.php'     => 'Create Sponsor Task',
                        'temp/editponsor-task-update-template.php' =>  'Edit Sponsor Task Update',
                        'temp/change_password_template.php' =>  'Change Password',
                        'temp/welcome_email_template.php' =>  'Welcome Email',
                        'temp/create-role-template.php' =>  'Create New Role',
                        'temp/addcontentmanager-template.php' =>  'Add Content Manager',
			'temp/edit_content_page.php'     => 'Edit Content',
                        'temp/admin_dashboard.php'     => 'Dashboard',
                        'temp/bulk_download_task_files_template.php'     => 'Download Bulk Email',
                        'temp/user_change_password_template.php'     => 'User Change Password',
                        'temp/settings-template.php'     => 'Admin Settings',
                        'temp/bulkuser_import.php'     => 'Bulk Import Users',
                        'temp/sponsor-task-update-template.php'=>'Sponsor Task Update',
                        'temp/sync_to_floorplan.php'=>'Sync to Floorplan',
                        'temp/bulk_edit_task.php'=>'Bulk Edit Task',
                        'temp/bulk_edit_task_list.php'=>'Bulk Edit Task List view',
                        'temp/managerole_assignment.php'=>'Role Assignment',
                        'temp/product-order-reporting-table-template.php'=>'Order Report',
                        'temp/new_user_report_template.php'=>'User Report',
                        'temp/view_products_manage_all_template.php'=>'Manage Product',
                        'temp/add_new_product_template.php'=>'Add New Product',
                        'temp/users_result_report_template.php'=>'User Report Result',
                        'temp/selfsignup_addsponsor_template.php'=>'User Self Signup',
                        'temp/selfsign_review_profiles.php'=>'User Self Signup Report',
                        'temp/landing-page.php'=>'Landing Page',
                        'temp/admin_landing_page_multisite_template.php'=>'Multi site Landing Page',
                        'temp/egpl_default_page_template.php'=>'EGPL Default Template',
                        'temp/egpl-home-template.php'=>'EGPL Home 100%',
                        'temp/egpl_login.php'=>'Users Login',
                        'temp/egpl_resources_template.php'=>'Resources',
                        'temp/updateusersprefix.php'=>'Update User Meta',
                        'temp/syncuserscvent.php'=>'Cvent Sync Users',
                        'temp/product-order-reporting-booth-template.php'=>'Manage Exhibitor Booths',
                        'temp/bulk_edit_product.php'=>'Manage Bulk Products',
                        'temp/scriptrunner.php'=>'Moved Tasks Option to post',
                        'temp/scriptrunnerfixedpatch.php'=>'Task Fixed Patch',
                        'temp/bulk_manage_custom_fields.php'=>'User Fields',
                        'temp/custome_task_reports.php'=>'Task Report',
                        'temp/custome_tasks_report_filters.php'=>'Task Report Filters',
                        'temp/expo-genie-log-template.php'=>'Expo Genie Logs',
                        'temp/virtual-loby-template.php'=>'Virtual Lobby',
                        'temp/virtual-auditorium-template.php'=>'Virtual Auditorium',
                        'temp/virtual-booth-view-template.php'=>'Virtual Booth View',
                        'temp/virtual-exhibitorhall-template.php'=>'Virtual Exhibit Hall',
                        'temp/virtual-resourcenter-template.php'=>'Virtual Resource Center',
                        'temp/virtual-breakouts-template.php'=>'Virtual Breakouts',
                        'temp/virtual-breakouts-template.php'=>'Virtual Breakouts',
                        'temp/virtual-networklounge-template.php'=>'Virtual Nerworking',
                        'temp/exhibitor-entry-settings-template.php'=>'Exhibitor Entry Setting',
                        'temp/add_new_product__package_template.php'=>'Add New Packages',
                        'temp/floorplan_warning_template.php'=>'Floor Plan Warning',
                        'temp/admin_view_orders.php'=>'Admin View User Orders',
                        'temp/create-new-page-template.php'=>'Create New Page',
                        'temp/manage-menu-template.php'=>'Manage Menu Template',
                       
                        
                     
                   
                    
                );
			
	} 

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list. 
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		} 

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	} 

	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {
		
		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta( 
			$post->ID, '_wp_page_template', true 
		)] ) ) {
			return $template;
		} 

		$file = plugin_dir_path( __FILE__ ). get_post_meta( 
			$post->ID, '_wp_page_template', true
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;

	}
} 
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );


// [showuserfield field='COMPANY_NAME']
function showuserfield_func($atts) {
    $fieldname = $atts['field'];
    $postid = get_current_user_id();
    $value = get_user_option($fieldname,$postid);
   
    return $value;
   
}

add_shortcode('showuserfield', 'showuserfield_func');

// [sponsor_roles]
function sponsor_roles_fun() {
    $role = '';
    if (is_user_logged_in()) { 
    
        global $wp_roles;
        global $current_user, $wpdb;
        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);
        $role = $wpdb->prefix . 'capabilities';
        $current_user->role = array_keys($current_user->$role);
        $role = $editable_roles[$current_user->role[0]]['name'];
       }
    
    
    return $role;
}

add_shortcode('sponsor_roles', 'sponsor_roles_fun');


function mycustomelogin($user_login, $user) {
    
    global $wpdb;
    $postid = $user->ID;
    $blog_id = get_current_blog_id();
    
    if (is_multisite()) {
    
    
    $user_blogs = get_blogs_of_user( $postid );
    
    if (array_key_exists($blog_id,$user_blogs)){
        
        // echo '<pre>';
        // print_r($user_blogs);exit;
         
    }else{
        
        
        //wp_logout();
        wp_redirect( '/warning' );
        exit();
        
    }
    }
    $t=time();
    $result = update_user_meta($postid , 'wp_user_login_date_time',  $t);
    
    if(get_current_blog_id() == 1){
        $tablename = 'contentmanager_log';
    }else{
    
        $tablename = 'contentmanager_'.$blog_id.'_log';
    } 
    
   // $query = "INSERT INTO ".$tablename." (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
  //  $wpdb->query($wpdb->prepare($query, "Login", "User Action",serialize($user),$user->ID,$user->user_email,$result));
    $activitylog = array(
        'post_title'    => wp_strip_all_tags( 'Login' ),
        'post_content'  => "",
        'post_status'   => 'publish',
        'post_author'   => $user->ID,
        'post_type'=>'expo_genie_log'
    );
    $logID = wp_insert_post( $activitylog );
    $_SERVER['currentuseremail'] = $email;
    update_post_meta( $logID, 'actiontype', 'User Action' );
    update_post_meta( $logID, 'preactiondata', $user );
    update_post_meta( $logID, 'currentuserinfo', $_SERVER );
    update_post_meta( $logID, 'email', $user->user_email );
    update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
    update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
    update_post_meta( $logID, 'result', $result );

}
add_action('wp_login', 'mycustomelogin', 10, 2);



//add_action( 'loop_start', 'personal_message_when_logged_in' );

function personal_message_when_logged_in() {

if ( is_user_logged_in() ) :
 
    global $wpdb;
    $current_user = wp_get_current_user();
    $postid = get_current_user_id();
    $t=time();
    $result = update_user_meta($postid , 'wp_user_login_date_time',  $t);
    $blog_id =get_current_blog_id();
    if(get_current_blog_id() == 1){
        $tablename = 'contentmanager_log';
    }else{
    
        $tablename = 'contentmanager_'.$blog_id.'_log';
    }
    
   // $query = "INSERT INTO ".$tablename." (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
//$wpdb->query($wpdb->prepare($query, "Login", "User Action",serialize($current_user),$postid,$current_user->user_email,$result));
  $activitylog = array(
        'post_title'    => wp_strip_all_tags( 'Login' ),
        'post_content'  => "",
        'post_status'   => 'publish',
        'post_author'   => $postid,
        'post_type'=>'expo_genie_log'
    );
    $logID = wp_insert_post( $activitylog );
    $_SERVER['currentuseremail'] = $email;
    update_post_meta( $logID, 'actiontype', 'User Action' );
    update_post_meta( $logID, 'preactiondata', $current_user );
    update_post_meta( $logID, 'currentuserinfo', $_SERVER );
    update_post_meta( $logID, 'email', $current_user->user_email );
    update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
    update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
    update_post_meta( $logID, 'result', $result );

    endif;
}

add_action( 'authenticate', 'my_front_end_login_fail',10,2);  // hook failed login

function my_front_end_login_fail($error,$user) {
     // where did the post submission come from?
 
   $message['error'] = $error;
   $message['username'] = $user;
   //$message['pass'] = $pass;
   $blog_id =get_current_blog_id();
   if(get_current_blog_id() == 1){
        $tablename = 'contentmanager_log';
    }else{
    
        $tablename = 'contentmanager_'.$blog_id.'_log';
    }
$_SERVER['currentuser'] = $user;
 
    global $wpdb;
   // $query = "INSERT INTO ".$tablename." (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
   // $wpdb->query($wpdb->prepare($query, "Login Failed", "User Action",serialize($message),'',$_SERVER['currentuser'],''));
     $activitylog = array(
        'post_title'    => wp_strip_all_tags( 'Login Failed' ),
        'post_content'  => "",
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'=>'expo_genie_log'
    );
    $logID = wp_insert_post( $activitylog );
    $_SERVER['currentuseremail'] = $email;
    update_post_meta( $logID, 'actiontype', 'User Action' );
    update_post_meta( $logID, 'preactiondata', $message );
    update_post_meta( $logID, 'currentuserinfo', $_SERVER );
    update_post_meta( $logID, 'email', $user );
    update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
    update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
    update_post_meta( $logID, 'result', '' );

}


function afterlogoutredirect() {
    // your code
  
     wp_redirect( home_url('/') );
     exit();
}
add_action('wp_logout', 'afterlogoutredirect');

// [customelogout ]
function customelogout() {
       

    global $wpdb;
    global $switched;
    
    $current_user = wp_get_current_user();
    $postid = get_current_user_id();
    $blog_id =get_current_blog_id();
    
   if(get_current_blog_id() == 1){
        $tablename = 'contentmanager_log';
    }else{
    
        $tablename = 'contentmanager_'.$blog_id.'_log';
    }
    $_SERVER['currentuser'] = $current_user->user_email;
    $result="1";
    
    $activitylog = array(
        'post_title'    => wp_strip_all_tags( 'Logout' ),
        'post_content'  => "",
        'post_status'   => 'publish',
        'post_author'   => $postid,
        'post_type'=>'expo_genie_log'
    );
    $logID = wp_insert_post( $activitylog );
    $_SERVER['currentuseremail'] = $email;
    update_post_meta( $logID, 'actiontype', 'User Action' );
    update_post_meta( $logID, 'preactiondata', $current_user );
    update_post_meta( $logID, 'currentuserinfo', $_SERVER );
    update_post_meta( $logID, 'email', $current_user->user_email );
    update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
    update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
    update_post_meta( $logID, 'result', $result );
    //$query = "INSERT INTO ".$tablename." (action_name, action_type,pre_action_data,user_id,user_email,result) VALUES (%s,%s,%s,%s,%s,%s)";
    //$wpdb->query($wpdb->prepare($query, "Logout", "User Action",serialize($current_user),$postid,$_SERVER,$result));
    
    //switch_to_blog(1);
    wp_logout();
    //restore_current_blog();
    //switch_to_blog($blog_id);
   // wp_logout();
   // restore_current_blog();
    exit;
   
}
add_shortcode( 'customelogout', 'customelogout' );

function contentmanagerlogging($acction_name,$action_type,$pre_action_data,$user_id,$email,$result){

    


// Create post object
$activitylog = array(
  'post_title'    => wp_strip_all_tags( $acction_name ),
  'post_content'  => "",
  'post_status'   => 'publish',
  'post_author'   => $user_id,
  'post_type'=>'expo_genie_log'
);
 

 $logID = wp_insert_post( $activitylog );
 $_SERVER['currentuseremail'] = $email;
 update_post_meta( $logID, 'actiontype', $action_type );
 update_post_meta( $logID, 'preactiondata', $pre_action_data );
 update_post_meta( $logID, 'currentuserinfo', $_SERVER );
 update_post_meta( $logID, 'email', $email );
 update_post_meta( $logID, 'ip', $_SERVER['REMOTE_ADDR'] );
 update_post_meta( $logID, 'browseragent', $_SERVER['HTTP_USER_AGENT'] );
 update_post_meta( $logID, 'result', $result );
 
 return $logID;
 
}
function contentmanagerlogging_file_upload($lastInsertId,$result){

    

    update_post_meta( $lastInsertId, 'result', $result );



}

function custome_email_send($user_id, $userlogin='', $welcomeemailtemplatename=''){
    
    //require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
    
    
 try {

    global $wpdb, $wp_hasher;
    $site_prefix = $wpdb->get_blog_prefix();
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mandrill = $oldvalues['ContentManager']['mandrill'];
    $mandrill = new Mandrill($mandrill);
    
   
    
        $user = get_userdata($user_id);
        $floor_Plan_Settings = 'floorPlanSettings';
        $get= get_option($floor_Plan_Settings);
        if(empty($userlogin)){
            
          $user_login = stripslashes($user->user_login);
          $user_email = stripslashes($user->user_email);
          
        }else{
            
            $user_email = $userlogin;
            $user_login = $userlogin;
        }

        if(empty($welcomeemailtemplatename)){

           $welcomeemailtemplatename = "welcome_email_template";     
        }

        //$plaintext_pass=wp_generate_password( 8, false, false );
        //wp_set_password( $plaintext_pass, $user_id );

        $settitng_key='AR_Contentmanager_Email_Template_welcome';

        $sponsor_info = get_option($settitng_key);
        //  print_r($sponsor_info);
        $site_url = get_option('siteurl' );
        $data=  date("Y-m-d");
        $time=  date('H:i:s');
        $site_title=get_option( 'blogname' );
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $formemail = $oldvalues['ContentManager']['formemail'];
        if(empty($formemail)){
            $formemail = 'noreply@expo-genie.com';
        
        }
        
       
      
        $formemail = $oldvalues['ContentManager']['formemail'];
        $fromname = stripslashes ($sponsor_info[$welcomeemailtemplatename]['fromname']);
        if(empty($formemail)){

            $formemail = 'noreply@expo-genie.com';

        }
        
        $subject = $sponsor_info[$welcomeemailtemplatename]['welcomesubject'];
	$bcc =  $sponsor_info[$welcomeemailtemplatename]['BCC'];
       // $cc =  $sponsor_info[$welcomeemailtemplatename]['CC'];
	$formname = $sponsor_info[$welcomeemailtemplatename]['fromname'];
        $replaytoemailadd = $sponsor_info[$welcomeemailtemplatename]['replaytoemailadd'];
        $bcc_array = $bcc;
      //  $cc_array = explode(',',$cc);
        
       

    $subject = $sponsor_info[$welcomeemailtemplatename]['welcomesubject'];
    $body=stripslashes ($sponsor_info[$welcomeemailtemplatename]['welcomeboday']);
   
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    
    $field_key_string =  getInbetweenStrings('{', '}', $body);
    $field_key_subject = getInbetweenStrings('{', '}', $subject);
    
  
    $site_url = get_option('siteurl' );

    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $sitetitle = get_bloginfo( 'name' );
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
   // $body = str_replace('[site_url]', $site_url, $body);
   // $body = str_replace('[login_url]', $site_url, $body);
   // $body = str_replace('[admin_email]', $admin_email, $body);
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    
    

    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url),
        array('name'=>'site_title','content'=>$sitetitle)
        );

  

       
       $data_field_array= array();
       $t=time();
       update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
       
       foreach($field_key_subject as $index_subject=>$keyvalue_subject){

                      if($keyvalue_subject == 'wp_user_id' ||$keyvalue_subject == 'Role' || $keyvalue_subject == 'site_title' || $keyvalue_subject == 'date' || $keyvalue_subject == 'time' || $keyvalue_subject == 'site_url' || $keyvalue_subject == 'user_pass'|| $keyvalue_subject == 'user_login'){


                      if($keyvalue_subject == 'user_pass'){


                            
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$plaintext_pass);

                      }else if($keyvalue_subject == 'user_login'){

                          $data_field_array[] = array('name'=>$index_subject,'content'=>$user->user_login);
                      }else if($keyvalue_subject == 'Role'){

                         
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {

                              if(implode(', ', $getcurrentuserdata->roles) == $key){

                                  $currentuserRole = $name['name'];


                              }



                          }


                          $data_field_array[] = array('name'=>$index_subject,'content'=>$currentuserRole);
                      }elseif($keyvalue_subject == 'wp_user_id'){
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$user_id);
                          
                      }



                   }else{


                       $get_meta_value = get_user_meta_merger_field_value($user_id,$keyvalue_subject);
                       
                       
                       
                       
                    if($get_meta_value!=""){
                        
                       
                        $getfieldType = getcustomefieldKeyValue($keyvalue_subject,"fieldType");
                        
                       if($getfieldType == "date"){
                            
                            $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0])/1000);
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$date_value);
                           
                       }else{
                           
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$get_meta_value);
                           
                       }
                             
                      
                             
                        
                       
                   }else{
                       
                       $data_field_array[] = array('name'=>$index_subject,'content'=>'');
                   }




                  }



             }
       foreach($field_key_string as $index=>$keyvalue){

                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){


                      if($keyvalue == 'user_pass'){


                            
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index,'content'=>$plaintext_pass);

                      }else if($keyvalue == 'user_login'){

                          $data_field_array[] = array('name'=>$index,'content'=>$user->user_login);
                      }else if($keyvalue == 'Role'){

                         
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {

                              if(implode(', ', $getcurrentuserdata->roles) == $key){

                                  $currentuserRole = $name['name'];


                              }



                          }


                          $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole);
                      }elseif($keyvalue == 'wp_user_id'){
                          
                          $data_field_array[] = array('name'=>$index,'content'=>$user_id);
                          
                      }



                   }else{


                       $get_meta_value = get_user_meta_merger_field_value($user_id,$keyvalue);
                       if($get_meta_value!=""){

                            $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");

                           if($getfieldType == "date"){

                                $date_value =   date('d-m-Y' , intval($get_meta_value)/1000);
                                $data_field_array[] = array('name'=>$index,'content'=>$date_value);

                           }else{

                                $data_field_array[] = array('name'=>$index,'content'=>$get_meta_value);

                           }





                       }else{

                           $data_field_array[] = array('name'=>$index,'content'=>'');
                       }




                  }



             }
       $to_message_array[]=array('email'=>$user_email,'name'=>$first_name,'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$user_email,
                'vars'=>$data_field_array
           );

       


       //$result = send_email($to,$subject,$body_message);

//        if(sizeof($bcc_array) > 1){
//
//            foreach ($bcc_array as $key => $value) {
//                $to_message_array[] = array('email' => $value, 'name' => '', 'type' => 'bcc');
//                $user_data_array[] =array(
//                'rcpt'=>$value,
//                'vars'=>$data_field_array
//                );
//            }
//        }else{
//
//            if(!empty($bcc_array)){
//
//                $to_message_array[]=array('email'=>$bcc_array[0],'name'=>'','type'=>'bcc');
//                $user_data_array[] =array(
//                'rcpt'=>$bcc_array[0],
//                'vars'=>$data_field_array
//                );
//            }
//        }
//        if(sizeof($cc_array) > 1){
//
//            foreach ($cc_array as $key => $value) {
//                $to_message_array[] = array('email' => $value, 'name' => '', 'type' => 'cc');
//                $user_data_array[] =array(
//                'rcpt'=>$value,
//                'vars'=>$data_field_array
//                );
//            }
//        }else{
//
//            if(!empty($cc_array)){
//
//                $to_message_array[]=array('email'=>$cc_array[0],'name'=>'','type'=>'cc');
//                $user_data_array[] =array(
//                'rcpt'=>$cc_array[0],
//                'vars'=>$data_field_array
//                );
//            }
//        }



        
        $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
        $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
        
       
        
        $logourl = '';

        if(!empty($mainheaderlogo)){

            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderlogo.'" alt="" width="250" />';

        }else if(!empty($mainheaderbackground)){

            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderbackground.'" alt="" height="100" />';


        }

        $html_body_message = '<table width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
<tbody>
<tr>
<td align="left">
<div style="border: solid 1px #d9d9d9;">
<table id="header" style="line-height: 1.6;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="text-align: center;">'.$logourl.'</td>
</tr>
</tbody>
</table>
<table id="content" style="padding-right: 30px;padding-left: 30px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="border-top: solid 1px #d9d9d9;" colspan="2">
<div style="padding: 1em 0;">
'.$body.'
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>';


       
        
   $get_currentsiteURl = get_site_url();
   $message = array(

        'html' => $html_body_message,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replaytoemailadd),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
         
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
        "tags" => [$get_currentsiteURl]

    );

    // exit;

    $lastInsertId = contentmanagerlogging('Welcome Email',"Admin Action",serialize($message),$user_id,$user_info->user_email,"pre_action_data");

    $async = false;
    $ip_pool = 'Main Pool';
   // $send_at = 'example send_at';
    $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    // echo json_encode('successfully send');
   


}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'


    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}
    
}


function set_html_content_type_utf8() {
return 'test/html';
}

function getInbetweenStrings($start, $end, $str){
    
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
    $GetAllcustomefields = new EGPLCustomeFunctions();
    $listOFcustomfieldsArray = $GetAllcustomefields->getAllcustomefields();
    global $wpdb;
    $matches = array();
    $regex = "/$start([a-zA-Z0-9_]*)$end/";
    preg_match_all($regex, $str, $matches);
    $site_prefix = $wpdb->get_blog_prefix();
    
    
  
    foreach ($matches[1] as $key=>$keyMatch){
        
      
        foreach($listOFcustomfieldsArray as $keyMatchvalue=>$kayMatchName){
            
           
         
            if(str_replace('_', ' ', $keyMatch) == strtolower($kayMatchName['fieldName'])){
                
                if($kayMatchName['fieldName'] == "Email" || $kayMatchName['fieldName'] == "Level" || $kayMatchName['fieldName'] == "User ID" || $kayMatchName['fieldName'] == "Action"  || $kayMatchName['fieldName'] == "Last login" ){
                   
                    $returnDataKeys[$keyMatch] =  $kayMatchName['fielduniquekey'];
                
                }else{
                    
                    $returnDataKeys[$keyMatch] =  $site_prefix.$kayMatchName['fielduniquekey'];
                    //$columns_list_defult_user_report[$index_count]['key'] = $site_prefix.$value['fielduniquekey'];
                }
                
            }
            
            
        }
        
    }
    if(in_array("site_url", $matches[1]) ){
        
        $returnDataKeys['site_url'] =  'site_url';
    }
    if(in_array("user_id", $matches[1]) ){
        
        $returnDataKeys['user_id'] =  'wp_user_id';
    }
    if(in_array("user_login", $matches[1]) ){
        
        $returnDataKeys['user_login'] =  'user_login';
    }
    if(in_array("user_pass", $matches[1]) ){
        
        $returnDataKeys['user_pass'] =  'user_pass';
    }
    if(in_array("date", $matches[1]) ){
        
        $returnDataKeys['date'] =  'date';
    }
    if(in_array("time", $matches[1]) ){
        
        $returnDataKeys['time'] =  'time';
    }
    
    if(in_array("site_title", $matches[1]) ){
        
        $returnDataKeys['site_title'] =  'site_title';
    }
    
    
    
    
    //echo '<pre>';
    //print_r($returnDataKeys);
    return $returnDataKeys;
}

function get_user_meta_merger_field_value($userid,$key){
    
    
      $value = get_user_option($key, $userid);
      
      return $value;
    
    
}
 function cmp($a, $b) {
    if ($a == $b) return 0;
      
    return (strtotime($a) < strtotime($b))? -1 : 1;
}

function gettaskduesoon(){
 
   
    $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
        );
        $assign_new_role = get_posts( $args );
        
   
    
        
    foreach($assign_new_role as $taskIndex => $tasksObject) {
        
        $tasksID = $tasksObject->ID;
        $keyvalue = get_post_meta( $tasksID, 'key' , false);
        $label = get_post_meta( $tasksID, 'label' , false);
        $attrs = get_post_meta( $tasksID, 'attrs' , false);
        $value['label'] = $label[0];
        $value['attrs'] = $attrs[0];
        if (strpos($keyvalue[0], "task") !== false) { 
         if (strpos($value['label'], 'Status') !== false || strpos($value['label'], 'Date-Time') !== false) {
            
        }else{
             $arrDates[] = array($key=>$value['attrs']);
        }
        
        } 
     }
    
    
 $html_task_due_soon ="";
 $flat =array_reduce($arrDates, 'array_merge', array());
 uasort($flat, "cmp");
 $duetaskcount= 0;
 

 
    foreach ($flat as $index=>$taskdate){
     
       $time = strtotime($taskdate);
       $currenttime = strtotime(date('Y-m-d'));                                      //echo $index;
                                              //  echo $taskdate;
    if($time>= $currenttime) {                                         
    $html_task_due_soon .= '<tr><td>'.$result['profile_fields'][$index]['label'].'</td><td nowrap align="center"><span class="semibold">'.$taskdate.'</span></td></tr>';
    $duetaskcount++;
    }                  
                                               
                                         
    }
    
   if($duetaskcount == 0){
      $html_task_due_soon .= 'No Task Due Soon.';
    }  
    
 return  $html_task_due_soon;
//echo '<pre>';
//print_r($taskduesoon);exit;
    
    
    
    
}

function cmp2($a, $b) {
    if ($a['attrs'] == $b['attrs']) {
        return 0;
    }
    return (strtotime($a['attrs']) < strtotime($b['attrs'])) ? -1 : 1;
}


// [contentmanagersettings key='infocontent']
function settings_key_data($atts) {
    
    $fieldname = $atts['key'];
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $key_data_return=$oldvalues['ContentManager'][$fieldname];

    return $key_data_return;
   
}

add_shortcode('contentmanagersettings', 'settings_key_data');

function bulkimport_mappingdata($fileurl){
    
   
   
 require_once 'third_party/PHPExcel.php';
    
    $tempname = 'import/'.$fileurl;
 
            
    
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);

            $objPHPExcel = $objReader->load($tempname);
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            
            if($highestRow == 1 ){
                
                $createdusercount = 0;
                $errorcount = 1;
                $data_column_array['data']='your sheet is empty.';
        
        
            }else{
               
                for ($colname = 0; $colname <= $highestColumnIndex; $colname++) {
                
              
                    $data_column_array[$colname]['colindex'] =  $colname ;
                    $data_column_array[$colname]['colname'] = $objWorksheet->getCellByColumnAndRow($colname, 1)->getValue();
                
                  
                }
                
                $data_column_array['uploadedfileurl'] = $tempname;
                $data_column_array['totalnumberofrows'] = $highestRow;
                
            }
           
            return $data_column_array;
          
            
        
}


function createuserlist_after_mapping($fileurl,$colmapping_list,$welcomeemailstatus,$selectwelcomeemailtempname){
    
   
   
 require_once 'third_party/PHPExcel.php';
    
            $tempname = $fileurl;
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($tempname);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            
            
  
       
    //echo $welcomeemailstatus;exit;
    $createdusercount=0;
    $errorcount = 0;
    
    for ($row = 2; $row <= $highestRow; ++$row) {
     
        $data_field_array= array();
        
        
        foreach ($colmapping_list as $colmappingKey=>$colmappingdata){
         
            if($colmappingdata['fieldname'] == 'Semail' ){
                
                $email = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                
            }else if($colmappingdata['fieldname'] == 'first_name' ){
                
                $firstname = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                
            }else if($colmappingdata['fieldname'] == 'last_name' ){
                
                $lastname = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                
            }else if($colmappingdata['fieldname'] == 'Role' ){
                
                $role = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                
            }else if($colmappingdata['fieldname'] == 'company_name' ){
                
                $company_name = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
            }
            
        }
        
        $username =$email;
        
        
        
        
        $status = checkimportrowstatus($username,$email,$firstname,$lastname,$role,$company_name);
        
        
       
       if(empty($email)){
           $email="";
       }
       if(empty($company_name)){
           $company_name="";
       }
       // $message[$row]['username'] = $username;
        $message['data'][$row]['email'] = $email;
        $message['data'][$row]['companyname'] = $company_name;
        
      
        if($status == 'clear'){
        
      
        
            $statusresponce = importbulkuseradd(str_replace("+","",$username),$email,$firstname,$lastname,$role,$company_name,$welcomeemailstatus);
           
            
            $message['data'][$row]['status']=$statusresponce['msg'];
            $message['data'][$row]['created_id']=$statusresponce['created_id'];
            
         
            $user_pass=$statusresponce['userpass'];
            
            
          if($message['data'][$row]['status'] == 'User created successfully.' || $message['data'][$row]['status'] == 'User added to this site Successfully.'){
              
              $createdusercount++;
            
              
              
           foreach ($colmapping_list as $colmappingKey=>$colmappingdata){
               
               if($colmappingdata['fieldname'] != 'Semail' && $colmappingdata['fieldname'] != 'first_name' && $colmappingdata['fieldname'] != 'last_name' && $colmappingdata['fieldname'] != 'Role' && $colmappingdata['fieldname'] != 'company_name' ){
                   
                   
                   if(!empty($colmappingdata['fieldvalue'])){
                       
                       
                     $getrow_value = $objWorksheet->getCellByColumnAndRow($colmappingdata['fieldvalue'], $row)->getValue();
                     
                     update_user_option($statusresponce['created_id'], $colmappingdata['fieldname'], $getrow_value);
                     //$data_field_array[] = array('name'=>$colmappingdata['fieldname'],'content'=>$getrow_value);
                     $user_data_array[$statusresponce['created_id']][$colmappingdata['fieldname']] = $getrow_value;
                   }
                  
                   
                   
               }
            }
              
            $user_data_array[$message['data'][$row]['created_id']]['Semail'] = $email;
            $user_data_array[$message['data'][$row]['created_id']]['user_login'] = $username;
            $user_data_array[$message['data'][$row]['created_id']]['user_pass'] = $user_pass;
            $user_data_array[$message['data'][$row]['created_id']]['first_name'] = $firstname;
            $user_data_array[$message['data'][$row]['created_id']]['last_name'] = $lastname;
            $user_data_array[$message['data'][$row]['created_id']]['Role'] = $role;
           
            
          
            }else{
		
                $errorcount++;
		
                
            }
            
        }else{
            
            $message['data'][$row]['status'] = $status;
            $message['data'][$row]['created_id']='';
            $errorcount++;
        } 
        
     
    }
  
  
if($welcomeemailstatus == 'send'){ 
        
      // echo $selectwelcomeemailtempname;
      
       
       $welcomeemail_status = send_bulk_import_welcome_email($to_message_array,$user_data_array,$selectwelcomeemailtempname,$otherfields_array); 
      // echo $welcomeemail_status;exit;
       
   }else{
       
       $welcomeemail_status="Do not send welcome email's."; 
   }
   
   $message['createdcount']=$createdusercount;
   $message['errorcount']=$errorcount;
   $message['result']=$welcomeemail_status;
  
   
  
       
    
  
   
   return $message;
}


function wpse_183245_upload_dir( $dirs ) {
    
    $dirs['subdir'] = '/import';
    $dirs['path'] = dirname(__FILE__).'/import';
    $dirs['url'] =  get_site_url().'/wp-content/plugins/EGPL/import';
    
    
    return $dirs; 
}

function importbulkuseradd($username,$email,$firstname,$lastname,$role,$company_name,$welcomeemailstatus){
    
    require_once('../../../wp-load.php');
    
    if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
              $get_all_roles = get_option($get_all_roles_array);
              foreach ($get_all_roles as $key => $item) {
                 if($role == $item['name']){
                     $role = $key;
                     
                 }
              }
    
    
    $user_id = username_exists($username);
        if (!$user_id and email_exists($email) == false) {
        
            $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $user_id = myregisterrequest_new_user($username, $email);//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
            
            $type = gettype($user_id);
          
           // echo $type;exit;
        if($type == 'object'){
            
             if(empty($user_id->errors['invalid_username'][0])){
                 
                $status['msg'] = $user_id->errors['invalid_email'][0];
             
             }else{
                 
                $status['msg'] = $user_id->errors['invalid_username'][0];  
             
             }
              
                
                $status['created_id'] = '';
        
                
            }else{
             
              
              $status['created_id'] = $user_id;
              $status['msg'] = 'User created successfully.';
              $meta_array['first_name']=$firstname;
              $meta_array['last_name']=$lastname;
              $meta_array['company_name']=$company_name;
              add_user_to_blog(1, $user_id, $role);
               
              if($welcomeemailstatus == 'send'){
                
                  $t=time();
                  $meta_array['convo_welcomeemail_datetime']=$t*1000;
                  $plaintext_pass=wp_generate_password( 8, false, false );
                  wp_set_password( $plaintext_pass, $user_id );
                  $status['userpass'] = $plaintext_pass;
              
              }
              
            
              add_new_sponsor_metafields($user_id,$meta_array,$role);
              
              
              
              
            }
            
            
            
        } else {
             
            $currentblogid = get_current_blog_id();
            $user_blogs = get_blogs_of_user( $user_id );
            $user_status_for_this_site = 'not_exist';
            foreach ($user_blogs as $blog_id) { 
               
               if($blog_id->userblog_id == $currentblogid ){
                   
                   $user_status_for_this_site = 'alreadyexist';
                   break;
               }
               
            }
            if($user_status_for_this_site == 'alreadyexist'){
        
               $status['msg'] = 'A user with this email already exists. User not created.';
               $status['created_id'] ='';
        
            }else{
                
               $currentblogid = get_current_blog_id();
               switch_to_blog($currentblogid); 
               
              
               
               $status['created_id'] = $user_id;
               $status['msg'] = 'User added to this site Successfully.';
               $meta_array['first_name']=$firstname;
               $meta_array['last_name']=$lastname;
               $meta_array['company_name']=$company_name;
               
               
               if($welcomeemailstatus == 'send'){
                
                  $t=time();
                  $meta_array['convo_welcomeemail_datetime']=$t*1000;
                  $plaintext_pass=wp_generate_password( 8, false, false );
                  wp_set_password( $plaintext_pass, $user_id );
                  $status['userpass'] = $plaintext_pass;
              
              }
              
              add_user_to_blog($currentblogid, $user_id, $role);
              add_new_sponsor_metafields($user_id,$meta_array,$role);
             
             
              
            }    
            
      }
       
       
       
       return $status;
}


function checkimportrowstatus($username,$email,$firstname,$lastname,$role,$company_name){
    global $wp_roles;
     
    $all_roles = $wp_roles->get_names();
   
    
    $all_roles = array_map('strtolower', $all_roles);//edit new add 
    
    if(!empty($username)&&!empty($email)&&!empty($firstname)&&!empty($lastname)&&!empty($role)&&!empty($company_name)){
        //$role = ucwords($role);
		$role =	strtolower($role);//edit
        if (in_array($role, $all_roles)) {
            $status = 'clear';
           
           
        }else{
        $status= "User level does not exist. User not created.";
       
       }
        
    }else{
        $status= 'A required field such as email, first name, etc. is missing. User not created.';
       
    }
    
    return $status; 
}

function send_bulk_import_welcome_email($to_message_array,$user_data_array,$selectwelcomeemailtempname,$otherfields_array){
    
    require_once('../../../wp-load.php');
    require_once 'Mandrill.php';
    global $wpdb, $wp_hasher;
    
   
    
    
   
    if(!empty($to_message_array)||!empty($user_data_array)){
try { 
    
    
  
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mandrill = $oldvalues['ContentManager']['mandrill'];
    
    $mandrill = new Mandrill($mandrill);
    $settitng_key='AR_Contentmanager_Email_Template_welcome';
    $sponsor_info = get_option($settitng_key);
        
    $subject = $sponsor_info[$selectwelcomeemailtempname]['welcomesubject'];
    $body=stripslashes ($sponsor_info[$selectwelcomeemailtempname]['welcomeboday']);
    
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    $replay_to = $sponsor_info[$selectwelcomeemailtempname]['replaytoemailadd'];
    $formname =$sponsor_info[$selectwelcomeemailtempname]['fromname'];
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    if(empty($formemail)){
        $formemail = 'noreply@expo-genie.com';
        
    }
    $bcc = $sponsor_info[$selectwelcomeemailtempname]['BCC'];
    //$cc = $sponsor_info[$selectwelcomeemailtempname]['CC'];
    $bcc_array = $bcc;
    //$cc_array = explode(',',$cc);
   
   
    $site_url = get_option('siteurl' );
    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
     $field_key_string = getInbetweenStrings('{', '}', $body);
     $field_key_subject = getInbetweenStrings('{', '}', $subject);
          
   
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    
    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url),
        array('name'=>'site_title','content'=>$fromname)
        );
        
    
        foreach($user_data_array as $userID=>$Onerowvalue){
        
            $data_field_array= array();
           
            
            
            $userdata = get_user_by_email($Onerowvalue['Semail']);
            $t=time();
            update_user_option($userdata->ID, 'convo_welcomeemail_datetime', $t*1000);
            $email_address = $Onerowvalue['Semail'];
            $first_name = $Onerowvalue['first_name'];
            $all_meta_for_user = get_user_meta($userdata->ID);
            
          
              
              
              
             
           
             foreach($field_key_subject as $index_subject=>$keyvalue_subject){
                  
                      if($keyvalue_subject == 'Role' || $keyvalue_subject == 'site_title' || $keyvalue_subject == 'date' || $keyvalue_subject == 'time' || $keyvalue_subject == 'site_url' || $keyvalue_subject == 'user_pass'|| $keyvalue_subject == 'user_login'){
                      
                       
                      if($keyvalue_subject == 'user_pass'){
                          
                            
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue_subject == 'user_login'){
                          
                         
                          
                          
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$userdata->user_login);  
                      }elseif($keyvalue_subject == 'Role'){
                          
                          $user_id = $userdata->ID;
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {
                              
                              if(implode(', ', $getcurrentuserdata->roles) == $key){
                                  
                                  $currentuserRole = $name['name'];
                                  
                                  
                              }
                              
                              
                              
                          }
                          
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$currentuserRole); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                   
                        
                       if (!empty($all_meta_for_user[$keyvalue_subject][0])) {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $keyvalue_subject)); // 1 
                           
                           
                            $getfieldType = getcustomefieldKeyValue($keyvalue_subject,"fieldType");
                        
                            
                           
                        
                        if($getfieldType == 'date') {
                            
                            
                          
                          $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0])/1000);
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                 
                                $data_field_array[] = array('name'=>$index_subject,'content'=>$all_meta_for_user[$keyvalue_subject][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index_subject,'content'=>''); 
                          
                       }
                   
                      
                      
                      
                      
                  }
                 
                 
                 
             }
            foreach($field_key_string as $index=>$keyvalue){
                
                        
                     
                      
                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Semail' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){
                      
                          
                      if($keyvalue == 'user_pass'){
                          
                           
                            $user_id = $userdata->ID;
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index,'content'=>$plaintext_pass);  
                          
                      }elseif($keyvalue == 'user_login'){
                          
                       
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->user_login);  
                          
                         
                          
                      }elseif($keyvalue == 'Role'){
                          
                        
                          $user_id = $userdata->ID;
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {
                              
                              if(implode(', ', $getcurrentuserdata->roles) == $key){
                                  
                                  $currentuserRole = $name['name'];
                              }
                          }
                          $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole); 
                      }elseif($keyvalue == 'Semail'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$email_address); 
                      }elseif($keyvalue == 'wp_user_id'){
                          
                        
                          $data_field_array[] = array('name'=>$index,'content'=>$userdata->ID); 
                      }
                      
                      
                      
                   }else{
                       
                       
                       
                        
                       if (!empty($all_meta_for_user[$keyvalue][0])) {
                           
                           $result = multidimensional_search($colsdatatype, array('colkey' => $all_meta_for_user[$keyvalue][0])); // 1 
                           
                           $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");
                           
                        if($getfieldType == 'date') {
                            
                          $date_value =   date('d-m-Y', intval($all_meta_for_user[$keyvalue][0]/1000));
                          $data_field_array[] = array('name'=>$index,'content'=>$date_value);
                          
                        } else{
                             
                                 
                                $data_field_array[] = array('name'=>$index,'content'=> $all_meta_for_user[$keyvalue][0]);  
                             
                        }
                       }else{
                           
                                $data_field_array[] = array('name'=>$index,'content'=>''); 
                          
                       }
                  
                      
                      
                      
                      
                  }
                 
                 
                 
             }
              
          
              
                
           $to_message_array[]=array('email'=>$email_address,'name'=>$first_name,'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$email_address,
                'vars'=>$data_field_array
           );
 
        }
    
    
    
    
        $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
        $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
        $logourl = '';
        
        if(!empty($mainheaderlogo)){
            
            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderlogo.'" alt="" width="250" />';
        
        }else if(!empty($mainheaderbackground)){
            
            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderbackground.'" alt="" height="100" />';
        
            
        }
        
        $html_body_message = '<table width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
            <tbody>
            <tr>
            <td align="left">
            <div style="border: solid 1px #d9d9d9;">
            <table id="header" style="line-height: 1.6;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
            <tbody>
            <tr>
            <td style="text-align: center;">'.$logourl.'</td>
            </tr>
            </tbody>
            </table>
            <table id="content" style="padding-right: 30px;padding-left: 30px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
            <tbody>
            <tr>
            <td style="border-top: solid 1px #d9d9d9;" colspan="2">
            <div style="padding: 1em 0;">
            '.$body.'
            </div>
            </td>
            </tr>
            </tbody>
            </table>
            </div>
            </td>
            </tr>
            </tbody>
            </table>
            <p>&nbsp;</p>'; 
    
   $body_message =    $body ;
//   if(sizeof($bcc_array) > 1){
//       
//            foreach ($bcc_array as $key => $value) {
//                $to_message_array[] = array('email' => $value, 'name' => '', 'type' => 'bcc');
//            }
//        }else{
//       
//            if(!empty($bcc_array)){
//
//                $to_message_array[]=array('email'=>$bcc_array[0],'name'=>'','type'=>'bcc');
//            }
//        }
//   if(sizeof($cc_array) > 1){
//       
//            foreach ($cc_array as $key => $value) {
//                $to_message_array[] = array('email' => $value, 'name' => '', 'type' => 'cc');
//            }
//        }else{
//       
//            if(!empty($cc_array)){
//
//                $to_message_array[]=array('email'=>$cc_array[0],'name'=>'','type'=>'cc');
//            }
//        }
   $get_currentsiteURl = get_site_url();
   $message = array(
        
        'html' => $html_body_message,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $formname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replay_to),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
        
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
         "tags" => [$get_currentsiteURl]
        
    );
   
    // exit;
  
    $lastInsertId = contentmanagerlogging('Import Welcome Email',"Admin Action",serialize($message),$user_ID,$user_info->user_email,"pre_action_data");
     
    $async = false;
    $ip_pool = 'Main Pool';
   
    $send_at = '';
    $result['send_at_date'] =  '';
    $result['result_send_mail'] = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    return $result;
    
   
    
}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    
 
    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}

}  
    
}

function portalsettingheaderimage( $base64_img, $title , $file_type ) {

	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
        
        
        $img = str_replace('data:image/png;base64,', '', $base64_img);
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace('data:image/jpg;base64,', '', $img);
       
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
        $fielname = uniqid();
        
	$file = $upload_path . $fielname . '.'.$file_type;
	$success = file_put_contents($file, $data);
	
        
        
	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $fielname ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $upload_dir['url'] . '/' . basename( $fielname )
	);

	$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $fielname.$file_type );
        return $upload_dir['url'] . '/' . basename( $fielname ).'.'.$file_type;exit;
}


/// child theme code just like short code and hide menu bar 
function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css' );
}
//add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
//add_action( 'after_setup_theme', 'avada_lang_setup' );


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
   if (!current_user_can('administrator')) {
         show_admin_bar(false);
    }
}

function no_admin_access()
{
 if ( !defined( 'DOING_AJAX' ) && !current_user_can('administrator') ){
     wp_redirect( site_url() );
     die();
  }
}
add_action( 'admin_init', 'no_admin_access', 1 );



function wpse_lost_password_redirect() {

    // Check if have submitted
    $confirm = ( isset($_GET['action'] ) && $_GET['action'] == resetpass );

    if( $confirm ) {
        wp_redirect( home_url() );
        exit;
    }
}
add_action('login_headerurl', 'wpse_lost_password_redirect');





// ShortCode For Display Name
function displayname_func( $atts ){
	  global $current_user;
      get_currentuserinfo();
      return $current_user->display_name;
}
add_shortcode( 'user_name', 'displayname_func' );


function specialtext_shortcode( $atts, $content = null ) {
    
    global $current_user, $wpdb;
    if ( is_user_logged_in() ) {
    $role = $wpdb->prefix . 'capabilities';
    $current_user->role = array_keys($current_user->$role);
    $role = $current_user->role[0];
    $role_list =explode(",",$atts['invisiblefor']);
    if (in_array($role, $role_list)) {
        
        
    }else{
        
        return $content;
    }
   
    } 
   
        
        
}
add_shortcode( 'specialtext', 'specialtext_shortcode' );


function auth_with_map_dynamics($request_call){
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mapapikey = $oldvalues['ContentManager']['mapapikey'];
    $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
    $access_hash = md5($mapsecretkey.$request_call);
    
    //ASSEMBLE THE POST VALUES ARRAY
    $post_values = array('key'=>$mapapikey, 'access_hash'=>$access_hash, 'call'=>$request_call, 'format'=>'json');
    
    $ch = curl_init('http://api.map-dynamics.com/services/auth/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_values);
    $result = curl_exec($ch);
    curl_close($ch);
    $results = json_decode($result);
   
    if($results->status == 'success'){
        
        $output  =  $results->results->hash;
        
    }else{
        
       $output  = 'error'; 
        
    }
    
    return $output;
    
}


function insert_exhibitor_map_dynamics($data_array){
    
    
    $hsah = auth_with_map_dynamics('exhibitors/insert');
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mapapikey = $oldvalues['ContentManager']['mapapikey'];
    $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
    $post_values = array('key'=>$mapapikey, 'call'=>'exhibitors/insert', 'hash'=>$hsah, 'format'=>'json');
    
    
    $dataarray =  array_merge($post_values, $data_array);
    //echo '<pre>';
   // print_r($dataarray);
    
   // exit;
  
    $ch = curl_init('http://api.map-dynamics.com/services/exhibitors/insert/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataarray);
    $result = curl_exec($ch);
    curl_close($ch);
    $results = json_decode($result);
    
     
    return $results;
    
    
    
    
}

function update_exhibitor_map_dynamics($data_array){
    
    
    $hsah = auth_with_map_dynamics('exhibitors/update');
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mapapikey = $oldvalues['ContentManager']['mapapikey'];
    $mapsecretkey = $oldvalues['ContentManager']['mapsecretkey'];
    $post_values = array('key'=>$mapapikey, 'call'=>'exhibitors/update' ,'hash'=>$hsah, 'format'=>'json');
    $dataarray = array_merge($post_values, $data_array);
    
    //echo '<pre>';
    //print_r($dataarray);
    
   // exit;
    
    
    $ch = curl_init('http://api.map-dynamics.com/services/exhibitors/update/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataarray);
    $result = curl_exec($ch);
    curl_close($ch);
    $results = json_decode($result);
    
     
    return $results;
    
    
    
    
}
// auto upload plugin from github
function changeuseremailaddress($request){
    
     try{
      
        
         
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Edit user email',"Admin Action",serialize($request),''.$user_ID,$user_info->user_email,"pre_action_data");
        $newemail = $request['newemailaddress'];
        $welcome_email_status = $request['welcomememailstatus'];
        $welcome_selected_email_template = $request['selectedtemplateemailname'];
        $userid = $request['userid'];
        $email_status = isValidEmail($newemail);
        if($email_status){
            if( email_exists( $newemail )) {
                
                $result_status['msg'] = 'A user with that email address already exists Please try another email address.';
            
                
            }else{
                
                //$result_update = wp_update_user( array ( 'ID' => $userid, 'user_login' => $newemail,'user_email'=>$newemail) ) ;
                global $wpdb;
                $tablename = $wpdb->prefix . "users";
                $sql = $wpdb->prepare( "UPDATE `wp_users` SET `display_name`='".$newemail."' , `user_login`='".$newemail."',`user_email`='".$newemail."' WHERE `ID`=".$userid."", $tablename );
                $result_update = $wpdb->query($sql);
                //echo '<pre>';
                //print_r($result_update);exit;
                update_user_option($userid, 'nickname', $newemail);
                //echo $result_update;
                //echo  "UPDATE ".$tablename." SET user_login=".$newemail.",user_email=".$newemail." WHERE ID=".$userid."";
                $result_status['msg'] = 'update';
               
                if($result_update == 1 && $welcome_email_status == 'checked'){
                    custome_email_send($userid,$newemail,$welcome_selected_email_template);
                }
               
            }
            
        }else{
            
            $result_status['msg'] = 'Email address is invalid. Please try again and enter a valid email.';
        }
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($result_status));
        
       echo json_encode($result_status);
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
    
    
}

function checkwelcomealreadysend($request){
    
     try{
      
        
         
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        
        $lastInsertId = contentmanagerlogging('Check Welcome Email Send',"Admin Action",serialize($request),''.$user_ID,$user_info->user_email,"pre_action_data");
        $emailaddress_array=explode(",", $request['emailAddress']);
        $usertimezone=intval($request['usertimezone']);
        foreach($emailaddress_array as $key=>$emailaddress){
            
            $user = get_user_by( 'email', $emailaddress );
            $welcome_email_date = get_user_option('convo_welcomeemail_datetime', $user->ID);
            if(!empty($welcome_email_date)){
                
                $last_send_welcome_email= date('d-M-Y H:i:s', $welcome_email_date/1000);
                if($usertimezone > 0){
                    $last_send_welcome_date_time = (new DateTime($last_send_welcome_email))->sub(new DateInterval('PT'.abs($usertimezone).'H'))->format('d-M-Y H:i:s');
                }else{
                    $last_send_welcome_date_time = (new DateTime($last_send_welcome_email))->add(new DateInterval('PT'.abs($usertimezone).'H'))->format('d-M-Y H:i:s');
                
                }
                $responce[$emailaddress]=$last_send_welcome_date_time;
            }
            
        }
        contentmanagerlogging_file_upload ($lastInsertId,serialize($responce));
        
       echo json_encode($responce);
         
    }catch (Exception $e) {
       
        contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
    }
 
 die();  
    
    
}

function isValidEmail($email){ 
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

include_once('updater.php');


if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
        $config = array(
            'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
            'proper_folder_name' => 'EGPL', // this is the name of the folder your plugin lives in
            'api_url' => 'https://api.github.com/repos/QasimRiaz/EGPL', // the GitHub API url of your GitHub repo
            'raw_url' => 'https://raw.github.com/QasimRiaz/EGPL/master', // the GitHub raw url of your GitHub repo
            'github_url' => 'https://github.com/QasimRiaz/EGPL', // the GitHub url of your GitHub repo
            'zip_url' => 'https://github.com/QasimRiaz/EGPL/zipball/master', // the zip url of the GitHub repo
            'sslverify' => true, // whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
            'requires' => '3.0', // which version of WordPress does your plugin require?
            'tested' => '3.3', // which version of WordPress is your plugin tested up to?
            'readme' => 'README.md', // which file to use as the readme for the version number
            'access_token' => '', // Access private repositories by authorizing under Appearance > GitHub Updates when this example plugin is installed
        );
        new WP_GitHub_Updater($config);
    }

//add_filter('woocommerce_payment_complete_order_status', 'exp_autocomplete_paid_orders', 10, 2);
add_action('woocommerce_thankyou', 'exp_autocomplete_all_orders',10,2);

function exp_autocomplete_all_orders($order_id) {

        if (!$order_id)
            return;
        $orderstatus = "completed";
        //$order = new WC_Order($order_id);
        $order = wc_get_order($order_id);
        //$user_ID = get_current_user_id();
        $payment_method = get_post_meta($order->id, '_payment_method', true);

        //ravenhub additional code -- 01-06-2020////
        
        global  $wpdb;
        
        $site_prefix = $wpdb->get_blog_prefix();
	    $postid = get_current_user_id();
        if(empty($postid)){
            
            
            $postid = get_post_meta($order_id,'_customer_user',true);
            if(empty($postid)){
                
               $postid = $_SESSION['userID'];
               $useremail = $_SESSION['useremail'];
               //update_post_meta($order_id,'_customer_user',$postid);
            }
            
            
        }
        
        
       
        
	    $data = array();                                                                    
        $getsiteurl = get_site_url();
        $companyname = get_user_meta($postid, $site_prefix.'company_name',true);
        
        
        $ordersreporturl =  $getsiteurl.'/order-reporting/';
        $getcodeuro = str_replace("https://","",$getsiteurl);
        $subscribersID = str_replace("/","-",$getcodeuro);
        $tasknotificationurl = "https://api.ravenhub.io/company/ahWkagLbTC/subscribers/".$subscribersID."/events/kyVFhfWFtB" ;//$sponsor_info['ContentManager']['ravenhuburls']['tasknotificationtemplates']['url'];
        $data = array("company_name" => $companyname,"ordersreporturl"=>$ordersreporturl); 
        $parameter_json = json_encode($data);
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/ravenhub_api_request.php';
        $ravenhubapirequest = new Revenhubapi();
        $result_send_notification = $ravenhubapirequest->sendnotifaciton($tasknotificationurl,$parameter_json);
        
        $email_body_message_for_admin['ravenhub']['responce'] = $result_send_notification;
        $email_body_message_for_admin['ravenhub']['requestdata'] = $parameter_json;
        $email_body_message_for_admin['ravenhub']['requestedurl'] = $tasknotificationurl;
        
        
        //ravenhub additional code -- 01-06-2020////
        $original_order_fields = wc_get_order( $order_id );
        
        
        
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
        if($remmaningamount !=0){
            
            $original_order = wc_get_order( $order_id );
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
                                        'qty'       => 0,
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
                                
                                    wc_add_order_item_meta( $item_id, '_original_order_id', $order_id );

                                /* translators: Payment number for product's title */
                                    wc_update_order_item( $item_id, array( 'order_item_name' => sprintf( __( 'Payment #%d for %s', 'woocommerce-deposits' ), 2, $item['product']->get_title() ) ) );
                                    
                            
                        }
                        
                                    
                        // (WC_Abstract_Order) Calculate totals by looking at the contents of the order. Stores the totals and returns the orders final total.
                        $new_order->calculate_totals( wc_tax_enabled() );

                        // Set future date and parent
                        $new_order_post = array(
                            'ID'          => $new_order->id,
                            'post_date'   => date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ),
                            'post_parent' => $order_id,
                        );
                        wp_update_post( $new_order_post );                                   
                        do_action( 'woocommerce_deposits_create_order', $new_order->id );
                        $new_order->update_status('wc-pending-deposit');

                        foreach ( $new_order->get_items() as $order_item_id => $order_item ) {
                         $order_item_pro_id = wc_get_order_item_meta($order_item_id, '_product_id', true);
                            if (in_array($order_item_pro_id, $productremaningProductsID)) {
                                    $order_item_id_update = $order_item_id;                                 
                                    wc_add_order_item_meta( $order_item_id_update, '_remaining_balance_order_id', $order_id );                                   
                            }
                        }
                        $new_order_ID =  $new_order->id;
                    }
            
   
                
                
            
            $emails = WC_Emails::instance();
            $emails->customer_invoice( wc_get_order( $new_order_ID ) );
            $orderstatus = "partial-payment";
            $mailer = WC()->mailer();
            $mails = $mailer->get_emails();
            if (!empty($mails)) {
                foreach ($mails as $mail) {
                    if ($mail->id == 'customer_completed_order') {
                        $mail->trigger($order_id);
                    }
                }
            }
        }
        
            //if($payment_method == 'cheque'){
            foreach( $order->get_items() as $item ) {     

                $porduct_ids_array[] = $item['product_id'];  				
            }
           
            
                 //exp_updateuser_role_onmpospurches($order,$porduct_ids_array);
                 //echo '<pre>';
                 //print_r($porduct_ids_array);exit;
                 exp_updateuser_role_onmpospurches($order->id,$porduct_ids_array);
                 $order->update_status($orderstatus);
     
}
function exp_autocomplete_paid_orders($order_status, $order_id) {
        
       
        if (!$order_id)
                return;
        $order = wc_get_order($order_id);
        
       
        
        $payment_method = get_post_meta($order->id, '_payment_method', true);
        
        
            if (count($order->get_items()) > 0) {
                foreach ($order->get_items() as $item_id => $item_obj) {
                        
                       
                        $result_check = wc_get_order_item_meta($item_id, '_bundled_by', true);
                        if(empty($result_check)){
                            
                            $porduct_ids_array[] = wc_get_order_item_meta($item_id, '_product_id', true);
                            
                        }
                        
                        
                   
                }
            }
            
            
         
            //exp_updateuser_role_onmpospurches($order->id,$porduct_ids_array);
            
            
            
           
          
            if ($order_status == 'processing' && ($order->status == 'on-hold' || $order->status == 'pending' || $order->status == 'failed')) {
                return 'completed';
            }
            return 'completed';
}




add_action( 'woocommerce_checkout_process', 'reviewboothproducts', 10 );



function reviewboothproducts($order){
    
    
    
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/floorplan-manager.php';
    $floorplanObject = new FloorPlanManager();
    $items = WC()->cart->get_cart();
     
      $contentmanager_settings = get_option( 'ContenteManager_Settings' );
      $FloorpLanid = $contentmanager_settings['ContentManager']['floorplanactiveid'];
      
      
   
    
    
    foreach ($items as $item => $values)
    {
        $_product = $values['data']->post;
        $product_ID = $_product->ID;
        $product_title = $_product->post_title;
       
        $getthisproductdetailinfloorplan = $floorplanObject->getProductstauts($product_ID);
        
        $get_BoothCellID = $getthisproductdetailinfloorplan['BoothID'];
        
        if(!empty($get_BoothCellID)){
            
         $ViewerLockstatus = $floorplanObject->getFloorplanStatus($FloorpLanid);
         if($ViewerLockstatus != 'lock'){
             
            $getBoothOwner = $getthisproductdetailinfloorplan['BoothOwner'];
        
            if($getBoothOwner != 'none' && $getBoothOwner != ''){
            
            
                wc_add_notice( __( 'Booth number '.$product_title.' in your cart is no longer available for purchase. Please try another booth.' ), 'error' );
            }
            
         }else{
             
            wc_add_notice( __( 'The floorplan is currently locked by the Administrators so checkout is not possible. Please try again later.' ), 'error' );
         
            
         }
        }
    }
  
   
    
}

function exp_updateuser_role_onmpospurches($order,$porduct_ids_array){
        
      
        // echo '<pre>';
        // print_r($porduct_ids_array);exit;

    
    
        if(is_array($order)){
            
            $order_ID = $order->id;
            
        }else{
            
            $order_ID = $order;
        }
 
        $current_user = get_current_user_id();
        
        if(empty($current_user)){
            
            
            $current_user = get_post_meta($order_ID,'_customer_user',true);
            
            if(empty($current_user)){
                
               $current_user = $_SESSION['userID'];
               $useremail = $_SESSION['useremail'];
               update_post_meta($order_ID,'_customer_user',$current_user);
               
            }
        }
         $lastInsertId = contentmanagerlogging('New Order Placed',"User Action",serialize($order_ID),$current_user,'',"pre_action_data");
   
       // $lastInsertId = contentmanagerlogging('Purches MPOs',"User Action",serialize($order),''.$current_user->id,$current_user->user_email,"pre_action_data");
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
        $boothsettingsstatus = "noboothavaiable";
        $boothpurchaseenablestatus = $woocommerce_rest_api_keys['ContentManager']['boothpurchasestatus'];
        $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
        $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
        $woocommerce = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );

                    if (count($porduct_ids_array) > 0) {
                        $getpackagelevel = [];
                        foreach ($porduct_ids_array as $item=>$ids) {

                            $getproduct_detail = $woocommerce->products->get( $ids );
                            $productID =  $ids;
                            if($getproduct_detail->product->categories[0] != 'Package' && $getproduct_detail->product->categories[0] != 'Add-ons'){
                                
                                
                                $boothsettingsstatus = "boothavaiable";
                                
                                $id = wp_insert_post(array('post_title'=>'Booth Purchase Review_'.$order_ID, 'post_type'=>'booth_review', 'post_content'=>''));
                                update_post_meta( $id, 'porductID', $ids );
                                update_post_meta( $id, 'orderID', $order_ID );
                                update_post_meta( $id, 'OrderUserID', $current_user);

                                if(!empty($boothpurchaseenablestatus) && $boothpurchaseenablestatus == "enabled"){

                                    $OrderUserID = $current_user;
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
                 
                    if(!empty($get_productlevel)){
                        
                         $seletedroleValue = $get_productlevel;
                         if(!empty($seletedroleValue)){
                            $assign_role[] = $seletedroleValue;
                         }
                    }
                    
                    $selectedTaskListData = get_post_meta( $ids);
                    $selectedTaskList = unserialize($selectedTaskListData['seletedtaskKeys'][0]);
                    
                    if(!empty($selectedTaskList['selectedtasks'])){
                        foreach ($selectedTaskList['selectedtasks'] as $taskindex1=>$taskKey1){
                        
                      
                            $latestProductsValue['selectedtasks'][] = $taskKey1;
                       
                        }  
                    }   
                    if ($term_obj_list[0]->slug == 'packages') {   
                        if(!empty($get_productlevel)){
                        $getpackagelevel[] = $get_productlevel;
                        }    
                    }
                }
            }
            
            
                        $user_info = get_userdata($current_user);
                        $user_info_to_update = get_userdata($current_user);
                         
                                //      echo 'QasimRiaz<pre>';
                                //     print_r($getpackagelevel);
                                //     echo 'QasimRiaz<pre>';
                                //     echo 'Qassimriaz<pre>';
                                //      print_r($assign_role);
                                //      echo 'Qassimriaz<pre>';
                                //     $get_email_template='AR_Contentmanager_Email_Template_welcome';
                                //     $email_template_data = get_option($get_email_template);
                                //   echo "<pre>";
                                //   print_r($email_template_data['Booth Turn Email']['fromname']);
                                //   print_r($email_template_data['welcome_email_template']);exit;
                                  
                        if(!empty($getpackagelevel)){
                         
                            $counter=0;
                            foreach($getpackagelevel as $key1=>$roleName){

                                $productroleOrder = getroleorder($roleName);
                                //$get_Ovveride_check = get_post_meta( $porduct_ids_array[$counter],"overrideCheck",true );//Ovveride Check to override user level
                                // echo "---";
                                // echo $get_Ovveride_check;
                                
                                        //  echo 'QasimriaZ<pre>';
                                        //  echo $productroleOrder;
                                        //  echo 'QasimriaZ<pre>';
                                if ($user_info->roles[0] != 'administrator' && $user_info->roles[0] != 'contentmanager' ) {
                                    
                                    $u = new WP_User($current_user);
                                    $currentroleName = $u->get_role();
                                    
                                        if($key1 == 0){
    
                                            $currentroleOrder = getroleorder($user_info->roles[0]);
                                            
                                        }else{
                                            
                                            $currentroleOrder = getroleorder($getpackagelevel[$key1-1]);
                                        }
                                     
                                            //   echo 'Qasimriiaz<pre>';
                                            //   echo $roleName.'<br>';
                                            //   echo $user_info->roles[0].'<br>';
                                            //  echo $currentroleName.'<br>';
                                            //   echo $productroleOrder.'_________';
                                            //   echo 'Qasimriiazz<pre>';
                                            
                                            if($productroleOrder < $currentroleOrder) {

                                                $u->set_role($roleName);
                                                $responce['assignrole'] = $roleName;
                                                $loggin_data['rolename'][] = $roleName;
                                                    
                                                    // echo $roleName.'Orderuu___________________';
                                            } else 
                                            {

                                            $responce['assignrole'] = $currentroleName['name'];
                                            $loggin_data['rolename'][] = $currentroleName['name'];
                                            }
                                }
                                else{
                                    echo "UserRole Remain Same";
                                }
                                $counter++; 
                             }
                            //$u = new WP_User($current_user);
                            //$u->set_role($getpackagelevel);
                            //$loggin_data['rolename'][] = $getpackagelevel;
                            //echo 'Qasimriaz_________';
            
                        }else{
                            
                                
                                //echo 'Qasimriaz<pre>';
                                //print_r($assign_role);
                                $counter=0;
                                foreach($assign_role as $key=>$roleName){

                                    $productroleOrder = getroleorder($roleName);

                                    $getproduct_detail = $woocommerce->products->get( $porduct_ids_array[$counter] );
                                    if($getproduct_detail->product->categories[0] == 'Add-ons'){

                                        if ($user_info->roles[0] != 'administrator' && $user_info->roles[0] != 'contentmanager') {
        
                                            $u = new WP_User($current_user);
                                            $currentroleName = $u->get_role();
                                            
                                            if($key == 0){ 
                                                $currentroleOrder = getroleorder($user_info->roles[0]);                                        
                                            }else{                                         
                                                $currentroleOrder = getroleorder($assign_role[$key-1]);
                                            }

                                            // echo $productroleOrder;
                                            // echo $currentroleOrder;
                                            if ($productroleOrder < $currentroleOrder) {
                                                // echo"IN HERE";
                                                $u->set_role($roleName);
                                                $responce['assignrole'] = $roleName;
                                                $loggin_data['rolename'][] = $roleName;                                      
                                                // echo $roleName.'___________________';
                                            } else {

                                                $responce['assignrole'] = $currentroleName['name'];
                                                $loggin_data['rolename'][] = $currentroleName['name'];
                                            }
                                        }


                                    }else{

                                        $get_Ovveride_check = get_post_meta( $porduct_ids_array[$counter],"overrideCheck",true );

                                        if ($user_info->roles[0] != 'administrator' && $user_info->roles[0] != 'contentmanager' && $get_Ovveride_check=='0') {

                                            $u = new WP_User($current_user);
                                            $currentroleName = $u->get_role();
                                            
                                            if($key == 0){ 
                                                $currentroleOrder = getroleorder($user_info->roles[0]);                                        
                                            }else{                                         
                                                $currentroleOrder = getroleorder($assign_role[$key-1]);
                                            }
                                            // echo $roleName.'<br>';
                                            // echo $user_info->roles[0].'<br>';
                                            // echo $currentroleOrder.'<br>';
                                            // echo $productroleOrder.'_________';
                                            // exit;
                                            if ($productroleOrder < $currentroleOrder) {
                                                $u->set_role($roleName);
                                                $responce['assignrole'] = $roleName;
                                                $loggin_data['rolename'][] = $roleName;                                      
                                                //echo $roleName.'___________________';
                                            } else {

                                                $responce['assignrole'] = $currentroleName['name'];
                                                $loggin_data['rolename'][] = $currentroleName['name'];
                                            }
                                        }

                                    }

        
                                $counter++;
                            }
                            
                        }
            
                            
                            if(!empty($latestProductsValue['selectedtasks'])){  
                                    foreach ($latestProductsValue['selectedtasks'] as $taskindex=>$taskKey){                             
                                        $value_usersids = get_post_meta( $taskKey, 'usersids' , false);
                                        if(!empty($value_usersids[0])){                                            
                                                array_push($value_usersids[0], $current_user);
                                                update_post_meta( $taskKey, 'usersids' , $value_usersids[0]);
                                                $loggin_data['usersids'][] = $value_usersids[0];                                           
                                        }else{                                           
                                                $newindex[]=$current_user;
                                                update_post_meta( $taskKey, 'usersids' , $newindex);
                                                $loggin_data['usersids'][] = $newindex;
                                            
                                        }

                                    }
                   
                            }
                  
           
                                $oldvalues = get_option( 'ContenteManager_Settings' );
                                $applicationmoderationstatus = $oldvalues['ContentManager']['applicationmoderationstatus'];
                                $exhibitorflowstatusKey = "exhibitorentryflowstatus";
                                $exhibitorflowstatus = get_option($exhibitorflowstatusKey);
             
                                if($exhibitorflowstatus['status'] =="checked"){
                                    
                                    update_user_option($current_user, 'user_entry_wizerd', "completeflow");
                                    
                                    
                                    if (!empty($current_user)) {
                                        
                                        if (!is_user_logged_in()) {
                                            
                                        
                                            if($applicationmoderationstatus != 'checked'){
                            
                                                custome_email_send_woocomerce($current_user, $useremail, 'welcome_email_template');
                                                $result = update_user_option($current_user, 'selfsignupstatus',  'Approved');
                                                $t = time();
                                                update_user_option($current_user, 'convo_welcomeemail_datetime', $t * 1000);
                                                $loggin_data['welcomeemailstatus'] = $t;
                                
                                    
                                            }else{
                                                
                                            
                                                $send_email_type = 'selfsignuprequest';
                                                $responce = selfsign_registration_emails_woocomerce($current_user,$send_email_type); 
                                                $t = time();
                                                update_user_option($current_user, 'convo_welcomeemail_datetime', $t * 1000);
                                                $loggin_data['welcomeemailstatus'] = $t;

                                            }
                                            
                                            
                                        }
                                    }
                                    
                                    
                                }
        
                            contentmanagerlogging_file_upload ($lastInsertId,serialize($loggin_data));
                            
                            $responce['paymentmethod'] = $payment_method;
                            $responce['paymentstatus'] = 'completed';
                            $responce['assignrole'] = $assign_role[0];
                         //contentmanagerlogging_file_upload ($lastInsertId,serialize($responce));

                        //--------------------------------Code By AD--------------------------------//
                        //Getting Settings for Booth//
                        $floor_Plan_Settings = 'floorPlanSettings';
                        $Booth_Queue_Settings='boothQueueSettings';
                        $get_booth_settings= get_option($Booth_Queue_Settings);
                        $get= get_option($floor_Plan_Settings);
                        // print_r($boothsettingsstatus);
                        // exit;
                        if ( $get['tableSort']=="checked" && $boothsettingsstatus == "boothavaiable") {
                            user_pirority_Updates($get_booth_settings); 
                        }
                    
                        
                        


                        
}

//-----------------------Function to update the User Piroirty--------------------------------//
//--------------------------------Code By AD--------------------------------//
function user_pirority_Updates($floorPlanSetting){
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/floorplan-manager.php';
    // echo "-------a---";
    
    if($floorPlanSetting["Open_users"] == "Auto and Email" || $floorPlanSetting["Open_users"] =="Auto and No Email" )
    {
        //echo "----------";exit;
        //Array for getting all the Users//
        $arr = array();
        $arrTurn = array();
        $args = array(
            'role__not_in' => 'Administrator',
        );
        $demo = new FloorPlanManager();
        $AllBoothsList = $demo->getAllbooths();
        $user_query =new WP_User_Query( $args  );
        $lisstofuser = $user_query->get_results();
        $blog_id = get_current_blog_id();
        $user_ID = get_current_user_id();
        //Get the loggindUsers Prirority Number//
        $loggedInUser = get_user_meta($user_ID,'wp_'.$blog_id.'_priorityNum'); 
        $Number=$loggedInUser[0];
        $Number++;
        
        
        
        
        foreach($lisstofuser as $key=> $a_value) {
            
            $user_Info=get_user_meta($a_value->ID,'nickname');
            $user_Priroty_Num=get_user_meta($a_value->ID,'wp_'.$blog_id.'_priorityNum');
         
            $user_option=get_user_meta($a_value->ID,'wp_'.$blog_id.'_myTurn');
            $user_Status=get_user_meta($a_value->ID,'wp_'.$blog_id.'_userBoothStatus');
            $user_Remove_status=get_user_meta($a_value->ID,'wp_'.$blog_id.'_RemoveFromQueue');
            
            if(empty($user_Remove_status[0]) && !empty($user_Priroty_Num[0]) && $user_Priroty_Num[0]!="-")
            {
            array_push($arr,(object)[
            'Email' => $user_Info[0],
            'PrirotyNumber' => $user_Priroty_Num[0],
            'Id' => $a_value->ID,
            'turn'=> $user_option[0],
            'Status'=>$user_Status[0],
            ]);
            $count++;
             }
        
          }
          usort($arr, function($a, $b) {
            if (($a->PrirotyNumber == '-' ||$a->PrirotyNumber == '') && ($b->PrirotyNumber != '-' ||$b->PrirotyNumber != '')) return 1;
            if (($b->PrirotyNumber == '-' ||$b->PrirotyNumber == '') && ($a->PrirotyNumber != '-' ||$a->PrirotyNumber != '')) return -1;
            if(!empty($a->PrirotyNumber) && !empty($b->PrirotyNumber))
                {
                  return $a->PrirotyNumber > $b->PrirotyNumber;
                }
            return 0;
          });
            // echo "<pre>";
            // print_r($arr);
            $array_Pr=array();
            foreach($arr as $key=> $a_value) {
                if($a_value->turn=='Checked')
                {
                    array_push($array_Pr,(object)[
                        'Id' => $a_value->Id,
                        'Status'=> $a_value->Status,
                        'PriorityNum'=>$a_value->PrirotyNumber
                        ]);
                        $TurnUsers=$a_value->PrirotyNumber;
                }
            }  
            // echo "----";
              $TurnUsers++;
            // echo "----";
            //  echo $TurnUsers;
           
            // $value = max($array_Pr);
            // $valueA = min($array_Pr);
            // echo "<pre>";
            //print_r($array_Pr);
            // print_r(count($array_Pr));
            // $Max=++$value;
            // $Min=$valueA;
             //echo "<pre>";
            // print_r($arr);
            $check=0;
            foreach($arr as $key=> $a_value) {

                if($a_value->PrirotyNumber >= $TurnUsers && $a_value->Status=='checked' )
                {
                      //echo "----------trtrtrtrtrt-----------";
                    foreach($array_Pr as $key=> $a_values) {
                        $flag="true";
                        // echo "<pre>";
                        // print_r($a_values);
                        foreach($AllBoothsList as $boothIndex=>$boothValue ){
                            if($boothValue['bootheOwnerID'] == $a_values->Id ){  
                                // echo $a_values->Id;
                                array_push($arrTurn,(object)[
                                    'Id' => $a_values->Id
                                ]);
                                $flag="false";
                            }           
                        }  
                        if($flag=="false")
                        {
                            $check++;
                        }
                    }
                    
                     
                    if($check==count($array_Pr))
                    {
                        $myturn='Checked';
                        $myStatus='';
                        $myStatusForBooth='checked';
                        update_user_option($a_value->Id,'myTurn',$myturn);
                        update_user_option($a_value->Id,'userBoothStatus',$myStatusForBooth);
                    
                        //update_user_option($user_ID,'userBoothStatus',$myStatus);
                        update_user_option($user_ID,'myTurn',$myStatus);
                        update_user_option($user_ID,'myTurnLoos',$myStatusForBooth);
                        foreach ($arrTurn as $key => $value) {
                            $myStatus='';
                            update_user_option($value->Id,'myTurn',$myStatus);
                            # code...
                        }
                        //--------------------------------Code By AD--------------------------------//
                        //--------------------------------To Send Email To next User--------------------------------//
    
                        if ($floorPlanSetting["Open_users"] == "Auto and Email") {
                            $email=get_user_option($a_value->Id,'nickname');
                            $email_type=$floorPlanSetting['Email_Selection'];
                            //print_r($email_type);
                            custome_email_send_AD($a_value->Id,$email,$email_type);
                        }
                        // break;
                    }
                    break;
                    
                }
           
            }
    }
      
    // }elseif ($floorPlanSetting["Open_users"] == "Nothing") {

    //      //Array for getting all the Users//
    //      $arr = array();
    //      $args = array(
    //          'role__not_in' => 'Administrator',
    //      );
    //      $user_query =new WP_User_Query( $args  );
    //      $lisstofuser = $user_query->get_results();
    //      $blog_id = get_current_blog_id();
    //      $user_ID = get_current_user_id();
    //      //Get the loggindUsers Prirority Number//
    //      $loggedInUser = get_user_meta($user_ID,'wp_'.$blog_id.'_priorityNum'); 
    //      $Number=$loggedInUser[0];
    //      $Number++;
    //      foreach($lisstofuser as $key=> $a_value) {
    //          // echo "<pre>";
    //          // print_r($a_value);
            
    //          $user_Info=get_user_meta($a_value->ID,'nickname');
    //          $user_Priroty_Num=get_user_meta($a_value->ID,'wp_'.$blog_id.'_priorityNum');
          
    //          $user_option=get_user_meta($a_value->ID,'ID','wp_'.$blog_id.'_myTurn');
    //          $user_Status=get_user_meta($a_value->ID,'wp_'.$blog_id.'_userBoothStatus');
    //          $user_Remove_status=get_user_meta($a_value->ID,'wp_'.$blog_id.'_RemoveFromQueue');
    //          // echo "<pre>";
    //          // print_r(  $user_option[0]);
    //          if(empty($user_Remove_status[0]) && !empty($user_Priroty_Num[0]) && $user_Priroty_Num[0]!="-")
    //          {
    //          array_push($arr,(object)[
    //          'Email' => $user_Info[0],
    //          'PrirotyNumber' => $user_Priroty_Num[0],
    //          'Id' => $a_value->ID,
    //          'Status'=>$user_Status[0],
    //          ]);
    //          $count++;
    //           }
         
    //        }
    //      usort($arr, function($a, $b) {
    //          if ($a->PrirotyNumber == '' && $b->PrirotyNumber != '') return 1;
    //          if ($b->PrirotyNumber == '' && $a->PrirotyNumber != '') return -1;
    //          if(!empty($a->PrirotyNumber) && !empty($b->PrirotyNumber))
    //              {
    //                return $a->PrirotyNumber > $b->PrirotyNumber;
    //               }
    //          return 0;
    //      });
    //          // echo "<pre>";
    //          // print_r($arr);
            
    //      foreach($arr as $key=> $a_value) {
             
    //          //   echo "----------trtrtrtrtrt-----------";
    //          if($a_value->PrirotyNumber>=$Number && $a_value->Status=='checked')
    //          {
    //              // echo "----------trtrtrtrtrt-----------";
    //              $myturn='Checked';
    //              $myStatus='';
    //              $myStatusForBooth='checked';
    //              update_user_option($a_value->Id,'myTurn',$myturn);
    
    //              $email=get_user_option($a_value->Id,'nickname');
    //             // update_user_option($user_ID,'userBoothStatus',$myStatus);
    //              update_user_option($user_ID,'myTurn',$myStatus);
    //              //--------------------------------Code By AD--------------------------------//
    //                break;
    //          }
    //      }
    // }
}
//--------------------------------Code By AD--------------------------------//

function multidimensional_search($parents, $searched) { 
  if (empty($searched) || empty($parents)) { 
    return false; 
  } 

  foreach ($parents as $key => $value) { 
    $exists = true; 
    foreach ($searched as $skey => $svalue) { 
      $exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue); 
    } 
    if($exists){ return $key; } 
  } 

  return false; 
} 


function registrtionlink_func( $atts ) {
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $selfsignstatus = $oldvalues['ContentManager']['selfsignstatus'];
     if($selfsignstatus == 'enable'){
         
         $button_text = '<a href="/registration/" class ="fusion-button fusion-button-default fusion-button-large fusion-button-round fusion-button-flat" >Registration</a>';
     }else{
         
         $button_text = "";
     }
    return $button_text;
}
add_shortcode( 'registrtionlink', 'registrtionlink_func' );

function myregisterrequest_new_user($username, $email){
    
    
      $username = sanitize_user($username);
      $user_id = register_new_user( $username, $email );
      return $user_id;
    
    
}

add_action( 'wp_footer','checkloginuserstatus_fun' );
function checkloginuserstatus_fun() {
    
     global $woocommerce;
     $site_url  = get_site_url();
     $oldvalues = get_option( 'ContenteManager_Settings' );
     $mainheader = $oldvalues['ContentManager']['mainheader'];
     $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
     $redirectname = $oldvalues['ContentManager']['redirectcatname'];
     
     $exhibitorflowstatusKey = "exhibitorentryflowstatus";
     $exhibitorflowstatus = get_option($exhibitorflowstatusKey);
     
     
     if(!empty($mainheader)){
                      //$headerbanner =  "url('".$mainheader."')";
                      //echo '<style> .fusion-header{background-image:'.$headerbanner.'}; </style>';
                }
     
     $redirectURL = "";
    if ( is_user_logged_in() ) {
     
     $current_user = wp_get_current_user();
     $user_id = get_current_user_id();
     $currentSiteID = get_current_blog_id();
     
     $current_user_blogs = get_blogs_of_user( $user_id );
     foreach($current_user_blogs as $BlogIndex=>$BlogData){
         
         
         $currentuserArray[]=$BlogData->userblog_id;
         
         
     }
     
     if (in_array($currentSiteID, $currentuserArray)) {
    
         
                if($redirectname == 'boothpurchase'){

                    $redirectURL = $site_url.'/floor-plan/';
                    $valuename = "booth";

                }else{
                                     //packages                           
                    $redirectURL = $site_url.'/product-category/add-ons/';
                    $valuename = "add-ons";
                }

               

               $current_user = wp_get_current_user();
               $roles = $current_user->roles;

               $newvalue = time();
               $custome_login_time_site = update_user_option( $current_user->ID, 'custom_login_time_as_site',$newvalue );



               if ( class_exists( 'WooCommerce' ) ) {	
                   if (is_user_logged_in()) {

                               if ($roles[0] == 'subscriber') {
                                   
                                   $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                  
                                   
                                   if(!empty($exhibitorflowstatus['status'])){
                                       
                                        $redirectURL = $site_url.'/entry-wizard/';
                                        if(strpos($actual_link, '/floor-plan-viewer/') == false && strpos($actual_link, '/floor-plan/') == false && strpos($actual_link, '/change-password-2/') == false && strpos($actual_link, '/my-account/orders/') == false && strpos($actual_link, '/my-sites/') == false && strpos($actual_link, '/logout/') == false && strpos($actual_link, '/product/') == false && strpos($actual_link, '/entry-wizard/') == false && strpos($actual_link,'/checkout/order-received/') == false && strpos($actual_link,'/checkout/') == false && strpos($actual_link,'/cart/') == false && strpos($actual_link,'/product-category/add-ons/') == false   && strpos($actual_link,'/product-category/packages/') == false && strpos($actual_link, '/registrations/') == false && strpos($actual_link,'/intro/') == false && strpos($actual_link,'/edit-sponsor-task/') == false){
                                            
                                            
                                         echo '<script type="text/javascript">window.location.replace("'.$redirectURL.'");</script>';

                                            
                                        }
                                        
                                       
                                   }else{
                                   
                                   
                                   
                                   if($actual_link != "https://" . $_SERVER['SERVER_NAME'].'/home/'){
                                       if (strpos($actual_link, 'task-list/') !== false || strpos($actual_link, 'home/') !== false  || strpos($actual_link, 'resources/') !== false || strpos($actual_link, 'registration-codes/') !== false) {
                                            
                                        //session_start();
                                        //$_SESSION['userID'] = $current_user;
                                        //$_SESSION['useremail'] = $current_user->username;
                                        //$woocommerce->cart->empty_cart(); 
                                        echo '<script src="https://' . $_SERVER['SERVER_NAME'].'/wp-content/plugins/EGPL/cmtemplate/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script><script type="text/javascript">swal({title: "Welcome!", type: "success", html:true,showConfirmButton:false,text: "<p>This will serve as your portal for managing all of your pre-show logistics. Click \'Next\' below to view your exhibit/sponsor options.</p><p style=\'margin-top:18px\'><a href='.$redirectURL.' class=\'confirm btn btn-lg btn btn-info mr-2\'>Next</a></p>"});</script>';

                                       }
                                    }
                                   }
                               }
                   } 
               }
    }else{
        
       
        wp_redirect("https://" . $_SERVER['SERVER_NAME'].'/home/'); 
      
        die();
        
        
    }
    }
}


// ShortCode For Display View Floor Plan Button

function viewfloorplanbutton( $atts ){
	  
      
      return '<button id="floorplanpopup" onclick="openpopup()" class="button fusion-button fusion-button-default button-square fusion-button-xlarge button-xlarge button-flat  fusion-mobile-button continue-center">View Floor Plan</button>';
      
}
add_shortcode( 'viewfloorplanbutton', 'viewfloorplanbutton' );
add_filter('manage_expo_genie_log_posts_columns', 'bs_event_table_head');
function bs_event_table_head( $defaults ) {
    
    
    
    
    
    $defaults['action-type-name']  = 'Action Type';
    $defaults['currentuseremail']    = 'User Email';
    $defaults['ip-address']   = 'IP Address';
    $defaults['browser-agent'] = 'Browser Agent';
    $defaults['request-data-and-time'] = 'Date & Time';
    return $defaults;
}

add_action( 'manage_expo_genie_log_posts_custom_column', 'bs_event_table_content', 10, 2 );

function bs_event_table_content( $column_name, $post_id ) {
    if ($column_name == 'actiontype') {
    $event_date = get_post_meta( $post_id, 'actiontype', true );
      echo   $event_date ;
    }
    if ($column_name == 'preactiondata') {
    $event_date = print_r(get_post_meta( $post_id, 'preactiondata', true ));
      echo   $event_date ;
    }
    if ($column_name == 'email') {
    $event_date = get_post_meta( $post_id, 'email', true );
      echo   $event_date ;
    }
    if ($column_name == 'ip') {
    $event_date = get_post_meta( $post_id, 'ip', true );
      echo   $event_date ;
    }
    if ($column_name == 'browseragent') {
    $event_date = get_post_meta( $post_id, 'browseragent', true );
      echo   $event_date ;
    }
    if ($column_name == 'result') {
    $event_date = print_r(get_post_meta( $post_id, 'result', true ));
      echo   $event_date ;
    }
    

}


//function myplugin_plugin_path() {
//
//  // gets the absolute path to this plugin directory
//
//  return untrailingslashit( plugin_dir_path( __FILE__ ) );
//}
//add_filter( 'woocommerce_locate_template', 'myplugin_woocommerce_locate_template', 10,10);
//
//
//
//function myplugin_woocommerce_locate_template( $template, $template_name, $template_path ) {
//    
//    
//  global $woocommerce;
//  $_template = $template;
//
//  if ( ! $template_path ) $template_path = $woocommerce->template_url;
//
//  $plugin_path  = myplugin_plugin_path() . '/woocommerce/';
//
//  // Look within passed path within the theme - this is priority
//  $template = locate_template(
//
//    array(
//      $template_path . $template_name,
//      $template_name
//    )
//  );
//
//  // Modification: Get the template from this plugin, if it exists
//  if ( ! $template && file_exists( $plugin_path . $template_name ) )
//    $template = $plugin_path . $template_name;
//
//  // Use default template
//  if ( ! $template )
//    $template = $_template;
//
//  // Return what we found
//  
//  //echo $template;
//  
//  return $template;
//}


add_filter('woocommerce_cart_item_permalink','__return_false');
add_filter( 'woocommerce_order_item_permalink', '__return_false' );



add_filter('woocommerce_get_availability_text', function($text, $product) {
    if (!$product->is_in_stock()) {
        $text = 'No Longer Available';
    }
 
    return $text;
}, 10, 2);

add_filter( 'woocommerce_my_account_my_orders_query', 'custom_my_account_orders_query', 20, 1 );
function custom_my_account_orders_query( $args ) {
    $args['limit'] = -1;

    return $args;
}


add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );
function wc_empty_cart_redirect_url() {
	$site_url  = get_site_url();
	return $site_url.'/product-category/add-ons/';
}
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_cart', 10, 3 );
function woocommerce_rename_coupon_field_on_cart( $translated_text, $text, $text_domain ) {
	// bail if not modifying frontend woocommerce text
	
	if ( 'Apply coupon' === $text ) {
		$translated_text = 'Apply Discount';
	}


	return $translated_text;
}


//add_filter( 'woocommerce_coupon_error', 'rename_coupon_label', 10, 3 );
//add_filter('woocommerce_coupon_message', 'rename_coupon_label', 10, 3);
//add_filter('woocommerce_checkout_coupon_message', 'rename_coupon_label', 10, 3);

//function rename_coupon_label( $translated_text, $text, $text_domain ) {
	
	//$text = str_replace("Coupon","Discount",$translated_text);
  //      return  $text;
        
        
        
//}


function getcustomefieldKeyValue($fieldKey,$getKeyValue){

	require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
    $GetAllcustomefields = new EGPLCustomeFunctions();
    $additional_fields = $GetAllcustomefields->getAllcustomefields();
    $blog_id = get_current_blog_id();
    $site_prefix = 'wp_'.$blog_id.'_';
    
    $fieldKey = str_replace($site_prefix,"",$fieldKey);
	foreach ($additional_fields as $key=>$value){ 
	
	
		if($fieldKey == $value['fielduniquekey']){
		
			$FieldType = $value[$getKeyValue];
		
		
		}
 
 
 
	}

	return $FieldType;


}

///-----------------Expogenie API Endpoints ---------------------///

add_action('rest_api_init', function() {
	register_rest_route('w1/v1', 'createuser', [
		'methods' => 'POST',
		'callback' => 'createuser',
	]);
        
        
        register_rest_route('w1/v1', 'getuserinfo', [
		'methods' => 'POST',
		'callback' => 'getuserinfo',
	]);
        
        
        
        register_rest_route('w1/v1', 'updateuser', [
		'methods' => 'POST',
		'callback' => 'updateuser',
	]);
         register_rest_route('w1/v1', 'updatetasksdata', [
		'methods' => 'POST',
		'callback' => 'updatetasksdata',
	]);
        
        register_rest_route('w1/v1', 'updatetask', [
		'methods' => 'POST',
		'callback' => 'updatetask',
	]);
        
        register_rest_route('w1/v1', 'getuserfields', [
		'methods' => 'GET',
		'callback' => 'getuserfields',
	]);
        register_rest_route('w1/v1', 'getuserfieldsdata', [
		'methods' => 'GET',
		'callback' => 'getuserfieldsdata',
	]);
        
        register_rest_route('w1/v1', 'getorders', [
		'methods' => 'GET',
		'callback' => 'getorders',
	]);
	
});
function getuserfields(){
    
    
    try {
    
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $additional_fields = $GetAllcustomefields->getAllcustomefields();
        $additional_tasks_list = $GetAllcustomefields->getAllcustometasks();
        usort($additional_fields, 'sortByOrder');
    
        $index_count=0;
        foreach ($additional_fields as $key=>$value){
            
           
            if($value['fieldType']!="html" && $value['SystemfieldInternal']!="checked"){
                
                
                $requiredStatus = $value['fieldrequriedstatus'];
                if($requiredStatus == "checked"){
                    
                   $columns_list_attitional[$index_count]['required']  = true; 
                }
                $columns_list_attitional[$index_count]['key']  = $site_prefix.$value['fielduniquekey'];
                $columns_list_attitional[$index_count]['type']= $value['fieldType'];
                $columns_list_attitional[$index_count]['label']= $value['fieldName'];
               
                
                $index_count++;
            }
            
        }
     
     
     
    
    
    
     
     echo json_encode($columns_list_attitional);
     die();
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}

function getuserfieldsdata(){
    
    
    try {
    
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $additional_fields = $GetAllcustomefields->getAllcustomefields();
        $additional_tasks_list = $GetAllcustomefields->getAllcustometasks();
        usort($additional_fields, 'sortByOrder');
    
        $index_count=0;
        foreach ($additional_fields as $key=>$value){
            
           
            if($value['fieldType']!="html" && $value['SystemfieldInternal']!="checked"){
                
                
                $requiredStatus = $value['fieldrequriedstatus'];
                if($requiredStatus == "checked"){
                    
                   $columns_list_attitional['users'][$index_count]['required']  = true; 
                }else{
                    
                   $columns_list_attitional['users'][$index_count]['required']  = false;  
                }
                $columns_list_attitional['users'][$index_count]['name']  = $site_prefix.$value['fielduniquekey'];
                $columns_list_attitional['users'][$index_count]['type']= 'text';//$value['fieldType'];
                $columns_list_attitional['users'][$index_count]['label']= $value['fieldName'];
                
               
                
                $index_count++;
            }
            
        }
     
        foreach ($additional_tasks_list as $key_task=>$value_task){
            
           
            
                
                
                $requiredStatus = $value_task['required'];
                if($requiredStatus == "checked"){
                    
                   $columns_list_attitional['users'][$index_count]['required']  = true; 
                }else{
                    
                   $columns_list_attitional['users'][$index_count]['required']  = false;  
                }
                $columns_list_attitional['users'][$index_count]['name']  = $value_task['key'];
                $columns_list_attitional['users'][$index_count]['type']= 'text';//$value_task['type'];
                $columns_list_attitional['users'][$index_count]['label']= $value_task['label'];
               
                
                $index_count++;
            
            
        }   
    
    
     echo json_encode($columns_list_attitional);
     die();
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}

function getorders(){
    
     try {

        global $wpdb;
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);
        $lastInsertId = contentmanagerlogging('Get Order Report Date', "Admin Action", $orderreportdata, $user_ID, $user_info->user_email, "pre_action_data");
        
     
        
        $query = new WP_Query( array( 'post_type' => 'shop_order' ,'post_status'=>array('wc-pending-deposit','wc-scheduled-payment','wc-partial-payment','wc-failed','wc-refunded','wc-processing','wc-pending','wc-cancelled','wc-completed','wc-on-hold','wc-pending'),'posts_per_page' => -1) );
        $all_posts = $query->posts;
        
        $columns_headers = [];
        $columns_rows_data = [];

        $columns_list_order_report[0]['title'] = 'Action';
        $columns_list_order_report[0]['type'] = 'string';
        $columns_list_order_report[0]['key'] = 'action';
        
        $columns_list_order_report[1]['title'] = 'Order Date';
        $columns_list_order_report[1]['type'] = 'date';
        $columns_list_order_report[1]['key'] = 'post_date';
     
        $columns_list_order_report_postmeta[2]['title'] = 'Company Name';
        $columns_list_order_report_postmeta[2]['type'] = 'string';
        $columns_list_order_report_postmeta[2]['key'] = '_billing_company';
        
        $columns_list_order_report[3]['title'] = 'Order Status';
        $columns_list_order_report[3]['type'] = 'string';
        $columns_list_order_report[3]['key'] = 'post_status';
        
        
        $columns_list_order_report_postmeta[4]['title'] = 'Amount';
        $columns_list_order_report_postmeta[4]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[4]['key'] = '_order_total';
        
        
        
          
        $columns_list_order_report_postmeta[5]['title'] = 'Product Details';
        $columns_list_order_report_postmeta[5]['type'] = 'string';
        $columns_list_order_report_postmeta[5]['key'] = 'Products';
        
        
        $columns_list_order_report_postmeta[6]['title'] = 'Payment Method';
        $columns_list_order_report_postmeta[6]['type'] = 'string';
        $columns_list_order_report_postmeta[6]['key'] = '_payment_method_title';
        
        
        $columns_list_order_report_postmeta[7]['title'] = 'Payment Date';
        $columns_list_order_report_postmeta[7]['type'] = 'date';
        $columns_list_order_report_postmeta[7]['key'] = '_paid_date';
        
        $columns_list_order_report_postmeta[8]['title'] = 'Order Note';
        $columns_list_order_report_postmeta[8]['type'] = 'string';
        $columns_list_order_report_postmeta[8]['key'] = '_order_custome_note';
        
        
        $columns_list_order_report_postmeta[9]['title'] = 'Age';
        $columns_list_order_report_postmeta[9]['type'] = 'string';
        $columns_list_order_report_postmeta[9]['key'] = '_product_age_calculate';
        
        $columns_list_order_report_postmeta[10]['title'] = 'First Name';
        $columns_list_order_report_postmeta[10]['type'] = 'string';
        $columns_list_order_report_postmeta[10]['key'] = '_billing_first_name';
        
        $columns_list_order_report_postmeta[11]['title'] = 'Last Name';
        $columns_list_order_report_postmeta[11]['type'] = 'string';
        $columns_list_order_report_postmeta[11]['key'] = '_billing_last_name';
        
        $columns_list_order_report_postmeta[12]['title'] = 'Email';
        $columns_list_order_report_postmeta[12]['type'] = 'string';
        $columns_list_order_report_postmeta[12]['key'] = '_billing_email';
        
        
        $columns_list_order_report_postmeta[13]['title'] = 'Phone Number';
        $columns_list_order_report_postmeta[13]['type'] = 'string';
        $columns_list_order_report_postmeta[13]['key'] = '_billing_phone';
        
        


        $columns_list_order_report_postmeta[14]['title'] = 'Order Currency';
        $columns_list_order_report_postmeta[14]['type'] = 'string';
        $columns_list_order_report_postmeta[14]['key'] = '_order_currency';

        $columns_list_order_report_postmeta[15]['title'] = 'User IP Address';
        $columns_list_order_report_postmeta[15]['type'] = 'string';
        $columns_list_order_report_postmeta[15]['key'] = '_customer_ip_address';


        $columns_list_order_report_postmeta[16]['title'] = 'Address Line 1';
        $columns_list_order_report_postmeta[16]['key'] = '_billing_address_1';
        $columns_list_order_report_postmeta[16]['type'] = 'string';

        $columns_list_order_report_postmeta[17]['title'] = 'Address Line 2';
        $columns_list_order_report_postmeta[17]['key'] = '_billing_address_2';
        $columns_list_order_report_postmeta[17]['type'] = 'string';

        $columns_list_order_report_postmeta[18]['title'] = 'Zipcode';
        $columns_list_order_report_postmeta[18]['key'] = '_billing_postcode';
        $columns_list_order_report_postmeta[18]['type'] = 'string';

        $columns_list_order_report_postmeta[19]['title'] = 'City';
        $columns_list_order_report_postmeta[19]['key'] = '_billing_city';
        $columns_list_order_report_postmeta[19]['type'] = 'string';

        $columns_list_order_report_postmeta[20]['title'] = 'State';
        $columns_list_order_report_postmeta[20]['key'] = '_billing_state';
        $columns_list_order_report_postmeta[20]['type'] = 'string';

        $columns_list_order_report_postmeta[21]['title'] = 'Country';
        $columns_list_order_report_postmeta[21]['key'] = '_billing_country';
        $columns_list_order_report_postmeta[21]['type'] = 'string';

        $columns_list_order_report_postmeta[22]['title'] = 'Stripe Fee';
        $columns_list_order_report_postmeta[22]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[22]['key'] = '_stripe_fee';

        $columns_list_order_report_postmeta[23]['title'] = 'Net Revenue From Stripe';
        $columns_list_order_report_postmeta[23]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[23]['key'] = '_stripe_net';

        $columns_list_order_report_postmeta[24]['title'] = 'Order ID';
        $columns_list_order_report_postmeta[24]['type'] = 'string';
        $columns_list_order_report_postmeta[24]['key'] = 'ID';
        
        
        $columns_list_order_report_postmeta[25]['title'] = 'Initial Order ID';
        $columns_list_order_report_postmeta[25]['type'] = 'string';
        $columns_list_order_report_postmeta[25]['key'] = '_initial_payment_order_id';
        

        $columns_list_order_report_postmeta[26]['title'] = 'Transaction ID';
        $columns_list_order_report_postmeta[26]['type'] = 'string';
        $columns_list_order_report_postmeta[26]['key'] = '_transaction_id';

        

        $columns_list_order_report_postmeta[27]['title'] = 'Account Holder Email';
        $columns_list_order_report_postmeta[27]['type'] = 'string';
        $columns_list_order_report_postmeta[27]['key'] = 'Account Holder Email';
        
          $columns_list_order_report_postmeta[28]['title'] = 'Order Discount';
        $columns_list_order_report_postmeta[28]['type'] = 'num-fmt';
        $columns_list_order_report_postmeta[28]['key'] = '_cart_discount';

        
       

        $custom_field = "";

        foreach ($columns_list_order_report as $col_keys => $col_keys_title) {


            $colums_array_data['title'] = $columns_list_order_report[$col_keys]['title'];
            $colums_array_data['type'] = $columns_list_order_report[$col_keys]['type'];
            $colums_array_data['data'] = $columns_list_order_report[$col_keys]['title'];
            array_push($columns_headers, $colums_array_data);
        }
        foreach ($columns_list_order_report_postmeta as $col_keys => $col_keys_title) {


            $colums_array_data['title'] = $columns_list_order_report_postmeta[$col_keys]['title'];
            $colums_array_data['data'] = $columns_list_order_report_postmeta[$col_keys]['title'];
            $colums_array_data['type'] = $columns_list_order_report_postmeta[$col_keys]['type'];

            array_push($columns_headers, $colums_array_data);
        }
        foreach ($all_posts as $single_post) {

            $header_array = get_object_vars($single_post);
            $post_meta = get_post_meta($header_array['ID']);
            $order = wc_get_order( $header_array['ID'] );
         // if($header_array['ID'] == 5236){   
           foreach ( $order->get_items() as $item_id => $item ) {
                
                $custom_field = wc_get_order_item_meta( $item_id, '_remaining_balance_order_id', true );
                
                
            }
            
            
         // }
            
            
            $column_row;
            ksort($post_meta);
            foreach ($columns_list_order_report as $col_keys_index => $col_keys_title) {
                
                if($columns_list_order_report[$col_keys_index]['key'] == 'action'){
                    
                    $orderID = $header_array['ID'];
                    
                    if($header_array['post_status'] !="wc-completed" && $header_array['post_status'] !="wc-partial-payment"){
                        
                        
                        if($header_array['post_status'] == "wc-cancelled"){
                        
                            $column_row[$columns_list_order_report[$col_keys_index]['title']] = '<div style="width: 110px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><a onclick="markorderstatuscompleted('.$orderID.')"  data-toggle="tooltip" title="Complete Order"><i  class="hi-icon fusion-li-icon fa fa-check-circle" ></i></a><i style="color:#e5e6e8" title="Cancel Order" class="hi-icon fusion-li-icon fa fa-times-circle" ></i><a onclick="updatecurrentordernotes('.$orderID.')"  name="order-notes" data-toggle="tooltip"  title="Add Note" ><i class="hi-icon fusion-li-icon fa fa-clipboard" ></i></a></div>';
                    
                        }else{
                        
                            $column_row[$columns_list_order_report[$col_keys_index]['title']] = '<div style="width: 110px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><a onclick="markorderstatuscompleted('.$orderID.')"  data-toggle="tooltip" title="Complete Order"><i  class="hi-icon fusion-li-icon fa fa-check-circle" ></i></a><a onclick="markorderstatuscancel('.$orderID.')"  name="cancel-order" data-toggle="tooltip"  title="Cancel Order" ><i class="hi-icon fusion-li-icon fa fa-times-circle" ></i></a><a onclick="updatecurrentordernotes('.$orderID.')"  name="order-notes" data-toggle="tooltip"  title="Add Note" ><i class="hi-icon fusion-li-icon fa fa-clipboard" ></i></a></div>';
                        
                        
                        }
                        
                    }else{
                        
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] = '<div style="width: 110px !important;"class = "hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i  style="color:#e5e6e8" title="Complete Order" class="hi-icon fusion-li-icon fa fa-check-circle"></i><a onclick="markorderstatuscancel('.$orderID.')"  name="cancel-order" data-toggle="tooltip"  title="Cancel Order" ><i class="hi-icon fusion-li-icon fa fa-times-circle" ></i></a><a onclick="updatecurrentordernotes('.$orderID.')"  name="order-notes" data-toggle="tooltip"  title="Add Note" ><i class="hi-icon fusion-li-icon fa fa-clipboard" ></i></a></div>';
                    
                    }
                   
                }else if ($columns_list_order_report[$col_keys_index]['key'] == 'post_date') {

                    if (!empty($header_array[$columns_list_order_report[$col_keys_index]['key']])) {
                        $time = strtotime($header_array[$columns_list_order_report[$col_keys_index]['key']]);
                        $newformat = $time * 1000; // date('d-M-Y  H:i:s', $time);
                    } else {
                        $newformat = '';
                    }
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $newformat;
                    // echo '<pre>';
                    //print_r($column_row);exit;
                }else if ($columns_list_order_report[$col_keys_index]['key'] == 'post_status') {
                        
                    
                    if(esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']])) == 'Partially Paid'){
                        
                        
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] =  'Initial Deposit Completed';//esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']]));
                 
                        
                    }else if(esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']])) == 'Pending Deposit Payment'){
                        
                        
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] =  'Balance Due';//esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']]));
                 
                        
                    }else{
                        
                        $column_row[$columns_list_order_report[$col_keys_index]['title']] =  esc_html( wc_get_order_status_name( $header_array[$columns_list_order_report[$col_keys_index]['key']]));
                 
                        
                    }
                    
                    
                   
                    
                }else if ($columns_list_order_report[$col_keys_index]['key'] == '_initial_payment_order_id') {
                        
                     
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $custom_field;
               
                    
                }else if ($columns_list_order_report[$col_keys_index]['key'] == 'ID') {
                        
                     
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = (int)$header_array[$columns_list_order_report[$col_keys_index]['key']];
               
                    
                }else {

                     
                    $column_row[$columns_list_order_report[$col_keys_index]['title']] = $header_array[$columns_list_order_report[$col_keys_index]['key']];
                
                    
                }
            }
            
            
            
            
            foreach ($columns_list_order_report_postmeta as $col_keys_index => $col_keys_title) {
                
                 if($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_product_age_calculate'){
                    
                    $now = time(); // or your date as well
                    $your_date = strtotime($header_array['post_date']);
                    $datediff = $now - $your_date;
                    if(esc_html( wc_get_order_status_name( $header_array['post_status'])) == "Pending Deposit Payment"){
                        
                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($datediff / (60 * 60 * 24));
                    
                        
                    }else{
                        
                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = "";
                    
                        
                    }
                    
                }else if($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_initial_payment_order_id'){
                    
                    $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $custom_field;
                    
                }else if($columns_list_order_report_postmeta[$col_keys_index]['key'] == 'ID'){
                    
                    $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = (int)$header_array[$columns_list_order_report_postmeta[$col_keys_index]['key']];
                    
                }else if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_paid_date') {

                    if (!empty($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0])) {
                        $time = strtotime($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                        $newformat = $time * 1000; //date('d-M-Y H:i:s', $time);
                    } else {
                        $newformat = '';
                    }
                    $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $newformat;
                } else if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Products' || $columns_list_order_report_postmeta[$col_keys_index]['key'] == 'Account Holder Email') {
                    
                }else if ($columns_list_order_report_postmeta[$col_keys_index]['key'] == '_order_total' ) {
                    
                     $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                     $totalAmountOrder = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                     
                } else {
                    if ($columns_list_order_report_postmeta[$col_keys_index]['type'] == 'num' || $columns_list_order_report_postmeta[$col_keys_index]['type'] == 'num-fmt') {
                        
                        if($columns_list_order_report_postmeta[$col_keys_index]['title'] == 'Stripe Fee'){
                            
                           if (array_key_exists($columns_list_order_report_postmeta[$col_keys_index]['key'],$post_meta)){
                               
                               $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                       
                               
                           }else{
                               
                             $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta['Stripe Fee'][0]);
                        
                               
                           }
                            
                            
                            
                        }else if($columns_list_order_report_postmeta[$col_keys_index]['title'] == 'Net Revenue From Stripe'){
                            
                            if (array_key_exists($columns_list_order_report_postmeta[$col_keys_index]['key'],$post_meta)){
                               
                               $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                       
                               
                           }else{
                               
                             $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta['Net Revenue From Stripe'][0]);
                        
                               
                           }
                            
                            
                        }else{
                            
                          $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = round($post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0]);
                      
                            
                        }
                        
                        
                        
                    } else {
                        $column_row[$columns_list_order_report_postmeta[$col_keys_index]['title']] = $post_meta[$columns_list_order_report_postmeta[$col_keys_index]['key']][0];
                    }
                }
            }



            $userdata = get_userdata($post_meta['_customer_user'][0]);
            $accountholder_email = $userdata->user_email;
            $blog_id = get_current_blog_id();
            
            $get_items_sql = "SELECT items.order_item_id,items.order_item_name,Pid.meta_value as Pid,Qty.meta_value as Qty FROM wp_".$blog_id."_woocommerce_order_items AS items LEFT JOIN wp_".$blog_id."_woocommerce_order_itemmeta AS Pid ON(items.order_item_id = Pid.order_item_id)LEFT JOIN wp_".$blog_id."_woocommerce_order_itemmeta AS Qty ON(items.order_item_id = Qty.order_item_id) WHERE items.order_id = " . $header_array['ID'] . " AND Qty.meta_key IN ( '_qty' )AND Pid.meta_key IN ( '_product_id' ) ORDER BY items.order_item_id";
            $products = $wpdb->get_results($get_items_sql);
            $order_productsnames = "";
            foreach ($products as $single_product => $productname) {
                
                
                
                $order_productsnames.= $productname->order_item_name.' (x'.$productname->Qty.')<br>';
            }
            $column_row['Product Details'] = '<a style="cursor: pointer;" onclick="getOrderproductdetail('.$header_array['ID'].')">Product Details</a>';//$order_productsnames;
            $column_row['Account Holder Email'] = $accountholder_email;
            array_push($columns_rows_data, $column_row);
        }

        $orderreport_all_col_rows_data['columns'] = $columns_headers;
        $orderreport_all_col_rows_data['data'] = $columns_rows_data;

        contentmanagerlogging_file_upload($lastInsertId, serialize($orderreport_all_col_rows_data));

        echo json_encode($columns_rows_data) . '//' . json_encode($columns_headers);
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}

function createuser(){
    
    try {
    
      
        
    $newContactUserData =  json_decode(file_get_contents('php://input')) ;
    $lastInsertId = contentmanagerlogging('Zapier Action Create User', "Admin Action", "", "", "", $newContactUserData);
    
    if(!empty($newContactUserData)){
        $resultRegistratedUser = CreateNewUser($newContactUserData);
    }else{
        
        $resultRegistratedUser["error"] = "Something went going wrong. Please Connect with App administrative.";
        $resultRegistratedUser = json_encode($resultRegistratedUser);
    }
    return $resultRegistratedUser;
    
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}




function getuserinfo(){
    
    try {
    
      
        
    $newContactUserData =  json_decode(file_get_contents('php://input')) ;
    $lastInsertId = contentmanagerlogging('Zapier Get User Information', "Admin Action", "", "", "", $newContactUserData);
    
    if(!empty($newContactUserData)){
        
        
        $useroppnumber = $newContactUserData->useropp;
        global $wpdb;
         $site_prefix = $wpdb->get_blog_prefix();
         
        
         
        
          
        $args = array(
	'order'          => 'ASC',
	'orderby'        => 'display_name',
	'meta_query'     => array(
		'relation' => 'AND',
		array(
			'key'     => $site_prefix.'external_reference_id_zapier',
			'value'   => $useroppnumber,
			'compare' => '=',
		),
		
	)
        );


        
        $user_query = new WP_User_Query( $args );
        $authors = $user_query->get_results();
        if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
        $get_all_roles = get_option($get_all_roles_array);
        
        if(!empty($authors)){
            
            
            
            $user_id = $authors[0]->ID;
            $user_data = get_userdata($user_id);
            $all_meta_for_user = get_user_meta($user_id);
                
                
            $resultRegistratedUser['email'] = $user_data->user_email;
            $resultRegistratedUser['First Name'] = $all_meta_for_user[$site_prefix.'first_name'][0];;
            $resultRegistratedUser['Last Name'] = $all_meta_for_user[$site_prefix.'last_name'][0];;
            $resultRegistratedUser['Role'] = $get_all_roles[$user_data->roles[0]]['name'];
            $resultRegistratedUser['message'] = "success";
            
            
            //$resultRegistratedUser = json_encode($resultRegistratedUser);
            
        }else{
            
            
            $resultRegistratedUser["message"] = "There is no user exist with this opp id.";
            //$resultRegistratedUser = json_encode($resultRegistratedUser);
            
        }
        
        
        
        
    }else{
        
        $resultRegistratedUser["message"] = "Something went going wrong. Please Connect with App administrative.";
        $resultRegistratedUser = json_encode($resultRegistratedUser);
    }
    
    return $resultRegistratedUser;
    
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}



function updateuser(){
    
    try {
    
      
        
    $updateContactUserData =  json_decode(file_get_contents('php://input')) ;
    $lastInsertId = contentmanagerlogging('Zapier Action Update User', "Admin Action", "", "", "", $updateContactUserData);
    
    if(!empty($updateContactUserData)){
        
        $resultRegistratedUser = UpdateNewUser($updateContactUserData);
        
    }else{
        
        $resultRegistratedUser["error"] = "Something went going wrong. Please Connect with App administrative.";
        $resultRegistratedUser = json_encode($resultRegistratedUser);
    }
    return $resultRegistratedUser;
    
    }catch (Exception $e) {

      

        return $e;
    }
    
    
    
    
}


function UpdateNewUser($updateContactUserData){
    
     try{
         
         global $wpdb;
         $site_prefix = $wpdb->get_blog_prefix();
         
        
         
        $external_reference_id_zapier = $updateContactUserData->external_reference_id_zapier;
          
        $args = array(
	'order'          => 'ASC',
	'orderby'        => 'display_name',
	'meta_query'     => array(
		'relation' => 'AND',
		array(
			'key'     => $site_prefix.'external_reference_id_zapier',
			'value'   => $external_reference_id_zapier,
			'compare' => '=',
		),
		
	)
        );


        
        $user_query = new WP_User_Query( $args );
        $authors = $user_query->get_results();
         
        
        if(!empty($authors)){
            
            
            $user_id = $authors[0]->ID;
            $user_data = get_userdata($authors[0]->ID);
            
             $role = $updateContactUserData->Role;
            
            
    
                if (is_multisite()) {
                    $blog_id = get_current_blog_id();
                    $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                }else{
                    $get_all_roles_array = 'wp_user_roles';
                }
                $get_all_roles = get_option($get_all_roles_array);
                foreach ($get_all_roles as $key => $item) {
                     if($role == $item['name']){
                         $role = $key;

                     }
                }
          
            if($updateContactUserData->Semail != $user_data->user_email){
            
                
                $lastInsertId = contentmanagerlogging('Update user email Zapier Request',"Admin Action",serialize($updateContactUserData),''.$user_id,$user_data->user_email,"pre_action_data");
                $newemail = $updateContactUserData->Semail;
                
               
                $email_status = isValidEmail($newemail);
                if($email_status){
                    if( email_exists( $newemail )) {

                        $responce['emai-update-message'] = 'A user with that email address already exists Please try another email address.';
                        


                    }else{

                        //$result_update = wp_update_user( array ( 'ID' => $userid, 'user_login' => $newemail,'user_email'=>$newemail) ) ;
                       global $wpdb;
                        $tablename = $wpdb->prefix . "users";
                        $sql = $wpdb->prepare( "UPDATE `wp_users` SET `display_name`='".$newemail."' , `user_login`='".$newemail."',`user_email`='".$newemail."' WHERE `ID`=".$user_id."", $tablename );
                        $result_update = $wpdb->query($sql);
                        //echo '<pre>';
                        //print_r($result_update);exit;
                        update_user_option($user_id, 'nickname', $newemail);
                        //echo $result_update;
                        //echo  "UPDATE ".$tablename." SET user_login=".$newemail.",user_email=".$newemail." WHERE ID=".$userid."";
                        
                       // $t = time();
                       // update_user_option($user_id, 'profile_updated', $t*1000);

                    }

                }else{

                    $responce['emai-update-message'] = 'Email address is invalid. Please try again and enter a valid email.';
                   
                }

                contentmanagerlogging_file_upload ($lastInsertId,serialize($responce));
                
                
            }
            //$t = time();
            //update_user_option($user_id, 'profile_updated', $t*1000);
            updateregistredUserMeta($user_id,$updateContactUserData,$role);
            
            $responce['id'] = time()*1000;
            $responce['message'] = "Requested data has been updated successfully.";
            
        }else{
            
            
            $responce['message'] = "There is no record exist in expo-genie for this request.Please Connect with App administrative. Reference id".$external_reference_id_zapier;
            $responce['id'] = time()*1000;
            
            
        }
         
        echo json_encode($responce);
        die();
        
     }  catch (Exception $e){
        
        return $e;
    }
    
    
}


function CreateNewUser($newContactUserData){
    
    try{
    
     global $wpdb;
     $site_prefix = $wpdb->get_blog_prefix();
         
    $blogid = get_current_blog_id() ;
    $user_id = username_exists($newContactUserData->username);
    $role = $newContactUserData->Role;
    $email = $newContactUserData->username;
    $external_reference_id_zapier = $newContactUserData->external_reference_id_zapier;
          
        $args = array(
	'order'          => 'ASC',
	'orderby'        => 'display_name',
	'meta_query'     => array(
		'relation' => 'AND',
		array(
			'key'     => $site_prefix.'external_reference_id_zapier',
			'value'   => $external_reference_id_zapier,
			'compare' => '=',
		),
		
	)
        );


        
     $user_query = new WP_User_Query( $args );
     $authors = $user_query->get_results();
    
    
    
    
    $t=time();
    $roleKeyValue = "";
            if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
            $get_all_roles = get_option($get_all_roles_array);
            foreach ($get_all_roles as $key => $item) {
                 if($role == $item['name']){
                     $role = $key;
                     $roleKeyValue = $key;
                     
                 }
            }
    
            
    if(empty($role) || empty($roleKeyValue)){
        
        $message['message'] = 'Failed to add user due to User Level not vaild.';
        
    }else{
    
    if (!$user_id and email_exists($newContactUserData->username) == false && empty($authors)) {
        
       $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
       $user_id = myregisterrequest_new_user($newContactUserData->username, $newContactUserData->Semail) ;//register_new_user( $username, $email );//wp_create_user($username, $random_password, $email);
       if ( ! is_wp_error( $user_id ) ) {
       
        $result=$user_id;
        $loggin_data['created_id']=$result;

        $useremail='';
        
        updateregistredUserMeta($user_id,$newContactUserData,$role);
        
        custome_email_send($user_id,$newContactUserData->Semail,"welcome_email_template");
        //update_user_option($user_id, 'profile_updated', $t*1000);
        
        if (add_user_to_blog($blogid, $user_id, $role)) {

                 add_user_to_blog(1, $user_id, $role);
                 $message['user_id'] = $user_id;
                 $message['msg'] = 'User created';
                 $message['userrole'] = $newContactUserData->Role;


                 $message['message'] =  'User added to this blog.';

             } else {

                 $message['message'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
             }
        
    }else{
        
                  $userregister_responce = (array)$user_id;
		  
		   if(empty($userregister_responce['errors']['invalid_username'][0])){
			   
			   $message['message'] = $userregister_responce['errors']['invalid_email'][0];
		   }else{
			   
			   $message['message'] = $userregister_responce['errors']['invalid_username'][0];
		   }
        
        } 
    } else {
        
        
        $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
        $currentblogid = get_current_blog_id() ;
        $user_blogs = get_blogs_of_user( $user_id );
        $user_status_for_this_site = 'not_exist';
        foreach ($user_blogs as $blog_id) { 
               
               if($blog_id->userblog_id == $currentblogid ){
                   
                   $user_status_for_this_site = 'alreadyexist';
                   break;
               }
               
        }
        
  
    if($user_status_for_this_site == 'alreadyexist'){
        
        $message['message'] =  'User already exists for this site.';
        //update_user_option($user_id, 'profile_updated', $t*1000);
    }else{    
        
        
        if (add_user_to_blog($blogid, $user_id, $role)) {
                add_user_to_blog(1, $user_id, $role);
                $message['user_id'] = $user_id;
                $message['msg'] = 'User created';
                $message['userrole'] = $role;
               
                
               
                    $useremail='';
                    
                    updateregistredUserMeta($user_id,$newContactUserData,$role);
                    custome_email_send($user_id,$email,"welcome_email_template");
                    $t=time();
                    //update_user_option($user_id, 'profile_updated', $t*1000);
                    
                
                $message['message'] =  'User added to this blog.';
            
            } else {
                
                $message['message'] = 'Failed to add user ' . $user_id . ' as ' . $role . ' to blog ' . $blogid . '.';
            }
        }
    }
    }
        echo json_encode($message);
        die();    
        
    }  catch (Exception $e){
        
        
        return $e;
    }
    
}

function updateregistredUserMeta($userID,$userMetaData,$role){
    
    try {
    
        foreach($userMetaData as $keyIndex=>$valueDataIndex){
            
            if (is_numeric($valueDataIndex)) {
                
                $valueDataIndex =  str_replace(".00","",$valueDataIndex);
                $valueDataIndex =  str_replace(".0","",$valueDataIndex);
                
            }
            
            if (strpos($keyIndex, 'task') !== false) {
                
                $current_date_time = date('d-M-Y H:i:s');
                update_user_meta($userID, $keyIndex.'_datetime', '');
                update_user_meta($userID, $keyIndex.'_status', "Complete");
                update_user_meta($userID, $keyIndex, $valueDataIndex);
                
            }else{
                
                update_user_option($userID, $keyIndex, $valueDataIndex); 
                
            }
            
            
            
            
        }
        
        
        $leavel[strtolower($role)] = 1;
        $result = update_user_option($userID, 'capabilities',  $leavel);
    
     }catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }
}

function updatetask(){
    
    try {
            
       
       // $newContactUserData =  $_POST['taskename'];//json_decode(file_get_contents('php://input')) ;
  $newContactUserData =  json_decode(file_get_contents('php://input')) ;       
        $taskkey = $newContactUserData->taskkey;
        $getkeyinformation  = gettasktype($taskkey);
        
        
        global $wpdb;
        
        $site_prefix = $wpdb->get_blog_prefix();
        $user_query = new WP_User_Query( array( 'role__not_in' => 'Administrator' ) );
        $authors = $user_query->get_results();
        
        
        $mainArrayIndex = 0;
        $contactIDkey = $site_prefix."external_reference_id_zapier";
                    
                    $arrayMonth['JAN']='01';
                    $arrayMonth['Feb']='02';
                    $arrayMonth['MAR']='03';
                    $arrayMonth['APR']='04';
                    $arrayMonth['MAY']='05';
                    $arrayMonth['JUN']='06';
                    $arrayMonth['JUL']='07';
                    $arrayMonth['AUG']='08';
                    $arrayMonth['SEP']='09';
                    $arrayMonth['OCT']='10';
                    $arrayMonth['Nov']='11';
                    $arrayMonth['DEC']='12';
                    
        if($getkeyinformation['responce'] != "invaild"){
        foreach ($authors as $userKey=>$aid) {
             
            
             $user_data = get_userdata($aid->ID);
             $all_meta_for_user = get_user_meta($aid->ID);
             $fiekldlable = str_replace(' ', '-', strtolower($taskkey));
            
                   
             if($getkeyinformation['fieldtype'] == 'customfield'){
                 
                $dataandtime = $all_meta_for_user[$site_prefix.'profile_updated'][0]/1000;
               
                
                $createuniqueKey = date('YmdHis', $dataandtime);
              
               
                
             if($getkeyinformation['type'] == 'fileupload'){
                 
                 $file_info   = unserialize($all_meta_for_user[$getkeyinformation['key']][0]);
                
                
                 
                
                 
                 if(!empty($file_info)&& !empty($all_meta_for_user[$contactIDkey][0])){
                     
                     $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$createuniqueKey;
                     $columns_rows_dataFinalData[$mainArrayIndex][$fiekldlable] = $file_info['url'];
                     $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                     $columns_rows_dataFinalData[$mainArrayIndex]['date-time'] = date('d-M-Y H:i:s', $dataandtime);;
                     $mainArrayIndex++;
                 }
                 
                }else{
                    
                        if(!empty($dataandtime) && !empty($all_meta_for_user[$contactIDkey][0])){
                            
                            $dataandtime = $all_meta_for_user[$site_prefix.'profile_updated'][0]/1000;
                            $createuniqueKey = date('YmdHis', $dataandtime);
                            
                            $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$createuniqueKey;
                            $columns_rows_dataFinalData[$mainArrayIndex][$fiekldlable] = $all_meta_for_user[$getkeyinformation['key']][0];
                            $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                            $columns_rows_dataFinalData[$mainArrayIndex]['date-time'] = date('d-M-Y H:i:s', $dataandtime);
                            $mainArrayIndex++;
                        }
                 
                 
                }
             }else{
                 
                  if($getkeyinformation['type'] == 'color'){
                      
                      
                      
                      
                      $file_info = unserialize($all_meta_for_user[$getkeyinformation['key']][0]);
                      $dateandtime =$all_meta_for_user[$getkeyinformation['key'].'_datetime'][0];
                      $dateData1 = explode(" ",$dateandtime);
                      $dateData2 = explode("-",$dateData1[0]);
                      $dateData3 = explode(":",$dateData1[1]);
                      $updateDateformat = $dateData2[2].$arrayMonth[$dateData2[1]].$dateData2[0].$dateData3[0].$dateData3[1]."15";
                      
                      if (!empty($file_info)&& !empty($all_meta_for_user[$contactIDkey][0])) {
                          
                           $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$updateDateformat;
                           $columns_rows_dataFinalData[$mainArrayIndex][$fiekldlable] = $file_info['url'];
                           $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                           $columns_rows_dataFinalData[$mainArrayIndex]['date-time'] = $dateandtime;
                           $mainArrayIndex++;
                          
                      }
                      
                      
                  }else{
                      
                     $dateandtime =$all_meta_for_user[$getkeyinformation['key'].'_datetime'][0];
                     $createuniqueKey = strtotime($dataandtime);
                     if(!empty($dateandtime) && !empty($all_meta_for_user[$contactIDkey][0])){
                         
                        $dateData1 = explode(" ",$dateandtime);
                        $dateData2 = explode("-",$dateData1[0]);
                        $dateData3 = explode(":",$dateData1[1]);
                        $updateDateformat = $dateData2[2].$arrayMonth[$dateData2[1]].$dateData2[0].$dateData3[0].$dateData3[1]."15";

                        $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$updateDateformat;
                        $columns_rows_dataFinalData[$mainArrayIndex][$fiekldlable] = $all_meta_for_user[$getkeyinformation['key']][0];
                        $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                        $columns_rows_dataFinalData[$mainArrayIndex]['date-time'] = $dateandtime;
                        $mainArrayIndex++;
                     }
                    }
                }
            }
        
        if(empty($columns_rows_dataFinalData)){
            
              $resutl ="[]";
              echo $resutl;
        }else{
            
            $resutl =  json_encode($columns_rows_dataFinalData);
            echo $resutl;
        }
        
        }else{
            
            $resutl ="[]";
            echo $resutl;
           
        }
        die();
        
    }catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

 
    
       
    
}

function updatetasksdata(){
    
    try {
            
        
        $newContactUserData =  json_decode(file_get_contents('php://input')) ;
        
        $mainfinalArray = [];
        
        $taskkey = $newContactUserData->taskkey;
        $userfields = $newContactUserData->userfieds;
        
        //$taskkey = "Company Description,Company Logo,Company Name for Print,Company Website";//$newContactUserData->taskkey;
        //$userfields = "Does NOT receive attendee list,Do NOT List as Sponsor,Booth";//$newContactUserData->userfieds;
        
        
        if (strpos($taskkey, ',') !== false) {
            
            $taskkeyArray =  explode(",",$taskkey);
            foreach ($taskkeyArray as $keys=>$keystitle) {
                
                $taskdataArray = gettaskdatabyarray($keystitle);
                
                if(!empty($taskdataArray)){
                    
                   
                    $mainfinalArray = array_merge_recursive($mainfinalArray,$taskdataArray);
                   
                   
                    
                }
               
                
            }
            
        }else{
            
            
          $taskdataArray = gettaskdatabyarray($taskkey);
          if(!empty($taskdataArray)){
                    
                   
                    $mainfinalArray = array_merge_recursive($mainfinalArray,$taskdataArray);
                   
                   
                    
                }
        }
       
        
        if(!empty($userfields)){
            
            if (strpos($userfields, ',') !== false) {
            
                $userfieldsArray =  explode(",",$userfields);
                foreach ($userfieldsArray as $keys=>$keystitle) {
                
                    $userfieldsArray = gettaskdatabyarray($keystitle);
                    if(!empty($userfieldsArray)){
                         
                          $mainfinalArray = array_merge_recursive($mainfinalArray,$userfieldsArray);
                         
                     }
                }
                
            }else{
            
                $userfieldsArray = gettaskdatabyarray($userfields);
                if(!empty($userfieldsArray)){
                         
                          $mainfinalArray = array_merge_recursive($mainfinalArray,$userfieldsArray);
                         
                     }
            }
            
        }
        
       // print_r($mainfinalArray);
        $result_final_array =  json_encode($mainfinalArray);
        
        echo $result_final_array;
        
        die();
        
    }catch (Exception $e) {

        contentmanagerlogging_file_upload($lastInsertId, serialize($e));

        return $e;
    }

 
    
       
    
}

function gettaskdatabyarray($taskkey){
    
        global $wpdb;
        
        $site_prefix = $wpdb->get_blog_prefix();
        $user_query = new WP_User_Query( array( 'role__not_in' => 'Administrator' ) );
        $authors = $user_query->get_results();
        
        
        $mainArrayIndex = 0;
        $contactIDkey = $site_prefix."external_reference_id_zapier";
                    
        $arrayMonth['Jan']='01';
        $arrayMonth['Feb']='02';
        $arrayMonth['Mar']='03';
        $arrayMonth['Apr']='04';
        $arrayMonth['May']='05';
        $arrayMonth['Jun']='06';
        $arrayMonth['Jul']='07';
        $arrayMonth['Aug']='08';
        $arrayMonth['Sep']='09';
        $arrayMonth['Oct']='10';
        $arrayMonth['Nov']='11';
        $arrayMonth['Dec']='12';
        
        $getkeyinformation  = gettasktype($taskkey);
        
        if($getkeyinformation['responce'] != "invaild"){
        foreach ($authors as $userKey=>$aid) {
             
            
             $user_data = get_userdata($aid->ID);
             $all_meta_for_user = get_user_meta($aid->ID);
             $fiekldlable = str_replace(' ', '-', strtolower($taskkey));
            
                   
             if($getkeyinformation['fieldtype'] == 'customfield'){
                 
                $dataandtime = $all_meta_for_user[$site_prefix.'profile_updated'][0]/1000;
               
                
                $createuniqueKey = date('YmdHis', $dataandtime);
              
               
                
             if($getkeyinformation['type'] == 'fileupload'){
                 
                 $file_info   = unserialize($all_meta_for_user[$getkeyinformation['key']][0]);
                
                
                 
                
                 
                 if(!empty($file_info)&& !empty($all_meta_for_user[$contactIDkey][0])){
                     
                     $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$createuniqueKey;
                     $columns_rows_dataFinalData[$mainArrayIndex]['taskvalue'] = $file_info['url'];
                     $columns_rows_dataFinalData[$mainArrayIndex]['taskname'] = $taskkey;
                     $columns_rows_dataFinalData[$mainArrayIndex]['label'] = $taskkey.' - '.date("Y-m-d", $dataandtime).' - '.$all_meta_for_user[$contactIDkey][0];
                     $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                     $columns_rows_dataFinalData[$mainArrayIndex]['created'] =  date("Y-m-d H:i:s", $dataandtime);//date('d-M-Y H:i:s', $dataandtime);
                     $mainArrayIndex++;
                 }
                 
                }else{
                    
                        if(!empty($dataandtime) && !empty($all_meta_for_user[$contactIDkey][0])){
                            
                            $dataandtime = $all_meta_for_user[$site_prefix.'profile_updated'][0]/1000;
                            $createuniqueKey = date('YmdHis', $dataandtime);
                            
                            $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$createuniqueKey;
                            $columns_rows_dataFinalData[$mainArrayIndex]['taskname'] = $taskkey;
                            $columns_rows_dataFinalData[$mainArrayIndex]['label'] = $taskkey.' - '.date("Y-m-d", $dataandtime).' - '.$all_meta_for_user[$contactIDkey][0];
                    
                            $columns_rows_dataFinalData[$mainArrayIndex]['taskvalue'] = $all_meta_for_user[$getkeyinformation['key']][0];
                            $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                            $columns_rows_dataFinalData[$mainArrayIndex]['created'] = date("Y-m-d H:i:s", $dataandtime);//date('d-M-Y H:i:s', $dataandtime);
                            $mainArrayIndex++;
                        }
                 
                 
                }
             }else{
                 
                  if($getkeyinformation['type'] == 'color'){
                      
                      
                      
                      
                      $file_info = unserialize($all_meta_for_user[$getkeyinformation['key']][0]);
                      $dateandtime =$all_meta_for_user[$getkeyinformation['key'].'_datetime'][0];
                      $dateData1 = explode(" ",$dateandtime);
                      $dateData2 = explode("-",$dateData1[0]);
                      $dateData3 = explode(":",$dateData1[1]);
                      
                     
                      
                    //  $stortnewdatetime = $arrayMonth[$dateData2[1]]
                    //  echo $stortnewdatetime;
                      
                      $updateDateformat = $dateData2[2].$arrayMonth[$dateData2[1]].$dateData2[0].$dateData3[0].$dateData3[1]."15";
                      
                      if (!empty($file_info)&& !empty($all_meta_for_user[$contactIDkey][0])) {
                          
                          
                         
                          
                           $stortnewdatetime = $dateData2[2].'-'.$arrayMonth[$dateData2[1]].'-'.$dateData2[0].' '.$dateData3[0].':'.$dateData3[1].':00';
                           $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$updateDateformat;
                           $columns_rows_dataFinalData[$mainArrayIndex]['taskname'] = $taskkey;
                           $columns_rows_dataFinalData[$mainArrayIndex]['label'] = $taskkey.' - '.date("Y-m-d", strtotime($stortnewdatetime)).' - '.$all_meta_for_user[$contactIDkey][0];
                    
                           $columns_rows_dataFinalData[$mainArrayIndex]['taskvalue'] = $file_info['url'];
                           $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                           $columns_rows_dataFinalData[$mainArrayIndex]['created'] = date("Y-m-d H:i:s", strtotime($stortnewdatetime));//$dateandtime;
                           $mainArrayIndex++;
                          
                      }
                      
                      
                  }else if($getkeyinformation['type'] == 'select-2'){
                      
                      $dateandtime =$all_meta_for_user[$getkeyinformation['key'].'_datetime'][0];
                   
                     $createuniqueKey = strtotime($dataandtime);
                     if(!empty($dateandtime) && !empty($all_meta_for_user[$contactIDkey][0])){
                         
                        $dateData1 = explode(" ",$dateandtime);
                        $dateData2 = explode("-",$dateData1[0]);
                        $dateData3 = explode(":",$dateData1[1]);
                        
                        
                        
                        //echo $dateandtime.'<br>';
                       $stortnewdatetime = $dateData2[2].'-'.$arrayMonth[$dateData2[1]].'-'.$dateData2[0].' '.$dateData3[0].':'.$dateData3[1].':00';
                       // echo $stortnewdatetime.'<br>';
                        //echo strtotime($stortnewdatetime).'<br>';
                        //echo date("Y-m-d H:i:s", strtotime($stortnewdatetime)).'<br>';
                        
                        
                        $updateDateformat = $dateData2[2].$arrayMonth[$dateData2[1]].$dateData2[0].$dateData3[0].$dateData3[1]."15";

                        $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$updateDateformat;
                        $columns_rows_dataFinalData[$mainArrayIndex]['taskname'] = $taskkey;
                        $columns_rows_dataFinalData[$mainArrayIndex]['label'] = $taskkey.' - '.date("Y-m-d", strtotime($stortnewdatetime)).' - '.$all_meta_for_user[$contactIDkey][0];
                        
                        
                        if($getkeyinformation['additional_attribute'] == "checked" ){
                            
                            
                            $arraysofmultiselect =  unserialize($all_meta_for_user[$getkeyinformation['key']][0]); 
                           
                              foreach ($arraysofmultiselect as $multivalueIndex=>$multivalue){
                               $mutivalues .=$arraysofmultiselect[$multivalueIndex].',';
                               
                               
                           }
                            
                            
                        }else{
                            
                            $mutivalues =  $all_meta_for_user[$getkeyinformation['key']][0]; 
                            
                        }
                        
                        
                        
                        $columns_rows_dataFinalData[$mainArrayIndex]['taskvalue'] = rtrim($mutivalues,',');
                        $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                        $columns_rows_dataFinalData[$mainArrayIndex]['created'] = date("Y-m-d H:i:s", strtotime($stortnewdatetime));
                        $mainArrayIndex++;
                     }
                      
                      
                  }else if($getkeyinformation['type'] == 'multivaluedtask'){
                      
                      $dateandtime =$all_meta_for_user[$getkeyinformation['key'].'_datetime'][0];
                   
                     $createuniqueKey = strtotime($dataandtime);
                     if(!empty($dateandtime) && !empty($all_meta_for_user[$contactIDkey][0])){
                         
                        $dateData1 = explode(" ",$dateandtime);
                        $dateData2 = explode("-",$dateData1[0]);
                        $dateData3 = explode(":",$dateData1[1]);
                        
                        
                        
                        //echo $dateandtime.'<br>';
                       $stortnewdatetime = $dateData2[2].'-'.$arrayMonth[$dateData2[1]].'-'.$dateData2[0].' '.$dateData3[0].':'.$dateData3[1].':00';
                       // echo $stortnewdatetime.'<br>';
                        //echo strtotime($stortnewdatetime).'<br>';
                        //echo date("Y-m-d H:i:s", strtotime($stortnewdatetime)).'<br>';
                        
                        
                        $updateDateformat = $dateData2[2].$arrayMonth[$dateData2[1]].$dateData2[0].$dateData3[0].$dateData3[1]."15";

                        $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$updateDateformat;
                        $columns_rows_dataFinalData[$mainArrayIndex]['taskname'] = $taskkey;
                        $columns_rows_dataFinalData[$mainArrayIndex]['label'] = $taskkey.' - '.date("Y-m-d", strtotime($stortnewdatetime)).' - '.$all_meta_for_user[$contactIDkey][0];
                        
                        $multivaluetaskarray = json_decode($all_meta_for_user[$getkeyinformation['key']][0]);
                        foreach ($multivaluetaskarray as $multivalueIndex=>$multivalue){
                                                        
                            $multitaskvalues.=$multivaluetaskarray[$multivalueIndex].",";
                        }
                                                 
                        
                        $columns_rows_dataFinalData[$mainArrayIndex]['taskvalue'] = rtrim($multitaskvalues, ',');
                        $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                        $columns_rows_dataFinalData[$mainArrayIndex]['created'] = date("Y-m-d H:i:s", strtotime($stortnewdatetime));
                        $mainArrayIndex++;
                     }
                      
                      
                  }else{
                      
                     $dateandtime =$all_meta_for_user[$getkeyinformation['key'].'_datetime'][0];
                   
                     $createuniqueKey = strtotime($dataandtime);
                     if(!empty($dateandtime) && !empty($all_meta_for_user[$contactIDkey][0])){
                         
                        $dateData1 = explode(" ",$dateandtime);
                        $dateData2 = explode("-",$dateData1[0]);
                        $dateData3 = explode(":",$dateData1[1]);
                        
                        
                        
                        //echo $dateandtime.'<br>';
                       $stortnewdatetime = $dateData2[2].'-'.$arrayMonth[$dateData2[1]].'-'.$dateData2[0].' '.$dateData3[0].':'.$dateData3[1].':00';
                       // echo $stortnewdatetime.'<br>';
                        //echo strtotime($stortnewdatetime).'<br>';
                        //echo date("Y-m-d H:i:s", strtotime($stortnewdatetime)).'<br>';
                        
                        
                        $updateDateformat = $dateData2[2].$arrayMonth[$dateData2[1]].$dateData2[0].$dateData3[0].$dateData3[1]."15";

                        $columns_rows_dataFinalData[$mainArrayIndex]['id'] = $aid->ID.$updateDateformat;
                        $columns_rows_dataFinalData[$mainArrayIndex]['taskname'] = $taskkey;
                        $columns_rows_dataFinalData[$mainArrayIndex]['label'] = $taskkey.' - '.date("Y-m-d", strtotime($stortnewdatetime)).' - '.$all_meta_for_user[$contactIDkey][0];
                    
                        $columns_rows_dataFinalData[$mainArrayIndex]['taskvalue'] = $all_meta_for_user[$getkeyinformation['key']][0];
                        $columns_rows_dataFinalData[$mainArrayIndex]['external_reference_id_zapier'] = $all_meta_for_user[$contactIDkey][0];
                        $columns_rows_dataFinalData[$mainArrayIndex]['created'] = date("Y-m-d H:i:s", strtotime($stortnewdatetime));
                        $mainArrayIndex++;
                     }
                    }
                }
            }
        
        if(empty($columns_rows_dataFinalData)){
            
              $resutl =array();
              //echo $resutl;
        }else{
          //  $columns_rows_dataFinalData =  (object) $columns_rows_dataFinalData;
            $resutl =  $columns_rows_dataFinalData;
          
        }
        
        }else{
            
             $resutl =array();
            //echo $resutl;
           
        }
        
        return $resutl;
    
}
function gettasktype($taskkey){
    
        
        
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'egpl_custome_tasks',
            'post_status'      => 'draft',

        );
        global $wpdb;
        $site_prefix = $wpdb->get_blog_prefix();
        $result_task_array_list = get_posts( $args );
        $columns_rows_data = [];
        $columns_list=[];
        
       
        require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/egpl-custome-functions.php';
        $GetAllcustomefields = new EGPLCustomeFunctions();
        $additional_fields = $GetAllcustomefields->getAllcustomefields();
        usort($additional_fields, 'sortByOrder');
        
        
       
        
        foreach ($additional_fields as $key=>$value){
            
            if($value['fieldName'] == $taskkey){
                
                $getOrginalData['key'] = $site_prefix.$value['fielduniquekey'];
                $getOrginalData['type'] = $value['fieldType'];
                $getOrginalData['fieldtype'] = 'customfield';
                $getOrginalData['responce'] = "ok";
            }
            
        }
        
        
        foreach ($result_task_array_list as $taskIndex => $taskObject) {
            
            $tasksID=$taskObject->ID;
            
            $value_type = get_post_meta( $tasksID, 'type' , true);
            $value_key = get_post_meta( $tasksID, 'key', true);
            $label = get_post_meta( $tasksID, 'label', true);
            $additional_attribute = get_post_meta( $tasksID, 'multiselectstatus', true);
            if($label == $taskkey){
                
                $getOrginalData['key'] = $value_key;
                $getOrginalData['type'] = $value_type;
                $getOrginalData['fieldtype'] = 'task';
                $getOrginalData['additional_attribute'] = $additional_attribute;
                $getOrginalData['responce'] = "ok";
                
            }
        }
    if(empty($getOrginalData)){
        
        $getOrginalData['responce'] = "invaild";
    }
    return $getOrginalData;
}

///-----------------Expogenie API Endpoints ---------------------///

//[siturl]
function currentsiteurl_func( $atts ){
	
    $site_url = get_option('siteurl' );
    return $site_url;
}
add_shortcode( 'siturl', 'currentsiteurl_func' );




add_action( 'woocommerce_thankyou', 'adding_customers_details_to_thankyou', 10, 1 );
function adding_customers_details_to_thankyou( $order_id ) {
    // Only for non logged in users
    if ( ! $order_id || is_user_logged_in() ) return;

    $order = wc_get_order($order_id); // Get an instance of the WC_Order object

    wc_get_template( 'order/order-details-customer.php', array('order' => $order ));
}


function getroleorder($rolename){
    
    //echo $rolename;
    
    if(!empty($rolename)){
        
        if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
        $get_all_roles = get_option($get_all_roles_array);
        
        
        
        foreach ($get_all_roles as $key => $name) {
            
         
            if ($rolename  == 'subscriber' || $rolename  == 'customer' ) {
                
                $orderNumber = 1000;
                return $orderNumber;
                
            }else{
               // echo $rolename .'=='. $key.'__________';
               if($rolename == $key) {
                   
                   
                   $orderNumber = (int)$name['priorityNum'];
                   return $orderNumber;
               }
                
                
                
            }
            
        }
        
    }
}

function selfsign_registration_emails_woocomerce($user_id,$send_email_type){
        
        //require_once 'Mandrill.php';
        
        $user = get_userdata($user_id);
        $email = $user->user_email;
        global $wpdb;
        $site_prefix = $wpdb->get_blog_prefix();
    
        $all_meta_for_user = get_user_meta( $user_id );
        $site_url = get_option('siteurl' );
        $site_title=get_option( 'blogname' );
       
        //$settitng_key='AR_Contentmanager_Email_Template_welcome';
        //$sponsor_info = get_option($settitng_key);
        
        $sponsor_info['selfsign_registration_request_email']['selfsignfromname'] = $site_title;
        $sponsor_info['selfsign_registration_request_email']['selfsignsubject'] = 'Exhibitor Application Received for '.$site_title.'';
        $sponsor_info['selfsign_registration_request_email']['selfsignboday'] = '<p>Hi '.$all_meta_for_user[$site_prefix.'first_name'][0].'  '.$all_meta_for_user[$site_prefix.'last_name'][0].',</p><p>Thank you for submitting your application form for <strong>'.$site_title.'</strong>. We are currently reviewing your submission. You will receive an email with login credentials once the review is complete.</p><p>Thank You!</p>';
        
        $sponsor_info['selfsign_registration_declined_email']['declinedfromname'] = $site_title;
        $sponsor_info['selfsign_registration_declined_email']['declinedsubject'] = 'Registration Application Declined for ['.$site_title.']';
        $sponsor_info['selfsign_registration_declined_email']['declinedboday'] = '<p>Dear '.$all_meta_for_user[$site_prefix.'first_name'][0].'  '.$all_meta_for_user[$site_prefix.'last_name'][0].',</p><p>Your registration application on <strong>'.$site_title.'</strong>  has been declined. If you have any further queries, please contact us: <strong>'.$site_url.'</strong> </p><p>Thanks</p>';

        $oldvalues = get_option( 'ContenteManager_Settings' );
        $formemail = $oldvalues['ContentManager']['formemail'];
        
        if(empty($formemail)){
    
            $formemail = 'noreply@convospark.com';
        
        }
        if($send_email_type == 'declined'){
            
            $subject_body = $sponsor_info['selfsign_registration_declined_email']['declinedsubject'];
            $body_message=$sponsor_info['selfsign_registration_declined_email']['declinedboday'];
            $formemailandtitle = $sponsor_info['selfsign_registration_declined_email']['declinedfromname'];
            
        }else{
            
            $subject_body = $sponsor_info['selfsign_registration_request_email']['selfsignsubject'];
            $body_message=$sponsor_info['selfsign_registration_request_email']['selfsignboday'];
            $formemailandtitle = $sponsor_info['selfsign_registration_request_email']['selfsignfromname']; 
            
        }
       
     
          
        $welcomememailreplayto = get_option('AR_Contentmanager_Email_Template_welcome');
        $replaytoemailadd = $welcomememailreplayto['welcome_email_template']['replaytoemailadd'];
        
       
        
        $oldvalues = get_option( 'ContenteManager_Settings' );
        
        $registration_notificationemails = $oldvalues['ContentManager']['registration_notificationemails'];
        $mandrillKeys = $oldvalues['ContentManager']['mandrill'];
        $to_message_array[]=array('email'=>$email,'name'=>$all_meta_for_user[$site_prefix.'first_name'][0],'type'=>'to');
       
        if(!empty($registration_notificationemails)){
            $getemailsaddress = explode(",",$registration_notificationemails);
            foreach($getemailsaddress as $emailsindex=>$emailaddress){
                
                $to_message_array[]=array('email'=>$emailaddress,'name'=>"",'type'=>'bcc');
                
            }
            
        }
        
       
        
        //$mandrill = new Mandrill($mandrillKeys);
        
      
        $message = array(

             'html' => $body_message,
             'text' => '',
             'subject' => $subject_body,
             'from_email' => $formemail,
             'from_name' => $formemailandtitle,
             'to' => $to_message_array,
             'headers' => array('Reply-To' => $formemail),
             'bcc_address'=>$replaytoemailadd,
             'track_opens' => true,
             'track_clicks' => true


         );
     
    $async = false;
    $ip_pool = 'Main Pool';
  
    //$result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    $get_currentsiteURl = get_site_url();
    $uri = 'https://mandrillapp.com/api/1.0/messages/send.json';
    $postString['key'] = $mandrill;
    
    $postString['message'] = $message; 
    $postString['async'] = false;
    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postString));

    $result = curl_exec($ch);

    
    
    
}



function custome_email_send_AD($user_id, $userlogin='', $welcomeemailtemplatename=''){
    
    //require_once('../../../wp-load.php');
    //require_once 'Mandrill.php';
    
    
 try {

    global $wpdb, $wp_hasher;
    $site_prefix = $wpdb->get_blog_prefix();
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $mandrill = $oldvalues['ContentManager']['mandrill'];
    //$mandrill = new Mandrill($mandrillkey);
    
   
    
        $user = get_userdata($user_id);
        $floor_Plan_Settings = 'floorPlanSettings';
        $get= get_option($floor_Plan_Settings);
        if(empty($userlogin)){
            
            $user_login = stripslashes($user->user_login);
            $user_email = stripslashes($user->user_email);
            
          }else{
              
              $user_email = $userlogin;
              $user_login = $userlogin;
          }
  
          if(empty($welcomeemailtemplatename)){
  
             $welcomeemailtemplatename = "welcome_email_template";     
          }
  

        //$plaintext_pass=wp_generate_password( 8, false, false );
        //wp_set_password( $plaintext_pass, $user_id );

        // if($settitng_key == '')
        //     $settitng_key='AR_Contentmanager_Email_Template_welcome';

        $sponsor_info = get_option("AR_Contentmanager_Email_Template_booth");
        //  print_r($sponsor_info);
        $site_url = get_option('siteurl' );
        $data=  date("Y-m-d");
        $time=  date('H:i:s');
        $site_title=get_option( 'blogname' );
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $formemail = $oldvalues['ContentManager']['formemail'];
        if(empty($formemail)){
            $formemail = 'noreply@expo-genie.com';
        
        }
        
       
      
        $formemail = $oldvalues['ContentManager']['formemail'];
        $fromname = stripslashes ($sponsor_info[$welcomeemailtemplatename]['fromname']);
        if(empty($formemail)){

            $formemail = 'noreply@expo-genie.com';

        }
        
        $subject = $sponsor_info[$welcomeemailtemplatename]['welcomesubject'];
	$bcc =  $sponsor_info[$welcomeemailtemplatename]['BCC'];
       // $cc =  $sponsor_info[$welcomeemailtemplatename]['CC'];
	$formname = $sponsor_info[$welcomeemailtemplatename]['fromname'];
        $replaytoemailadd = $sponsor_info[$welcomeemailtemplatename]['replaytoemailadd'];
        $bcc_array = $bcc;
      //  $cc_array = explode(',',$cc);
        
       

    $subject = $sponsor_info[$welcomeemailtemplatename]['welcomesubject'];
    $body=stripslashes ($sponsor_info[$welcomeemailtemplatename]['welcomeboday']);
   
    $user_ID = get_current_user_id();
    $user_info = get_userdata($user_ID);
    
    $field_key_string =  getInbetweenStrings('{', '}', $body);
    $field_key_subject = getInbetweenStrings('{', '}', $subject);
    
  
    $site_url = get_option('siteurl' );

    $login_url = get_option('siteurl' );
    $admin_email= get_option('admin_email');
    $data=  date("Y-m-d");
    $time=  date('H:i:s');
    $sitetitle = get_bloginfo( 'name' );
    if(empty($fromname)){
        $fromname = get_bloginfo( 'name' );
    }
   // $body = str_replace('[site_url]', $site_url, $body);
   // $body = str_replace('[login_url]', $site_url, $body);
   // $body = str_replace('[admin_email]', $admin_email, $body);
    $subject = str_replace('{', '*|', $subject);
    $subject = str_replace('}', '|*', $subject);
    $body = str_replace('{', '*|', $body);
    $body = str_replace('}', '|*', $body);
    
    

    $goble_data_array =array(
        array('name'=>'date','content'=>$data),
        array('name'=>'time','content'=>$time),
        array('name'=>'site_url','content'=>$site_url),
        array('name'=>'site_title','content'=>$sitetitle)
        );

  

       
       $data_field_array= array();
       $t=time();
       update_user_option($user_id, 'convo_welcomeemail_datetime', $t*1000);
       
       foreach($field_key_subject as $index_subject=>$keyvalue_subject){

                      if($keyvalue_subject == 'wp_user_id' ||$keyvalue_subject == 'Role' || $keyvalue_subject == 'site_title' || $keyvalue_subject == 'date' || $keyvalue_subject == 'time' || $keyvalue_subject == 'site_url' || $keyvalue_subject == 'user_pass'|| $keyvalue_subject == 'user_login'){


                      if($keyvalue_subject == 'user_pass'){


                            
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$plaintext_pass);

                      }else if($keyvalue_subject == 'user_login'){

                          $data_field_array[] = array('name'=>$index_subject,'content'=>$user->user_login);
                      }else if($keyvalue_subject == 'Role'){

                         
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {

                              if(implode(', ', $getcurrentuserdata->roles) == $key){

                                  $currentuserRole = $name['name'];


                              }



                          }


                          $data_field_array[] = array('name'=>$index_subject,'content'=>$currentuserRole);
                      }elseif($keyvalue_subject == 'wp_user_id'){
                          
                          $data_field_array[] = array('name'=>$index_subject,'content'=>$user_id);
                          
                      }



                   }else{


                       $get_meta_value = get_user_meta_merger_field_value($user_id,$keyvalue_subject);
                       
                       
                       
                       
                    if($get_meta_value!=""){
                        
                       
                        $getfieldType = getcustomefieldKeyValue($keyvalue_subject,"fieldType");
                        
                       if($getfieldType == "date"){
                            
                            $date_value =   date('d-m-Y' , intval($all_meta_for_user[$keyvalue_subject][0])/1000);
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$date_value);
                           
                       }else{
                           
                            $data_field_array[] = array('name'=>$index_subject,'content'=>$get_meta_value);
                           
                       }
                             
                      
                             
                        
                       
                   }else{
                       
                       $data_field_array[] = array('name'=>$index_subject,'content'=>'');
                   }




                  }



             }
       foreach($field_key_string as $index=>$keyvalue){

                      if($keyvalue == 'wp_user_id' || $keyvalue == 'Role' || $keyvalue == 'site_title' || $keyvalue == 'date' || $keyvalue == 'time' || $keyvalue == 'site_url' || $keyvalue == 'user_pass'|| $keyvalue == 'user_login'){


                      if($keyvalue == 'user_pass'){


                            
                            $plaintext_pass=wp_generate_password( 8, false, false );
                            wp_set_password( $plaintext_pass, $user_id );
                            $data_field_array[] = array('name'=>$index,'content'=>$plaintext_pass);

                      }else if($keyvalue == 'user_login'){

                          $data_field_array[] = array('name'=>$index,'content'=>$user->user_login);
                      }else if($keyvalue == 'Role'){

                         
                          $getcurrentuserdata = get_userdata( $user_id );
                          $blog_id = get_current_blog_id();
                          $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                          $get_all_roles = get_option($get_all_roles_array);
                          foreach ($get_all_roles as $key => $name) {

                              if(implode(', ', $getcurrentuserdata->roles) == $key){

                                  $currentuserRole = $name['name'];


                              }



                          }


                          $data_field_array[] = array('name'=>$index,'content'=>$currentuserRole);
                      }elseif($keyvalue == 'wp_user_id'){
                          
                          $data_field_array[] = array('name'=>$index,'content'=>$user_id);
                          
                      }



                   }else{


                       $get_meta_value = get_user_meta_merger_field_value($user_id,$keyvalue);
                       if($get_meta_value!=""){

                            $getfieldType = getcustomefieldKeyValue($keyvalue,"fieldType");

                           if($getfieldType == "date"){

                                $date_value =   date('d-m-Y' , intval($get_meta_value)/1000);
                                $data_field_array[] = array('name'=>$index,'content'=>$date_value);

                           }else{

                                $data_field_array[] = array('name'=>$index,'content'=>$get_meta_value);

                           }





                       }else{

                           $data_field_array[] = array('name'=>$index,'content'=>'');
                       }




                  }



             }
       $to_message_array[]=array('email'=>$user_email,'name'=>$first_name,'type'=>'to');
           $user_data_array[] =array(
                'rcpt'=>$user_email,
                'vars'=>$data_field_array
           );


        
        $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
        $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
        
       
        
        $logourl = '';

        if(!empty($mainheaderlogo)){

            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderlogo.'" alt="" width="250" />';

        }else if(!empty($mainheaderbackground)){

            $logourl = '<img style="margin-top: 16px;" src="'.$mainheaderbackground.'" alt="" height="100" />';


        }

        $html_body_message = '<table width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
<tbody>
<tr>
<td align="left">
<div style="border: solid 1px #d9d9d9;">
<table id="header" style="line-height: 1.6;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="text-align: center;">'.$logourl.'</td>
</tr>
</tbody>
</table>
<table id="content" style="padding-right: 30px;padding-left: 30px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="border-top: solid 1px #d9d9d9;" colspan="2">
<div style="padding: 1em 0;">
'.$body.'
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>';


   $get_currentsiteURl = get_site_url();
   $uri = 'https://mandrillapp.com/api/1.0/messages/send.json';
   $postString['key'] = $mandrill;
        
   
   $message = array(

        'html' => $html_body_message,
        'text' => '',
        'subject' => $subject,
        'from_email' => $formemail,
        'from_name' => $fromname,
        'to' => $to_message_array,
        'headers' => array('Reply-To' => $replaytoemailadd),
        'bcc_address'=>$bcc_array,
        'track_opens' => true,
        'track_clicks' => true,
         
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => $goble_data_array,
        'merge_vars' => $user_data_array,
        "tags" => [$get_currentsiteURl]

    );

    // exit;
    
    $postString['message'] = $message; 
    $postString['async'] = false;
   
    $lastInsertId = contentmanagerlogging('Welcome Email',"Admin Action",serialize($message),$user_id,$user_info->user_email,"pre_action_data");

    $async = false;
    $ip_pool = 'Main Pool';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postString));

    $result = curl_exec($ch);
    
    
   // $send_at = 'example send_at';
    //$result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
    contentmanagerlogging_file_upload($lastInsertId,serialize($result));
    // echo json_encode('successfully send');
   


}catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    $error_msg = 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'


    contentmanagerlogging_file_upload($lastInsertId,$error_msg);
     echo   $e->getMessage();
    //throw $e;
}
    
}
function user_file_upload_AD($keyvalue,$updatevalue,$userid) {
    
    //$key = $_POST['value'];
    
   try {
    $user_info = get_userdata($userid);
    $old_meta_value=get_user_meta($userid, $keyvalue); 
    
       global  $wpdb;
       $site_prefix = $wpdb->get_blog_prefix();
         
        
    if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
    //$upload_overrides = array( 'test_form' => false, 'mimes' => array('eps'=>'application/postscript','ai' => 'application/postscript','jpg|jpeg|jpe' => 'image/jpeg','gif' => 'image/gif','png' => 'image/png','bmp' => 'image/bmp','pdf'=>'text/pdf','doc'=>'application/msword','docx'=>'application/msword','xlsx'=>'application/msexcel') );
    $mime_type = array(
	// Image formats
	'jpg|jpeg|jpe'                 => 'image/jpeg',
	'gif'                          => 'image/gif',
	'png'                          => 'image/png',
	'bmp'                          => 'image/bmp',
	'tif|tiff'                     => 'image/tiff',
	'ico'                          => 'image/x-icon',
        'eps'                          => 'application/postscript',
        'ai'                           =>  'application/postscript',
	// Video formats
	'asf|asx'                      => 'video/x-ms-asf',
	'wmv'                          => 'video/x-ms-wmv',
	'wmx'                          => 'video/x-ms-wmx',
	'wm'                           => 'video/x-ms-wm',
	'avi'                          => 'video/avi',
	'divx'                         => 'video/divx',
	'flv'                          => 'video/x-flv',
	'mov|qt'                       => 'video/quicktime',
	'mpeg|mpg|mpe'                 => 'video/mpeg',
	'mp4|m4v'                      => 'video/mp4',
	'ogv'                          => 'video/ogg',
	'webm'                         => 'video/webm',
	'mkv'                          => 'video/x-matroska',
	
	// Text formats
	'txt|asc|c|cc|h'               => 'text/plain',
	'csv'                          => 'text/csv',
	'tsv'                          => 'text/tab-separated-values',
	'ics'                          => 'text/calendar',
	'rtx'                          => 'text/richtext',
	'css'                          => 'text/css',
	'htm|html'                     => 'text/html',
            'svg'                          => 'image/svg+xml',
        
	// Audio formats
	'mp3|m4a|m4b'                  => 'audio/mpeg',
	'ra|ram'                       => 'audio/x-realaudio',
	'wav'                          => 'audio/wav',
	'ogg|oga'                      => 'audio/ogg',
	'mid|midi'                     => 'audio/midi',
	'wma'                          => 'audio/x-ms-wma',
	'wax'                          => 'audio/x-ms-wax',
	'mka'                          => 'audio/x-matroska',
	
	// Misc application formats
	'rtf'                          => 'application/rtf',
	'js'                           => 'application/javascript',
	'pdf'                          => 'application/pdf',
	'swf'                          => 'application/x-shockwave-flash',
	'class'                        => 'application/java',
	'tar'                          => 'application/x-tar',
	'zip'                          => 'application/zip',
	'gz|gzip'                      => 'application/x-gzip',
	'rar'                          => 'application/rar',
	'7z'                           => 'application/x-7z-compressed',
	'exe'                          => 'application/x-msdownload',
	
	// MS Office formats
	'doc'                          => 'application/msword',
	'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
	'wri'                          => 'application/vnd.ms-write',
	'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
	'mdb'                          => 'application/vnd.ms-access',
	'mpp'                          => 'application/vnd.ms-project',
	'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
	'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
	'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
	'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
	'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
	'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
	'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
	'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
	
	// OpenOffice formats
	'odt'                          => 'application/vnd.oasis.opendocument.text',
	'odp'                          => 'application/vnd.oasis.opendocument.presentation',
	'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
	'odg'                          => 'application/vnd.oasis.opendocument.graphics',
	'odc'                          => 'application/vnd.oasis.opendocument.chart',
	'odb'                          => 'application/vnd.oasis.opendocument.database',
	'odf'                          => 'application/vnd.oasis.opendocument.formula',
	
	// WordPerfect formats
	'wp|wpd'                       => 'application/wordperfect',
	
	// iWork formats
	'key'                          => 'application/vnd.apple.keynote',
	'numbers'                      => 'application/vnd.apple.numbers',
	'pages'                        => 'application/vnd.apple.pages',
);
    $upload_overrides = array( 'test_form' => false,$mime_type);
    $movefile = wp_handle_upload( $updatevalue, $upload_overrides );
    
    
   if($movefile && !isset( $movefile['error'])) {
           $utl_value = str_replace('\\', '/', $movefile['file']);
           $fileurl['file'] =$utl_value ;
           $fileurl['type'] = $movefile['type'];
           $fileurl['user_id'] = $userid;
           $fileurl['url'] = $movefile['url'];
           
           //var_dump($fileurl); exit;
           $result =  update_user_meta($userid, $keyvalue , $fileurl);
           //$email_body_message_for_admin.="Task Name ::".$task_id."\n File Name::".$fileurl['url']."\n File Url::".$fileurl['file']."\n ------------------ \n";
         
          
      }else{         
            $result =   update_user_meta($userid, $keyvalue , "");         
      }
       echo '////'.json_encode($movefile);
      updatetocventAD($postid,$movefile['url'],$keyvalue);
      die();

    //wp_mail($to, $subject, $email_body_message_for_admin,$headers);
   } catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
   }
  
}
function updatetocventAD($postid,$updatevalue,$keyvalue){
    
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $cventAccountNo = $oldvalues['ContentManager']['cventaccountname'];
    $cventUsername = $oldvalues['ContentManager']['cventusername'];
    $cventAPiName = $oldvalues['ContentManager']['cventapipassword'];
    
    
   if(!empty($cventAccountNo) && !empty($cventUsername) && !empty($cventAPiName)){
      
       
      
       require('temp/php-cvent-master/CventClient.class.php');
       include 'defult-content.php';
       
       $bar = get_user_option( 'contactStub', $postid );
       
       if(!empty($bar)){
       $cventID[0] = $bar;
       
       
       
       
       $cc = new CventClient();
       $cc->Login($cventAccountNo, $cventUsername, $cventAPiName);
       $type = 'Update';
       $getContact = $cc->RetrieveContacts($cventID);
       
       
       
       
       $getContact['request_input_value_expogenie'] = $request_value;
       
       $lastInsertId = contentmanagerlogging('Update Cvent Custome Field Retrieve',"User Action",serialize($getContact),$postid,$user_info->user_email,"pre_action_data");
      
       
       
       if($cventmappingarray[$keyvalue]['type']  == 'custome'){
       
           foreach($getContact[0]->CustomFieldDetail as $key=>$value){



                   if($cventmappingarray[$keyvalue]['id'] == $value->FieldId){

                       $contactUpdate[0]->CustomFieldDetail[0]->FieldName = $value->FieldName;
                       $contactUpdate[0]->CustomFieldDetail[0]->FieldType = $value->FieldType;
                       $contactUpdate[0]->CustomFieldDetail[0]->FieldValue = $updatevalue;
                       $contactUpdate[0]->CustomFieldDetail[0]->FieldId = $value->FieldId;


                   }




           }
       }
       
       $contactUpdate[0]->Id = $cventID[0];
       
      
       
       
       $lastInsertId = contentmanagerlogging('Update Cvent Custome Field Pre Request',"User Action",serialize($contactUpdate),$postid,$user_info->user_email,"pre_action_data");
    
     
       
       $result = $cc->CreateUpdateContacts($type, $contactUpdate);
       
       contentmanagerlogging_file_upload ($lastInsertId,serialize($result));
       }
   
}
}
                        //code by AD

 // if($dataRequestedFloor['select1']=="Auto and Email"||$dataRequestedFloor['select1']=="Auto and No Email"){
                           
                        //     if ($userStatus=='checked'&& $ordernumber != '-' ) {
                        //         array_push($arr,(object)[
                        //             'Id' => $rolekey,
                        //             'Status'=> $userStatus,
                        //             'PriorityNum'=>$ordernumber
                        //             ]);
                        //     }elseif($userStatus!='checked')
                        //     {
                        //         $myturn='';
                        //         update_user_option($rolekey , 'myTurn', $myturn);  
                        //     } 
                        // }elseif ($dataRequestedFloor['select1']=="Nothing") {

                        //     update_user_option($rolekey, 'priorityNum', $ordernumber);
                        //     update_user_option($rolekey, 'userBoothStatus', $userStatus);
                           
                        //     if ($userStatus=='checked' && $ordernumber != '-' ) {
                        //         array_push($arr,(object)[
                        //             'Id' => $rolekey,
                        //             'Status'=> $userStatus,
                        //             'PriorityNum'=>$ordernumber
                        //      ]);
                        //     }elseif($userStatus!='checked')
                        //     {
                        //         $myturn='';
                        //         update_user_option($rolekey , 'myTurn', $myturn);  
                        //     }
                            
                        // }
                        //code by AD


include 'woocommerce-registration-fields.php';
include 'egpl-patches.php';

