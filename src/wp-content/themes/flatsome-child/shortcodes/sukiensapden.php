<?php

// ### CLICK TAB
function show_sukien_list() {
    // Lấy thời gian hiện tại theo định dạng 'Y-m-d H:i:s'
    $now = current_time('Y-m-d H:i:s');

    // Query các bài viết sắp tới
    $args = array(
        'post_type' => 'sukien-daotao',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        'meta_key' => 'ngaybatdau', // Tên trường ACF datetime
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'ngaybatdau',
                'value' => $now,
                'compare' => '>=',
                'type' => 'DATETIME', // Quan trọng: vì định dạng đang là 'Y-m-d H:i:s'
            ),
        ),
    );

    //$query = new WP_Query($args);
    $getposts = get_posts($args);

    // $getposts = get_posts(array(
    //         'showposts' => -1,
    //         'post_type' => 'giatricotloi',
    //         /*'orderby'   => array(
    //             'date' =>'DESC',
    //         )*/
    //     )
    // );
    if(isset($getposts)):
        ob_start();
?>

<div class="sukiensapden-container container-page">
    <!-- LEFT -->
    <?php 
        $v = $getposts[0];
        $id = (int)$v->ID;
        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
        $title = $v->post_title;
        $content = $v->post_excerpt;
        $linkdieuhuong = get_field('linkdieuhuong',$id);
        $linkdieuhuong = ($linkdieuhuong!='') ? $linkdieuhuong : get_the_permalink($id);

        $ngaybatdau = get_field('ngaybatdau', $id);
        $wp_timezone = wp_timezone(); // Lấy timezone đang được cấu hình trong WordPress admin
        $ngaybatdau = new DateTime($ngaybatdau, new DateTimeZone('UTC'));
        $ngaybatdau->setTimezone($wp_timezone);
        $ngaybatdau = $ngaybatdau->format('Y-m-d H:i');        
        //dd($img);
    ?>
    <a href="<?=$linkdieuhuong?>" class="sukiensapden-left">
        <img src="<?=$img?>" alt="photo">
        <div class="sukiensapden-info">
            <p class="sukiensapden-date"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_2497)"> <path d="M10.0001 4.99984V9.99984L13.3334 11.6665M18.3334 9.99984C18.3334 14.6022 14.6025 18.3332 10.0001 18.3332C5.39771 18.3332 1.66675 14.6022 1.66675 9.99984C1.66675 5.39746 5.39771 1.6665 10.0001 1.6665C14.6025 1.6665 18.3334 5.39746 18.3334 9.99984Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_296_2497"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg><?=date('d/m/Y H:i', strtotime($ngaybatdau))?></p>
            <h3 class="sukiensapden-title"><?=esc_html($title)?></h3>
            <div class="sukiensapden-des"><?=esc_html($content)?></div>
        </div>
    </a>
    <!-- RIGHT -->
    <div class="sukiensapden-right">
        <?php foreach($getposts as $k => $v):
            if($k!=0):
            $v = $getposts[$k];
            $id = (int)$v->ID;
            $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
            $title = $v->post_title;
            $content = $v->post_excerpt;
            $linkdieuhuong = get_field('linkdieuhuong',$id);
            $linkdieuhuong = ($linkdieuhuong!='') ? $linkdieuhuong : get_the_permalink($id);

            $ngaybatdau = get_field('ngaybatdau', $id);            
            $ngaybatdau = new DateTime($ngaybatdau, new DateTimeZone('UTC'));
            $ngaybatdau->setTimezone($wp_timezone);
            $ngaybatdau = $ngaybatdau->format('Y-m-d H:i');
        ?>
            <a href="<?=$linkdieuhuong?>" class="sukiensapden-small-item">
                <?php if($img):?><img src="<?=$img?>" alt="photo"><?php else: ?><span class="no-image">No image</span><?php endif;?>
                <div class="sukiensapden-small-info">
                    <h3><?=esc_html($title)?></h3>
                    <p class="sukiensapden-small-date"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_2497)"> <path d="M10.0001 4.99984V9.99984L13.3334 11.6665M18.3334 9.99984C18.3334 14.6022 14.6025 18.3332 10.0001 18.3332C5.39771 18.3332 1.66675 14.6022 1.66675 9.99984C1.66675 5.39746 5.39771 1.6665 10.0001 1.6665C14.6025 1.6665 18.3334 5.39746 18.3334 9.99984Z" stroke="#6D6D6D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_296_2497"> <rect width="20" height="20" fill="#6D6D6D"/> </clipPath> </defs> </svg><?=date('d/m/Y H:i', strtotime($ngaybatdau))?></p>
                </div>
            </a>
        <?php endif; endforeach;?>
    </div>
</div>

<?php
        $output = ob_get_clean(); 
        return $output;
    endif;
}
add_shortcode( 'sukiensapden_shortcode', 'show_sukien_list' );