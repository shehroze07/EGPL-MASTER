<?php
// Silence is golden.
if (current_user_can('administrator') || current_user_can('contentmanager')) {
    
    
   // $term = term_exists('Content Manager Editor', 'category');
    //$oldvalues = get_option( 'ContenteManager_Settings' );
    
    
  // $get_all_nav = wp_get_nav_menu_items('main_menu');
   //echo '<pre>';
   //print_r($get_all_nav);
   //exit;   // echo  $term['term_id'];exit;
  // echo '<pre>';
   //print_r($term);exit;

    
    
   
    $all_meta_for_user = get_user_meta( 3 );
        //code by AD//
    $emailTemplateTitle = 'AR_Contentmanager_Email_Template_welcome';
    $boothTemplateTitle = 'AR_Contentmanager_Email_Template_booth';
      //code by AD//
    //$settitng_key='AR_Contentmanager_Email_Template_welcome';
   
    
  
   // unset($sponsor_info['demo_optional_welcome_email']);
   // unset($sponsor_info['demo_optional_welcome_email_2']);
   // $result= update_option($settitng_key, $sponsor_info);
  
     
         //code by AD//
    
    //code by AD//

    
    if(isset($_GET['emailtemplatetype'])) {
        $settitng_key = $_GET['emailtemplatetype'];
    } else {
        $settitng_key = $emailTemplateTitle;
    }
    //code by AD//
    $sponsor_info = get_option($settitng_key); 
    
    $oldvalues = get_option( 'ContenteManager_Settings' );
    $formemail = $oldvalues['ContentManager']['formemail'];
    // if(isset($_GET['loademailtemplate'])){
        
    //     $loadreportname = $_GET['loademailtemplate'];
    // }else{
        
    //     $loadreportname = 'welcome_email_template';
        
    // }
     
    if(isset($_GET['loademailtemplate'])){
        
        $loadreportname = $_GET['loademailtemplate'];
    }else{
        
        if(isset($_GET['emailtemplatetype']) && $_GET['emailtemplatetype'] == $boothTemplateTitle) $loadreportname = 'Booth Turn Email';
        else $loadreportname = 'welcome_email_template';
        
    }
 
    // if(empty($sponsor_info[$loadreportname]['name'])){
        
    //     $template_name_selected = ucwords(str_replace('_', ' ', $loadreportname));
        
    // }
    
       //code by AD//
    // $template_name_selected = $sponsor_info[$loadreportname]['name'];
    // $content = $sponsor_info[$loadreportname]['welcomeboday'];
    $template_name_selected = ucwords(str_replace('_', ' ', $loadreportname));
    $content = ($sponsor_info[$loadreportname] && $sponsor_info[$loadreportname]['welcomeboday'])? $sponsor_info[$loadreportname]['welcomeboday'] : "Message here...";
       //code by AD//
    
    
    
    $editor_id = 'welcomebodytext';
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    ?>


    <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Welcome Email</h3>
                           
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p>
                
                    Edit the content for your Welcome E-Mail that's sent to new users here. Use the merge fields to insert user specific values such as user first name, e-mail, etc. 
                </p>

                <h5 class="m-t-lg with-border"></h5>
                <section class="box-typical faq-page">
                            <div class="faq-page-header-search">
                                <div class="search">
                                    <div class="row">

                                    <div class="col-md-4">

                                            <fieldset class="form-group">

                                                <select style="width:100%;height:38px;"class="form-control" onchange="loadMultiBoothEmailTemplates()" id="loadmultiboothemailtemplate" egid="loadmultiemailtemplate">
                                                <option value="">-- Select Template Type --</option>
                                                <?php if((isset($_GET['emailtemplatetype']) && $_GET['emailtemplatetype'] == $emailTemplateTitle) || (!isset($_GET['emailtemplatetype']))) { ?>
                                                    <option value="AR_Contentmanager_Email_Template_welcome" selected>Email Template</option>
                                                <?php } else { ?>
                                                    <option value="AR_Contentmanager_Email_Template_welcome">Email Template</option>
                                                <?php } ?>

                                                <?php if(isset($_GET['emailtemplatetype']) && $_GET['emailtemplatetype'] == $boothTemplateTitle) { ?>
                                                    <option value="AR_Contentmanager_Email_Template_booth" selected>Booth Queue Template</option>
                                                <?php } else { ?>
                                                    <option value="AR_Contentmanager_Email_Template_booth">Booth Queue Template</option>
                                                <?php } ?>    
                                                </select>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-4">

                                            <fieldset class="form-group">

                                                <select style="width:100%;height:38px;"class="form-control" onchange="loadmultiwelcomeemailtemplate()" id="loadmultiwelcomeemailtemplate" egid="loadmultiwelcomeemailtemplate">
                                                    <option disabled hidden>Load a Template</option>
                                                    <option value="">Save Current Template As</option>
                                                    <optgroup label="Saved Templates" id="multiloaduserreportlist">
                                                    <?php
                                                        foreach ($sponsor_info as $key=>$value) {
                                                            
                                                            
                                                            if(isset($_GET['loademailtemplate'])){
                                                                
                                                                if($key == $_GET['loademailtemplate']){
                                                                $template_name = ucwords(str_replace('_', ' ', $key));
                                                                echo '<option value="' . $key . '" selected="selected">'.$template_name.'</option>';  
                                                            }else{
                                                              if($key == 'welcome_email_template'){
                                                                
                                                                echo '<option value="' . $key . '" selected="selected">Welcome Email</option>';  
                                                              }else{
                                                              $template_name = ucwords(str_replace('_', ' ', $key));
                                                              echo '<option value="' . $key . '">' . $template_name . '</option>';
                                                              }
                                                            }
                                                            }else{
                                                                if(($key == 'welcome_email_template' && $_GET['emailtemplatetype'] == $emailTemplateTitle) || (!isset($_GET['emailtemplatetype']) && $key == 'welcome_email_template')){
                                                                    echo '<option value="' . $key . '" selected="selected">Welcome Email</option>';  
                                                                }
                                                                else if($key == 'Booth Turn Email' && $_GET['emailtemplatetype'] == $boothTemplateTitle){
                                                                    echo '<option value="' . $key . '" selected="selected">Booth Turn Email</option>';  
                                                                }else{
                                                                    $template_name = ucwords(str_replace('_', ' ', $key));
                                                                    echo '<option value="' . $key . '">' . $template_name . '</option>';  
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </optgroup>
                                                </select>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-4">

                                            <form method="post" action="javascript:void(0);" onSubmit="multi_welcomeemail_save_template()">    	
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input style="height: 38px;" placeholder="Template Name" value="<?php if(isset($_GET['loademailtemplate'])){echo $template_name_selected;}else{ if(isset($_GET['emailtemplatetype']) && $_GET['emailtemplatetype'] == $boothTemplateTitle) echo 'Booth Turn Email'; else echo 'Welcome Email';}?>" id="welcomeemailtemplatename" type="text" class="form-control" egid="welcomeemailtemplatename" required>
                                                        <input type="hidden"  name="settitng_key" id="settitng_key" value="<?php echo $settitng_key; ?>" />
                                                        <div class="input-group-btn">
                                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <button type="submit"  name="saveuserreport" egid="save-template" class="dropdown-item"  ><i class="font-icon fa fa-save" aria-hidden="true"></i> Save</button>
                                                                <a class="dropdown-item" onclick="multi_welcome_removeeuserreport()" egid="remove-template"><i class="font-icon fa fa-remove" aria-hidden="true"></i>Delete</a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>		


                                        </div>
                                    </div>
                                </div>
                            </div><!--.faq-page-header-search-->



                        </section><!--.faq-page-->
                  
                
                
                 <h5 class="m-t-lg with-border"></h5>
                
              <form method="post" action="javascript:void(0);" onSubmit="welcomeemail_preview()">
                    
                   <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">From Name <strong>*</strong></label>
                                    <div class="col-sm-5">
                                       <div class="form-control-wrapper form-control-icon-left">    
							<input type="text"  class="form-control" id="welcomeemailfromname" placeholder="From Name" egid="welcomeemailfromname" value="<?php echo  $sponsor_info[$loadreportname]['fromname'];?>" required>
							<i class="font-icon fa fa-arrow-right"></i>	
                                       </div>
                                    </div>
                                     <div class="col-sm-4">
                                         
                                         <strong><?php if(!empty($formemail)){echo $formemail; }else{echo 'noreply@convospark.com';}?></strong>
                                     </div>
                    </div>
                   <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Reply-To <i style="cursor: pointer;" title = 'Please input an email address. When the recipient hits Reply on the message, this email address will be selected to receive their reply.' class="reporticon font-icon fa fa-question-circle"></i></label>
                                    <div class="col-sm-9">
                                       <div class="form-control-wrapper form-control-icon-left">    
							<input type="text"  class="form-control" id="replaytoemailadd" egid="replaytoemailadd" placeholder="Reply To Email address" value="<?php echo  $sponsor_info[$loadreportname]['replaytoemailadd'];?>" >
							<i class="fa fa-mail-reply"></i>	
                                       </div>
                                    </div>
                                    
                    </div>
                  <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Bcc <i style="cursor: pointer;" title='Please input an (only one) email address. All outgoing Welcome emails will be blind carbon copied to this address.' class="reporticon font-icon fa fa-question-circle"></i></label>
                                    <div class="col-sm-9">
                                       <div class="form-control-wrapper form-control-icon-left">    
							<input type="text"  class="form-control" id="BCC" placeholder="BCC" egid="BBC" value="<?php echo  $sponsor_info[$loadreportname]['BCC'];?>" >
							<i class="font-icon fa fa-copy"></i>	
                                       </div>
                                        
                                    </div>
                                    
                    </div>
<!--                  <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">CC <i style="cursor: pointer;" title='Please input one or multiple email address (comma separated). All outgoing Welcome emails will be carbon copied to these address(es).'class="reporticon font-icon fa fa-question-circle"></i></label>
                                    <div class="col-sm-9">
                                       <div class="form-control-wrapper form-control-icon-left">    
							<input type="text"  class="form-control" id="CC" placeholder="CC" value="<?php echo  $sponsor_info[$loadreportname]['CC'];?>" >
							<i class="font-icon fa fa-copy"></i>	
                                       </div>
                                    </div>
                                    
                    </div>-->
                  <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Subject <strong>*</strong></label>
                                    <div class="col-sm-9">
                                       <div class="form-control-wrapper form-control-icon-left">    
							<input type="text"  class="form-control" id="welcomeemailsubject" egid="welcomeemailsubject" placeholder="Subject" value="<?php echo  $sponsor_info[$loadreportname]['welcomesubject'];?>" >
							<i class="font-icon fa fa-edit"></i>	
                                       </div>
                                    </div>
                                    
                    </div>
                  <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Message <strong>*</strong><p style="margin-top: 48px;"><a  class="btn btn-sm btn-inline btn-primary mergefieldbutton"  onclick="welcome_available_merge_fields()" egid="insert-merge-fields">Select Merge Fields</a></p></label>
                                    <div class="col-sm-9">
                                      
							
						 <textarea   name="welcomebodytext" id="welcomebodytext" egid="welcomebodytext" required><?php echo  $content;?></textarea>		
              
                                    </div>
                                    
                    </div>
                  
                  
                     <h5 class="m-t-lg with-border"></h5>
                  <div class="form-group row">
                                    <label class="col-sm-3 form-control-label"></label>
                                    <div class="col-sm-6">
                                             <input type="hidden"  name="welcomeemailAddress" id="welcomeemailAddress" value="" >
                                             <input type="hidden"  name="settitng_key" id="settitng_key" value="<?php echo $settitng_key; ?>" >
                                             <button type="submit"  name="welcomepreview"  class="btn btn-lg mycustomwidth btn-success" value="saveandpreview" egid="preview-and-save">Preview & Save</button>
                                            
                                        
                                    </div>
                                </div>
                  
                

                </form>
            </div>
        </div>
    </div>
   

                                              

       
  <?php   
  
    include 'cm_footer.php';
		
   }else{
       
       $redirect = get_site_url();
       wp_redirect( $redirect );exit;
   
   }
   ?>