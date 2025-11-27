<?php
/**
 * Posts single.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

if ( have_posts() ) : 

	$categories = get_the_category();
	$category_id = $categories[0]->cat_ID;
	//dd($categories);
?>

	<?php /* Start the Loop */ ?>
	<?php if($category_id==18): // PAGE: the team single page ?>
		<?php while ( have_posts() ) : the_post(); 
			$id = (int)get_the_ID();
			$link_appoinment = get_field('ourteam_appoinment',$id);
		?>
			<div class="ourteam-single-contain">
				<div class="ourteam-single-left">					
					<?php if(has_post_thumbnail()) { ?>
						<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
							<?php the_post_thumbnail( $image_size ); ?>
						</div>
					<?php }else{ ?>
						<div class="s-ourteam-item">
							<div class="box-image">
								<span class="box-noneImg">No image</span>
							</div>
						</div>
					<?php }?>
					<a href="<?=($link_appoinment!='') ? $link_appoinment : 'javascript:void(0)'?>" target="_blank" class="ourteam-single-book">Book appointment</a>
					<a href="<?=get_category_link($category_id)?>" class="ourteam-single-back">Back to search</a>			
				</div>
				<div class="ourteam-single-right">
					<div class="article-inner <?php flatsome_blog_article_classes(); ?>">
						<?php
							/*if(flatsome_option('blog_post_style') == 'default' || flatsome_option('blog_post_style') == 'inline'){
								get_template_part('template-parts/posts/partials/entry-header', flatsome_option('blog_posts_header_style') );
							}*/
						?>
						<h1 class="ourteam-single-title"><?php echo esc_attr( the_title() ); ?></h1>
						<?php get_template_part( 'template-parts/posts/content', 'single' ); ?>
					</div>
				</div>
			</div>
		<?php endwhile; ?>

	<?php else:?>

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="article-inner <?php flatsome_blog_article_classes(); ?>">
					<?php
						if(flatsome_option('blog_post_style') == 'default' || flatsome_option('blog_post_style') == 'inline'){
							get_template_part('template-parts/posts/partials/entry-header', flatsome_option('blog_posts_header_style') );
						}
					?>
					<?php get_template_part( 'template-parts/posts/content', 'single' ); ?>
				</div>
			</article>
		<?php endwhile; ?>

	<?php endif;?>

<?php else : ?>

	<?php get_template_part( 'no-results', 'index' ); ?>

<?php endif; ?>
