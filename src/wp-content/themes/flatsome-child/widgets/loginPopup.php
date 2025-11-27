<?php

// SHORTCODE: LOGIN POPUP
function load_custom_login_popup() {
    ob_start();
    include get_template_directory() . '/layouts/popup-login-template.php';
    return ob_get_clean();
}
add_shortcode('popup_login', 'load_custom_login_popup');


// SHORTCODE : BUTTON LOGIN
function load_custom_login_btn() {
    ob_start();
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $avatar_url = get_avatar_url($current_user->ID);
        $profile_url = admin_url('profile.php'); // Hoặc link custom của bạn
        $logout_url = wp_logout_url(home_url()); // Redirect về trang chủ sau khi logout
    ?>

        <div id="custom-login-view" class="logged-in-avatar avatar-wrapper">
            <div class="logged-in-main"><img src="<?php echo $avatar_url; ?>" alt="Avatar"><svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1 1.5L6 6.5L11 1.5" stroke="#3D3D3D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></div>
            <div class="dropdown-menu">
                <a href="<?php echo esc_url($profile_url); ?>">Thông tin tài khoản</a>
                <a href="<?php echo URL_HISTORY_PAGE; ?>">Lịch sử chơi game</a>
                <a href="<?php echo esc_url($logout_url); ?>">Đăng xuất</a>
            </div>
        </div>

<?php } else {
        echo '<div id="custom-login-btn" class="dev-userLogin">Đăng nhập</div>';
        //echo '<a href="'.do_shortcode('[openid_connect_generic_auth_url]').'" class="dev-userLogin">Đăng nhập</a>';
    }
    return ob_get_clean();
}
add_shortcode('btn_login', 'load_custom_login_btn');


// AJAX: LOGIN SUBMIT
function custom_login_ajax() {
    $info = array();
    $info['user_login'] = sanitize_text_field($_POST['username']);
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon($info, false);

    if (is_wp_error($user_signon)) {
        echo 'Sai tên đăng nhập hoặc mật khẩu!';
    } else {
        echo 'Đăng nhập thành công!';
        header("Refresh:0");
    }

    wp_die();
}
add_action('wp_ajax_custom_login', 'custom_login_ajax');
add_action('wp_ajax_nopriv_custom_login', 'custom_login_ajax');


// AJAX : GET AVATAR AFTER LOGIN SUCCESS
function get_user_avatar_ajax() {
    ob_start();
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $avatar_url = get_avatar_url($current_user->ID);
        $profile_url = admin_url('profile.php');
        $logout_url = wp_logout_url(home_url());

    ?>

        <div id="custom-login-view" class="logged-in-avatar avatar-wrapper">
        <div class="logged-in-main"><img src="<?php echo $avatar_url; ?>" alt="Avatar"><svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1 1.5L6 6.5L11 1.5" stroke="#3D3D3D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></div>
            <div class="dropdown-menu">
                <a href="<?php echo esc_url($profile_url); ?>">Thông tin tài khoản</a>
                <a href="<?php echo esc_url($logout_url); ?>">Đăng xuất</a>
            </div>
        </div>

    <?php } else {
        echo '<div id="custom-login-btn" class="dev-userLogin">Đăng nhập</div>';
        //echo '<a href="'.do_shortcode('[openid_connect_generic_auth_url]').'" class="dev-userLogin">Đăng nhập</a>';
    }

    $html = ob_get_clean();
    echo $html;
    wp_die();
}
add_action('wp_ajax_get_user_avatar', 'get_user_avatar_ajax');
add_action('wp_ajax_nopriv_get_user_avatar', 'get_user_avatar_ajax');