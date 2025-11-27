<?php
// HIDDEN BUTTON LOGIN OPENID
function hide_openid_connect_button() {
    ?>
    <style>
        .openid-connect-login-button {
            display: none !important;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'hide_openid_connect_button');

// SEARCH CHANGE POST TYPE TO POST NOT PRODUCT
function filter_search_post_types($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', 'post'); // Chỉ hiển thị kết quả từ bài viết (post)
    }
    return $query;
}
add_filter('pre_get_posts','filter_search_post_types');

// PLL TEXT LANGUAGE
function pll($str_jp,$str_en)
{
    if(get_locale() == "en_US")
    {
        return $str_en;
    }
    else
    {
        return $str_jp;
    }
}


function GetCurrentWeekday($date) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $weekday = date("l", $date);
    $weekday = strtolower($weekday);
    switch($weekday) {
        case 'monday':
            $weekday = 'Thứ hai';
            break;
        case 'tuesday':
            $weekday = 'Thứ ba';
            break;
        case 'wednesday':
            $weekday = 'Thứ tư';
            break;
        case 'thursday':
            $weekday = 'Thứ năm';
            break;
        case 'friday':
            $weekday = 'Thứ sáu';
            break;
        case 'saturday':
            $weekday = 'Thứ bảy';
            break;
        default:
            $weekday = 'Chủ nhật';
            break;
    }
    return $weekday;
}


// ### CONVERT DATE 
// function convertDate(){    

//     $result['date_from'] = (isset($_GET['from']) && $_GET['from']!='') ? $_GET['from'] : date('d-m-Y', time());
//     $result['date_to'] = (isset($_GET['to']) && $_GET['to']!='') ? $_GET['to'] : date('d-m-Y', strtotime('+1 day'));

//     $result['time_from'] = strtotime($result['date_from']);
//     $result['time_to'] = strtotime($result['date_to']);

//     $result['number_of_nights'] =  ceil(($result['time_to'] - $result['time_from']) / 86400);
//     // ?from=15-03-2025&to=18-03-2025

//     return $result;
// }

// PLL TEXT LANGUAGE

//### set original object
function original_obj($id, $type){
    return (int)apply_filters( 'wpml_object_id', $id, $type, true, 'vi' );
}

function current_obj($id, $type){
    return (int)apply_filters( 'wpml_object_id', $id, $type, true);
}

function lang_obj($id, $type, $lang='vi'){
    return (int)apply_filters( 'wpml_object_id', $id, $type, true, $lang);
}

// function redirect_page($idPage_jp,$idPage_en)
// {
//     if(get_locale() == "en_US")
//     {
//         return get_the_permalink($idPage_en);
//     }
//     else
//     {
//         return get_the_permalink($idPage_jp);
//     }
// }


// AOS INIT CUSTOM
add_filter( 'aos_init', 
    function($aos_init) {
        return '
            var aoswp_params = {
                "offset":"50",
                "duration":"1000",
                "delay":"0",
                "once": false
            };
        ';
    } 
);

// HIDDEN ADMINBAR FOR NOT ADMIN 
function disable_admin_bar_for_non_admins() {
    if (!current_user_can('administrator')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'disable_admin_bar_for_non_admins');


// LẤY BÀI VIẾT TRƯỚC BÀI HIỆN TẠI
function get_previous_post_same_category_by_id($current_post_id) {
    // Lấy object bài viết hiện tại
    $current_post = get_post($current_post_id);

    if (!$current_post) return null;

    // Lấy danh sách category (danh mục cấp) của bài hiện tại
    $categories = wp_get_post_categories($current_post_id);

    if (empty($categories)) return null;

    // Truy vấn bài viết trước
    $args = [
        'posts_per_page' => 1,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        //'orderby'        => 'ID',
        'order'          => 'DESC',
        'category__in'   => $categories,
        'post__lt'       => $current_post_id, // Lấy bài có ID nhỏ hơn hiện tại (bài viết trước)
        'fields'         => 'all',
        'ignore_sticky_posts' => 1,
    ];

    $query = new WP_Query($args);

    return $query->have_posts() ? $query->posts[0] : null;
}

// LẤY BÀI VIẾT SAU BÀI HIỆN TẠI
function get_next_post_same_category_by_id($current_post_id) {
    $current_post = get_post($current_post_id);

    if (!$current_post) return null;

    $categories = wp_get_post_categories($current_post_id);

    if (empty($categories)) return null;

    $args = [
        'posts_per_page' => 1,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        //'orderby'        => 'ID',
        'order'          => 'ASC', // Đảo ngược thứ tự để lấy bài sau
        'category__in'   => $categories,
        'post__gt'       => $current_post_id, // Lấy bài có ID lớn hơn (bài sau)
        'fields'         => 'all',
        'ignore_sticky_posts' => 1,
    ];

    $query = new WP_Query($args);

    return $query->have_posts() ? $query->posts[0] : null;
}


function convertSeconds($seconds) {
    if (!is_numeric($seconds) || $seconds < 0) {
        return "Số giây không hợp lệ";
    }

    if ($seconds >= 3600) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;
        return "{$hours} giờ {$minutes} phút {$remainingSeconds} giây";
    } elseif ($seconds >= 60) {
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        return "{$minutes} phút {$remainingSeconds} giây";
    } else {
        return "{$seconds} giây";
    }
}

// Sắp xếp bài viết dựa theo lượt bình chọn
function sort_archive_by_luot_binh_chon( $query ) {
    // Kiểm tra: frontend, truy vấn chính, và trang category archive
    if ( !is_admin() && $query->is_main_query() && $query->is_category() ) {
        $cat_obj = get_queried_object(); // Lấy object của danh mục hiện tại
        if ( $cat_obj && isset($cat_obj->term_id) ) {
            $current_cat_id = $cat_obj->term_id;
            $parent_cat_id = 96; // ID danh mục cha bạn muốn kiểm tra

            // Kiểm tra nếu danh mục hiện tại là 96 hoặc là con của 96
            if ( $current_cat_id == $parent_cat_id || cat_is_ancestor_of($parent_cat_id, $current_cat_id) ) {
                $query->set( 'meta_key', 'luot_binh_chon' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
            }
        }
    }
}
add_action( 'pre_get_posts', 'sort_archive_by_luot_binh_chon' );
