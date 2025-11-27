<?php

add_shortcode( 'gallery-detail-shortcode', 'gallery_detail_init' );

function gallery_detail_init($attr) {
    $option = shortcode_atts(['id'=>''],$attr);
    $ourwork_carousel_list = get_field('ourwork_carousel_list',$option['id']);

    if(isset($ourwork_carousel_list)):
        ob_start();
?>


<div class="detailPost-gallery" data-aos="fade-up">
    <!-- ARROWS -->
    <div>
        <span class="mySwiper-carousel-prev mySwiper-carousel-btn"><svg width="21" height="36" viewBox="0 0 21 36" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M19 2L3 18L19 34" stroke="#666666" stroke-width="4" stroke-linecap="round"/> </svg></span>
        <span class="mySwiper-carousel-next mySwiper-carousel-btn"><svg width="21" height="36" viewBox="0 0 21 36" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M19 2L3 18L19 34" stroke="#666666" stroke-width="4" stroke-linecap="round"/> </svg></span>
    </div>

    <!-- SWIPER -->
    <div class="swiper mySwiper-carousel">
        <div class="swiper-wrapper">
            <?php foreach($ourwork_carousel_list as $k=>$v):
                $photo = $v['ourwork_carousel_photo'];
                $title = $v['ourwork_carousel_title'];
                $description = $v['ourwork_carousel_description'];
            ?>
            <div class="swiper-slide">
                <div class="detailPost-carousel-item">
                    <p class="detailPost-carousel-photo">
                        <?php if($photo!=''):?><img src="<?=$photo?>" alt="gallery">
                        <?php else:?>
                            <span class="box-noneImg">No image</span>
                        <?php endif;?>
                    </p>
                    <div class="detailPost-carousel-info">
                        <h3><?=$title?></h3>
                        <div class="detailPost-carousel-des"><?=$description?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
    

<?php 
        $output = ob_get_clean(); 
        return $output;
    endif;
}