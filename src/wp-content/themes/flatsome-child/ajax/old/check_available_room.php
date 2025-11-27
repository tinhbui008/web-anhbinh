<?php
// AJAX
add_action( 'wp_ajax_check_available_room', 'check_available_room_init' );
add_action( 'wp_ajax_nopriv_check_available_room', 'check_available_room_init' );
function check_available_room_init() { 
    $is_booking = true;
    $text_alert = "";

    if (isset($_POST['checkinTime']) && isset($_POST['checkoutTime']) && isset($_POST['idProduct'])) {
        $checkinTime = date('d/m/Y',$_POST['checkinTime']);
        $checkoutTime = date('d/m/Y',$_POST['checkoutTime']);
        $idProduct = $_POST['idProduct'];
        $quantity = $_POST['quantity'];        
        $totalRooms = get_post_meta($idProduct, 'total_rooms', true);

        //## get info product by id
        //$product = wc_get_product($idProduct);

        // ### Check available room
        $start_date = new DateTime(str_replace('/','-',$checkinTime)); // Ngày bắt đầu
        $end_date = new DateTime(str_replace('/','-',$checkoutTime));   // Ngày kết thúc
        //$end_date->modify('+1 day'); // Để bao gồm ngày kết thúc trong vòng lặp
        
        $interval = new DateInterval('P1D'); // Khoảng thời gian là 1 ngày
        $date_range = new DatePeriod($start_date, $interval, $end_date);

        $arr_dates = [];
        foreach ($date_range as $date) {
            $date_tmp = $date->format('d/m/Y');

            $args = array(
                'post_type' => 'history-order', // Replace with your custom post type
                'posts_per_page' => -1, // -1 to get all posts, adjust as needed
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'history_date', // Replace with your custom field key
                        'value' => $date_tmp, // Replace with the value you're looking for
                        'compare' => '=', // Comparison operator (can be '=', '!=', 'LIKE', etc.)
                    ),
                    array(
                        'key' => 'history_product_id', // Custom field 2 key
                        'value' => $idProduct, // Custom field 2 value
                        'compare' => '=', // Comparison operator
                    ),
                    array(
                        'relation' => 'OR',
                        array(
                            'key' => 'history_status', // Custom field 2 key
                            'value' => 'on-hold', // Custom field 2 value
                            'compare' => '=', // Comparison operator
                        ),
                        array(
                            'key' => 'history_status', // Custom field 2 key
                            'value' => 'processing', // Custom field 2 value
                            'compare' => '=', // Comparison operator
                        ),
                    ),
                ),
            );
            $query = new WP_Query($args);

            if($query->have_posts()){
                $total_quantity_ordered = 0;
                while ($query->have_posts()) : $query->the_post();
                    $id = get_the_ID();
                    $total_quantity_ordered += get_field('history_quantity',$id);
                endwhile;
                wp_reset_postdata();

                $arr_dates[$date_tmp]['totalRooms'] = $totalRooms;
                $arr_dates[$date_tmp]['booked'] = $total_quantity_ordered;
                $arr_dates[$date_tmp]['available'] = $totalRooms - $total_quantity_ordered;
                $arr_dates[$date_tmp]['ordering'] = $quantity;

                if($arr_dates[$date_tmp]['ordering']>$arr_dates[$date_tmp]['available']){
                    $is_booking = false;
                }
            }else if($quantity>$totalRooms){
                $is_booking = false;
            }
        }

        if($is_booking){
            wp_send_json_success('Successfully');
        }else{
            wp_send_json_error(pll('部屋が利用できません','The room is not available'));
        }
    }

    wp_die();//bắt buộc phải có khi kết thúc
}