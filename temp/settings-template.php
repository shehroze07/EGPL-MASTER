<?php
    // Silence is golden.
       if (current_user_can('administrator') || current_user_can('contentmanager') ) {
           
        //role.js 
    		
          
         $oldvalues = get_option( 'ContenteManager_Settings' );
         $eventdate = $oldvalues['ContentManager']['eventdate'];
         $mainheaderbackground = $oldvalues['ContentManager']['mainheader'];
         
         $headelogo = $oldvalues['ContentManager']['headerlogo'];
         $sitefavicon = $oldvalues['ContentManager']['sitefavicon'];
         
         $mainheaderlogo = $oldvalues['ContentManager']['mainheaderlogo'];
         $applicationmoderationstatus = $oldvalues['ContentManager']['applicationmoderationstatus'];
         $eventstartdate = $oldvalues['ContentManager']['eventstartdate'];
         $eventenddate = $oldvalues['ContentManager']['eventenddate'];
         $prinarythemecolor = $oldvalues['ContentManager']['prinarythemecolor'];
         $secondarythemecolor = $oldvalues['ContentManager']['secondarythemecolor'];
         $eventaddress = $oldvalues['ContentManager']['eventaddress'];
         $buttonfontcolor = $oldvalues['ContentManager']['buttonfontcolor'];
         
         $exhibitorterminology = $oldvalues['ContentManager']['exhibitorterminology'];
         $boothterminology = $oldvalues['ContentManager']['boothterminology'];
         $packageterminology = $oldvalues['ContentManager']['packageterminology'];
         $addonsterminology = $oldvalues['ContentManager']['addonsterminology'];
         
         $aptycode = $oldvalues['ContentManager']['aptycode'];
         if(empty($aptycode)){
             
            $aptycode= "(function() {var tenantKey = '7afo0ZPu';var contentURL = 'https://client.app.apty.io';var element = window.document.createElement('script');element.setAttribute('src', contentURL + '/tenant-' + tenantKey + '/injected-hook.js');element.setAttribute('async','');window.document.head.appendChild(element);})();"; 
         }
         
         $blog_id = get_current_blog_id();
         
                
         
         $ordernotficationemails = $oldvalues['ContentManager']['ordernotficationemails'];
         $welcomememailreplayto = get_option('AR_Contentmanager_Email_Template_welcome');
         $replaytoemailadd = $welcomememailreplayto['welcome_email_template']['replaytoemailadd'];
         $registration_notificationemails = $oldvalues['ContentManager']['registration_notificationemails'];
         
         if(empty($registration_notificationemails)){
             
             $registration_notificationemails  = $replaytoemailadd;
         }
         
         echo $mainheader;
         
         $formemail = $oldvalues['ContentManager']['formemail'];
         $mandrill = $oldvalues['ContentManager']['mandrill'];
         $infocontent = $oldvalues['ContentManager']['infocontent'];
         $addresspoints = $oldvalues['ContentManager']['addresspoints'];

        //  $user_query =new WP_User_Query( array( 'role__not_in' => 'Administrator' ) );
        //  $lisstofuser = $user_query->get_results();
        //  foreach($lisstofuser as $key => $name){
        //         $user_Info=get_user_meta($name->ID);
        //         //    echo "<pre>";
        //         //  print_r($user_Info);
        //   } 
      

              //  echo "<pre>";
              //    print_r($lisstofuser);
              //exit;

         
           include 'cm_header.php';
           include 'cm_left_menu_bar.php';
           ?>
          
          <select id="usersList" >
          
            <?php  
           $blog_id = get_current_blog_id();
           $args = array(
             'role__not_in' => 'Administrator',
             );
           $user_query =new WP_User_Query( $args  );
           $lisstofuser = $user_query->get_results();
          
         
              
                       foreach ($lisstofuser as $key=>$a_value) { 
                                $user_Info=get_user_meta($a_value->ID,'wp_'.$blog_id.'_company_name',true);
                                $user_Remove_status=get_user_meta($a_value->ID,'wp_'.$blog_id.'_RemoveFromQueue');
                                         if(!empty($user_Remove_status[0])){
                                          echo  '<option value="' . $a_value->ID . '" >'.$user_Info.'</option>';
                                         }
                                   
                                  }
                                  ?>
                              
         
      </select>    
      <?php
           
           
           //////////////Entry Wizard Settings Page
           
           $cat_args = array();
$product_categories = get_terms( 'product_cat', $cat_args );
$category = get_term_by('slug', 'add-ons', 'product_cat');

$key = 'custome_exhibitor_flow_settings_data';
$savedexhibitorEntryLevel = get_option($key);

$exhibitorflowstatusKey = "exhibitorentryflowstatus";
$exhibitorflowstatus = get_option($exhibitorflowstatusKey);
$flowsharthideclass = "";
if(empty($exhibitorflowstatus['status'])){
    
    
    $exhibitorflowstatus['status']="";
    $flowsharthideclass = "style='display:none;'";
    
}
//update_option($key,"");
 
//$savedexhibitorEntryLevel = [];

//echo '<pre>';
//print_r($savedexhibitorEntryLevel);exit;


$exhibitorEntryLevel = [];

$exhibitorEntryLevel[0]['url'] = $site_url."/intro/";
$exhibitorEntryLevel[0]['name'] = "Intro";
$exhibitorEntryLevel[0]['icon'] = "fas fa-chalkboard-teacher";
$exhibitorEntryLevel[0]['slug'] = "intro";
$exhibitorEntryLevel[0]['status'] = "optional";
$exhibitorEntryLevel[0]['statusactive'] = true;
$exhibitorEntryLevel[0]['description'] = 'This is a blank screen where you can add any introductory content for your end users. It is the first screen they will see when beginning the registration process.';




$exhibitorEntryLevel[1]['url'] = $site_url."/product-category/packages/";
$exhibitorEntryLevel[1]['name'] = "Select Package";
$exhibitorEntryLevel[1]['icon'] = "fas fa-medal";
$exhibitorEntryLevel[1]['slug'] = "packages";
$exhibitorEntryLevel[1]['status'] = "optional-2";
$exhibitorEntryLevel[1]['statusactive'] = true;
$exhibitorEntryLevel[1]['description'] = 'Enable this option if you want to require your end users to purchase a sponsorship or exhibitor package during the entry process. Packages are created in the Shop area of the admin, where you will map a Level a user will be assigned upon purchasing this package, as well as have the option to add Booths as part of the paid package.';


$exhibitorEntryLevel[2]['url'] = $site_url."/floor-plan/";
$exhibitorEntryLevel[2]['name'] = "Select Booth";
$exhibitorEntryLevel[2]['icon'] = "flaticon-map-location";
$exhibitorEntryLevel[2]['slug'] = "floor-plan";
$exhibitorEntryLevel[2]['status'] = "optional-2";
$exhibitorEntryLevel[2]['statusactive'] = true;
$exhibitorEntryLevel[2]['description'] = 'Enable this option for online booth sales during the user entry process. If you have Packages enabled (above), you can even include booths as part of the paid package.';


$exhibitorEntryLevel[3]['url'] = $site_url."/product-category/add-ons/";
$exhibitorEntryLevel[3]['name'] = "Add-Ons";
$exhibitorEntryLevel[3]['icon'] = "fas fa-shopping-basket";
$exhibitorEntryLevel[3]['slug'] = "add-ons";
$exhibitorEntryLevel[3]['status'] = "optional";
$exhibitorEntryLevel[3]['statusactive'] = true;
$exhibitorEntryLevel[3]['description'] = 'Enable this option if you want to provide optional add-ons for your end users, such as additional sponsorship opportunities, for example.';

$exhibitorEntryLevel[4]['url'] = $site_url."/registrations/";
$exhibitorEntryLevel[4]['name'] = "Register";
$exhibitorEntryLevel[4]['icon'] = "far fa-id-badge";
$exhibitorEntryLevel[4]['slug'] = "registrations";
$exhibitorEntryLevel[4]['status'] = "requried";
$exhibitorEntryLevel[4]['statusactive'] = true;
$exhibitorEntryLevel[4]['description'] = 'The registration form page where you will collect your end user initial registration information.';

   
$exhibitorEntryLevel[5]['url'] = $site_url."/cart/";
$exhibitorEntryLevel[5]['name'] = "Review Cart";
$exhibitorEntryLevel[5]['icon'] = "fas fa-shopping-cart";
$exhibitorEntryLevel[5]['slug'] = "cart";
$exhibitorEntryLevel[5]['status'] = "requried";
$exhibitorEntryLevel[5]['statusactive'] = true;
$exhibitorEntryLevel[5]['description'] = 'This page will provide a summary of the end users cart.';



$exhibitorEntryLevel[6]['url'] = $site_url."/checkout/";
$exhibitorEntryLevel[6]['name'] = "Checkout";
$exhibitorEntryLevel[6]['icon'] = "fas fa-cart-arrow-down";
$exhibitorEntryLevel[6]['slug'] = "checkout";
$exhibitorEntryLevel[6]['status'] = "requried";
$exhibitorEntryLevel[6]['statusactive'] = true;
$exhibitorEntryLevel[6]['description'] = 'This page is where the end user will complete the final checkout.';



$exhibitorEntryLevel[7]['url'] = $site_url."/checkout/order-received/";
$exhibitorEntryLevel[7]['name'] = "Confirmation";
$exhibitorEntryLevel[7]['icon'] = "far fa-check-circle fa-2x";
$exhibitorEntryLevel[7]['slug'] = "order-received";
$exhibitorEntryLevel[7]['status'] = "requried";
$exhibitorEntryLevel[7]['statusactive'] = true;
$exhibitorEntryLevel[7]['description'] = 'This will be the Confirmation page after checkout, the final page of the registration. Your end users will then be able to access the entire portal after completing the registration.';


           
           

           
           
           
           
           
           ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">
    <link  href="/wp-content/plugins/EGPL/css/jquery.datetimepicker.min.css" rel="stylesheet">
    <link  href="/wp-content/plugins/EGPL/css/cropper.css" rel="stylesheet">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/main.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
 

           <style>
        
        .egmb-10{margin-bottom: 10px;}
        .egmb-20{margin-bottom: 20px;}
        .radio input{visibility: unset;}
        .egradio-inline{display: inline;}
        .egcolor{color:red;}
        .row{margin-left: 0px;}

         .previewDivselectedImage{
            border: solid lightgray 1px;
            height: auto;
            width: auto;
        }
         .eg-optional{
        
        background: linear-gradient(to right, #1f9bd9, #99d2f2 200%);
        border: 2px solid #53b2e4;
/*        cursor: not-allowed;*/
    }
    .sweet-alert p{color:#333 !important;}
    .eg-requried{
        
        background: linear-gradient(to right, #b2b2b2, #c3c2c2 200%);
        border: 2px solid #b6b6b6;
/*        cursor: not-allowed;*/
    }
    .eg-optional-type-2{
        
        background: linear-gradient(to right, #ff9800, #ef8d22 200%);
        border: 2px solid #ff9800;
/*        cursor: move;*/
    }
    .eg-boxed{
        
        height: 210px;
        margin-bottom: 20px;
        padding: 20px;
        color:#fff;
        
        
        padding: 20px;
        border-radius: 50px 20px;
        box-shadow: 0 8px 8px -4px #ccc;
    }
    .switch {
  position: absolute;
  margin-left:385px;
  display: inline-block;    margin-top: 8px;
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
.btn-div{
  display: block;
    display: flex;
    justify-content: flex-end;
    margin-top: 19px;
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
   
    .eg-mr-top{
        
        margin-top: 12px;
    }
    .styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}

.styled-table tbody tr.active-row {
    font-weight: bold;
    color: #009879;
}
    .eg-editlink{
        
        
        text-decoration: underline !important;
        
    }
    .eg-editlink a{color:#fff !important;}
    /* .toggle.btn{
    width: 110px;
    min-height: 34px;
} */


/********************************************************************************/
/*            Booth Management Style Start                                        */
/********************************************************************************/

    .bulkeditfield_AD_1 tbody tr td:first-child{display: block;}
    .hi-icon-wrap{cursor: move;}
    .dataTables_empty{display: block !important;}
    
    .select2-container--default .select2-search--dropdown {
    padding-left: 0px;
    padding-right: 0px;
	border-radius: 0px;
}
.RowName{
     display: flex;
     justify-content: center;
     text-align: justify;
   
     border-radius: 6px;
        margin-top: 14px;
    
     padding: 5px 9px 11px 13px;
     margin-right: 7px;
     margin-left: 7px;
    
     height:56px;
    
     line-height:25px;
     padding-top: 173px;
 }
.cardsfields tbody tr {
    float: left;
    margin: 10px;
    border: 1px solid #aaa;
    box-shadow: 3px 3px 6px rgba(0,0,0,0.25);
    background-color: white;
    height: 60px;
    width: 244px;
    border-radius: 9px;
}
td:hover{
		cursor:move;
 }
.select2-search__field .newmultiselect{   
		margin-bottom: -20px;
}
.select2-search--dropdown {
	
	    padding: 0px;
}
.turn-display{
  background: #7cb5ec;
    color: white;
    padding: 5px;
    border-radius: 4px;
}
.select2-container--open .select2-dropdown--below {
z-index:100000000000;
height: 50% !important;
}
.select2-results__options {
	background: #FFFFFF !important;
	border: 1px solid #d7dee2 !important;
}
.jconfirm{z-index: 99 !important;}
.selected
{
    background-color: #666;
    color: #fff;
}
.table-bordered, .table-bordered td, .table-bordered th {
    /* border: 1px solid #eceeef; */
    border: none;
}

#table_id_AD_1 .toggle-off.btn {
    padding-left: 14px;
   
}
/* #table_id_AD_1 .toggle-on.btn {
  padding-right: 22px;
  margin-left: -4px;
  margin-top: 2px;
} */

#table_id_AD_1 .toggle-handle {
    position: relative;
    margin: 0 auto;
    margin-top: -3px;
    padding-top: 0;
    padding-bottom: 0;
    height: 100%;
    width: 0;
    border-width: 0 1px;
}
#flowchart .toggle.btn {
    min-width: 110px;
    min-height: 34px;
}

#tabs-1-tab-2  .toggle.btn {
    min-width: 110px;
    min-height: 34px;
}
#table_id_AD_1  .toggle.btn {
    width: 60px;
    max-width: 60px;
    height: 26px;
    min-height: 25px;
}

.backColor{
  background-color: #cbeeff !important;
}

/********************************************************************************/
/*            Booth Management Style End                                        */
/********************************************************************************/
        
    </style>     
    <div class="blockUI" style="display:none;"></div>
    <div class="blockUI blockOverlay" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background: rgba(142, 159, 167, 0.8); opacity: 1; cursor: wait; position: absolute;"></div>
    <div class="blockUI block-msg-default blockElement" style="z-index: 1011; position: absolute; padding: 0px; margin: 0px;  top: 300px;  text-align: center; color: rgb(0, 0, 0); border: 3px solid rgb(170, 170, 170); background-color: rgb(255, 255, 255); cursor: wait; height: 200px;left: 50%;">
            <div class="blockui-default-message">
                <i class="fa fa-circle-o-notch fa-spin"></i><h6>Please Wait.</h6></div></div> 
            <div class="page-content">
            <div class="container-fluid">
                <header class="section-header" style="text-align: center;">
                    <div class="tbl">
                        <div class="tbl-row">
                            <div class="tbl-cell">
                                <h3>Settings</h3>
                               
                            </div>
                        </div>
                    </div>
                </header>
                 <section class="tabs-section">
                  <div class="tabs-section-nav tabs-section-nav-icons">
                    <div class="tbl">
                        <ul class="nav" role="tablist" id="myTabs">
                            <li class="nav-item" style="width:33%;">
                                <a class="nav-link " href="#tabs-1-tab-1" role="tab" data-toggle="tab">
                                    <span class="nav-link-in">
                                        <i class="fas fa-hat-wizard"></i>
                                            Event Settings
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tabs-1-tab-2" role="tab"  data-toggle="tab">
                                    <span class="nav-link-in">
                                         <i class="fas fa-hat-wizard"></i>
                                        
                                         Registration
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="#tabs-1-tab-3" role="tab"  data-toggle="tab">
                                    <span class="nav-link-in">
                                         <i class="fas fa-hat-wizard"></i>
                                        
                                         Booth Management Settings
                                    </span>
                                </a>
                            </li>
                           

                        </ul>
                    </div>
                </div><!--.tabs-section-nav-->
                
                <div class="tab-content">
                  
                    <div role="tabpanel" class="tab-pane fade in active"  id="tabs-1-tab-1">
                        
                          <div class="box-typical box-typical-padding">
                    
                        <div class="card-header egmb-20">
                            <h4 class="card-title">Branding</h4>
                                
                            <form method="post" action="javascript:void(0);" onSubmit="portalsettings_update()">
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label"></label>
                                     
                                                 <button style="float: right;" type="submit"  name="addsettings"  class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                                
                                            
                                        </div>
                                
                        </form>

                        </div>
                      
                        <form method="post" action="javascript:void(0);" onSubmit="portalsettings_update()">
                                
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label" for="inputEventStart">Event Start Date</label>
                                        <input type="text" class="form-control datetimepicker portalsettings" name="eventstartdate" value="<?php echo $eventstartdate;?>" id="inputEventStart" placeholder="Event Start Date" required="true">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label" for="inputEventend">Event End Date</label>
                                        <input type="text" class="form-control datetimepicker portalsettings" name="eventenddate" value="<?php echo $eventenddate;?>" id="inputEventend" placeholder="Event End Date" required="true">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="form-control-label" for="inputEventStart">Event Address</label>
                                        <textarea  class="form-control portalsettings" name="eventaddress"  id="inputEventaddress" placeholder="Event Address" ><?php echo $eventaddress;?></textarea>
                                    </div>
                                </div>
                            <p><h6 class="card-title">Site Theme Colors</h6></p>
                                
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="form-control-label" >Primary Color <i class="fa fa-question-circle fa-sm" title="The primary color of buttons and icons throughout the portal"></i></label>
                                        <input type="text" class="form-control cp-component portalsettings" name="prinarythemecolor" id="inputprimarytheme" value="<?php echo $prinarythemecolor;?>" placeholder="Primary Theme Color" required="true">
                                    </div>
                                  <!--                                <div class="form-group col-sm-6">
                                        <label class="form-control-label">Secondary Color <i class="fa fa-question-circle fa-sm"></i></label>
                                        <input type="text" class="form-control cp-component portalsettings" name="secondarythemecolor" id="inputsecondarytheme" value="<?php echo $secondarythemecolor;?>" placeholder="Secondary Theme Color" required="true">
                                    </div>-->
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label" >Button Font Color <i class="fa fa-question-circle fa-sm" title="The font color within buttons"></i></label>
                                        <input type="text" class="form-control cp-component portalsettings" name="buttonfontcolor" id="inputbuttonfontcolor" value="<?php echo $buttonfontcolor;?>" placeholder="Buttons Font Color" required="true">
                                    </div>
                                    
                                </div>
                        
                        
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="form-control-label" >Header Logo </label>
                                        <input type="file" class="form-control unhidelogo" id="inputImageLogo"  accept=".jpeg, .jpg, .jpe,.png">
                                        <input type="hidden"  id="headerimageLogo"  <?php if(!empty($headelogo)){ echo 'value="'.$headelogo.'"';}?> >
                                    </div>
                                    
                                    <div class="headerimagecropperLogo" >
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <!-- <h3>Demo:</h3> -->
                                                    <div class="img-container">
                                                        <?php if(!empty($headelogo)){
                                                            
                                                             echo '<img id="headerbgimgLogo" src="'.$headelogo.'" alt="">';
                                                            
                                                        }else{
                                                            
                                                            echo '<img id="headerbgimgLogo" src="" alt="">';
                                                        }?>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                        <!-- <h3>Preview:</h3> -->
                                                       
                                                        <div class="row">
                                                        <div class="form-group col-md-10">
                                                                <label class="form-control-label" for="dataHeightLogo">Width</label>
                                                                <input type="text" class="form-control"   id="dataWidthLogo" placeholder="Width in px" title="Width in px"readonly="true">
                                                                
                                                        </div></div><div class="row">
                                                        <div class="form-group col-md-10">
                                                                <label class="form-control-label" for="dataHeightLogo">Height</label>
                                                                <input type="text" class="form-control"   id="dataHeightLogo" placeholder="Height in px" title="Height in px"readonly="true">
                                                                
                                                        </div></div>
                                                        <div class="row">
                                                        <div class="form-group col-md-11">
                                                                
                                                            <div class="previewDivselectedImageLogo previewDivselectedImage "></div>
                                                                
                                                        </div></div>
                                                    </div>
                                                    

                                                    <!-- <h3>Data:</h3> -->

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9 logo-docs-buttons docs-buttons">
                                                    <!-- <h3>Toolbar:</h3> -->


                                                    <div class="logo-btn-group btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom In">
                                                                <span class="fa fa-search-plus"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom Out">
                                                                <span class="fa fa-search-minus"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                    <div class="logo-btn-group btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Left">
                                                                <span class="fa fa-arrow-left"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Right">
                                                                <span class="fa fa-arrow-right"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Up">
                                                                <span class="fa fa-arrow-up"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Down">
                                                                <span class="fa fa-arrow-down"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                    <div class="logo-btn-group btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Left">
                                                                <span class="fa fa-undo"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Right">
                                                                <span class="fa fa-repeat"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                  

                                                    <div class="logo-btn-group btn-group">
                                                       <button type="button" class="btn btn-success btn-md hidebtnlogo " data-method="getCroppedCanvas" data-toggle="tooltip" data-placement="top" title="Apply Changes">
                                                          <!--   <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Apply Changes">
                                                                <span class="fa fa-check"></span> 
                                                            </span> -->
                                                       Apply Changes </button>
                                                       
                                                    </div>


                                             <!-- My code Shehroze starts -->

                                                     <div class="logo-btn-group btn-group">
                                                        <button type = "button" class="btn btn-md btn-danger fa fa-trash removelogo" data-method="deleteimage" >
                                                           <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Remove Logo Image">
                                        
                                                            </span>

                                                        </button>
                                                       
                                                    </div>

                                         <!-- My code Shehroze  ends -->
                
                                                </div><!-- /.docs-buttons -->


                                            </div>
                                        </div>
                                    <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="form-control-label" >Site Favicon </label>
                                        <input type="file" class="form-control unhidefavicon" id="inputImageFavicon"  accept=".jpeg, .jpg, .jpe,.png">
                                        <input type="hidden"  id="headerimageFavicon" <?php if(!empty($sitefavicon)){ echo 'value="'.$sitefavicon.'"';}?> >
                                    </div>
                                    
                                    <div class="headerimagecropperFavicon" >
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <!-- <h3>Demo:</h3> -->
                                                    <div class="img-container">
                                                        <?php if(!empty($sitefavicon)){
                                                            
                                                             echo '<img id="headerbgimgFavicon" src="'.$sitefavicon.'" alt="">';
                                                            
                                                        }else{
                                                            
                                                            echo '<img id="headerbgimgFavicon" src="" alt="">';
                                                        }?>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                        <!-- <h3>Preview:</h3> -->
                                                        
                                                        <div class="row">
                                                        <div class="form-group col-md-10">
                                                                <label class="form-control-label" for="dataHeightFavicon">Width</label>
                                                                <input type="text" class="form-control"   id="dataWidthFavicon" placeholder="Width in px" title="Width in px"readonly="true">
                                                                
                                                        </div></div><div class="row">
                                                        <div class="form-group col-md-10">
                                                                <label class="form-control-label" for="dataHeightFavicon">Height</label>
                                                                <input type="text" class="form-control"   id="dataHeightFavicon" placeholder="Height in px" title="Height in px"readonly="true">
                                                                
                                                        </div></div>
                                                        <div class="row">
                                                        <div class="form-group col-md-11">
                                                                
                                                            <div class="previewDivselectedImageFavicon previewDivselectedImage"></div>
                                                                
                                                        </div></div>
                                                    </div>
                                                    

                                                    <!-- <h3>Data:</h3> -->

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9 favicon-docs-buttons docs-buttons">
                                                    <!-- <h3>Toolbar:</h3> -->


                                                    <div class="favicon-btn-group btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom In">
                                                                <span class="fa fa-search-plus"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom Out">
                                                                <span class="fa fa-search-minus"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                    <div class="favicon-btn-group btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Left">
                                                                <span class="fa fa-arrow-left"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Right">
                                                                <span class="fa fa-arrow-right"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Up">
                                                                <span class="fa fa-arrow-up"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Down">
                                                                <span class="fa fa-arrow-down"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Left">
                                                                <span class="fa fa-undo"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Right">
                                                                <span class="fa fa-repeat"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                  

                                                    <div class="favicon-btn-group btn-group">
                                                                     <button type="button" class="btn btn-success btn-md hidebtnfavicon" data-method="getCroppedCanvas"  data-toggle="tooltip" data-placement="top" title="Apply Changes">
                                                          <!--   <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Apply Changes">
                                                                <span class="fa fa-check"></span>
                                                            </span> -->
                                                      Apply Changes  </button>
                                                       
                                                    </div>


                                                    <!-- My code Shehroze starts -->

                                                     <div class="favicon-btn-group btn-group">
                                                        <button type = "button" class="btn btn-md btn-danger fa fa-trash removefavicon" data-method="deleteimage" >
                                                           <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Remove Favicon Image">
                                        
                                                            </span>

                                                        </button>
                                                       
                                                    </div>

                                  <!-- My code Shehroze  ends -->


                
                                                </div><!-- /.docs-buttons -->


                                            </div>
                                        </div>
                        
                                    <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="form-control-label" >Header Image (1800 x 230)</label>
                                        <input type="file" class="form-control unhideheader" id="inputImage"  accept=".jpeg, .jpg, .jpe,.png">
                                        <input type="hidden"  id="headerimage" <?php if(!empty($mainheaderbackground)){ echo 'value="'.$mainheaderbackground.'"';}?>>
                                    </div>
                                    
                                    <div class="headerimagecropper" >
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <!-- <h3>Demo:</h3> -->
                                                    <div class="img-container">
                                                        <?php if(!empty($mainheaderbackground)){
                                                            
                                                             echo '<img id="headerbgimg" src="'.$mainheaderbackground.'" alt="">';
                                                            
                                                        }else{
                                                            
                                                            echo '<img id="headerbgimg" src="" alt="">';
                                                        }?>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                        <!-- <h3>Preview:</h3> -->
                                                        
                                                        <div class="row">
                                                        <div class="form-group col-md-10">
                                                                <label class="form-control-label" for="dataHeight">Width</label>
                                                                <input type="text" class="form-control"   id="dataWidth" placeholder="Width in px" title="Width in px"readonly="true">
                                                                
                                                        </div></div><div class="row">
                                                        <div class="form-group col-md-10">
                                                                <label class="form-control-label" for="dataHeight">Height</label>
                                                                <input type="text" class="form-control"   id="dataHeight" placeholder="Height in px" title="Height in px"readonly="true">
                                                                
                                                        </div></div>
                                                        <div class="row">
                                                        <div class="form-group col-md-11">
                                                                
                                                            <div class="previewDivselectedImageheader previewDivselectedImage"></div>
                                                                
                                                        </div></div>
                                                    </div>
                                                    

                                                    <!-- <h3>Data:</h3> -->

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-9 header-docs-buttons docs-buttons">
                                                    <!-- <h3>Toolbar:</h3> -->


                                                    <div class="header-btn-group btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom In">
                                                                <span class="fa fa-search-plus"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Zoom Out">
                                                                <span class="fa fa-search-minus"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                    <div class="header-btn-group btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Left">
                                                                <span class="fa fa-arrow-left"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Right">
                                                                <span class="fa fa-arrow-right"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Up">
                                                                <span class="fa fa-arrow-up"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Move Down">
                                                                <span class="fa fa-arrow-down"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                    <div class="header-btn-group btn-group">
                                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Left">
                                                                <span class="fa fa-undo"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                            <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Rotate Right">
                                                                <span class="fa fa-repeat"></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                  

                                                    <div class="header-btn-group btn-group">
                                                       <button type="button" class="btn btn-success btn-md hidebtnheader" data-method="getCroppedCanvas" data-toggle="tooltip" data-animation="false" data-placement="top" title="Apply Changes">
                                                           <!--  <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Apply Changes">
                                                                <span class="fa fa-check"></span>
                                                            </span> -->
                                                       Apply Changes </button>
                                                       
                                                    </div>

                                                    <!-- My code Shehroze starts -->

                                                     <div class="header-btn-group btn-group">
                                                        <button type = "button" class="btn btn-md btn-danger fa fa-trash removeheader" data-method="deleteimage" onclick=""  >
                                                           <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Remove Header Image">
                                        
                                                            </span>

                                                        </button>
                                                       
                                                    </div>

                                                     <!-- My code Shehroze  ends -->


                
                                                </div><!-- /.docs-buttons -->


                                            </div>
                                        </div>
                                    
                        
                                    
                               
                                
                            <!--                            <p><h5 class="card-title">Terminology</h5></p>
                                <p><h6 class="card-title">Set your site's terminology below</h6></p>
                                 <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label" >Exhibitor </label>
                                        <input type="text" class="form-control portalsettings" value="<?php echo $exhibitorterminology;?>" name="exhibitorterminology" id="inputexhibitorterminology" >
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label">Booth </label>
                                        <input type="text" class="form-control portalsettings" value="<?php echo $boothterminology;?>" name="boothterminology" id="inputboothterminology" >
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label" >Package </label>
                                        <input type="text" class="form-control portalsettings" value="<?php echo $packageterminology;?>" name="packageterminology" id="inputpackageterminology" >
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label">Add-Ons </label>
                                        <input type="text" class="form-control portalsettings" value="<?php echo $addonsterminology;?>" name="addonsterminology" id="inputaddonsterminology" >
                                    </div>
                                </div>
                        
                            <div class="card-header egmb-20">
                                <h4 class="card-title">Entry Wizard</h4>
                            </div>
                            
                        <p><h6 class="card-title">The Entry Wizard is where you configure the pages  and flow that you end-users will go through before becoming users in the system.Click "Entry Wizard" to configure your user entry wizard flow. </h6></p>
                            
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <button class="btn btn-large btn-success">Entry Wizard</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                    <label class="form-control-label" >Enable Application Moderation </label>
                                    <input type="checkbox" class="toggle-one form-control" id="applicationmoderationstatus" data-toggle="toggle" <?php echo $applicationmoderationstatus;?>>
                            </div>
                        </div>
                        
                            <div class="card-header egmb-20">
                                <h4 class="card-title">Booth Assignment Settings</h4>
                            </div>
                        <br>
                            <p><h5 class="card-title">Visibility Settings</h5></p>
                            
                             Default unchecked 
                            
                            <div class="row">
                                    <div class="form-group col-sm-12">
                                        
                                        <div class="form-check">
                                                <input class="form-check-input" type="radio" name="hideexhibitordetails" id="hideexhibitordetails" value="option1" >
                                                <label class="form-check-label egradio-inline" for="hideexhibitordetails">
                                                    Hide Exhibitor Details
                                                </label>
                                                <p>Hides all exhibitor details from the public floor plan view. Booths will be just be labeled "Available, Reserved, or Occupied"</p>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="hidereservedboothexhibitordetails" id="hidereservedboothexhibitordetails" value="option2">
                                                <label class="form-check-label egradio-inline" for="hidereservedboothexhibitordetails">
                                                    Hide Reserved Booth Exhibitor Details
                                                </label>
                                                <p>Hides all exhibitor details for "Reserved" booths only</p>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="hideboothprice" id="hideboothprice" value="option3" >
                                                <label class="form-check-label egradio-inline" for="hideboothprice">
                                                    Hide Booth Price on Public View
                                                </label>
                                                <p>Hides booth prices from non-exhibitor view.</p>
                                            </div>
                                        
                                            <div class="form-check disabled">
                                                <input class="form-check-input" type="radio" name="displaycompnaynameonbooth" id="displaycompnaynameonbooth" value="option3" >
                                                <label class="form-check-label egradio-inline" for="displaycompnaynameonbooth">
                                                    Display Company Name on Booth
                                                </label>
                                                <p>This shows both the booth number and company name on assigned booths. If this is unchecked, then the company name can be viewed by hovering over the booth, clicking into the booth, or selecting it on the exhibitor list.</p>
                                            </div>
                                        <br>
                                        <p><h4 class="card-title">Booth Selection Order</h4></p>
                                    
                                    </div>
                                </div>
                            
                                <div class="card-header egmb-20">
                                    <h4 class="card-title">Payment Settings</h4>
                                </div>
                            
                                <p><h5 class="card-title">Payment Options</h5></p>
                                
                                <div class="row">
                                <div class="form-group col-sm-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="stripeoption" id="stripeoption" value="option1" >
                                                <label class="form-check-label egradio-inline" for="stripeoption">
                                                    Stripe
                                                </label>
                                               
                                            </div>
                                </div>
                                <div class="form-group col-sm-5">
                                    <input type="text" class="form-control" id="stipesecruitkey" placeholder="Secret Key">
                                </div>
                                <div class="form-group col-sm-5">
                                    <input type="text" class="form-control" id="stripepublishkey" placeholder="Publisher Key">
                                </div>
                                </div>
                        
                                <div class="row">
                                <div class="form-group col-sm-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="authorizenet" id="authorizenet" value="option1" >
                                                <label class="form-check-label egradio-inline" for="authorizenet">
                                                    Authorize.net
                                                </label>
                                               
                                            </div>
                                </div>
                                <div class="form-group col-sm-5">
                                    <input type="text" class="form-control" id="authorizesecruitkey" placeholder="Secret Key">
                                </div>
                                <div class="form-group col-sm-5">
                                    <input type="text" class="form-control" id="authorizepublishkey" placeholder="Publisher Key">
                                </div>
                                </div>
                        
                        
                                <div class="row">
                                <div class="form-group col-sm-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="paypaleoption" id="paypaleoption" value="option1" >
                                                <label class="form-check-label egradio-inline" for="paypaleoption">
                                                    PayPal
                                                </label>
                                               
                                            </div>
                                </div>
                                <div class="form-group col-sm-5">
                                    <input type="text" class="form-control" id="paypalsecruitkey" placeholder="Secret Key">
                                </div>
                                <div class="form-group col-sm-5">
                                    <input type="text" class="form-control" id="paypalpublishkey" placeholder="Publisher Key">
                                </div>
                                </div>
                        
                                <div class="row">
                                <div class="form-group col-sm-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="paypaleoption" id="paypaleoption" value="option1" >
                                                <label class="form-check-label egradio-inline" for="paypaleoption">
                                                    Check
                                                </label>
                                               
                                            </div>
                                </div>
                                <div class="form-group col-sm-5">
                                    
                                </div>
                                <div class="form-group col-sm-5">
                                    
                                </div>
                                </div>-->
    							
    							 <br>
                      <br>
                                <div class="card-header egmb-20">
                                    <h4 class="card-title">User Application Settings</h4>
                                </div>
    							
<!--                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="form-control-label" >Apty Code </label>
                                        <textarea id="aptycode" name="aptycode" class="form-control portalsettings" ><?php echo $aptycode;?></textarea>
                                    </div>
                                </div>       -->
    							
                                <div class="card-header egmb-20">
                                    <h4 class="card-title">Notification Settings</h4>
                                </div>
                        
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label class="form-control-label" >Registration Notification Emails </label>
                                        <input type="text" class="form-control portalsettings" value="<?php echo $registration_notificationemails;?>" name="registration_notificationemails" id="registrationnoticationemails" >
                                    </div>
                                    <div class="form-group" style="display:none;">
                                        <label class="form-control-label">Order Notification Emails:  </label>
                                        <input type="text" class="form-control portalsettings" value="<?php echo $ordernotficationemails;?>" name="ordernotficationemails" id="ordernotifcationemails" >
                                    </div>
                                </div>
                        <br>
                         <br>
                      <div class="form-group row">
                                        <label class="col-sm-4 form-control-label"></label>
                                        <div class="col-sm-8">
                                                 <button type="submit"  name="addsettings"  class="btn btn-lg mycustomwidth btn-success" value="Update">Update</button>
                                                
                                            
                                        </div>
                                    </div>
                        
                        </form>
                   
                    </div>
                        
                    </div>
                    
                    <div role="tabpanel" class="tab-pane fade" id="tabs-1-tab-2">
                           	<div class="box-typical box-typical-padding">
                           		<div class="card-header egmb-20">
                               <p>This is where you can configure the flow for your users to be guided through during a new registration process. Use the following URL to send out to your users to register or login:</p>
                               <p><a href="<?php echo site_url().'/entry-wizard/';?>" target="_blank">Registration URL</a></p>
                               <p><a href="<?php echo site_url().'/login/';?>" target="_blank">Login URL</a></p>
                           		</div>
                           		<br>
                           		<form method="post" action="javascript:void(0);" onSubmit="exhibitor_entry_flow_settings_update()">
                           			<div class="form-group row">
                           				<div class="col-sm-3 ">
                           					<input type="checkbox" data-on="Enabled" data-off="Disabled" <?php echo $exhibitorflowstatus[ 'status'];?> class="toggle-one" value='checked' id="exhibitorentryflow" data-toggle="toggle">
                           					<p style="margin-top: 10px;">Registration</p>
                           				</div>
                           				<div class="col-sm-3 ">
                           					<input type="checkbox" class="toggle-one" id="applicationmoderationstatus" data-toggle="toggle" <?php echo $applicationmoderationstatus;?>>
                           					<p style="margin-top: 10px;">Application Moderation <i data-toggle="tooltip" title="" class="fa fa-question-circle" aria-hidden="true" data-original-title="Enable this setting if you want to moderate user applications before they gain access into this event portal. If this is disabled, when a user completes the form the system will immediately create an account for that user and send them the Welcome Email with login credentials."></i></p>
                           				</div>
                           				<div class="col-sm-3" style="margin-top: 7px;"><a target="_blank" href="<?php echo site_url().'/entry-wizard/?preview=on';?>" style="float: right;" class="btn mycustomwidth btn-success">Preview</a></div>
                           				<div class="col-sm-3" style="margin-top: 7px;">
                           					<button style="float: right;" type="submit" name="savealltask" class="btn mycustomwidth btn-success" value="Register">Save All Changes</button>
                           				</div>
                           			</div>
                           	</div>
                           	<div class="form-group row" id="flowchart" <?php echo $flowsharthideclass;?>>
                           		<div class="col-sm-1"></div>
                           		<div class="col-sm-10">
                           			<table id='table-draggable1'>
                           				<tbody>
                           					<?php foreach($exhibitorEntryLevel as $levelKey=>$levelData){
                                                                   
                                                                   $levelstatusclass = "";
                                        $pageURL = $levelData['slug'];
                                        $levelstatuscheckbox = "";
                                        $disableclass="";
                                        $statusactive = "";
                                        
                                        foreach($savedexhibitorEntryLevel as $savedkey=>$savedpagedata){
                                            
                                            if($pageURL == $savedpagedata['slug']){
                                                
                                                $statusactive = $savedpagedata['statusactive'];
                                            }
                                            
                                            
                                        }
                                        
                                        
                                        
                                        
                                        if($statusactive == 1){
                                            
                                            $levelstatuscheckbox = "checked";
                                        }
                                        if($levelData['status'] == 'optional' ||  $levelData['status'] == 'optional-2'){
                                            
                                            $levelstatusclass = "eg-optional";
                                                    
                                        }else{
                                            
                                            $levelstatusclass = "eg-requried";
                                            $disableclass = "disabled";
                                        }?>
						<tr>
							<td>
								<input type="hidden" id="title-<?php echo $levelData['slug'];?>" value="<?php echo $levelData['name'];?>" />
								<input type="hidden" id="url-<?php echo $levelData['slug'];?>" value="<?php echo $levelData['url'];?>" />
								<input type="hidden" id="slug-<?php echo $levelData['slug'];?>" value="<?php echo $levelData['slug'];?>" />
								<input type="hidden" id="status-<?php echo $levelData['slug'];?>" value="<?php echo $levelData['status'];?>" />
								<input type="hidden" id="description-<?php echo $levelData['slug'];?>" value="<?php echo $levelData['description'];?>" />
								<input type="hidden" id="icon-<?php echo $levelData['slug'];?>" value="<?php echo $levelData['icon'];?>" />
								<div id="<?php echo $levelData['slug'];?>" class="saveeverything eg-boxed row <?php echo $levelstatusclass;?>">
									<div class="col-sm-4">
										<h2><?php echo $levelData['name'];?></h2> </div>
									<div class="col-sm-6">
										<p>
											<?php echo $levelData['description'];?>
										</p>
										<?php if($levelData['name'] == "Intro"){?>
											<p class="eg-editlink"><a href="<?php echo site_url().'/content-editor/';?>" target="_blank">Update the Intro Content Here</a></p>
											<? }else if($levelData['name'] == "Register"){?>
												<p class="eg-editlink"><a href="<?php echo site_url().'/user-fields/';?>" target="_blank">Update the Registration Form Here</a></p>
												<? }else if($levelData['name'] == "Confirmation"){?>                       
                          <p class="eg-editlink"><a href="<?php echo site_url().'/content-editor/';?>" target="_blank">Update Content on the Confirmation Page Here</a></p>
                          <? }else if($levelData['name'] == "Select Package"){?>
													<p class="eg-editlink"><a href="<?php echo site_url().'/add-new-package/';?>" target="_blank">Create Your Packages Here</a></p>
													<? }else if($levelData['name'] == "Select Booth"){?>
														<p class="eg-editlink"><a href="<?php echo site_url().'/floor-plan-editor/';?>" target="_blank">Sell Your Booths Here</a></p>
														<? }else if($levelData['name'] == "Add-Ons"){?>
															<p class="eg-editlink"><a href="<?php echo site_url().'/add-new-product/?producttype=addons';?>" target="_blank">Create Your Add-Ons Here</a></p>
															<? }?>
									</div>
									<?php if($levelstatusclass != "eg-requried"){?>
										<div class="col-sm-2">
											<input type="checkbox" <?php echo $levelstatuscheckbox. ' '. $disableclass;?> class="toggle-one eg-toggle" id="<?php echo $levelData['slug'].'-status';?>" data-toggle="toggle"></div>
								</div>
								<?php }else{?>
									<input type="checkbox" style="display: none;" checked="checked" id="<?php echo $levelData['slug'].'-status';?>">
									<?php }?>
              	</div>
                       	 </td>
                       	  	</tr>
                    		<?php }?>
                       			</tbody>
                       			</table>
                       	</div>
                       	<div class="col-sm-1"></div>
                       </div>
                       <br>
                       <hr>
                       <div class="form-group row">
                       	<div class="col-sm-10">
                       		<button type="submit" name="savealltask" class="btn btn-lg mycustomwidth btn-success" value="Register">Save All Changes</button>
                       	</div>
                       </div>
                       </form>

            </div>
                                    <!--//********************************************************************************/
                                    /*            Booth Management Style Start                                        */
                                    /********************************************************************************/-->
                   <?php
                    $floor_Plan_Settings = 'floorPlanSettings';
                    $Booth_Queue_Settings='boothQueueSettings';
                    $get= get_option($floor_Plan_Settings);
                    $get_booth_settings= get_option($Booth_Queue_Settings);
                    $boothTemplateTitle = 'AR_Contentmanager_Email_Template_booth';
                    $sponsor_info = get_option($boothTemplateTitle); 
                    // echo "<pre>";
                    // print_r($sponsor_info);
                    
                    // echo "<pre>";
                    // print_r($get);
                    
                    $floorPlanArray = array(
                      '0' => 'Auto and Email',
                      '1' =>  'Auto and No Email',
                      '3' =>  'Nothing',
                      '4' =>  'Yes',
                      '5' =>  'No',
                      '6' =>  'Open',
                      '7' =>  'Close',
                      '8' => 'Hide_Details',
                      '9' =>'Hide_Reserved_Booth',
                      '10' =>'Hide_Booth_price',
                      '11' =>'Show_Company_Name',
                      '12' =>'checked');
                    $selected7=$get['usersNum'];
                    $selected11=$get['zoom'];
                    $selected9=$get['tableSort'];
                    $selected10=$get['PrePaidChk'];
                    $email_selected=$get['Email_Selection'];
                  
                    if(($selected9!='checked'))
                    {?>
                    <style type="text/css">
                      #table_id_AD_1{
                        display:none;
                      }
                      #updt_btn_table{
                        display:none;
                      }
                      #section_table{
                        display:none;
                      }
                      #table-div{
                        display:none;
                      }
                      #Queue_Behavior{
                        display:none;
                      }
                    </style>
                   
                    <?php }
                    if(array_search($get_booth_settings['Open_users'],$floorPlanArray)!==false)
                    {
                      $selected1=$get_booth_settings['Open_users'];
                    }

                    if(array_search($get_booth_settings['Email_Selection'],$floorPlanArray)!==false)
                    {
                      $selected2=$get_booth_settings['Email_Selection'];
                    }
                    if(array_search($get_booth_settings['Deafult_status'],$floorPlanArray)!==false)
                    {
                      $selected3=$get_booth_settings['Deafult_status'];
                    }
                    if(!empty($get['Hide_exhibitor_Details']))
                    {
                        if(array_search($get['Hide_exhibitor_Details'],$floorPlanArray)!==false)
                        {
                          $selected78=$get['Hide_exhibitor_Details'];
                        }
                    }
                    if(!empty($get['Hide_reserved_Details']))
                    {
                        if(array_search($get['Hide_reserved_Details'],$floorPlanArray)!==false)
                        {
                          $selected4=$get['Hide_reserved_Details'];
                        }
                    }
                    if(!empty($get['Hide_Price']))
                    {
                        if(array_search($get['Hide_Price'],$floorPlanArray)!==false)
                        {
                          $selected5=$get['Hide_Price'];
                        }
                    }
                    if(!empty($get['Hide_Company_Name']))
                    {
                        if(array_search($get['Hide_Company_Name'],$floorPlanArray)!==false)
                        {
                          $selected6=$get['Hide_Company_Name'];
                        }
                    }
                 
                   
                   
                    
                     
                   
                   ?>
                    <div role="tabpanel" class="tab-pane fade" target="_self" id="tabs-1-tab-3">
   
                       <div class="box-typical box-typical-padding" >
                             <div class="card-header egmb-20" style="height: 10rem;">
                           			<p>Set the default settings for online booth selection/purchase.</p>
                           			<p>Only Update these settings if you are allowing users to self-select/purchase booths directly from the live floor plan.</p>
                                 <button style="float: right;" id="updt_btn" type="submit"  name="addsettings"  class="btn btn-lg my-button mycustomwidth btn-success" value="Update">Save All Changes</button>
                              </div>
                           		<br>
                                <div style="display: flex;justify-content: space-between;      margin-top: 20px;">
                                            <h3>
                                                   Enable Booth Selection Queue
                                            </h3>
                                          <label class="switch">
                                              <input id="toggle_btn" type="checkbox" value="checked" <?php echo $selected9=='checked'? 'checked':'unchecked';?> >
                                              <span class="slider round"></span>
                                          </label>
                                         
                                          <button style="margin-right: 20px;" id="updt_btn_table" type="submit" name="addsettings" class="btn btn-lg mycustomwidth btn-success" value="Update">Update Queue</button>
                                 </div> 
                                        <br>
                                 <section class="faq-page-cats" Id="section_table" style="border-bottom:none;margin-bottom:30px;">
                                    <div class="row">


                                        <div class="col-md-3 filtersarraytooltip">
                                            <div Id ="SELECT" class="faq-page-cat"  placement='bottom' >
                                                <div class="faq-page-cat-icon">
                                                  <i Id ="SELECT_Icon" class="reporticon font-icon fa fa fa-filter fa-2x"></i>
                                              </div>
                                                <div class="faq-page-cat-title" >
                                                    <h6>
                                                    Select All:
                                                    </h6>
                                      
                                                    <label style="  margin-left: -19px !important;" class="switch">
                                                        <input  style="margin-left: 5px;margin-top: 2px;" id='select_all_user' type='checkbox' class='checkcheckedstatus' name='id[]'>
                                                          <span class="slider round">
                                        
                                                          </span>
                                                    </label>
                                                </div>
                                              
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="faq-page-cat">
                                                <div class="faq-page-cat-icon"><i  class=" reporticon font-icon  fa fa-plus fa-2x"></i></div>
                                                <div class="faq-page-cat-title " id="add_newUser" placement='bottom' style="cursor: pointer;" >
                                                <button type="button" style=" color: white !important;border-color: #5690c7;background-color: #7cb5ec;"  class="btn btn-inline btn-square-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Add User to Queue
                                                    </button>
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="faq-page-cat" >
                                                <div class="faq-page-cat-icon"><i class="bulkbtuton reporticon font-icon fa fa-users fa-2x"></i></div>

                                                <div class="faq-page-cat-txt">

                                                    <div class="btn-group">
                                                        <button disabled type="button" id="newsendbulkemailstatus" class="btn btn-inline dropdown-toggle btn-square-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Bulk Action
                                                            <span class="label label-pill label-danger" id="newbulkemailcounter">0</span>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                        <a class="dropdown-item" id="activate_users" ><i class="fa fa-eye"></i> Open</a>
                                                        <a class="dropdown-item" id="close_users"><i class=" fa fa-eye-slash"></i> Close</a>
                                                        <a class="dropdown-item" id="turn_the_users"><i class=" fa fa-user-plus"></i> Assign Turn in Queue</a>
                                                        <a class="dropdown-item" id="remove_users"><i class="fa fa-window-close"></i> Remove From Queue</a>
                                                            
                                                            

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            </div>
                                            <div class="col-md-3">
                                            <div class="faq-page-cat" >
                                                <div class="faq-page-cat-icon"><i class="bulkbtuton reporticon "></i></div>

                                                <div class="faq-page-cat-txt" style="margin-top: 42px;">

                                                    <div class="btn-group" >
                                                        <button disabled type="button" id="newsendbulkemailstatus" class="btn btn-inline btn-square-icon" style=" display: grid" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Current Turn
                                                            <p  id="turn-counter">0</p>
                                                        </button>
                                                        
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div><!--.row-->
                                  </section>
                           <div class="box-typical-padding" id="table-div" style="height: 500px;overflow-y: auto;">
                               <table style="    margin-top: 50px;" id="table_id_AD_1" class="bulkeditfield_AD_1  table-bordered compact dataTable  cardsfields" width="100%">
                                     <thead>
                                                <tr class="text_th">

                                                  
                                                    <th>Action</th>
                                                  
                                                </tr>
                                      </thead>
                                      <tbody>
                                              <tr style="display: none;"></tr>
                                              <?php
                                              
                                                $blog_id = get_current_blog_id();
                                                $args = array(
                                                  'role__not_in' => 'Administrator',
                                                  
                                  
                                                  );
                                              $user_query =new WP_User_Query( $args  );
                                              $lisstofuser = $user_query->get_results();
                                              $arr = array();
                                              $count=1;
                                                foreach($lisstofuser as $key=> $a_value) {
                                                  // echo "<pre>";
                                                  // print_r($a_value);
                                                
                                                  $user_Info=get_user_meta($a_value->ID,'wp_'.$blog_id.'_company_name');
                                                  $user_Priroty_Num=get_user_meta($a_value->ID,'wp_'.$blog_id.'_priorityNum');
                                                  $user_ID=get_user_meta($a_value->ID,'ID');
                                                  $user_option=get_user_meta($a_value->ID,'wp_'.$blog_id.'_myTurn');
                                                  $user_Status=get_user_meta($a_value->ID,'wp_'.$blog_id.'_userBoothStatus');
                                                  $user_Remove_status=get_user_meta($a_value->ID,'wp_'.$blog_id.'_RemoveFromQueue');
                                                  // echo "<pre>";
                                                  // print_r(  $user_option[0]);
                                                  if(empty($user_Remove_status[0]))
                                                  {
                                                  array_push($arr,(object)[
                                                  'Email' => $user_Info[0],
                                                  'PrirotyNumber' => $user_Priroty_Num[0],
                                                  'Id' => $a_value->ID,
                                                  'Status'=>$user_Status[0],
                                                  'turn'=> $user_option[0],
                                                  'StatusRemove'=>$user_Remove_status[0],
                                                  ]);
                                                
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
                                                      
                                                $currentturnnumnber = array();
                                                foreach($arr as $key=> $a_value) {
                                                  $status=$a_value->Status;
                                                  //  echo "<pre>";
                                                  // print_r($a_value);
                                                   
                                                  if ($status=='checked') {
                                                    $status='checked';
                                                    $title='Active';
                                                    }
                                                    else {
                                                      $status='unchecked';
                                                      $title='Close';
                                                  }
                                                  if($a_value->PrirotyNumber=='' ||$a_value->PrirotyNumber=='-')
                                                  {
                                                    $a_value->PrirotyNumber="-";   
                                                  }
                                                  if($a_value->turn=='')
                                                  {
                                                    $count++;
                                                    echo "<tr    class='unselect'>";
                                                    echo "<td style='    width: 6px;
                                                    margin-top: 17px;'><input type='checkbox' id='select_one' name='status_check' class='checkcheckedstatus' name='id[]' value='".$a_value->Id."'></td><td style='     margin-top: -23px;
                                                    font-weight: bold;
                                                    margin-left: 20px;
                                                    color: #adb7be;'/>"
                                                    .$a_value->PrirotyNumber.
                                                    "</td><td title=".$a_value->Email." id='".$a_value->Id."' class='RowName' style='    width: 97px;
                                                    margin-left: 54px;
                                                    white-space: nowrap;
                                                    overflow: hidden !important;
                                                    text-overflow: ellipsis;
                                                    font-size: 13px;
                                                    margin-top: -25px;
                                                    '>".
                                                    $a_value->Email .  
                                                  
                                                    "</td><td  title=".$title." style='
                                                    display: flex;
                                                    justify-content: flex-end;
                                                    width: 223px;
                                                    margin-top: -54px'><input type='checkbox' name='active_close_check'  data-on='Open' data-off='Close' ".$status." id='User_PRI_Btn".$a_value->Id."' data-toggle='toggle' data-size='xs'> </td>";
                                                
                                                    echo "</tr>"; 
                                                  }else{
                                                    array_push($currentturnnumnber,$a_value->PrirotyNumber);
                                                    //$currentturnnumnber = $count;
                                                    
                                                    // $currentturnnumnber = $a_value->PrirotyNumber;
                                                    echo "<tr  style='background-color: #cbeeff;'  class='unselect'>";
                                                    echo "<td style='    width: 6px;
                                                    margin-top: 17px;'><input type='checkbox' id='select_one' name='status_check' class='checkcheckedstatus' name='id[]' value='".$a_value->Id."'></td><td style='     margin-top: -23px;
                                                    font-weight: bold;
                                                    margin-left: 20px;
                                                    color: #adb7be;'/>"
                                                    .$a_value->PrirotyNumber.
                                                    "</td><td title=".$a_value->Email." id='".$a_value->Id."' class='RowName' style='    width: 97px;
                                                    margin-left: 54px;
                                                    white-space: nowrap;
                                                    overflow: hidden !important;
                                                    text-overflow: ellipsis;
                                                    font-size: 13px;
                                                    margin-top: -25px;
                                                    '>".
                                                    $a_value->Email .  
                                                  
                                                    "</td><td  title=".$title." style='
                                                    display: flex;
                                                    justify-content: flex-end;
                                                    width: 223px;
                                                    margin-top: -54px'><input type='checkbox' name='active_close_check'  data-on='Open' data-off='Close' ".$status." id='User_PRI_Btn".$a_value->Id."'  data-toggle='toggle' data-size='xs'> </td>";
                                                
                                                    echo "</tr>";
                                                  }
                                                
                                                  
                                                }
                                        
                                            
                                              ?>
                                            
                                   </tbody>
                              </table>    
                       </div>  
                        <div class="card-header" style="margin-bottom: 30px;margin-top: 20px;"></div>
                           		
                            
                              <div id="Queue_Behavior"style="border-bottom: 1px solid #d8e2e7;padding-bottom: 60px;">
                                    <h2 style="margin-buttom:3px;    ;">  
                                         Booth Queue Behavior
                                    </h2> 
                                    
                                 
                                  <div class="row">
                                      <div class="form-group col-sm-6">
                                        <label class="form-control-label" >When Open User Selects Booth, THEN:</label>
                                        <select id="Open_users" class="form-control portalsettings" style="width: 100%;margin-right: 100px;">
                                            <option <?php echo $selected1=='Auto and Email'?'selected':''?> value="Auto and Email">Automatically Open Next Queue & Send Email</option>
                                            <option <?php echo $selected1=='Auto and No Email'?'selected':''?> value="Auto and No Email">Automatically Open Next Queue & Do NOT send Email</option>
                                            <option <?php echo $selected1=='Nothing'?'selected':''?> value="Nothing">Do Nothing</option>
                                          </select>
                                      </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label" for="inputEventend"> Select Email:</label>
                                        <select id="Email_Selection" class="form-control portalsettings" style="width: 100%;border-radius: 5px;">
                                        <?php    foreach ($sponsor_info as $key=>$value) {
                                          if($key == $email_selected){
                                          $template_name = ucwords(str_replace('_', ' ', $key));
                                          echo '<option value="' . $key . '" selected="selected">'.$template_name.'</option>';  
                                        }else{
                                          $template_name = ucwords(str_replace('_', ' ', $key));
                                          echo '<option value="' . $key . '">'.$template_name.'</option>';  
                                        } }?>
                        
                                           
                                        </select>
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-sm-12" style="    width: 99%;">
                                        <label class="form-control-label" for="inputEventStart"> Default Status for Unassigned and New Users:</label>
                                        <select id="Deafult_status" class="form-control portalsettings" style="border-radius: 5px;">
                                            <option <?php echo $selected3=='Open'?'selected':''?> value="Open">Open</option>
                                            <option  <?php echo $selected3=='Close'?'selected':''?>  value="Close">Closed</option>
                                           
                                          </select>
                                    </div>
                                </div>
                                </div>
                                     

                              </div>
                              <h2 style="    margin-top: 21px;  ">  
                                   Advanced Settings
                                 </h2>
                                 <br>
                                   <div  style="border-bottom: 1px solid #d8e2e7; padding-bottom: 30px;">
                                       <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="form-control-label" for="inputEventStart">Number Of Allowed Booths Per User</label>
                                                <input id="usersNum" type="number" min="1" oninput="validity.valid||(value='');" value="<?php echo $selected7;?>"   placeholder="Enter Number of Users" class="form-control portalsettings">    
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-control-label" >Default FloorPlan Zoom Settings(Percentage)</label>
                                                <input id="zoom" type="number" min="1"  oninput="validity.valid||(value='');" value="<?php echo $selected11;?>"  placeholder="Enter Default FloorPlan Zoom(Percentage) " class="form-control portalsettings">      
                                            </div>
                                            
                                       </div>
                                       <div class="row">
                                             <div class="form-group col-sm-2" style="display:flex">
                                                <label style="width:220px" class="form-control-label" for="inputEventStart">Pre-Paid?</label>
                                               
                                                  
                                                <label class="switch" style="    margin-left: 106px !important;">
                                                <input  type="checkbox" id="prePaidChk" style="    margin-top: 9px;" value="checked" <?php echo $selected10=='checked'? 'checked':'unchecked';?>  class="form-control portalsettings">    
                                                      <span class="slider round"></span>
                                              </label>
                                              </div>
                                        </div>
                                  </div>
                                 <br>
                                 <h2 style="    margin-top: 21px;     ">  
                                   Visibility Settings
                                 </h2>
                                 <br>
                                 <div style="margin-top: -10px;">
                                       <div style="border-bottom: 1px solid #d8e2e7;padding-bottom: 30px;">
                                        <div style="display: flex;">
                                       <input id="Hide_exhibitor_Details" <?php echo $selected78=='Hide_Details'?'checked':''?> type="checkbox" value="Hide_Details">
                                       <label style="margin-left: 9px;" > Hide Exhibitor Details</label><br>
                                       </div>
                                       <div style="margin-left: 21px;font-size: 14px;">
                                       <p>Hides All exhibitor details from public floor plan view.<br>Booths will just be labeled "Available, Reserved, or Occupied'</p>
                                     </div> 
                                      <div style="display: flex;">
                                        <input id="Hide_reserved_Details" <?php echo $selected4=='Hide_Reserved_Booth'?'checked':''?> type="checkbox" value="Hide_Reserved_Booth">
                                        <label style="margin-left: 9px;" > Hide Reserved Booth Exhibitor Details</label><br>
                                        </div>  <div style="margin-left: 21px;font-size: 14px;"> <p>Hides all exhibitor details for 'Reserved' booths Only.</p>
                                        </div> 
                                        <div style="display: flex;">
                                       <input id="Hide_Price" <?php echo $selected5=='Hide_Booth_price'?'checked':''?> type="checkbox" value="Hide_Booth_price">
                                       <label style="margin-left: 9px;" >  Hide Booth Price on Public View</label><br>
                                       </div>   <div style="margin-left: 21px;font-size: 14px;">  <p>Hide booth prices from non-exhibitor view.</p>
                                       </div>  
                                      
                                                                
                                
                                  </div>
                              
                                 <br>
                                 
                                  <div class="btn-div">
                                     <button style="float: right;" id="updt_btn" type="submit"  name="addsettings"  class="btn btn-lg my-button mycustomwidth btn-success" value="Update">Save All Changes</button>
                                  </div>
                                   

                                     
                                 

                               
                        </div>   
                        <!-- User Settings Table Start -->
                         
                <!-- User Settings Table End -->
                    </div>

                </div>
                
                </section>
             
              
            </div>
             <!--//********************************************************************************/
/*            Booth Management Style End                                        */
/**************************************************************************f******/-->
        </div>
         
                               
                                       




            
           
    <?php   include 'cm_footer.php';?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js"></script>
    <script src="/wp-content/plugins/EGPL/js/cropper.js?v=1.2"></script>
    <script src="/wp-content/plugins/EGPL/js/jquery.datetimepicker.full.js?v=1.2"></script>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/exhibitor_entryflow.js?v=1.31"></script>   

    
    <?php 
    $listofturn = "";
    foreach ($currentturnnumnber as $key=>$counternumbervalue){
        
        $listofturn =$counternumbervalue;
        
    }
    //$listofturn = rtrim($listofturn, ',');
    
    ?>
    
    
    <script>          
    multiturnArray = [];
    RemoveDataArray = [];
    newlistofuseradd = [];
    
    jQuery( document ).ready(function() {

      /********************************************************************************/
/*            Booth Management Coding Start                                       */
/********************************************************************************/
           
jQuery("#turn-counter").html('<?php echo $listofturn; ?>');   
                                                   
            
            
            
           jQuery('.js-example-basic-multiple').select2();
             t= jQuery('#table_id_AD_1').sortable({
                items: 'tr:not(tr:first-child)',
                cursor: 'pointer',
                dropOnEmpty: true,
                start: function (e, ui) {
                    ui.item.addClass("selected");
                },
               
                stop: function (e, ui) {     
                    ui.item.removeClass("selected");
                    let flag="true";
                    
                    console.log("Qasimriaz100");
                    
                    
                    
                    
                    
                    jQuery('#table_id_AD_1 tbody').find("tr").each(function (index) {
                      
                    
                        if (index >=0) {
                  
                          
                          jQuery(this).find("td").eq(1).html(index);
                          
                          
                          
                          
                          
//                          if( jQuery(this).find("td").eq(1).html()==counter)
//                          {
//                              console.log(counter+'_________Qasimr'+jQuery(this).find("td").eq(1).html());
//                            ui.item.addClass("backColor");
//                            flag="false";
//                          }else if(jQuery(this).find("td").eq(1).html()!=counter){
//                            jQuery(this).removeClass("backColor");
//                            jQuery(this).css("background-color", "#fff");
//                          }
                        }
                    });
                    
                    
                    jQuery('#table_id_AD_1 tbody').find("tr").css("background-color", "");
                    jQuery('#table_id_AD_1 tbody').find("tr").removeClass("backColor");
                    
                    
                    
                    jQuery('#table_id_AD_1 tbody').find("tr").each(function (index) {
                    
                        
                          var counter=<?php echo json_encode($currentturnnumnber);?>;
                          console.log(counter);
                          console.log(counter.includes(jQuery(this).find("td").eq(1).html()));
                          if(counter.includes(jQuery(this).find("td").eq(1).html()))
                          {
                              console.log(counter+'_________Qasimr'+jQuery(this).find("td").eq(2).html());
                                jQuery(this).addClass("backColor");
                           
                          }
                    
                    
                    });
                    
                    
                }
                
            });
            /********************************************************************************/
/*            Booth Management Coding End                                        */
/********************************************************************************/
  
                  //   t.rowReordering();
   
      // console.log(authors); 
      
      
            jQuery('.cp-component').colorpicker();
            jQuery(".datetimepicker").datetimepicker({
              format: 'd M Y',
              timepicker:false
            });
            jQuery(window).load(function() {
                if ( window.location.href.indexOf("admin-settings") > -1)
                 {
                    jQuery('.block-msg-default').remove();
                    jQuery('.blockOverlay').remove();
                 }
            });  
            
      });
      
      jQuery(function () {
      'use strict';

      var console = window.console || { log: function () {} };
      var URL = window.URL || window.webkitURL;
      var $image = jQuery('#headerbgimg');
      var $download = jQuery('#download');
      var $dataX = jQuery('#dataX');
      var $dataY = jQuery('#dataY');
      var $dataHeight = jQuery('#dataHeight');
      var $dataWidth = jQuery('#dataWidth');
      var $dataRotate = jQuery('#dataRotate');
      var $dataScaleX = jQuery('#dataScaleX');
      var $dataScaleY = jQuery('#dataScaleY');
      var options = {
        aspectRatio: 50 / 9,
        zoomOnWheel: false,

    // my code Shehroze starts

        autoCropArea: 1, 
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',

    // my code Shehroze ends

        preview: '.img-preview',
        crop: function (e) {
          var data = e.detail;
          dataHeight.value = Math.round(data.height);
          dataWidth.value = Math.round(data.width);
        }
      };
      var originalImageURL = $image.attr('src');
      var uploadedImageName = 'cropped.jpg';
      var uploadedImageType = 'image/jpeg';
      var uploadedImageURL;

      // Tooltip
      jQuery('[data-toggle="tooltip"]').tooltip();

      // Cropper
      $image.on({
        ready: function (e) {
          console.log(e.type);
        },
        cropstart: function (e) {
          console.log(e.type, e.detail.action);
        },
        cropmove: function (e) {
          console.log(e.type, e.detail.action);
        },
        cropend: function (e) {
          console.log(e.type, e.detail.action);
        },
        crop: function (e) {
          console.log(e.type);
        },
        zoom: function (e) {
          console.log(e.type, e.detail.ratio);
        }
      }).cropper(options);
      

     
      jQuery('.header-docs-buttons').on('click', '[data-method]', function () {
        var $this = jQuery(this);
        var data = $this.data();
        var cropper = $image.data('cropper');
        var cropped;
        var $target;
        var result1;
        console.log(data)
        console.log($image)
        result1 = $image.cropper(data.method, data.option, data.secondOption);
        console.log(result1);
        
        if ($this.prop('disabled') || $this.hasClass('disabled')) {
          return;
        }

        if (cropper && data.method) {
          data = jQuery.extend({}, data); // Clone a new one

          if (typeof data.target !== 'undefined') {
            $target = jQuery(data.target);

            if (typeof data.option === 'undefined') {
              try {
                data.option = JSON.parse($target.val());
              } catch (e) {
                console.log(e.message);
              }
            }
          }

          cropped = cropper.cropped;

          switch (data.method) {
            case 'rotate':
              if (cropped && options.viewMode > 0) {
                $image.cropper('clear');
              }

              break;

            case 'getCroppedCanvas':
              if (uploadedImageType === 'image/jpeg') {
                if (!data.option) {
                  data.option = {};
                }

                data.option.fillColor = '#fff';
              }

              break;

              // my code Shehroze starts

            case 'deleteimage': 

            

             swal({
        							title: "Are you sure?",
        							text: 'Do you want to remove this Header Image ?',
        							type: "warning",
        							showCancelButton: true,
        							confirmButtonClass: "btn-danger",
        							confirmButtonText: "Yes, delete it!",
        							cancelButtonText: "No, cancel please!",
        							closeOnConfirm: false,
        							closeOnCancel: false
        						},
        						function(isConfirm) {
                                                            
                                                            
                                                             
        							if (isConfirm) {
                                                                     
                                                                     cropper.destroy();
                                                                    jQuery('#headerbgimg').removeAttr('src');


                                                                      if (jQuery("#headerimage").val(" ")) {

                                                                          jQuery("#headerimage").val('');

                                                                        
                                                                     
                                                                          jQuery(".hidebtnheader").attr('disabled', true);
                                                                          jQuery(".removeheader").attr('disabled', true);
                                                                      }

                                                                       jQuery(".unhideheader").val("");
                                                                       jQuery(".hidebtnheader").attr('disabled', true);
                                                                       jQuery(".removeheader").attr('disabled', true);

                                                                   jQuery(".unhideheader").on('change', function(event){

                                                                     jQuery(".hidebtnheader").attr('disabled', false);
                                                                     jQuery(".removeheader").attr('disabled', false);



                                                                    });
                                                                     
                                                                     swal.close();
                                                                     
        							} else {
        								swal({
        									title: "Cancelled",
        									text: "Header Image is safe :)",
        									type: "error",
        									confirmButtonClass: "btn-danger"
        								});
        							}
        						});


           
             break;
      
    //my code Shehroze ends
          }
          
          
          
          

          switch (data.method) {
            case 'rotate':
              if (cropped && options.viewMode > 0) {
                $image.cropper('crop');
              }

              break;

            case 'scaleX':
            case 'scaleY':
              jQuery(this).data('option', -data.option);
              break;

            case 'getCroppedCanvas':
              if (result1) {
                // Bootstrap's Modal
                
                var inputImageType = jQuery("#inputImage")[0].files[0];
                var Formiamgedata = new FormData();
                var extension;
                jQuery("body").css({'cursor':'wait'});
                if(inputImageType !="" && inputImageType != undefined){
                
                        var filetypeAc = jQuery("#inputImage")[0].files[0].type;
                        var filetype = filetypeAc.split('/');
                        extension = filetype[1];
                        console.log(filetypeAc);
                        console.log(filetype[1]);
                        Formiamgedata.append('imagetype', filetype[1]);


                }else{
                        var fileURL = jQuery("#headerbgimg").attr('src');
                        extension = fileURL.substr( (fileURL.lastIndexOf('.') +1) );
                        console.log(extension);
                        console.log(filetype);
                        Formiamgedata.append('imagetype', extension);
                }
                
                console.log(getheaderimage);
                
                var getheaderimage = result1.toDataURL(extension);
                Formiamgedata.append('imagedata', getheaderimage);
               
                var url = currentsiteurl+'/';
                var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=uploadbase64image';
                jQuery.ajax({
                        url: urlnew,
                        data: Formiamgedata,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function (data) {
                            
                            
                            jQuery("body").css({'cursor':'default'});
                            var newURL = jQuery.parseJSON(data);
                            jQuery("#headerimage").val(newURL);
                            jQuery('.previewDivselectedImageheader').empty();
                            var imagehtml = '<img style="width: 100%;" src="'+newURL+'" >';
                            jQuery('.previewDivselectedImageheader').append(imagehtml);
                            
                            
                        }
                            
                            
                            
                        });
                  
               
              }

              break;

            case 'destroy':
              if (uploadedImageURL) {
                URL.revokeObjectURL(uploadedImageURL);
                uploadedImageURL = '';
                $image.attr('src', originalImageURL);
              }

              break;
          }

          if (jQuery.isPlainObject(result1) && $target) {
            try {
              $target.val(JSON.stringify(result1));
            } catch (e) {
              console.log(e.message);
            }
          }
        }
      });

      
      // Import image
      var $inputImage = jQuery('#inputImage');

      if (URL) {
        $inputImage.change(function () {
          var files = this.files;
          var file;

          if (!$image.data('cropper')) {
            return;
          }
          jQuery(".headerimagecropper").show();
          if (files && files.length) {
            file = files[0];

            if (/^image\/\w+$/.test(file.type)) {
              uploadedImageName = file.name;
              uploadedImageType = file.type;

              if (uploadedImageURL) {
                URL.revokeObjectURL(uploadedImageURL);
              }

              uploadedImageURL = URL.createObjectURL(file);
              $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
              //$inputImage.val('');
            } else {
              window.alert('Please choose an image file.');
            }
          }
        });
      } else {
        $inputImage.prop('disabled', true).parent().addClass('disabled');
      }
    });
    jQuery(function () {
      'use strict';

      var console = window.console || { log: function () {} };
      var URL = window.URL || window.webkitURL;
      var $imageLogo = jQuery('#headerbgimgLogo');
      var $downloadLogo = jQuery('#downloadLogo');
      var $dataXLogo = jQuery('#dataXLogo');
      var $dataYLogo = jQuery('#dataYLogo');
      var $dataHeightLogo = jQuery('#dataHeightLogo');
      var $dataWidthLogo = jQuery('#dataWidthLogo');
      var $dataRotateLogo = jQuery('#dataRotateLogo');
      var $dataScaleXLogo = jQuery('#dataScaleXLogo');
      var $dataScaleYLogo = jQuery('#dataScaleYLogo');
      var optionsLogo = {
        aspectRatio: 2 / 1,
        zoomOnWheel: false,

    // my code Shehroze starts

        autoCropArea: 1, 
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',

    // my code Shehroze ends

        preview: '.img-previewLogo',
        crop: function (e) {
          var dataLogo = e.detail;
          console.log(dataLogo)
          dataHeightLogo.value = Math.round(dataLogo.height);
          dataWidthLogo.value = Math.round(dataLogo.width);
        }
      };
      var originalImageURLLogo = $imageLogo.attr('src');
      var uploadedImageNameLogo = 'cropped.jpg';
      var uploadedImageTypeLogo = 'image/jpeg';
      var uploadedImageURLLogo;

      // Tooltip
      jQuery('[data-toggle="tooltip"]').tooltip();

      // Cropper
      $imageLogo.on({
        ready: function (e) {
          console.log(e.type);
        },
        cropstart: function (e) {
          console.log(e.type, e.detail.action);
        },
        cropmove: function (e) {
          console.log(e.type, e.detail.action);
        },
        cropend: function (e) {
          console.log(e.type, e.detail.action);
        },
        crop: function (e) {
          console.log(e.type);
        },
        zoom: function (e) {
          console.log(e.type, e.detail.ratio);
        }
      }).cropper(optionsLogo);
/********************************************************************************/
/*            Booth Management Codeing Start                                      */
/********************************************************************************/

 

       
      jQuery('#toggle_btn').on('click',function () {
        var val=jQuery('#toggle_btn:checked').val();
        
              
        console.log(val);
        if(val==undefined)
        {
         jQuery("#table_id_AD_1").hide();
         jQuery("#table-div").hide();
         jQuery("#updt_btn_table").hide();
         jQuery("#section_table").hide();
         jQuery("#Queue_Behavior").hide();
         
        }
        else if(val=='checked')
        {
          //var cancel= "<button id='cncl_btn' style='margin-left: 320px;width:140px;' type='submit'  name='addsettings'  class='btn btn-danger' value='Cancel'>Cancel</button>";
          if(jQuery('#exhibitorentryflow').prop('checked')){
                  swal({
                            title: "Registration is Enabled",
                            text: 'Kindly Disable the Registration for Enable Queue',
                            // type: "success",
                            html:true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Ok"
                            });
                            jQuery("#toggle_btn").prop('checked',false); 
                }else{
                          swal({
                                      title: "Are You Sure?",
                                      text: 'Updating this area may affect your users engaged in this process in real time. It is recommended to only make changes before your portal is live, or if it is live, to notify your users ahead if time.',
                                      // type: "success",
                                      html:true,
                                      confirmButtonClass: "btn-success",
                                      confirmButtonText: "Ok"
                                      });
                                      
                          jQuery("#table_id_AD_1").show();
                          jQuery("#updt_btn_table").show();
                          jQuery("#section_table").show();
                          jQuery("#table-div").show();
                          jQuery("#Queue_Behavior").show();
                }
        }
      })

      //===============================================================//
      jQuery('#Open_users').on('change',function(){
        console.log('Select');
        swal({
              type: "warning",
							title: "Queue Behaviour Changes",
							text: 'This change will effect the queue behavior and allow the first user in the queue with the status "Open" to select a booth. When changing the behavior, be sure to double check the queue order and make any necessary updates.',
							confirmButtonClass: "btn-danger",
              html:true,
              confirmButtonText: "Ok"
						})
      });
  jQuery("#updt_btn_table").on('click',function () { 
    swal({
							title: "Are you sure?",
							text: 'This change will start the queue from the first user with the status "Open" to select a booth.',
							type: "warning",
							showCancelButton: true,
							confirmButtonClass: "btn-danger",
							confirmButtonText: "Continue",
							cancelButtonText: "Cancel",
							closeOnConfirm: false,
							closeOnCancel: false
						},
            function(isConfirm) {

              if(isConfirm)
              {

            

                      jQuery("body").css({'cursor':'wait'});     
                      var data = new FormData();
                      var AllDataArray = []; 
                      var floorPlanDataArray = []; 
                      var count=0;

                        jQuery('#table_id_AD_1 tbody').find("tr").each(function (index) {
                            var User_ID = jQuery(this).find("td").eq(2).attr("id");
                            var OrderNumber =  jQuery(this).find("td").eq(1).html();
                            
                            
                            
                            
                            var table_Val=jQuery('#toggle_btn:checked').val();
                            var status =  "";//jQuery(this).find("input:checked").val();
                            var shopstatus = jQuery("#User_PRI_Btn"+User_ID).val();
                            console.log(jQuery("#User_PRI_Btn"+User_ID).prop('checked')+'________________________');
                            if(jQuery("#User_PRI_Btn"+User_ID).prop('checked')){
                                
                                status = "checked";
                                
                            }
                            var dataArray = {ID:User_ID,priorityNum:OrderNumber,toggle:table_Val,userStatus:status};
                            AllDataArray.push(dataArray);
                            count++;  
                        });

                        var select_1= jQuery('#Open_users option:selected').val();
                        var select_2= jQuery('#Email_Selection option:selected').val();
                        var select_3= jQuery('#Deafult_status option:selected').val();
                        var floorPlan={select1: select_1,select2:select_2,select3:select_3};
                        floorPlanDataArray.push(floorPlan);
                        if(AllDataArray!=""){
                          AllDataArray.splice(0,1);
                        }
                          console.log(AllDataArray);
                          data.append('leveleslist',JSON.stringify(AllDataArray));
                          data.append('multiturnArray',JSON.stringify( multiturnArray));
                          data.append('RemoveDataArray',JSON.stringify( RemoveDataArray));
                          data.append('boothQueueSettings',JSON.stringify( floorPlanDataArray));
                          data.append('newlistofuseradd',JSON.stringify( newlistofuseradd));
                          
                          
                     

                          var url = currentsiteurl+'/';
                          var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=setUserPirority';
                          jQuery.ajax({
                              url: urlnew,
                              data: data,
                              cache: false,
                              contentType: false,
                              processData: false,
                              type: 'POST',
                              success: function (data) {
                                
                                jQuery("body").css({'cursor':'default'});
                                  swal({
                                    title: "Success",
                                    text: 'User Priorities saved successfully.',
                                    type: "success",
                                    html:true,
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "Ok"
                                    },function(){
                                        
                                        //localStorage.setItem('activeTab', "#tabs-1-tab-3");
                                        //location.reload();
                                        //console.log("Success");
                                    });
                              },error: function (xhr, ajaxOptions, thrownError) {
                                swal({
                                  title: "Error",
                                  text: "There was an error during the requested operation. Please try again.",
                                  type: "error",
                                  confirmButtonClass: "btn-danger",
                                  confirmButtonText: "Ok"
                                });
                              }
                            });
                }else {
								swal({
									title: "Cancelled",
									text: "",
									type: "error",
									confirmButtonClass: "btn-danger"
								});
							}
                
            }
                
        )
    }); 


                     //===========================================================================//

    jQuery(".my-button").on('click',function () { 

           jQuery("body").css({'cursor':'wait'});     
           var data = new FormData();
           var AllDataArray = []; 
           var floorPlanDataArray = []; 
          
           var value=jQuery('#toggle_btn:checked').val();
           if (value=='checked') {
                   var select_1= jQuery('#Open_users option:selected').val();
                   var select_2= jQuery('#Email_Selection option:selected').val();
                   var select_3= jQuery('#Deafult_status option:selected').val();
                   var select_4= jQuery('#Hide_exhibitor_Details:checked').val();
                   var select_5= jQuery('#Hide_reserved_Details:checked').val();
                   var select_6= jQuery('#Hide_Price:checked').val();
                   var select_7= jQuery('#Hide_Company_Name:checked').val();
                   var select_8= jQuery('#usersNum').val();
                   var Zoom= jQuery('#zoom').val();
                   var select_9= jQuery('#prePaidChk:checked').val();
                    var floorPlan={
                      select1: select_1, select2:select_2,select3:select_3,select4:select_4,select5:select_5,select6:select_6,select7:select_7,select8:select_8,select9:select_9,select10:Zoom,tableValue:value}
                      floorPlanDataArray.push(floorPlan);
                      jQuery('#table_id_AD_1 tbody').find("tr").each(function (index) {
                        var User_ID = jQuery(this).find("td").eq(2).attr("id");
                        var OrderNumber =  jQuery(this).find("td").eq(1).html();
                        var status =  jQuery(this).find("input:checked").val();
                        var table_Val=jQuery('#toggle_btn:checked').val();
                        // console.log(OrderNumber);
                         console.log(User_ID);
                        var dataArray = {ID:User_ID,priorityNum:OrderNumber,toggle:table_Val,userStatus:status};
                        AllDataArray.push(dataArray);
                  });
                  if(AllDataArray!=""){
                AllDataArray.splice(0,1);
                  }
                console.log(AllDataArray);
                
            }else{

                    var select_1=jQuery('#Open_users option:selected').val();
                    var select_2=jQuery('#Email_Selection option:selected').val();
                    var select_3=jQuery('#Deafult_status option:selected').val();
                    var select_4=jQuery('#Hide_exhibitor_Details:checked').val();
                    var select_5=jQuery('#Hide_reserved_Details:checked').val();
                    var select_6=jQuery('#Hide_Price:checked').val();
                    var select_7=jQuery('#Hide_Company_Name:checked').val();
                    var select_8= jQuery('#usersNum').val();
                    var Zoom= jQuery('#zoom').val();
                    var select_9= jQuery('#prePaidChk:checked').val();
                    var value=jQuery('#toggle_btn:checked').val();
                         value="";
                         var floorPlan={
                      select1: select_1, select2:select_2,select3:select_3,select4:select_4,select5:select_5,select6:select_6,select7:select_7,select8:select_8,select9:select_9,select10:Zoom,tableValue:value}
                      floorPlanDataArray.push(floorPlan);

            }
            // console.log(AllDataArray);
            // console.log(floorPlanDataArray);
            data.append('leveleslist',JSON.stringify(AllDataArray));
            data.append('floorPlanSetting',JSON.stringify( floorPlanDataArray));
            
            
           var url = currentsiteurl+'/';
           var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=setFloorPlanSettings';
            jQuery.ajax({
               url: urlnew,
               data: data,
               cache: false,
               contentType: false,
               processData: false,
               type: 'POST',
               success: function (data) {
                 
                 jQuery("body").css({'cursor':'default'});
                   swal({
                     title: "Success",
                     text: 'FloorPlan Settings Saved Successfully.',
                     type: "success",
                     html:true,
                     confirmButtonClass: "btn-success",
                     confirmButtonText: "Ok"
                     },function(){
                            
                          
                          localStorage.setItem('activeTab', "#tabs-1-tab-3");
                          location.reload();
                          console.log("Success");
                     });
               },error: function (xhr, ajaxOptions, thrownError) {
                 swal({
                   title: "Error",
                   text: "There was an error during the requested operation. Please try again.",
                   type: "error",
                   confirmButtonClass: "btn-danger",
                   confirmButtonText: "Ok"
                 });
               }
             });
    });  

    //-----------------------------------Click User Selection-------------------------------//
    var rows_selected = [];
    jQuery("#table_id_AD_1 tbody").on(
          "click",
          'input[type="checkbox"]',
          function (e) {
            console.log(e);
            var $row = jQuery(this).closest("tr");
            var values=jQuery(this).val();
            console.log(values);
              console.log($row);

            var index = jQuery.inArray(values, rows_selected);

          
            if (this.checked && index === -1) {
              rows_selected.push(values);

            
            } else if (!this.checked && index !== -1) {
              rows_selected.splice(index, 1);
            }

            if (this.checked) {
              $row.removeClass("unselect");
              $row.addClass("selected");
            } else {
              $row.removeClass("selected");
              $row.addClass("unselect");
            }

           
            console.log(rows_selected);
            if(rows_selected.length>=1)
            {
              jQuery("#newsendbulkemailstatus").prop("disabled", false);
            }else{
              
              jQuery("#newsendbulkemailstatus").prop("disabled", true);
            }
          
            jQuery("#newbulkemailcounter").empty();
          
            jQuery("#newbulkemailcounter").append(rows_selected.length);
            
            // Prevent click event from propagating to parent
            e.stopPropagation();
          }
        );  
            //-----------------------------------Click User Selection-------------------------------//
            //-----------------------------------Click User Selection ALL User-------------------------------//
   
        jQuery('#select_all_user').on("click", function (e) {
          if (this.checked) {
            jQuery(
              '#table_id_AD_1 tbody td  input[name="status_check"]:not(:checked)'
            ).trigger("click");
            jQuery("#SELECT").css("color","#00a8ff !important");
            jQuery("#SELECT_Icon").css("color","#00a8ff !important");
          } else {
            jQuery('#table_id_AD_1 tbody td input[name="status_check"]:checked').trigger(
              "click"
            );
            jQuery("#SELECT").css("color", "black");
            jQuery("#SELECT_Icon").css("color", "black");
          }

          // Prevent click event from propagating to parent
          e.stopPropagation();

        });
                    //-----------------------------------Click User Selection ALL User-------------------------------//
            //-----------------------------------Status Activate User Function-------------------------------//

        jQuery("#activate_users").on('click',function () {
          
                  var data = new FormData();
                  var AllDataArray = []; 
                  var AllDataArraySort = []; 
                 
                  // var rows= jQuery("#table_id_AD_1 tbody tr.unselect").innerText;
                  rows_selected.forEach(element => {

                    var dataArray = {ID:element};
                    AllDataArray.push(dataArray);
                    jQuery('#User_PRI_Btn'+element).bootstrapToggle('on');
                    console.log("Qasimriaz");
                    console.log(element);
                    
                    });

                    jQuery('#table_id_AD_1 tbody').find("tr").each(function (index) {
                    var User_ID = jQuery(this).find("td").eq(2).attr("id");
                    var OrderNumber =  jQuery(this).find("td").eq(1).html();
                    var status =  jQuery(this).find("input[name='active_close_check']:checked").val();
                    var dataArray = {ID:User_ID,priorityNum:OrderNumber,userStatus:status};
                    AllDataArraySort.push(dataArray);
                  });
                    data.append('userListSort',JSON.stringify(AllDataArraySort));
                    data.append('userListRemove',JSON.stringify(AllDataArray));

                var url = currentsiteurl+'/';
                var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=activeUserFromQueue';
                
                
                
                
//                jQuery.ajax({
//                    url: urlnew,
//                    data: data,
//                    cache: false,
//                    contentType: false,
//                    processData: false,
//                    type: 'POST',
//                    success: function (data) {
//                        
//                        jQuery("body").css({'cursor':'default'});
//                          swal({
//                            title: "Success",
//                            text: 'Users Status Open Successfully.',
//                            type: "success",
//                            html:true,
//                            confirmButtonClass: "btn-success",
//                            confirmButtonText: "Ok"
//                            },function(){
//                                localStorage.setItem('activeTab', "#tabs-1-tab-3");
//                                location.reload();
//                                console.log("Success");
//                            });
//                      },error: function (xhr, ajaxOptions, thrownError) {
//                        swal({
//                          title: "Error",
//                          text: "There was an error during the requested operation. Please try again.",
//                          type: "error",
//                          confirmButtonClass: "btn-danger",
//                          confirmButtonText: "Ok"
//                        });
//                      }
//                    });

        })  
                    //-----------------------------------Status Activate User Function-------------------------------//
            //-----------------------------------Status Closed User Function-------------------------------//

        jQuery("#close_users").on('click',function () {
          var data = new FormData();
                  var AllDataArray = []; 
                  var AllDataArraySort = []; 
                 
                  // var rows= jQuery("#table_id_AD_1 tbody tr.unselect").innerText;
                  rows_selected.forEach(element => {

                    var dataArray = {ID:element};
                    AllDataArray.push(dataArray);
                    
                    jQuery('#User_PRI_Btn'+element).bootstrapToggle('off');
                    
                    });

                    jQuery('#table_id_AD_1 tbody').find("tr").each(function (index) {
                    var User_ID = jQuery(this).find("td").eq(2).attr("id");
                    var OrderNumber =  jQuery(this).find("td").eq(1).html();
                    var status =  jQuery(this).find("input[name='active_close_check']:checked").val();
                    var dataArray = {ID:User_ID,priorityNum:OrderNumber,userStatus:status};
                    AllDataArraySort.push(dataArray);
                  });
                    data.append('userListRemove',JSON.stringify(AllDataArray));
                    data.append('userListSort',JSON.stringify(AllDataArraySort));

                var url = currentsiteurl+'/';
                var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=closeUserFromQueue';
//                jQuery.ajax({
//                    url: urlnew,
//                    data: data,
//                    cache: false,
//                    contentType: false,
//                    processData: false,
//                    type: 'POST',
//                    success: function (data) {
//                        
//                        jQuery("body").css({'cursor':'default'});
//                          swal({
//                            title: "Success",
//                            text: 'Users Status Closed Successfully.',
//                            type: "success",
//                            html:true,
//                            confirmButtonClass: "btn-success",
//                            confirmButtonText: "Ok"
//                            },function(){
//                                localStorage.setItem('activeTab', "#tabs-1-tab-3");
//                                location.reload();
//                                console.log("Success");
//                            });
//                      },error: function (xhr, ajaxOptions, thrownError) {
//                        swal({
//                          title: "Error",
//                          text: "There was an error during the requested operation. Please try again.",
//                          type: "error",
//                          confirmButtonClass: "btn-danger",
//                          confirmButtonText: "Ok"
//                        });
//                      }
//                    });

        }) 
                     //-----------------------------------Status Closed User Function-------------------------------//
            //-----------------------------------Status Closed User Function-------------------------------//

        jQuery("#turn_the_users").on('click',function () {
          var data = new FormData();
                  var AllDataArray = []; 
                  var AllDataArraySort = [];
                  
                  jQuery('#table_id_AD_1 tbody').find("tr").css("background-color", "");
                  jQuery('#table_id_AD_1 tbody').find("tr").removeClass("backColor");
                  
                 
                  // var rows= jQuery("#table_id_AD_1 tbody tr.unselect").innerText;
                  rows_selected.forEach(element => {

                    var dataArray = {ID:element};
                    multiturnArray.push(dataArray);
                    
                    //console.log("Qasimriazbgcolors");
                    jQuery("#"+element).parent("tr").css("background-color","#cbeeff");
                    
                    
                    
                    });

                    jQuery('#table_id_AD_1 tbody').find("tr").each(function (index) {
                    var User_ID = jQuery(this).find("td").eq(2).attr("id");
                    var OrderNumber =  jQuery(this).find("td").eq(1).html();
                    var status =  jQuery(this).find("input[name='active_close_check']:checked").val();
                    var dataArray = {ID:User_ID,priorityNum:OrderNumber,userStatus:status};
                    AllDataArraySort.push(dataArray);
                  });
                    data.append('userListRemove',JSON.stringify(AllDataArray));
                    data.append('userListSort',JSON.stringify(AllDataArraySort));

                var url = currentsiteurl+'/';
                var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=turnMultiple';
                
                swal({
                    
                    title: "Success",
                    text: 'Users Turn on Successfully.',
                    type: "success",
                    html:true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Ok"
                    },function(){
                       
                    });
                
                
//                jQuery.ajax({
//                    url: urlnew,
//                    data: data,
//                    cache: false,
//                    contentType: false,
//                    processData: false,
//                    type: 'POST',
//                    success: function (data) {
//                        
//                        jQuery("body").css({'cursor':'default'});
//                          swal({
//                            title: "Success",
//                            text: 'Users Turn Saved Successfully.',
//                            type: "success",
//                            html:true,
//                            confirmButtonClass: "btn-success",
//                            confirmButtonText: "Ok"
//                            },function(){
//                                localStorage.setItem('activeTab', "#tabs-1-tab-3");
//                                location.reload();
//                                console.log("Success");
//                            });
//                      },error: function (xhr, ajaxOptions, thrownError) {
//                        swal({
//                          title: "Error",
//                          text: "There was an error during the requested operation. Please try again.",
//                          type: "error",
//                          confirmButtonClass: "btn-danger",
//                          confirmButtonText: "Ok"
//                        });
//                      }
//                    });

        }) 
                     //-----------------------------------Status Closed User Function-------------------------------//
                     //-------------------------------Delete USer From Queue Function-------------------------------//

        jQuery("#remove_users").on('click',function () {
          swal({
							title: "Are you sure?",
							text: 'This change will apply to this event portal where the select user is enabled.',
							type: "warning",
							showCancelButton: true,
							confirmButtonClass: "btn-danger",
							confirmButtonText: "Continue",
							cancelButtonText: "Cancel",
							closeOnConfirm: false,
							closeOnCancel: false
						},
						function(isConfirm) {
                                       
							if (isConfirm) {
                                                           
                var data = new FormData();
                  var AllDataArray = []; 
                  
                  var AllDataArrayForPriority = []; 
                  // var rows= jQuery("#table_id_AD_1 tbody tr.unselect").innerText;
                  jQuery('#table_id_AD_1 tbody').find("tr.unselect").each(function (index) {
                    var User_ID = jQuery(this).find("td").eq(2).attr("id");
                    var OrderNumber =  jQuery(this).find("td").eq(1).html();
                    var status =  jQuery(this).find("input[name='active_close_check']:checked").val();
                    var dataArray = {ID:User_ID,priorityNum:OrderNumber,userStatus:status};
                      AllDataArray.push(dataArray);
                  });

                
                var count=1;
                AllDataArray.forEach(element=> {
                  var User_ID = element['ID'];
                    var OrderNumber = count;
                    var status = element['userStatus'];
                    var dataArray = {ID:User_ID,priorityNum:OrderNumber,userStatus:status};
                    AllDataArrayForPriority.push(dataArray);
                    count++;
                });
                console.log(AllDataArrayForPriority);
                
         
                  rows_selected.forEach(element => {
                    var dataArray = {ID:element};
                    RemoveDataArray.push(dataArray);
                    
                    var companyname = jQuery("#"+element).html();
                    jQuery("#"+element).parent("tr").remove();
                    
                    if(companyname !=undefined && companyname !='undefined'){
                        console.log("<option value='"+element+"'>"+companyname+"</option>");
                        jQuery("#usersList").append("<option value='"+element+"'>"+companyname+"</option>");
                    }
                    
                  });
                  console.log(RemoveDataArray);
                  data.append('userListRemove',JSON.stringify(RemoveDataArray));
                  data.append('userListPriority',JSON.stringify(AllDataArrayForPriority));

                var url = currentsiteurl+'/';
                var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=removeUserFromQueue';
//                jQuery.ajax({
//                    url: urlnew,
//                    data: data,
//                    cache: false,
//                    contentType: false,
//                    processData: false,
//                    type: 'POST',
//                    success: function (data) {
//                        
//                        jQuery("body").css({'cursor':'default'});
//                          swal({
//                            title: "Success",
//                            text: 'Users Removed From Queue Successfully.',
//                            type: "success",
//                            html:true,
//                            confirmButtonClass: "btn-success",
//                            confirmButtonText: "Ok"
//                            },function(){
//                                localStorage.setItem('activeTab', "#tabs-1-tab-3");
//                                location.reload();
//                                console.log("Success");
//                            });
//                      },error: function (xhr, ajaxOptions, thrownError) {
//                        swal({
//                          title: "Error",
//                          text: "There was an error during the requested operation. Please try again.",
//                          type: "error",
//                          confirmButtonClass: "btn-danger",
//                          confirmButtonText: "Ok"
//                        });
//                      }
//                    });

                 swal.close();
                                                             
							} else {
								swal({
									title: "Cancelled",
									text: "",
									type: "error",
									confirmButtonClass: "btn-danger"
								});
							}
						});
          
        })  
                    //-----------------------------------Delete User From Queue Function-------------------------------//
                    //-----------------------------------Add User Into Queue Function-------------------------------//
                    
        
        jQuery('#add_newUser').on('click', function () {

                var hiddentemplatelist = jQuery("#usersList").html();
                jQuery.confirm({

                  onOpen : function () {
                      jQuery('.js-example-basic-multiple').select2();
                      console.log("IN open");
      
                  },
                   title: "Add New User",
                   content: '<div> <select class="js-example-basic-multiple" name="states[]" aria-invalid="false" multiple="multiple" style="border: #cccccc 1px solid;border-radius: 7px;height: 36px;width: 100%;"id="UserSelected">'+hiddentemplatelist+'</select><br><p style="color:red;margin: 19px 4px;">This action will add the User to the Piroirty Queue at the end of the Queue.</p><br></div>',
                   confirmButtonClass: 'mycustomwidth specialbuttoncolor',
                   confirmButton:'Add User',
                   cancelButton:false,
                   animation: 'rotateY',
                   closeIcon: true,
          
                         confirm: function () {
                          var selectedtemplateemailname=[];
                             selectedtemplateemailname = jQuery( "#UserSelected  option:selected" );
                             console.log(selectedtemplateemailname);  
                             //if(selectedtemplateemailname!=undefined)
                              //{
                                   var data = new FormData();
                                   var AllDataArray = [];
                                   var dataArray= new Array();
                                   console.log(selectedtemplateemailname);
                                   jQuery("#UserSelected :selected").each(function(i, el) {
                                      
                                      var element = jQuery(el).val();
                                      var companyname = jQuery(el).text();
                                      //dataArray={ID:element};
                                      //AllDataArray.push(dataArray);
                                      //newlistofuseradd.push(dataArray);
                                     
                                      if(companyname != undefined && companyname != 'undefined'){
                                      
                                      var appendnewuser = '<tr class="unselect"><td style="width: 6px;margin-top: 17px;"><input type="checkbox" id="select_one" name="status_check" class="checkcheckedstatus" value="'+element+'"></td><td style="     margin-top: -23px;font-weight: bold;margin-left: 20px;color: #adb7be;">'+jQuery("#table_id_AD_1 tbody tr").length+'</td><td title="" foundation="" id="'+element+'" class="RowName" style="width: 97px;margin-left: 54px;white-space: nowrap;overflow: hidden !important;text-overflow: ellipsis;margin-top: -25px;font-size: 13px;">'+companyname+'</td><td title="Close" style="display: flex;justify-content: flex-end; width: 223px;margin-top: -54px"><div class="toggle btn btn-default off" data-toggle="toggle" style="width: 50px; height: 0px;"><input type="checkbox" name="active_close_check" data-on="Open" data-off="Close" unchecked="" id="User_PRI_Btn'+element+'" data-toggle="toggle" data-size="xs"><div class="toggle-group"><label class="btn btn-primary toggle-on">Open</label><label class="btn btn-default active toggle-off">Close</label><span class="toggle-handle btn btn-default"></span></div></div> </td></tr>';
                                     console.log(appendnewuser);  
                                      jQuery(".bulkeditfield_AD_1 tbody").append(appendnewuser);
                                  }
                                     
                                     
                                   });
                                    // dataArray.push({ID:selectedtemplateemailname});
                                  
                                  
                                  //  AllDataArray.push(dataArray);
                                  //console.log(AllDataArray);
                               //}
                                    data.append('userListAdd',JSON.stringify(AllDataArray));
                                    var url = currentsiteurl+'/';
                                    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=addUserIntoQueue';
     
//                                         jQuery.ajax({
//                                                 url: urlnew,
//                                                 data: data,
//                                                 cache: false,
//                                                 contentType: false,
//                                                 processData: false,
//                                                 type: 'POST',
//                                                 success: function (data) {
//                                                     
//                                                     jQuery("body").css({'cursor':'default'});
//                                                     
////                                                       swal({
////                                                         title: "Success",
////                                                         text: 'Users Added to Queue Successfully.',
////                                                         type: "success",
////                                                         html:true,
////                                                         confirmButtonClass: "btn-success",
////                                                         confirmButtonText: "Ok"
////                                                         },function(){
////                                                             localStorage.setItem('activeTab', "#tabs-1-tab-3");
////                                                             location.reload();
////                                                             console.log("Success");
////                                                         }
////                                                             
////                                                         );
////                                                 
//                                                 
//                                                 
//                                                   },error: function (xhr, ajaxOptions, thrownError) {
//                                                     swal({
//                                                       title: "Error",
//                                                       text: "There was an error during the requested operation. Please try again.",
//                                                       type: "error",
//                                                       confirmButtonClass: "btn-danger",
//                                                       confirmButtonText: "Ok"
//                                                     });
//                                                   }
//                                                  
//     
//                         }
//                                         )
                                        },
         
          });
       });
                           //-----------------------------------Add User Into Queue Function-------------------------------//

     
                     //===========================================================================//

      /********************************************************************************/
/*            Booth Management Coding End                                        */
/********************************************************************************/
  

      jQuery('.logo-docs-buttons').on('click', '[data-method]', function () {
        var $this = jQuery(this);
        var data = $this.data();
        var cropper = $imageLogo.data('cropper');
        var cropped;
        var $target;
        var result2;
        
        
        console.log(data)
        console.log($imageLogo)
        result2 = $imageLogo.cropper(data.method, data.option, data.secondOption);
        //console.log(result2.toDataURL(uploadedImageTypeLogo));
        
        
        if ($this.prop('disabled') || $this.hasClass('disabled')) {
          return;
        }

        if (cropper && data.method) {
          data = jQuery.extend({}, data); // Clone a new one

          if (typeof data.target !== 'undefined') {
            $target = jQuery(data.target);

            if (typeof data.option === 'undefined') {
              try {
                data.option = JSON.parse($target.val());
              } catch (e) {
                console.log(e.message);
              }
            }
          }

          cropped = cropper.cropped;

          switch (data.method) {
            case 'rotate':
              if (cropped && options.viewMode > 0) {
                $imageLogo.cropper('clear');
              }

              break;

            case 'getCroppedCanvas':
              if (uploadedImageTypeLogo === 'image/jpeg') {
                if (!data.option) {
                  data.option = {};
                }

                data.option.fillColor = '#fff';
              }

              break;

              // my code Shehroze starts

            case 'deleteimage':


                swal({
                                        title: "Are you sure?",
                                        text: 'Do you want to remove this Logo Image ?',
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonClass: "btn-danger",
                                        confirmButtonText: "Yes, delete it!",
                                        cancelButtonText: "No, cancel please!",
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    },
                                    function(isConfirm) {
                                                                
                                                                
                                                                 
                                        if (isConfirm) {
                                                                         
                                                             cropper.destroy();
                                                           jQuery('#headerbgimgLogo').removeAttr('src');

                      
                                                  if (jQuery("#headerimageLogo").val(" ")) {

                                                                        jQuery("#headerimageLogo").val('');
                                                                        jQuery(".hidebtnlogo").attr('disabled', true);
                                                                        jQuery(".removelogo").attr('disabled', true);
                                                            }

                                                                    jQuery(".unhidelogo").val("");
                                                                    jQuery(".hidebtnlogo").attr('disabled', true);
                                                                     jQuery(".removelogo").attr('disabled', true);
                                                
                                                                 jQuery(".unhidelogo").on('change', function(event){

                                                                    jQuery(".removelogo").attr('disabled', false);
                                                                    jQuery(".hidebtnlogo").attr('disabled', false);

         
            
                                     });
                                                                     
                                 swal.close();
                                                                     
                                    } else {
                                        swal({
                                            title: "Cancelled",
                                            text: "Logo Image is safe :)",
                                            type: "error",
                                            confirmButtonClass: "btn-danger"
                                        });
                                    }
                                });
         
         

           
             break;

    //my code Shehroze ends
          }

          result2 = $imageLogo.cropper(data.method, data.option, data.secondOption);

          switch (data.method) {
            case 'rotate':
              if (cropped && options.viewMode > 0) {
                $image.cropper('crop');
              }

              break;

            case 'scaleXLogo':
            case 'scaleYLogo':
              jQuery(this).data('option', -data.option);
              break;

            case 'getCroppedCanvas':
              if (result2) {
                // Bootstrap's Modal
                result2 = $imageLogo.cropper(data.method, data.option, data.secondOption);
                
                
                
                
                var inputImageType = jQuery("#inputImageLogo")[0].files[0];
                var FormImage2data = new FormData();
                var extension;
                jQuery("body").css({'cursor':'wait'});
                if(inputImageType !="" && inputImageType != undefined){
                
                        var filetypeAc = jQuery("#inputImageLogo")[0].files[0].type;
                        var filetype = filetypeAc.split('/');
                        extension = filetype[1];
                        console.log(filetypeAc);
                        console.log(filetype[1]);
                        FormImage2data.append('imagetype', extension);


                }else{
                        var fileURL = jQuery("#headerbgimgLogo").attr('src');
                        var extension = fileURL.substr( (fileURL.lastIndexOf('.') +1) );
                        console.log(extension);
                        console.log(filetype);
                        FormImage2data.append('imagetype', extension);
                }
                
                
                var Image2ULR = result2.toDataURL(extension);
                FormImage2data.append('imagedata', Image2ULR);
               
                var url = currentsiteurl+'/';
                var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=uploadbase64image';
                jQuery.ajax({
                        url: urlnew,
                        data: FormImage2data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function (data) {
                            
                            
                            jQuery("body").css({'cursor':'default'});
                            var newURL = jQuery.parseJSON(data);
                            jQuery("#headerimageLogo").val(newURL);
                            jQuery('.previewDivselectedImageLogo').empty();
                            var imagehtml = '<img style="width: 100%;" src="'+newURL+'" >';
                             
                            jQuery('.previewDivselectedImageLogo').append(imagehtml);
                          
                            
                        }
                            
                            
                            
                        });
                 
               
              }

              break;

            case 'destroyLogo':
              if (uploadedImageURLLogo) {
                URL.revokeObjectURL(uploadedImageURLLogo);
                uploadedImageURLLogo = '';
                $image.attr('src', originalImageURLLogo);
              }

              break;
          }

          if (jQuery.isPlainObject(result2) && $target) {
            try {
              $target.val(JSON.stringify(result2));
            } catch (e) {
              console.log(e.message);
            }
          }
        }
      });

     
      // Import image
      var $inputImageLogo = jQuery('#inputImageLogo');

      if (URL) {
        $inputImageLogo.change(function () {
          var files = this.files;
          var file;

          if (!$imageLogo.data('cropper')) {
            return;
          }
          jQuery(".headerimagecropperLogo").show();
          if (files && files.length) {
            file = files[0];

            if (/^image\/\w+$/.test(file.type)) {
              uploadedImageNameLogo = file.name;
              uploadedImageTypeLogo = file.type;

              if (uploadedImageURLLogo) {
                URL.revokeObjectURL(uploadedImageURLLogo);
              }

              uploadedImageURLLogo = URL.createObjectURL(file);
              $imageLogo.cropper('destroy').attr('src', uploadedImageURLLogo).cropper(optionsLogo);
              //$inputImage.val('');
            } else {
              window.alert('Please choose an image file.');
            }
          }
        });
      } else {
        $inputImageLogo.prop('disabled', true).parent().addClass('disabled');
      }
    });

    jQuery(function () {
      'use strict';

      var console = window.console || { log: function () {} };
      var URL = window.URL || window.webkitURL;
      var $imageFavicon = jQuery('#headerbgimgFavicon');
      var $downloadFavicon = jQuery('#downloadFavicon');
      var $dataXFavicon = jQuery('#dataXFavicon');
      var $dataYFavicon = jQuery('#dataYFavicon');
      var $dataHeightFavicon = jQuery('#dataHeightFavicon');
      var $dataWidthFavicon = jQuery('#dataWidthFavicon');
      var $dataRotateFavicon = jQuery('#dataRotateFavicon');
      var $dataScaleXFavicon = jQuery('#dataScaleXFavicon');
      var $dataScaleYFavicon = jQuery('#dataScaleYFavicon');
      var optionsFavicon = {
        aspectRatio: 1 / 1,
        zoomOnWheel: false,

    // my code Shehroze starts

        autoCropArea: 1, 
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',

    // my code Shehroze ends
        preview: '.img-previewFavicon',
        crop: function (e) {
          var dataFavicon = e.detail;
          dataHeightFavicon.value = Math.round(dataFavicon.height);
          dataWidthFavicon.value = Math.round(dataFavicon.width);
        }
      };
      var originalImageURLFavicon = $imageFavicon.attr('src');
      var uploadedImageNameFavicon = 'cropped.jpg';
      var uploadedImageTypeFavicon = 'image/jpeg';
      var uploadedImageURLFavicon;

      // Tooltip
      jQuery('[data-toggle="tooltip"]').tooltip();

      // Cropper
      $imageFavicon.on({
        ready: function (e) {
          console.log(e.type);
        },
        cropstart: function (e) {
          console.log(e.type, e.detail.action);
        },
        cropmove: function (e) {
          console.log(e.type, e.detail.action);
        },
        cropend: function (e) {
          console.log(e.type, e.detail.action);
        },
        crop: function (e) {
          console.log(e.type);
        },
        zoom: function (e) {
          console.log(e.type, e.detail.ratio);
        }
      }).cropper(optionsFavicon);
      
      
      jQuery('#exhibitorentryflow').change(function() {
            if(jQuery('#exhibitorentryflow').prop('checked')){

              jQuery("#toggle_btn").prop('checked',false);
                jQuery("#flowchart").show();
                jQuery("#table_id_AD_1").hide();
                jQuery("#table-div").hide();
                jQuery("#updt_btn_table").hide();
                jQuery("#section_table").hide();
         jQuery("#Queue_Behavior").hide();
            }else{
                
                jQuery("#flowchart").hide();
                jQuery("#toggle_btn").prop('checked',true);
                jQuery("#table_id_AD_1").show();
                jQuery("#updt_btn_table").show();
                jQuery("#section_table").show();
                jQuery("#table-div").show();
                jQuery("#Queue_Behavior").show();
            }
        });
      

     
      jQuery('.favicon-docs-buttons').on('click', '[data-method]', function () {
        var $this = jQuery(this);
        var data = $this.data();
        var cropper = $imageFavicon.data('cropper');
        var cropped;
        var $target;
        var result3;

        if ($this.prop('disabled') || $this.hasClass('disabled')) {
          return;
        }

        if (cropper && data.method) {
          data = jQuery.extend({}, data); // Clone a new one

          if (typeof data.target !== 'undefined') {
            $target = jQuery(data.target);

            if (typeof data.option === 'undefined') {
              try {
                data.option = JSON.parse($target.val());
              } catch (e) {
                console.log(e.message);
              }
            }
          }

          cropped = cropper.cropped;

          switch (data.method) {
            case 'rotate':
              if (cropped && optionsFavicon.viewMode > 0) {
                $imageFavicon.cropper('clear');
              }

              break;

            case 'getCroppedCanvas':
              if (uploadedImageTypeFavicon === 'image/jpeg') {
                if (!data.option) {
                  data.option = {};
                }

                data.option.fillColor = '#fff';
              }

              break;

                 // my code Shehroze starts

            case 'deleteimage':

             swal({
                                        title: "Are you sure?",
                                        text: 'Do you want to remove this Favicon Image ?',
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonClass: "btn-danger",
                                        confirmButtonText: "Yes, delete it!",
                                        cancelButtonText: "No, cancel please!",
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    },
                                    function(isConfirm) {
                                                                
                                                                
                                                                     
                                            if (isConfirm) {
               cropper.destroy();
                jQuery('#headerbgimgFavicon').removeAttr('src');

              
                  if (jQuery("#headerimageFavicon").val(" ")) {

                      jQuery("#headerimageFavicon").val("abc"); // favicon image was not removing so a dummy string is added 
                      jQuery(".hidebtnfavicon").attr('disabled', true);
                      jQuery(".removefavicon").attr('disabled', true);
                  }

                   jQuery(".unhidefavicon").val("");
                   jQuery(".hidebtnfavicon").attr('disabled', true);
                   jQuery(".removefavicon").attr('disabled', true);

               jQuery(".unhidefavicon").on('change', function(event){

                jQuery(".removefavicon").attr('disabled', false);
                jQuery(".hidebtnfavicon").attr('disabled', false);

         
            
        });
                                                                     
                                 swal.close();
                                                                     
                                    } else {
                                        swal({
                                            title: "Cancelled",
                                            text: "Favicon Image is safe :)",
                                            type: "error",
                                            confirmButtonClass: "btn-danger"
                                        });
                                    }
                                });
            
       
           
             break;

    //my code Shehroze ends
          }

          result3 = $imageFavicon.cropper(data.method, data.option, data.secondOption);

          switch (data.method) {
            case 'rotate':
              if (cropped && optionsFavicon.viewMode > 0) {
                $image.cropper('crop');
              }

              break;

            case 'scaleXFavicon':
            case 'scaleYFavicon':
              jQuery(this).data('option', -data.option);
              break;

            case 'getCroppedCanvas':
              if (result3) {
                // Bootstrap's Modal
                var inputImageType = jQuery("#inputImageFavicon")[0].files[0];
                var image1Date = new FormData();
                var extension;
                
                jQuery("body").css({'cursor':'wait'});
                if(inputImageType !="" && inputImageType != undefined){
                
                        var filetypeAc = jQuery("#inputImageFavicon")[0].files[0].type;
                        var filetype = filetypeAc.split('/');
                        extension = filetype[1];
                        console.log(filetypeAc);
                        console.log(filetype[1]);
                        image1Date.append('imagetype', filetype[1]);


                }else{
                        var fileURL = jQuery("#headerbgimgFavicon").attr('src');
                        extension = fileURL.substr( (fileURL.lastIndexOf('.') +1) );
                        console.log(extension);
                        console.log(filetype);
                        image1Date.append('imagetype', extension);
                }
                
                
                 var getheaderimage = result3.toDataURL(extension);
                image1Date.append('imagedata', getheaderimage);
               
                var url = currentsiteurl+'/';
                var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=uploadbase64image';
                jQuery.ajax({
                        url: urlnew,
                        data: image1Date,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function (data) {
                            
                            
                            jQuery("body").css({'cursor':'default'});
                            var newURL = jQuery.parseJSON(data);
                            jQuery("#headerimageFavicon").val(newURL);
                            jQuery('.previewDivselectedImageFavicon').empty();
                            var imagehtml = '<img style="width: 100%;" src="'+newURL+'" >';
                            jQuery('.previewDivselectedImageFavicon').append(imagehtml);
                        }
                            
                            
                            
                        });
                  
               
              }

              break;

            case 'destroyFavicon':
              if (uploadedImageURLFavicon) {
                URL.revokeObjectURL(uploadedImageURLFavicon);
                uploadedImageURLFavicon = '';
                $image.attr('src', originalImageURLFavicon);
              }

              break;
          }

          if (jQuery.isPlainObject(result3) && $target) {
            try {
              $target.val(JSON.stringify(result3));
            } catch (e) {
              console.log(e.message);
            }
          }
        }
      });

      
      var $inputImageFavicon = jQuery('#inputImageFavicon');

      if (URL) {
        $inputImageFavicon.change(function () {
          var files = this.files;
          var file;

          if (!$imageFavicon.data('cropper')) {
            return;
          }
          jQuery(".headerimagecropperFavicon").show();
          if (files && files.length) {
            file = files[0];

            if (/^image\/\w+$/.test(file.type)) {
              uploadedImageNameFavicon = file.name;
              uploadedImageTypeFavicon = file.type;

              if (uploadedImageURLFavicon) {
                URL.revokeObjectURL(uploadedImageURLFavicon);
              }

              uploadedImageURLFavicon = URL.createObjectURL(file);
              $imageFavicon.cropper('destroy').attr('src', uploadedImageURLFavicon).cropper(optionsFavicon);
              //$inputImage.val('');
            } else {
              window.alert('Please choose an image file.');
            }
          }
        });
      } else {
        $inputImageFavicon.prop('disabled', true).parent().addClass('disabled');
      }
    });
    
    jQuery( document ).ready(function() {
            
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab == ""){
                
                jQuery('#myTabs a[href="#tabs-1-tab-1"]').tab('show');
            }else{
                jQuery('#myTabs a[href="'+activeTab+'"').tab('show');
                localStorage.setItem('activeTab', "");
            }
            
            jQuery(function() {
          jQuery('#exhibitorentryflow').bootstrapToggle({
            on: 'Enabled',
            off: 'Disabled'
          });
        })
        jQuery('#exhibitorentryflow').change(function() {
            if(jQuery('#exhibitorentryflow').prop('checked')){

                jQuery("#toggle_btn").prop('checked',false);
                jQuery("#flowchart").show();
                jQuery("#table_id_AD_1").hide();
                jQuery("#updt_btn_table").hide();
                jQuery("#section_table").hide();
                jQuery("#Queue_Behavior").hide();
                jQuery("#table-div").hide();
            }else{
                
                jQuery("#flowchart").hide();
                jQuery("#toggle_btn").prop('checked',true);
                jQuery("#table_id_AD_1").show();
                jQuery("#table-div").show();
                jQuery("#updt_btn_table").show();
                jQuery("#section_table").show();
                jQuery("#Queue_Behavior").show();
            }
        });
        jQuery(window).load(function() {
            if ( window.location.href.indexOf("user-entry-settings") > -1)
             {
                jQuery('.block-msg-default').remove();
                jQuery('.blockOverlay').remove();
             }
        });
        
        jQuery('.eg-toggle').bootstrapToggle(
            );
        var $tabs=jQuery('#table-draggable2');
        
//        jQuery( "tbody" )
//            .sortable({
//               
//                items: "> tr:not(:nth-last-child(1),:nth-last-child(2),:nth-last-child(3),:nth-last-child(4),:nth-last-child(5),:nth-last-child(8))",
//                
//            })
//            .disableSelection()
//        ;
        
        
});
    
    
 
   
    
    
    </script>

          
           
       <?php }else{
           $redirect = get_site_url();
        wp_redirect( $redirect );exit;
       
       }
       ?>