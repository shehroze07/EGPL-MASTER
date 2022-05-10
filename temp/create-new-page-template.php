<?php
// Silence is golden.
// Template Name: Create New Page
   if (current_user_can('administrator') || current_user_can('contentmanager') ) {
       
   
$editor_id = 'mycustomeditor';
//    $args = array(

//     'current_category'    => 0,
   
//     'hide_empty'          => 0,
//     'hide_title_if_empty' => false,
//     'order'               => 'ASC',
//     'orderby'             => 'name',
//     'separator'           => '<br />',
//     'show_count'          => 0,
//     'show_option_all'     => '',
//     'style'               => 'list',
//     'taxonomy'            => 'category',
//     'title_li'            => __( 'Categories' ),
//     'use_desc_for_title'  => 1,
// );

// var_dump( wp_list_categories($args) );
// $cat = 'Content Manager Editor';
// $cat_id = get_cat_ID( $cat );
// echo $cat_id;
 
    // $categories = get_categories();

      
    
     include 'cm_header.php';
     include 'cm_left_menu_bar.php';
                ?>


   


                
   <div class="page-content">
        <div class="container-fluid">
              <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Create New Page</h3>
                           
                        </div>
                    </div>
                </div>
            </header>
          <div class="box-typical box-typical-padding">
                <p>
                
                 Here you can add pages and their content. Be sure to carefully review your changes before saving since your saved changes will be published immediately.
                </p>

                <h5 class="m-t-lg with-border"></h5>
            </div>

                <div class="box-typical box-typical-padding">

                    <form method="post" action="javascript:void(0);" onSubmit="create_page()">

             <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Add New Page <strong>*</strong></label>
                            <div class="col-sm-7">
  <div class="form-control-wrapper form-control-icon-left">    
                                <input type="text"  class="form-control" id="pagename" placeholder="Page Name" value="" egid="pagename" required>
 <i class="font-icon fa fa-edit"></i>
 </div>
                            </div>
                        </div>
                            <!-- <div class="col-sm-3">
                                <button type="submit" id="page"  name="addsponsor"  class="btn btn-inline mycustomwidth btn-success" value="Register">Create Page</button>
                              
                            </div> -->
                           <br><br><br>
                           <div class="form-group row">
                            <div>
                                      <label class="col-sm-2 form-control-label">Content <strong>*</strong></label>
                                    <div class="col-sm-7">
                                        <textarea id="mycustomeditor" egid="mycustomeditor"></textarea>
                                    </div>
                                </div>
                            </div>
                                  <div class="form-group row">
                                     <label class="col-sm-2 form-control-label"></label>
                                 <div class="col-sm-6">
                                <button type="submit" id="page"  name="addsponsor"  class="btn btn-inline mycustomwidth btn-success" value="Register" style="text-align: center;" egid="create-page">Create Page</button>
                              
                            </div>
                        </div>
                        </div>
                        </form>
                </div>
            </div>
        </div>


 
                              
                  


    
			<?php   include 'cm_footer.php';?>

            <script >

function create_page(){
    
  
 var pagetitle = jQuery('#pagename').val();
 var contentbody =tinymce.activeEditor.getContent();
   console.log(pagetitle);
   console.log(contentbody);
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/egpl_new_requests.php?contentManagerRequest=createpage';
    var data = new FormData();

    data.append('pagetitle', pagetitle);
    data.append('contentbody', contentbody);
    
    
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            method: "POST",
            contentType: false,
            processData: false,
            type: 'PAGE',
            success: function(data) {
                
               console.log(data);
                    
                 swal({
                    title: "Success",
                    text: 'Page created successfully. Go to "Manage Menu" to add the page to your navigation menu.',
                    type: "success",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Ok"
                });
                jQuery("form")[0].reset();
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
}
            </script>





       
   <?}else{
       $redirect = get_site_url();
    wp_redirect( $redirect );exit;
   
   }
   ?>