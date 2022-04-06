  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php
  // Silence is golden.
  // Template Name: Manage Menu Template

     if (current_user_can('administrator') || current_user_can('contentmanager') ) {
         
     
      
      global $wp_roles;
      $site_url  = get_site_url();

 

      
        include 'cm_header.php';
        include 'cm_left_menu_bar.php';
  ?>
        
    
   
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/nestable.css?v=1.04"> 

    <style>
      .custom_width{

          min-width: 900px !important;
      }

      .btn-icon-del{

        cursor: not-allowed  !important;
        pointer-events: none !important;

        /*Button disabled - CSS color class*/
        color: #c0c0c0 !important;
        background-color: #ffffff !important;

       
      }
    </style>
    
    </head>

    
      <div class="page-content">
          <div class="container-fluid">
              <header class="section-header">
                
                      
                          <div >
                              <h3>Manage Menu</h3>
       
                      </div>
                
              </header>
      
             
                  <div class="box-typical box-typical-padding">
                             <div class="card-block">

        <div class="row">
          <div class="col-md-6">
          
            <div class="dd nestable">
       

                <!--- Initial Menu Items --->
                <?php


                              
                                        
                                          $menu_name = 'primary';
                                          $locations = get_nav_menu_locations();
                                          $menu = wp_get_nav_menu_object($locations[$menu_name]);
                                          $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));

                                          $main_menu_id = $menu->term_id;


                                         //echo "<pre>";
                                         //print_r($menuitems);

                                        //exit;


                                          ?>
            
                                          <ol class="dd-list parent-list " >
                                              
                                          <?php
                                            

                                         foreach ($menuitems as $key => $item){

                                                  $link = $item->url;
                                                  $title = $item->title;
                                                  $id = $item->ID;
                                                  // echo "<pre>";
                                                  // print_r($id);

                                                  $menuorder = $item->menu_order;
                                                    //    echo "<pre>";
                                                    // print_r($menuorder);
                                                  $pageid = $item->object_id;

                                                  $object = $item->object;

                                                   // echo "<pre>";
                                                   // print_r($object);

                                                  $post = get_post($item->object_id); 
                                                  $slug = $post->post_name;

                                                
                                              
                                                   //echo "<pre>";
                                                   //print_r($slug);

                                                   $menuitemparent1 = $item->menu_item_parent;
                                                 
                                                     // echo "<pre>";
                                                     //  print_r($menuitemparent1);

  if($menuitemparent1 == 0){                                                   
   // echo "<pre>";
   // print_r( $title.'_______________parent') ;
if ($slug == 'add-ons' || $slug == 'add-ons-2' || $slug == 'account' || $slug == 'account-2' || $slug == 'cart' || $slug == 'floor-plan' || $slug == 'my-portals' || $slug == 'my-sites' || $slug == 'order-history' || $slug == 'order-history-2' ||  $slug == 'change-password' || $slug == 'change-password-2' || $slug == 'logout' ) {
      
       echo'
                                                                            
                <li class="dd-item parent" id="'.$id.'"  data-id="'.$menuorder.'" name="'.$title.' " url="'.$link.'" slug="'.$slug.'" pageid = "'.$pageid.'" menuorder = "'.$menuorder.'" itemobject = "'.$object.'" menuitemparent = "'.$menuitemparent1.'" >

               
                 <div style="background:  #e7f3fe !important;" class="dd-handle dd-parent" > '.$title.' </div>
                                      

                                                  
                                              
                                <span class="button-edit  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >              

     
                       

                                                 <i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit Menu Item" name="'.$title.' " url="'.$link.'" slug="'.$slug.'" main_menu_id = "'.$main_menu_id.'"  pageid = "'.$pageid.'" menuorder = "'.$menuorder.'"   onclick="editmenuname(this)" id="'.$id.'" egid="edit-menu-item" ></i>
    </span>


       <span class="button-delete btn-icon-del btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >
                                                 <i style="color: gray;" class="hi-icon fusion-li-icon fa fa-times-circle fa-2x"  onclick="delete_menu_name(this)" id="'.$id.'" slug="'.$slug.'" title="Delete Menu Item" egid="delete-menu-item" ></i>

                                                  </span>';
}
else{
  echo'
                                                                            
                <li class="dd-item parent" id="'.$id.'"  data-id="'.$menuorder.'" name="'.$title.' " url="'.$link.'" slug="'.$slug.'" pageid = "'.$pageid.'" menuorder = "'.$menuorder.'" itemobject = "'.$object.'" menuitemparent = "'.$menuitemparent1.'" >

               
                 <div class="dd-handle dd-parent" > '.$title.' </div>
                                      

                                                  
                                              
                                <span class="button-edit  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >              

     
                       

                                                 <i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit Menu Item" name="'.$title.' " url="'.$link.'" slug="'.$slug.'" main_menu_id = "'.$main_menu_id.'"  pageid = "'.$pageid.'" menuorder = "'.$menuorder.'"   onclick="editmenuname(this)" id="'.$id.'" egid="edit-menu-item"></i>
    </span>

                                  <span  class="button-delete  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >
                                                 <i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x"  onclick="delete_menu_name(this)" id="'.$id.'" slug="'.$slug.'" title="Delete Menu Item" egid="delete-menu-item" ></i>

                                                  </span>';
                                                                                         



  }



}
 

  $counterul = 0;

  $childclassname = "childeclass-".$id;

  // echo $childclassname;

 echo '<ol class="dd-list child-list '.$childclassname.'"  id="'.$id.'">';  


 
  foreach ($menuitems as $items) {

                  $menuitemparent = $items->menu_item_parent;
                   $ctitle = $items->title;


                    //  echo "<pre>";
                    // print_r($ctitle);

                    $clink = $items->url;
                                               
                   $cid = $items->ID;

                   // echo "<pre>";
                   // print_r($cid);

                   $cmenuorder = $items->menu_order;
                      //    echo "<pre>";
                      // print_r($cmenuorder);

                    $cpageid = $items->object_id;

                   $cobject = $items->object;

                    //  echo "<pre>";
                    // print_r($cobject);

                   $cpost = get_post($items->object_id); 
                   $cslug = $cpost->post_name;

                                              
                    //echo "<pre>";
                    //print_r($slug);

                                              
                  if ($id == $menuitemparent) {

                  //   if($counterul == 0){

                      

                  //   }

                  //   $counterul++;
if ($cslug == 'add-ons' || $cslug == 'add-ons-2' || $cslug == 'account' || $cslug == 'account-2' || $cslug == 'cart' || $cslug == 'floor-plan' || $cslug == 'my-portals' || $cslug == 'my-sites' || $cslug == 'order-history' || $cslug == 'order-history-2' ||  $cslug == 'change-password' || $cslug == 'change-password-2' || $cslug == 'logout') {


    echo ' <li class="dd-item child" id="'.$cid.'"  name="'.$ctitle.' " url="'.$clink.'" slug="'.$cslug.'" pageid = "'.$cpageid.'" menuorder = "'.$cmenuorder.'" itemobject = "'.$cobject.'"  menuitemparent = "'.$menuitemparent.'"  >
             
                <div style="background:  #e7f3fe !important;" class="dd-handle dd-child" > '.$ctitle.' </div>
                       
    <span class="button-edit  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >    
                       

                                                 <i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit Menu Item" name="'.$ctitle.' " url="'.$clink.'" slug="'.$cslug.'" main_menu_id = "'.$main_menu_id.'"  pageid = "'.$cpageid.'" menuorder = "'.$cmenuorder.'"   onclick="editmenuname(this)" id="'.$cid.'" egid="edit-menu-item" ></i>
    </span>

      <span class="button-delete btn-icon-del btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >    
                                                 <i style="color: gray;" class="hi-icon fusion-li-icon fa fa-times-circle fa-2x"  onclick="delete_menu_name(this)" id="'.$cid.'" title="Delete Menu Item" egid="delete-menu-item" ></i>

                                                  </span>
                                                  <ol class="dd-list child-list childeclass-'.$cid.'" id="'.$cid.'" ></ol>
                                                  </li>';

}

else{
               echo ' <li class="dd-item child" id="'.$cid.'"  name="'.$ctitle.' " url="'.$clink.'" slug="'.$cslug.'" pageid = "'.$cpageid.'" menuorder = "'.$cmenuorder.'" itemobject = "'.$cobject.'"  menuitemparent = "'.$menuitemparent.'"  >
             
                <div class="dd-handle dd-child" > '.$ctitle.' </div>
                       
    <span class="button-edit  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >    
                       

                                                 <i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit Menu Item" name="'.$ctitle.' " url="'.$clink.'" slug="'.$cslug.'" main_menu_id = "'.$main_menu_id.'"  pageid = "'.$cpageid.'" menuorder = "'.$cmenuorder.'"   onclick="editmenuname(this)" id="'.$cid.'" egid="edit-menu-item"></i>
    </span>

    <span class="button-delete  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a" >    
                                                 <i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x"  onclick="delete_menu_name(this)" id="'.$cid.'" title="Delete Menu Item" egid="delete-menu-item" ></i>

                                                  </span>
                                                  <ol class="dd-list child-list childeclass-'.$cid.'" id="'.$cid.'" ></ol>
                                                  </li>';
    }                             
                  }

                

     }



        echo  '</ol>';
       if($counterul != 0){

           
          }   

       echo '</li>';       
                                              
  }

                   ?>

                                    
     </ol> 
  </div>
      </div>
        <div class="col-md-6">
                  
                  <span style="float: right;"><button onclick="add_new_menu_item()" class="btn btn-inline mycustomwidth btn-success" title="Add New Menu Item" egid="add-menu-item">Add Menu Item</button></span>
     
                                                   <span style="float: right;"><button onclick="set_menu_item_order()" class="btn btn-inline mycustomwidth btn-success" title="Add New Menu Item" egid="save-menu-order">Save Menu Order</button></span>
             </div>

         </div>
  </div>

  </div>
  </div>

  </div>


  <?php   include 'cm_footer.php';?>

    
    

<script type="text/javascript" src="/wp-content/plugins/EGPL/js/jquery.nestable.js?v=1.15"></script>

<script>
// $('.dd.nestable').nestable({
//        maxDepth: 5
//      })
//        .on('change', updateOutput);

  jQuery('.dd.nestable').nestable({
        maxDepth: 2
      }).on( 'change', function(e) {


          jQuery(".dd-child").addClass("dd-parent");
          jQuery(".dd-child").removeClass("dd-child");
         

         jQuery(".child").addClass("parent");
         jQuery(".child").removeClass("child");

  });

   
  jQuery('.parent-list').find('.parent').each(function(index, value){
      
            var menuItemid = jQuery(this).attr('id');
            var childarray = jQuery(".childeclass-"+menuItemid+" > li").length;
            if(childarray==0){
                
                jQuery("#"+menuItemid +"> button").hide();
            }
      
  });
  
  jQuery('.nestable').find('ol').each(function(index, value){
      
            var menuItemid = jQuery(this).attr('id');
            var childarray = jQuery(".childeclass-"+menuItemid+" > li").length;
            if(childarray==0){
                
                jQuery("#"+menuItemid +"> button").hide();
            }
      
  });


  
</script>   


  <?}else{
         $redirect = get_site_url();
      wp_redirect( $redirect );exit;
     
     }
     ?>




