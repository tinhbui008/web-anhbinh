<?php

// ### SHOW DS TABS CATEGORY
function show_child_categories_list( $atts ) {
    ob_start();

    $atts = shortcode_atts( array(
        'parent' => 0, // ID danh mục cha
    ), $atts, 'post_child_categories' );

    $categories = get_categories( array(
        'parent'     => intval( $atts['parent'] ),
        'hide_empty' => false,
    ) );
?>

<div class="s-daotao-listCategory">
    <?php if($categories): foreach($categories as $category):?>
        <a data-id="<?= $category->term_id; ?>" class="s-daotao-tab"><?=esc_html( $category->name )?></a>
    <?php endforeach;endif;?>
</div>

<?php
    $output = ob_get_clean(); 
    return $output;
}
add_shortcode( 'post_child_categories', 'show_child_categories_list' );



// ### CLICK TAB
function show_post_categories_list( $atts ) {
    ob_start();
?>

<div id="tab-show-daotao"></div>

<?php
    $output = ob_get_clean(); 
    return $output;
}
add_shortcode( 'post_daotao_categories', 'show_post_categories_list' );