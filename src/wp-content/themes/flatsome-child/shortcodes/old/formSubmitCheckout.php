<?php
add_shortcode( 'checkoutForm_shortcode', 'checkoutForm_data' );
function checkoutForm_data() {
    ob_start();

    if(isset($_POST['booking-submit'])){
        $dev_date = $_SESSION['booking'];
        $nights = $dev_date['nights'];
        $checkinTime = $dev_date['checkinTime'];
        $checkoutTime = $dev_date['checkoutTime'];
        $fromDate = $dev_date['fromDate'];
        $toDate = $dev_date['toDate'];

        $fullname = sanitize_text_field($_POST['booking-ho']).' '.sanitize_text_field($_POST['booking-ten']);
        $email = sanitize_email($_POST['booking-email']);
        $phone = sanitize_text_field($_POST['booking-sodienthoai']);
        //$address = sanitize_textarea_field($_POST['address']);
        //$payment_method = sanitize_text_field($_POST['payment_method']);
        $payment_method = 'cod';
        $custom_total = 0;

        // Tạo đơn hàng WooCommerce
        $order = wc_create_order();
        $order->set_address([
            'first_name' => $fullname,
            'email'      => $email,
            'phone'      => $phone,
            'address_1'  => $address
        ], 'billing');

        foreach($dev_date['rooms'] as $k=>$v){
            $order->add_product(wc_get_product($v['id']), $v['quantity']);

            $product = wc_get_product( $v['id'] );
            $price = $product->get_price();
            $roomPrice = $price*$v['quantity']*$nights;
            $custom_total += $roomPrice;
        }
        
        $order->set_total($custom_total);
        //$order->calculate_totals();
        $order->set_status('processing');
        $order->set_payment_method($payment_method);
        $order->set_payment_method_title('Cash payment');

        // Add Custom Field to Order Meta
        $order_ID = $order->get_id();
        update_post_meta($order_ID, 'custom_order_nights', $nights);
        update_post_meta($order_ID, 'custom_order_fromdate', $fromDate);
        update_post_meta($order_ID, 'custom_order_todate', $toDate);
        update_post_meta($order_ID, 'custom_order_fromtime', $_POST['booking-checkintime']);
        update_post_meta($order_ID, 'custom_order_comment', $_POST['booking-loinhan']);

        update_post_meta($order_ID, 'custom_order_checkintime', $checkinTime);
        update_post_meta($order_ID, 'custom_order_checkouttime', $checkoutTime);

        //Save Order
        $order->save();

        // ###inser row to history order

        // get list date to insert row history
        $start_date = new DateTime(str_replace('/','-',$fromDate)); // Ngày bắt đầu
        $end_date = new DateTime(str_replace('/','-',$toDate));   // Ngày kết thúc
        //$end_date->modify('+1 day'); // Để bao gồm ngày kết thúc trong vòng lặp

        $interval = new DateInterval('P1D'); // Khoảng thời gian là 1 ngày
        $date_range = new DatePeriod($start_date, $interval, $end_date);

        foreach($dev_date['rooms'] as $k=>$v){
            // foreach list date
            foreach($date_range as $date){
                $date_insert = $date->format('d/m/Y');

                $post_data = array(
                    'post_title'    => $order_ID.'-'.$v['id'].'-'.$date_insert, // Title of the post
                    'post_content'  => '', // Content of the post
                    'post_status'   => 'publish', // Post status (draft, publish, etc.)
                    'post_author'   => 1, // Author ID
                    'post_type'     => 'history-order', // Custom post type slug (replace 'movie' with your CPT slug)
                    'post_date'     => current_time('mysql'), // Current timestamp
                );
                
                // Insert the post into the database
                $post_id = wp_insert_post($post_data);

                // Optionally, add custom fields or meta
                if ($post_id) {
                    update_post_meta($post_id, 'history_order_id', $order_ID);
                    update_post_meta($post_id, 'history_product_id', $v['id']);
                    update_post_meta($post_id, 'history_quantity', $v['quantity']);
                    update_post_meta($post_id, 'history_status', 'processing');
                    update_post_meta($post_id, 'history_date', $date_insert);
                }
            }
        }


        // flush booking session
        unset($_SESSION['booking']);

        // Chuyển hướng đến trang cảm ơn
        if($_POST['lang'] == "en_US")
        {
            //wp_redirect(site_url('en/success-page/'));
            wp_redirect(get_the_permalink(original_obj(1886, 'page')));
        }
        else
        {
            //wp_redirect(site_url('/success-page/'));
            wp_redirect(get_the_permalink(original_obj(1886, 'page')));
        }
        exit;
    }
?>

<form method="POST" id="checkoutpage-form" class="container-checkoutForm" action="<?=get_the_permalink(original_obj(1837, 'page'))?>">
    <h2><?=pll('ゲスト情報','Guest information')?></h2>
    <p><?=pll('個人情報を入力して予約を完了してください。直接当社にご予約いただくと、最安価格でご宿泊いただけます。','Complete your reservation with your personal details. Book directly with us for your stay at the best price.')?></p>
    
    <div class="input-row">
        <div>
            <label for="last-name"><?=pll('ファーストネーム','First name')?> *</label>
            <input type="text" id="last-name" required name="booking-ho">
        </div>
        <div>
            <label for="first-name"><?=pll('苗字','Last name')?> *</label>
            <input type="text" id="first-name" required name="booking-ten">
        </div>
    </div>
    
    <div class="input-row">
        <div>
            <label for="email"><?=pll('電子メール','Email')?></label>
            <input type="email" id="email" name="booking-email">
        </div>
        <div>
            <label for="phone"><?=pll('電話番号','Phone number')?> *</label>
            <input type="number" id="phone" required name="booking-sodienthoai">
        </div>
    </div>
    
    <!-- <label for="country">Quốc gia *</label>
    <select id="country">
        <option value="Vietnam">Vietnam</option>
        <option value="USA">USA</option>
        <option value="UK">UK</option>
        <option value="France">France</option>
    </select> -->
    
    <label for="arrival-time"><?=pll('チェックイン時間','Check-in time')?> <span class="optional">(<?=pll('オプション','Options')?>)</span></label>
    <select id="arrival-time" name="booking-checkintime">
        <option value="14:00">14:00</option>
        <option value="15:00">15:00</option>
        <option value="16:00">16:00</option>
    </select>
    
    <label for="requests"><?=pll('個人的なリクエストはありますか？','Do you have any personal requests?')?> <span class="optional">(<?=pll('オプション','Options')?>)</span></label>
    <textarea id="requests" rows="3" name="booking-loinhan"></textarea>

    <div class="booking-checkout-submit">
        <input type="submit" value="<?=pll('今すぐ予約','Booking now')?>" name="booking-submit">
    </div>
</form>

<?php 
    $output = ob_get_clean(); 
    return $output;
}