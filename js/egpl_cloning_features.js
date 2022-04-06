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

            type: "warning",
            html:true,
            icon: "warning",
            confirmButtonClass: "btn-success",
            confirmButtonText: "Close"
        });
       


    }else if(!jQuery("#termscondition").prop("checked")){

        swal.fire({
            title: "Missing !",
            text: 'Please clicked the acknowlegded checkbox.',
            type: "warning",
            html:true,
            icon: "warning",
            confirmButtonClass: "btn-success",
            confirmButtonText: "Close"
        });
        

    }else if(jQuery("#termscondition").prop("checked") && cloneportalname!=""){


        
          Swal.fire({
            title: 'Are you sure?',
            text: "This action will replace your settings with the selected site.",
            type: "warning",
            showDenyButton: true,
            showCancelButton: true,
            icon: "warning",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Yes',
            denyButtonText: `No`,
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
   
    if(jQuery("#eventsettings").prop("checked")){

        cloningfeatureslist['eventsettings'] = 'checked';
        if(jQuery("#eventsettingsstatus").prop("checked")){

            cloningfeatureslist['eventsettingsstatus'] = 'checked';
        }
        
       

    } if(jQuery("#reports").prop("checked")){

       
       
        cloningfeatureslist['reports'] = 'checked';
       
        if(jQuery("#reportsstatus").prop("checked")){

            cloningfeatureslist['reportsstatus'] = 'checked';
        }

    } if(jQuery("#menupages").prop("checked")){

       
       
        cloningfeatureslist['menupages'] = 'checked';
       
        if(jQuery("#menupagesstatus").prop("checked")){

            cloningfeatureslist['menupagesstatus'] = 'checked';
        }

    } if(jQuery("#users").prop("checked")){

        
      
        cloningfeatureslist['users'] = 'checked';
        
        if(jQuery("#usersstatus").prop("checked")){

            cloningfeatureslist['usersstatus'] = 'checked';
        }

    } if(jQuery("#levels").prop("checked")){

        
        
        cloningfeatureslist['levels'] = 'checked';
        
        if(jQuery("#levelsstatus").prop("checked")){

            cloningfeatureslist['levelsstatus'] = 'checked';
        }

    }if(jQuery("#tasks").prop("checked")){

        cloningfeatureslist['tasks'] = 'checked';
       
        if(jQuery("#tasksstatus").prop("checked")){

            cloningfeatureslist['tasksstatus'] = 'checked';
        }

    } if(jQuery("#resources").prop("checked")){

        
       
        cloningfeatureslist['resources'] = 'checked';
       
        if(jQuery("#resourcesstatus").prop("checked")){

            cloningfeatureslist['resourcesstatus'] = 'checked';
        }

    } if(jQuery("#Shop").prop("checked")){

    
        
        cloningfeatureslist['Shop'] = 'checked';
       
        if(jQuery("#Shopstatus").prop("checked")){

            cloningfeatureslist['Shopstatus'] = 'checked';
        }

    } if(jQuery("#florrplan").prop("checked")){

        
        cloningfeatureslist['florrplan'] = 'checked';
        
        if(jQuery("#florrplanstatus").prop("checked")){

            cloningfeatureslist['florrplanstatus'] = 'checked';
        }

    }
    if(jQuery("#userfields").prop("checked")){

        
        cloningfeatureslist['userfields'] = 'checked';
        
        if(jQuery("#userfieldsstatus").prop("checked")){

            cloningfeatureslist['userfieldsstatus'] = 'checked';
        }

    }
    
    
    cloningfeatureslist['clonesiteid'] = cloneportalname;
    data.append('cloningfeatureslist',JSON.stringify(cloningfeatureslist));
    

    let timerInterval
    Swal.fire({
    title: 'Auto close alert!',
    html: 'I will close in <b></b> milliseconds.',
    timer: 2000,
    timerProgressBar: true,
    didOpen: () => {
        Swal.showLoading()
        const b = Swal.getHtmlContainer().querySelector('b')
        timerInterval = setInterval(() => {
        b.textContent = Swal.getTimerLeft()
        }, 100)
    },
    willClose: () => {
        clearInterval(timerInterval)



    


    let timerInterval
    Swal.fire({
    title: 'Data Validation !',
    html: '<div class="popupcontent"><p>Please wait data is validating...</p></div>',
    timerProgressBar: true,
    icon: 'info',
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
                var appendmessage = "";
                jQuery.each( message, function( i, item ) {

                    if(item.msg !="success"){
                       
                        appendmessage += '<p style="font-size:15px;color:red;">'+item.msg+'</p>';
                    }

                });
               
                
                if(appendmessage !=""){

                    jQuery(".popupcontent").empty();
                    jQuery(".popupcontent").append(appendmessage);
                    Swal.hideLoading();
                   

                }else{




                }
                
                
                
            } });

    },
    willClose: () => {
        //clearInterval(timerInterval)
    }
    }).then((result) => {
    /* Read more about handling dismissals below */
    if (result.dismiss === Swal.DismissReason.timer) {
        console.log('I was closed by the timer')
    }
    });

   
    
   
      

   /* jQuery.ajax({
        url: urlnew,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            
            console.log(cloningfeatureslist);
            
            
            
        } });*/
            

}


