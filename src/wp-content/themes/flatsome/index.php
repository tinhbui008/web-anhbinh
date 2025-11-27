<?php
/**
 * The blog template file.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header();
date_default_timezone_set('Asia/Ho_Chi_Minh');	
?>

<!-- BANNER -->
<?php

	$categories = get_the_category();
	//$category_name = $categories[0]->name;
	
	//$category_id = $categories[0]->cat_ID;
	$category_id = get_query_var('cat');
	//$banner = get_field('banner','category_'.$category_id);
	$banner_text = get_field('banner_text','category_'.$category_id);
	
	//$category_id = get_query_var('cat');
	$category_name = get_cat_name($category_id);

?>

<?php /*if($banner!=''):?>
<div class="banner-section">
	<p class="banner-img" style="background-image:url(<?=$banner?>)"></p>
	<div class="banner-breadcrum">
		<div class="banner-breadcrum-contain">
			<h1 class="banner-breadcrum-title"><?=$category_name?></h1>
			<div class="banner-breadcrum-slug"><?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?></div>
	
			<p class="banner-breadcrum-text"><?=$banner_text?></p>
		</div>
	</div>
</div>
<?php endif;*/?>


<!-- <div class="banner-breadcrum-slug"><?php //flatsome_breadcrumb(); ?></div> -->

<div id="content" class="blog-wrapper blog-archive page-wrapper">
	<?php get_template_part( 'template-parts/posts/layout', get_theme_mod('blog_layout','right-sidebar') ); ?>
</div>

<?php get_footer(); ?>