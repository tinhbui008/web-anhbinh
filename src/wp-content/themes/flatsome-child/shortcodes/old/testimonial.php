<?php

add_shortcode( 'testimonial_shortcode', 'testimonial_data' );
    function testimonial_data() {
        $getposts = get_posts(array(
                'showposts' => -1,
                'post_type' => 'testimonials',
                'orderby'   => array(
                    'date' =>'DESC',
                )
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>
      
    <div class="testimonial-contain overflow-hidden container-page">
        <div class="swiper mySwiper-testimonial">
            <div class="swiper-wrapper">
                <?php
                    foreach($getposts as $k=>$v):
                        $id = (int)$v->ID;
                        $name = $v->post_title;
                        //$content = $v->post_content;
                        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                        $position = get_field('testimonials_postion',$id);
                        $title = get_field('testimonials_title',$id);
                        $description = get_field('testimonials_description',$id);
                ?>
                <div class="swiper-slide">
                    <div class="testimonial-box">
                        <h3 class="testimonial-title">"<?=$title?>"</h3>
                        <div class="testimonial-description"><?=$description?></div>
                        <div class="testimonial-info">
                            <div class="testimonial-info-left">
                                <p class="testimonial-info-img"><img src="<?=$img?>" alt="testimonial"></p>
                                <div>
                                    <p class="testimonial-name"><?=$name?></p>
                                    <span class="testimonial-position"><?=$position?></span>
                                </div>
                            </div>
                            <span class="testimonial-quote"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M50.5,29.48H33V3.25H59V31.71l-.15,1.09c-.09.73-.19,1.44-.32,2.15A26.31,26.31,0,0,1,48.07,51.6a25.78,25.78,0,0,1-15,5.15H33v-8A18.81,18.81,0,0,0,51.05,30v-.56Z"></path><path d="M18.53,29.48H1V3.25H27V31.71c-.06.37-.1.73-.15,1.09-.1.73-.19,1.44-.33,2.15A26.27,26.27,0,0,1,16.09,51.6a25.75,25.75,0,0,1-15,5.15H1v-8A18.47,18.47,0,0,0,15.72,40.7,18.34,18.34,0,0,0,19.08,30v-.56Z"></path></svg></span>
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