<?php

add_shortcode( 'transportion_shortcode', 'transportion_data' );

function transportion_data() {
        $getposts = get_posts(array(
                'showposts' => -1,
                'post_type' => 'transportion',
                //'category__in' => 36, // Lay bai viet cung theo category
                /*'orderby'   => array(
                    'date' =>'DESC',
                )*/
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>

    <!-- SWIPER -->
    <div class="h-transportion-contain">      
        <div class="swiper mySwiper-transportion">
            <div class="swiper-wrapper">  
                <?php
                    foreach($getposts as $k=>$v):
                        $id = (int)$v->ID;
                        $title = $v->post_title;
                        $excerpt = $v->post_excerpt;
                        $year = get_field('transportion_year',$id);
                        //$service_icon = get_field('service_icon',$id);
                        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                        //$permalink = get_the_permalink($id);
                ?>

                    <div class="swiper-slide h-transportion-slide">
                        <div class="h-transportion-item">
                            <span class="h-transportion-year"><?=$year?></span>
                            <div class="h-transportion-info">
                                <span class="h-transportion-icon"></span>
                                <h3><?=$title?></h3>
                                <div><?=$excerpt?></div>
                            </div>
                        </div>
                    </div>

                <?php endforeach;?>   
            </div>
        </div>
    </div>

    <!-- ARROWS  -->
    <!-- <div class="h-solution-arrows">        
        <div class="h-service-btns">
            <span class="swiperDev-btn-prev solution-btn-prev"><svg width="30px" height="30px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M1.55,28.68a1.86,1.86,0,0,0,0,2.64l11.9,11.91a1.87,1.87,0,1,0,2.65-2.65L5.52,30,16.1,19.42a1.87,1.87,0,1,0-2.65-2.65ZM59,28.13H2.87v3.74H59Z"></path></svg></span>
            <span class="swiperDev-btn-next solution-btn-next"><svg width="30px" height="30px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M58.45,31.32a1.86,1.86,0,0,0,0-2.64L46.55,16.77a1.87,1.87,0,1,0-2.65,2.65L54.48,30,43.9,40.58a1.87,1.87,0,1,0,2.65,2.65ZM1,31.87H57.13V28.13H1Z"></path></svg></span>
        </div>
    </div> -->

<?php 
    $output = ob_get_clean(); 
    return $output;
    endif;
}