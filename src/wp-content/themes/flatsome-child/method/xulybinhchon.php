<?php
// Gọi hàm này khi người dùng bình chọn bài viết
function handle_user_vote($post_id, $user_id) {
    if (get_post_type($post_id) !== 'post') return;

    // Kiểm tra bài viết có trong danh mục ID 96 (vnplay)
    if (!has_category(96, $post_id)) return;

    // Lấy danh sách user đã vote
    $user_ids = get_post_meta($post_id, 'luot_binh_chon', true);
    if (!is_array($user_ids)) {
        $user_ids = [];
    }

    if (in_array($user_id, $user_ids)) {
        // Nếu đã vote → gỡ bỏ
        $user_ids = array_diff($user_ids, [$user_id]);
    } else {
        // Nếu chưa vote → thêm vào
        $user_ids[] = $user_id;
    }

    // Cập nhật lại danh sách
    update_post_meta($post_id, 'luot_binh_chon', array_values($user_ids));
}


// Tạo meta box hiển thị thông tin luot_binh_chon
function show_luot_binh_chon_meta_box() {
    add_meta_box(
        'luot_binh_chon_box', // ID
        'Lượt Bình Chọn', // Tiêu đề
        'render_luot_binh_chon_box', // Callback
        'post',                       // Post type
        'side',                       // Vị trí
        'default'                     // Độ ưu tiên
    );
}
add_action('add_meta_boxes', 'show_luot_binh_chon_meta_box');


// Callback hiển thị nội dung
function render_luot_binh_chon_box($post) {
    if (!has_category(96, $post)) {
        echo 'Bài viết này không thuộc danh mục vnplay.';
        return;
    }

    $user_ids = get_post_meta($post->ID, 'luot_binh_chon', true);

    if (!empty($user_ids) && is_array($user_ids)) {
        echo '<div>'.count($user_ids).'</div>';
    } else {
        echo 'Chưa có lượt bình chọn nào.';
    }
}
