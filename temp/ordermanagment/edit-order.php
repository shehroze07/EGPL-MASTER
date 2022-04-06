<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
   // Silence is golden.
   // Template Name: Edit Order Template
      if (current_user_can('administrator') || current_user_can('contentmanager') ) {
          
       $siteurl = get_site_url();
   
        include 'cm_header.php';
        include 'cm_left_menu_bar.php';
   
   
       $orderid = 1001;
   
   
                   ?>
<style>
   .order-history{
   border: solid 1px;
   height: auto;
   float: right;
   }
   .order-notes{
   border: solid 1px;
   height: auto;
   float: right;
   }
   .bord{
   border: solid 1px;
   padding-bottom: 20px;
   }

   .quantity{
    width: 25% !important;
    margin-left: 78px;
    padding-top: 22px;
   }
   .custom_dis_width{
      width: 20% !important;
   }
</style>

<div class="page-content">
   <div class="container-fluid">
      <header class="section-header">
         <div class="tbl">
            <div class="tbl-row">
               <div class="tbl-cell">
                  <h3>Create New Order</h3>
               </div>
            </div>
         </div>
      </header>
      <div class="box-typical box-typical-padding">
         <h5 class="m-t-lg with-border"></h5>
      </div>
 <div class="box-typical box-typical-padding">

      <form method="post" action="javascript:void(0);" onSubmit="create_order()">

         <h2>Order #<?php echo $orderid?> Details</h2>
         <div class="container-fluid">
            <div class= "row">
               <div  class="col-sm-3">
                  <h3>General Details</h3>
                  <label class="">Order date</label>
                  <div class='input-group date' id='datetimepicker1' style="width: 70%">
                     <input type='text' class="form-control"  />
                     <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                     </span>
                  </div>
                  <br>
                  <div style="width: 70%">
                     <label class="">Status</label>
                     <select class="select2 mycustomedropdown" >
                        <option value=""></option>
                        <option>Balance Due</option>
                        <option>Initial Deposit Paid</option>
                        <option>Canceled</option>
                        <option>Refunded</option>
                     </select>
                  </div>
                  <br>
                  <div style="width: 70%">
                     <label class="">User</label>
                     <select class="select2 mycustomedropdown" >
                        <option value=""></option>
                        <option>User1</option>
                        <option>User2</option>
                        <option>User3</option>
                     </select>
                  </div>
               </div>
               <div  class="col-sm-6">
                  <h3>Billing Details</h3>
                  <div class="form-row">
                     <div class="col-md-6 mb-3">
                        <label >First name</label>
                        <input type="text" class="form-control" id="" placeholder="First name" value="" >
                     </div>
                     <div class="col-md-6 mb-3">
                        <label>Last name</label>
                        <input type="text" class="form-control" id="" placeholder="Last name" value="" >
                     </div>
                     <div class="col-md-6 mb-3" style="width: 50.333% !important;">
                        <label>Company</label>
                        <input type="text" class="form-control" id="" placeholder="Company" value="" >
                     </div>
                     <div class="col-md-6 mb-3">
                        <label >Address Line 1</label>
                        <input type="text" class="form-control" id="" placeholder="Address Line 1" value="" >
                     </div>
                     <div class="col-md-6 mb-3">
                        <label>Address Line 2</label>
                        <input type="text" class="form-control" id="" placeholder="Address Line 2" value="" >
                     </div>
                     <div class="col-md-6 mb-3">
                        <label >City</label>
                        <input type="text" class="form-control" id="" placeholder="City" value="" >
                     </div>
                     <div class="col-md-6 mb-3">
                        <label>Postcode / ZIP</label>
                        <input type="text" class="form-control" id="" placeholder="Postcode / ZIP" value="" >
                     </div>
                     <div class="col-md-6 mb-3">
                        <label >Country / Region</label>
                        <select class="select2 mycustomedropdown" >
                           <option>United States (US)</option>
                           <option>Unitied Kingdom (UK)</option>
                           <option>Pakistan</option>
                        </select>
                     </div>
                     <div class="col-md-6 mb-3">
                        <label>State / Country</label>
                        <select class="select2 mycustomedropdown">
                           <option>Colorado</option>
                           <option>Denver</option>
                           <option>Punjab</option>
                        </select>
                     </div>
                     <div class="col-md-6 mb-3">
                        <label >Email address</label>
                        <input type="email" class="form-control" id="" placeholder="Email address" value="" >
                     </div>
                     <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" id="" placeholder="Phone" value="" >
                     </div>
                     <div class="col-md-6 mb-3">
                        <label>Payment Method</label>
                        <select class="select2 mycustomedropdown"  title ="Payment Method">
                           <option>N/A</option>
                           <option>Pay by check</option>
                           <option>Credit card</option>
                        </select>
                     </div>
                     <div class="col-md-6 mb-3">
                        <label>Payment Date</label>
                        <input type="date" class="form-control" id="" placeholder="Payment Date" value="" >
                     </div>
                  </div>
                  <div class="col-md-6 mb-3" style="width: 50.333% !important;">
                     <label>Transaction ID</label>
                     <input type="text" class="form-control" id="" placeholder="Transaction ID" value="">
                  </div>
               </div>
               <div   class="col-sm-3">
                  <h3>Order Actions</h3>
                  <div class="col-md-8 mb-3" style="float: right;">
                     <select class="select2 mycustomedropdown" >
                        <option>Email Invoice/Order Details to Customers</option>
                     </select>
                  </div>
                  <br><br>
                  <div class="col-md-10 order-history">
                     <div class="order-hist">
                        <h3>Order History</h3>
                     </div>
                  </div>
                  <br><br><br>
                  <div class="col-md-10 order-notes">
                     <div class="order-not">
                        <h3>Add Notes</h3>
                        <textarea id="" name="notes" rows="4" cols="30"></textarea>
                        <br><br>
                        <div class="col-md-8 mb-3" style="float: left; margin-left: -15px">
                           <select class="select2 mycustomedropdown">
                              <option>Note to user</option>
                              <option>Private</option>
                           </select>
                        </div >
                        <div class="col-md-4 mb-3" style="float: right;">
                           <button style="float: rightt; min-width: 0px !important;" name="" class="btn btn-sm mycustomwidth btn-success" value="">Add</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <br><br>
         <div class="container-fluid bord" style="height: auto">
            <div class="row">
               <div class="order-table col-md-12">
                  <table style="width: 100%;" cellpadding="">
                     <tbody>
                     <thead>
                        <tr>
                           <th>&nbsp;Item</th>
                           <th>Price</th>
                           <th>Qty</th>
                           <th>Discount</th>
                           <th>Sales Price</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>Gold Package</td>
                           <td>$10000</td>
                           <td>1</td>
                           <td>$1000</td>
                           <td>$9000</td>
                           <td><span class="button-edit  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit"onclick=""></i></span></td>
                           <td><span class="button-remove  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x" title="Remove"onclick=""></i></span></td>
                        </tr>
                        <br>
                        <tr>
                           <td>Lanyard Sponser</td>
                           <td>$5000</td>
                           <td>1</td>
                           <td>$0</td>
                           <td>$5000</td>
                           <td><span class="button-edit  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit"onclick=""></i></span></td>
                           <td><span class="button-remove  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i class="hi-icon fusion-li-icon fa fa-times-circle fa-2x" title="Remove"onclick=""></i></span></td>
                        </tr>
                     </tbody>
                  </table>
                  <hr style="border-top: 1px solid #000 !important;">
                  <div class="col-md-8">
                     <div class="discount-area">
                         <h3>Discount Codes</h3>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="total-price">
                        <p>Total Price: $15000</p>
                        <p>Product Discount: $1000</p>
                        <p>Cart Discount: $0</p>
                     </div>
                     <hr style="border-top: 1px solid #000 !important; width: 250px;">
                     <div class="total-amount">
                        <p>Total Amount: $14000</p>
                        <p>First Payment: $1000</p>
                        <p>Second Payment: $0</p>
                        <p>Balance Due: $9000</p>
                     </div>
                  </div>
                  <br>
                  <div class="col-md-8 mb-3" style="float: left; margin-left: -15px">
                  <button id="add-product" style=" min-width: 30px !important;" onclick="add_product()" name="" class="btn btn-sm mycustomwidth btn-success" >Add Product</button>
                  <button id="apply-discount" style=" min-width: 30px !important;" onclick="apply_discount()"  name="" class="btn btn-sm mycustomwidth btn-success">Apply Discount</button>
                  <button id="apply-discount" style=" min-width: 30px !important;" onclick=""  name="" class="btn btn-sm mycustomwidth btn-success">Refund</button>
                    </div>

                    <div class="col-md-4 mb-3" style="float: left; margin-left: -15px">
                  <button style="float: rightt; min-width: 30px !important;"  name="" class="btn btn-sm mycustomwidth btn-success" >Cancel</button>
                  <button id="delete-order" style="float: rightt; min-width: 30px !important;" onclick="delete_order()" name="" class="btn btn-sm mycustomwidth btn-danger" >Delete Order</button>
                  <button type="submit" id="update-order" style="float: rightt; min-width: 30px !important;" name="" class="btn btn-sm mycustomwidth btn-success" value="">Update Order</button>
                    </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <form>
</div>
<?php   include 'cm_footer.php';?>
<script>
   jQuery(function () {
       jQuery('#datetimepicker1').datetimepicker();
   });
   
</script>

<script type="text/javascript" src="/wp-content/plugins/EGPL/js/ordermanagment/edit-order.js?v=1.01"></script>
<script type="text/javascript" src="/wp-content/plugins/EGPL/js/ordermanagment/create-new-order.js?v=1.20"></script>

<?}else{
   $redirect = get_site_url();
   wp_redirect( $redirect );exit;
   
   }
   ?>