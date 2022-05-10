function update_order(){

    console.log('HELLO');
}
function delete_order(){
    
    Swal.fire({

        title: 'Are you sure?',
        icon: 'warning',
        scrollbarPadding: false,

        cancelButtonClass:' btn btn-danger',
        confirmButtonText: 'Next',
        confirmButtonClass:' btn btn-primary',
        showCancelButton: true,
        cancelButtonText:'Cancel',
        allowOutsideClick: false,
        
        html:
        '<div style = "overflow-x: hidden !important">'+
                '<p>You cannot undo this action. You will be asked the next screen what to do about the stock quantity for all the products in this order upon delete</p>'+
         '</div>',
      
       

   didOpen: () => {

          
   },

   preConfirm: function() {

    Swal.fire({

        scrollbarPadding: false,
        
        cancelButtonClass:' btn btn-danger',
        confirmButtonText: 'Delete',
        confirmButtonClass:' btn btn-primary',
        showCancelButton: true,
        cancelButtonText:'Cancel',
        allowOutsideClick: false,
        
        html:
        '<div style = "overflow-x: hidden !important">'+
                '<p>Choose what to do with the quantity of all items in this order  </p>'+
                '<br>'+
                '<div style="display: flex;">'+
                '<input id="restore-stock-quantity" type="checkbox" value="">'+
                '&nbsp'+
                '<p>Restore Stock Quantity</p><br>'+
                '</div>'+

                '<div style="display: flex;">'+
                '<input id="leave-stock-quantity" type="checkbox" value="">'+
                '&nbsp'+
                '<p>Leave Stock Quantity the Same</p><br>'+
                '</div>'+
            
         '</div>',
         
       

   didOpen: () => {

          
   },

   preConfirm: function() {

   
    }, })
   
    }, })

    }
  
    jQuery("#refund").one('click', function (e) { 

            jQuery('#refunded').append('<p>Amount Already refunded: $0</p><p>total available to refund: $10000</p><div style="display:flex;">Refund Amount:<input style="width:50% !important; margin-left: 98px;" type="text" class="form-control" id="" value="" ></div> <br> <div style="display:flex;">Reason for Refund (optional):  <input style="width:50% !important; margin-left: 9px;" type="text" class="form-control" id="" value="" ></div>');
    })



jQuery("#cancel").click(function(){
    document.location.href = currentsiteurl+'/dashboard';
  });
   

 