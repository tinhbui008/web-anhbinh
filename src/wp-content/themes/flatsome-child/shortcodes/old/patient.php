<?php

add_shortcode( 'patient_shortcode', 'patient_data' );

function patient_data() {
        $getposts = get_posts(array(
                'showposts' => 9999,
                'post_type' => 'patient-stories',
                'orderby'   => array(
                    'date' =>'DESC',
                )
            )
        );

        $dem = (count($getposts)<6) ? 2: 1;

        if(isset($getposts)):
            ob_start();
    ?>
      
    <div class="feedback-contain overflow-hidden">
        <!-- INFO -->
        <div id="feedback-info" class="feedback-info feedback-info-active">
            <div class="feedback-comment"></div>
            <p class="feedback-title"></p>
            <p class="feedback-position"></p>
        </div>
        <!-- LIST ITEMS -->
        <div class="feedbackList-item">
            <div class="feedback-nav-prev"> <p class="slide-review-prev"><svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="22" cy="22" r="22" transform="matrix(-1 0 0 1 44 0)" fill="#111111"></circle> <path d="M25 28L19 22L25 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </svg><br> </p></div>
            
            <div class="feedback-nav-next"> <p class="slide-review-next"><svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="22" cy="22" r="22" transform="matrix(-1 0 0 1 44 0)" fill="#111111"></circle> <path d="M25 28L19 22L25 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </svg><br> </p></div>
            
            <div class="swiper swiper-review">
                <div class="swiper-wrapper">
                    <?php 
                    for($i=0;$i<$dem;$i++):
                        foreach($getposts as $k=>$v):
                            $id = (int)$v->ID;
                            $title = $v->post_title;
                            //$content = $v->post_content;
                            $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                            $position = get_field('patient_position',$id);
                            $link = get_field('patient_link',$id);
                            $feedback = get_field('patient_feedback',$id);
                    ?>

                        <div class="swiper-slide ">
                            <div class="feedback-item" data-title="<?=$title?>" data-position="<?=$position?>" data-comment='<?=$feedback?>' data-link="<?=$link?>">
                                <img src="<?=($img) ? $img : 'https://placehold.co/408x517'?>" alt="feedback">
                                <?php if($link!=''):?><a href="<?=$link?>" class="feedback-link" data-fancybox><svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="24" cy="24" r="23" stroke="white" stroke-width="2"/> <path d="M29.6742 23.1563C30.2917 23.5493 30.2917 24.4507 29.6742 24.8437L21.5369 30.022C20.8712 30.4456 20 29.9674 20 29.1783L20 18.8217C20 18.0326 20.8712 17.5544 21.5369 17.978L29.6742 23.1563Z" fill="white"/> </svg></a><?php endif;?>
                            </div>
                        </div>

                    <?php endforeach; endfor;?>
                </div>
            </div>
        </div>
        <!-- ARROWS -->
        <div class="feedback-arrows">
            <span class="slide-review-prev"><svg width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_107_75)"> <path d="M22.9565 8H1.04348M1.04348 8L8.34783 1M1.04348 8L8.34783 15" stroke="#4F4F4F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_107_75"> <rect width="24" height="16" fill="white" transform="matrix(-1 0 0 1 24 0)"/> </clipPath> </defs> </svg></span>
            <span class="slide-review-next"><svg width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_107_75)"> <path d="M22.9565 8H1.04348M1.04348 8L8.34783 1M1.04348 8L8.34783 15" stroke="#4F4F4F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_107_75"> <rect width="24" height="16" fill="white" transform="matrix(-1 0 0 1 24 0)"/> </clipPath> </defs> </svg></span>
        </div>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }
?>