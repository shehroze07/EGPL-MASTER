<?php



add_action('admin_bar_menu', 'egpl_add_toolbar_items', 100);
function egpl_add_toolbar_items($admin_bar){
    $admin_bar->add_menu( array(
        
		'id'    => 'my-item',
        'title' => 'Egpl Patches',
		'parent' => 'network-admin',
        'href'  => admin_url('network/admin.php?page=egpl_patches'),
        'meta'  => array(
            'title' => __('Egpl Patches'),
        ),
    ));
}

function egplpatches_page() {
    
     add_menu_page( 'Egpl Patches',      
        'Egpl Patches',          
        'manage_network',                
        'egpl_patches',                
        'egpl_patches_content', 
        'dashicons-admin-tools');         
                                   
    
}
add_action( 'network_admin_menu', 'egplpatches_page' );

function egpl_patches_content(){
    
    
    $bodayContent = '<div class="row"><div class="col-12"><p><h1>Content Editor Standardized Page Titles</h1></p><p>Date of Patch Applied : 25-10-2021 </p><p><button class="button button-primary button-large" onclick="runthiscriptupdatepagestitles()" >Update Pages Titles</button></p></div></div>';
            
     echo  $bodayContent;   
			
   
}

