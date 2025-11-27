<?php

add_shortcode( 'slider_shortcode', 'slider_data' );

function slider_data() {
        $getposts = get_posts(array(
                'showposts' => -1,
                'post_type' => 'slider',
                //'category__in' => 36, // Lay bai viet cung theo category
                /*'orderby'   => array(
                    'date' =>'DESC',
                )*/
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>
      
    <div class="slider-container">
        <div class="slider-boxes">
            <div class="swiper mySwiper-slider">
                <div class="swiper-wrapper">
                    <?php
                        foreach($getposts as $k=>$v):
                            $id = (int)$v->ID;
                            $title = $v->post_title;
                            $excerpt = $v->post_excerpt;
                            $slider_link = get_field('slider_link',$id);
                            $slider_descript = get_field('slider_descript',$id);
                            //$img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                            //$permalink = get_the_permalink($id);
                    ?>

                        <div class="swiper-slide">
                            <div class="slider-item">
                                <div class="slider-item-top">
                                    <p class="slider-item-title"><?=$title?></p>
                                    <p class="slider-item-descript"><?=$slider_descript?></p>
                                </div>
                                <div class="slider-item-info">
                                    <div class="slider-item-excerpt"><?=$excerpt?></div>
                                    <a href="<?=$slider_link?>" class="slider-item-explore">Explore More</a>
                                </div>
                            </div>
                        </div>

                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }