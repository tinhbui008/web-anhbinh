<?php
// AJAX
add_action( 'wp_ajax_create_session_booking', 'create_session_booking_init' );
add_action( 'wp_ajax_nopriv_create_session_booking', 'create_session_booking_init' );
function create_session_booking_init() { 
    
    // ### Check booking submit
    if (isset($_POST['arr_rooms']) && is_array($_POST['arr_rooms'])) {
        $checkinTime = $_POST['checkinTime'];
        $checkoutTime = $_POST['checkoutTime'];
        $nights = $_POST['nights'];
        $arr_rooms = $_POST['arr_rooms'];

        // Set session variables
        $_SESSION['booking'] = array(
            'checkinTime' => $checkinTime,
            'checkoutTime' => $checkoutTime,
            'fromDate' => date('d/m/Y',$checkinTime),
            'toDate' => date('d/m/Y',$checkoutTime),
            'nights' => $nights,
            'rooms' => $arr_rooms,
            'totalPrice' => 0,
            'totalRoom' => 0
        );

        // get product information
        foreach($arr_rooms as $k=>$v){
            $id = $v['id']; // Replace with the actual product ID
            $quantity = $v['quantity']; // Replace with the actual product ID

            $product = wc_get_product( $id );
            $price = $product->get_price();
            $roomPrice = $price*$quantity*$nights;

            $_SESSION['booking']['rooms'][$k]['price'] = $roomPrice;
            $_SESSION['booking']['totalPrice'] += $roomPrice;
            $_SESSION['booking']['totalRoom'] += $v['quantity'];
        }    

        // Send a response back
        wp_send_json_success('Array received successfully');
    } else {
        wp_send_json_error('No array data received');
    }

    wp_die();//bắt buộc phải có khi kết thúc
}