<?php

add_shortcode( 'blogNews_shortcode', 'blogNews_data' );

function blogNews_data() {
        $getposts = get_posts(array(
                'showposts' => 3,
                //'post_type' => 'wide_network',
                'category__in' => 47, // Lay bai viet cung theo category
                /*'orderby'   => array(
                    'date' =>'DESC',
                )*/
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>

    <!-- LIST -->
    <div class="h-blognews-container">
        <?php
            foreach($getposts as $k=>$v):
                $id = (int)$v->ID;
                $title = $v->post_title;
                $excerpt = $v->post_excerpt;
                //$service_type = get_field('service_type',$id);
                //$service_icon = get_field('service_icon',$id);
                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                $permalink = get_the_permalink($id);
        ?>
            <a href="<?=$permalink?>" class="h-blognews-item">
                <?php if($img):?><img src="<?=$img?>" alt="service" class="h-blognews-img"><?php else:?><span class="no-image">No image</span><?php endif;?>
                <div class="h-blognews-info">
                    <h3 class="h-blognews-title"><?=$title?></h3>
                    <div class="h-blognews-excerpt"><?=$excerpt?></div>
                    <p class="h-blognews-readmore">
                        Read more
                        <span><svg width="24px" fill="white" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"> <path d="M97.5,47.5L74.6,24.6c-1.4-1.4-3.7-1.4-5.1,0c-1.4,1.4-1.4,3.7,0,5.1l16.7,16.7H1.5v7.2h84.8L69.5,70.3 c-1.4,1.4-1.4,3.7,0,5.1c1.4,1.4,3.7,1.4,5.1,0l22.9-22.9C98.9,51.1,98.9,48.9,97.5,47.5z"></path></svg></span>
                    </p>
                </div>
            </a>
        <?php endforeach;?>
    </div>

<?php 
    $output = ob_get_clean(); 
    return $output;
    endif;
}