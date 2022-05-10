jQuery( document ).ready(function() {


    jQuery('.eg-toggle-2').bootstrapToggle();

  
});


jQuery(".eg-toggle-2").change(function(){

    var divid = jQuery(this).attr("id"); 
    console.log(jQuery(this).prop("checked"));
    if(jQuery(this).prop("checked")){

        jQuery("."+divid).addClass("eg-boxed-3");
        jQuery("."+divid).addClass("eg-optional");
        jQuery("."+divid).removeClass("eg-boxed-2");
        
        jQuery("."+divid).removeClass("eg-optional-2");

    }else{

        jQuery("."+divid).addClass("eg-boxed-2");
        jQuery("."+divid).addClass("eg-optional-2");
        jQuery("."+divid).removeClass("eg-boxed-3");

        
        jQuery("."+divid).removeClass("eg-optional");

    }
    


});

function submitcloningfeature(){

    var cloneportalname = jQuery("#usersportals option:selected").val();
    var data = new FormData();


    if(cloneportalname == ""){

       swal.fire({
            title: "Missing !",
            text: 'Please select the event name from the dropdown.',
            icon: "warning",
           
            confirmButtonClass: "btn-success",
            confirmButtonText: "Close"
        });
       


    }else if(!jQuery("#termscondition").prop("checked")){

        swal.fire({
            title: "Missing !",
            text: 'Please clicked the acknowlegded checkbox.',
            icon: "warning",
           
            confirmButtonClass: "btn-success",
            confirmButtonText: "Close"
        });
        

    }else if(jQuery("#termscondition").prop("checked") && cloneportalname!=""){


        
          Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone and is only advised before going live with your portal. Clicking "Confirm" will add and/or completely override data and configurations in this current portal based on your selections.',
            icon: "warning",
            confirmButtonColor: '#86cceb',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: `Cancel`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                console.log("Qasimriaz");
                cloningfeatureconfrim();
            } else if (result.isDenied) {
              Swal.fire('Changes are not saved', '', 'info')
            }
          })




    }

    

}


function cloningfeatureconfrim(){

    var cloneportalname = jQuery("#usersportals option:selected").val();
    var data = new FormData();
    var cloningfeatureslist = {};

    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/cloningfeature.php?contentManagerRequest=cloningfeature';
    var datavalidateurl = url + 'wp-content/plugins/EGPL/cloningfeature.php?contentManagerRequest=datavalidateurl';

    console.log(urlnew);
   
    if(jQuery("#eventsettings2").prop("checked") || jQuery("#eventsettings3").prop("checked")){

        cloningfeatureslist['eventsettings'] = 'checked';
       

    } if(jQuery("#reports2").prop("checked") || jQuery("#reports3").prop("checked")){

       
       cloningfeatureslist['reports'] = 'checked';


    } if(jQuery("#menupages2").prop("checked") || jQuery("#menupages3").prop("checked")){

       
       
        cloningfeatureslist['menupages'] = 'checked';
       
       

    } if(jQuery("#users2").prop("checked") || jQuery("#users3").prop("checked")){

        
      
        cloningfeatureslist['users'] = 'checked';
        
        if(jQuery("#users2").prop("checked")){

            cloningfeatureslist['users'] = 'checked-add';
        }

    } if(jQuery("#levels2").prop("checked") || jQuery("#levels3").prop("checked")){

        
        
        cloningfeatureslist['levels'] = 'checked';
        
        if(jQuery("#levels2").prop("checked")){

            cloningfeatureslist['levels'] = 'checked-add';
        }

    }if(jQuery("#tasks2").prop("checked") || jQuery("#tasks3").prop("checked")){

        cloningfeatureslist['tasks'] = 'checked';
       
        

    } if(jQuery("#resources2").prop("checked") || jQuery("#resources3").prop("checked")){

        
       
        cloningfeatureslist['resources'] = 'checked';
       
       

    } if(jQuery("#Shop2").prop("checked") || jQuery("#Shop3").prop("checked")){

    
        
        cloningfeatureslist['Shop'] = 'checked';
       

    } if(jQuery("#florrplan2").prop("checked") || jQuery("#florrplan3").prop("checked")){

        
        cloningfeatureslist['florrplan'] = 'checked';
        
       

    }
    if(jQuery("#userfields2").prop("checked") || jQuery("#userfields3").prop("checked")){

        
        cloningfeatureslist['userfields'] = 'checked';
        
      

    }
    
    
    cloningfeatureslist['clonesiteid'] = cloneportalname;
    data.append('cloningfeatureslist',JSON.stringify(cloningfeatureslist));
    


    console.log(data);


    let timerInterval
    Swal.fire({
    title: 'Data Validation in Progress',
    html: '<div class="popupcontent"><p>Please wait while your choices are validated</p></div>',
    timerProgressBar: true,
    icon: 'info',
    showConfirmButton: true,
    confirmButtonText: `Execute`,
    showCancelButton: true,
    cancelButtonText: `Close`,
    confirmButtonColor: '#86cceb',
    allowOutsideClick: false,
    didOpen: () => {
        Swal.showLoading()
        const b = Swal.getHtmlContainer().querySelector('p');
        

        jQuery.ajax({
            url: datavalidateurl,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                
                var message = jQuery.parseJSON(data);
                var appendmessage = "<ul class='warningmessage'>";
                var softwarning  =  "";
                jQuery.each( message, function( i, item ) {

                  
                    if(i == 'Shop'){

                        if(item.msg !== "success"){

                        jQuery.each( item.msg, function( j, object ) {

                            console.log(j);
                            console.log(object); 

                            if(j == "level"){

                            
                                if(object == "missinglevels"){
                                    softwarning = "off";
                                    appendmessage += '<li><p style="font-size:15px;color:red;">Shop products have dependencies on Levels which are not part of the current selection.</p></li>';
                                   
                                }
                           
                            }else if(j == 'users'){

                           

                                if(object == "missingusers"){
                                    softwarning = "off";
                                    appendmessage += '<li><p style="font-size:15px;color:red;">Shop products have dependencies on Users which are not part of the current selection.</p></li>';
                                    
                                }
    
                           

                            }else if( j == 'tasks'){

                           

                                if(object == "missingtasks"){
                                    softwarning = "off";
                                    appendmessage += '<li><p style="font-size:15px;color:red;">Shop products have dependencies on Tasks which are not part of the current selection.</p></li>';
                                   
                                }
    
                           

                            }else if( j == 'floorplan'){

                           

                                if(object == "missingbooths"){
    
                                    appendmessage += '<li><p style="font-size:15px;color:red;">Shop products have dependencies on Floor Plan which are not part of the current selection.</p></li>';
                                   
                                }
    
                           

                            }
                        });
                        
                      }
                      

                    }else if(i == 'tasks'){


                        console.log(item.msg);

                        if(item.msg !== "success"){


                            console.log(item.msg);
                              
                            

                            jQuery.each( item.msg, function( j, object ) {

                                console.log(j);   
                                console.log(object); 

                            if(j == "level"){

                            
                                if(object == "missinglevels"){
                                    softwarning = "off";
                                    appendmessage += '<li><p style="font-size:15px;color:red;">Tasks have dependencies on Levels which are not part of the current selection.</p></li>';
                                   
                                }
                           
                            }else if(j == 'users'){

                           

                                if(object == "missingusers"){
                                    softwarning = "off";
                                    appendmessage += '<li><p style="font-size:15px;color:red;">Tasks have dependencies on Users which are not part of the current selection.</p></li>';
                                    
                                }
    
                           

                            }



                            });
                        }

                    }else if(i == 'reports'){

                        if(item.msg !="success"){
                          
                            appendmessage += '<li><p style="font-size:15px;color:red;">'+item.msg+'</p></li>';
    
                        }

                    }else{

                        if(item.msg !="success"){
                            softwarning = "off";
                            appendmessage += '<li><p style="font-size:15px;color:red;">'+item.msg+'</p></li>';
    
                        }


                    }

                    

                });
               
                console.log(appendmessage);
                console.log(softwarning);
                if(appendmessage !=""){

                    appendmessage += '</ul>';

                    jQuery(".popupcontent").empty();
                    jQuery(".popupcontent").append(appendmessage);

                    if(softwarning == "off"){

                        

                    jQuery(".popupcontent").append('<p>Please change your selections and try again.</p>');
                    Swal.hideLoading();
                    jQuery(".swal2-confirm").hide();
                   

                    }else{

                        jQuery(".popupcontent").append('<p>Are you want to Execute without dependencies ?</p>');
                        Swal.hideLoading();
                         
                    }
                    
                   
                   
                   
                   

                }else{

                    jQuery(".popupcontent").empty();
                    jQuery(".popupcontent").append('<p>Please wait while the selected objects are cloned.</p>');
                    var responce = cloningfeaturesconfrim(cloningfeatureslist);
                    console.log(responce);

                }
                
                
                
            } });

    },
    willClose: () => {
        //clearInterval(timerInterval)
    }
    }).then((result) => {
    /* Read more about handling dismissals below */

    if(result.value){
            Swal.fire({
                title: 'Executing Clone Operation',
                html: '<div class="popupcontent"><p>Please wait while the selected objects are cloned.</p></div>',
                timerProgressBar: true,
                confirmButtonColor: '#86cceb',
                //allowOutsideClick: false,
                icon: 'info',
                didOpen: () => {

                    Swal.showLoading();
                    cloningfeaturesconfrim(cloningfeatureslist);
                
                }
                
            

            });
        }
    
    });

   
    
   
      

  
            

}
function cloningfeaturesconfrim(dataobject){


    var data = new FormData();
    

    var url = currentsiteurl+'/';
    var urlnew = url + 'wp-content/plugins/EGPL/cloningfeature.php?contentManagerRequest=cloningfeature';
    data.append('cloningfeatureslist',JSON.stringify(dataobject));

      jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            
            console.log(data);


            Swal.fire({
                title: 'Completed!',
                html: 'Selected objects have been cloned successfully.',
                icon: 'success',
                confirmButton: "btn-success",
                confirmButtonText: "Close",
                confirmButtonColor: '#86cceb',
               
                }).then((result) => {

                    location.reload();


                });
           
            
            
        } });

        
}
