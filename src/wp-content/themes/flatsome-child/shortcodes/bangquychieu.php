<?php
// ### CLICK TAB
function bangquychieu_init() {

    $categories_bangquychieu = get_terms([
        'taxonomy'   => 'bangquychieuthuong',
        'hide_empty' => false, // true nếu chỉ muốn danh mục có bài viết
    ]);

    ob_start();
?>


<section class="bangquychieu-container">
    <!-- TITLE -->
    <h2 class="tuyendung-list-title"><p>BẢNG QUY CHIẾU THƯỞNG</p></h2>
    <div class="bangquychieu-contain container-page">
        <!-- TABLE -->
        <div class="bangquychieu-table-contain">
            <div class="bangquychieu-table">
                <div class="bangquychieu-table-th">
                    <span></span>
                    <span>Phân loại/Bậc</span>
                    <span>Kinh nghiệm</span>
                    <span>Mức thưởng</span>
                </div>
                <?php foreach ($categories_bangquychieu as $category) :
                    $args = array(
                        'post_type' => 'bangquychieuthuong',
                        'posts_per_page' => -1, // Lấy tất cả bài viết
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'bangquychieuthuong', // Tên taxonomy
                                'field'    => 'id', // Hoặc 'slug' nếu bạn muốn truy vấn theo slug
                                'terms'    => $category->term_id, // ID danh mục cần lấy bài viết
                            ),
                        ),
                    );

                    $getposts = get_posts($args);

                    //$query = new WP_Query($args);
                ?>
                <div class="bangquychieu-table-body">
                    <div>
                        <h3><?=esc_html($category->name)?></h3>
                        <div><?=esc_html($category->description)?></div>
                    </div>
                    <div class="bangquychieu-table-right">
                        <!-- ITEM -->
                        <?php if (isset($getposts)) :
                            foreach ($getposts as $v) :   
                                $id = $v->ID;
                                $title = $v->post_title; 
                                $kinhnghiem = get_field('bangquychieu_kinhnghiem',$id);
                                $mucthuong = get_field('bangquychieu_mucthuong',$id);
                        ?>
                        <div>
                            <div><?=$title?></div>
                            <div><?=$kinhnghiem?></div>
                            <div><?=$mucthuong?></div>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- NOTE -->
        <p class="bangquychieu-note">Lưu ý: Cấp bậc áp dụng thưởng của ứng viên sẽ được xét theo kết quả đánh giá trên Phiếu phỏng vấn của bộ phận chuyên môn và phòng Nhân sự.</p>
    </div>
</section>


<?php
    $output = ob_get_clean(); 
    return $output;
}
add_shortcode( 'bangquychieu_shortcode', 'bangquychieu_init' );