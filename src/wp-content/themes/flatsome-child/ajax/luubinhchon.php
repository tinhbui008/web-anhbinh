<?php

// AJAX SHOW THÔNG TIN BÌNH CHỌN : KHI CLICK NÚT BÌNH CHỌN
add_action( 'wp_ajax_luubinhchon', 'luubinhchon_init' );
add_action( 'wp_ajax_nopriv_luubinhchon', 'luubinhchon_init' );
function luubinhchon_init() {
    $id = (isset($_POST['id']))?esc_attr($_POST['id']) : '';

    // XỬ LÝ BÌNH CHỌN
    if (is_user_logged_in()) {
        //$current_user = wp_get_current_user();
        //$user_id = $current_user->ID;
        handle_user_vote($id, USER_LOGGED); //xulybinhchon.php

        $user_ids = get_post_meta($id, 'luot_binh_chon', true);
		$user_ids = ($user_ids) ? $user_ids : null;
        wp_send_json_success([
            'luotbinhchon' => (isset($user_ids)) ? count($user_ids) : 0
        ]);
    }

    wp_die();
}