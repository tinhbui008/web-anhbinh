<?php

add_shortcode( 'widenetwork_shortcode', 'widenetwork_data' );

function widenetwork_data() {
        $getposts = get_posts(array(
                'showposts' => -1,
                'post_type' => 'wide_network',
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
    <div class="h-widenetwork-container">
        <?php
            foreach($getposts as $k=>$v):
                $id = (int)$v->ID;
                $title = $v->post_title;
                $excerpt = $v->post_excerpt;
                //$service_type = get_field('service_type',$id);
                //$service_icon = get_field('service_icon',$id);
                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                //$permalink = get_the_permalink($id);
        ?>
            <div class="h-widenetwork-item <?=($k==0) ? 'h-widenetwork-item-active' : ''?>">
                <span class="h-widenetwork-arrow"><svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 60 60"><path d="M58.45,31.32a1.86,1.86,0,0,0,0-2.64L46.55,16.77a1.87,1.87,0,1,0-2.65,2.65L54.48,30,43.9,40.58a1.87,1.87,0,1,0,2.65,2.65ZM1,31.87H57.13V28.13H1Z"></path></svg></span>
                <div class="h-widenetwork-info">
                    <!-- TITLE -->
                    <div class="h-widenetwork-title"><?=$title?></div>
                    <div class="h-widenetwork-box">
                        <!-- IMG -->
                        <div class="h-widenetwork-box-img">
                            <?php if($img):?><img src="<?=$img?>" alt="service" class="h-widenetwork-img"><?php else:?><span class="no-image">No image</span><?php endif;?>
                        </div>                        
                    </div>
                    <!-- SHADOW -->
                    <span class="h-widenetwork-box-shadow"></span>
                    <!-- CONTENT -->
                    <div class="h-widenetwork-box-excerpt"><div><?=$excerpt?></div></div>
                </div>
            </div>
        <?php endforeach;?>
    </div>

<?php 
    $output = ob_get_clean(); 
    return $output;
    endif;
}