


<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
    require_once plugin_dir_path( __DIR__ ) . '/includes/egpl-custome-functions.php';
    require_once plugin_dir_path( __DIR__ ) . '/includes/floorplan-manager.php';
   
		
      if(!empty($_GET['sponsorid'])){
          $sponsor_id=$_GET['sponsorid'];
          $meta_for_user = get_userdata( $sponsor_id );
          $all_meta_for_user = get_user_meta($sponsor_id );
          $boothproudctList = new FloorPlanManager();
         
          $Boothproducts = $boothproudctList->getAllboothswithproducts();
          $sponsor_id = $_GET['sponsorid'];
          $blog_id = get_current_blog_id();
          $listofboothsID = get_user_meta( $sponsor_id,'wp_'.$blog_id.'_customefield_booth_numbers_87hg5');
          $Oveeridedata = get_user_meta( $sponsor_id,'wp_'.$blog_id.'_OverrideNumberOfBooths');
        //   $OveeridePrePaid = get_user_meta( $sponsor_id,'wp_'.$blog_id.'_customefield_booth_pre_paid');
          $OverrideCheck = get_user_meta( $sponsor_id,'wp_'.$blog_id.'_Override_Check');
          $OveeridePrePaid = get_user_meta( $sponsor_id,'wp_'.$blog_id.'_prePaid_checkbox');
          if($OverrideCheck[0] != 'checked')
          {       
              $display="none";
          }
        //   echo $Oveeridedata;
        //     echo '<pre>';
        //   print_r( $all_meta_for_user );exit;
        //   print_r( $listofboothsID );exit;
        //   echo '<pre>';
           //print_r( $Boothproducts );exit;
          
      }
       $settitng_key='ContenteManager_Settings';
       $sponsor_info = get_option($settitng_key);
       $additional_fields_settings_key = 'EGPL_Settings_Additionalfield';
       $additional_fields = get_option($additional_fields_settings_key);
       $sponsor_name = $sponsor_info['ContentManager']['sponsor-name'];
       $welcomeemail_template_info_key='AR_Contentmanager_Email_Template_welcome';
       $welcomeemail_template_info = get_option($welcomeemail_template_info_key);
       $floor_Plan_Settings='floorPlanSettings';
    //    $get= get_option($floor_Plan_Settings);
    //    $allowedBooth=$get['usersNum'];
    //    echo $allowedBooth;
    //    echo '<pre>';
    //        print_r( $additional_fields );
    $args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => 'egpl_custome_tasks',
        'post_status'      => 'draft',
        
        );
        $listOFtaskArray = get_posts( $args );
       $GetAllcustomefields = new EGPLCustomeFunctions();
       
       $additional_fields = $GetAllcustomefields->getAllcustomefields();
       $demo = new FloorPlanManager();
       $AllBoothsList = $demo->getAllbooths();
    //    $thisBoothNumber ="";
            
    //    if(!empty($AllBoothsList)){
       
    //             foreach ($AllBoothsList as $boothIndex=>$boothValue ){
                    
    //                 if($boothValue['bootheOwnerID'] == $sponsor_id){ 
    //                     $thisBoothNumber .= $boothValue['boothNumber'].',';    
    //                 }
                
    //             }
    //    }
    //    echo "<pre>";
    //    print_r($thisBoothNumber);exit;
        function sortByOrder($a, $b) {
            return $a['fieldIndex'] - $b['fieldIndex'];
        }

        usort($additional_fields, 'sortByOrder');
       
       
        global $wp_roles;

  //  $all_roles = $wp_roles->roles;
        
        
       // echo '<pre>';
      //  print_r($all_roles);exit;
        
    if (is_multisite()) {
                $blog_id = get_current_blog_id();
                $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                $site_prefix = 'wp_'.$blog_id.'_';
            }else{
                $get_all_roles_array = 'wp_user_roles';
            }
     $all_roles = get_option($get_all_roles_array);
       $blog_id =get_current_blog_id();
       if (is_multisite()) {
           $getroledata = unserialize($all_meta_for_user['wp_'.$blog_id.'_capabilities'][0]);
           reset($getroledata);
           $rolename = key($getroledata);
       }else{
         $rolename = $meta_for_user->roles[0];  
       }
      
     //  echo $rolename;exit;
       include 'cm_header.php';
       include 'cm_left_menu_bar.php';
                ?>
 <style>
.switch {
  position: absolute;
  margin-left:409px;
  display: inline-block;
  width: 42px;
  height: 20px; 
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 15px;
  left: 0px;

  bottom: 1px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.select2-search__field{

width: 200px !important;
}
                </style>
          
                
       <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Edit User</h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            <select id="hiddenlistemaillist" style="display: none;">
                
                <?php  foreach ($welcomeemail_template_info as $key=>$value) { 
                                            
                                            $template_name = ucwords(str_replace('_', ' ', $key));
                                            if($key == "welcome_email_template"){
                                                 echo  '<option value="' . $key . '" selected="selected">Defult Welcome Email</option>';
                                            }else{
                                                 echo  '<option value="' . $key . '" >'.$template_name.'</option>';
                                            }
                                          
                                         }
                ?>
                                     
                
            </select>
            <div class="box-typical box-typical-padding">
                <p>
                You can edit the selected user here and change their password. </p>

                <br>
                <br>

              <form method="post" action="javascript:void(0);" onSubmit="update_sponsor()">
                    
                  <section class="tabs-section">
				<div class="tabs-section-nav tabs-section-nav-icons">
					<div class="tbl">
						<ul class="nav" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" href="#tabs-1-tab-1" role="tab" data-toggle="tab">
									<span class="nav-link-in">
										<i class="fa fa-info-circle" ></i>
										User Details
									</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#tabs-1-tab-2" role="tab" data-toggle="tab">
									<span class="nav-link-in">
										<span class="fa fa-list-alt"></span>
										Additional Information
									</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#tabs-1-tab-3" id="tab-3" role="tab" data-toggle="tab">
									<span class="nav-link-in">
										<span class="fa fa-list-alt"></span>
										Booth Details
									</span>
								</a>
							</li>
							
						</ul>
					</div>
				</div><!--.tabs-section-nav-->

				<div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="tabs-1-tab-1">  
                                        <input type="hidden" name="sponsorid" id="sponsorid" value="<?php echo $sponsor_id;?>" >
                                         <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>Email *</label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="email"  class="form-control mymetakey" value="<?php echo $meta_for_user->user_email;?>" id="Semail" name="Semail" placeholder="Email" required='true'>
                                            </div>
                                            <div class="col-sm-5">
                                        
                                               <a    class="btn btn-inline mycustomwidth btn-success" onclick="changeuseremailaddressalert()">Change Email</a>
                                            </div> 
                                        </div>  
                                        
                                        
                                        <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>First Name *</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input value="<?php echo $all_meta_for_user[$site_prefix."first_name"][0];?>" type="text"  class="form-control mymetakey" id="first_name" name="first_name" placeholder="First Name" required='true'>
                                            </div>
                                           
                                        </div>  
                                        
                                        <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>Last Name *</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input value="<?php echo $all_meta_for_user[$site_prefix."last_name"][0];?>" type="text"  class="form-control mymetakey" id="last_name" name="last_name" placeholder="Last Name" required='true'>
                                            </div>
                                           
                                        </div> 
                                        
                                        
                                        <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>Level *</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <select class="select2 mycustomedropdown"  title="Role" id="Role" data-allow-clear="true" data-toggle="tooltip" required='true' >
                                                    
                                                    
                                                    
                                                    <?php  if($rolename == 'contentmanager'){?>
                                                         <option value='contentmanager' selected="true">Content Manager</option>
                                                       <?php }else{
                                                        foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator' && $key != 'contentmanager') {
                                                            
                                                            if($rolename == $key){
                                                        ?>
                                                  
                                                        <option value='<?php echo $key; ?>' selected="true"><?php echo $name['name'];?></option>
                                                            <?php }else{?>
                                                        <option value='<?php echo $key; ?>' ><?php echo $name['name'];?></option>
                                                        <? }}}     } ?>  
                                                       
                                                            
                                                   
                                                </select>
                                            </div>
                                           
                                        </div> 
                                        
                                        <div class="form-group row" >
                                            <div class="col-sm-2">
                                                <label>Company Name *</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <input value="<?php echo $all_meta_for_user[$site_prefix."company_name"][0];?>" type="text"  class="form-control mymetakey" id="company_name" name="company_name" placeholder="Company Name" required='ture'>
                                            </div>
                                           
                                        </div> 
                                        
                                     <?php   foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] == "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked"){
                                $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                $requriedStatus = "";
                                $requiredStatueUpdate = "";
                                if($requiredStatus == true){
                                    
                                    
                                    $requiredStatueUpdate = "required='ture'";
                                    $requriedStatus = "*";
                                    
                                }
                                ?>
                                
                                <?php if($additional_fields[$key]['fielduniquekey'] !="Semail" && $additional_fields[$key]['fielduniquekey'] !="first_name" && $additional_fields[$key]['fielduniquekey'] !="last_name" && $additional_fields[$key]['fielduniquekey'] !="Role" && $additional_fields[$key]['fielduniquekey'] !="company_name" && $additional_fields[$key]['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display'){ ?>
                               
                                    <div class="form-group row" >
                                    <div class="col-sm-2">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatus;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                    
                                    <?php if($additional_fields[$key]['fieldType'] == 'text' || $additional_fields[$key]['fieldType'] == 'email' || $additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number'){ ?> 
                                        
                                        <?php if($additional_fields[$key]['fieldType'] == 'email'){?>
                                    
                                            <div class="col-sm-5">
                                                <input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" value="<?php echo $meta_for_user->user_email;?>" readonly placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            </div>
                                            <div class="col-sm-5">
                                        
                                                 <a    class="btn btn-inline mycustomwidth btn-success" onclick="changeuseremailaddressalert()">Change Email</a>
                                        
                                             </div>
                                    
                                        <?php }else{?>
                                        <div class="col-sm-10">
					<input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" value="<?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?>" <?php echo $requiredStatueUpdate;?>>
                                        </div>
                                        <?php }}else if($additional_fields[$key]['fieldType'] == 'textarea'){?>
                                             <div class="col-sm-10">
                                             <textarea   <?php echo $additional_fields[$key]['attribute'];?> class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>><?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?></textarea>
                                             </div>
                                        
                                        
                                       
                                       <?php }else if($additional_fields[$key]['fieldType'] == 'url'){?>
                                        
                                              <div class="col-sm-10">
                                                <input type="text"  class="form-control speiclurlfield" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                              </div>
                                       
                                        <?php }else if($additional_fields[$key]['fieldType'] == 'dropdown'){?>
                                             
                                             <div class="col-sm-10">
                                             <?php if($additional_fields[$key]['multiselect'] == 'chekced') {?>
                                              <select class="select2 mycustomedropdown "  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                    <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator' && $key != 'subscriber') {
                                                        ?>
                                                  
                                                         <option value='<?php echo$key; ?>'><?php echo $name['name'];?></option>
                                                    
                                                    <? }} ?>
                                                   
                                              </select>
                                             <?php }else {?>
                                                    
                                                    <select class="select2 mycustomedropdown "  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" <?php echo $requiredStatueUpdate;?>>

                                                       <?php  foreach ($all_roles as $key => $name) { 
                                                        
                                                        
                                                        if ($key != 'administrator') {
                                                            
                                                           // echo $rolename;exit;
                                                            if($rolename == $key){
                                                        ?>
                                                        <option value='<?php echo $key;?>' selected="true"><?php echo $name['name'];?></option>
                                                        <? }else{if ($key != 'administrator' && $key != 'contentmanager') {?>
                                                        <option value='<?php echo $key;?>' ><?php echo $name['name'];?></option>
                                                        <?php }}}} ?>

                                                   </select>
                                             
                                             <?php } ?>
                                             </div>
                                       <?php }?>
                                    
                                    
                                        </div> <?php }else if($additional_fields[$key]['fieldType'] == 'url'){ ?>
                                           
                                           
                                           <div class="form-group row" >
                                            <div class="col-sm-4">
                                            <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                            <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>

                                              <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                            <?php }?>
                                            </label>
                                            <?php if(!empty($additional_fields[$key]['fielddescription'])){?>

                                            <?php echo $additional_fields[$key]['fielddescription'];?>
                                            <?php }?>
                                            </div>
                                            <div class="col-sm-8">
                                            <input type="text"  class="form-control speiclurlfield" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                            </div>
                                            </div>
                                           
                                           
                                           
                                       <?php } ?> 
                                       <?php if($additional_fields[$key]['fieldType'] == 'checkbox'){ 
                                           
                                           $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                            $requriedStatus = "";
                                            $requiredStatueUpdate = "";
                                            if($requiredStatus == true){


                                                $requiredStatueUpdate = "required='ture'";
                                                $requriedStatus = "*";

                                            }
                                           
                                           
                                           
                                           ?>
                                             <div class="form-group row" >
                                                
                                                <div class="col-sm-12">
                                                     <?php if($all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0] == "Checked"){?>
                                                     <input  class="mycustomcheckbox" <?php echo $requiredStatueUpdate;?> checked="true" type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                                     <?php }else{?>
                                                     <input  class="mycustomcheckbox" <?php echo $requiredStatueUpdate;?>  type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                                     <?php }?>
                                                </div>
                                                </div>      
                                               
                                            
                                       <?}?>
                                       <?php if($additional_fields[$key]['fieldType'] == 'display'){ ?>
                                             <div class="form-group row" >
                                                  <div class="col-sm-2">
                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                      <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                    <?php }?>
                                    </label>
                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                    
                                    <?php //echo $additional_fields[$key]['fielddescription'];?>
                                    <?php }?>
                                    </div>
                                                 <div class="col-sm-10">
                                                     
                                                     <?php echo $additional_fields[$key]['fielddescription'];?>
                                                </div>
                                               </div>  
                                       <?}?>
                                    
                                  
                           
                                <?php }} ?>
              
                                   
                                        
                             
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label">Change Password <p>(if u want to change or reset password)</p></label>
                                    <div class="col-sm-10">
                                          
                            <input type="password"  class="form-control mymetakey" id="password" name="password" placeholder="Password" value="" >
                            
                                            </div>
                                        </div>
                                        
                              
                       
                              
                                   
                     </div>                  
                      <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-2">
                          
                           
                             <?php   foreach ($additional_fields as $key=>$value){ 
                           
                                if($additional_fields[$key]['fieldsystemtask'] != "checked" && $additional_fields[$key]['SystemfieldInternal'] != "checked"){ 
                                    if( ['fieldType'] != 'checkbox' && $additional_fields[$key]['fieldType'] != 'display'){ 
                                        $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                        $requriedStatussysomb = "";
                                        $requiredStatueUpdate = "";
                                        $showstatus = "";
                                            if($requiredStatus == true)
                                            {
                                                $requiredStatueUpdate = "required='ture'";
                                                $requriedStatussysomb = "*";
                                                $showstatus = true; 
                                            }  
                                            ?>
                               
                                   
                                            <?php 
                                            
                                                if(($additional_fields[$key]['fieldType'] == 'text' || $additional_fields[$key]['fieldType'] == 'email' || $additional_fields[$key]['fieldType'] == 'date' ||$additional_fields[$key]['fieldType'] == 'number')&&($additional_fields[$key]['BoothSettingsField'] != 'checked')){ ?> 
                                                    <div class="form-group row" >
                                                    <div class="col-sm-4">
                                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                                    <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                                    <?php }?>
                                                    </label>
                                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                                        <?php }?>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                                            <input type="<?php echo $additional_fields[$key]['fieldType'];?>"  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" value="<?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                                        </div></div>
                                                    <?php 
                                                }else if(($additional_fields[$key]['fieldType'] == 'url')&&($additional_fields[$key]['BoothSettingsField'] != 'checked')){?>
                                                        <div class="form-group row" >
                                                        <div class="col-sm-4">
                                                        <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                                        <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                    
                                                        <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                                        <?php }?>
                                                        </label>
                                                        <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                                        <?php echo $additional_fields[$key]['fielddescription'];?>
                                                        <?php }?>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                                            <input type="text"  class="form-control speiclurlfield" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" value="<?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>>
                                                         </div>
                                                        
                                                    </div>
                          
                                                   <?php 
                                                }else if(($additional_fields[$key]['fieldType'] == 'textarea')&&($additional_fields[$key]['BoothSettingsField'] != 'checked')){?>
                                                    <div class="form-group row" >
                                                    <div class="col-sm-4">
                                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                                        <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                                    <?php }?>
                                                    </label>
                                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                                    <?php }?>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                                        <textarea   <?php echo $additional_fields[$key]['attribute'];?>  class="form-control mymetakey" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" placeholder="<?php echo $additional_fields[$key]['fieldplaceholder'];?>" <?php echo $requiredStatueUpdate;?>><?php echo $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0];?></textarea>

                                                    </div></div>
                                             
                                                  <?php
   
                                                }else if(($additional_fields[$key]['fieldType'] == 'file')&&($additional_fields[$key]['BoothSettingsField'] != 'checked')){?>
                                           
                                                            <div class="form-group row" >
                                                    <div class="col-sm-4">
                                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                                    
                                                    <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                                    <?php }?>
                                                    </label>
                                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                                    
                                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                                    <?php }?>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                                        <input type="hidden" name="specialattributes" id="<?php echo $additional_fields[$key]['fieldID'];?>_specialattributes" value="<?php echo $additional_fields[$key]['attribute'];?>" >
                                                
                                                        <?php   if(!empty($all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0])){
                                                            
                                                            echo '<div id="'.$additional_fields[$key]['fieldID'].'_fileuploadholder"></div><div id="'.$additional_fields[$key]['fieldID'].'_fileuploadpic"><div class="col-sm-5"><img width="200" id="'.$additional_fields[$key]['fieldID'].'_fileuploadpicviewer"  name="userprofilepic" src="'.$all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0].'" ></div><div class="col-sm-4"><a width="200" id="'.$additional_fields[$key]['fieldID'].'_fileuploadbutton" class="btn btn-inline mycustomwidth btn-success" name="'.$additional_fields[$key]['fielduniquekey'].'" onclick="showprofilefieldupload(this)" >Edit</a></div></div>';
                                                            
                                                        }else{
                                                            
                                                            echo '<input '.$additional_fields[$key]['attribute'].' type="'.$additional_fields[$key]['fieldType'].'"  class="form-control '.$additional_fields[$key]['fieldID'].'_fileupload"" id="'.$additional_fields[$key]['fielduniquekey'].'" name="customefiels[]" placeholder="'.$additional_fields[$key]['fieldplaceholder'].'"'.$requiredStatueUpdate.'>';
                                                            
                                                            
                                                        }
                                                        
                                                        ?>
                                                            </div></div>
                                                        
                                                    <?php 
                                                    
                                                }
                                       
                                       
                                       
                                                else if(($additional_fields[$key]['fieldType'] == 'dropdown')&&($additional_fields[$key]['BoothSettingsField'] != 'checked')){?>
                                             
                                                            <div class="form-group row" >
                                                    <div class="col-sm-4">
                                                    <label><?php echo $additional_fields[$key]['fieldName'].' '.$requriedStatussysomb;?>
                                                    <?php if(!empty($additional_fields[$key]['fieldtooltiptext'])){?>
                                                    
                                                    <i style="cursor: pointer;" title="<?php echo $additional_fields[$key]['fieldtooltiptext'];?>" class="reporticon font-icon fa fa-question-circle"></i>
                                                    <?php }?>
                                                    </label>
                                                    <?php if(!empty($additional_fields[$key]['fielddescription'])){?>
                                                    
                                                    <?php echo $additional_fields[$key]['fielddescription'];?>
                                                    <?php }?>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="hidden" name="requiredstatus" id="<?php echo $additional_fields[$key]['fieldID'];?>_requiredstatus" value="<?php echo $showstatus;?>" >
                                                
                                                            <?php if($additional_fields[$key]['multiselect'] == "checked") {
                                                                
                                                                $userValuesArray = explode(",",$all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0]);
                                                                
                                                                
                                                                
                                                                ?>
                                                            <select class="select2 mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" <?php echo $requiredStatueUpdate;?>>
                                                                    <?php foreach ($additional_fields[$key]['options'] as $key=>$value){ 
                                                                        
                                                                        
                                                                        if (in_array($value->label, $userValuesArray)){
                                                                        
                                                                        
                                                                        ?>
                                                                
                                                                <option value='<?php echo $value->label;?>' selected="true"><?php echo $value->label;?></option>
                                                                    
                                                                    <? }else{?>
                                                                        
                                                                        
                                                                        <option value='<?php echo $value->label;?>' ><?php echo $value->label;?></option>
                                                                        
                                                                <?php }} ?>
                                                                
                                                            </select>
                                                            <?php }else {
                                                                    ?>
                                                                    <select class="select2 mycustomedropdown mymetakey"  title="<?php echo $additional_fields[$key]['fielduniquekey'];?>" name="<?php echo $additional_fields[$key]['fielduniquekey'];?>" data-allow-clear="true" <?php echo $requiredStatueUpdate;?>>

                                                                    <?php foreach ($additional_fields[$key]['options'] as $keysoptions=>$additionalvalue){ 
                                                                        
                                                                        if($additionalvalue->label == $all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0]){?>
                                                                            
                                                                        <option value='<?php echo $additionalvalue->label;?>' selected="true"><?php echo $additionalvalue->label;?></option>    
                                                                    <? }else{?>
                                                                        
                                                                        <option value='<?php echo $additionalvalue->label;?>'><?php echo $additionalvalue->label;?></option>  
                                                                    <?php }} ?>

                                                                </select>
                                                            
                                                            <?php } ?>
                                                        </div></div>
                                                    <?php
                                                    
                                                } }?> 
                                               <?php
                                                if($additional_fields[$key]['fieldType'] == 'checkbox')
                                                        { 
                                                        $requiredStatus = $additional_fields[$key]['fieldrequriedstatus'];
                                                        $requriedStatus = "";
                                                        $requiredStatueUpdate = "";
                                                        if($requiredStatus == true){
                                                            $requiredStatueUpdate = "required='ture'";
                                                            $requriedStatus = "*";
                                                        }
                                                    
                                           
                                           
                                                ?>
                                                    <div class="form-group row" > 
                                                        <div class="col-sm-12">
                                                            <?php if($all_meta_for_user[$site_prefix.$additional_fields[$key]['fielduniquekey']][0] == "Checked"){?>
                                                            <input  class="mycustomcheckbox" <?php echo $requiredStatueUpdate;?> checked="true" type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                                            <?php }else{?>
                                                            <input  class="mycustomcheckbox" <?php echo $requiredStatueUpdate;?>  type="checkbox" id="<?php echo $additional_fields[$key]['fielduniquekey'];?>"><?php echo '   '.$additional_fields[$key]['fieldName'];?><br/>
                                                            <?php }?> 
                                                        </div>
                                                    </div>
                                            
                                               <?}
                                       
                                       
                                          ?>
                                       <?php if($additional_fields[$key]['fieldType'] == 'display'){ ?>
                                             <div class="form-group row" >
                                                 
                                                 <div class="col-sm-12 ">
                                                     
                                                     <?php echo $additional_fields[$key]['fielddescription'];?>
                                                </div></div>
                                       <?}?>
                                    
                                    
                           
                              <?php }} ?>
                             
                          
                          
                          
	                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-3">
                                    <div class="form-group row" >
                                            <div class="col-sm-4">
                                               <label>Booth Numbers</label>
                                                                        
                                            </div>
                                            <div class="col-sm-8" style="display: inline-flex;">
                                                                
                                                <?php foreach ($AllBoothsList as $boothIndex=>$boothValue ){ ?>
                                                    
                                                   <?php if($boothValue['bootheOwnerID'] == $sponsor_id){ ?>
                                
                                                            <label style="       background-color: #e6e9ed;
    color: black;
    padding: 4px;
    border-radius: 8px;
    margin-right: 8px;
    /* font-size: 15px; */
    font-weight: 600;"><?php echo $boothValue['boothNumber'];?></label>
                                                            
                                                        <?php } ?>
                                                <?php } ?>

                                            </div>
                                    </div>
                                    <?php foreach ($listOFtaskArray as $key => $tasksObject) {

$tasksID=$tasksObject->ID;
$profile_field_settings = [];
$value_value = get_post_meta( $tasksID, 'value' , false);
$value_unique = get_post_meta( $tasksID, 'unique' , false);
$value_class = get_post_meta( $tasksID, 'class' , false);
$value_after = get_post_meta( $tasksID, 'after', false);
$value_required = get_post_meta( $tasksID, 'required' , false);
$value_allow_tags = get_post_meta( $tasksID, 'allow_tags' , false);
$value_add_to_profile = get_post_meta( $tasksID, 'add_to_profile' , false);
$value_allow_multi = get_post_meta( $tasksID, 'allow_multi', false);
$value_label = get_post_meta( $tasksID, 'label' , false);
$value_type = get_post_meta( $tasksID, 'type' , false);
$value_lin_url = get_post_meta( $tasksID, 'link_url' , false);
$value_linkname = get_post_meta( $tasksID, 'linkname', false);
$value_attr = get_post_meta( $tasksID, 'duedate', false);
$multivaluetasklimit = get_post_meta( $tasksID, 'multivaluetasklimit', true);



$value_taskattrs = get_post_meta( $tasksID, 'taskattrs', false);
$value_taskMWC = get_post_meta( $tasksID, 'taskMWC' , false);
$value_taskMWDDP = get_post_meta( $tasksID, 'taskMWDDP' , false);
$value_roles = get_post_meta( $tasksID, 'roles' , false);
$value_usersids = get_post_meta( $tasksID, 'usersids' , false);
$value_descrpition = get_post_meta( $tasksID, 'descrpition', false);
$value_multiselectstatus = get_post_meta( $tasksID, 'multiselectstatus', false);
$TaskCode = get_post_meta( $tasksID, 'taskCode', false);
$value_key = get_post_meta( $tasksID, 'key', false);
$profile_field_name  = $value_key[0];
$profile_field_settings['value'] = $value_value[0];
$profile_field_settings['unique'] = $value_unique[0];
$profile_field_settings['class'] =$value_class[0];
$profile_field_settings['after'] =$value_after[0];
$profile_field_settings['required'] =$value_required[0];
$profile_field_settings['allow_tags'] =$value_allow_tags[0];
$profile_field_settings['add_to_profile'] =$value_add_to_profile[0];
$profile_field_settings['allow_multi'] =$value_allow_multi[0];
$profile_field_settings['label'] =$value_label[0];
$profile_field_settings['type'] =$value_type[0];
$profile_field_settings['lin_url'] =$value_lin_url[0];
$profile_field_settings['TaskCode'] = $TaskCode[0];
$profile_field_settings['linkname'] =$value_linkname[0];
$profile_field_settings['attrs'] =$value_attr[0];
$profile_field_settings['taskattrs'] =$value_taskattrs[0];
$profile_field_settings['taskMWC'] =$value_taskMWC[0];
$profile_field_settings['taskMWDDP'] =$value_taskMWDDP[0];
$profile_field_settings['roles'] =$value_roles[0];
$profile_field_settings['usersids'] =$value_usersids[0];
$profile_field_settings['descrpition'] =$value_descrpition[0];
$profile_field_settings['multiselectstatus'] =$value_multiselectstatus[0];
$value = get_user_meta($sponsor_id, $profile_field_name, true);
        

if(( $profile_field_settings['type'] == 'text' ||  $profile_field_settings['type'] == 'email' ||  $profile_field_settings['type'] == 'date' || $profile_field_settings['type'] == 'number') && ( $profile_field_settings['TaskCode']!="")){ ?> 
   <div class="form-group row" >
        <div class="col-sm-4">
                    <label><?php echo   $profile_field_settings['label'];?></label>
                    
        </div>
            <div class="col-sm-8">
                    <input type="hidden" name="requiredstatus" id="<?php echo  $profile_field_name;?>"  >
                    <input type="<?php echo  $profile_field_settings['type'];?>"  class="form-control taskKey" id="<?php echo  $profile_field_name;?>" name="<?php echo  $profile_field_name;?>" value="<?php echo $value;?>" placeholder="<?php echo $profile_field_settings['label'];?>" >              
            </div>
    </div>
   <?php 
}else if(( $profile_field_settings['type'] == 'color')  && ( $profile_field_settings['TaskCode']!="")){
    
    //echo '<pre>';
    //print_r($profile_field_settings);exit;
    
        // echo"<pre>";
        // print_r($value);
        // exit;
    
    
    ?>

<div class="form-group row" >
        <div class="col-sm-4">
                   <label><?php echo   $profile_field_settings['label'];?></label>
        </div>
    <div class="col-sm-8">

            <input type="hidden" name="requiredstatus" id="<?php echo  $tasksID;?>" value="true" >
            <input type="hidden" name="specialattributes" id="<?php echo   $tasksID;?>" value="<?php echo  $profile_field_name;?>" >
            <?php  if(!empty($value)){
                                 
                                
                                echo '<div id="'.$tasksID.'_fileuploadholder"></div><div id="'.$tasksID.'_fileuploadpic"><div class="col-sm-5"><img width="200" id="'.$tasksID.'_fileuploadpicviewer"  name="userprofilepic" src="'.$value['url'].'" ></div><div class="col-sm-4"><a width="200" id="'.$tasksID.'_fileuploadbutton" class="btn btn-inline mycustomwidth btn-success" name="'.$profile_field_name.'" onclick="showprofilefieldupload(this)" >Edit</a></div></div>';
                                
                            }else{
                           
                                echo '<input type="file"  class="form-control '.$profile_field_name.'_fileupload" id="'.$profile_field_name.'" name="taskimages[]" '.$profile_field_settings['taskattrs'].' placeholder="'.$profile_field_settings['label'].'">';
                                
                                
                            }
                            
                            ?>
    
    </div>
</div>

<?php 

}else if( ($profile_field_settings['type'] == 'url') && ( $profile_field_settings['TaskCode']!="")){?>
    <div class="form-group row" >
            <div class="col-sm-4">
            <label><?php echo  $profile_field_settings['label'];?></label>
        
            </div>
                    <div class="col-sm-8">
                        <input type="hidden" name="requiredstatus"  id="<?php echo  $profile_field_name;?>" >
                        <input  oninvalid="this.setCustomValidity('Please add the full (https://www) url.')" oninput="this.setCustomValidity('')" title="Please add the full (https://www) url." type="<?php echo  $profile_field_settings['type'];?>"  class="form-control taskKey" id="<?php echo  $profile_field_name;?>" name="<?php echo  $profile_field_name;?>" value="<?php echo $value;?>" placeholder="<?php echo $profile_field_settings['label'];?>" > 
                   </div>
       
    </div>

  <?php 
}else if(( $profile_field_settings['type'] == 'textarea') && ( $profile_field_settings['TaskCode']!="")){?>
 <div class="form-group row" >
            <div class="col-sm-4">
            <label><?php echo  $profile_field_settings['label'];?></label>
        
            </div>
                    <div class="col-sm-8">
                        <input type="hidden" name="requiredstatus" id="<?php echo  $profile_field_name;?>"  >
                        <textarea   class="form-control taskKey" id="<?php echo $profile_field_name;?>" name="<?php echo $profile_field_name;?>" placeholder="<?php echo $profile_field_settings['label'];?>" ><?php echo $value;?></textarea>

   </div></div>
<?php }} ?>
                          
                                            <div class="m-t-lg with-border" style="border-top: 1px solid #d8e2e7; padding-top: 50px;">
                                                <h4 style="font-weight: 600;">Advanced </h4>
                                                <div class="form-group row"> 
                                                        <div class="col-sm-8"  style="display:flex"  >
                                                            <h5>
                                                                Override Booth Default Settings for this User? 
                                                            </h5>
                                                            <label class="switch">
                                                                <input  <?php echo $OverrideCheck[0]?> name="Override_Check" id="Override_Check" id="toggle_btn" type="checkbox" value="checked" >
                                                                <span class="slider round"></span>
                                                            </label>
                                                       </div>
                                                            <div class="col-sm-4 text-right" >
                                                                <button type="button" style="background-color: #7cb5ec;border-color: white; " class="btn btn-primary" id="navigate" >Go To Booth Management Settings</button>
                                                            </div>
                                                </div> 
                                                <div class="form-group row" id="Booth_div" style="display:<?php echo $display;?>" > 
                                                        <div class="col-sm-4">
                                                            
                                                            <label >
                                                                Number of Allowed Booth
                                                            </label>
                                                       </div>
                                                       <div class="col-sm-8">
                                                       <input type="number" min="1" oninput="validity.valid||(value='');" class="form-control mymetakey" id="customefield_booth_allow" name="customefield_booth_allow" value="<?php echo $Oveeridedata[0] ?>" placeholder="Enter Number">
                                                   </div>
                                                </div> 
                                                <div class="form-group row" id="Booth_Pre_div" style="display:<?php echo $display;?>"> 
                                                        <div class="col-sm-2" style="display:flex;" >
                                                            
                                                        <label style="    display: flex;width: 126px;">
                                                               Pre-Paid?
                                                            </label>
                                                            <label class="switch" style="    margin-left: 100px !important;">
                                                            <input type="checkbox" id="prePaid_checkbox" <?php echo $OveeridePrePaid[0]?>   name="prePaid_checkbox" value="checked" >
                                                                <span class="slider round"></span>
                                                            </label>
                                                       
                                                        </div>
                                                       
                                                   
                                                </div> 
                                                
                                            </div>
                                
                               
                     
                     
                     
                                    

                                    </div>
                        <!--.box-typical-body-->

	                </section><!--.box-typical-dashboard-->           
                               
                      <h5 class="m-t-lg with-border"></h5>
                                  <div class="form-group row" >
                                    <label class="col-sm-2 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <button type="submit"  id="addnewsponsor_q" name="updatesponsor"  class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                          
                                        
                                    </div>
                                </div>
                               
                
                </form>
            </div>
        </div>
    </div>
    
     
                        
				<?php   include 'cm_footer.php';
		
      
      
      
       
   }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>
<script>

jQuery( document ).ready(function() {

    console.log("sd==================================");

});
jQuery('#Override_Check').on('click',function() {
 var val=jQuery('#Override_Check:checked').val();
console.log(val);
if(val==undefined)
{
 jQuery("#Booth_div").hide();
 jQuery("#Booth_Pre_div").hide();
 
  
 console.log(jQuery("#prePaid_checkbox :checked").val());
 console.log(jQuery("#customefield_booth_allow ").val());
    jQuery("#customefield_booth_allow").val(" ");
    jQuery("#prePaid_checkbox :checked").val(" ");
  console.log(jQuery("#customefield_booth_allow ").val());
  console.log(jQuery("#prePaid_checkbox :checked").val());
//  jQuery("#addnewsponsor_q").prop('disabled', false);
 
}
else 
{
    
  jQuery("#Booth_div").css('display',"block");
 // var valeu=jQuery("#customefield_booth_allow ").val());
 
  jQuery("#Booth_Pre_div").css('display',"block");



}
    
});
// jQuery('#addnewsponsor_q').on('click',function(){
   
//   console.log("----------------");
// });
jQuery('#navigate').on('click',function(){
    var url      = currentsiteurl+'/';
    var newurl=url+"/admin-settings/";
    console.log(newurl);
    window.location.href=newurl;
  
})
// jQuery('#tab-3').on('click',function () {
    
    


 

// })

</script>