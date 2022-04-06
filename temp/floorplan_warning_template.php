<?php
// Silence is golden.
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
    
    
    
  

       
       
        include 'cm_header.php';
        include 'cm_left_menu_bar.php';
       
    
       
                ?>

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

            <div class="box-typical box-typical-padding">
                
                
                
                <p>
                    Your floor plan editing session has expired due to inactivity. This is to prevent conflicts in floor plan changes. <a href="<?php echo site_url().'/floor-plan-editor/';?>" >Click</a> here to return to the floor plan editor.
                </p>
               
               
                

            
            </div>
        </div>
    </div>

    <?php include 'cm_footer.php'; ?>
    
   
        
   <?php }else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>