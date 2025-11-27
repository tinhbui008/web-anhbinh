<?php
add_action('wp_ajax_remove_user_code', 'remove_user_code');
function remove_user_code() {
    // if (get_option('is_game_active')) {
    //     wp_send_json_error(['message' => 'Không thể xoá khi đang active popup']);
    // }

    $code = sanitize_text_field($_POST['user_code']);
    $user_list = get_option('user_form_list', []);
    

    unset($user_list[$code]);
    update_option('user_form_list', $user_list);

    /*$key = array_search($code, $user_list);

    if ($key !== false) {
        unset($user_list[$key]);
        update_option('user_form_list', $user_list);
    }*/

    // Gửi realtime update cho tất cả client
    // require_once WP_CONTENT_DIR . '/themes/flatsome/vendor/autoload.php';
    // $pusher = new Pusher\Pusher(PUSHER_APP_KEY, PUSHER_APP_SECRET, PUSHER_APP_ID, [
    //     'cluster' => CLUSTER, 'useTLS' => true
    // ]);

    $pusher = get_pusher_instance();
    $pusher->trigger('user-channel', 'user-removed', [
        'userID' => $code,
        'users' => $user_list,
        'count' => count($user_list)
        //'user' => $user_list[$code],
    ]);

    wp_send_json(['success' => true, 'users' => $user_list]);
}
