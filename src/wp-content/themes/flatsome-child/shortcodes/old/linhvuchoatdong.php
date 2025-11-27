<?php

add_shortcode( 'lvhoatdong_shortcode', 'lvhoatdong_data' );

function lvhoatdong_data() {
        $getposts = get_posts(array(
                'showposts' => 9999,
                'category__in' => 35, // Lay bai viet cung theo category
                'orderby'   => array(
                    'date' =>'DESC',
                )
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>
      
    <div class="">
        <div class="swiper swiper-linhvuc">
            <div class="swiper-wrapper">
                <?php
                    foreach($getposts as $k=>$v):
                        $id = (int)$v->ID;
                        $title = $v->post_title;
                        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                        $icon = get_field('icon',$id);
                        $prefix_title = get_field('prefix_title',$id);
                        $main_title = get_field('main_title',$id);
                        $permalink = get_the_permalink($id);
                ?>

                    <div class="swiper-slide ">
                        <div class="linhvuc-Box">
                            <img src="<?=($img) ? $img : 'https://placehold.co/350x540'?>" alt="linh-vuc" class="img-object-cover">
                            <a href="<?=$permalink;?>" class="linhvuc-Box-info">
                                <span class="linhvuc-Box-icon"><img src="<?=($icon) ? $icon : 'https://placehold.co/29x29'?>" alt="icon-linh-vuc"></span>
                                <h3><?=$prefix_title?> <span><?=$main_title?></span></h3>
                                <div class="linhvuc-bg-after">
                                    <span></span>
                                </div>
                                <div class="linhvuc-bg-before"></div>
                                <span class="linhvuc-bg-arrow"></span>
                            </a>
                        </div>
                    </div>

                <?php endforeach;?>
            </div>
        </div>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }
?>