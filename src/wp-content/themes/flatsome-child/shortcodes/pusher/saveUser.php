<?php
add_action('wp_ajax_submit_user_code', 'submit_user_code');
add_action('wp_ajax_nopriv_submit_user_code', 'submit_user_code');

function submit_user_code() {

    if (get_option('is_game_active')) {
        wp_send_json_error(['message' => 'Đang trong quá trình trò chơi, bạn vui lòng đăng ký lần sau!']);
    }

    $userID = sanitize_text_field($_POST['userID']);
    $userName = sanitize_text_field($_POST['userName']);
    $userAvatar = sanitize_text_field($_POST['userAvatar']);

    if (!$userID) {
        wp_send_json_error(['message' => 'Thiếu mã người dùng']);
    }

    $user_list = get_option('user_form_list', []);

    $user_list[$userID] = [
        'id' => $userID,
        'name' => $userName ?: 'Không tên',
        'avatar' => $userAvatar ?: '',
        'status' => 'Đang chờ'
        //'joined_at' => current_time('mysql')
    ];

    update_option('user_form_list', $user_list);

    $pusher = get_pusher_instance();
    $pusher->trigger('user-channel', 'user-added', [
        'userID' => $userID,
        'user' => $user_list[$userID],
        'users' => $user_list,
        'count' => count($user_list)
    ]);

    wp_send_json(['success' => true, 'users' => $user_list, 'count' => count($user_list)]);
}