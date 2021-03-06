<?php 
/**
 *	Template Name: Process Payment
 */
$session = et_read_session();
global $ad , $payment_return, $order_id, $user_ID;

/**
 * If seller reuse purchased packages
 */
if(isset($_GET['payment-type']) && $_GET['payment-type'] == 'usePackage') {
    $payment_return = ae_process_payment($_GET['payment-type'], $session);
    // If success, return to mjob detail
    if($payment_return['ACK']) {
        $mjob_url = get_the_permalink($session['ad_id']);

        // Destroy session for order data
        et_destroy_session();

        // Redirect to mjob detail
        wp_redirect($mjob_url);
        exit;
    }
}

/**
 * Get order data
 */
$order_id = isset($_GET['order-id']) ? $_GET['order-id'] : '';
if( isset($session['process_type']) && $session['process_type'] == 'buy' ){
    $order = new MJE_Order($order_id);
} else {
    $order = new AE_Order($order_id);
}
$order_data = $order->get_order_data();

/**
 * Allow current user is payer and administrator can view the order
 */
if($order_id && ($user_ID == $order_data['payer'] || is_super_admin($user_ID))) {
    // Get product data
    $ad = get_post($order_data['product_id']);

    $payment_type = $order_data['payment'];

    get_header();
?>
    <!-- Page Blog -->
    <section id="blog-page">
        <div class="container page-container">
            <!-- block control  -->
            <div class="row block-page">
                <div class="blog-content info-payment-method">
                    <h1 class="title"><?php _e('Order Received', 'enginethemes'); ?></h1>

                    <p class="sub-title"><?php _e('Thank you. Your order has been received and is now being processed!', 'enginethemes'); ?></p>

                    <div class="invoice-detail">
                        <div class="row list-payment-cash">
                            <div class="name">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?php _e('Invoice no', 'enginethemes') ?></div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?php _e('Date', 'enginethemes'); ?></div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?php _e('Payment type', 'enginethemes') ?></div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?php _e('Total', 'enginethemes') ?></div>
                            </div>
                            <div class="info">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?php echo $order_data['ID']; ?></div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?php echo get_the_date(get_option('date_format'), $order_id); ?></div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <?php
                                        $payment_method_txt_arr = mje_render_payment_name();
                                    ?>
                                    <?php echo $payment_method_txt_arr[$order_data['payment']]; ?>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><?php echo mje_format_price($order_data['total']); ?></div>
                            </div>
                        </div>

                        <?php if($order_data['payment'] == 'cash') : ?>
                            <?php
                            $cash_options = ae_get_option('cash');
                            $cash_message = $cash_options['cash_message'];
                            ?>
                            <div class="invoice-note">
                                <p class="type-cash"><?php _e('CASH NOTE', 'enginethemes'); ?></p>
                                <?php echo $cash_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="link-detail-method">
                        <?php if($ad->post_type == 'mjob_post') : ?>
                            <a href="<?php echo get_the_permalink($ad->ID) ?>" class="<?php mje_button_classes( array( ) ); ?>"><?php _e('Visit your mJob', 'enginethemes'); ?><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                        <?php else : 
								echo apply_filters('show_text_button_process_payment',$content="", $ad);
						 	  endif 
						 ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
    // Make sure that process payment just happen one time
    if($order_id && !get_post_meta($order_id, 'et_order_is_process_payment')) {
        if( isset($session['process_type']) && $session['process_type'] == 'buy' ){
            $payment_return = MJE_Checkout::process_payment( $payment_type, $session );
        } else {
            $payment_return = ae_process_payment($payment_type, $session);
        }
        update_post_meta($order_id, 'et_order_is_process_payment', true);
        et_destroy_session();
    }
    get_footer();
} else {
    wp_redirect(get_home_url());
    exit;
}