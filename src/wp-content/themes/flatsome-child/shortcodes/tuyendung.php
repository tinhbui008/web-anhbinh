<?php
// ### CLICK TAB
function tuyendung_init() {

    // Lấy các giá trị filter từ Ajax request
    $keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
    $khu_vuc = isset($_GET['khu_vuc']) ? sanitize_text_field($_GET['khu_vuc']) : '';
    $khoi_van_phong = isset($_GET['khoi_van_phong']) ? sanitize_text_field($_GET['khoi_van_phong']) : '';
    $trang_thai = isset($_GET['trang_thai']) ? sanitize_text_field($_GET['trang_thai']) : '';
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            
    // Thiết lập các tham số truy vấn WP
    $args = $args_tmp = array(
        'post_type' => 'post',
        'cat' => ID_TUYENDUNG_CATEGORY, // Danh mục tuyển dụng có ID là 100
        'posts_per_page' => PAGINATION_TUYENDUNG_CATEGORY,
        'paged' => $paged,
        's' => $keyword, // Tìm kiếm theo từ khóa
        'meta_query' => array('relation' => 'AND'),
    );

    if ($khu_vuc) {
        $args['meta_query'][] = array(
            'key' => 'tuyendung_khuvuc',
            'value' => $khu_vuc,
            'compare' => 'LIKE',
        );
    }

    if ($khoi_van_phong) {
        $args['meta_query'][] = array(
            'key' => 'tuyendung_khoivanphong',
            'value' => $khoi_van_phong,
            'compare' => 'LIKE',
        );
    }

    if ($trang_thai) {
        $args['meta_query'][] = array(
            'key' => 'tuyendung_trangthai',
            'value' => $trang_thai,
            'compare' => 'LIKE',
        );
    }

    ob_start();  

    // Query WP để lấy các bài viết
    $query_first = new WP_Query($args_tmp);

    $first_post = $query_first->posts[0]; // Đây là bài viết đầu tiên
    $khuvucs = get_field_object('tuyendung_khuvuc',$first_post->ID); // Lấy danh sách các lựa chọn;
    $khoivanphongs = get_field_object('tuyendung_khoivanphong',$first_post->ID); // Lấy danh sách các lựa chọn;
    $trangthais = get_field_object('tuyendung_trangthai',$first_post->ID); // Lấy danh sách các lựa chọn;


    // Query WP để lấy các bài viết
    $query = new WP_Query($args);
?>
<section class="tuyendung-list-contain">
     <div class="tuyendung-list-title"><p>CƠ HỘI NGHỀ NGHIỆP</p></div>
    <div class="container-page tuyendung-list-main">       
        <div class="tuyendung-list-top">            
            <!-- SEARCH -->
            <div class="tuyendung-list-search">
                <input id="tuyendung_search" type="text" placeholder="Tìm kiếm" autocomplete="off" value="<?=$keyword?>">
                <span><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M28 28L22.2 22.2M25.3333 14.6667C25.3333 20.5577 20.5577 25.3333 14.6667 25.3333C8.77563 25.3333 4 20.5577 4 14.6667C4 8.77563 8.77563 4 14.6667 4C20.5577 4 25.3333 8.77563 25.3333 14.6667Z" stroke="#005BAA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
            </div>
        </div>
        <div class="tuyendung-list-bottom">
            <!-- FILTER LEFT -->
            <div class="tuyendung-list-filter">
                <!-- KHU VỰC -->
                <div class="tuyendung-list-filter-box">
                    <p>Khu vực</p>
                    <select id="tuyendung_khuvuc">
                        <?php foreach($khuvucs['choices'] as $k=>$v):?>
                            <option value="<?=$k?>" <?=($khu_vuc==$k) ? 'selected' : ''?>><?=$v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <!-- KHỐI VP -->
                <div class="tuyendung-list-filter-box">
                    <p>Khối văn phòng</p>
                    <select id="tuyendung_khoivanphong">
                        <?php foreach($khoivanphongs['choices'] as $k=>$v):?>
                            <option value="<?=$k?>" <?=($khoi_van_phong==$k) ? 'selected' : ''?>><?=$v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <!-- TRẠNG THÁI -->
                <div class="tuyendung-list-filter-box">
                    <p>Trạng thái</p>
                    <select id="tuyendung_trangthai">
                        <?php foreach($trangthais['choices'] as $k=>$v):?>
                            <option value="<?=$k?>" <?=($trang_thai==$k) ? 'selected' : ''?>><?=$v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <!-- PAGED -->
                <input type="hidden" value="<?=$paged?>" id="tuyendung-paged">
            </div>
            <!-- RESULT LIST -->
            <div id="tuyendung_results" class="tuyendung-list-right">
                <?php if ($query->have_posts()) :?>
                    <!-- LIST AJAX-->
                    <div class="tuyendung-list-items">
                        <?php while ($query->have_posts()):
                            $query->the_post();

                            $id = (int)get_the_ID();
                            $vitri = get_field('tuyendung_vitri',$id);
                            $thoigian= get_field('tuyendung_thoigian',$id);
                            $luong= get_field('tuyendung_luong',$id);
                            $khuvuc= get_field('tuyendung_khuvuc',$id);
                            $permalink = get_the_permalink($id);
                        ?>
                            <div class="tuyendung-list-item">
                                <div class="tuyendung-list-box">
                                    <h3><?php the_title(); ?></h3>
                                    <div class="tuyendung-list-info">
                                        <!-- VỊ TRÍ -->
                                        <div class="tuyendung-list-info-item"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M16.6667 17.5V15.8333C16.6667 14.9493 16.3155 14.1014 15.6903 13.4763C15.0652 12.8512 14.2174 12.5 13.3333 12.5H6.66666C5.78261 12.5 4.93476 12.8512 4.30964 13.4763C3.68452 14.1014 3.33333 14.9493 3.33333 15.8333V17.5M13.3333 5.83333C13.3333 7.67428 11.8409 9.16667 9.99999 9.16667C8.15905 9.16667 6.66666 7.67428 6.66666 5.83333C6.66666 3.99238 8.15905 2.5 9.99999 2.5C11.8409 2.5 13.3333 3.99238 13.3333 5.83333Z" stroke="#6D6D6D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg><span><?=$vitri?></span></div>
                                        <!-- KHU VỰC -->
                                        <div class="tuyendung-list-info-item"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_3993)"> <path d="M17.5 8.3335C17.5 14.1668 10 19.1668 10 19.1668C10 19.1668 2.5 14.1668 2.5 8.3335C2.5 6.34437 3.29018 4.43672 4.6967 3.03019C6.10322 1.62367 8.01088 0.833496 10 0.833496C11.9891 0.833496 13.8968 1.62367 15.3033 3.03019C16.7098 4.43672 17.5 6.34437 17.5 8.3335Z" stroke="#6D6D6D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> <path d="M10 10.8335C11.3807 10.8335 12.5 9.71421 12.5 8.3335C12.5 6.95278 11.3807 5.8335 10 5.8335C8.61929 5.8335 7.5 6.95278 7.5 8.3335C7.5 9.71421 8.61929 10.8335 10 10.8335Z" stroke="#6D6D6D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_296_3993"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg><span><?=$khuvucs['choices'][$khuvuc]?></span></div>
                                    </div>
                                    <div class="tuyendung-list-info">
                                        <!-- THỜI GIAN -->
                                        <div class="tuyendung-list-info-item"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_3989)"> <path d="M10 4.99984V9.99984L13.3333 11.6665M18.3333 9.99984C18.3333 14.6022 14.6024 18.3332 10 18.3332C5.39763 18.3332 1.66667 14.6022 1.66667 9.99984C1.66667 5.39746 5.39763 1.6665 10 1.6665C14.6024 1.6665 18.3333 5.39746 18.3333 9.99984Z" stroke="#6D6D6D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_296_3989"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg><span><?=$thoigian?></span></div>
                                        <!-- LƯƠNG -->
                                        <div class="tuyendung-list-info-item"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_3996)"> <path d="M10 0.833496V19.1668M14.1667 4.16683H7.91667C7.14312 4.16683 6.40125 4.47412 5.85427 5.0211C5.30729 5.56808 5 6.30995 5 7.0835C5 7.85704 5.30729 8.59891 5.85427 9.14589C6.40125 9.69287 7.14312 10.0002 7.91667 10.0002H12.0833C12.8569 10.0002 13.5987 10.3075 14.1457 10.8544C14.6927 11.4014 15 12.1433 15 12.9168C15 13.6904 14.6927 14.4322 14.1457 14.9792C13.5987 15.5262 12.8569 15.8335 12.0833 15.8335H5" stroke="#6D6D6D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_296_3996"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg><span><?=$luong?></span></div>
                                    </div>
                                </div>
                                <a href="<?=$permalink?>" class="tuyendung-list-view">
                                    <span>Ứng tuyển</span>
                                    <span></span>
                                </a>
                            </div>
                        <?php endwhile;?>
                    </div>
                    <!-- PAGINATION -->
                    <div id="tuyendung_pagination">
                        <?php
                            // Phân trang bằng paginate_links()
                            $pagination = paginate_links(array(
                                //'base' => '%_%', // base sẽ bị thay bằng JS
                                //'format' => '?paged=%#%',
                                'current' => max(1, $paged),
                                'total' => $query->max_num_pages,
                                'type'  => 'array',
                                'prev_text' => '<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M7 13L1 7L7 1" stroke="#3D3D3D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                                'next_text' => '<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1 13L7 7L1 1" stroke="#3D3D3D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg>'
                            ));
                            if ($pagination):
                        ?>
                        <div class="tuyendung-pagination">
                            <?php
                                foreach ($pagination as $page_link):
                                    $page_num = 1;
                                    if (preg_match('#/page/(\d+)/?#', $page_link, $matches)) {
                                        $page_num = (int)$matches[1];
                                    } elseif (preg_match('/[?&]paged=(\d+)/', $page_link, $matches)) {
                                        $page_num = (int)$matches[1];
                                    }

                                    // Nếu là thẻ <a>, thêm data-page vào
                                    if (strpos($page_link, '<a') !== false) {
                                        $page_link = preg_replace('/href="[^"]*"/', 'href=""', $page_link);

                                        // Thêm thủ công data-page vào thẻ a đầu tiên
                                        $page_link = preg_replace('/<a\b(.*?)>/', '<a\1 data-page="' . $page_num . '">', $page_link);
                                    }
                            ?>
                                <?=$page_link ?>  
                                                        
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else: echo 'Không tìm thấy bài viết nào.';?>
                <?php endif;?>
            </div>
        </div>
    </div>
</section>

<?php
    $output = ob_get_clean(); 
    return $output;
}
add_shortcode( 'tuyendung_shortcode', 'tuyendung_init' );