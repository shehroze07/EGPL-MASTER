<?php 

global $current_user;
get_currentuserinfo();

$oldvalues = get_option( 'ContenteManager_Settings' );

$logo_imag = $oldvalues['ContentManager']['adminsitelogo'];
$expogeniefloorplanstatus = $oldvalues['ContentManager']['expogeniefloorplan'];
$autoreviewstatus = $oldvalues['ContentManager']['boothpurchasestatus'];



$site_url  = get_site_url();
$blog_title = get_bloginfo( 'name' );
$user_id = get_current_user_id();

$user_blogs = get_blogs_of_user( $user_id );
$firstName = get_user_option('first_name',$user_id);
$lastname = get_user_option('last_name',$user_id);
$blog_id2 = get_current_blog_id();

$getsiteurl = get_site_url();
        
        $getcodeurl1 = str_replace("https://","",$getsiteurl);
        $getcodeurl2 = str_replace("/","-",$getcodeurl1);
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $virtualpluginstatus = get_option('Activated_VirtualEGPL');
        
      
?>

<style>
    
    .unread-status{
        
        margin-top:-20px !important;
    }
    .feed-window div{ font-family: 'Proxima Nova' !important; }
    
      .dropdown-menu1{

    	margin-left: -50px;
    }
    .dropdown-menu2{

    	margin-left: -100px;
    }
</style>

</head>
<body class="with-side-menu theme-picton-blue">
     

	<header class="site-header">
	    <div class="container-fluid">
           <?php if(!empty($logo_imag)){?>
	        <a   class="site-logo" style="cursor: default;">
	            <img class="hidden-md-down" src="<?php echo $logo_imag;?>" alt="">
	            <img class="hidden-lg-up"   src="<?php echo $logo_imag;?>" alt="">
	        </a>
           <?php }?>
	        <button class="hamburger hamburger--htla">
	            <span>toggle menu</span>
	        </button>
	        <div class="site-header-content">
	            <div class="site-header-content-in" style="margin-top: -10px;">
	               <!-- <div class="site-header-shown">
	                  
                            <section class="widget widget-simple-sm statistic-box yellow __web-inspector-hide-shortcut__" style="margin-top: -13px !important;">
                                <div class="widget-simple-sm-statistic" style="height: 72px;">
                                    <div class="number" id="eventdays" style="padding: 0px !important;font-size: 36px;"></div>
                                    <div class="caption color-blue" style="color: #ffffff !important;font-size: 9px;"> Days to event</div>
                                </div>

                            </section>
                        </div>
                        <div class="site-header-shown" style="    margin-right: 13px !important;">
	                  
                               <section class="widget widget-simple-sm statistic-box purple __web-inspector-hide-shortcut__" style="margin-top: -13px !important;">
                                <div class="widget-simple-sm-statistic" style="height: 72px;">
                                    <div class="number" id="activeuser" style="padding: 0px !important;font-size: 36px;"></div>
                                    <div class="caption color-blue" style="color: #ffffff  !important;font-size: 9px;"> Active Users</div>
                                </div>

                            </section>
	                </div>
	            -->
	
	                <div class="mobile-menu-right-overlay"></div>
                        
                        <?php 
                        
                        $contentmanager_settings = get_option( 'ContenteManager_Settings' );
                        $id = $contentmanager_settings['ContentManager']['floorplanactiveid'];
                        $floorplanstatuslockunlock = get_post_meta( $id, 'updateboothpurchasestatus', true );
                        
                        if($floorplanstatuslockunlock !='unlock'){?>
                        <style> .site-header-collapsed {
                            margin-top: -11px !important;
                        }</style>
                        <div class="toast-fixed"><div class="js-toast-container"><div class="toast toast--error toast--dismiss"><p class="toast__text">Important: Your floor plan is currently locked since you haven't properly exited the editor. It is recommended to go back to the editor and click "Exit" to prevent any live floor plan conflicts.</p></div></div></div>
                        <?php }?>
                        <div class="site-header-collapsed" >
	                    <div class="site-header-collapsed-in">
                              <div class="dropdown" style="margin-top: 5px;margin-left: 4px;">
                                    
                                   <notification-center appId="ahWkagLbTC" subscriberId="<?php echo $getcodeurl2;?>" />
                                    
                                </div>
                                <div class="dropdown" style="margin-top: 5px;">
                                    <button class="btn btn-rounded dropdown-toggle" id="dd-header-add" style="width: 100px;" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Admin
                                    </button>
                                    <div class="dropdown-menu dropdown-menu1" aria-labelledby="dd-header-add">
                                        <a class="dropdown-item" href="<?php echo $site_url; ?>/add-content-manager-user/">
                                            <i class="font-icon fa fa-user-md"></i>
                                            <span class="lbl">Add New Admin</span>
                                        </a>
                                        <a class="dropdown-item" href="<?php echo $site_url; ?>/admin-settings/">
                                            <i class="font-icon fa fa-cogs"></i>
                                            <span class="lbl">Settings</span>
                                        </a>
                                        
                                          
                                        
                                        <a class="dropdown-item" href="<?php echo $site_url; ?>/change-password/">
                                            <i class="font-icon fa fa-lock"></i>
                                            <span class="lbl">Change Password</span>
                                        </a>
                                        <a class="dropdown-item" target="_blank" href="https://expo-genie.com/release-notes/">
                                            <i class="font-icon fa fa-sticky-note-o"></i>
                                            <span class="lbl">Release Notes</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        
                                        <a class="dropdown-item" href="<?php echo $site_url; ?>/logout/">
                                            <i class="font-icon fa fa-sign-out"></i>
                                            <span class="lbl">LogOut</span>
                                        </a>
                                        
                                    </div>
                                </div>
                                
                                
                              
                                
                                 <div class="dropdown" style="margin-top: 5px;margin-right: 4px;">
                                    <button  class="btn btn-rounded dropdown-toggle" style="width: 100px;" id="dd-header-add" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Portals
                                    </button>
                                    <div class="dropdown-menu dropdown-menu2" aria-labelledby="dd-header-add">
                                        
                                        <div class="dropdown-header">Switch Portal</div>
                                        
                                        <?php 
                                        
                                        
                                       
                                        
                                        foreach ($user_blogs as $blog_id) { 
                                            
                                            $sitename = $blog_id->blogname;
                                             if($blog_id->userblog_id != 1){
                                                 
                                               echo '<a class="dropdown-item" href="'.$blog_id->siteurl.'/dashboard/"><i class="font-icon fa fa-globe"></i><span class="lbl">'.$sitename.'</span></a>';
                                
                                               
                                             }
                                        }
                                        ?> 
                                        
                                        
                                       
                                    </div>
                                </div>
                                
    
    
   

                                <div class="dropdown" style="margin-top: 5px;margin-right: 4px;">
                                    <button  class="btn btn-rounded " id="btn-help" onclick="embadhelpvidoe()" type="button" style="width: 100px;height: 30px;padding: 0 12px; font-size: .8125rem;line-height: 28px;">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;&nbsp;Help 
                                    </button>
                                    
                                </div>


    							 <div class="dropdown" style="margin-top: 5px;margin-right: 4px;">
                                    <button  class="btn btn-rounded " id="btn-contact" onclick="contact_us()" type="button" style="width: 140px;height: 30px;padding: 0 12px; font-size: .8125rem;line-height: 28px;">
                                        <i class="fa fa-envelope-open" aria-hidden="true"></i>&nbsp;&nbsp;Contact Support 

                                    </button>
                                    
                                </div>

	                        <div class="">
                                    <h6 style="text-align: left;color:#000;margin-top: 10px;font-weight: bolder;" >
                                        
                                        
                                        <a style="color:#000;" href="<?php echo $site_url; ?>" target="_blank"><?php echo $blog_title;?></a>
                                        
                                    </h6>
	                           
	                        </div><!--.help-dropdown-->
	                     
	                    </div><!--.site-header-collapsed-in-->
	                </div><!--.site-header-collapsed-->
	            </div><!--site-header-content-in-->
	        </div><!--.site-header-content-->
	    </div><!--.container-fluid-->
	</header><!--.site-header-->
        
        
<div class="mobile-menu-left-overlay"></div>
	<nav class="side-menu">
	<div class="side-menu-avatar" style="background: #fff;">
	        <div class="avatar-preview avatar-preview-100">
                    Welcome,
                    <p><strong><?php echo $firstName.' '.$lastname;?></strong></p>
                    
	        </div>
            
	    </div>
 <hr style="margin: 0px;">           
<ul class="side-menu-list" style="margin-top:7px;">
                
                
            <li class="mythemestyle">
	            <a href="<?php echo $site_url; ?>/dashboard/">
	               
                        <i style="color:#004598 !important;" class="font-icon fa fa-dashboard"></i>
	                <span class="lbl">Dashboard</span>
	            </a>
	    </li>
            <li class="mythemestyle with-sub opened">
	            <span>
	                 <i style="color:#004598 !important;" class="font-icon fa fa-plus-square"></i>
	                <span class="lbl">Reports</span>
	            </span>
	            <ul class="mynav">
                        
                        <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/custom_task_report/">
	                   <i class="font-icon fa fa-th-list"></i>
	                    <span class="lbl menumargine">Task Report</span>
	                </a>
	            </li>
                        
	                <li class="mythemestyle">
                             <a href="<?php echo $site_url; ?>/user-report-result/">
                               <span class="glyphicon glyphicon-th"></span>
	                       <span class="lbl menumargine">User Report</span>
                            </a>
                            
                        </li>
                    
	            <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/order-report/">
	                   <i class="font-icon fa fa-shopping-cart"></i>
	                    <span class="lbl menumargine">Orders Report</span>
	                </a>
	            </li>
                    
	            </ul>
	        </li>
            <li class="mythemestyle with-sub">
	            <span>
	                 <i style="color:#004598 !important;" class="font-icon fa fa-plus-square"></i>
	                <span class="lbl">Users</span>
	            </span>
	            <ul class="mynav">
	               
	            <li class="mythemestyle">
                           <a href="<?php echo $site_url; ?>/create-user/">
                               <i class="font-icon fa fa-user-plus"></i>
	                    <span class="lbl menumargine">Add New User</span>
                            </a>
                            
                   </li>
                     
                       
                         <li class="mythemestyle">
                            <a href="<?php echo $site_url; ?>/bulk-import-user/">
	                   <i class="font-icon fa fa-upload"></i>
	                    <span class="lbl menumargine">Bulk Import Users</span>
	                </a>
                        </li>
                           
                    <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/review-registration/">
	                  <i class="font-icon fa fa-eye"></i>
	                    <span class="lbl menumargine">Review Applicants</span>
	                </a>
                    </li>
                       
                 
	            </ul>
	        </li>
                <li class="mythemestyle with-sub">
	            <span>
	                 <i style="color:#004598 !important;" class="font-icon fa fa-plus-square"></i>
	                <span class="lbl">Content</span>
	            </span>
                    <ul class="mynav">
                       <li class="mythemestyle"> 
	                <a href="<?php echo $site_url; ?>/welcome-email/">
	                   <i class="font-icon fa fa-envelope"></i>
	                    <span class="lbl menumargine">Welcome Email</span>
	                </a>
	            </li>
	            <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/content-editor/">
	                   <i class="font-icon fa fa-pencil"></i>
	                    <span class="lbl menumargine">Content Editor</span>
	                </a>
	            </li>
	          <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/create-new-page/">
	                  <i class="font-icon fa fa-pencil"></i>
	                    <span class="lbl menumargine">Create New Page</span>
	                </a>
	           </li>
	               <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/manage-menu/">
	                  <i class="font-icon fa fa-bars"></i>
	                    <span class="lbl menumargine">Manage Menu</span>
	                </a>
	           </li>
	            </li> 
                    <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/user-fields/">
	                  <i class="font-icon fa fa-wpforms"></i>
	                    <span class="lbl menumargine">User Fields</span>
	                </a>
	            </li> 
                  
                    
<!--                    <a href="<?php //echo $site_url; ?>/admin-settings/">
	                  <i class="font-icon fa fa-gears"></i>
	                    <span class="lbl menumargine">Header Image</span>
	                </a>-->
                    </ul>
                </li>
                <li class="mythemestyle with-sub">
	            <span>
	                 <i style="color:#004598 !important;" class="font-icon fa fa-plus-square"></i>
	                <span class="lbl">Levels & Tasks</span>
	            </span>
                    <ul class="mynav">
                        <li class="mythemestyle"> 
	                <a href="<?php echo $site_url; ?>/bulk-edit-task/">
	                   <i class="font-icon fa fa-tasks"></i>
	                    <span class="lbl menumargine">Manage Tasks</span>
	                </a>
	            </li>
	           <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/add-new-level/">
	                  <i class="font-icon fa fa-bars"></i>
	                    <span class="lbl menumargine">Manage Levels</span>
	                </a>
	           </li>
                   <li class="mythemestyle">
                            <a href="<?php echo $site_url; ?>/bulk-download-files/">
                                <i class="font-icon fa fa-download"></i>
                                <span class="lbl menumargine">Bulk Download</span>
                            </a>
                            
                   </li> 
                    </ul>
                </li>
                <li class="mythemestyle with-sub">
	            <span>
	                  <i style="color:#004598 !important;" class="font-icon fa fa-plus-square"></i>
	                <span class="lbl">Resources</span>
	            </span>
                    <ul class="mynav">
                       
	            <li class="mythemestyle"> 
	                <a href="<?php echo $site_url; ?>/all-resources/">
	                   <i class="font-icon fa fa-files-o"></i>
	                    <span class="lbl menumargine">Manage Resources</span>
	                </a>
	            </li> 
                    </ul>
                </li>
                <li class="mythemestyle with-sub">
	            <span>
	                  <i style="color:#004598 !important;" class="font-icon fa fa-plus-square"></i>
	                <span class="lbl">Shop</span>
	            </span>
                    <ul class="mynav">
                       <li class="mythemestyle">
	                <a href="<?php echo $site_url; ?>/manage-products/">
	                  <i class="font-icon fa fa-shopping-cart"></i>
	                    <span class="lbl menumargine">Manage Shop</span>
	                </a>
                        </li>
                       
                    </ul>
                </li>
             <?php if($expogeniefloorplanstatus == 'enable'){?>
                 <li class="mythemestyle with-sub">
	            <span>
	                  <i style="color:#004598 !important;" class="font-icon fa fa-plus-square"></i>
	                <span class="lbl">Floor Plan</span>
	            </span>
                    <ul class="mynav">
                       
                       <li class="mythemestyle">
                           <a href="<?php echo $site_url; ?>/floor-plan-editor/" target="_blank">
                                <i class="font-icon fa fa-map-o"></i>
                                <span class="lbl menumargine">Floor Plan Editor    </span><i style="color:black;left:170px;"class="fa fa-window-restore" aria-hidden="true"></i>
                           </a>
                      </li>
<!--	              <li class="mythemestyle">
                           <a href="<?php echo $site_url; ?>/floor-plan-2/" target="_blank">
                                <i class="font-icon fa fa-map"></i>
                                <span class="lbl menumargine">Floor Plan Viewer </span>
                           
                            </a>
                      </li>-->
                     <?php if($autoreviewstatus != 'enabled'){?>  
                     
                      <li class="mythemestyle">
                           <a href="<?php echo $site_url; ?>/manage-exhibitor-booths/" >
                                <i class="font-icon fa fa-cart-arrow-down"></i>
                                <span class="lbl menumargine">Review Booth Purchases </span>
                           
                            </a>
                      </li>
                      <?php } ?>  
                    </ul>
                </li>
             <?php } ?>  
              <?php 
              
                if( $virtualpluginstatus == 'VirtualEGPL/virtualegpl.php' ) {?>
                
                       
                      <?php include_once( WP_PLUGIN_DIR . '/VirtualEGPL/templates/cm_left_menu_bar_vu.php' ); ?>

                     
                  
             <?php } ?>     
 </ul>

</nav><!--.side-menu-->


<?php 



switch_to_blog( $blog_id2 );?>
