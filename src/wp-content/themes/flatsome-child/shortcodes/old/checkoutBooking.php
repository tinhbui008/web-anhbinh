
<?php

add_shortcode( 'checkoutBooking_shortcode', 'checkoutBooking_data' );
function checkoutBooking_data() {
    ob_start();

    // ### CONVERT DATE : functions.php
    //$dev_date = convertDate();

    $dev_date = $_SESSION['booking'];
?>

<div class="dev-booking-right">
    <!-- TITLE -->
    <p class="dev-booking-right-title"><?=pll('予約情報','Reservation information')?></p>
    <!-- INFO CHECKIN -->
    <div class="dev-booking-right-info">
        <div class="dev-booking-right-checkinout">
            <!-- CHECK IN -->
            <div class="dev-booking-right-checkin">
                <span class=""><svg width="16" height="16" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12.3333 1.66669V5.00002M5.66667 1.66669V5.00002M1.5 8.33335H16.5M3.16667 3.33335H14.8333C15.7538 3.33335 16.5 4.07955 16.5 5.00002V16.6667C16.5 17.5872 15.7538 18.3334 14.8333 18.3334H3.16667C2.24619 18.3334 1.5 17.5872 1.5 16.6667V5.00002C1.5 4.07955 2.24619 3.33335 3.16667 3.33335Z" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><?=pll('チェックイン','Check in')?></span>
                <p><?=date('d/m/Y',$dev_date['checkinTime'])?></p>
            </div>
            <!-- CHECK OUT -->
            <div class="dev-booking-right-checkin">
                <span class=""><svg width="16" height="16" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12.3333 1.66669V5.00002M5.66667 1.66669V5.00002M1.5 8.33335H16.5M3.16667 3.33335H14.8333C15.7538 3.33335 16.5 4.07955 16.5 5.00002V16.6667C16.5 17.5872 15.7538 18.3334 14.8333 18.3334H3.16667C2.24619 18.3334 1.5 17.5872 1.5 16.6667V5.00002C1.5 4.07955 2.24619 3.33335 3.16667 3.33335Z" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><?=pll('チェックアウト','Check out')?></span>
                <p><?=date('d/m/Y',$dev_date['checkoutTime'])?></p>
            </div>
        </div>
        <!-- SỐ ĐÊM NGHỈ -->
        <div class="dev-booking-nights"><p><?=pll('泊数','Number of nights');?>:</p> <span><?=$dev_date['nights']?></span></div>
        <!-- SỐ LƯỢNG PHÒNG -->
        <div class="dev-booking-rooms"><p><?=pll('部屋数','Number of rooms');?>:</p> <span><?=$dev_date['totalRoom']?></span></div>
        <!-- THÔNG TIN LIST BOOKING -->
        <div id="dev-showListBooking">
            <?php foreach($dev_date['rooms'] as $k=>$v):
                $product = wc_get_product( $v['id'] );  
                $title = $product->get_title();  
                $adults = get_field('adults',$v['id']);
            ?>
            <div class="dev-listbooking">
                <div class="dev-listbooking-title"><?=$title?> <span>(x<?=$v['quantity']?>)</span></div>
                <div class="dev-listbooking-adults"><?=$adults?> <?=pll('大人','Adults')?></div>
                <div class="dev-listbooking-price"><?=number_format($v['price'],0,',','.')?> đ</div>
            </div>
            <?php endforeach;?>
        </div>
        <!-- SVG LOADING -->
		<div id="loading-booking" class="loading-hidden-booking"><div><img src="<?=IMG?>/images/loading.svg" alt="loading"></div></div>	
        <!-- TOTAL PRICE ALL -->
        <div id="dev-booking-total" class="">
            <div class="dev-booking-total-title"><span><?=pll('合計','Total')?></span><span id="dev-booking-totalPrice"><?=number_format($dev_date['totalPrice'],0,',','.')?> đ</span></div>
        </div>
    </div>
</div>

<?php 
    $output = ob_get_clean(); 
    return $output;
}