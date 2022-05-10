<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// Silence is golden.
// Template Name: Create New Order
// require_once('temp/lib/woocommerce-api.php');
if (current_user_can('administrator') || current_user_can('contentmanager')) {

    $path =  dirname(__FILE__);
    $hom_path = str_replace("ordermanagment","",$path);

    
    include $hom_path.'cm_header.php';
    include $hom_path.'cm_left_menu_bar.php';
    global $woocommerce;
    $payment_gateways = WC()->payment_gateways->payment_gateways();
    $countries_obj   = new WC_Countries();
    $countries   = $countries_obj->__get('countries');
    if (isset($_GET['orderid'])) {

        // $note = wc_get_order_notes($orderid);
        // $order_notes=get_post_meta( $orderid, '_order_custome_note' );
        // echo '<pre>';   
        // print_r($order_notes);  
        // exit;
        $ID =  $_GET['orderid'];
        $orderid = (int)$ID;
        $order = wc_get_order($orderid);
        $order_data = $order->get_data();
        
        $user_id = $order->get_user_id();
        // echo $user_id;
        // exit;
        $all_meta_for_user = get_user_meta($user_id);
        // echo '<pre>';
        // print_r($all_meta_for_user);
        // exit;
        $useremail = $all_meta_for_user['nickname'][0];
        $order_date_created =$order->get_date_created();
        //$order_date_payed = $order_data['date_modified']->date('m-d-Y H:i:s');
        $order_date_payed = $order->get_date_paid();
        $order_status = $order_data['status'];
        $billing_details = $order_data['billing'];
        $payment_method = $order_data['payment_method_title'];
        $transaction_id = $order_data['transaction_id'];
        $order_items = $order->get_items();
        $arrayString=  explode("T", $order_date_created );
        $arrayStringP=  explode("T", $order_date_payed );
        if($arrayString && $arrayString[1])
        {
            
            $arrayForMints=$arrayString[1];
            $arrayStringM=  explode(":", $arrayForMints );
        }else {
            # code...
            $arrayString=  explode(" ", $order_date_created );
            $arrayForMints=$arrayString[1];
            $arrayStringM=  explode(":", $arrayForMints );
        }
        if(empty( $arrayStringP))
        {
            $arrayStringP=  explode(" ", $order_date_payed );
        }
        
        // echo '<pre>';
        // print_r($order_date_payed);
        // exit;
        foreach ($order_items as $item_id => $item) {
          $custom_field = wc_get_order_item_meta( $item_id, '_remaining_balance_order_id', true );
          break;
        }                
        $order_value = 'wc-completed';
        if ($order_status == 'completed') {
            $order_value = 'wc-completed';
        } elseif ($order_status == 'pending') {
            $order_value = 'wc-pending';
        } elseif ($order_status == 'processing') {
            $order_value = 'wc-processing';
        } elseif ($order_status == 'on-hold') {
            $order_value = 'wc-on-hold';
        } elseif ($order_status == 'cancelled') {
            $order_value = 'wc-cancelled';
        } elseif ($order_status == 'refunded') {
            $order_value = 'wc-refunded';
        } elseif ($order_status == 'partial-payment') {
            $order_value = 'wc-partial-payment';
        } elseif ($order_status == 'scheduled-payment') {
            $order_value = 'wc-scheduled-payment';
        } elseif ($order_status == 'pending-deposit') {
            $order_value = 'wc-pending-deposit';
        } else {
            $order_value = 'wc-pending';
        }
    }

?>


<div class="page-content">
    <div class="container-fluid">
 
        <header class="section-header">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <?php if ($orderid) { ?>
                        <h3>Edit Order</h3>
                        <?php } else { ?>
                        <h3>Create New Order</h3>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </header>
        <div class="box-typical box-typical-padding">
            <?php if ($orderid) { ?>
            <h2>Order #<?php echo $orderid ?> Details</h2>
            <?php } else { ?>
            <h6>This is where you can manage and create various orders.</h6>
            <?php } ?>

        </div>
        <div class="box-typical box-typical-padding">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <h3>General Details</h3>
                        <!-- <label style="margin-top: 25px;" class="">Order Date</label> -->
                        <!-- <div class='date' id='datetimepicker1' egid="datetimepicker1" style="margin-bottom: 10px; width: 50%;">
                            <input id="date" style="position:relative;" egid="date" type='text' class="form-control"
                                value="<?php echo $order_date_created; ?>">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <div  >
                            <input id="time-hour" style="position:relative;" egid="time-hour" type='number' class="form-control">:
                            <input id="time-mins" style="position:relative;" egid="time-mins" type='number' class="form-control">
                            
                            </div>
                        </div> -->
                        <label for="order_date">Date created:</label>
                        <form method="post" id="biling-form" action="javascript:void(0);" onsubmit="create_order()">
                        <div class="form-field form-field-wide" style='    display: flex;align-content: center;    align-items: baseline;'>
                            <div style='display: flex;' class='date' id='datetimepicker1' egid="datetimepicker1">
                                    <input type="text" id="date" egid="date" required   class="form-control text" required
                                    value="<?php echo $arrayString[0]; ?>">
                                    <span class="input-group-addon" style='width: 38px;'>
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>@
							<input type="number" id="time-hour" class="hour form-control"   value="<?php echo $arrayStringM[0]; ?>"
                            style='    width: 20%;' placeholder="h" required name="order_date_hour" min="0" max="23" step="1"  pattern="([01]?[0-9]{1}|2[0-3]{1})">:
							<input type="number" id="time-mins" class="minute form-control"     value="<?php echo $arrayStringM[1]; ?>"
                            style='    width: 20%;' placeholder="m" required name="order_date_minute" min="0" max="59" step="1"  pattern="[0-5]{1}[0-9]{1}">
							<input type="hidden" name="order_date_second" value="03">
                            </div>
                      

                        <div>
                            <label class="">Status</label>
                            <?php if ($orderid) { ?>
                            <select id="order_status" egid="order_status" name="order_status"
                                onchange="statusChange('<?php echo $order_value ;?>')"
                                class="select2 option2 mycustomedropdown">
                                <?php if (!empty($order_status)) { ?>

                                <?php echo  '<option  selected value=' . $order_value . '>' . ucfirst($order_status) . '</option>'; ?>

                                <?php } ?>

                                <option id="wc-partial-payment" value="wc-partial-payment">Initial Deposit Paid</option>
                                <option id="wc-completed" value="wc-completed">Completed</option>
                                <option id="wc-pending" value="wc-pending">Balance Due</option>
                                <option id="wc-cancelled" value="wc-cancelled">Cancelled</option>
                                <option id="wc-refunded" value="wc-refunded">Refunded</option>

                            </select>
                            <?php } else { ?>
                            <select id="order_status" egid="order_status" name="order_status" class="select2 option2 mycustomedropdown" onchange="getval(this)">
                                <option></option>
                                <option id="wc-completed" value="wc-completed">Paid in Full</option>
                                <option id="wc-partial-payment" value="wc-partial-payment">Initial Deposit Paid</option>
                                <option id="wc-pending" value="wc-pending">Balance Due</option>
                                <option id="wc-cancelled" value="wc-cancelled">Cancelled</option>
                                <option id="wc-refunded" value="wc-refunded">Refunded</option>

                            </select>
                            <?php } ?>
                        </div>

                        <div>

                            <label class="">User</label>
                            <select id="order_user" egid= "order_user" name="order_user" class="select2  mycustomedropdown"
                                onchange="buttonLoad()">
                                <option value=""></option>
                                <?php
                                    $blog_id = get_current_blog_id();
                                    $args = array(
                                        'role__not_in' => 'Administrator',
                                    );
                                    $user_query = new WP_User_Query($args);
                                    $lisstofuser = $user_query->get_results();
                                    ?>


                                <?php foreach ($lisstofuser as $key => $value) {
                                        $user_email = $value->user_email;
                                        $blog_id = get_current_blog_id();
                                        $loggedInUser = get_user_meta($value->ID);
                                        $getroledata = unserialize($loggedInUser['wp_'.$blog_id.'_capabilities'][0]);
                                        reset($getroledata);
                                        $rolename = key($getroledata);
                                        $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                                        $all_roles = get_option($get_all_roles_array);
                                       
                                        // exit;

                                         foreach ($all_roles as $keys => $name) { 
                                            if($rolename == $keys){
                                                $level=$name['name'];
                                                break;
                                                // $userLevel=$name['key'];
                                            }
                                        }

                                    ?>
                                <?php if ($orderid) { ?>
                                <?php if ($value->ID == $user_id) { ?>

                                <option value=<?php echo $value->ID; ?> level=<?php echo $level;?> selected> <?php echo $user_email; ?></option>

                                <?php } else { ?>

                                <option  level=<?php echo $level;?> value=<?php echo $value->ID; ?>><?php echo $user_email; ?></option>
                                <?php } ?>

                                <?php } else { ?>
                                <option  level=<?php echo $level;?> value=<?php echo $value->ID; ?>><?php echo $user_email; ?></option>

                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if ($orderid) {
                                $view_user_paymentPage = site_url() . "/view-user-order/?id=" . $orderid;
                                $view_user_paymentPage_parent = site_url() . "/manage-order/?orderid=" . $custom_field;
                            ?>
                         <div>
                                 <p> 
                                     <a href="<?php echo $view_user_paymentPage ?>" target="_blank" id="view-user-pp" egid="view-user-pp"><u>View
                                    User Payment Page</u>
                                     </a>
                                
                                </p> 
                                <?php if ($custom_field) { ?>
                                <a href="<?php echo $view_user_paymentPage_parent ?>" target="_blank" id="view-user-pp" egid="view-user-pp"><u>Intial Parent Order</u>
                                     </a>
                                     <?php } ?>
                                </div>

                        <?php } ?>
                    </div>
                    
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3 style="padding-left: 12px;">Billing Details</h3>
                                </div>
                                <div class=col-sm-6>
                                    <button type="button" id='Load' egid="Load" disabled=true class="btn btn-sm mycustomwidth btn-success"
                                        value="">Load Billing Address</button>
                                </div>
                            </div>


                            <div class="form-row" style="line-height: 30px;">

                                <div class="col-md-6 mb-3">
                                    <label id="first_name_label" name="first_name">First Name</label>
                                    <input type="text" class="form-control text"  id="first_name" egid="first_name" required
                                        value="<?php echo $billing_details['first_name']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="last_name_label" name="last_name">Last Name</label>
                                    <input type="text" class="form-control text"   id="last_name" egid="last_name" required
                                        value="<?php echo $billing_details['last_name']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="company_label" name="company">Company Name</label>
                                    <input type="text" class="form-control text"   id="company" egid="company" required
                                        value="<?php echo $billing_details['company']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="address_1_label" name="address_1">Address Line 1</label>
                                    <input type="text" class="form-control text"   id="address_1" egid="address_1" 
                                        value="<?php echo $billing_details['address_1']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="address_2_label" name="address_2">Address Line 2</label>
                                    <input type="text" class="form-control text"    id="address_2" egid="address_2"
                                        value="<?php echo $billing_details['address_2']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="city_label" name="city">City</label>
                                    <input type="text" class="form-control text"   id="city" egid="city"
                                        value="<?php echo $billing_details['city']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label name="postcode">postal code / ZIP </label>
                                    <input type="text" class="form-control text"   id="postcode" egid="postcode"
                                        value="<?php echo $billing_details['postcode']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Country / Region</label>
                                    <select name="region" id="region" egid="region"  name="" class="select2 option2 mycustomedropdown">
                                        <?php if (!empty($billing_details['country'])) { ?>
                                        <?php echo  '<option selected value=' . $billing_details['country'] . '>' . $billing_details['country'] . '</option>'; ?>
                                        <?php } ?>
                                        <?php foreach ($countries as $key => $country) { ?>
                                           <option></option>
                                        <option value=<?php echo $country; ?>><?php echo $country; ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="state_label" name="state">State / Country</label>
                                    <input type="text" class="form-control text" id="state"   egid="state" name="State"
                                        value="<?php echo $billing_details['state']; ?>">
                                </div>
                                <div class="col-md-6 mb-3" style="min-height: 75px;">
                                    <label id="email_label" name="email">Email</label>
                                    <input type="email" class="form-control text" required    id="email" egid="email"
                                        value="<?php echo $billing_details['email']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="phone_label" name="phone">Phone</label>
                                    <input type="text" class="form-control text" id="phone"  egid="phone"
                                        value="<?php echo $billing_details['phone']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Payment Method</label>
                                    <select name="Payment Method" id="payment_method" egid="payment_method" required onchange="paymentChange()"
                                        class="select2 option2 mycustomedropdown" title="Payment Method">
                                        <option value=""></option>

                                        <?php foreach ($payment_gateways as $key => $value ) {?>
                                        <?php if ($value->title == $payment_method) { ?>

                                        <option value=<?php echo $key; ?> selected>
                                            <?php echo $payment_method; ?></option>

                                        <?php } else { ?>
                                           
                                        <option value=<?php echo $key; ?>><?php echo $value->title; ?></option>;
                                        <?php } ?>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Payment Date</label>
                                    <div class='input-group date' id='datetimepicker2' egid="datetimepicker2">
                                        <input type='text' class="form-control text" required id="payment_date" egid="payment_date"
                                            value="<?php echo $arrayStringP[0]; ?>">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Transaction ID</label>
                                    <input type="text" class="form-control text" id="Transaction_ID" egid="Transaction_ID"
                                        value="<?php echo $transaction_id; ?>">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">
                            <h3>Order Actions</h3>
                            <div style="margin-top: 29px;">
                                <div class="col-md-8 mb-3"
                                    style="width: 108%; margin-top: 20px; margin-left: -15px;margin-bottom: 20px;">

                                    <select id="emailinvoice" egid="emailinvoice" class="select2 option2 mycustomedropdown">
                                        <option>Email Invoice/Order Details to Customers</option>
                                    </select>
                                </div>
                                <br><br>
                                <?php
                                    if ($orderid) {
                                        $note = wc_get_order_notes($orderid);
                                        // echo '<pre>';   
                                        // print_r($note);
                                    
                                        $xs =  get_comments($orderid);
                                    }

                                    ?>
                               
                                    <div class="order-hist" egid="order-hist"  style='padding-top: 50px;'>
                                       
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                            <h5 class="mb-0">
                                                <a style="    color: black;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                  Order History
                                                </a>
                                            </h5>
                                            </div>
                                           
                                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body" style='overflow: auto; height: 177px;'>
                                            <?php
                                                if ($orderid) { ?>
                                            <?php foreach ($xs as $key => $notes) { ?>
                                            <?php $comment_post_ID = $notes->comment_post_ID; ?>
                                            <?php if ($orderid == $comment_post_ID) { ?>
                                            <p><?php echo $notes->comment_content; ?><br><span>At:
                                                    <?php echo $notes->comment_date; ?></span><br><span>By:
                                                    <?php echo $notes->comment_author; ?></span>
                                            </p>
                                            <?php } ?>
                                            <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            <a style="    color: black;" data-toggle="collapse" data-target="#collapsetwo" aria-expanded="true" aria-controls="collapseOne">
                                                Order Note
                                            </a>
                                        </h5>
                                    </div>
                                    
                                    <div id="collapsetwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                                <?php if ($orderid) { 
                                                     $order_notes=get_post_meta( $orderid, '_order_custome_note',true );?>

                                                <p><?php echo $order_notes; ?></p>
                                                <?php } ?>
                                            
                                               
                                           </div>
                                            </div>
                                        </div>

                                    </div>
                                    <br>
                                    <!-- <hr class="m-t-lg with-border"> -->

                                    <!-- <div id="note-div" egid="note-div" class="order-note">
                                        <label>Add Notes</label>
                                        <textarea id="order_note" egid="order_note" class="form-control" name="notes" rows="4"
                                            cols="30"></textarea>
                                        <br>
                                        <div class="col-md-8 mb-3" style="float: left; margin-left: -15px">
                                            <select id="Private" egid="Private" class="select2 option2 mycustomedropdown">
                                                <option>Note to user</option>
                                                <option>Private</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3" style="float: right;">
                                            <button type="button" style="float: rightt; min-width: 0px !important;" id='addNote' disabled=true egid="addNote" name=""
                                                class="btn btn-md mycustomwidth btn-success" value="">Add</button>
                                        </div>
                                    </div> -->
                               
                            </div>
                        </div>
                </div>
            </div>

            <br><br>
        </div>
        <div class="box-typical box-typical-padding">
            <div class="container-fluid bord" style="height: auto">
                <div class="row">
                    <div class="order-table col-md-12">
                        <table id="productTable" egid="productTable" class="table table-striped w-auto table-bordered"
                            style="width: 100%; overflow-x:auto;" cellpadding="">
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
                            <tbody >
                                <?php
                                    if ($orderid) {


                                        foreach ($order_items as $item_id => $item) { ?>

                                <?php
                                            // Get the product name
                                            //    echo '<pre>';
                                            //    print_r($item);
                                           
                                            
                                            //  exit;
                                            $product_name = $item['name'];
                                            $product_id = $item["product_id"];
                                            $subtotal = $item["subtotal"];
                                            $total = $item["total"];
                                            $disc = $subtotal - $total;
                                            // Get the item quantity
                                            $item_quantity = $order->get_item_meta($item_id, '_qty', true);
                                            // Get the item line total
                                            $item_total += $subtotal;
                                            $item_totals += $total;
                                            // echo $item_total;
                                            $total_prices = $order->get_total();
                                            $total__refunded_prices = $order->get_total_refunded();
                                            $total_price = $order->get_formatted_order_total();
                                           
                                            $item_price = $subtotal / $item_quantity;
                                            $total_pricess=$total_prices-$total__refunded_prices;
                                            ?>

                                <tr id="<?php echo  $product_id; ?>">

                                    <td><?php echo  $product_name; ?></td>
                                    <td>$<?php echo  $item_price; ?></td>
                                    <td><?php echo  $item_quantity; ?></td>
                                    <td id="packageDiscount" disc="">$<?php echo   $disc ? $disc : 0 ?></td>
                                    <td>$<?php echo   $total; ?></td>

                                    <td><span><i class=" fusion-li-icon fa fas fa-pencil-square fa-2x" title="Edit"
                                                onclick="editProduct(<?php echo $product_id; ?>, '<?php echo  $product_name; ?>' , <?php echo  $item_quantity; ?>,<?php echo $stock_quantity; ?>)"></i></span>
                                    </td>
                                    <td><span><i class="fusion-li-icon fa fas fa-times-circle fa-2x" title="Remove"
                                                title="Remove"
                                                onclick="deleteProduct(<?php echo $product_id; ?>, <?php echo $item_quantity; ?>)"></i></span>
                                    </td>
                                </tr>




                                <?php }  ?>

                                <?php } ?>
                            </tbody>
                        </table>
                        <hr class="m-t-lg with-border">
                        <div class="col-md-8">
                            <div class="discount-area">

                                <label>Discount Codes:</label>&nbsp;
                                <div id="disocuntLabels" egid="disocuntLabels">
                                    <?php
                                        if ($orderid) {
                                            foreach ($order->get_coupon_codes() as $coupon_code) {
                                                // Get the WC_Coupon object
                                                $coupon = new WC_Coupon($coupon_code);
                                                //   echo "<pre>";
                                                //   print_r($coupon);
                                                $discount_type = $coupon->get_discount_type(); // Get coupon discount type
                                                $discount_code = $coupon->get_code(); // Get coupon discount type
                                                $coupon_amount = $coupon->get_amount();
                                                $coupon_product = $coupon->get_product_ids();
                                                if ($discount_type == 'fixed_cart') {
                                                    $discount_code_cart=$discount_code;
                                                    $coupon_amount_cart = $coupon_amount;
                                                } else {
                                                    $discount_code_pro=$discount_code;
                                                    foreach ($order_items as $item_id => $item) {
                                                        $product_id = $item["product_id"];
                                                        $item_quantity = $order->get_item_meta($item_id, '_qty', true);
                                                        if (array_search($product_id, $coupon_product) !== false) {
                                                            $coupon_amount = $coupon_amount * $item_quantity;
                                                        }
                                                    }
                                                    $coupon_amount_product = $coupon_amount;
                                                }

                                        ?>

                                    <li id="<?php echo $discount_code?>" class='code editable'><span>
                                            <?php echo $discount_code?>
                                        </span>

                                    </li>

                                    <?php
                                            }
                                            ?>
                                    <?php
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <?php if ($orderid) { ?>
                            <div class="total-price disp">
                                <label>Total Price:</label>&nbsp;

                                <p id="totalPrice" egid="totalPrice">$<?php echo $item_total ? $item_total : 0 ?></p>
                            </div>
                            <?php } else { ?>
                            <div class="total-price disp">
                                <label>Total Price:</label>&nbsp;

                                <p id="totalPrice" egid="totalPrice">$<?php echo $item_total ? $item_total : 0 ?></p>
                            </div>
                            <?php } ?>
                            <div class="discs">

                                <div class="product-discount disp">
                                    <label>Product Discount:</label>&nbsp;
                                    <p id="productDiscount" egid="productDiscount" disc='<?php echo $discount_code_pro;?>' prod="">
                                        $<?php echo $coupon_amount_product ? $coupon_amount_product : 0; ?></p>
                                </div>
                                <div class="cart-discount disp">
                                    <label>Cart Discount:</label>&nbsp;
                                    <p id="cartDiscount" egid="cartDiscount" disc='<?php echo   $discount_code_cart;?>' Percent="">
                                        $<?php echo $coupon_amount_cart ? $coupon_amount_cart : 0; ?></p>
                                </div>

                            </div>

                            <hr class="m-t-lg with-border" style="width: 250px;">

                            <div class="total-amount">
                                <?php if ($orderid) {
                                    ?>
                                <div class=" disp">
                                    <label>Total Amount:</label>&nbsp;
                                    <p id="totalAmount" egid="totalAmount"><?php echo  $total_price ? $total_price : 0; ?></p>
                                </div>
                                <?php } else { ?>
                                <div class=" disp">
                                    <label>Total Amount:</label>&nbsp;
                                    <p id="totalAmount" egid="totalAmount">$<?php echo  $total_price ? $total_price : 0; ?></p>
                                </div>
                                <?php } ?>

                                <div class=" disp">
                                    <label>First Payment:</label>&nbsp;
                                    <p id="firstPayment" egid="firstPayment">$0</p>
                                </div>

                                <div class=" disp">
                                    <label>Second Payment:</label>&nbsp;
                                    <p id="secondPayment" egid="secondPayment">$0</p>
                                </div>
                                <?php if($orderid && ($order_status=='pending' || $order_status=='pending-deposit')) { ?>
                                <div class=" disp">
                                    <label>Balance Due:</label>&nbsp;
                                    <p id="balanceDue" egid="balanceDue"><?php echo  $total_price ?></p>
                                </div>
                                <?php } else { ?>
                                <div class=" disp">
                                    <label>Balance Due:</label>&nbsp;
                                    <p id="balanceDue" egid="balanceDue">$0</p>
                                </div>
                                <?php } ?>

                            </div>

                        </div>
                        <div class="col-md-12">
                            <hr class="m-t-lg with-border">
                        </div>
                        <br>
                        <div class="col-md-8 mb-3" style="float: left; margin-left: -15px">
                            <a id="add-product" egid="add-product" style=" min-width: 30px !important;" onclick="add_product()" name=""
                                class="btn btn-sm mycustomwidth btn-success">Add
                                Product</a>
                            <a id="apply-discount" egid="apply-discount" style="float: rightt; min-width: 30px !important;"
                                onclick="apply_discount()" name="" class="btn btn-sm mycustomwidth btn-success">Apply
                                Discount</a>
                            <?php if ($orderid && $order_status != 'refunded') { ?>
                            <a id="refund" egid="refund" style=" min-width: 30px !important;" name=""
                                onclick="refund_order( <?php echo $orderid; ?>, <?php echo $total_pricess; ?>)"
                                class="btn btn-sm mycustomwidth btn-success">Refund</a>
                            <?php } ?>
                        </div>

                        <div class="col-md-4 mb-3" style="float: right; margin-left: -15px">
                            <a id="cancel1" egid="cancel1" style="float: rightt; min-width: 30px !important;" name=""
                                class="btn btn-sm mycustomwidth btn-success">Cancel</a>
                            <?php if ($orderid) { ?>
                            <a id="delete-order" egid="delete-order" order_id="<?php echo $orderid; ?>"
                                style="float: rightt; min-width: 30px !important;" onclick="delete_order(this)" name=""
                                class="btn btn-sm btn-danger">Delete
                                Order</a>
                            <?php } ?>
                            <?php if ($orderid && $order_status != 'refunded') { ?>
                            <a type="submit" id="update-order" egid="update-order" style="float: rightt; min-width: 30px !important;"
                                name="" class="btn btn-sm mycustomwidth btn-success" value=""
                                onclick="update_order(<?php echo $orderid; ?>, '<?php echo $order_status?>')">Update Order</a>
                            <?php } else if(!$orderid) { ?>
                            <button type="submit" id="create-order" egid="create-order" style="float: rightt; min-width: 30px !important;"
                                name="" class="btn btn-sm mycustomwidth btn-success" value="">Create Order</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </form>
    </div>

</div>
<?php include $hom_path.'cm_footer.php'; ?>
<script>
jQuery(function() {

    // var today = new Date();
    // var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    // var dateTime = date;
    var today = new Date();
    var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    //var dateTime = date + ' ' + time;
    var dateTime = date;

   
    let check = jQuery("#order_user option:selected").val();
    console.log(check);
    if (check == "") {
        jQuery('#datetimepicker1').datepicker();
        jQuery('#date').val(dateTime);
        //jQuery('#datetimepicker1').datetimepicker({ defaultDate:moment(today).hours(0).minutes(0).seconds(0).milliseconds(0)});
        jQuery('#datetimepicker2').datepicker();
       // jQuery('#datetimepicker1').val(dateTime);
        // jQuery('#date').val(dateTime);
        jQuery('#payment_date').val(dateTime);
        jQuery('#time-hour').val(today.getHours());
        jQuery('#time-mins').val(today.getMinutes());

    } else {
        jQuery('#datetimepicker1').datepicker({
            "setDate": new Date(),
            "autoclose": false
        });
        jQuery('#datetimepicker2').datepicker({
            "setDate": new Date(),
            "autoclose": true
        });
    }

    jQuery("#biling-form").validate();

});
</script>

<? } else {
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>
