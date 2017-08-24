<?php
get_header();
global $wp_query, $ae_post_factory, $post, $user_ID;
$user_author = $post->post_author;

$post_object    = $ae_post_factory->get( 'mjob_post' );
$current        = $post_object->convert($post);

$cats = $current->tax_input['mjob_category'];
$breadcrumb = '';
if( !empty($cats) ) {
// Show breadcrumb
    $parent = $cats['0']->parent;
    $breadcrumb = '<div class="mjob-breadcrumb"><a class="parent" href="' . get_term_link($cats["0"]) . '">'.'<p>' . $cats["0"]->name .'</p>'. '</a></div>';
    if ($parent != 0) {
        $parent = get_term_by('ID', $parent, 'mjob_category');
        $breadcrumb = '<div class="mjob-breadcrumb"><a class="parent" href="' . get_term_link($parent) . '">'.'<p>' . $parent->name .'</p>'. '</a> <i class="fa fa-angle-right"></i> <span><a class="child" href="' . get_term_link($cats["0"]) . '">' . $cats['0']->name . '</a></span></div>';
    }
}
// End show breadcumb

$skills = $current->tax_input['skill'];
$disableClass = 'mjob-order-action';
$customOrderId = 'bt-send-custom';
if( $current->post_status == 'pause' ){
    $disableClass = 'mjob-order-disable';
    $customOrderId = 'bt-send-custom-disable';
}

if( in_array( $current->post_status, array( 'pending', 'draft', 'reject', 'archive' ) ) ) {
    $disableClass = 'disable-button';
    $customOrderId = 'disable-custom-order-link';
}

$is_edit = false;
if( isset($_GET['action']) && $_GET['action'] = 'edit'){
    $is_edit = MJE_MJob_Action::checkEdit( $current );
}

// Check if user can edit a mjob then show edit button to him
$show_edit_button = MJE_MJob_Action::checkEdit( $current );

$current->is_edit = $is_edit;

// mJob statistic
$count_review = mje_get_total_reviews($current->ID);

echo '<script type="text/template" id="mjob_single_data" >'.json_encode($current).'</script>';
?>
    <div id="content" class="mjob-single-page">
        <?php get_template_part('template/content', 'page');?>
        <div class="block-items-detail">
            <div class="container">
                <div class="row block-detail-job">
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <div class="title-detail-job">
                            <h2 class="single-detail-content"><p class="mjob-title"><?php echo $current->post_title; ?></p>
                                <?php if( $show_edit_button ): ?>
                                    <a href="#" class="edit-mjob-action"><i class="fa fa-pencil"></i><?php _e('EDIT', 'enginethemes') ?></a>
                                <?php endif; ?>
                            </h2>
                        </div>
                        <div class="single-detail-content">
                            <div class="items-private">
                                <div class="cate-items mjob-cat"><?php echo $breadcrumb; ?></div>
                                <div class="time-post"><?php _e('Last modified: ', 'enginethemes') ;?> <span class="mjob-modified-day"><?php echo $current->modified_date; ?></span></div>
                            </div>
                            <div class="gallery">
                                <!-- <img src="<?php /*echo $current->the_post_thumbnail; */?>" width="100%" alt="">-->
                                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                    <!-- Indicators -->
                                    <?php  if( !empty($current->et_carousel_urls) ):
                                        $active ='active';
                                        $i = 0;
                                        ?>
                                    <ol class="carousel-indicators mjob-carousel-indicators">
                                        <?php foreach($current->et_carousel_urls as $key=>$value){
                                        ?>
                                        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i ?>" class="<?php echo $active; ?>"></li>
                                        <?php
                                            $i++;
                                            $active = '';
                                        } ?>
                                    </ol>
                                    <?php endif; ?>
                                    <!-- Wrapper for slides -->
                                    <?php  if( !empty($current->et_carousel_urls) ):
                                        $active ='active';
                                        ?>
                                    <div class="carousel-inner mjob-single-carousels" role="listbox">
                                        <?php
                                            foreach($current->et_carousel_urls as $key=>$value){
                                                $slide = wp_get_attachment_image_src($value->ID, "mjob_detail_slider");
                                                $slide_url = $slide[0];
                                        ?>
                                            <div class="item <?php echo $active;?>">
                                                <img src="<?php echo $slide_url; ?>" alt="">
                                            </div>
                                        <?php
                                            $active = '';
                                        } ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                        <span class="fa fa-angle-left" aria-hidden="true"></span>
                                        <span class="sr-only"><?php _e('Previous', 'enginethemes'); ?></span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                        <span class="fa fa-angle-right" aria-hidden="true"></span>
                                        <span class="sr-only"><?php _e('Next', 'enginethemes'); ?></span>
                                    </a>
                                </div>
                            </div>
                            <div class="information-items-detail">
                                <div class="tabs-information">
                                    <span class="title"><?php _e('DESCRIPTION', 'enginethemes') ;?></span>
                                    <div class="tabs-information" id="description"><?php echo $current->post_content; ?></div>
                                    <div class="tags">
                                        <?php mje_list_tax_of_mjob($current->ID, 'skill', 'skill') ?>
                                    </div>
                                </div>
                                <div class="outer-function-group">
                                    <?php if( $user_ID != $current->post_author ): ?>
                                    <button class="<?php mje_button_classes( array( 'btn-order', 'waves-effect', 'waves-light' ) ) ?> <?php echo $disableClass; ?>" ><?php echo sprintf(__('ORDER (<span class="mjob-price mje-price-text">%s</span>)', 'enginethemes'), $current->et_budget_text) ; ?></button>
                                    <?php endif; ?>
                                    <button class="btn-bookmark"><i class="fa fa-heart"></i></button>
                                    <div class="sharing">
                                        <span><?php _e('Share', 'enginethemes'); ?></span>
                                        <ul class="link-social list-share-social addthis_toolbox addthis_default_style">
                                            <li><a href="<?php echo $current->permalink; ?>" class="addthis_button_facebook face" title="<?php _e('Facebook', 'enginethemes'); ?>"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="<?php echo $current->permalink; ?>" class="addthis_button_twitter twitter" title="<?php _e('Twitter', 'enginethemes'); ?>"><i class="fa fa-twitter"></i></a></li>
                                            <li><a href="https://plus.google.com/share?url=<?php echo $current->permalink; ?>" class=" google" title="<?php _e('Google', 'enginethemes'); ?>" target="_blank" ><i class="fa fa-google-plus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mjob-edit-content">
                            <?php get_template_part('template-js/template', 'edit-mjob');?>
                        </div>
                        <div class="review-job">
                            <p class="title">
                                <?php printf(__('Review <span class="total-review">(%s total)</span>', 'enginethemes'), $count_review); ?>
                            </p>
                            <ul>
                                 <?php
                                    $reviews_per_page = 5;
                                    $total_args =  array(
                                        'type' => 'mjob_review',
                                        'post_id' => $current->ID ,
                                        'paginate' => 'load',
                                        'order' => 'DESC',
                                        'orderby' => 'date',
                                    );

                                     $query_args = wp_parse_args(array(
                                         'number' => $reviews_per_page,
                                         'page' => 1
                                     ), $total_args);

                                    // Get reviews
                                    $review_obj = MJE_Review::get_instance();
                                    $reviews = $review_obj->fetch($query_args);
                                    $reviews = $reviews['data'];
                                    $review_data = array();

                                    // Get total reviews
                                    $total_reviews = count(get_comments($total_args));
                                    // Get review pages
                                    $review_pages  =   ceil($total_reviews/$query_args['number']);
                                    $query_args['total'] = $review_pages;

                                    if(!empty($reviews)):
                                        foreach($reviews as $key => $value) {
                                            $review_data[] = $value;
                                            ?>
                                                <li id="review-<?php echo $value->comment_ID; ?>" class="review-item clearfix">
                                                    <div class="image-avatar">
                                                        <?php echo $value->avatar_user; ?>
                                                    </div>
                                                    <div class="profile-viewer">
                                                        <a href="<?php echo $value->author_data->author_url; ?>" class="name-author">
                                                            <?php echo $value->author_data->display_name; ?>
                                                        </a>
                                                        <p class="review-time"><?php echo $value->date_ago; ?></p>
                                                        <div class="rate-it star" data-score="<?php echo $value->et_rate; ?>"></div>
                                                        <div class="commnet-content"><?php echo $value->comment_content;  ?></div>
                                                    </div>
                                                </li>
                                            <?php
                                        }

                                        endif; ?>
                            </ul>

                            <div class="paginations-wrapper" >
                                <?php
                                if($review_pages > 1) {
                                    ae_comments_pagination($review_pages, $paged ,$query_args);
                                }
                                ?>
                            </div>
                            <?php echo '<script type="json/data" class="review-data" > ' . json_encode($review_data) . '</script>'; ?>
                        </div>
                </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 aside-detail-bar">
                        <div class="box-aside blog-detail">

                            <?php if( is_super_admin() || $user_ID == $current->post_author ): ?>
                                <div class="text-center mjob-status <?php echo $current->post_status; ?>-text">
                                    <?php echo $current->status_text; ?>
                                </div>
                            <?php endif; ?>

                            <div class="package-statistic">
                                <span class="price"><?php echo $current->et_budget_text; ?></span>
                                <div class="vote">
                                    <div class="rate-it" data-score="<?php echo round($current->rating_score, 1); ?>"></div>
                                    <span class="total-review"><?php printf('(%s)',  $current->mjob_total_reviews); ?></span>
                                </div>
                                <div class="text-content">
                                    <ul>
                                        <li>
                                           <span><i class="fa fa-star"></i><?php _e('Overall rate', 'enginethemes'); ?></span>
                                           <div class="total-number"><?php echo round($current->rating_score, 1); ?></div>
                                        </li>
                                        <li>
                                            <span><i class="fa fa-commenting"></i><?php _e('Reviews', 'enginethemes'); ?></span>
                                            <div class="total-number"><?php echo $count_review; ?></div>
                                        </li>
                                        <li>
                                            <span><i class="fa fa-shopping-cart"></i><?php _e('Sales', 'enginethemes'); ?></span>
                                            <div class="total-number"><?php echo mje_get_mjob_order_count($current->ID); ?></div>
                                        </li>
                                        <li>
                                            <span><i class="fa fa-calendar"></i><?php _e('Time of delivery', 'enginethemes'); ?></span>
                                            <div class="total-number time-delivery-label"><?php echo $current->time_delivery; ?> day(s)</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="action">
                                <?php if( $user_ID != $current->post_author ){ ?>
                                <button class="<?php mje_button_classes( array( 'btn-order', 'btn-order-aside-bar', 'waves-effect', 'waves-light' ) ) ?> <?php echo $disableClass; ?>" ><?php echo sprintf(__('ORDER NOW (<span class="mjob-price mje-price-text">%s</span>)', 'enginethemes'), $current->et_budget_text) ; ?></button>
                                <?php 
								}
								else{
								  /**
								  * Add button claim this job
								  *
								  * @since 1.3.1
								  * @author Tan Hoai
								  */
								 do_action('mje_seller_mjob_button');
								}
								?>
                                <button class="btn-bookmark"><i class="fa fa-heart"></i></button>
                            </div>
                            <div class="add-extra mjob-add-extra">
                                <span class="extra"><?php _e('EXTRA', 'enginethemes') ;?></span>
                                <div class="extra-container">
                                    <?php get_template_part('template/list', 'extras'); ?>
                                </div>
                            </div>
                            <div class="custom-order-link">
                            <?php
                                if($user_ID != $user_author)
                                {
                                    $conversation_parent = 0;
                                    $conversation_guid = '';
                                    if($conversation = mje_get_conversation($user_ID, $user_author)) {
                                        $conversation_parent = $conversation[0]->ID;
                                        $conversation_guid = $conversation[0]->guid;
                                    }
                            ?>
                                <div>
                                    <a id="<?php echo $customOrderId; ?>" data-mjob-name="<?php echo $post->post_title; ?>" data-mjob="<?php echo $post->ID ?>" data-conversation-guid="<?php echo $conversation_guid; ?>" data-conversation-parent="<?php echo $conversation_parent; ?>" data-to-user="<?php echo $user_author; ?>" data-from-user="<?php echo $user_ID ?>" style="cursor: pointer"><?php _e('Send custom order','enginethemes'); ?><i class="fa fa-paper-plane"></i></a>
                                </div>
                            <?php
                                }
                            ?>
                            </div>
                        </div>
                        <?php
                            if($current->opening_message && $current->opening_message != '' && ($user_ID == $user_author || is_super_admin())) {
                        ?>
                            <div class="box-aside opening-message">
                                <div class="aside-title">
                                    <?php _e('Opening Message', 'enginethemes') ?> <i class="fa fa-question-circle popover-opening-message" style="cursor: pointer" aria-hidden="true"></i>
                                </div>
                                <div class="content">
                                    <?php
                                    $opening_message = wpautop($current->opening_message);
                                    $num_opening_message = str_word_count($opening_message);
                                    if($num_opening_message > 40) {
                                        ?>
                                        <div class="content-opening-message hide-content gradient">
                                            <?php
                                                echo $opening_message;
                                            ?>
                                        </div>
                                        <a class="show-opening-message"><?php _e('Show more', 'enginethemes') ?></a>
                                        <?php
                                    } else {
                                        echo '<div class="content-opening-message">';
                                            echo $opening_message;
                                        echo '</div>';
                                        echo '<a class="show-opening-message"></a>';

                                        }
                                    ?>
                                </div>
                            </div>
                        <?php
                            }
                        ?>

                        <?php

                        ?>

                        <?php get_sidebar('single-profile'); ?>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="amount" value="<?php echo $current->et_budget ?>">
    </div>
   <?php
        get_template_part('template/modal-send-custom-order');
    ?>

<?php
get_footer();
