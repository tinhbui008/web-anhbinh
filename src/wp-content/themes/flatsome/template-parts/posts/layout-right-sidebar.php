<?php
/**
 * Posts layout right sidebar.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

do_action('flatsome_before_blog');
?>

<?php if(!is_single() && flatsome_option('blog_featured') == 'top'){ get_template_part('template-parts/posts/featured-posts'); } ?>

<div class="dev-layoutRight-contain">
	<div class="dev-layoutRight-left">
		<?php if(!is_single() && flatsome_option('blog_featured') == 'content'){ get_template_part('template-parts/posts/featured-posts'); } ?>
		<?php
			if(is_single()){
				get_template_part( 'template-parts/posts/single');
				comments_template();
			} elseif(flatsome_option('blog_style_archive') && (is_archive() || is_search())){
				get_template_part( 'template-parts/posts/archive', flatsome_option('blog_style_archive') );
			} else {
				get_template_part( 'template-parts/posts/archive', flatsome_option('blog_style') );
			}
		?>
	</div>
	<div class="dev-layoutRight-right">
		<?php flatsome_sticky_column_open( 'blog_sticky_sidebar' ); ?>
		<?php get_sidebar(); ?>
		<?php flatsome_sticky_column_close( 'blog_sticky_sidebar' ); ?>
	</div>
</div>

<!-- UL BLOCK CAREER -->
<?= do_shortcode('[block id="career-opportunities"]'); ?>

<?php
	do_action('flatsome_after_blog');
?>