<?php
// ### CLICK TAB
function thuvien_sukiensapden_init() {

    $getposts = get_posts(array(
            'showposts' => -1,
            'category__in' => ID_THUVIEN_CATEGORY, // Lay bai viet cung theo category
            'orderby'   => array(
                'date' =>'DESC',
            )
        )
    );

    ob_start();
?>


<div class="thuvien-sksd-container">
    <!-- ARROW -->
    <div class="thuvien-sksd-arrows">
        <span class="thuvien-btn-prev"><svg width="20" height="20" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M20 24L12 16L20 8" stroke="#6D6D6D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
        <span class="thuvien-btn-next"><svg width="20" height="20" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M20 24L12 16L20 8" stroke="#6D6D6D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
    </div>
    <!-- SWIPER -->
    <div class="swiper-thuvien">
        <div class="swiper-wrapper">
            <?php if (isset($getposts)) :
                foreach ($getposts as $v) :
                    $id = $v->ID;
                    $title = $v->post_title;
                    $excerpt = $v->post_excerpt;
                    $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') ); 
                    $permalink = get_field('thuvien_linkdieuhuong',$id);
                    $permalink = ($permalink!='') ? $permalink : get_the_permalink($id);//get_the_permalink($id);
                    
            ?>
            <div class="swiper-slide">
                <div class="thuvien-sksd-item">
                    <div class="thuvien-sksd-info">
                        <div>
                            <h3><?=$title?></h3>
                            <div><?=$excerpt?></div>
                        </div>
                        <a href="<?=$permalink?>">Xem chi tiết</a>
                    </div>
                    <div class="thuvien-sksd-img"><img src="<?=($img) ? $img : 'https://placehold.co/990x550'?>" alt="su-kien-sap-den" class="img-object-cover"></div>
                    <div class="thuvien-sksd-info thuvien-sksd-info-mobile">
                        <div>
                            <h3><?=$title?></h3>
                            <div><?=$excerpt?></div>
                        </div>
                        <a href="<?=$permalink?>">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<?php
    $output = ob_get_clean();
    return $output;
}
add_shortcode( 'thuvien_sukiensapden_shortcode', 'thuvien_sukiensapden_init' );