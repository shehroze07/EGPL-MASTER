<?php /* 

Template Name: Admin Edit Tasks */ 

if ( is_user_logged_in() ) { 
     if (current_user_can('administrator') || current_user_can('contentmanager') ){
//wp_head();
get_header(); 



$ordernumber = $_GET['id'];
$order = wc_get_order( $ordernumber );



?>

                <div class="container mb-8 mt-8">
            <div class="card">
                <div class="card-body">
                    <div class="p-6">




                        <div class="woocommerce">
                            <div class="woocommerce-MyAccount-content" style="margin-left: 0px;">
                                <div class="woocommerce-notices-wrapper"></div>
        
<?php if(isset($_GET['id']) && !empty($order)){  ?>      
        
        <p>
            
        <?php
	/* translators: 1: order number 2: order date 3: order status */
	printf(
		__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce' ),
		'<mark class="order-number">' . $order->get_order_number() . '</mark>',
		'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>',
		'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>'
	);
        
        
        
        ?><button style="float: right;" type="button" class="no-print-able btn btn-light-primary font-weight-bold" onclick="window.print();">Print</button></p>

<?php 

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = true;//is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<section class="woocommerce-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<h2 style="text-align: center;" class="woocommerce-order-details__title"><?php esc_html_e( 'Order Details', 'woocommerce' ); ?></h2>
        <br>
	<table class="woocommerce-table woocommerce-table--order-details shop_table order_details shop_table order_details table table-bordered">

		<thead>
			<tr>
				<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			do_action( 'woocommerce_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = $item->get_product();
                               // echo $product;exit;
				wc_get_template(
					'order/order-details-item.php',
					array(
						'order'              => $order,
						'item_id'            => $item_id,
						'item'               => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'      => $product ? $product->get_purchase_note() : '',
						'product'            => $product,
					)
				);
			}

			do_action( 'woocommerce_order_details_after_order_table_items', $order );
			?>
		</tbody>

		<tfoot>
			<?php
			foreach ( $order->get_order_item_totals() as $key => $total ) {
				?>
					<tr>
						<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
						<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					</tr>
					<?php
			}
			?>
			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
			<?php endif; ?>
		</tfoot>
	</table>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
        
        
}else{
    
    echo '<div class="woocommerce"><div class="woocommerce-MyAccount-content" style="margin-left: 0px;"><div class="woocommerce-notices-wrapper"></div><div class="">Invalid order. <a href="'.site_url().'"/order-reporting/" class="wc-forward">Order Report</a></div></div></div>';
    
  
    
}?>


</div>
</div>
           	 	     
        	        			  



									</div>
								</div>
							</div>
						</div></div>
			
			
<?php



//wp_footer();
get_footer();


}else{
         
           $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
         
         
     }}else{
    
      $redirect = get_site_url();
        wp_redirect($redirect);
        exit;
    
    
    
}?>