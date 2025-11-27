<?php

// ### SHOW DS TABS CATEGORY
function show_categories_daotao( $atts ) {
    ob_start();

    $categories = get_categories( array(
        'parent'     => 74,
        'hide_empty' => false,
    ) );

    if($categories):
?>

<div class="daotao-list-danhmuc">
    <div class="swiper-daotao">
        <div class="swiper-wrapper">
            <?php  foreach($categories as $category):
                $image_url = z_taxonomy_image_url($category->term_id, 'full', true);
            ?>
                <div class="swiper-slide">
                    <div class="daotao-s2-item">
                        <a href="<?= get_category_link($category->term_id) ?>" class="daotao-s2-title"><?=esc_html( $category->name )?></a>
                        <a href="<?= get_category_link($category->term_id) ?>"><img src="<?=$image_url?>" alt="photo"></a>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>

<?php
    endif;

    $output = ob_get_clean(); 
    return $output;
}
add_shortcode( 'daotao_category', 'show_categories_daotao' );