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
        $responce = array();

        
        if($requestData['users'] == "checked" || $requestData['users'] == "checked-add"){

            if($requestData['levels'] == 'checked' || $requestData['levels'] == 'checked-add'){

                $responce['users']['msg'] =  'success';
    
            }else{

                $responce['users']['msg'] = $validating->validateusers($requestData);
            
            }
        }

        if($requestData['levels'] == "checked" || $requestData['levels'] == "checked-add"){

            $responce['levels']['msg'] =  'success';
            //$responce['levels']['msg'] = $validating->validatelevels($requestData);
        }

        if($requestData['tasks'] == "checked"){

            if(($requestData['levels'] == 'checked' || $requestData['levels'] == 'checked-add') && ($requestData['users'] == 'checked' || $requestData['users'] == 'checked-add')){

                $responce['tasks']['msg'] =  'success';

            }else{

                $responce['tasks']['msg'] = $validating->validatetasks($requestData);

            }
        
            
        }

        if($requestData['Shop'] == "checked"){

            if(($requestData['levels'] == 'checked' || $requestData['levels'] == 'checked-add') ){

                $responce['Shop']['msg'] =  'success';

            }else{

                $responce['Shop']['msg'] = $validating->validateshop($requestData);

            }

           
        }

        if($requestData['florrplan'] == "checked"){

            if(($requestData['levels'] == 'checked' || $requestData['levels'] == 'checked-add') && ($requestData['users'] == 'checked' || $requestData['users'] == 'checked-add')){

                $responce['florrplan']['msg'] =  'success';

            }else{

                $responce['florrplan']['msg'] = $validating->validatefloorplan($requestData);

            }

            
        }

        if($requestData['userfields'] == "checked"){

           
                $responce['userfields']['msg'] =  'success';

           
            
        }


        if($requestData['resources'] == "checked"){

        
            $responce['resources']['msg'] = 'success';
        }

        if($requestData['eventsettings'] == "checked"){

            
            $responce['eventsettings']['msg'] = 'success'; 

        }

        if($requestData['reports'] == "reports"){

            $responce['reports']['msg'] = 'success';

        }

        if($requestData['menupages'] == "checked"){

        
            $responce['menupages']['msg'] = 'success';
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

            
            $responce['eventsettings'] = $cloningfeatures->cloneeventsettings($requestData['eventsettings']);

        }

        
        if($requestData['reports'] == "checked"){

            $responce['reports'] = $cloningfeatures->clonereports($requestData['reports']);

        }

        
        if($requestData['menupages'] == "checked"){

            
             $responce['menupages'] = $cloningfeatures->clonemenupages($requestData['menupages']);
        }

        
        if($requestData['users'] == "checked" || $requestData['users'] == "checked-add"){

            
            $responce['users'] = $cloningfeatures->cloneusers($requestData['users']);
        }

        if($requestData['levels'] == "checked" || $requestData['levels'] == "checked-add"){

            
            $responce['levels'] = $cloningfeatures->cloneLevel($requestData['levels']);
        }

        if($requestData['tasks'] == "checked"){

            
            $responce['tasks'] = $cloningfeatures->clonetasks($requestData['tasks']);
        }

        if($requestData['resources'] == "checked"){

            
            $responce['resources'] = $cloningfeatures->cloneresources($requestData['resources']);
        }

        if($requestData['Shop'] == "checked"){

            
            $responce['Shop'] = $cloningfeatures->cloneShop($requestData['Shop']);
        }

        if($requestData['florrplan'] == "checked"){

            
            $responce['florrplan'] = $cloningfeatures->cloneflorrplan($requestData['florrplan']);
        }

        if($requestData['userfields'] == "checked"){

            
            $responce['userfields'] = $cloningfeatures->cloneuserfields($requestData['userfields']);
        }
      
        echo json_encode($responce);
        die();



    }catch (Exception $e) {
       
        //contentmanagerlogging_file_upload ($lastInsertId,serialize($e));
        return $e;

      }
    die();


}