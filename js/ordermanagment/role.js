   var roleassignmenttable ;
   jQuery(document).ready(function() {
       
       
       roleassignmenttable = jQuery(".assigntaskrole").DataTable({"pageLength": 100});
       var getcontainerwidth =  jQuery( "#previewheaderdiv" ).width() / 5.5 ;
       jQuery("#previewheaderdiv").css("height",getcontainerwidth);
        jQuery("#previewlogo").css("height",getcontainerwidth);  

    jQuery("#headerbanner").change(function(){
        getFilePathheaderbanner(this);
    });
    jQuery("#headerlogo").change(function(){
        getFilePathheaderlogo(this);
    });
       
   jQuery('#assignnewtask').on( 'click', function () {
          
          
          
        var alreadyexist = new Array();
          jQuery('.assignedtasks').each(function (i, selected1) {
                
                    alreadyexist.push(jQuery(selected1).attr('id'));
                    //console.log(jQuery('.assignedtasks').attr('id'));
             });
             
         // console.log(alreadyexist);
        jQuery('#addnewroleassignment :selected').each(function (i, selected) {
           var  valuereturn = jQuery.inArray(jQuery(selected).val(), alreadyexist);
           // console.log(valuereturn)
            
            
          if(valuereturn < 0){
                   
             var rowNode = roleassignmenttable.row.add([
                        '<p class="assignedtasks" id="' + jQuery(selected).val() + '">' + jQuery(selected).text() + '</p>',
                        '<i style=" cursor: pointer;margin-left: 10px;" onclick="removetask_forthisrole(this)" title="Remove this task" class="fusion-li-icon fa fa-times-circle fa-2x" style="color:#262626;"></i>'

                    ]).draw().nodes().to$().addClass("addnewtaskintorole"); 
                }         
           
           
          
        
          });
   });    

  }); 







function add_new_role_contentmanager(){
    
     var rolename =jQuery('#rolename').val();
     jQuery("body").css({'cursor':'wait'});
     var specialcharacterstatuslevelname=false;
     var url = currentsiteurl+'/';
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=addnewrole';
     var data = new FormData();
     data.append('rolename', rolename);
     
     if(/^[ A-Za-z0-9_()\-]*$/.test(rolename) == false) {
           specialcharacterstatuslevelname = true;
     }else{
         specialcharacterstatuslevelname = false;
     }
    if(specialcharacterstatuslevelname == false){
      jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                   var msg = jQuery.parseJSON(data);
                    jQuery("form")[0].reset();
                 //location.reload();
                 jQuery( "#sponsor-status" ).append( '<div class="alert wpb_content_element alert-success"><div class="messagebox_text"><p>'+msg.msg+'</p></div></div>' );
                swal({
					title: msg.title,
					text: msg.msg,
					type: msg.status,
                                        html:true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				},function() {
                                                                    location.reload();
                                                                 }
                            
            );
               
                
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
    }else{
        jQuery('body').css('cursor', 'default');
          swal({
					title: "Error",
					text: "Invalid characters used in Level name. Please remove and try again.",
					type: "error",
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
				});
        
    }
      
                                       
                                    
                                    

    
}
function delete_role_name(elem){
     
     
   
   
     var rolename =jQuery(elem).attr("id");
     var viewrolename= rolename.replace("_", " ");
      swal({
							title: "Are you sure?",
							text: 'you want to remove this level: '+viewrolename.toUpperCase(),
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
                                                             var Sname =  delete_role_name_conform(rolename);
								swal({
									title: "Deleted!",
									text: "Level deleted successfully",
									type: "success",
									confirmButtonClass: "btn-success"
								},function() {
                                                                    location.reload();
                                                                 }
                                                            );
							} else {
								swal({
									title: "Cancelled",
									text: "Resource is safe :)",
									type: "error",
									confirmButtonClass: "btn-danger"
								});
							}
						});
    
    
     
    
    
    
    
    
}
function delete_role_name_conform(namerole){
    var rolename =namerole;
     jQuery("body").css({'cursor':'wait'});
     var url = currentsiteurl+'/';
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=removerole';
     var data = new FormData();
     data.append('rolename', rolename);
      jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                   var msg = jQuery.parseJSON(data);
                    jQuery("form")[0].reset();
                 //location.reload();
                
                
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

/////////////////////////////////////////////////////my code Shehroze starts//////////////////////////////////////////////////////////

function delete_menu_name(elem){
     
     
   const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger'
  },
  buttonsStyling: false
})



   
     var menuname =jQuery(elem).attr("id");
     var viewmenuname= menuname.replace("_", " ");
      Swal.fire({
                            title: "Are you sure?",
                            text: 'you want to remove this menu item',
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Yes, delete it!",
                            cancelButtonText: "No, cancel please!",
                            confirmButtonClass:' btn btn-primary',
                            cancelButtonClass:' btn btn-danger',
                            closeOnConfirm: false,
                            closeOnCancel: false
                      }).then((result) => {

                        if (result.isConfirmed) {


                              var Sname =  delete_menu_name_conform(menuname);
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Menu item deleted successfully",
                                    type: "success",
                                    confirmButtonClass: " btn btn-success"

                                          
                                        }).then((result) => {

                                            if (result.isConfirmed) {

                                                 location.reload();
                                            }
                             }) 

                         }

                   

                      else{                     
                          
                                Swal.fire({
                                    title: "Cancelled",
                                    text: "Resource is safe :)",
                                    type: "error",
                                    confirmButtonClass: "btn btn-primary'"
                                });
                            }
                })       
    }
    
     
    
    
    
    
    

function delete_menu_name_conform(namerole){
    var menuname =namerole;
     jQuery("body").css({'cursor':'wait'});
     var url = currentsiteurl+'/';
     var urlnew = url + 'wp-content/plugins/EGPL/egpl_new_requests.php?contentManagerRequest=removemenu';
     var data = new FormData();
     data.append('menuname', menuname);
      jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                   var msg = jQuery.parseJSON(data);
                    jQuery("form")[0].reset();
                 //location.reload();
                
                
            },error: function (xhr, ajaxOptions, thrownError) {
                     Swal.fire({
                    title: "Error",
                    text: "There was an error during the requested operation. Please try again.",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Ok"
                });
      }
        });
     
}


///////////////////////////////////////////////////my code Shehroze ends//////////////////////////////////////////////////////

function update_admin_settings(){
    
    
    // var formemail = jQuery('#formemailaddress').val();
     var eventdate = jQuery('#eventdate').val();
     var data = new FormData();
     var oldheaderbannerurl = jQuery('#headerbannerurl').val();
     var getemailaddress = jQuery('#registration_notificationemails').val();
    
     
     
    if(oldheaderbannerurl ==""){
         
          var uploadedfile = jQuery('#headerbanner')[0].files[0]; 
          data.append('uploadedfile', uploadedfile);
     }
   
   
     
     jQuery("body").css({'cursor':'wait'});
     var url = currentsiteurl+'/';
     var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=adminsettings';
     
     data.append('oldheaderbannerurl', oldheaderbannerurl);
     data.append('eventdate', eventdate);
     
     if(jQuery('#applicationmoderationstatus').is(':checked')){
            data.append('applicationmoderationstatus', 'checked');
     }else{
            data.append('applicationmoderationstatus', '');
        }
     
     data.append('registration_notificationemails', getemailaddress);
   
     
      jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                 jQuery('body').css('cursor', 'default');
                 swal({
					title: "Success",
					text: "Content Manager Settings Updated",
					type: "success",
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				},function() {
                                      location.reload();
                                 }
                                        );
                
                
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
 function roleassignednewtask(){
     
      jQuery("body").css({'cursor':'wait'});
      var taskdataupdatelist = new Array();
      var removetasklist = new Array();
      var rolename = jQuery('#editrolename').val();
      jQuery('.assignedtasks').each(function (i, selected1) {
                
                    taskdataupdatelist.push(jQuery(selected1).attr('id'));
                    //console.log(jQuery('.assignedtasks').attr('id'));
      });
      jQuery('.removeitems').each(function (i, selected1) {
                
                    removetasklist.push(jQuery(selected1).attr('id'));
                    //console.log(jQuery('.assignedtasks').attr('id'));
      });
      
      
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=roleassignnewtasks';
    var data = new FormData();
    
    data.append('roleassigntaskdatalist',   JSON.stringify(taskdataupdatelist));
    data.append('removetasklist',   JSON.stringify(removetasklist));
    data.append('rolename',   rolename);
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                
                 jQuery('body').css('cursor', 'default');
                  swal({
                    title: "Updated!",
                    text: "Tasks assigned successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                },
        function(isConfirm) {
            
            location.reload();
        }
        
                        );
                
                
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
                                   
function removetask_forthisrole(e){
     
     swal({
            title: "Are you sure?",
            text: 'Click confirm to unassign this Task.',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        },function(isConfirm) {

            
           
            if (isConfirm) {
                roleassignmenttable.row( jQuery(e).parents('tr') ).draw().nodes().to$().addClass("removetaskrole");
                jQuery(e).parents('tr').children('td').children('p').removeClass('assignedtasks');
                jQuery(e).parents('tr').children('td').children('p').addClass('removeitems');
                swal({
                    title: "Unassigned!",
                    text: "Task unassigned successfully",
                    type: "success",
                    confirmButtonClass: "btn-success"
                }
                );
            } else {
                swal({
                    title: "Cancelled",
                    text: "Task is safe :)",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });    
     
     
     
 }
 
 
function editrolename(e){
   
    var rolekey = jQuery(e).attr('id');
    var oldrolename = jQuery(e).attr('name');
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=editrolekey';
    var data = new FormData();
    
    data.append('rolekey',   rolekey);
    
    
    swal({
		title: "Edit Level Name",
		text: '',
		type: 'input',
                inputValue:oldrolename,
		showCancelButton: true,
		closeOnConfirm: false,
		animation: "slide-from-top",
		inputPlaceholder: "Level Name",
	},
	function(inputValue){
		if (inputValue === false) return false;

		if (inputValue === "") {
			swal.showInputError("You need to write something!");
                        jQuery('body').css('cursor', 'default');
			return false;
		}
                data.append('rolenewname',   inputValue);
                jQuery.ajax({
                    url: urlnew,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(data) {
                        var finalresult = jQuery.parseJSON(data);
                        
                       jQuery('body').css('cursor', 'default');
                        
                        if(finalresult.msg == 'update'){
                        swal({
                                title: "Success!",
                                text: 'Edit Level Name: ' + inputValue+' changed successfully',
                                type: "success",
                                confirmButtonClass: "btn-success"
                            },
                                    function (isConfirm) {

                                        location.reload();
                                    }

                            );
                   }else{
                       swal.showInputError("A Level with that name already exists Please try another name.");
                   } 
                    }
                });
		

	});
    
    
}


//////////////////////////////////////////////my code Shehroze starts////////////////////////////////////////////////


     function add_new_menu_item(e){
       
        var menuid = jQuery(e).attr('id');
        var slug = jQuery(e).attr('slug');
        var pageid = jQuery(e).attr('pageid');
        var menuorder = jQuery(e).attr('menuorder');
       
        var oldmenuname = jQuery(e).attr('name');
        var oldmenuurl = jQuery(e).attr('url');
        var url = currentsiteurl+'/';
        var urlnew = url + 'wp-content/plugins/EGPL/egpl_new_requests.php?contentManagerRequest=addnewmenuitem';
        var urlnew2 = url + 'wp-content/plugins/EGPL/egpl_new_requests.php?contentManagerRequest=addmenuitem';
        var data = new FormData();
        var datas = new FormData();
        var finalresult = new Array();
        

        



         jQuery.ajax({
                        url: urlnew2,
                        data: datas,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function(datas) {


                             var finalresult = jQuery.parseJSON(datas);

                             jQuery('body').css('cursor', 'default');

                             var res = [];

                           res = finalresult['pageslist']; 
                            

                            var dropdown ='<select  name="getallPages" id="selectpage"  class="js-example-placeholder-multiple js-states form-control form-control" required><option style="color" value="" hidden >Select Page</option>';

                            for (var i = 0; i < res.length; i++) {
                                
                              //  console.log(res[i] );

                                if(pageid == res[i].pageId){

                                    dropdown +='<option value="'+res[i].pageId+'" selected="true">'+res[i].pagetitle+'</option>';

                                }else if(res[i].pageslug == "floor-plan" ||  res[i].pageslug == "cart" || res[i].pageslug == "my-sites" || res[i].pageslug == "my-sites-2" || res[i].pageslug == "logout" || res[i].pageslug == "change-password" || res[i].pageslug == "change-password-2"){
                    

                                }else{
                                            
                                   dropdown +='<option value="'+res[i].pageId+'" >'+res[i].pagetitle+'</option>';

                                }
                                
                               
                            }
                            dropdown +="</select>";


                        //    var toggle ='Custom Link <input type="checkbox" id= "checkbox" class="toggle-one" data-toggle="toggle" style="size: 1.5rem;">';
                           


                    //       var toggle = ' <div class="toggle-group"><label class="btn btn-primary toggle-on">On</label><label class="btn btn-default active toggle-off">Off</label><span class="toggle-handle btn btn-default"></span></div> ';

         Swal.fire({

            title: 'Add New Menu Item',
            customClass: 'custom_width',
            scrollbarPadding: false,
            confirmButtonText: 'Save',
            confirmButtonClass:' btn btn-primary',
            cancelButtonClass:' btn btn-danger',
            showCancelButton: true,
            cancelButtonText:'Cancel',
            allowOutsideClick: false,
            
            html:
            '<div style = "overflow-x: hidden !important" background: blue;>'+
            '<br>'+
            '<div class = "row" style="display:flex; justify-content:center;">'+  

            '<div  class="col-sm-6">'+
            '<p>Custom Link &nbsp;&nbsp;&nbsp;<input type="checkbox" id= "check" class="toggle-one" data-toggle="toggle" data-size="small"></p>'+
            '</div>'+  

            

            '<div class = "col-sm-6">'+
            '<p>Visible On Registration   &nbsp;&nbsp;&nbsp;<input type="checkbox" id= "checktwo" class="toggle-one" data-toggle="toggle" data-size="small"></p>'+
            '</div>'+ 

           

           '</div>'+
           
            '<div id ="mname" style = "padding-top: 20px" ><p style="float: left;">Menu Name</p>'+
            '<input style = "margin-bottom: 20px" id="title" type="text"  placeholder="Name" title ="Name"  class="form-control"><p id="name-error-div" style="margin-top: -15px;color: red !important;font-size: 12px;font-style: italic;"></p>'+
            ' <p style="float: left;">Menu URL</p><input style = "" id="link" type="text"  placeholder="Url" title ="Url" value="#" class="form-control">'+
            '</div>'+

             '<div id = "mpage" >'+
             '<label id = "lbl" ></label>'+dropdown+
             '</div><p id="page-error-div" style="margin-top: 6px; color: red !important;font-size: 12px;font-style: italic;"></p>'+

             '</div>',
             // columnClass: 'jconfirm-box-container-special',
           

       didOpen: () => {
         jQuery('.toggle-one').bootstrapToggle();   
        

           jQuery('#lin').hide();
            jQuery('#link').hide();
        
            jQuery('#check').change(function(){
                if(jQuery(this).is(":checked")){

                    jQuery('#lbl').hide();
                    jQuery('#selectpage').hide();
                    jQuery('#lin').show();
                    jQuery('#link').show();
                }
                else if(jQuery(this).is(":not(:checked)")){
                    jQuery('#lin').hide();
                    jQuery('#link').hide();
                    jQuery('#lbl').show();
                    jQuery('#selectpage').show();
                }
            });
        
              
       },

       preConfirm: function() {
           
       // return new Promise(function(e) {
       
            var inputValueName = jQuery('#title').val();
            var inputValueUrl = jQuery('#link').val();
            var selectpage = jQuery('#selectpage').val();
            var validateurl = "";
            if(jQuery('#check').is(':checked')){
                      
                      validateurl = inputValueUrl;
                      
                  }else{
                      
                      validateurl = selectpage;
                      
                  }
            
            
    
            if (inputValueName == "" ) {
    
                 // Swal.fire("All Feilds are Required!");
                 
                 jQuery('#name-error-div').empty();
                 jQuery('#page-error-div').empty();
                 jQuery('#name-error-div').append('Menu name is required field.');
                 return false;
                 
              }else if(validateurl == ""){
                  
                  
                  jQuery('#name-error-div').empty();
                  jQuery('#page-error-div').empty();
                  jQuery('#page-error-div').append('Menu Page/Url is required field.');
                  return false;
                  
              }
    
            
             
            
            
      //});
   
       
    }, }).then((result) => {

          if (result.isConfirmed) {
          
           var inputValueName = jQuery('#title').val();
           var inputValueUrl = jQuery('#link').val();
           var selectpage = jQuery('#selectpage').val();

        //   if (inputValueName == "" || inputValueUrl == "" || selectpage == "" ) {

        //         Swal.fire("All Feilds are Required!");
       
        //         return false;
        //     }

            // if (inputValueName == "" ) {


            //     jQuery('#mname').append('<p style="color:red !important">Menu Name is Requied</p>');
       
            //     return false;
            // }

            // if (inputValueUrl == "" || selectpage == "") {

            //     jQuery('#mpage').append('<p style="color:red !important">Menu Page/URL is Requied</p>');
       
            //     return false;
            // }
        

           if(jQuery('#check').is(':checked')){

            var customlinkurl = jQuery('#link').val();
            var type = "customlink";
    }else{
            var pageiddropdown = jQuery("#selectpage").val();
            var type = "page";
            
            
          
            
            
    }

         if(jQuery('#checktwo').is(':checked')){
            var public_visibility = "public";
            console.log(public_visibility);
    }

    else{

        var public_visibility = "private";
        console.log(public_visibility);
    }
           // console.log(inputValueName);
           // console.log(inputValueUrl);

                    data.append('menunewname',  inputValueName);
                    data.append('menunewurl',   inputValueUrl);
                    data.append('type', type);
                    data.append('customlinkurl',  customlinkurl);
                    data.append('pageiddropdown',pageiddropdown);
                    data.append('public_visibility',public_visibility);

                    jQuery.ajax({
                        url: urlnew,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function(data) {
                            
                            var finalresult = jQuery.parseJSON(data);
                            
                           jQuery('body').css('cursor', 'default');
                            
                          if(finalresult.msg == 'update'){
                            Swal.fire({
                                title: "Success!",
                                text: 'Menu Item Added',
                                type: "success",
                                confirmButtontext:'OK',
                                confirmButtonClass: 'btn btn-success',
                                
                                                   
                            }).then((result) => {
                             if (result.isConfirmed) {
                                                
                                        location.reload();

                                           
                                        }
                                        })

                                
                       }else{
                           Swal.fire("Already exists Please try another name.");
                       } 
                      }
                        
                    });
          }
    })
       
    }
                
        }); 
}       

     
       
    




//////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////

   function editmenuname(e){

        var main_menu_id = jQuery(e).attr('main_menu_id');
        var menuid = jQuery(e).attr('id');

        var slug = jQuery(e).attr('slug');
        var menuid = jQuery(e).attr('id');
        var pageid = jQuery(e).attr('pageid');
        var menuorder = jQuery(e).attr('menuorder');
       
        var oldmenuname = jQuery(e).attr('name');
        var oldmenuurl = jQuery(e).attr('url');
        var url = currentsiteurl+'/';
        var urlnew = url + 'wp-content/plugins/EGPL/egpl_new_requests.php?contentManagerRequest=editmenu';
        var urlnew2 = url + 'wp-content/plugins/EGPL/egpl_new_requests.php?contentManagerRequest=addmenuitem';
        
        var parentid = jQuery("#"+menuid).parent().attr('id');
       
       
       var data = new FormData();
        var datas = new FormData();
        var finalresult = new Array();
        datas.append('menuitemid', menuid);
        data.append('main_menu_id', main_menu_id);
        data.append('menuid', menuid);
        data.append('slug', slug);
        data.append('pageid', pageid);
        data.append('menuorder', menuorder);
        
        jQuery.ajax({
                        url: urlnew2,
                        data: datas,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function(datas) {


                             var finalresult = jQuery.parseJSON(datas);

                             // console.log(finalresult);

                             jQuery('body').css('cursor', 'default');

                             var res = [];

                             res = finalresult['pageslist']; 
                            

                            var dropdown ='';

                            for (var i = 0; i < res.length; i++) {
                                
                              //  console.log(res[i] );

                                if(pageid == res[i].pageId){

                                    dropdown +='<option value="'+res[i].pageId+'" selected="true">'+res[i].pagetitle+'</option>';

                                }else if(res[i].pageslug == "floor-plan" ||  res[i].pageslug == "cart" || res[i].pageslug == "my-sites" || res[i].pageslug == "my-sites-2" || res[i].pageslug == "logout" || res[i].pageslug == "change-password" || res[i].pageslug == "change-password-2"){
                    

                                }else{
                                            
                                   dropdown +='<option value="'+res[i].pageId+'" >'+res[i].pagetitle+'</option>';

                                }
                                
                               
                            }
                           
         
                      
         var popuphtml = ""; 
         popuphtml += '<div style = "overflow-x: hidden !important"><br><div class = "row" style="display:flex; justify-content:center;">';
            
         if(finalresult['page_type'] == "customlink" || slug == 'account' || slug == 'account-2' || slug == 'add-ons' || slug == 'add-ons-2' || slug =='order-history' || slug =='order-history-2' ){
              
              popuphtml += '<div  class="col-sm-4"><p>Custom Link &nbsp;&nbsp;&nbsp;<input type="checkbox" id= "check" class="toggle-one" data-toggle="toggle" data-size="small" checked></p></div>';
              
          }else{
              
              
              popuphtml += '<div  class="col-sm-4"><p>Custom Link &nbsp;&nbsp;&nbsp;<input type="checkbox" id= "check" class="toggle-one" data-toggle="toggle" data-size="small"></p></div>';
              
          } 
          
          
          if(finalresult['page_visibility'] == "public"){
              
              popuphtml += '<div  class="col-sm-4"><p>Visible On Registration &nbsp;&nbsp;&nbsp;<input type="checkbox" id= "checktwo" class="toggle-one" data-toggle="toggle" data-size="small" checked></p></div>';
              
              
          }else{
              
              popuphtml += '<div  class="col-sm-4"><p>Visible On Registration &nbsp;&nbsp;&nbsp;<input type="checkbox" id= "checktwo" class="toggle-one" data-toggle="toggle" data-size="small" ></p></div>';
              
          }



          if(slug == 'add-ons' || slug == 'add-ons-2' || slug == 'cart' || slug =='order-history' || slug =='order-history-2' || slug == 'floor-plan'){

    
            if(finalresult['addon_enabled'] == "enabled" || finalresult['addon_enabled'] == ""){

                popuphtml += '<div class="col-sm-4"><p>Enable &nbsp;&nbsp;&nbsp;<input type="checkbox" id= "addons-check" class="toggle-one" data-toggle="toggle" data-size="small" checked></p></div>';
             }

             else if(finalresult['addon_enabled'] == "disabled"){

                popuphtml += '<div class="col-sm-4"><p>Enable &nbsp;&nbsp;&nbsp;<input type="checkbox" id= "addons-check" class="toggle-one" data-toggle="toggle" data-size="small"></p></div>';
             }

               
          }
 
          popuphtml +='</div><div style = "padding-top: 20px" ><p style="float: left;">Menu Name</p><input style = "margin-bottom: 20px" id="title" type="text"  placeholder="Name" title ="Name" value="'+oldmenuname+'"  class="form-control"><p id="name-error-div" style="margin-top: -15px;color: red !important;font-size: 12px;font-style: italic;"></p>';

          
          if(finalresult['page_type'] == "customlink"){
              
              
              popuphtml +='<p id="menu-url" style="float: left;">Menu URL</p> <input style = "" id="link" type="text"  placeholder="Url" title ="Url" value="'+oldmenuurl+'"   class="form-control"><select  style = "display:none;" name="getallPages" id="selectpage"  class="js-example-placeholder-multiple js-states form-control form-control"><option value=""></option>'+dropdown+'</select></div><p id="page-error-div" style="margin-top: 6px; color: red !important;font-size: 12px;font-style: italic;"></p></div>';
              
          }else{
              
              popuphtml +='<p id="menu-page" style="float: left;">Menu Page</p><input style = "display:none;" id="link" type="text"  placeholder="Url" title ="Url" value="'+oldmenuurl+'"   class="form-control"><select  name="getallPages" id="selectpage"  class="js-example-placeholder-multiple js-states form-control form-control page"><option value="" hidden></option>'+dropdown+'</select></div><p id="page-error-div" style="margin-top: 6px; color: red !important;font-size: 12px;font-style: italic;"></p></div>';
              
          }

        
        
         

         Swal.fire({
            title: 'Edit Menu Item',
            scrollbarPadding: false,
            confirmButtonText: 'Save',
            confirmButtonClass:' btn btn-primary',
            cancelButtonClass:' btn btn-danger',
            showCancelButton: true,
            customClass: 'custom_width',
            cancelButtonText:'Cancel',
            allowOutsideClick: false,

            html:popuphtml,
            
            // columnClass: 'jconfirm-box-container-special',

        didOpen: () => {
         jQuery('.toggle-one').bootstrapToggle();   
         
        if (slug == 'add-ons' || slug == 'add-ons-2' || slug == 'account' || slug == 'account-2' || slug == 'cart' || slug == 'floor-plan' || slug == 'my-portals' || slug == 'my-sites' || slug == 'order-history' || slug == 'order-history-2' ||  slug == 'change-password' || slug == 'change-password-2' || slug == 'logout') {

            
            jQuery('#check').prop('disabled', true);
            
            jQuery('#link').hide();
            jQuery('#menu-url').hide();
            jQuery('#menu-page').hide();
            jQuery('.page').attr("style", "display: none !important");
        }


        
            jQuery('#check').change(function(){
                if(jQuery(this).is(":checked")){

                    jQuery('#lbl').hide();
                    jQuery('#selectpage').hide();
                    jQuery('#lin').show();
                    jQuery('#link').show();
                }
                else if(jQuery(this).is(":not(:checked)")){
                    jQuery('#lin').hide();
                    jQuery('#link').hide();
                    jQuery('#lbl').show();
                    jQuery('#selectpage').show();
                }
            });
        
              
       },    
       
            preConfirm: function() {
           
        // return new Promise(function(e) {
        
             var inputValueName = jQuery('#title').val();
             var inputValueUrl = jQuery('#link').val();
             var selectpage = jQuery('#selectpage').val();
             var validateurl = "";
             if(jQuery('#check').is(':checked')){
                       
                       validateurl = inputValueUrl;
                       
                   }else{
                       
                       validateurl = selectpage;
                       
                   }
             
             
     
             if (inputValueName == "" ) {
     
                  // Swal.fire("All Feilds are Required!");
                  
                  jQuery('#name-error-div').empty();
                  jQuery('#page-error-div').empty();
                  jQuery('#name-error-div').append('Menu name is required field.');
                  return false;
                  
               }else if(validateurl == ""){
                   
                   
                    jQuery('#name-error-div').empty();
                   jQuery('#page-error-div').empty();
                   jQuery('#page-error-div').append('Menu Page/Url is required field.');
                   return false;
                   
               }
     
             
              
             
             
       //});
    
        
     },
       
        }).then((result) => {

          if (result.isConfirmed) {
        

           var inputValueName = jQuery('#title').val();
           var inputValueUrl = jQuery('#link').val();
           var selectpage = jQuery('#selectpage').val();


           if(jQuery('#check').is(':checked')){
            jQuery('#check').val('checked');
            var customlinkurl = jQuery('#link').val();
            var type = "customlink";
    }else{
            var pageiddropdown = jQuery("#selectpage").val();
            var type = "page";
            console.log(pageiddropdown);
    }
         if(jQuery('#checktwo').is(':checked')){
            jQuery('#checktwo').val('checked');
            var public_visibility = "public";
            console.log(public_visibility);
    }

    else{

        var public_visibility = "private";
        console.log(public_visibility);
    }

    
    if(jQuery('#addons-check').is(':checked')){
            jQuery('#addons-check').val('checked');
            var addons_enable = "enabled";
            console.log(addons_enable);
    }

    else{
        
        var addons_enable = "disabled";
        console.log(addons_enable);

        if(slug == 'add-ons' || slug == 'add-ons-2' || slug == 'cart' || slug =='order-history' || slug == 'floor-plan'){
            
             var addons_enable = "disabled";
        }else{
            
             var addons_enable = "";
        }
        
    }
           // console.log(inputValueName);
           // console.log(inputValueUrl);

         
                    data.append('menunewname',  inputValueName);
                    data.append('menunewurl',   inputValueUrl);
                    data.append('type', type);
                    data.append('customlinkurl',  customlinkurl);
                    data.append('pageiddropdown',pageiddropdown);
                    data.append('public_visibility',public_visibility);
                    data.append('parentid',parentid);
                    data.append('addons_enable',addons_enable);
                    
                    jQuery.ajax({
                        url: urlnew,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function(data) {
                            
                            var finalresult = jQuery.parseJSON(data);
        
                           jQuery('body').css('cursor', 'default');
                            
                            if(finalresult.msg == 'update'){
                            Swal.fire({
                                title: "Success!",
                                text: 'Changed Successfully',
                                type: "success",
                                confirmButtontext:'OK',
                                confirmButtonClass: 'btn btn-success',
                                
                                                   
                            }).then((result) => {
                             if (result.isConfirmed) {
                                                
                                        location.reload();

                                           
                                        }
                                        })

                                
                       }else{

                           Swal.fire("Already exists Please try another name.");
                       } 
                      }
                        
                    });
       }
    })
                
                             

        } });

     
       
    }



/////////////////////////////////////////////////my code Shehroze ends/////////////////////////////////////////////




////////////////////////////////////////////////////my code Shehroze starts///////////////////////////////////////////////////////////////


function set_menu_item_order(e){

    jQuery("body").css({'cursor':'wait'});
    jQuery(".child-list >li").removeClass("parent");
    var data = new FormData();
   
     var AllDataArray = [];

    var menuorder;
    
    jQuery('.parent-list').find('.parent').each(function(index, value){


       // console.log(index);

        var menuItemid = jQuery(this).attr('id');

      //  console.log(menuItemid);

        var title = jQuery(this).attr('name');
       console.log(title);

        var pageid = jQuery(this).attr('pageid');
        //console.log(pageid);

        var url = jQuery(this).attr('url');
       //console.log(url);

        var itemobject = jQuery(this).attr('itemobject');
      // console.log(itemobject);

      var menuitemparent = "";//jQuery(this).attr('menuitemparent');

      // console.log(menuitemparent);

       var menuorder = index;

    // console.log(menuorder, value);

     //console.log(dataArray);

     if (jQuery(".childeclass-"+menuItemid)[0]) {
        
         var AllDataArray2 = [];

      jQuery(".childeclass-"+menuItemid+" > li").each(function(index, value) {

        
          // var type = "child";

      ///   console.log(value);
   
        var cmenuItemid = jQuery(this).attr('id');

      //  console.log(cmenuItemid);

        var ctitle = jQuery(this).attr('name');
       console.log(ctitle+'______child');

        var cpageid = jQuery(this).attr('pageid');
        // console.log(cpageid);

        var curl = jQuery(this).attr('url');
       // console.log(curl);

        var citemobject = jQuery(this).attr('itemobject');

        // console.log(citemobject);

      var cmenuitemparent = menuItemid;

       // console.log(cmenuitemparent);

      
       var cmenuorder = index;

    //console.log('I');

     // console.log(cmenuorder);
     

     var dataArray2 = {ordernumber:cmenuorder,menuitemid:cmenuItemid,pagetitle:ctitle,pageID:cpageid,itemurl:curl,itemObject:citemobject,menuitemparent:cmenuitemparent};
     AllDataArray2.push(dataArray2);  

     console.log(dataArray2);
       
      }); 

    }

     if(AllDataArray2 == ""){
        
        var dataArray = {ordernumber:menuorder,menuitemid:menuItemid,pagetitle:title,pageID:pageid,itemurl:url,itemObject:itemobject,menuitemparent:menuitemparent,childearray:JSON.stringify(AllDataArray2)};
     
     }else{
         
        var dataArray = {ordernumber:menuorder,menuitemid:menuItemid,pagetitle:title,pageID:pageid,itemurl:"#",itemObject:"custom",menuitemparent:menuitemparent,childearray:JSON.stringify(AllDataArray2)};
     
         
     }
     AllDataArray.push(dataArray);


    });
   
   //console.log(AllDataArray);

    data.append('orderlist',   JSON.stringify(AllDataArray));
  
    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/egpl_new_requests.php?contentManagerRequest=setmenuitemorder';
    jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
              jQuery("body").css({'cursor':'default'});
              
                                    Swal.fire({
                                  title: "Success!",
                                text: 'Menu Order saved',
                                type: "success",
                                confirmButtontext:'OK',
                                confirmButtonClass: 'btn btn-success',
                                   }).then((result) => {
                             if (result.isConfirmed) {
                                                
                                        location.reload();

                                           
                                        }
                                        })
              
            },error: function (xhr, ajaxOptions, thrownError) {
                     Swal.fire({
                        title: "Error",
            text: "There was an error during the requested operation. Please try again.",
            type: "error",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ok"
                     });
            }
        });
    
}





//////////////////////////////////////////////////my code Shehroze ends///////////////////////////////////////////////////////////////

function createroleclone(e){
   
    var rolekey = jQuery(e).attr('id');
    var oldrolename = jQuery(e).attr('name');
    
   var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/egpl.php?contentManagerRequest=createlevelclone';
    var data = new FormData();
    
    
    data.append('clonerolekey',   rolekey);
    
    swal({
		title: "Create Clone Level",
		text: '',
		type: 'input',
                inputValue:'Copy of '+oldrolename,
		showCancelButton: true,
		closeOnConfirm: false,
		animation: "slide-from-top",
		inputPlaceholder: "Level Name",
	},
	function(inputValue){
		if (inputValue === false) return false;

		if (inputValue === "") {
			swal.showInputError("You need to write something!");
			return false;
		}
                data.append('rolename',   jQuery.trim(inputValue));
                jQuery.ajax({
                    url: urlnew,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function(data) {
                        
                        jQuery('body').css('cursor', 'default');
                        var finalresult = jQuery.parseJSON(data);
                        if(finalresult.msg == 'New Level created' ){
                            
                            
                            
                            swal({
                                title: "Success!",
                                text: 'New Level: ' + inputValue+' created successfully',
                                type: "success",
                                confirmButtonClass: "btn-success"
                            },
                                    function (isConfirm) {

                                        location.reload();
                                    }

                            );
                           
                                
                        }else{
                        
                            swal.showInputError(finalresult.msg);
                        }
                        
                        
                        //location.reload();
                    }
                });
		

	});
    
    
}


function removeheaderimage(){
    
    jQuery(".privewdiv").hide();
    jQuery('#imageviewver').remove();
    jQuery('#headerbanner').show();
    jQuery('#headerbannerurl').val("");
    jQuery('.removebutton').empty("");
    jQuery('#headerlogourl').val("");
    jQuery("#previewheaderdiv").css("background-image","");
    jQuery("#previewlogo").attr("src", '');
    
}

function getFilePathheaderbanner(input){
    
    if (input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                 jQuery(".privewdiv").show();
                 jQuery('#headerbannerurl').val("");
                 var getcontainerwidth =  jQuery( "#previewheaderdiv" ).width() / 5.5 ;
                 console.log(getcontainerwidth);
                 jQuery("#previewheaderdiv").css("height",getcontainerwidth);
                 jQuery("#previewheaderdiv").css("background-image",'url(' +  e.target.result +' )');
                 //
//jQuery("#previewheaderdiv").css("background-repeat","no-repeat");
                 //jQuery("#previewheaderdiv").css("background-size","contain");
                 
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    
}
function getFilePathheaderlogo(input){
    
    
     if (input.files[0]) {
            var reader = new FileReader();
            
             
            reader.onload = function (e) {
                jQuery(".privewdiv").show();
                jQuery('#headerlogourl').val("");
                var getcontainerwidth =  jQuery( "#previewheaderdiv" ).width() / 5.5 ;
                jQuery("#previewlogo").attr("src", e.target.result);
                jQuery("#previewlogo").css("height",getcontainerwidth);
                console.log(input.files[0].width + " " + input.files[0].height);
         
            }
            
            
            reader.readAsDataURL(input.files[0]);
        }
     
     }
     
   