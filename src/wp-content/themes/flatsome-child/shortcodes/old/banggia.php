<?php

add_shortcode( 'banggia_shortcode', 'banggia_data' );

function banggia_data() {
    $getCategories = get_categories(array(
            'showposts' => -1,
            'taxonomy' => 'banggia_category',
            'post_type' => 'banggia'
        )
    );

    if(isset($getCategories)):
        ob_start();

        foreach($getCategories as $category):
            $id_cate = (int)$category->term_id;
            $title_cate = $category->cat_name;
            $description_cate = $category->category_description; 
            $banner = get_field('banner_banggia','term_'.$id_cate);
            $text_color = get_field('text_color','term_'.$id_cate);
            $title_color = get_field('title_color','term_'.$id_cate);
?>
<div style="background:url('<?=$banner?>') no-repeat center;background-size:cover;" class="page-banggia-container">
    <div class="page-container">
        <div class="page-banggia-boxTitle">
            <h2 style="color:<?=$title_color?>"><?=$title_cate?></h2>
            <div style="color:<?=$text_color?>"><?=$description_cate?></div>
        </div>

        <?php
            $args = array('post_type' => 'banggia',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'banggia_category',
                        'field' => 'slug',
                        'terms' => $category->slug,
                    ),
                ),
            );
            $getposts = new WP_Query($args);
            if($getposts->have_posts()):
        ?>

        <div class="page-banggia-list">
            <div class="swiper swiper-banggia swiper-banggia-<?=$id_cate?>" data-id="<?=$id_cate?>">
                <div class="swiper-wrapper">
                    <?php
                        while($getposts->have_posts()) : $getposts->the_post();
                            $id = (int)get_the_ID();
                            $title = get_the_title();
                            $permalink = get_the_permalink($id);
                            $giahientai = get_field('giahientai',$id);
                            $giacu = get_field('giacu',$id);
                            $feature_mota = get_field('feature_mota',$id);
                            $feature_list = get_field('feature_list',$id);
                            $excerpt = get_the_excerpt();                            
                    ?>
                    
                    <?php for($i=0;$i<3;$i++):?>
                    <div class="swiper-slide">
                        <div class="page-banggia-box">
                            <div class="page-banggia-boxTop">
                                <h3><?=$title?></h3>
                                <p class="page-banggia-boxTop-giahientai"><?=number_format($giahientai, 0, ',', ',')?> đ<span>/month</span></p>
                                <p class="page-banggia-boxTop-giaccu"><?=number_format($giacu, 0, ',', ',')?> đ<span>/month</span></p>
                                <p class="page-banggia-boxTop-mota"><?=$feature_mota?></p>
                                <a href="<?=$permalink?>" class="page-banggia-boxTop-link">GET STARTED</a>
                            </div>
                            <div class="page-banggia-boxBottom">
                                <p class="page-banggia-boxBottom-title">Feature</p>
                                <p class="page-banggia-boxBottom-mota"><?=$excerpt?></p>
                                <div class="page-banggia-boxBottom-list">
                                    <?php if($feature_list):?>
                                        <?php foreach($feature_list as $item): ?>
                                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M0.5 0C0.367392 0 0.240215 0.0526785 0.146447 0.146447C0.0526785 0.240215 0 0.367392 0 0.5V11.5C0 11.6326 0.0526785 11.7598 0.146447 11.8536C0.240215 11.9473 0.367392 12 0.5 12H11.5C11.6326 12 11.7598 11.9473 11.8536 11.8536C11.9473 11.7598 12 11.6326 12 11.5V0.5C12 0.367392 11.9473 0.240215 11.8536 0.146447C11.7598 0.0526785 11.6326 0 11.5 0H0.5ZM5.354 8.354L9.854 3.854L9.147 3.146L5 7.293L2.854 5.146L2.146 5.854L4.646 8.354C4.69245 8.40056 4.74762 8.43751 4.80837 8.46271C4.86911 8.48792 4.93423 8.50089 5 8.50089C5.06577 8.50089 5.13089 8.48792 5.19163 8.46271C5.25238 8.43751 5.30755 8.40056 5.354 8.354Z" fill="#03386E"/> </svg><span><?=$item['feature_title']?></span></div>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor;?>

                    <?php endwhile;?>
                </div>
            </div>
        </div>

        <?php
            endif;
        ?>
    </div>
</div>

<?php   
        endforeach;

        $output = ob_get_clean(); 
        return $output;
        endif;
    }
?>