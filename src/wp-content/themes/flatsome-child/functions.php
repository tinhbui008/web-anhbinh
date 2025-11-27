<?php
// Start the session if not already started
if (!session_id()) {
    session_start();
}

function dd($arr){
    echo "<pre>";
    var_dump($arr);
    echo "</pre>";
    die();
}


// IMG PATH
define('IMG', get_template_directory_uri());

/* config pusher */
function get_pusher_config() {
    if (function_exists('spc_get_pusher_config')) {
        return spc_get_pusher_config();
    }

    return [
        'app_id'  => '',
        'key'     => '',
        'secret'  => '',
        'cluster' => '',
    ];
}


// USER LOGGED
$user_id = 0;
$user_nicename = $avatar_url = '';
$user_info;
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $user_nicename = $current_user->user_nicename;

    //$userID= get_field('ls_iduser',$user_id);
    $avatar_url = get_avatar_url($user_id, array('size' => 270));
    $user_info = get_userdata($user_id);
}
define('USER_LOGGED', $user_id);
define('USER_NAME', $user_nicename);
define('USER_AVATAR', $avatar_url);


// KIỂM TRA ĐK HIỂN THỊ KHUNG CHƠI GAME
define('ID_POST_UXBLOCK', 3101); // ID UX BLOCK - chứa thông về cách hiển thị game , mã đăng ký, bộ câu hỏi thi hiện tại
define('HIENTHIGAME', function_exists('get_field') ? get_field('hienthigame', ID_POST_UXBLOCK) : false);
define('MADANGKY', function_exists('get_field') ? get_field('madangky', ID_POST_UXBLOCK) : '');
define('URL_HISTORY_PAGE', get_the_permalink(3259));


// SET SCORE GAME
define('GAME_SCORE', 1000);


// ID DANH MỤC
define('ID_TUYENDUNG_CATEGORY', 100);
define('PAGINATION_TUYENDUNG_CATEGORY', 8);
define('ID_THUVIEN_CATEGORY', 103);
define('ID_NEWS_EVENT_CATEGORY', 79);


// SHORTCODE: RELATIVE POST
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/relativePosts.php';

// SHORTCODE: BREADCRUM
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/breadcrumb.php';

// SHORTCODE: SỰ KIỆN
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/sukien.php';

// AJAX: CHANGE SỰ KIỆN
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/changeDate.php';

// AJAX: BÌNH CHỌN
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/thongtinbinhchon.php';
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/luubinhchon.php';

// AJAX: LOAD TOPIC BY CATEGORY ID
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/loadtopic.php';

// AJAX: LOAD BỘ CÂU HỎI BY ID POST BÀI THI
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/loadbocauhoi.php';

// AJAX: LƯU BÀI THI
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/savebaithi.php';

// AJAX: LẤY POSTS THUỘC CATEGORY ĐÀO TẠO
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/getpostDaotao.php';

// AJAX: LOAD BẢNG XẾP HẠNG
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/loadbangxephang.php';

// SHORTCODE: LẤY DS CATEGORY ĐÀO TẠO
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/categoryDaotao.php';

// SHORTCODE: LẤY DS GIÁ TRỊ CỐT LÕI
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/giatricotloi.php';

// SHORTCODE: LẤY DS LỊCH SỬ HÌNH THÀNH
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/lichsuhinhthanh.php';

// WIDGET: SHORTCODE AND AJAX - LOGIN POPUP
require_once WP_CONTENT_DIR . '/themes/flatsome-child/widgets/loginPopup.php';

// SHORTCODE: LẤY DS SỰ KIỆN NỔI BẬT
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/sukiennoibat.php';

// SHORTCODE: LẤY DS DANH MỤC ĐÀO TẠO
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/danhmucdaotao.php';

// SHORTCODE: LẤY DS LỊCH SỬ HÌNH THÀNH
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/sukiensapden.php';

// SHORTCODE: BẢNG XẾP HẠNG
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/bangxephang.php';

// SHORTCODE: GAME PLAY
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/gameplay.php';

// METHOD: ALL
require_once WP_CONTENT_DIR . '/themes/flatsome-child/method/all.php';
require_once WP_CONTENT_DIR . '/themes/flatsome-child/method/xulybinhchon.php';

// SHORTCODE: LẤY DS LỊCH SỬ BÀI THI CỦA TỪNG USER
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/lichsubaithi.php';

// SHORTCODE: TUYỂN DỤNG
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/tuyendung.php';
require_once WP_CONTENT_DIR . '/themes/flatsome-child/ajax/filter_tuyendung.php';

// SHORTCODE: BẢNG QUY CHIẾU
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/bangquychieu.php';

// SHORTCODE: BẢNG XẾP HẠNG - TUYỂN DỤNG
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/bangxephang_tuyendung.php';

// SHORTCODE: THƯ VIỆN - SỰ KIỆN SẮP ĐẾN
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/thuvien_sukiensapden.php';

// SHORTCODE: THƯ VIỆN - SỰ KIỆN SẮP ĐẾN
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/overlay_shortcode.php';

// WIDGET: RECENT POST BY CURRENT CATEGORY
//require_once WP_CONTENT_DIR . '/themes/flatsome-child/widgets/recentPost.php';

// SHORTCODE: THƯ VIỆN - SỰ KIỆN SẮP ĐẾN
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/tuyendung_apply.php';
require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/tintuc_sukien_ganday.php';


// SAMPLE PUSHER
add_action('init', function() {
    $theme_path = get_stylesheet_directory() . '/shortcodes/pusher/';

    require_once $theme_path . 'init.php';
    require_once $theme_path . 'userInputForm.php';
    require_once $theme_path . 'saveUser.php';
    require_once $theme_path . 'adminBtnActive.php';
    require_once $theme_path . 'userRemove.php';
});

// require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/pusher/init.php';
// require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/pusher/userInputForm.php';
// require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/pusher/saveUser.php';
// require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/pusher/adminBtnActive.php';
// require_once WP_CONTENT_DIR . '/themes/flatsome-child/shortcodes/pusher/userRemove.php';