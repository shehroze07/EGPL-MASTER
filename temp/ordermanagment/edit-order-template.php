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

<?php 

$order = wc_get_order(4158);

$order_data = $order->get_data(); // The Order data

$user_id = $order->get_user_id(); 



   //   echo "<pre>";
   //  print_r($user_id); exit;


    $order_date_created = $order_data['date_created']->date('m-d-Y H:i:s');

   //  echo $order_date_created;
    $order_status = $order_data['status'];
   
 
    
  

$billing_details = $order_data['billing'];

$payment_method = $order_data['payment_method_title'];

$transaction_id = $order_data['[transaction_id'];



   //  echo "<pre>";
   //  print_r($billing_details);



// foreach ($order_data as $key => $data){
//     echo "<pre>";
//     print_r($data); exit;

// }

// Getting the items in the order
$order_items = $order->get_items();
// echo "<pre>";
// print_r($order_items);
// Iterating through each item in the order

// $users = get_users( array( 'fields' => array( 'ID' ) ) );

// foreach($users as $user){
//    echo"<pre>";
   
//         print_r(get_userdata($user->ID));
//     }

// exit;


// foreach ($order_items as $item_id => $item) {

//    echo "<pre>";
// print_r($item);
//     // Get the product name
//     $product_name = $item['name'];
//     // Get the item quantity
//     $item_quantity = $order->get_item_meta($item_id, '_qty', true);
//     // Get the item line total
//     $item_total = $order->get_item_meta($item_id, '_line_total', true);

//     // Displaying this data (to check)
//    //  echo 'Product name: '.$product_name.' | Quantity: '.$item_quantity.' | Item total: '. $item_total;
// }
// exit;
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
    width: 25% !important;
    margin-left: 78px;
    padding-top: 22px;
}

.custom_dis_width {
    width: 20% !important;
}
</style>

<div class="page-content">
    <div class="container-fluid">
        <header class="section-header">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <h3>Edit Order</h3>
                    </div>
                </div>
            </div>
        </header>
        <div class="box-typical box-typical-padding">
            <h5 class="m-t-lg with-border"></h5>
        </div>
        <div class="box-typical box-typical-padding">

            <form method="post" action="javascript:void(0);" onSubmit="update_order()">

                <h2>Order #<?php echo $orderid?> Details</h2>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3">
                            <h3>General Details</h3>
                            <label class="">Order date</label>
                            <div class='input-group date' id='datetimepicker1' style="width: 70%">
                                <input type='text' class="form-control" value="<?php echo $order_date_created; ?>">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <br>
                            <div style="width: 70%">
                                <label class="">Status</label>
                                <select class="select2 mycustomedropdown">
                                    <?php if(!empty($order_status)){ ?>

                                    <?php echo  '<option>'.$order_status.'</option>'; ?>

                                    <?php }else { ?>


                                    <option id=""></option>
                                    <option id="wc-partial-payment">Initial Deposit Paid</option>
                                    <option id="wc-completed">Paid In Full</option>
                                    <option id="wc-cancelled">Cancelled</option>
                                    <option id="wc-refunded">Refunded</option>

                                    <?php } ?>
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
                                    <input type="text" class="form-control" id="" placeholder="First name"
                                        value="<?php echo $billing_details['first_name']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Last name</label>
                                    <input type="text" class="form-control" id="" placeholder="Last name"
                                        value="<?php echo $billing_details['last_name']; ?>">
                                </div>
                                <div class="col-md-6 mb-3" style="width: 50.333% !important;">
                                    <label>Company</label>
                                    <input type="text" class="form-control" id="" placeholder="Company"
                                        value="<?php echo $billing_details['company']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Address Line 1</label>
                                    <input type="text" class="form-control" id="" placeholder="Address Line 1"
                                        value="<?php echo $billing_details['address_1']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Address Line 2</label>
                                    <input type="text" class="form-control" id="" placeholder="Address Line 2"
                                        value="<?php echo $billing_details['address_2']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>City</label>
                                    <input type="text" class="form-control" id="" placeholder="City"
                                        value="<?php echo $billing_details['city']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Postcode / ZIP</label>
                                    <input type="text" class="form-control" id="" placeholder="Postcode / ZIP"
                                        value="<?php echo $billing_details['postcode']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Country / Region</label>
                                    <select class="select2 mycustomedropdown">
                                        <?php if(!empty($billing_details['country'])){ ?>
                                        <?php echo  '<option>'.$billing_details['country'].'</option>'; ?>
                                        <?php }else { ?>

                                        <option>United States (US)</option>
                                        <option>Unitied Kingdom (UK)</option>
                                        <option>Pakistan</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>State / Country</label>
                                    <select class="select2 mycustomedropdown">
                                        <?php if(!empty($billing_details['country'])){ ?>
                                        <?php echo  '<option>'.$billing_details['state'].'</option>'; ?>
                                        <?php }else { ?>
                                        <option>Colorado</option>
                                        <option>Denver</option>
                                        <option>Punjab</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email address</label>
                                    <input type="email" class="form-control" id="" placeholder="Email address"
                                        value="<?php echo $billing_details['email']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" id="" placeholder="Phone"
                                        value="<?php echo $billing_details['phone']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Payment Method</label>
                                    <select class="select2 mycustomedropdown" title="Payment Method">
                                        <?php if(!empty(payment_method)) { ?>
                                        <?php echo  '<option>'.$payment_method.'</option>'; ?>
                                        <?php }else { ?>
                                        <option>N/A</option>
                                        <option>Pay by check</option>
                                        <option>Credit card</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Payment Date</label>
                                    <input type="date" class="form-control" id="" placeholder="Payment Date" value="">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3" style="width: 50.333% !important;">
                                <label>Transaction ID</label>
                                <input type="text" class="form-control" id="" placeholder="Transaction ID"
                                    value="<?php echo $transaction_id;?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <h3>Order Actions</h3>
                            <div class="col-md-8 mb-3" style="float: right;">
                                <select class="select2 mycustomedropdown">
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
                                    <th>Sales Price</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    
                                    foreach ($order_items as $item_id => $item) { ?>

                                <?php

                                     
                                       // Get the product name
                                       $product_name = $item['name'];
                                       $product_id = $item["product_id"];
                                       // Get the item quantity
                                       $item_quantity = $order->get_item_meta($item_id, '_qty', true);
                                       // Get the item line total
                                       $item_total = $order->get_item_meta($item_id, '_line_total', true);
                                         
                                      $price = $item_quantity * $item_total;

                                       
                                      
                                 
                                       // Displaying this data (to check)
                                      //  echo 'Product name: '.$product_name.' | Quantity: '.$item_quantity.' | Item total: '. $item_total;
                                      ?>

                                <tr id="<?php echo  $product_id; ?>">
                                
                                    <td><?php echo  $product_name; ?></td>
                                    <td><?php echo  $item_total; ?></td>
                                    <td><?php echo  $item_quantity; ?></td>
                                    <td></td>
                                    <td><?php echo  $item_total; ?></td>

                                    <td><span
                                            class="button-edit btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i
                                                id="<?php echo $product_id; ?>" product="<?php echo $product_name; ?>"
                                                quantity="<?php echo $item_quantity; ?>"
                                                class="hi-icon fusion-li-icon fa fa-pencil-square fa-2x" title="Edit"
                                                onclick="editProduct(<?php echo $product_id; ?>, '<?php echo  $product_name; ?>' , <?php echo  $item_quantity; ?>)"></i></span></td>
                                    <td><span
                                            class="button-remove  btn-xs pull-right hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a"><i
                                                id="<?php echo $product_id; ?>"
                                                class="hi-icon button-remove fusion-li-icon fa fa-times-circle fa-2x"
                                                title="Remove" onclick="deleteProduct(<?php echo $product_id; ?>)"></i></span></td>
                                </tr>

                           


                                <?php } ?>





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
                                <p id="totalPrice" ></p>
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
                        <hr style="border-top: 1px solid #000 !important;">
                        <div class="col-md-8">

                        </div>
                        <div id="refunded" class="col-md-4" style="float:right;">

                        </div>
                        <!-- <div class="col-md-8">
                     <div class="discount-area">
                         <h5>Discount Codes</h5>
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
                  </div> -->
                        <br>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3" style="float: left; padding-left: 30px; padding-top: 20px;">
                            <button id="add-product" style=" min-width: 30px !important;" onclick="add_product()"
                                name="" class="btn btn-sm mycustomwidth btn-success">Add Product</button>
                            <button id="apply-discount" style=" min-width: 30px !important;" onclick="apply_discount()"
                                name="" class="btn btn-sm mycustomwidth btn-success">Apply Discount</button>
                            <button id="refund" style=" min-width: 30px !important;" name=""
                                class="btn btn-sm mycustomwidth btn-success">Refund</button>
                        </div>

                        <div class="col-md-4 mb-3" style="padding-top: 20px; padding-left: 200px;">
                            <button id="cancel" style="float: rightt; min-width: 30px !important;" name=""
                                class="btn btn-sm mycustomwidth btn-success">Cancel</button>
                            <button id="delete-order" style="float: rightt; min-width: 30px !important;"
                                onclick="delete_order(this)" name="" class="btn btn-sm btn-danger">Delete
                                Order</button>
                            <button type="submit" id="update-order" style="float: rightt; min-width: 30px !important;"
                                name="" class="btn btn-sm mycustomwidth btn-success" value="">Update Order</button>
                        </div>
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