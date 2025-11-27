<?php
add_shortcode( 'tuyendung-apply-shortcode', 'tuyendungApply' );

function tuyendungApply($attr) {
    $option = shortcode_atts(['post_id'=>''],$attr);
    ob_start();

    // Xử lý
    $post_id = $option['post_id'];
    $categories = get_the_category($post_id); // Thay $post_id bằng ID của bài viết

    // Khởi tạo biến để lưu ID của chuyên mục cha gần nhất
    $closest_parent_category_id = 0;
    $closest_parent_category_name = '';
    foreach ($categories as $category) {
        $closest_parent_category_id = $category->term_id;
        $closest_parent_category_name = $category->name;
    }

    $parentID = get_category($closest_parent_category_id)->parent;  
    $parentID = ($parentID==0) ? $closest_parent_category_id : $parentID;
?>  
<?php if ( $parentID==100 ) :?>
    <a href="#formTuyendung" data-fancybox class="button-style1 applySubmit-tuyendung not-color"><span>Nộp đơn</span></a>

    <div style="display:none">
        <div id="formTuyendung"><?=do_shortcode('[contact-form-7 id="67c49db" title="Tuyển dụng"]');?></div>
    </div>
<?php endif;?>

<?php 
        $output = ob_get_clean(); 
        return $output;
    }
?>