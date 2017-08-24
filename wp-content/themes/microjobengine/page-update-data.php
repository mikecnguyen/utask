<?php
/**
 * Template Name: Update Data
 */
global $user_ID;
if( ! is_super_admin( $user_ID ) ) {
    wp_redirect( get_home_url() );
}

get_header();
?>
<div id="content">
    <div class="block-page">
        <div class="container dashboard withdraw">
            <div class="row title-top-pages">
                <p class="block-title">Update Data</p>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    // Update invoices
                    $args = array(
                        'post_type' => 'order',
                        'post_status' => 'any',
                        'showposts' => -1
                    );
                    $invoices = get_posts( $args );
                    foreach ( $invoices as $invoice ) {
                        $created_time = get_post_meta( $invoice->ID, 'et_order_created_time', true );
                        if( ! $created_time ) {
                            update_post_meta( $invoice->ID, 'et_order_created_time', strtotime( $invoice->post_date ) );
                        }
                    }
                    echo 'Finished!';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();