<?php
// Template Name: Bulk Edit Task 
if (current_user_can('administrator') || current_user_can('contentmanager')) {
   

    $sponsor_id = get_current_user_id();
    
    global $wp_roles;
    $currentblogid = get_current_blog_id();
    $user_id = get_current_user_id();
    $user_blogs = get_blogs_of_user( $user_id );
    
    include 'cm_header.php';
    include 'cm_left_menu_bar.php';
    
    ?>
   
  
<div class="page-content">
        <div class="container-fluid">
            <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Portal Clone Settings</h3>

                        </div>
                    </div>
                </div>
            </header>
           
            <div class="box-typical box-typical-padding">
               
               <p>
               This is where you can clone content and configurations from another portal to this one. Select the event from the drop down and then choose which area(s) you want to clone/copy/migrate over. Please read the descriptions carefully, <b>these actions cannot be undone</b>.
            </p>
               
               <h5 class="m-t-lg with-border"></h5>
            <form method="post" class="needs-validation" action="javascript:void(0);" onSubmit="submitcloningfeature()" >
            
                    
                
                    <div class="form-group row" >
                        <div class="col-sm-1">
                            <label>Select Event </label>
                        </div>
                        <div class="col-sm-10">
                             <select class="select2" id="usersportals" data-placeholder="Select Event" requried>
                                 <option value=""></option>
                                <?php foreach ($user_blogs as $blog_id) { 

                                    
                                    if($blog_id->userblog_id != 1 && $blog_id->userblog_id != $currentblogid){
                                        $sitename = $blog_id->blogname;
                                    echo '<option value="'.$blog_id->userblog_id.'">'.$sitename.'</option>';


                                    }
                                 }?>

                             </select>
                        </div>
                       
                    </div>  
                    
                    <h5 class="m-t-lg with-border"></h5>

                    <div class="form-group row" id="flowchart" >

                    <div class="col-sm-1"></div>

                        <div class="col-sm-10">

                            <table id='table-draggable1'>
                           				
                                <tbody>

                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 eventsettings">
									    <div class="col-sm-3">
										    <h2>Event Settings</h2> </div>
									        <div class="col-sm-6">
										        <p>Includes event dates, address, colors, and graphic assets. Includes Registration Configurations and Booth Management Settings.</p>
	    									    <p class="eg-editlink-2"><a href="<?php echo site_url().'/admin-settings/';?>" target="_blank">Event Settings</a></p>
											</div>
                                            <div class="col-sm-3">
                                                <input type="checkbox" class="toggle-one eg-toggle-2 hidecursor" id="eventsettingsstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" disabled>
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="eventsettings" data-toggle="toggle" data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
											
                                        </div>
                                    </div>
								    

                                     </td>
                                 </tr>
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 menupages">
									    <div class="col-sm-3">
										    <h2>Menu Navigation</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes all custom pages and the current menu navigation settings.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/manage-menu/';?>" target="_blank">Menu Pages & Navigation</a></p>
											</div>
											<div class="col-sm-3">
                                                <input type="checkbox" class="toggle-one eg-toggle-2 hidecursor" id="menupagesstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" disabled>
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="menupages" data-toggle="toggle" data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
                                        </div>   
								    </div>

                                     </td>
                                 </tr>

                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 levels">
									    <div class="col-sm-3">
										    <h2>Levels</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes all Levels.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/add-new-level/';?>" target="_blank">Levels</a></p>
											</div>
											<div class="col-sm-3">
                                            <input type="checkbox" class="toggle-one eg-toggle-2" id="levelsstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" >
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="levels" data-toggle="toggle"  data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
                                        </div>  
								    </div>
                                    

                                     </td>
                                 </tr>
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 tasks">
									    <div class="col-sm-3">
										    <h2>Tasks</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes all Tasks.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/bulk-edit-task/';?>" target="_blank">Tasks</a></p>
											</div>
											<div class="col-sm-3">
                                            <input type="checkbox" class="toggle-one eg-toggle-2 hidecursor" id="tasksstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" disabled>
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="tasks" data-toggle="toggle"  data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
                                        </div>  
								    </div>
                                    

                                     </td>
                                 </tr>
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 resources">
									    <div class="col-sm-3">
										    <h2>Resources</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes all Resources.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/all-resources/';?>" target="_blank">Resources</a></p>
											</div>
											<div class="col-sm-3">
                                            <input type="checkbox" class="toggle-one eg-toggle-2 hidecursor" id="resourcesstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" disabled>
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="resources" data-toggle="toggle"  data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
                                        </div>  
								    </div>

                                     </td>
                                 </tr>

                                 <tr>
                                     <td>

                                    <div  class="saveeverything eg-boxed-2 row eg-optional-2 Shop">
									    <div class="col-sm-3">
										    <h2>Shop</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes all Packages & Add-Ons.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/manage-products/';?>" target="_blank">Shop</a></p>
											</div>
											<div class="col-sm-3">
                                            <input type="checkbox" class="toggle-one eg-toggle-2 hidecursor" id="Shopstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" disabled>
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="Shop" data-toggle="toggle" data-on="Added" data-off="Skip" data-onstyle="success"></div>
								            </div>
                                        </div>  
								    </div>

                                     </td>
                                 </tr>
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 reports">
									    <div class="col-sm-3">
										    <h2>Reports</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes all custom saved reports.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/user-report-result/';?>" target="_blank">Reports</a></p>
											</div>
                                            <div class="col-sm-3">
                                            <input type="checkbox" class="toggle-one eg-toggle-2 hidecursor" id="reportsstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" disabled>
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="reports" data-toggle="toggle" data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
                                        </div>   
									 </div>

                                     </td>
                                 </tr>


                                 
                               
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 users">
									    <div class="col-sm-3">
										    <h2>Users</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes all users. Note that this function will only clone the basic user information with the record: First Name, Last Name, Company Name, Level, & Email.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/user-report-result/';?>" target="_blank">Users</a></p>
											</div>
                                            <div class="col-sm-3">
                                            <input type="checkbox" class="toggle-one eg-toggle-2" id="usersstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" >
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="users" data-toggle="toggle" data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
                                        </div>  
								    </div>

                                     </td>
                                 </tr>
                                
                                
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 userfields">
									    <div class="col-sm-3">
										    <h2>User Fields</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes all custom user fields.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/user-fields/';?>" target="_blank">User Fields</a></p>
											</div>
											<div class="col-sm-3">
                                            <input type="checkbox" class="toggle-one eg-toggle-2 hidecursor" id="userfieldsstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" disabled>
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="userfields" data-toggle="toggle"  data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
                                        </div>  
								    </div>
                                    

                                     </td>
                                 </tr>

                                

                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 florrplan">
									    <div class="col-sm-3">
										    <h2>Floor Plan</h2> </div>
									        <div class="col-sm-6">
										    <p>
                                            Includes floor plan design, booths, booth tags, and legend labels.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/admin-settings/';?>" target="_blank">Floor Plan</a></p>
											</div>
											<div class="col-sm-3">
                                            <input type="checkbox" class="toggle-one eg-toggle-2 hidecursor" id="florrplanstatus" data-toggle="toggle" data-on="Append" data-off="Override" data-onstyle="success" disabled>
											    <input type="checkbox" class="toggle-one eg-toggle-2" id="florrplan" data-toggle="toggle"  data-on="Added" data-off="Skip" data-onstyle="success" ></div>
								            </div>
                                        </div>  
								    </div>

                                     </td>
                                 </tr>

                               </tbody>

                            </table>
                        
                        </div>

                    </div>
                    <h5 class="m-t-lg with-border"></h5>
                    <div class="form-group row" >
                        <div class="col-sm-1"></div>
                        <div class="col-sm-9"> 
                            
                                    <div style="display: flex;">
                                       <input id="termscondition" type="checkbox"  requried>
                                       <label style="margin-left: 9px;">I acknowledge that performing the "Clone" function may result in losing data and/or configurations currently in this portal based on my selections.</label><br>
                                    </div>
                        </div>

                    </div>
                    

                    <div class="form-group row" >
                        <div class="col-sm-2"></div>
                        <div class="col-sm-9"> <button style="float:right;" type="submit"  name="clone" id="clone"  class="btn btn-lg mycustomwidth btn-success" value="Register">Clone</button></div>
                     </div>
                   
            
            
            </form>
            </div>
        </div>
</div>

        <?php
        include 'cm_footer.php';
        ?>
            
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/egpl_cloning_features.js?v=3.31"></script>    
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       
        <?php
        
    } else {
        $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
    }
    ?>