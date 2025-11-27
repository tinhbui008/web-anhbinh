<?php

// AJAX SHOW THÔNG TIN BÌNH CHỌN : KHI CLICK NÚT BÌNH CHỌN
// Đăng ký hành động xử lý AJAX
function handle_quiz_result_ajax() {
    
    // Kiểm tra nếu có dữ liệu result được gửi
    if (isset($_POST['result'])) {
        // Mặc định: chưa trả kết quả
        $result = false;
        
        if(isset($_POST['result'])){
            $data = json_decode(stripslashes($_POST['result']), true);
            //$data = utf8ize($data);

            $userId = USER_LOGGED;
            $userName = USER_NAME;
            $idPost = get_field('baithihientai', ID_POST_UXBLOCK); //$data['idPost'];
            $idCategory = get_option('idCategory'); //$data['idCategory'];
            $score = $data['score'];
            $timeUsed = $data['timeUsed'];
            $result_test = json_encode($data['result_test'], JSON_UNESCAPED_UNICODE );

            $post_data = array(
                'post_title'    => 'ID: '.$userId.' - '.$userName, // Title of the post
                'post_content'  => '', // Content of the post
                'post_status'   => 'publish', // Post status (draft, publish, etc.)
                'post_author'   => 1, // Author ID
                'post_type'     => 'lichsubaithi', // Custom post type slug (replace 'movie' with your CPT slug)
                'post_date'     => current_time('mysql'), // Current timestamp
            );
            
            // Insert the post into the database
            $post_id = wp_insert_post($post_data);

            // Optionally, add custom fields or meta
            if ($post_id) {
                update_post_meta($post_id, 'ls_iduser', $userId);
                update_post_meta($post_id, 'ls_idCategory', $idCategory);
                update_post_meta($post_id, 'ls_idPost', $idPost);
                update_post_meta($post_id, 'ls_score', $score);
                update_post_meta($post_id, 'ls_timeUsed', $timeUsed);
                update_post_meta($post_id, 'ls_result_test', $result_test);
            }

            // Trả kết quả khi lưu thành công
            $result = true;
        }

        // Ví dụ: Xử lý dữ liệu, bạn có thể lưu vào database hoặc làm gì đó với nó
        // Ở đây chỉ đơn giản trả lại dữ liệu đã nhận
        wp_send_json_success($data);
    } else {
        wp_send_json_error('Không có dữ liệu gửi lên');
    }

    wp_die();
}

// Đăng ký hành động với WordPress
add_action('wp_ajax_process_quiz_result', 'handle_quiz_result_ajax'); // Dành cho người dùng đã đăng nhập
add_action('wp_ajax_nopriv_process_quiz_result', 'handle_quiz_result_ajax'); // Dành cho người dùng chưa đăng nhập