<?php

// AJAX SHOW THÔNG TIN BÌNH CHỌN : KHI CLICK NÚT BÌNH CHỌN
add_action( 'wp_ajax_loadbocauhoi', 'loadbocauhoi_init' );
add_action( 'wp_ajax_nopriv_loadbocauhoi', 'loadbocauhoi_init' );
function loadbocauhoi_init() {
    
    $id = $post_id = get_field('baithihientai', ID_POST_UXBLOCK);//(isset($_POST['id']))?esc_attr($_POST['id']) : get_field('baithihientai', ID_POST_UXBLOCK);
    if (!$id) {
        wp_send_json_error(['message' => 'ID không hợp lệ']);
    }

    $post = get_post($id);
    if (!$post) {
        wp_send_json_error(['message' => 'Không tìm thấy bài viết']);
    }

    // GET DATA
    //$id = 2998;
    $time_work = get_field('cauhoi_thoigian',$id);
    $questions_list = get_field('cauhoi_list',$id);

    if(isset($questions_list)){
        ob_start();

        // INIT
        $questions_tmp = array();

        // ID bài thi
        $result['idPost'] = $id;

        // FOR QUESTION
        foreach($questions_list as $k => $v){
            $question__item_tmp = array();
            // Loại câu hỏi
            $question__item_tmp['type'] = 'choice';
            // Loại hiển thị câu hỏi
            $question__item_tmp['question_display_type'] = $v['cauhoi_dangcauhoi'];
            // Nội dung câu hỏi
            $question__item_tmp['question'] = $v['cauhoi_noidung'];
            // Danh sách đáp án chọn
            $question__item_tmp['options']['A'] = (isset($v['cauhoi_dapan_a'])) ? $v['cauhoi_dapan_a'] : '';
            $question__item_tmp['options']['B'] = (isset($v['cauhoi_dapan_b'])) ? $v['cauhoi_dapan_b'] : '';
            $question__item_tmp['options']['C'] = (isset($v['cauhoi_dapan_c'])) ? $v['cauhoi_dapan_c'] : '';
            $question__item_tmp['options']['D'] = (isset($v['cauhoi_dapan_d'])) ? $v['cauhoi_dapan_d'] : '';
            // Đáp án đúng:
            $question__item_tmp['correct'] = array();
            // Hình ảnh:
            $question__item_tmp['image'] = $v['cauhoi_hinhanh'];
            // Audio:
            $question__item_tmp['audio'] = $v['cauhoi_audio'];
            // Video:
            $question__item_tmp['video'] = $v['cauhoi_video'];
            // Tự luận:
            if($v['cauhoi_dangcauhoi']=='cauhoi_tuluan'){
                if(isset($v['cauhoi_dapantuluan_list'])){
                    $question__item_tmp['type'] = 'text';
                    foreach($v['cauhoi_dapantuluan_list'] as $val){
                        array_push($question__item_tmp['correct'], $val['cauhoi_dapantuluan_dung']);
                    }
                }
                $question__item_tmp['score'] = GAME_SCORE;
            }else{
                if(isset($v['cauhoi_dapandung'])){
                    foreach($v['cauhoi_dapandung'] as $val){
                        if(isset($val) && $val=='cauhoi_dapandung_a'){array_push($question__item_tmp['correct'], "A");}
                        if(isset($val) && $val=='cauhoi_dapandung_b'){array_push($question__item_tmp['correct'], "B");}
                        if(isset($val) && $val=='cauhoi_dapandung_c'){array_push($question__item_tmp['correct'], "C");}
                        if(isset($val) && $val=='cauhoi_dapandung_d'){array_push($question__item_tmp['correct'], "D");}
                    }

                    if(count($v['cauhoi_dapandung'])>1){$question__item_tmp['type'] = 'multi-choice';}
                }
                $question__item_tmp['score'] = floor(GAME_SCORE / count($question__item_tmp['correct']));
            }
            

            // Add question
            array_push($questions_tmp, $question__item_tmp);
        }

        // KẾT QUẢ
        $result['totalTime'] = $time_work * 60; // convert phút --> giây
        $result['questions'] = $questions_tmp;

        wp_send_json_success($result);
        wp_die();
    }
}