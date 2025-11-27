<?php

// AJAX SHOW THÔNG TIN BÌNH CHỌN : KHI CLICK NÚT BÌNH CHỌN
add_action( 'wp_ajax_loadtopic', 'loadtopic_init' );
add_action( 'wp_ajax_nopriv_loadtopic', 'loadtopic_init' );
function loadtopic_init() {
    $user_code = (isset($_POST['user_code']))?esc_attr($_POST['user_code']) : 0;
    if($user_code!=MADANGKY || USER_LOGGED==0 ){
        $data = [
            'userId' => USER_LOGGED,
            'userName' => '',
            'userAvatar' => '',
        ];
        wp_send_json_error(USER_LOGGED);
    }else{
        $data = [
            'userId' => USER_LOGGED,
            'userName' => USER_NAME,
            'userAvatar' => USER_AVATAR,
        ];
        wp_send_json_success($data); 
    }
    wp_die();//bắt buộc phải có khi kết thúc
}