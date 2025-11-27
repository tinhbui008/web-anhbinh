<?php

add_shortcode( 'filter_booking_shortcode', 'filter_booking' );

function filter_booking() {
    ob_start();
?>

    <div class="filter-booking-section">
        <div class="filter-booking-container">
            <!-- CHECK IN -->
            <div class="filter-booking-item">
                <span class=""><svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12.3333 1.66669V5.00002M5.66667 1.66669V5.00002M1.5 8.33335H16.5M3.16667 3.33335H14.8333C15.7538 3.33335 16.5 4.07955 16.5 5.00002V16.6667C16.5 17.5872 15.7538 18.3334 14.8333 18.3334H3.16667C2.24619 18.3334 1.5 17.5872 1.5 16.6667V5.00002C1.5 4.07955 2.24619 3.33335 3.16667 3.33335Z" stroke="#111111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><?=pll('チェックイン','Check in')?></span>
                <div class="filter-booking-input">
                    <input id="timecheckin" type="text" placeholder="" class="" require value="<?=date('d/m/Y', time())?>">
                    <span class="filter-booking-icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M13.3333 1.66669V5.00002M6.66667 1.66669V5.00002M2.5 8.33335H17.5M4.16667 3.33335H15.8333C16.7538 3.33335 17.5 4.07955 17.5 5.00002V16.6667C17.5 17.5872 16.7538 18.3334 15.8333 18.3334H4.16667C3.24619 18.3334 2.5 17.5872 2.5 16.6667V5.00002C2.5 4.07955 3.24619 3.33335 4.16667 3.33335Z" stroke="#86C61C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
                </div>
            </div>

            <!-- CHECK OUT -->
            <div class="filter-booking-item">
                <span class=""><svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12.3333 1.66669V5.00002M5.66667 1.66669V5.00002M1.5 8.33335H16.5M3.16667 3.33335H14.8333C15.7538 3.33335 16.5 4.07955 16.5 5.00002V16.6667C16.5 17.5872 15.7538 18.3334 14.8333 18.3334H3.16667C2.24619 18.3334 1.5 17.5872 1.5 16.6667V5.00002C1.5 4.07955 2.24619 3.33335 3.16667 3.33335Z" stroke="#111111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><?=pll('チェックアウト','Check out')?></span>
                <div class="filter-booking-input">
                    <input id="timecheckout" type="text" placeholder="" class="" require value="<?=date('d/m/Y', strtotime('+1 day'))?>">
                    <span class="filter-booking-icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M13.3333 1.66669V5.00002M6.66667 1.66669V5.00002M2.5 8.33335H17.5M4.16667 3.33335H15.8333C16.7538 3.33335 17.5 4.07955 17.5 5.00002V16.6667C17.5 17.5872 16.7538 18.3334 15.8333 18.3334H4.16667C3.24619 18.3334 2.5 17.5872 2.5 16.6667V5.00002C2.5 4.07955 3.24619 3.33335 4.16667 3.33335Z" stroke="#86C61C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
                </div>
            </div>

            <!-- ADULT -->
            <div class="filter-booking-item">
                <span class=""><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M16.6666 17.5V15.8333C16.6666 14.9493 16.3155 14.1014 15.6903 13.4763C15.0652 12.8512 14.2174 12.5 13.3333 12.5H6.66665C5.78259 12.5 4.93474 12.8512 4.30962 13.4763C3.6845 14.1014 3.33331 14.9493 3.33331 15.8333V17.5M13.3333 5.83333C13.3333 7.67428 11.8409 9.16667 9.99998 9.16667C8.15903 9.16667 6.66665 7.67428 6.66665 5.83333C6.66665 3.99238 8.15903 2.5 9.99998 2.5C11.8409 2.5 13.3333 3.99238 13.3333 5.83333Z" stroke="#111111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><?=pll('大人','Adult')?></span>
                <div class="filter-booking-input">
                    <select name="" id="">
                        <?php for($i=1;$i<=5;$i++):?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?php endfor;?>
                    </select>
                    <span class="filter-booking-icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M5 7.5L10 12.5L15 7.5" stroke="#111111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
                </div>
            </div>

            <!-- CHILD -->
            <div class="filter-booking-item">
                <span class=""><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M16.6666 17.5V15.8333C16.6666 14.9493 16.3155 14.1014 15.6903 13.4763C15.0652 12.8512 14.2174 12.5 13.3333 12.5H6.66665C5.78259 12.5 4.93474 12.8512 4.30962 13.4763C3.6845 14.1014 3.33331 14.9493 3.33331 15.8333V17.5M13.3333 5.83333C13.3333 7.67428 11.8409 9.16667 9.99998 9.16667C8.15903 9.16667 6.66665 7.67428 6.66665 5.83333C6.66665 3.99238 8.15903 2.5 9.99998 2.5C11.8409 2.5 13.3333 3.99238 13.3333 5.83333Z" stroke="#111111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><?=pll('子供','Child')?></span>
                <div class="filter-booking-input">
                    <select name="" id="">
                        <?php for($i=1;$i<=5;$i++):?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?php endfor;?>
                    </select>
                    <span class="filter-booking-icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M5 7.5L10 12.5L15 7.5" stroke="#111111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
                </div>
            </div>

            <!-- BOOKING -->
            <div class="filter-booking-item">
                <span class=""></span>
                <span class="filter-booking-btn" slug="<?=get_permalink(original_obj(9, 'page'))?>"><?=pll('予約する','BOOK NOW')?></span>
            </div>

        </div>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
}


add_shortcode( 'reservation_shortcode', 'reservation_init' );

function reservation_init() {
    ob_start();
?>
    <div class="reservation-contain">
        <p class="reservation-title"><?=pll('オンライン予約','Online Reservastion')?></p>
        <!-- CHECK IN -->
        <div class="filter-booking-item">
            <span class=""><svg width="15" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12.3333 1.66669V5.00002M5.66667 1.66669V5.00002M1.5 8.33335H16.5M3.16667 3.33335H14.8333C15.7538 3.33335 16.5 4.07955 16.5 5.00002V16.6667C16.5 17.5872 15.7538 18.3334 14.8333 18.3334H3.16667C2.24619 18.3334 1.5 17.5872 1.5 16.6667V5.00002C1.5 4.07955 2.24619 3.33335 3.16667 3.33335Z" stroke="#111111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><?=pll('チェックイン','Check in')?></span>
            <div class="filter-booking-input">
                <input id="timecheckin" type="text" placeholder="" class="" require>
                <span class="filter-booking-icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M13.3333 1.66669V5.00002M6.66667 1.66669V5.00002M2.5 8.33335H17.5M4.16667 3.33335H15.8333C16.7538 3.33335 17.5 4.07955 17.5 5.00002V16.6667C17.5 17.5872 16.7538 18.3334 15.8333 18.3334H4.16667C3.24619 18.3334 2.5 17.5872 2.5 16.6667V5.00002C2.5 4.07955 3.24619 3.33335 4.16667 3.33335Z" stroke="#86C61C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
            </div>
        </div>
        <!-- CHECK OUT -->
        <div class="filter-booking-item">
            <span class=""><svg width="15" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M12.3333 1.66669V5.00002M5.66667 1.66669V5.00002M1.5 8.33335H16.5M3.16667 3.33335H14.8333C15.7538 3.33335 16.5 4.07955 16.5 5.00002V16.6667C16.5 17.5872 15.7538 18.3334 14.8333 18.3334H3.16667C2.24619 18.3334 1.5 17.5872 1.5 16.6667V5.00002C1.5 4.07955 2.24619 3.33335 3.16667 3.33335Z" stroke="#111111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><?=pll('チェックアウト','Check out')?></span>
            <div class="filter-booking-input">
                <input id="timecheckout" type="text" placeholder="" class="" require>
                <span class="filter-booking-icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M13.3333 1.66669V5.00002M6.66667 1.66669V5.00002M2.5 8.33335H17.5M4.16667 3.33335H15.8333C16.7538 3.33335 17.5 4.07955 17.5 5.00002V16.6667C17.5 17.5872 16.7538 18.3334 15.8333 18.3334H4.16667C3.24619 18.3334 2.5 17.5872 2.5 16.6667V5.00002C2.5 4.07955 3.24619 3.33335 4.16667 3.33335Z" stroke="#86C61C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
            </div>
        </div>
        <!-- BOOKING -->
        <div class="filter-booking-item">
            <span class="filter-booking-btn" slug="<?=get_permalink(original_obj(9, 'page'))?>"><?=pll('予約する','BOOK NOW')?></span>
        </div>
    </div>
<?php 
        $output = ob_get_clean(); 
        return $output;
}

//### TẠO NÚT ADD TO CART CUSTOME
function custom_add_to_cart_button($atts) {
    // Thiết lập các giá trị mặc định
    $atts = shortcode_atts(
        array(
            'id' => '',
        ), $atts, 'custom_add_to_cart');

    // Kiểm tra nếu có ID sản phẩm
    if (empty($atts['id'])) {
        return 'Vui lòng chỉ định ID sản phẩm.';
    }

    // Tạo nút Add to Cart
    return do_shortcode('[add_to_cart id="' . $atts['id'] . '"]');
}

// Đăng ký shortcode
add_shortcode('custom_add_to_cart', 'custom_add_to_cart_button');


//### XỬ LÝ ADD TO CART
add_action('woocommerce_after_single_product', 'custom_add_to_cart_functionality');
function custom_add_to_cart_functionality() {
    // Bạn có thể thực hiện các hành động tùy chỉnh ở đây sau khi người dùng nhấn nút đặt hàng.
}