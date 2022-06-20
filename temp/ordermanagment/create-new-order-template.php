<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="/wp-content/plugins/EGPL/css/order-management.css?v=2.73">
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
    //$payment_gateways = WC()->payment_gateways->get_available_payment_gateways();
    // echo '<pre>';
    // print_r($payment_gateways);
    // exit;
    
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
        // echo '<pre>';
        // print_r($order);
        // exit;
      
         if(empty($order)) { ?> 
           
<div class="page-content">
    <div class="container-fluid">
                <i class="flaticon-bell"></i>
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
           
            <h3>Order has been Deleted</h3>
           

        </div>
            </div>
        </div>
        <?php  exit;} 
         $order_data = $order->get_data();
        $user_id = $order->get_user_id();
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'EGPL_Order_History',
            'post_status'      => 'draft',
            
            );
        $listOFOrderHistory = get_posts( $args );
        
        // foreach ($listOFOrderHistory as $key => $value) {
        //     echo '<pre>';   
        //      print_r($value);
            
        //     // echo get_post_meta($value->ID,"status_log",true);
        //     // echo get_post_meta($value->ID,"order_id",true);
        //     // echo '<pre>';   
        //     // print_r(get_post_meta($value->ID,"custome_meta",true));

        // }
        // exit;
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
        //  echo '<pre>';
        // print_r($order_date_created);
        // exit;
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
          if(!empty($custom_field))
          {
              break;
          }
        }  
        foreach ($order_items as $item_id => $item) {
            $restored_stock = wc_get_order_item_meta( $item_id, '_restock_checked', true );
            if(!empty($restored_stock))
            {
                break;
            }
        }
        if(empty($restored_stock))
        {
            $restored_stock='unrestock';
        }              
        $order_value = 'wc-completed';
        if ($order_status == 'completed') {
            $order_status='Paid in Full';
            $order_value = 'wc-completed';
        } elseif ($order_status == 'pending') {
            $order_value = 'wc-pending-deposit';
        } elseif ($order_status == 'processing') {
            $order_value = 'wc-processing';
        } elseif ($order_status == 'on-hold') {
            $order_value = 'wc-on-hold';
        } elseif ($order_status == 'cancelled') {
            $order_value = 'wc-cancelled';
        } elseif ($order_status == 'refunded') {
            $order_value = 'wc-refunded';
        } elseif ($order_status == 'partial-payment') {
            $order_status='Initial Deposit Paid';
            $order_value = 'wc-partial-payment';
        } elseif ($order_status == 'scheduled-payment') {
            $order_value = 'wc-scheduled-payment';
        } elseif ($order_status == 'pending-deposit') {
            $order_status='Balance Due';
            $order_value = 'wc-pending-deposit';
        }elseif ($order_status == 'failed') {
            $order_status='Failed';
            $order_value = 'wc-failed';
        }  else {
            $order_value = 'wc-pending-deposit';
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
                        <label for="order_date">Date Created</label>
                        <form method="post" id="biling-form" action="javascript:void(0);" onsubmit="create_order()">
                        <div class="form-field form-field-wide datestyle" style='    display: flex;align-content: center;    align-items: baseline;'>
                            <div style='display: flex;' class='date' id='datetimepicker1' egid="datetimepicker1">
                                    <input type="text" id="date" egid="date" order_id=<?php echo $orderid ;?> required   class="form-control text" required
                                    value="<?php echo $arrayString[0]; ?>">
                                    <span class="input-group-addon" style='width: 38px;'>
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>@
							<input type="number" id="time-hour" class="hour form-control"   value="<?php echo $arrayStringM[0]; ?>"
                            style='    width: 30%;' placeholder="h" required name="order_date_hour" min="0" max="23" step="1"  pattern="([01]?[0-9]{1}|2[0-3]{1})">:
							<input type="number" id="time-mins" class="minute form-control"     value="<?php echo $arrayStringM[1]; ?>"
                            style='    width: 30%;' placeholder="m" required name="order_date_minute" min="0" max="59" step="1"  pattern="[0-5]{1}[0-9]{1}">
							<input type="hidden" name="order_date_second" value="03">
                            </div>
                      

                        <div>
                            <label id="prev_status" value= <?php echo $order_value ;?> class="">Status</label>
                            <?php if ($orderid) { ?>
                            <select id="order_status" egid="order_status" name="order_status"
                                onchange="statusChange('<?php echo $order_value ;?>',this)"
                                class="select2 option2 mycustomedropdown">
                                <?php if (!empty($order_status)) { ?>

                                <?php echo  '<option  selected  value=' . $order_value . '>' . ucfirst($order_status) . '</option>'; ?>

                                <?php } ?>

                                <option id="wc-partial-payment" value="wc-partial-payment">Initial Deposit Paid</option>
                                <option id="wc-completed" value="wc-completed">Paid in Full</option>
                                <option id="wc-pending-deposit" value="wc-pending-deposit">Balance Due</option>
                                <option id="wc-cancelled" value="wc-cancelled">Cancelled</option>
                                <option id="wc-refunded" value="wc-refunded">Refunded</option>
                                <option id="wc-failed" value="wc-failed">Failed</option>

                            </select>
                            <?php } else { ?>
                            <select id="order_status" egid="order_status" name="order_status" class="select2 option2 mycustomedropdown" onchange="getval(this)">
                                <option></option>
                                <option id="wc-completed" value="wc-completed">Paid in Full</option>
                                <option id="wc-partial-payment" value="wc-partial-payment">Initial Deposit Paid</option>
                                <option id="wc-pending-deposit" value="wc-pending-deposit">Balance Due</option>
                                <option id="wc-cancelled" value="wc-cancelled">Cancelled</option>
                                <option id="wc-refunded" value="wc-refunded">Refunded</option>
                                <option id="wc-failed" value="wc-failed">Failed</option>

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
                                        $site_prefix = 'wp_'.$blog_id.'_';
                                        $loggedInUser = get_user_meta($value->ID);
                                        $getroledata = unserialize($loggedInUser['wp_'.$blog_id.'_capabilities'][0]);
                                        $firstName=$loggedInUser[ $site_prefix."first_name"][0];
                                        $lastName=$loggedInUser[ $site_prefix."last_name"][0];
                                        $company_name=$loggedInUser[ $site_prefix."company_name"][0];
                                        reset($getroledata);
                                        $rolename = key($getroledata);
                                        $get_all_roles_array = 'wp_'.$blog_id.'_user_roles';
                                        $all_roles = get_option($get_all_roles_array);
                                       
                                        // exit;

                                         foreach($all_roles as $keys => $name) { 
                                            if($rolename == $keys){
                                                $level=$name['name'];
                                                break;
                                                // $userLevel=$name['key'];
                                            }
                                        }

                                    ?>
                                <?php if ($orderid) { ?>
                                <?php if ($value->ID == $user_id) { ?>

                                <option value=<?php echo $value->ID; ?> level=<?php echo $level;?> selected> <?php echo $company_name; ?></option>

                                <?php } else { ?>

                                <option  level=<?php echo $level;?> value=<?php echo $value->ID; ?>><?php echo $company_name; ?></option>
                                <?php } ?>

                                <?php } else { ?>
                                    <option  level=<?php echo $level;?> value=<?php echo $value->ID; ?>><?php echo $company_name;?></option>

                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if ($orderid) {
                                $view_user_paymentPage = site_url() . "/view-user-order/?id=" . $orderid;
                                $view_user_paymentPage_parent = site_url() . "/manage-order/?orderid=" . $custom_field;
                                $actions = wc_get_account_orders_actions( $order );
                                
                            ?>
                         <div>
                                <p> 
                                     <?php 
                                     foreach ( $actions as $key => $action ) {
                                        if( $action['name']=='Pay') { ?>
                                         <a href="<?php echo $action['url'] ?>" target="_blank" id="view-user-pp" egid="view-user-pp"><u>View User Payment Page</u></a>

                                     <?php }  ?>
                                     <?php }  ?>
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
                                    <label id="city_label" name="city" >City</label>
                                    <input type="text" class="form-control text"   id="city" egid="city"
                                        value="<?php echo $billing_details['city']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label name="postcode" id="postcode_label" name1='Zip' name2='ZIP' name3='Postal Code'>Postal Code / ZIP </label>
                                    <input type="text" class="form-control text"   id="postcode" egid="postcode"
                                        value="<?php echo $billing_details['postcode']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label name="country" name1='Country' id="Country_label">Country / Region</label>
                                    <select name="region" id="region" egid="region"   name="" class="select2 option2 mycustomedropdown form-control ">
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
                                    <label id="state_label" name="state" name1="State">State / Country</label>
                                    <input type="text" class="form-control text" id="state"   egid="state" name="State"
                                        value="<?php echo $billing_details['state']; ?>">
                                </div>
                                <div class="col-md-6 mb-3" style="min-height: 75px;">
                                    <label id="email_label" name="email">Email</label>
                                    <input type="email" class="form-control text" required    id="email" egid="email"
                                        value="<?php echo $billing_details['email']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label id="phone_label" name="phone" name1="Phone No" name2="Phone" name3='Phone '>Phone</label>
                                    <input type="text" class="form-control text" id="phone"  egid="phone"
                                        value="<?php echo $billing_details['phone']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Payment Method</label>
                                    <select name="Payment Method" id="payment_method" egid="payment_method"  onchange="paymentChange()"
                                        class="select2 option2 mycustomedropdown form-control" required="true" title="Payment Method">
                                        <option  selected></option>

                                        <?php foreach ($payment_gateways as $key => $value ) {?>
                                        <?php if ($value->title == $payment_method) { ?>

                                        <option value=<?php echo $key; ?> selected>
                                            <?php echo $payment_method; ?></option>

                                        <?php } else { ?>
                                           <?php if($value->title=='eCheck'){?>
                                        <option value=<?php echo $key; ?>>Authorize.Net</option>;
                                       
                                        <?php }else if($key =='cheque' || $key =='paypal'|| $key =='stripe'){ ?>
                                            <option value=<?php echo $key; ?>><?php echo $value->title; ?></option>;
                                        <?php } ?>
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
                            <div style="margin-top: 28px;">
                            <?php if ($orderid) { ?>
                                    <div class="col-md-8 mb-3 actionstyle" style="width: 92% !important; margin-top: 23px;margin-left: -15px;">
                                        
                                        <select id="emailinvoice" onchange='emailchange()' egid="emailinvoice" placeholder="Select Actions" class="select2 option2 mycustomedropdown">

                                            <option value="0"  >Select Action</option>
                                            <option value='1'> Resend Email Invoice/Order Details to Customer</option>
                                        </select>
                                        
                                        <div class="send-button">
                                              
                                                <button type="button"  disabled='true' id='sendEmail' egid="sendEmail" name="" class="btn btn-sm  btn-primary" value="">Send</button>
                        
                                        </div>
                                    </div>
                            <?php } else { ?>
                                    <div 
                                         style="margin-top: 50px;">
                                        <select id="emailinvoice" onchange='emailchange()' egid="emailinvoice" placeholder="Select Actions" class="select2 option2 mycustomedropdown">

                                            <option value="0"  >Select Action</option>
                                            <option value='1'>Email Invoice/Order Details to Customer</option>
                                        </select>
                                    </div>
                            <?php } ?>
                                <br><br>
                                <?php if ($orderid) { ?>
                                    <div class="order-hist" egid="order-hist"  style='padding-top: 51px;'>
                                    <?php } else { ?>
                                    <div class="order-hist" egid="order-hist"  style='margin-top: -21px;'>
                                    <?php } ?>
                                        <div class="card">
                                            <div class="card-header" id="headingOnes">
                                            <h6 class="mb-0">
                                                <a style=" margin-left: -5px; color: black; margin-left: -5px;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                  Order History
                                                </a>
                                                <i class="fa fa-expand" style='font-size: 14px;float:right;' aria-hidden="true" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"></i>
                                            </h6>
                                            </div>
                                           
                                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOnes" data-parent="#accordion">
                                            <div class="card-body" style='overflow: auto; height: 177px;padding:10px;'>
                                            <?php
                                                if ($orderid) { ?>
                                            <?php foreach ($listOFOrderHistory as $key => $value) { ?>
                                            <?php $status= get_post_meta($value->ID,"status_log",true);
                                                  $id= get_post_meta($value->ID,"order_id",true);
                                                  $history_id= get_post_meta($value->ID,"history_id",true);
                                                  $custome_meta= get_post_meta($value->ID,"custome_meta",true);
                                                  $author_id=$value->post_author;
                                                  $firstName=get_user_meta( $author_id, "first_name", true);
                                                  $lastName= get_user_meta( $author_id, "last_name", true);
                                                  $fullname= $firstName." ".$lastName." "; ?>
                                            <?php if ($orderid == $id) { 
                                                $date= date("F j, Y, g:i A",strtotime($value->post_date));
                                                $array = explode(',',  $date); ?>
                                            <p><?php echo  $status; ?>
                                            <br> <span>On: <?php echo $array[0]; ?>, <?php echo $array[1]; ?> @<?php echo $array[2]; ?></span><br><span>By:
                                             <?php echo $fullname; ?></span><a style="font-size: 12px;" onclick="OrderHistory(<?php echo $value->ID;?>,<?php echo $history_id?>,<?php echo  $orderid;?>)">See Detail</a>
                                            </p>
                                           
                                            <?php } ?>
                                            <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                               
                                   
                                  
                                    <div id="note-div" egid="note-div" class="order-note">
                                        <a style="    color: black;font-size: 17px; margin-left: 15px;">Order Notes</a>
                                        <?php if ($orderid) { 
                                                     $order_notes=get_post_meta( $orderid, '_order_custome_note',true );?>

                                                
                                                <?php } ?>
                                        <textarea id="order_note" egid="order_note" class="form-control orders_note" name="notes" rows="4"
                                            cols="30"><?php echo $order_notes; ?></textarea>
                                       
                                        <br>
                                        <div class="col-md-8 mb-3" style="float: left; margin-left: -15px">
                                            
                                        </div>
                                        <div class="col-md-4 mb-3" style="float: right;">
                                    
                                        </div>
                                    </div>
                               
                            </div>
                        </div>
                </div>
            </div>

            <br><br>
        </div>
        <div class="box-typical box-typical-padding">
            <div class="container-fluid bord" style="height: auto">
                <div class="row">
                    <div class="order-table col-md-12 table-responsive">
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

                                        $total__refunded_prices=0;
                                        $total_price = $order->get_formatted_order_total();
                                        foreach ($order_items as $item_id => $item) { ?>

                                <?php
                                            // // // Get the product name
                                            //    echo '<pre>';
                                            //    print_r($item);
                                           
                                            // $total__refunded_prices = $order->get_total_refunded();
                                            // echo 'sxxxxxxxx'.$total__refunded_prices;
                                            //  exit;
                                            $item_quantity = $order->get_item_meta($item_id, '_qty', true);
                                            $product_id = $item["product_id"];
                                            if(!empty($custom_field)&&$item_quantity==0)
                                            {
                                                $orders = wc_get_order($custom_field);
                                                $order_items = $orders->get_items();
                                                foreach ($order_items as $item) {
                                                    if ($item['product_id'] ==   $product_id) {
                                                        $product_value = $item->get_data();
                                                        $order_item_quantity = $product_value['quantity'];
                                                        break;
                                                    }
                                                }
                                            }
                                            
                                            $product_name = $item['name'];
                                          
                                            $subtotal = $item["subtotal"];
                                            $wc_deposit_enabled = get_post_meta($product_id, '_wc_deposit_enabled', true);
                                            $total = $item["total"];
                                            $disc = $subtotal - $total;
                                            // Get the item quantity
                                            // $item_quantity = $order->get_item_meta($item_id, '_qty', true);
                                            // Get the item line total
                                            $item_total += $subtotal;
                                            $item_totals += $total;
                                            $stock_quantity = get_post_meta($product_id, '_stock', true);
                                            // echo $item_total;
                                            $total_prices = $order->get_total();
                                            $total__refunded_prices = $order->get_total_refunded();
                                            // echo $total__refunded_prices;
                                            // if(empty($total__refunded_prices))
                                            // {
                                            //     $total__refunded_prices=0;
                                            // }
                                            $total_price = $order->get_formatted_order_total();
                                            if($item_quantity==0)
                                            {
                                                $item_quantity=$order_item_quantity;
                                            }
                                            $item_price = $subtotal / $item_quantity;
                                            $total_pricess=$total_prices-$total__refunded_prices;
                                            $terms = get_the_terms( $product_id, 'product_cat' );
                                            if($terms)
                                            {
                                                $cat= $terms[0]->name;
                                                
                                            }
                                            ?>

                                <tr id="<?php echo  $product_id; ?>">

                                    <td><?php echo  $product_name; ?></td>
                                    <td deposit_check="<?php echo $wc_deposit_enabled; ?>">$<?php echo  number_format($item_price); ?></td>
                                    <td><?php echo  $item_quantity; ?></td>
                                    <td id="packageDiscount" disc="">$<?php echo   $disc ? $disc : 0 ?></td>
                                    <td>$<?php echo   number_format($total); ?></td>
                                        <?php if($cat!='Uncategorized') {?>
                                    <td><span><i class=" fusion-li-icon fa fas fa-pencil-square fa-2x" title="Edit"
                                                onclick="editProduct(<?php echo $product_id; ?>, '<?php echo  $product_name; ?>' , <?php echo  $item_quantity; ?>,<?php echo $stock_quantity; ?>)"></i></span>
                                    </td>
                                    <?php } else {  ?>
                                        <td><span>-</span>
                                    </td>
                                        <?php }  ?>
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
                                                if ($discount_type == 'fixed_cart' ||$discount_type == 'percent') {
                                                    if($discount_type == 'percent')
                                                    {
                                                        $discount_code_cart=$discount_code;
                                                        $coupon_amount_cart = $coupon_amount *($item_total/100);

                                                    }else{
                                                        $discount_code_cart=$discount_code;
                                                        $coupon_amount_cart = $coupon_amount;

                                                    }
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

                                <p id="totalPrice" egid="totalPrice">$<?php echo $item_total ? number_format($item_total) : 0 ?></p>
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
                                    <p id="productDiscount" egid="productDiscount" discStill='<?php echo $discount_code_pro;?>' disc='<?php echo $discount_code_pro;?>' prod="">
                                        $<?php echo $coupon_amount_product ? $coupon_amount_product : 0; ?></p>
                                </div>
                                <div class="cart-discount disp">
                                    <label>Cart Discount:</label>&nbsp;
                                    <p id="cartDiscount" egid="cartDiscount" discStill='<?php echo $discount_code_cart;?>'  disc='<?php echo   $discount_code_cart;?>' Percent="">
                                        $<?php echo $coupon_amount_cart ? $coupon_amount_cart : 0; ?></p>
                                </div>

                            </div>

                            <hr class="m-t-lg with-border" style="width: 250px;">

                            <div class="total-amount">
                                <?php if ($orderid  ) {
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
                            <?php if ($orderid && $order_status != 'refunded'&& $order_status!='Balance Due' ) { ?>
                            <a id="refund" egid="refund" style=" min-width: 30px !important;" name=""
                                onclick="refund_order( <?php echo $orderid; ?>, <?php echo $total_pricess; ?>,<?php echo $total__refunded_prices;?>,'<?php  echo  $restored_stock;?>',<?php echo $custom_field;?>)"
                                class="btn btn-sm mycustomwidth btn-success">Refund</a>
                            <?php } ?>
                        </div>

                        <div class="col-md-4 mb-3" style="float: right; margin-left: -15px">
                            <a id="cancel1" egid="cancel1" style="float: rightt; min-width: 30px !important;" name=""
                                class="btn btn-sm mycustomwidth btn-success">Cancel</a>
                            <?php if ($orderid) { ?>
                            <a id="delete-order" egid="delete-order" order_id="<?php echo $orderid; ?>"
                                style="float: rightt; min-width: 30px !important;" onclick="delete_order(<?php echo $orderid; ?>,'<?php echo $order_status;?>',<?php echo $total__refunded_prices;?>,'<?php  echo  $restored_stock;?>',<?php echo $custom_field;?>)" name=""
                                class="btn btn-sm btn-danger">Delete Order</a>
                            <?php } ?>
                            <?php if ($orderid && $order_status != 'refunded') { ?>
                            <a type="submit" id="update-order" egid="update-order" style="float: rightt; min-width: 30px !important;"
                                name="" class="btn btn-sm mycustomwidth btn-success" value=""
                                onclick="update_order(<?php echo $orderid; ?>, '<?php echo $order_status;?>', <?php echo $total__refunded_prices;?>,'<?php  echo  $restored_stock;?>',<?php echo $custom_field;?>)">Update Order</a>
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
    jQuery( '#order_user' ).select2({
  /* Sort data using localeCompare */
  sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
     });
    var today = new Date();
    var date = ("0" + (today.getMonth() + 1)).slice(-2)  + '-' + ("0" + today.getDate()).slice(-2)  + '-' + today.getFullYear();
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
        jQuery("#datetimepicker1").datepicker("update", jQuery(this).attr("startdate"));
        jQuery("#datetimepicker1").datepicker("update", jQuery(this).attr("enddate"));
        jQuery('#date').val(jQuery('#date').attr('value'));
        // jQuery('#date').val(dateTime);
        jQuery("#datetimepicker2").datepicker("update", jQuery(this).attr("startdate"));
        jQuery("#datetimepicker2").datepicker("update", jQuery(this).attr("enddate"));
        jQuery('#payment_date').val(jQuery('#payment_date').attr('value'));
    }

    jQuery("#biling-form").validate();
    jQuery("#payment_method").select2();
    jQuery("#region").select2();

});
</script>

<? } else {
    $redirect = get_site_url();
    wp_redirect($redirect);
    exit;
}
?>