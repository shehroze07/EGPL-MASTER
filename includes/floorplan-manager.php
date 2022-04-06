<?php
class FloorPlanManager {
    
    
    
    
    
    public function __construct() {
    
        
        
    }
    
    
    public function getAllboothsforthisuser($userID){
        
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
	$CurrentFloorPlanID = $contentmanager_settings['ContentManager']['floorplanactiveid'];
        $FloorplanXml   = get_post_meta( $CurrentFloorPlanID, 'floorplan_xml', true );
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        $counter= 0;
        $xml = json_decode(json_encode($xml), TRUE);
        $listofbooths = "";
        foreach ($xml['root']['MyNode'] as $cellIndex=>$CellValue){
            
            if($cellboothlabelvalue['boothOwner'] == $userID){
                
                
                $listofbooths.=$cellboothlabelvalue['mylabel'].',';
            }
            
            
        }
        
        return rtrim($listofbooths, ',');
        
    }
    
    public function getAllbooths(){
        
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
	$CurrentFloorPlanID = $contentmanager_settings['ContentManager']['floorplanactiveid'];
        $FloorplanXml   = get_post_meta( $CurrentFloorPlanID, 'floorplan_xml', true );
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        $counter= 0;
        $xml = json_decode(json_encode($xml), TRUE);
        
        foreach ($xml['root']['MyNode'] as $cellIndex=>$CellValue){
            
           
            
            $cellboothlabelvalue = $CellValue['@attributes'];
            $GetAllBoothsWithUsers[$counter]['boothNumber']   = $cellboothlabelvalue['mylabel'];
            $GetAllBoothsWithUsers[$counter]['bootheOwnerID'] = $cellboothlabelvalue['boothOwner'];
            $GetAllBoothsWithUsers[$counter]['bootheID'] = $cellboothlabelvalue['id'];
            $counter++;
           
            
        }
        
        return $GetAllBoothsWithUsers;
    }
    
    public function getproductID($boothID){
        
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
	$CurrentFloorPlanID = $contentmanager_settings['ContentManager']['floorplanactiveid'];
        $FloorplanXml   = get_post_meta( $CurrentFloorPlanID, 'floorplan_xml', true );
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        $counter= 0;
        $xml = json_decode(json_encode($xml), TRUE);
        
        foreach ($xml['root']['MyNode'] as $cellIndex=>$CellValue){
            
           
           
            $cellboothlabelvalue = $CellValue['@attributes'];
            if($cellboothlabelvalue['id'] == $boothID){

                $GetAllBoothsWithUsers = $cellboothlabelvalue['boothproductid'];
            }
            
           
            
        }
        if(!empty($GetAllBoothsWithUsers) && $GetAllBoothsWithUsers!=null){
            return $GetAllBoothsWithUsers;
        }else{
            return 'no-product';
        }
    }
    
    
    public function getAllboothswithproducts(){
        
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
	$CurrentFloorPlanID = $contentmanager_settings['ContentManager']['floorplanactiveid'];
        $FloorplanXml   = get_post_meta( $CurrentFloorPlanID, 'floorplan_xml', true );
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
        $counter= 0;
        $xml = json_decode(json_encode($xml), TRUE);
        
        foreach ($xml['root']['MyNode'] as $cellIndex=>$CellValue){
            
           
            
            $cellboothlabelvalue = $CellValue['@attributes'];
            $GetAllBoothsWithUsers[$counter]['boothNumber']   = $cellboothlabelvalue['mylabel'];
            $GetAllBoothsWithUsers[$counter]['bootheOwnerID'] = $cellboothlabelvalue['boothOwner'];
            $GetAllBoothsWithUsers[$counter]['bootheID'] = $cellboothlabelvalue['id'];
            $GetAllBoothsWithUsers[$counter]['boothproductid'] = $cellboothlabelvalue['boothproductid'];
            $counter++;
           
            
        }
        
        return $GetAllBoothsWithUsers;
    }
    
    
    
    
    
    
    function getFloorplanStatus($floorplanID){
        
        
       
        $ViewerLockstatus = get_post_meta( $floorplanID, 'updateboothpurchasestatus', true );
        
        return $ViewerLockstatus;
        
    }
    function getProductstauts($product_ID){
        
        
        $get_BoothCellID = "";
        $getBoothOwner = "none";
        
        
        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
        $FloorpLanid = $contentmanager_settings['ContentManager']['floorplanactiveid'];
        
        $FloorplanXml = get_post_meta( $FloorpLanid, 'floorplan_xml', true );
        $sellboothsjson = json_decode(get_post_meta( $FloorpLanid, 'sellboothsjson', true ));
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml = str_replace('>n"','>',$FloorplanXml);
        
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
       
        
        foreach($sellboothsjson as $boothIndex=>$boothObject){
            
            
            if($boothObject->boothID == $product_ID){
                
                $get_BoothCellID = $boothObject->cellID;
                
                
            }
        }
        
        if(!empty($get_BoothCellID)){
            $currentIndex=0;
            foreach ($xml->root->MyNode as $cellIndex=>$CellValue){
            
            
          
       
                    $new_product_id = "";
                    $cellboothlabelvalue = $CellValue->attributes();
                    $getCellStylevalue = $xml->root->MyNode[$currentIndex]->mxCell->attributes();
                    $boothid = $cellboothlabelvalue['id'];
                    
                   
                    
                    if($boothid == $get_BoothCellID){
                        
                       
                        $getBoothOwner = $cellboothlabelvalue['boothOwner'];
                        break;
                       
                    }
                    $currentIndex++;
                    
                    
            }
            
            
            
        }
        
        $data['BoothID'] = $get_BoothCellID;
        $data['BoothOwner'] = $getBoothOwner;
        
        return $data;
        
    }
    
    
    function createAllBoothPorducts($floorplanID,$requestBoothsproductArray, $UpdatedFloorplanXml,$productpicID){
    
    
    try{
	
       
        
      
        
        $user_ID = get_current_user_id();
        $user_info = get_userdata($user_ID);  
        $FloorplanXml = stripslashes($UpdatedFloorplanXml);
        $requestBoothsproductArray = $requestBoothsproductArray;
        $lastInsertId = floorplan_contentmanagerlogging('AutoGenrate Booth Products Request',"Admin Action",unserialize($FloorplanXml),$user_ID,$user_info->user_email,"");
      
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml= str_replace('>n"','>',$FloorplanXml);
     
        $requestBoothsproductArray = json_decode(stripslashes($requestBoothsproductArray));
        $xml=simplexml_load_string($FloorplanXml) or die("Error: Cannot create object");
       
       
        $currentIndex = 0;
        $att = "boothproductid";
        $NewProductArrayIndex = 0;
        $NewProductArray = [];
        
        
          
       
        
        foreach ($xml->root->MyNode as $cellIndex=>$CellValue){
            
            
          
       
                    $new_product_id = "";
                    $cellboothlabelvalue = $CellValue->attributes();
                    $getCellStylevalue = $xml->root->MyNode[$currentIndex]->mxCell->attributes();
                    $boothid = $cellboothlabelvalue['id'];
                    $boothtitlebooth = $cellboothlabelvalue['mylabel'];
                    
                    
                    foreach ($requestBoothsproductArray as $boothIndex=>$boothObject){
                    
                        
                    
                    $newRequestBoothArray = new stdClass;
                        
                    if($boothObject->cellID == $boothid){
                        
                       
                        
                        $newRequestBoothArray->cellID=$boothObject->cellID;;
                        $newRequestBoothArray->boothdescripition=$boothObject->boothdescripition;
                        $newRequestBoothArray->boothprice=$boothObject->boothprice;
                        $newRequestBoothArray->overRideCheck=$boothObject->overRideCheck;
                        $newRequestBoothArray->reservedStatus=$boothObject->reservedStatus;
                        $newRequestBoothArray->userBooths=$boothObject->userBooths;
                        $newRequestBoothArray->userBoothsLevel=$boothObject->userBoothsLevel;
                        $newRequestBoothArray->boothlevel=$boothObject->boothlevel;
                        $newRequestBoothArray->boothtitle=$boothObject->boothtitle;
                        $newRequestBoothArray->depositstype=$boothObject->depositstype;
                        $newRequestBoothArray->depositsamount=$boothObject->depositsamount;
                        $newRequestBoothArray->depositestatus=$boothObject->depositestatus;
                        $newRequestBoothArray->despositeenablestatus=$boothObject->despositeenablestatus;
                      
                         
                        
                        if($boothObject->boothstatus == "newBooth"){
                
                            $objProduct = new WC_Product();
                            $objProduct->set_slug($boothObject->cellID);
                            $objProduct->set_name($boothtitlebooth); 

                            $objProduct->set_status('publish'); //Set product status.
                            $objProduct->set_featured(TRUE); //Set if the product is featured.                          | bool
                            $objProduct->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
                            $objProduct->set_description($boothObject->boothdescripition); //Set product description.
                            $objProduct->set_short_description($boothObject->boothdescripition); //Set product short description.

                            $objProduct->set_price($boothObject->boothprice); //Set the product's active price.
                            $objProduct->set_regular_price($boothObject->boothprice); //Set the product's regular price.

                            $objProduct->set_manage_stock(TRUE); //Set if product manage stock.                         | bool
                            $objProduct->set_stock_quantity(1); //Set number of items available for sale.
                            $objProduct->set_stock_status('instock'); //Set stock status.                               | string $status 'instock', 'outofstock' and 'onbackorder'
                            $objProduct->set_backorders('no'); //Set backorders.                                        | string $backorders Options: 'yes', 'no' or 'notify'.
                            $objProduct->set_sold_individually(FALSE);
                           // $objProduct->set_tax_class(); 
                            $objProduct->set_image_id($productpicID); //Set main image ID.
                            //  $objProduct->set_menu_order($menu_order); 
                            $objProduct->update_meta_data('productlevel', $boothObject->boothlevel);
                            $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                            $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                            $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                            $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)
                            $objProduct->set_reviews_allowed(TRUE); //Set if reviews is allowed.                        | bool
                            
                           
                           if($boothObject->despositeenablestatus == "optional" || $boothObject->despositeenablestatus == "forced"){
                                
                               
                               
                               
                                $objProduct->update_meta_data('_wc_deposit_type', $boothObject->depositstype);
                                $objProduct->update_meta_data('_wc_deposit_amount', $boothObject->depositsamount);
                                $objProduct->update_meta_data('_wc_deposit_enabled', $boothObject->despositeenablestatus);
                                $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                                $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                                $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                                $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)

                                
                            }else{
                                
                                $objProduct->update_meta_data('_wc_deposit_type', "");
                                $objProduct->update_meta_data('_wc_deposit_amount', "");
                                $objProduct->update_meta_data('_wc_deposit_enabled', "");
                                $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                                $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                                $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                                $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)

                                
                                
                            }
                            
                            $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.
                            $newRequestBoothArray->boothstatus = 'updated';
                            $newRequestBoothArray->boothID = $new_product_id;
                            $NewProductArray[$NewProductArrayIndex] = $newRequestBoothArray;
                            // echo "<pre>";
                            // print_r($newRequestBoothArray);
      
                            $NewProductArrayIndex++;

                        }else if($boothObject->boothstatus == "updated"){
   
                            if(!empty($boothObject->boothID)){  
                            $objProduct = wc_get_product( $boothObject->boothID ); 
                            if(!empty($objProduct)){
                            
                                $objProduct->set_name($boothtitlebooth); 
                           // $objProduct->set_stock_quantity(1); //Set number of items available for sale.
                            $objProduct->set_description($boothObject->boothdescripition); //Set product description.
                            $objProduct->set_short_description($boothObject->boothdescripition); //Set product short description.
                            $objProduct->set_price($boothObject->boothprice); //Set the product's active price.
                            $objProduct->set_regular_price($boothObject->boothprice); //Set the product's regular price.
                            $objProduct->update_meta_data('productlevel', $boothObject->boothlevel);
                            $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                            $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                            $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                            $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)

                            $objProduct->set_tax_class($boothObject->boothlevel); 
                            $objProduct->set_image_id($productpicID); //Set main image ID.
                            
                             if($boothObject->despositeenablestatus == "optional" || $boothObject->despositeenablestatus == "forced"){
                              
                                $objProduct->update_meta_data('_wc_deposit_type', $boothObject->depositstype);
                                $objProduct->update_meta_data('_wc_deposit_amount', $boothObject->depositsamount);
                                $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                                $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                                $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                                $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)

                                $objProduct->update_meta_data('_wc_deposit_enabled', $boothObject->despositeenablestatus);
                                
                            }else{
                                
                                $objProduct->update_meta_data('_wc_deposit_type', "");
                                $objProduct->update_meta_data('_wc_deposit_amount', "");
                                $objProduct->update_meta_data('_wc_deposit_enabled', "");   
                                $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                                $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                                $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                                $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)

                            }
 
                            $new_product_id = $objProduct->save();
                            $newRequestBoothArray->boothstatus = 'updated';
                            $newRequestBoothArray->boothID = $new_product_id;
                            $NewProductArray[$NewProductArrayIndex] = $newRequestBoothArray;
                            // echo "<pre>";
                            // print_r($newRequestBoothArray);
                            $NewProductArrayIndex++;
                            }else{
                                
                                
                            $objProduct = new WC_Product();
                            $objProduct->set_slug($boothObject->cellID);
                            $objProduct->set_name($boothObject->boothtitle); 

                            $objProduct->set_status('publish'); //Set product status.
                            $objProduct->set_featured(TRUE); //Set if the product is featured.                          | bool
                            $objProduct->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
                            $objProduct->set_description($boothObject->boothdescripition); //Set product description.
                            $objProduct->set_short_description($boothObject->boothdescripition); //Set product short description.

                            $objProduct->set_price($boothObject->boothprice); //Set the product's active price.
                            $objProduct->set_regular_price($boothObject->boothprice); //Set the product's regular price.

                            $objProduct->set_manage_stock(TRUE); //Set if product manage stock.                         | bool
                            $objProduct->set_stock_quantity(1); //Set number of items available for sale.
                            $objProduct->set_stock_status('instock'); //Set stock status.                               | string $status 'instock', 'outofstock' and 'onbackorder'
                            $objProduct->set_backorders('no'); //Set backorders.                                        | string $backorders Options: 'yes', 'no' or 'notify'.
                            $objProduct->set_sold_individually(FALSE);
                           // $objProduct->set_tax_class(); 
                            $objProduct->set_image_id($productpicID); //Set main image ID.
                            //  $objProduct->set_menu_order($menu_order); 
                            $objProduct->update_meta_data('productlevel', $boothObject->boothlevel);
                            $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                            $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                            $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                            $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)

                            $objProduct->set_reviews_allowed(TRUE); //Set if reviews is allowed.                        | bool
                            
                           
                           if($boothObject->despositeenablestatus == "optional" || $boothObject->despositeenablestatus == "forced"){
                              
                                $objProduct->update_meta_data('_wc_deposit_type', $boothObject->depositstype);
                                $objProduct->update_meta_data('_wc_deposit_amount', $boothObject->depositsamount);
                                $objProduct->update_meta_data('_wc_deposit_enabled', $boothObject->despositeenablestatus);
                                $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                                $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                                $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                                $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)

                                
                            }else{
                                
                                $objProduct->update_meta_data('_wc_deposit_type', "");
                                $objProduct->update_meta_data('_wc_deposit_amount', "");
                                $objProduct->update_meta_data('_wc_deposit_enabled', "");
                                $objProduct->update_meta_data('overrideCheck', $boothObject->overRideCheck);//Set the Override Level Change Check(Code By AD)
                                $objProduct->update_meta_data('reservedStatus', $boothObject->reservedStatus);//Set the Reserved Booth Check(Code By AD)
                                $objProduct->update_meta_data('BoothForUser', $boothObject->userBooths);//Set the User For Booth(Code By AD)
                                $objProduct->update_meta_data('LevelOfBooth', $boothObject->userBoothsLevel);//Set the Level of Booth(Code By AD)

                                
                                
                            }
                            
                            $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.
                            $newRequestBoothArray->boothstatus = 'updated';
                            $newRequestBoothArray->boothID = $new_product_id;
                            $NewProductArray[$NewProductArrayIndex] = $newRequestBoothArray;
                            // echo "<pre>";
                            // print_r($newRequestBoothArray);
                            $NewProductArrayIndex++;
                                
                                
                                
                            }}

                        }else if($boothObject->boothstatus == "deleterequest"){

                            wp_delete_post( $boothObject->boothID, true );
                            $new_product_id = "";

                        }
                
                        if($boothid == $boothObject->cellID){
                             $xml->root->MyNode[$currentIndex]->attributes()->$att = $new_product_id;  
                        }
                     }
                     
                    }
                     
                    $currentIndex++;
                   
                }
        
      
      
                // echo "<pre>";
                // print_r($NewProductArray);
        if(!empty($NewProductArray)){
            
            
            $NewProductArray = json_encode($NewProductArray);
            
        }else{
            
            $NewProductArray = "";
        }
        update_post_meta( $floorplanID, 'sellboothsjson', $NewProductArray );
        
        
        $FloorplanXml = str_replace('<?xml version="1.0"?>',"",$xml->asXML());
        
        $FloorplanXml = str_replace('"n<','<',$FloorplanXml);
        $FloorplanXml = str_replace('>n"','>',$FloorplanXml);
        
        update_post_meta( $floorplanID, 'floorplan_xml', json_encode($FloorplanXml));
        
       
        
        contentmanagerlogging_file_upload ($lastInsertId,serialize($FloorplanXml));
     
        echo 'createdAllboothsProducts';
     die();
        
        
        
        
        
    }catch (Exception $e) {
       
     
        return $e;
        
    }
  }

}