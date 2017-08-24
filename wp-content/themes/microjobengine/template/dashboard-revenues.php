<?php
    global $user_ID;
    $revenues = ae_credit_balance_info($user_ID);
    $total_earned = mje_format_price($revenues['working']->balance + $revenues['available']->balance +$revenues['freezable']->balance);
?>
<div class="revenues box-shadow">
    <div class="title"><?php _e('Revenues', 'enginethemes'); ?></div>
    <div class="line">
        <span class="line-distance"></span>
    </div>
    <ul class="row">
        <li class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <p class="cate"><?php _e('Working', 'enginethemes'); ?></p>
            <p class="currency"><?php echo $revenues['working_text']; ?></p>
        </li>

        <li class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <p class="cate"><?php _e('Available', 'enginethemes'); ?></p>
            <p class="currency available-text"><?php echo $revenues['available_text']; ?></p>
        </li>

        <li class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <p class="cate"><?php _e('Pending', 'enginethemes'); ?></p>
            <p class="currency freezable-text"><?php echo $revenues['freezable_text']; ?></p>
        </li>
    </ul>
    <div class="balance-withdraw">
        <p class="currency-balance"><span><?php _e('Balance:', 'enginethemes'); ?></span><span class="price-balance"><?php echo $total_earned; ?></span></p>
        <p class="note-balance"><?php _e('(Working + Available + Pending)', 'enginethemes'); ?></p>
    </div>

    <div class="balance-checkout">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 withdrew-text">
                <span class="total-currency"><?php _e('Withdrawn:', 'enginethemes'); ?></span>
                <span class="mje-price-text"> <?php echo $revenues['withdrew_text']; ?></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 withdrew-text">
                <span class="total-currency"><?php _e('Spent:', 'enginethemes'); ?></span>
                <span class="mje-price-text"><?php echo $revenues['checkout_text']; ?></span>
            </div>
        </div>
    </div>
</div>