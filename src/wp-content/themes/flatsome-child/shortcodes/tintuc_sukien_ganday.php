<?php
// ### CLICK TAB
function show_tintuc_sukien() {
    $getposts = get_posts(array(
            'showposts' => 4,
            'category__in' => ID_NEWS_EVENT_CATEGORY, // Lay bai viet cung theo category
            'orderby'   => array(
                'date' =>'DESC',
            )
        )
    );
    if(isset($getposts)):
        ob_start();
?>

<div class="container-page news-event-container">
    <div class="news-event-left">
        <?php
            // Tách bài viết đầu tiên
            $first_post = array_shift($getposts); // Lấy và loại bỏ phần tử đầu tiên

            // Xử lý bài đầu tiên
            setup_postdata($first_post);

            $first_date = get_the_date('d/m/Y', $first_post);
            $first_title = get_the_title($first_post);
            $first_permalink = get_permalink($first_post);
            $first_thumbnail = get_the_post_thumbnail_url($first_post, 'large');
        ?>
        <p class="news-event-left-date"><?=$first_date?></p>
        <h2><a href="<?=$first_permalink?>"><?=$first_title?></a></h2>
        <a href="<?=$first_permalink?>" class="news-event-img">
            <?php if($first_thumbnail!=''):?><img src="<?=$first_thumbnail?>"><?php else: ?><span class="box-noneImg">No image</span><?php endif;?>
        </a>
    </div>
    <div class="news-event-right">
        <?php 
            // Duyệt các bài còn lại
            foreach ($getposts as $post):
                setup_postdata($post);

                $date = get_the_date('d/m/Y', $post);
                $title = get_the_title($post);
                $permalink = get_permalink($post);
                $thumbnail = get_the_post_thumbnail_url($post, 'thumbnail');
        ?>
        <a href="<?=$permalink?>" class="news-event-right-item">
            <p href="<?=$permalink?>" class="news-event-right-img">
                <?php if($thumbnail!=''):?><img src="<?=$thumbnail?>"><?php else: ?><span class="box-noneImg">No image</span><?php endif;?>
            </p>
            <div class="news-event-right-info">
                <div>
                    <p><?=$date?></p>
                    <h3><?=$title?></h3>
                </div>
            </div>
        </a>
        <?php endforeach;
            wp_reset_postdata(); // Reset sau khi dùng vòng lặp tùy chỉnh
        ?>
    </div>
</div>


<?php
        $output = ob_get_clean(); 
        return $output;
    endif;
}
add_shortcode( 'tintuc_sukien_shortcode', 'show_tintuc_sukien' );