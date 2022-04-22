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
                        <label>From Event Portal </label>
                        </div>
                        <div class="col-sm-10">
                             <select class="select2" id="usersportals" egid="usersportals" data-placeholder="Select Event" requried>
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

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 eventsettings" egid="eventsettings">
									    
                                        <div class="col-sm-3">
										    <h2>Event Settings</h2> 
                                        </div>
									    <div class="col-sm-7">
										        <p>Includes event dates, address, colors, and graphic assets. Includes Registration Configurations and Booth Management Settings.</p>
	    									    <p class="eg-editlink-2"><a href="<?php echo site_url().'/admin-settings/';?>" target="_blank">Event Settings</a></p>
										</div>
                                        <div class="col-sm-2" >

                                        <div class="form-check myradiobtn">
                                        <input class="form-check-input" type="radio" name="eventsettings" id="eventsettings1" egid="eventsettings1" value="skip" checked>
                                        <label class="form-check-label mylabel" for="eventsettings1">
                                            Skip
                                        </label>
                                        </div>
                                    
                                        <div class="form-check myradiobtn">
                                        <input class="form-check-input" type="radio" name="eventsettings" id="eventsettings3" egid="eventsettings3" value="override" >
                                        <label class="form-check-label mylabel" for="eventsettings3">
                                        Override
                                        </label>
                                        </div>
                                      

                                        </div>
								    </div>
									

                                     </td>
                                 </tr>
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 menupages" egid="menupages">
									    <div class="col-sm-3">
                                        <h2>Pages, Content, & Menu</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes all standard page content, custom pages and their content, and the current menu navigation. </p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/manage-menu/';?>" target="_blank">Menu Pages & Navigation</a></p>
											</div>
											<div class="col-sm-2">
                                            <div class="form-check myradiobtn">
                                                <input class="form-check-input" type="radio" name="menupages" id="menupages1" egid="menupages1" value="skip" checked>
                                                <label class="form-check-label mylabel" for="menupages1">
                                                    Skip
                                                </label>
                                                </div>
                                           
                                                <div class="form-check myradiobtn">
                                                <input class="form-check-input" type="radio" name="menupages" id="menupages3" egid="menupages3" value="override" >
                                                <label class="form-check-label mylabel" for="menupages3">
                                                Override
                                                </label>
                                            </div>
                                       
                                    </div>
                                      

                                     </td>
                                 </tr>

                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 levels" egid="levels">
									    <div class="col-sm-3">
										    <h2>Levels</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes all Levels.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/add-new-level/';?>" target="_blank">Levels</a></p>
											</div>
											<div class="col-sm-2">

                                            <div class="form-check myradiobtn">
                                                <input class="form-check-input" type="radio" name="levels" id="levels1" egid="levels1" value="skip" checked>
                                                <label class="form-check-label mylabel" for="levels1">
                                                    Skip
                                                </label>
                                                </div>
                                                <div class="form-check disabled myradiobtn">
                                                <input class="form-check-input" type="radio" name="levels" id="levels2" egid="levels2" value="add" >
                                                <label class="form-check-label mylabel" for="levels2">
                                                    Add
                                                </label>
                                                </div>
                                                <div class="form-check myradiobtn">
                                                <input class="form-check-input" type="radio" name="levels" id="levels3" egid="levels3" value="override" >
                                                <label class="form-check-label mylabel" for="levels3">
                                                Override
                                                </label>
                                            </div>
                                            
                                            
                                            </div>
								    </div>
                                       
                                    

                                     </td>
                                 </tr>
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 tasks" egid="tasks">
									    <div class="col-sm-3">
										    <h2>Tasks</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes all Tasks.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/bulk-edit-task/';?>" target="_blank">Tasks</a></p>
											</div>
											<div class="col-sm-2">
                                            
                                            <div class="form-check myradiobtn">
                                                <input class="form-check-input" type="radio" name="tasks" id="tasks1" egid="tasks1" value="skip" checked>
                                                <label class="form-check-label mylabel" for="tasks1">
                                                    Skip
                                                </label>
                                                </div>
                                              
                                                <div class="form-check myradiobtn">
                                                <input class="form-check-input" type="radio" name="tasks" id="tasks3" egid="tasks3" value="override" >
                                                <label class="form-check-label mylabel" for="tasks3">
                                                Override
                                                </label>
                                            </div>
                                            
                                            
                                            </div>
                                      
                                    

                                     </td>
                                 </tr>
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 resources" egid="resources">
									    <div class="col-sm-3">
										    <h2>Resources</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes all Resources.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/all-resources/';?>" target="_blank">Resources</a></p>
											</div>
											<div class="col-sm-2">
                                            
                                            
                                            <div class="form-check myradiobtn">
                                                <input class="form-check-input" type="radio" name="resources" id="resources1" egid="resources1" value="skip" checked>
                                                <label class="form-check-label mylabel" for="resources1">
                                                    Skip
                                                </label>
                                                </div>
                                           
                                                <div class="form-check myradiobtn">
                                                <input class="form-check-input" type="radio" name="resources" id="resources3" egid="resources3" value="override" >
                                                <label class="form-check-label mylabel" for="resources3">
                                                Override
                                                </label>
                                            </div>
                                        
                                            </div>
                                      

                                     </td>
                                 </tr>

                                 <tr>
                                     <td>

                                    <div  class="saveeverything eg-boxed-2 row eg-optional-2 Shop" egid="Shop">
									    <div class="col-sm-3">
										    <h2>Shop</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes all Packages & Add-Ons.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/manage-products/';?>" target="_blank">Shop</a></p>
											</div>
											<div class="col-sm-2">
                                            
                                                <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="Shop" id="Shop1" egid="Shop1" value="skip" checked>
                                                    <label class="form-check-label mylabel" for="Shop1">
                                                        Skip
                                                    </label>
                                                    </div>
                                         
                                                    <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="Shop" id="Shop3" egid="Shop3" value="override" >
                                                    <label class="form-check-label mylabel" for="Shop3">
                                                    Override
                                                    </label>
                                                </div>
                                            

                                            </div>
                                       

                                     </td>
                                 </tr>
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 reports" egid="reports">
									    <div class="col-sm-3">
										    <h2>Reports</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes all custom saved reports.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/user-report-result/';?>" target="_blank">Reports</a></p>
											</div>
                                            <div class="col-sm-2">
                                            
                                                <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="reports" id="reports1" egid="reports1" value="skip" checked>
                                                    <label class="form-check-label mylabel" for="reports1">
                                                        Skip
                                                    </label>
                                                    </div>
                                          
                                                    <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="reports" id="reports3" egid="reports3" value="override" >
                                                    <label class="form-check-label mylabel" for="reports3">
                                                    Override
                                                    </label>
                                                </div>
                                        
                                        
                                            </div>
                                      

                                     </td>
                                 </tr>


                                 
                               
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 users" egid="users">
									    <div class="col-sm-3">
										    <h2>Users</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes all users. Note that this function will only clone the basic user information with the record: First Name, Last Name, Company Name, Level, & Email.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/user-report-result/';?>" target="_blank">Users</a></p>
											</div>
                                            <div class="col-sm-2">
                                           
                                            <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="users" id="users1" egid="users1" value="skip" checked>
                                                    <label class="form-check-label mylabel" for="users1">
                                                        Skip
                                                    </label>
                                                    </div>
                                                    <div class="form-check disabled myradiobtn">
                                                    <input class="form-check-input" type="radio" name="users" id="users2" egid="users2" value="add" >
                                                    <label class="form-check-label mylabel" for="users2">
                                                        Add
                                                    </label>
                                                    </div>
                                         

                                        </div>
                                      

                                     </td>
                                 </tr>
                                
                                
                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 userfields" egid="userfields">
									    <div class="col-sm-3">
										    <h2>User Fields</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes all custom user fields.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/user-fields/';?>" target="_blank">User Fields</a></p>
											</div>
											<div class="col-sm-2">
                                           
                                            <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="userfields" id="userfields1" egid="userfields1" value="skip" checked>
                                                    <label class="form-check-label mylabel" for="userfields1">
                                                        Skip
                                                    </label>
                                                    </div>
                                           
                                                    <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="userfields" id="userfields3" egid="userfields3" value="override" >
                                                    <label class="form-check-label mylabel" for="userfields3">
                                                    Override
                                                    </label>
                                                </div> 
                                        </div>
                                       
                                    

                                     </td>
                                 </tr>

                                

                                 <tr>
                                     <td>

                                    <div class="saveeverything eg-boxed-2 row eg-optional-2 florrplan" egid="florrplan">
									    <div class="col-sm-3">
										    <h2>Floor Plan</h2> </div>
									        <div class="col-sm-7">
										    <p>
                                            Includes floor plan design, booths, booth tags, and legend labels.</p>
	    									<p class="eg-editlink-2"><a href="<?php echo site_url().'/admin-settings/';?>" target="_blank">Floor Plan</a></p>
											</div>
											<div class="col-sm-2">
                                        
                                            <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="florrplan1" id="florrplan1" egid="florrplan1" value="skip" checked>
                                                    <label class="form-check-label mylabel" for="florrplan1">
                                                        Skip
                                                    </label>
                                            
                                                    <div class="form-check myradiobtn">
                                                    <input class="form-check-input" type="radio" name="florrplan3" id="florrplan3" egid="florrplan3" value="override" >
                                                    <label class="form-check-label mylabel" for="florrplan3">
                                                    Override
                                                    </label>
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
                                       <input id="termscondition" type="checkbox" egid="termscondition"  requried>
                                       <label style="margin-left: 9px;">I acknowledge that performing the "Clone" function may result in losing data and/or configurations currently in this portal based on my selections.</label><br>
                                    </div>
                        </div>

                    </div>
                    

                    <div class="form-group row" >
                        <div class="col-sm-2"></div>
                        <div class="col-sm-9"> <button style="float:right;" type="submit"  name="clone" id="clone" egid="clone"  class="btn btn-lg mycustomwidth btn-success" value="Register">Clone</button></div>
                     </div>
                   
            
            
            </form>
            </div>
        </div>
</div>

        <?php
        include 'cm_footer.php';
        ?>
            
        <script type="text/javascript" src="/wp-content/plugins/EGPL/js/egpl_cloning_features.js?v=4.16"></script>    
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
       
        <?php
        
    } else {
        $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
    }
    ?>