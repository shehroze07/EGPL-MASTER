<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
    
    require_once plugin_dir_path( __DIR__ ) . '/includes/egpl-custome-functions.php';
    require_once plugin_dir_path( __DIR__ ) . '/includes/floorplan-manager.php';
    
    $boothproudctList = new FloorPlanManager();
    $Boothproducts = $boothproudctList->getAllboothswithproducts();
    $woocommerce_rest_api_keys = get_option( 'ContenteManager_Settings' );
    $wooconsumerkey = $woocommerce_rest_api_keys['ContentManager']['wooconsumerkey'];
    $wooseceretkey = $woocommerce_rest_api_keys['ContentManager']['wooseceretkey'];
    $url = get_site_url();
    
    $query_args = array(
    'limit' => -1,  
    'category' => array( 'uncategorized' ),
    );
    //$Boothproducts = wc_get_products( $query_args );
    
    //echo '<pre>';
    //print_r($Boothproducts);exit;
    
    
    
    
    $UserList = new EGPLCustomeFunctions();
    $listofuseremails = $UserList->getAllusersemailsaddress();
    $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'egpl_custome_tasks',
	'post_status'      => 'draft',
	
    );
    $taskkeyContent = get_posts( $args );
    
  
if(!empty($wooconsumerkey) && !empty($wooseceretkey)){
    
       require_once( 'lib/woocommerce-api.php' );
       $url = get_site_url();
       $options = array(
            'debug'           => true,
            'return_as_array' => false,
            'validate_url'    => false,
            'timeout'         => 30,
            'ssl_verify'      => false,
        );
       
       
       global $wp_roles;
      
       
       $all_roles = $wp_roles->roles;
       $client = new WC_API_Client( $url, $wooconsumerkey, $wooseceretkey, $options );
       $product_cat_list = $client->products->get_categories() ;
       $product_type = 'Packages';
       $product_name_for_fields_lebal = "Package";
                 
                 
            
       
       
       
       
       
       
       if(isset($_GET['productid'])){
           
           $product_id = $_GET['productid'];
           $update_product = wc_get_product( $product_id );
           
           
           $get_results = get_post_meta($product_id, "productlevel",true);
           $get_deposit_type = get_post_meta($product_id, "_wc_deposit_type",true);
           $get_deposit_amount = get_post_meta($product_id, "_wc_deposit_amount",true);
         
           $selectedTaskListData = get_post_meta( $product_id);
           
           $getvisiblelevelsnames = get_post_meta($product_id, "_alg_wc_pvbur_visible",true);
           
           $getvisiblelistofusers = get_post_meta($product_id, "_alg_wc_pvbur_uservisible",true);
           $get_depositenable_type = get_post_meta($product_id, "_wc_deposit_enabled",true);
           $listofboothsID = get_post_meta( $product_id, '_list_of_selected_booth', true);
           
           //echo '<pre>';
           //print_r($selectedTaskListData);exit;
           
           
           $selectedTaskList = unserialize($selectedTaskListData['seletedtaskKeys'][0]);
         
            foreach ($product_cat_list->product_categories as $cat_key=>$cat_value){
           
                if($cat_value->id == $update_product->category_ids[0]){
                    
                    $selectedcat_name = $cat_value->name;
                    
                }
            }
       
           $selectedcat_name = 'Packages';
           $product_type = "package";
           $product_name_for_fields_lebal = "Package";
                
           
           
       }else{
           
           $product_id = "";
       }
       
      
       
        include 'cm_header.php';
        include 'cm_left_menu_bar.php';
       
    
       
                ?>
<style>
    
    .eg-hr{
        
        margin: 1rem 0;
        border-top: 2px solid #a8b5c1;
    }
    .eg-sub-title{
        
        font-weight: 700;
    }
    
</style>
        <div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            
                                   

                                        <h3><?php echo $product_name_for_fields_lebal;?></h3>
                                        
                                   
                            
                            
                           
                        </div>
                    </div>
                </div>
            </header>
            <form method="post" action="javascript:void(0);" onSubmit="check_whocat_selet_packages()">
                
            <div class="box-typical box-typical-padding">
                <p>
                    Fill out the required fields to create your package. A package is a Level in ExpoGenie that your users have the option to purchase. Whenever a user purchases a package, they will be automatically assigned OR re-assigned to the level associated with that package.
                </p>
                
                
                 <div class="form-group row">
                          <label class="col-sm-8 form-control-label"></label>
                          <div class="col-sm-4" >
                              <button style="float: right;" type="submit" id="addnewproduct" name="addsponsor"  class="btn mycustomwidth btn-success" value="Register" egid="add-new-package">Save</button>
                             <a style="float: right;margin-right: 5%;" href="<?php echo site_url().'/manage-products/';?>" class="btn mycustomwidth btn-success" egid="cancel">Cancel</a>
                              
                          </div>
                </div>
                
                <hr >
                
                <h4 class="eg-sub-title">Package Details</h4>
                
                <hr >
                  
                  <input type="hidden" id="productid" value="<?php echo $product_id;?>" />
                  
                  <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Label <strong>*</strong></label>
                          <div class="col-sm-9">
                              
                             
                              <input type="text"  class="form-control" id="ptitle" value="<?php echo $update_product->name; ?>" placeholder="<?php echo $product_name_for_fields_lebal;?> Title" egid="ptitle" required>
                             
                              <input type="hidden" value="<?php echo $product_name_for_fields_lebal;?>" id="getcatname" >

                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Price <strong>*</strong></label>
                          <div class="col-sm-9">

                              <input type="number" min="0" oninput="validity.valid||(value='');"  class="form-control" id="pprice" name="pprice" value="<?php echo $update_product->regular_price; ?>" egid="pprice" placeholder="<?php echo $product_name_for_fields_lebal;?> Price" required>


                          </div>
                      </div>
                       <div class="form-group row">
                          
                          
                          <label class="col-sm-3 form-control-label">Enable Deposits <i data-toggle="tooltip" title="Select to give your users ability to pay a deposit payment for this item" class="fa fa-question-circle" aria-hidden="true"></i></label>
                          <div class="col-sm-9">
                              
                             
                              <select class="form-control" id="depositsstatus" egid="depositsstatus">
                                  
                                <?php if (isset($_GET['productid'])) { 
                                  if($get_depositenable_type == "optional"){  ?> 
                                   <option value="optional" selected="true">Deposit OR Pay in Full</option>
                                   <option value="forced">Deposit Only - No Option to Pay in Full</option>
                                   <option value="no">No</option>
                                <?php }else if($get_depositenable_type == "forced"){?>
                                    
                                    <option value="optional" >Deposit OR Pay in Full</option>
                                    <option value="forced" selected="true">Deposit Only - No Option to Pay in Full</option>
                                    <option value="no" >No</option>
                                    
                                <?php }else{ ?>
                                  
                                   <option value="optional" >Deposit OR Pay in Full</option>
                                   <option value="forced">Deposit Only - No Option to Pay in Full</option>
                                   <option value="no" selected="true">No</option>
                                <?php }}else{?>
                                   
                                  <option value="optional" >Deposit OR Pay in Full</option>
                                   <option value="forced">Deposit Only - No Option to Pay in Full</option>
                                   <option value="no" selected="true">No</option>
                                <?php } ?>
                                  
                              </select>
                              
                              
                              
                              
                             
                          </div>
                   </div>
                   
                  
                   <?php if(isset($_GET['productid'])) { 
                       if(!empty($get_deposit_type)){  ?>   
                        <div class="form-group row depositsdetail" >
                    <?php }else{ ?>      
                         <div class="form-group row depositsdetail" style="display:none;">
                     <?php }}else{?>
                        <div class="form-group row depositsdetail" style="display:none;">
                     <?php } ?>
                  
                 
                          
                          
                          <label class="col-sm-3 form-control-label">Deposits Type <i data-toggle="tooltip" title="For the initial payment, enter either a fixed dollar amount or a percentage of the entire cost." class="fa fa-question-circle" aria-hidden="true"></i></label>
                          <div class="col-sm-9">

                              <select class="form-control" id="depositstype">
                                  
                                <?php if (isset($_GET['productid'])) { 
                                  if($get_deposit_type == "percent"){  ?> 
                                   <option value="percent" selected="true">Percentage</option>
                                   <option value="fixed">Fixed Amount</option>
                                <?php }else{ ?>
                                  
                                   <option value="percent" >Percentage</option>
                                   <option value="fixed" selected="true">Fixed Amount</option>
                                <?php }}else{?>
                                   
                                  <option value="percent" >Percentage</option>
                                  <option value="fixed">Fixed Amount</option>
                                <?php } ?>
                                  
                              </select>

                          </div>
                   </div>
                    <?php if (isset($_GET['productid'])) { 
                       if(!empty($get_deposit_type)){  ?>   
                        <div class="form-group row depositsdetail" >
                    <?php }else{ ?>      
                         <div class="form-group row depositsdetail" style="display:none;">
                     <?php }}else{?>
                        <div class="form-group row depositsdetail" style="display:none;">
                     <?php } ?>
                          <label class="col-sm-3 form-control-label">Deposit Amount <i data-toggle="tooltip" title = ' <?php echo' Enter dollar amount for "Fixed Amount" types, and percentage amount for "Percentage" types';?> ' class="fa fa-question-circle" aria-hidden="true"></i></label>
                          <div class="col-sm-9">

                               <input  id="depositamount" class="form-control"  value="<?php echo $get_deposit_amount;?>" type="number" >	
                               <p class="depositeerror"></p>

                          </div>
                   </div>
                   
                             
                       <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Publish Status <strong>*</strong></label>
                          <div class="col-sm-9">

                              <select id="pstatus" class="form-control" egid="pstatus" required>
                                     
                                      
                                      <?php if (isset($_GET['productid'])) {
                                          if ($update_product->status == 'publish') {
                                              ?>

                                              <option value="publish" selected="selected">Published</option>
                                             
                                              <option value="draft">Draft (<?php echo $product_name_for_fields_lebal;?> will not be visible in the shop)</option>

                                            <?php } else if ($update_product->status == 'draft') { ?>

                                              <option value="publish" >Published</option>
                                              
                                              <option value="draft" selected="selected">Draft (<?php echo $product_name_for_fields_lebal;?> will not be visible in the shop)</option>

                                            <?php } } else { ?>

                                               <option value="publish" selected="selected">Published</option>
                                              
                                               <option value="draft" >Draft (<?php echo $product_name_for_fields_lebal;?> will not be visible in the shop)</option>
                                        <?php } ?>
                              </select>


                          </div>
                   </div>
                             
                             
                     <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Stock status <strong>*</strong></label>
                          <div class="col-sm-9">

                              <select onchange="checkstockstatus()" id="pstrockstatus" class="form-control" egid="pstrockstatus" required>
                                 
                                <?php if(isset($_GET['productid'])){
                                    if($update_product->stock_status == 'instock'){?>
                                    
                                    <option value="instock" selected="selected">In Stock</option> 
                                    <option value="outofstock">Out of Stock</option>
                                    <?php }else{ ?>
                                    <option value="instock">In Stock</option>
                                    <option value="outofstock" selected="selected">Out of Stock</option> 
                                   <?php }  ?>
                                <?php }else{?>
                                    <option value="instock" selected="selected">In Stock</option> 
                                    <option value="outofstock">Out of Stock</option>
                                <?php }?>
                              </select>


                          </div>
                    </div>
               
                  <div class="quanititybox">
                      <?php if (isset($_GET['productid'])) { if($update_product->stock_status == 'instock'){ ?>
                  <div class="form-group row stockstatusbox">
                          <label class="col-sm-3 form-control-label">Stock Quantity<strong>*</strong></label>
                          <div class="col-sm-9">

                              <input type="number" min="0" oninput="validity.valid||(value='');"  class="form-control" id="pquanitity" value="<?php echo $update_product->stock_quantity; ?>" name="pquanitity" placeholder="Stock Quantity" egid="pquanitity" required>


                          </div>
                 </div>
                      <?php }}else{?>
                  <div class="form-group row stockstatusbox" >
                          <label class="col-sm-3 form-control-label">Stock Quantity<strong>*</strong></label>
                          <div class="col-sm-9">

                              <input type="number" min="0" oninput="validity.valid||(value='');"  class="form-control" id="pquanitity" name="pquanitity" placeholder="Stock Quantity"  egid="pquanitity" required>


                          </div></div>
                    <?php }?>
                  </div>  
                 
                      <div class="form-group row">
                          <label class="col-sm-3 form-control-label"> Image <i data-toggle="tooltip" title="Recommended Max Size: w:500 h:500" class="fa fa-question-circle" aria-hidden="true"></i></label>
                          <div class="col-sm-9" id="changeimageupload" style="display:none;">
                              <input  type="file" class="form-control" id="updateproductimage" egid="updateproductimage" >				
                            </div>
                           <?php if (isset($_GET['productid'])) { 
                               
                               if(!empty($update_product->image_id)){
                               $url = wp_get_attachment_thumb_url($update_product->image_id);
                               
                               
                              
                               
                               ?>
                                <div class="col-sm-5 productremoveimageblock">
                                   <img src="<?php echo $url; ?>" width="150" />
                                    <input type="hidden" id="productoldimage" value="<?php echo $update_product->image_id; ?>" />
                                </div>
                                <div class="col-sm-4 productremoveimageblock" style="margin-top: 5%;">
                                    <a   onclick="changeimage()" class="btn btn-lg btn-danger" >Change Image</a>
                                </div>
                           <?php }else{ ?>
                            <div class="col-sm-9">
                              <input  type="file" class="form-control" id="productimage" egid="productimage">				
                            </div>
                           <?php }}else{ ?>
                          <div class="col-sm-9">
                              <input  type="file" class="form-control" id="productimage" egid="productimage">				
                            </div>
                          <?php } ?>
                 </div>
                  <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Position <i data-toggle="tooltip" title="This determines the order in which this package shows up in the shop based on the numeric order. For example, if you create 3 packages, and you select the position 1,2,3 for each of the package, the package with the position '1' will appear first. Leaving a position blank will default the package by creation date." class="fa fa-question-circle" aria-hidden="true"></i></label>
                          <div class="col-sm-9">

                            <input  id="menu_order" class="form-control"  value="<?php echo $update_product->menu_order; ?>" type="number" min="0" oninput="validity.valid||(value='');" egid="menu_order">		

                          </div>
                      </div>  
                    <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Short Description</label>
                          <div class="col-sm-9">

                             <textarea egid="pshortdescription"  class="pdescriptionbox"  id="pshortdescription"  ><?php echo $update_product->short_description; ?></textarea>	


                          </div>
                    </div>       
                          
                      
                    <div class="form-group row">
                          <label class="col-sm-3 form-control-label">Long Description</label>
                          <div class="col-sm-9">

                             
                              <textarea egid="pdescription" class="pdescriptionbox"   id="pdescription"  ><?php echo $update_product->description; ?></textarea>		

                          </div>
                    </div>
                             
                     
                  
                    
                    
                    <hr>

                        <h4 class="eg-sub-title">Workflows</h4>

                    <hr>  
                    
                    
                    <h5 class="eg-sub-title">IF this Package is Purchased, THEN:</h5>
                    
                        <div class="form-group row" id="selectedboothslist">
                 
                
                    
                 
                                    <label class="col-sm-3 form-control-label">Include These Booths In Package Price: </label>
                                    <div class="col-sm-9">
                                           
								 <select  class="form-control select2"  data-placeholder="Select Booths" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" id="listofbooths" egid="listofbooths">
								
                                                                     <option></option>
                                                                     <?php if (isset($_GET['productid'])) { 
                                                                         foreach ($Boothproducts as $boothproindex => $boothproductData) {
                                                                             
                                                                                
                                                                             
                                                                                 $boothID = $boothproductData['bootheID'];
                                                                                 $value_label = $boothproductData['boothNumber'];
                                                                                 if(!empty($boothproductData['boothproductid']) && $boothproductData['boothproductid']!=null){
                                                                                    if (in_array($boothID, $listofboothsID)) {

                                                                                        echo '<option value="' . $boothID . '" selected="selected">' . $value_label . '</option>';

                                                                                    }else{

                                                                                       echo '<option value="' . $boothID  . '">' . $value_label . '</option>';
                                                                                    }
                                                                                 }
                                                                           
                                                                         } 
                                                                     }else{ 
                                                                        foreach ($Boothproducts as $boothproindex => $boothproductData) {

                                                                                $boothID = $boothproductData['bootheID'];
                                                                                $value_label = $boothproductData['boothNumber'];
                                                                                
                                                                                 if(!empty($boothproductData['boothproductid']) && $boothproductData['boothproductid']!=null){
                                                                                     
                                                                                      echo '<option value="' . $boothID . '">' . $value_label . '</option>';
                                                                                     
                                                                                 }
                                                                                 
                                                                           
                                                                                
                                                                            
                                                                         }
                                                                        
                                                                         
                                                                        }?>
								 </select>
                                        <p><a href="<?php echo site_url().'/floor-plan-editor/';?>" style="font-style: italic;margin-left: 1%;" target="_blank">View Floor Plan</a></p>
					    
                                        
                                    </div>
                 </div>
                    
                     <div class="form-group row" id="assignmentlevelfield" >
                 
                                    <label class="col-sm-3 form-control-label">Assign Level <i data-toggle="tooltip" title="If you select a level here, the buyer of this product will be automatically assigned this level on successfully placing the order." class="fa fa-question-circle" aria-hidden="true"></i></label>
                                    <div class="col-sm-9">
                                           
								 <select  class="form-control" id="roleassign" egid="roleassign" required>
								
                                                                     <option></option>
                                                                     <?php if (isset($_GET['productid'])) { 
                                                                         foreach ($all_roles as $key => $name) {


                                                                             if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber' ) {
                                                                                
                                                                                 if($get_results == $key){
                                                                                     
                                                                                     echo '<option value="' . $key . '" selected="selected">' . $name['name'] . '</option>';
                                                                                 }else{
                                                                                 echo '<option value="' . $key . '">' . $name['name'] . '</option>';
                                                                                 }
                                                                             }
                                                                         } 
                                                                     }else{ 
                                                                        foreach ($all_roles as $key => $name) {


                                                                             if ($key != 'administrator' && $key != 'contentmanager' && $key != 'subscriber' ) {
                                                                                 echo '<option value="' . $key . '">' . $name['name'] . '</option>';
                                                                             }
                                                                         }
                                                                        
                                                                         
                                                                        }?>
								 </select>
					    
                                        
                                    </div>
                 </div>
                  <div class="form-group row" id="assignmentlevelfield">
                 
                
                    
                 
                                    <label class="col-sm-3 form-control-label">Assign Task(s) <i data-toggle="tooltip" title="If you select one or more tasks here, the buyer of this product will be automatically assigned these tasks on successfully placing the order." class="fa fa-question-circle" aria-hidden="true"></i></label>
                                    <div class="col-sm-9">
                                           
								 <select  class="form-control select2"  data-placeholder="Select Tasks" data-allow-clear="true" data-toggle="tooltip" multiple="multiple" id="selectedtasks" egid="selectedtasks" >
								
                                                                     <option></option>
                                                                     <?php if (isset($_GET['productid'])) { 
                                                                         foreach ($taskkeyContent as $taskindex => $taskValue) {
                                                                             
                                                                                
                                                                             
                                                                                 $tasksID = $taskValue->ID;
                                                                                 $value_key = get_post_meta( $tasksID, 'key', false);
                                                                                 
                                                                                
                                                                                 
                                                                                 $value_label = get_post_meta( $tasksID, 'label' , false);
                                                                                
                                                                                 if (in_array($tasksID, $selectedTaskList['selectedtasks'])) {
                                                                                     
                                                                                     echo '<option value="' . $tasksID . '" selected="selected">' . $value_label[0] . '</option>';
                                                                                     
                                                                                 }else{
                                                                                     
                                                                                    echo '<option value="' . $tasksID  . '">' . $value_label[0] . '</option>';
                                                                                 
                                                                                   
                                                                                 }
                                                                           
                                                                         } 
                                                                     }else{ 
                                                                        foreach ($taskkeyContent as $taskindex => $taskValue) {

                                                                                 $tasksID = $taskValue->ID;
                                                                                 $value_key = get_post_meta( $tasksID, 'key', false);
                                                                                 $value_label = get_post_meta( $tasksID, 'label' , false);
                                                                                 
                                                                                 
                                                                           
                                                                                 echo '<option value="' . $tasksID . '">' . $value_label[0] . '</option>';
                                                                            
                                                                         }
                                                                        
                                                                         
                                                                        }?>
								 </select>
					    
                                        
                                    </div>
                 </div>
                
                 <div class="form-group row" style="display:none;">
                          <label class="col-sm-3 form-control-label">Type</label>
                          <div class="col-sm-9">
                              
                              
                              
                              <select id="pcategories" class="form-control" onchange="checkptoducttype()" required >
                                    
                                      
                                         
                                          
                                          
                                          
                                          
                                          
                                            <?php 
                                            
                                            $typename =  'Packages';
                                            foreach ($product_cat_list->product_categories as $key => $value) { ?>
                                                  <?php if($value->name == 'Packages'){?>
                                                  
                                                  <option value="<?php echo $value->id; ?>" selected="selected"><?php echo $value->name; ?></option>
                                                      
                                                  <?php } } ?>
                                 
                                              
                                            </select>
                                  <label class="col-sm-3 form-control-label"><?php echo $typename;?></label>

                          </div>
                   </div>
                  
                 
                    
                      <div class="form-group row" style="display:none;">
                          <label class="col-sm-3 form-control-label">Position <i data-toggle="tooltip" title="This determines the order in which this product shows up in the shop based on the numeric order. For example, if you create 3 products, and you select the position 1,2,3 for each of the products, the product with the position '1' will appear first. Leaving a position blank will default the product by creation date." class="fa fa-question-circle" aria-hidden="true"></i></label>
                          <div class="col-sm-9">

                            <input  id="menu_order" class="form-control"  value="<?php echo $update_product->menu_order; ?>" type="number" min="0" oninput="validity.valid||(value='');"  egid="menu_order">		

                          </div>
                      </div>
                   
                    
                  
                   
                 

                     <div class="form-group row">
                          <label class="col-sm-8 form-control-label"></label>
                          <div class="col-sm-4" >
                              <button style="float: right;" type="submit" id="addnewproduct" name="addsponsor"  class="btn mycustomwidth btn-success" value="Register" egid="add-new-package">Save</button>
                             <a style="float: right;margin-right: 5%;" href="<?php echo site_url().'/manage-products/';?>" class="btn mycustomwidth btn-success" egid="cancel">Cancel</a>
                              
                          </div>
                </div>

                

                </form>
            </div>
        </div>
    </div>
    <?php  }else{?>
   <div class="page-content">
        <div class="container-fluid">
            <header class="section-header" id="bulkimport">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3><?php echo $product_name_for_fields_lebal;?></h3>
                           
                        </div>
                    </div>
                </div>
            </header>
            

<!--           /* <div class="box-typical box-typical-padding" >-->
                <div class="form-group row">
                
                    <p class="col-sm-12 "><strong>Shop is not configured for this site. Please contact ExpoGenie.</strong></p>
               
                </div>*/
            </div>
        </div>
    </div>

    <?php }include 'cm_footer.php'; ?>
    <script type="text/javascript" src="/wp-content/plugins/EGPL/js/manage-products.js?v=5.0"></script>
   
        
   <?php }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>