<?php

if ($_GET['contentManagerRequest'] == 'cloningfeature') {
    
    require_once('../../../wp-load.php');
    cloningfeature($_POST);
    die();
    
}else if ($_GET['contentManagerRequest'] == 'datavalidateurl') {
    
    require_once('../../../wp-load.php');
    datavalidateurl($_POST);
    die();
    
}

function datavalidateurl($requestData){

    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/cloningfeatures-manager.php';

    try{

        $requestData = json_decode(stripslashes($requestData['cloningfeatureslist']), true);
        $validating = new ClonefeatureManager($requestData['clonesiteid']);
        $responce = [];

        
        if($requestData['users'] == "checked"){

            if($requestData['levelsstatus'] == 'checked'){

                $responce['users']['msg'] =  'success';
    
            }else{

                $responce['users']['msg'] = $validating->validateusers($requestData);
            
            }
        }

        if($requestData['levels'] == "checked"){

        
            $responce['levels']['msg'] = $validating->validatelevels($requestData);
        }

        if($requestData['tasks'] == "checked"){

        
            $responce['tasks']['msg'] = $validating->validatetasks($requestData);
        }

        if($requestData['Shop'] == "checked"){

        
            $responce['Shop']['msg'] = $validating->validateshop($requestData);
        }

        if($requestData['florrplan'] == "checked"){

        
            $responce['florrplan']['msg'] = $validating->validatefloorplan($requestData);
        }

        if($requestData['userfields'] == "checked"){

        
            $responce['userfields']['msg'] = $validating->validateuserfields($requestData);
        }



        if($requestData['resources'] == "checked"){

        
            $responce['resources']['msg'] = 'suceess';
        }

        if($requestData['eventsettings'] == "checked"){

            
            $responce['eventsettings']['msg'] = 'success'; 

        }

        if($requestData['reports'] == "reports"){

            $responce['reports']['msg'] = 'suceess';

        }

        if($requestData['menupages'] == "checked"){

        
            $responce['menupages']['msg'] = 'suceess';
        }

       echo json_encode($responce);
       die();


    }catch (Exception $e) {
        
        //contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
        return $e;

    }
    die();

}

function cloningfeature($requestData){

   
    require_once plugin_dir_path( __DIR__ ) . 'EGPL/includes/cloningfeatures-manager.php';

    try{


        $requestData = json_decode(stripslashes($requestData['cloningfeatureslist']), true);

       


        $cloningfeatures = new ClonefeatureManager($requestData['clonesiteid']);

        $responce = [];
        if($requestData['eventsettings'] == "checked"){

            
            $responce['eventsettings'] = $cloningfeatures->cloneeventsettings($requestData['eventsettingsstatus']);

        }

        
        if($requestData['reports'] == "reports"){

            $responce['reports'] = $cloningfeatures->clonereports($requestData['reportsstatus']);

        }

        
        if($requestData['menupages'] == "checked"){

            
             $responce['menupages'] = $cloningfeatures->clonemenupages($requestData['menupagesstatus']);
        }

        
        if($requestData['users'] == "checked"){

            
            $responce['users'] = $cloningfeatures->cloneusers($requestData['usersstatus']);
        }

        if($requestData['levels'] == "checked"){

            
            $responce['levels'] = $cloningfeatures->cloneLevel($requestData['levelsstatus']);
        }

        if($requestData['tasks'] == "checked"){

            
            $responce['tasks'] = $cloningfeatures->clonetasks($requestData['tasksstatus']);
        }

        if($requestData['resources'] == "checked"){

            
            $responce['resources'] = $cloningfeatures->cloneresources($requestData['resourcesstatus']);
        }

        if($requestData['Shop'] == "checked"){

            
            $responce['Shop'] = $cloningfeatures->cloneShop($requestData['Shopstatus']);
        }

        if($requestData['florrplan'] == "checked"){

            
            $responce['florrplan'] = $cloningfeatures->cloneflorrplan($requestData['florrplanstatus']);
        }

        if($requestData['userfields'] == "checked"){

            
            $responce['userfields'] = $cloningfeatures->cloneuserfields($requestData['userfieldsstatus']);
        }
      
        print_r($responce);



    }catch (Exception $e) {
       
        //contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
        return $e;

      }
    die();


}