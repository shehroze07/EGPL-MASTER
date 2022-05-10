<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
   // Silence is golden.
   // Template Name: Create New Order
   // require_once('temp/lib/woocommerce-api.php');
      if (current_user_can('administrator') || current_user_can('contentmanager') ) {
   
        include 'cm_header.php';
        include 'cm_left_menu_bar.php';
       
       $orderid = 1001;
      
   
   
                   ?>
<style>
.order-history {
    border: solid 1px;
    height: auto;
    float: right;
}

.order-notes {
    border: solid 1px;
    height: auto;
    float: right;
}

.bord {
    border: solid 1px;
    padding-bottom: 20px;
}

.quantity {
    /* width: 25% !important; */
    /* margin-left: 78px; */
    padding-top: 22px;
    display: flex;
}

.custom_dis_width {
    width: 20% !important;
}
.center{
    text-align:center;
}
.partial-div{
    padding: 6px;
    display: flex;
    /* justify-content: center; */
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

            <form method="post" action="javascript:void(0);">

                <h2>Order #<?php echo $orderid?> Details</h2>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3">
                            <h3>General Details</h3>
                            <label class="">Order date</label>
                            <div class='input-group date' id='datetimepicker1' style="width: 70%">
                                <input id="date" type='text' class="form-control" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <br>
                            <div style="width: 70%">
                                <label class="">Status</label>
                                <select id="order_status" name="order_status" class="select2 option2 mycustomedropdown">
                                    <?php 
                        ?>
                                    <option id=""></option>
                                    <option id="wc-partial-payment"  value="wc-partial-payment">Partially Paid</option>
                                    <option id="wc-completed" value="wc-completed">Completed</option>
                                    <option id="wc-pending" value="wc-completed">Blance Due</option>
                                    <option id="wc-cancelled" value="wc-cancelled">Cancelled</option>
                                    <option id="wc-refunded" value="wc-refunded">Refunded</option>

                                </select>
                            </div>
                            <br>
                            <div style="width: 70%">

                                <label class="">User</label>
                                <select id="order_user" name="order_user" class="select2  mycustomedropdown">
                                    <option value=""></option>
                                    <?php
                     $blog_id = get_current_blog_id();
                     $args = array(
                        'role__not_in' => 'Administrator',
                        );
                     $user_query =new WP_User_Query( $args  );
                     $lisstofuser = $user_query->get_results();
                     
         
              
                       foreach ($lisstofuser as $key=>$value) { 
                    
                                $user_email = $value->user_email;
                                 echo  '<option value="'.$value->ID.'">'.$user_email.'</option>';
                                       
                                   
                                  }
                                  ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h3>Billing Details</h3>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>First name</label>
                                    <input type="text" class="form-control text" id="first_name"
                                        placeholder="First name" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Last name</label>
                                    <input type="text" class="form-control text" id="last_name" placeholder="Last name"
                                        value="">
                                </div>
                                <div class="col-md-6 mb-3" style="width: 50.333% !important;">
                                    <label>Company</label>
                                    <input type="text" class="form-control text" id="company" placeholder="Company"
                                        value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Address Line 1</label>
                                    <input type="text" class="form-control text" id="address_1"
                                        placeholder="Address Line 1" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Address Line 2</label>
                                    <input type="text" class="form-control text" id="address_2"
                                        placeholder="Address Line 2" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>City</label>
                                    <input type="text" class="form-control text" id="city" placeholder="City" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Postcode / ZIP</label>
                                    <input type="text" class="form-control text" id="postcode"
                                        placeholder="Postcode / ZIP" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Country / Region</label>
                                    <select name="region" id="region" class="select2 option2 mycustomedropdown">
                                        <option>United States (US)</option>
                                        <option>Unitied Kingdom (UK)</option>
                                        <option>Pakistan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>State / Country</label>
                                    <select name="State" id="state" class="select2 option2 mycustomedropdown">
                                        <option>Colorado</option>
                                        <option>Denver</option>
                                        <option>Punjab</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email address</label>
                                    <input type="email" class="form-control text" id="email" placeholder="Email address"
                                        value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" class="form-control text" id="phone" placeholder="Phone"
                                        value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Payment Method</label>
                                    <select name="Payment Method" id="payment_method" class="select2 option2 mycustomedropdown"
                                        title="Payment Method">
                                        <option>N/A</option>
                                        <option>Pay by check</option>
                                        <option>Credit card</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Payment Date</label>
                                    <input type="date" class="form-control text" id="payment_date"
                                        placeholder="Payment Date" value="">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" style="width: 50.333% !important;">
                                <label>Transaction ID</label>
                                <input type="text" class="form-control text" id="Transaction ID"
                                    placeholder="Transaction ID" value="">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <h3>Order Actions</h3>
                            <div class="col-md-8 mb-3" style="float: right;">

                                <select id="emailinvoice" class="select2 option2 mycustomedropdown">
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
                                        <select id="Private" class="select2 option2 mycustomedropdown">
                                            <option>Note to user</option>
                                            <option>Private</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3" style="float: right;">
                                        <button style="float: rightt; min-width: 0px !important;" name=""
                                            class="btn btn-sm mycustomwidth btn-success" value="">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <br><br>
            <div class="container-fluid bord" style="height: auto">
                <div class="row">
                    <div class="order-table col-md-12">
                        <table id="productTable" style="width: 100%;" cellpadding="">
                            <thead>
                                <tr>
                                    <th>&nbsp;Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Discount</th>
                                    <th>Total Sales Price</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <hr style="border-top: 1px solid #000 !important;">
                        <div class="col-md-8">
                            <div class="discount-area">
                                <h5>Discount Codes</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="total-price">
                                <label>Total Price:</label>
                                <p id="totalPrice" >$0</p>
                                <label>Product Discount:</label>
                                <p id="productDiscount" disc="">$0</p>
                                <label>Cart Discount:</label>
                                <p id="cartDiscount" disc="">$0</p>
                            </div>
                            <hr style="border-top: 1px solid #000 !important; width: 250px;">
                            <div class="total-amount">
                                <label>Total Amount:</label>
                                <p id="totalAmount">$0</p>
                                <label>First Payment:</label>
                                <p id="firstPayment">$0</p>
                                <label>Second Payment:</label>
                                <p id="secondPayment">$0</p>
                                <label>Balance Due:</label>
                                <p id="balanceDue">$0</p>

                            </div>
                        </div>
                        <br>
                        <div class="col-md-8 mb-3" style="float: left; margin-left: -15px">
                            <button id="add-product" style="float: rightt; min-width: 30px !important;"
                                onclick="add_product()" name="" class="btn btn-sm mycustomwidth btn-success">Add
                                Product</button>
                            <button id="apply-discount" style="float: rightt; min-width: 30px !important;"
                                onclick="apply_discount()" name="" class="btn btn-sm mycustomwidth btn-success">Apply
                                Discount</button>
                        </div>

                        <div class="col-md-4 mb-3" style="float: left; margin-left: -15px">
                            <button style="float: rightt; min-width: 30px !important;" name=""
                                class="btn btn-sm mycustomwidth btn-success">Cancel</button>
                            <!-- <button style="float: rightt; min-width: 30px !important;"  name="" class="btn btn-sm mycustomwidth btn-success" >Preview Order</button> -->
                            <button type="submit" id="create-order" style="float: rightt; min-width: 30px !important;"
                                name="" class="btn btn-sm mycustomwidth btn-success" value=""
                                onclick="create_order()">Create Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php   include 'cm_footer.php';?>
<script>
jQuery(function() {
    jQuery('#datetimepicker1').datetimepicker();
});
</script>

<?}else{
   $redirect = get_site_url();
   wp_redirect( $redirect );exit;
   
   }
   ?>