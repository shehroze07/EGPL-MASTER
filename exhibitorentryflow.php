<?php
if ($_GET['exhibitorflowrequest'] == 'saveallflowsettings') {
    
    require_once('../../../wp-load.php');
    saveallflowsettings($_POST);
    die();
    
}


function saveallflowsettings($userinfo){
    
     try{
      
        if (current_user_can('administrator') || current_user_can('contentmanager')) {
        
        $key = 'custome_exhibitor_flow_settings_data';
        $settingsdata = get_option($key);
    
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $lastInsertId = contentmanagerlogging('Update Exhibtor Flow',"Admin Action",serialize($userinfo),serialize($settingsdata),$user_ID,$user_info->user_email,"pre_action_data");
        $listoftaks = json_decode(stripslashes($userinfo['exhibitorsavedata']));
        $index = 0;
        $exhibitorEntryLevel = [];
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $applicationmoderationstatus = $userinfo['applicationmoderationstatus'];
        $applicationflowstatus = $userinfo['flowshowstatus'];
        $loginpageID = get_page_by_path('login');
        $landingpageID = get_page_by_path('landing-page');
        
        if ($applicationflowstatus == "checked") {
                 //code by AD
                 $floor_Plan_Settings='floorPlanSettings';
                 $get= get_option($floor_Plan_Settings);
         
                 $get['tableSort']='';
                 update_option($floor_Plan_Settings,$get);
                 //code by AD
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
        
        $oldvalues['ContentManager']['applicationmoderationstatus']=$applicationmoderationstatus;
        update_option('ContenteManager_Settings', $oldvalues);
        
        
        if(!empty($applicationflowstatus)){
        foreach ($listoftaks as $taksKey=>$taskObject){
            
            
            $exhibitorEntryLevel[$index]['url'] = $taskObject->url;
            $exhibitorEntryLevel[$index]['name'] = $taskObject->name;
            $exhibitorEntryLevel[$index]['icon'] = $taskObject->icon;
            $exhibitorEntryLevel[$index]['slug'] = $taskObject->slug;
            $exhibitorEntryLevel[$index]['status'] = $taskObject->status;
            $exhibitorEntryLevel[$index]['statusactive'] = $taskObject->statusactive;
            $exhibitorEntryLevel[$index]['description'] = $taskObject->description;
            $index++;
            
        }
        
        
        
        }
        
        $exhibitorflowstatus['status'] = $applicationflowstatus;
        $exhibitorflowstatusKey = "exhibitorentryflowstatus";
        update_option($exhibitorflowstatusKey, $exhibitorflowstatus);
        
        
        $result = update_option($key, $exhibitorEntryLevel);
        contentmanagerlogging_file_upload ($lastInsertId,serialize($exhibitorEntryLevel));
        $message['success'] = "Settings has been updated successfully.";
        
        echo json_encode($message);
        die();
        
        
        }else{
            
            $redirect = get_site_url();
            wp_redirect($redirect);
            exit;
            
        }
     }catch (Exception $e) {
       
         contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
   
      return $e;
       die();
     }
    
    
    
}

