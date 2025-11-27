<?php
// Gửi realtime update cho tất cả client
// require_once WP_CONTENT_DIR . '/themes/flatsome/vendor/autoload.php';

// function get_pusher_instance(){
//     if (!defined('PUSHER_APP_KEY') || !defined('PUSHER_APP_SECRET') || !defined('PUSHER_APP_ID') || !defined('CLUSTER')) {
//         return null; // constants chưa define → không tạo instance
//     }
    
//     $pusher = new Pusher\Pusher(PUSHER_APP_KEY, PUSHER_APP_SECRET, PUSHER_APP_ID, [
//         'cluster' => CLUSTER, 'useTLS' => true
//     ]);

//     return $pusher;
// }

require_once WP_CONTENT_DIR . '/themes/flatsome/vendor/autoload.php';

/**
 * Tạo instance Pusher luôn sử dụng config mới nhất.
 */
function get_pusher_instance() {
    if (!function_exists('spc_get_pusher_config')) {
        return new WP_Error('pusher_missing_func', 'Hàm spc_get_pusher_config không tồn tại.');
    }

    $conf = spc_get_pusher_config();

    if (empty($conf['key']) || empty($conf['secret']) || empty($conf['app_id']) || empty($conf['cluster'])) {
        return new WP_Error('pusher_config_incomplete', 'Thông tin cấu hình Pusher chưa đầy đủ.');
    }

    try {
        $pusher = new Pusher\Pusher(
            $conf['key'],
            $conf['secret'],
            $conf['app_id'],
            [
                'cluster' => $conf['cluster'],
                'useTLS'  => true,
            ]
        );

        // Test kết nối với một channel tạm thời
        $pusher->trigger('test-channel', 'test-event', ['message' => 'test']);

        return $pusher;
    } catch (\Pusher\Exceptions\PusherException $e) {
        return new WP_Error('pusher_connection_failed', 'Không thể kết nối Pusher: ' . $e->getMessage());
    }
}


// function get_pusher_instance() {
//     if (!function_exists('spc_get_pusher_config')) {
//         return null;
//     }

//     $conf = spc_get_pusher_config();

//     // Nếu thiếu bất kỳ thông tin nào → không tạo instance
//     if (empty($conf['key']) || empty($conf['secret']) || empty($conf['app_id'])) {
//         return null;
//     }

//     return new Pusher\Pusher(
//         $conf['key'],
//         $conf['secret'],
//         $conf['app_id'],
//         [
//             'cluster' => $conf['cluster'],
//             'useTLS'  => true,
//         ]
//     );
// }
