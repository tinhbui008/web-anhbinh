<?php
/**
 * Posts content single.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$category_id = 0;
$categories = get_the_category();
if ( ! empty( $categories ) ) {
	$category_id = $categories[0]->term_id;
}

$idPost = get_the_ID();

?>


<div class="entry-content single-page single-page-detail <?=($category_id!=71) ? 'section-page-detail' : 'ourwork-page-detail'?>">
	<!-- IMAGE POST -->
	<?php
		//$img = get_the_post_thumbnail_url($idPost, 'thumnail', array( 'class' =>'thumnail') );
	?>

	<!-- DATE POST -->
	<div class="detailPost-header">
		<h1 class="detailPost-title"><?php the_title(); ?></h1>
		<div class="detailPost-author">Ngày đăng: <?=get_the_date('h:i d/m/Y')?> - Đăng bởi <?=get_the_author();?></div>
	</div>

	<!-- FORM SUBMIT TUYỂN DỤNG -->
	<?php
		echo do_shortcode('[tuyendung-apply-shortcode post_id="'.get_the_ID().'"]');
	?>

	<!-- CONTENT -->
	<div class="detailPost-content"><?php the_content(); ?></div>

	<?php
		wp_link_pages();
	?>

	<?php if ( get_theme_mod( 'blog_share', 1 ) ) {
		// SHARE ICONS
		echo '<div class="blog-share text-center">';
		echo '<div class="is-divider medium"></div>';
		echo do_shortcode( '[share]' );
		echo '</div>';
	} ?>
</div>

<span class="page-tuyendung-icon7"><img src="<?=IMG?>/images/tuyendung/td_i7.png" alt="icon" width="100%"></span>
<span class="page-tuyendung-icon2"><img src="<?=IMG?>/images/tuyendung/td_i2.png" alt="icon" width="100%"></span>
<span class="page-tuyendung-icon1"><img src="<?=IMG?>/images/tuyendung/td_i1.png" alt="icon"></span>
<span class="page-tuyendung-icon3"><img src="<?=IMG?>/images/tuyendung/td_i3.png" alt="icon"></span>
<span class="page-tuyendung-icon8"><img src="<?=IMG?>/images/tuyendung/td_i8.png" alt="icon"></span>


<!-- BÀI VIẾT LIÊN QUAN -->
<?php
  	//echo do_shortcode('[relativePost-shortcode post_id="'.get_the_ID().'"]');
?>



<?php if ( get_theme_mod( 'blog_single_footer_meta', 1 ) ) : ?>
	<footer class="entry-meta text-<?php echo get_theme_mod( 'blog_posts_title_align', 'center' ); ?>">
		<?php
		/* translators: used between list items, there is a space after the comma */
		$category_list = get_the_category_list( __( ', ', 'flatsome' ) );

		/* translators: used between list items, there is a space after the comma */
		$tag_list = get_the_tag_list( '', __( ', ', 'flatsome' ) );


		// But this blog has loads of categories so we should probably display them here.
		if ( '' != $tag_list ) {
			$meta_text = __( 'This entry was posted in %1$s and tagged %2$s.', 'flatsome' );
		} else {
			$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'flatsome' );
		}

		printf( $meta_text, $category_list, $tag_list, get_permalink(), the_title_attribute( 'echo=0' ) );
		?>
	</footer>
<?php endif; ?>

<?php if ( get_theme_mod( 'blog_author_box', 1 ) ) : ?>
	<div class="entry-author author-box">
		<div class="flex-row align-top">
			<div class="flex-col mr circle">
				<div class="blog-author-image">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), apply_filters( 'flatsome_author_bio_avatar_size', 90 ) ); ?>
				</div>
			</div>
			<div class="flex-col flex-grow">
				<h5 class="author-name uppercase pt-half">
					<?php the_author_meta( 'display_name' ); ?>
				</h5>
				<p class="author-desc small"><?php the_author_meta( 'description' ); ?></p>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ( get_theme_mod( 'blog_single_next_prev_nav', 1 ) ) :
	flatsome_content_nav( 'nav-below' );
endif; ?>
