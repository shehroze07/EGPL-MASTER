function selfisignupadd_new_sponsor(){
    
  var getrecaptcha  = jQuery('textarea[id="g-recaptcha-response"]').val();
  if(getrecaptcha.length > 0){
  var url = currentsiteurl + "/";
  var email =  jQuery("#Semail").val();
  
  
  
  var profilepic = ""; 
  var data = new FormData();
  var errorURLValidation = false;
  data.append('getrecaptcha-responce', getrecaptcha);
  var sponsorlevel = 'subscriber';
  var urlnew = url + 'wp-content/plugins/EGPL/userreport.php?contentManagerRequest=selfsignadd_new_sponsor_metafields';
 
  jQuery("body").css("cursor", "progress");
  
  jQuery('.speiclurlfield').each(function(){
           
            var metaupdate = jQuery(this).val();
            var pattern = /(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/;
            if(metaupdate !=""){
            if(pattern.test(metaupdate)  == true){
               
                 var checkstatus  = metaupdate.includes("http");
                 var checkstatuswww  = metaupdate.includes("www");
                 if(checkstatus == false && checkstatuswww == false){
                     
                     
                     metaupdate = "https://www."+metaupdate;
                     
                 }else if(checkstatus == false && checkstatuswww == true){
                     
                     metaupdate = "https://"+metaupdate;
                     
                 }
                  
                data.append(jQuery(this).attr( "name" ), metaupdate);
            }else{
                
                errorURLValidation = true;
                
            }}else{
            
            
            data.append(jQuery(this).attr( "name" ), metaupdate);
            
            }
            
           
       });
       
  
  
  if(errorURLValidation == false){
  if(email !=""  ){
      
       data.append('username', email);
       data.append('email', email);
       data.append('profilepic', profilepic);
       data.append('sponsorlevel', sponsorlevel);
       
       jQuery('.mymetakey').each(function(){
           
            data.append(jQuery(this).attr( "name" ), jQuery(this).val());
       });
       
       
       
       jQuery('input[name="customefiels[]"]').each(function() {
      var fieldID = jQuery(this).attr("id");
      if(jQuery(this)[0].files[0]){
         data.append(fieldID, jQuery(this)[0].files[0]);
      }else{
          
           data.append(fieldID, "");
      }
      
    });
        jQuery('.mycustomedropdown').each(function() {
           
           
           var ID = jQuery(this).attr( "id" );
           var selectedValues = "";
           var mydata = jQuery("#"+ID).val();
           if(jQuery("#"+ID).prop("multiple")){
               
               
            jQuery.each(mydata,function(index,value){

                console.log(value);
                 selectedValues += value+';';

            });
               
           }else{
               
               selectedValues = mydata;
               
           }
           
           
           
           
           
           
           
          data.append(jQuery(this).attr( "id" ), selectedValues);
           
           
           
       });
       jQuery('.mycustomesingledropdown').each(function() {
          
          var ID = jQuery(this).attr( "id" );
          var value = jQuery("#"+ID).val();
          var selectedValues = "";
          selectedValues += value+';'; 
          data.append(jQuery(this).attr( "id" ), selectedValues); 
           
       });
      
       
       jQuery('.mycustomcheckbox').each(function() {
           
            var ID = jQuery(this).attr( "id" );
            if(jQuery(this).prop("checked")) {
                data.append(jQuery(this).attr( "id" ), "Checked");
              } else {
                 data.append(jQuery(this).attr( "id" ), "");
              }
           
           
       });
       
       
       
       
       jQuery.ajax({
            url: urlnew,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                console.log(data);
               var message = jQuery.parseJSON(data);
              
               
               jQuery('body').css('cursor', 'default');
               
                if(message.msg == 'User created'){
                        
                      //jQuery(window).scrollTop(top);
                      //window.parent.scrollTo(0,600);
                      if(message.showmsg == 'exhibitorentryflow'){
                          
                        jQuery('#registrationnextpageurl')[0].click();
                        
                      }else{
                           swal({
					title: "Success",
					text: message.showmsg,
					type: "success",
                                        html:true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Ok"
				},function(){
                                    
                                    window.location.replace(url+'/login/');
                                    
                                });
                          
                      }
                      
                   
                    


                }else{          
                                var heightpage = jQuery(document).height()/2;
                                jQuery(window).scrollTop(top);
                                //window.parent.scrollTo(0,700);
                                window.parent.scrollTo(0,heightpage);
                                grecaptcha.reset();
                                swal({
					title: "Error",
					text: message.msg,
					type: "error",
                                        html:true,
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Ok"
                                       
				});
                }
               
                
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
      
  }else{
      
      
       swal({
            title: "Error",
            text: "Url is not valid. Provide a valid url (e.g. https://www.domain.com).",
            type: "error",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ok"
        });
           
  }
  }else{
      
      document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">This field is required.</span>';
      return false;
      
      
  }
}

function verifyCaptcha() {
    document.getElementById('g-recaptcha-error').innerHTML = '';
}