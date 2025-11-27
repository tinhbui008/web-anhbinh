<?php
add_shortcode( 'breadcrum-shortcode', 'breadcrumInit' );

function breadcrumInit() {
    ob_start();
?>  

    <div class="banner-breadcrum-slug"><?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?></div>
    
<?php 
        $output = ob_get_clean(); 
        return $output;
}