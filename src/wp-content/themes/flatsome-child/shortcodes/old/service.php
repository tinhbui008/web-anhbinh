<?php

add_shortcode( 'service_shortcode', 'service_data' );

function service_data() {
        $getposts = get_posts(array(
                'showposts' => -1,
                'post_type' => 'quality_service',
                //'category__in' => 36, // Lay bai viet cung theo category
                /*'orderby'   => array(
                    'date' =>'DESC',
                )*/
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>
      
    <!-- ARROWS  -->
    <div class="h-service-arrows">
        <span><svg width="143px" height="16px" fill="#f5752f" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 143 16" style="enable-background:new 0 0 143 16;" xml:space="preserve"><g>	<path d="M119.8,10.3h9.9c0.4,0,0.6-0.3,0.6-0.6V0.5c0-0.4-0.3-0.6-0.6-0.6h-9.9c-0.4,0-0.6,0.3-0.6,0.6v9.1  C119.2,10,119.5,10.3,119.8,10.3z M124,0.9h1.7l0,2.4H124V0.9z M120.2,0.9h2.8v2.8l0.1,0.6h3.1l0.6-0.1l0-3.3h2.7v8.4h-9.2V0.9z"></path>	<path d="M142.5,8.6l-2.9-4.7c-0.1-0.2-0.4-0.4-0.6-0.4h-3.7V2.6c0-0.3-0.3-0.6-0.6-0.6h-2.3c-0.3,0-0.5,0.2-0.5,0.5  s0.2,0.5,0.5,0.5h1.9v10h-4.6c-0.2-0.9-0.9-1.7-1.9-1.9c-0.7-0.2-1.3,0-1.9,0.3s-1,0.9-1.1,1.6c0,0,0,0,0,0h-2v-1  c0-0.3-0.2-0.5-0.5-0.5s-0.5,0.2-0.5,0.5v1.4c0,0.3,0.3,0.6,0.6,0.6h2.3c0,0.1,0.1,0.3,0.1,0.4H0.3v1h125v-0.2  c0.3,0.4,0.8,0.7,1.3,0.8c0.2,0,0.4,0.1,0.6,0.1c1.1,0,2.2-0.8,2.4-1.9v0c0,0,0-0.1,0-0.1h6.4c0.1,0.5,0.3,0.9,0.7,1.3  c0.5,0.5,1.1,0.7,1.8,0.7c0.6,0,1.3-0.2,1.8-0.7c0.4-0.4,0.6-0.8,0.7-1.3h0.4c0.8,0,1.5-0.7,1.5-1.5V9.3  C142.7,9,142.7,8.8,142.5,8.6z M141.3,8.5h-3l-0.4-2.3h2L141.3,8.5z M128.6,13.9c-0.2,0.8-1,1.3-1.8,1.1c-0.8-0.2-1.3-1-1.1-1.8  c0.2-0.7,0.8-1.2,1.5-1.2c0.1,0,0.2,0,0.3,0C128.3,12.3,128.8,13.1,128.6,13.9z M139.5,14.7c-0.6,0.6-1.5,0.6-2.1,0  c-0.6-0.6-0.6-1.5,0-2.1c0.3-0.3,0.7-0.4,1.1-0.4s0.8,0.1,1.1,0.4C140.1,13.1,140.1,14.1,139.5,14.7z M141.3,13.1h-0.4  c-0.1-0.5-0.3-0.9-0.7-1.2c-1-1-2.6-1-3.5,0c-0.4,0.4-0.6,0.8-0.7,1.2h-0.7V4.5h3.6l0.5,0.7h-1.7c-0.2,0-0.4,0.1-0.5,0.2  c-0.1,0.2-0.2,0.4-0.1,0.6l0.5,2.8c0.1,0.4,0.4,0.6,0.8,0.6h3.6v3.1C141.7,12.9,141.5,13.1,141.3,13.1z"></path></g></svg></span>
        <div class="h-service-btns">
            <span class="swiperDev-btn-prev service-btn-prev"><svg width="30px" height="30px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M1.55,28.68a1.86,1.86,0,0,0,0,2.64l11.9,11.91a1.87,1.87,0,1,0,2.65-2.65L5.52,30,16.1,19.42a1.87,1.87,0,1,0-2.65-2.65ZM59,28.13H2.87v3.74H59Z"></path></svg></span>
            <span class="swiperDev-btn-next service-btn-next"><svg width="30px" height="30px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M58.45,31.32a1.86,1.86,0,0,0,0-2.64L46.55,16.77a1.87,1.87,0,1,0-2.65,2.65L54.48,30,43.9,40.58a1.87,1.87,0,1,0,2.65,2.65ZM1,31.87H57.13V28.13H1Z"></path></svg></span>
        </div>
    </div>

    <!-- SWIPER -->
    <div class="h-service-container">
        <div class="swiper mySwiper-service">
            <div class="swiper-wrapper">
                <?php
                    foreach($getposts as $k=>$v):
                        $id = (int)$v->ID;
                        $title = $v->post_title;
                        //$excerpt = $v->post_excerpt;
                        $service_type = get_field('service_type',$id);
                        $service_icon = get_field('service_icon',$id);
                        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                        //$permalink = get_the_permalink($id);
                ?>

                    <div class="swiper-slide">
                        <div class="h-service-item">
                            <?php if($img):?><img src="<?=$img?>" alt="service" class="h-service-img"><?php else:?><span class="no-image">No image</span><?php endif;?>
                            <div class="h-service-info">
                                <div class="h-service-info-top">
                                    <span class="h-service-icon"><img src="<?=$service_icon?>" alt="icon" width="24px" height="24px"></span>
                                    <span class="h-service-type border-line"><?=$service_type?></span>
                                </div>
                                <div class="h-service-info-bottom">0<?=($k+1).'. '.$title?></div>
                            </div>
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