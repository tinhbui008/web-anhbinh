<?php
// [blog_posts]
function shortcode_latest_from_blog($atts, $content = null, $tag = '' ) {

	$defined_atts = $atts;

	extract($atts = shortcode_atts(array(
		"_id" => 'row-'.rand(),
		'style' => '',
		'class' => '',
		'visibility' => '',

		// Layout
		"columns" => '4',
		"columns__sm" => '1',
		"columns__md" => '',
		'col_spacing' => '',
		"type" => 'slider', // slider, row, masonery, grid
		'width' => '',
		'grid' => '1',
		'grid_height' => '600px',
		'grid_height__md' => '500px',
		'grid_height__sm' => '400px',
		'slider_nav_style' => 'reveal',
		'slider_nav_position' => '',
		'slider_nav_color' => '',
		'slider_bullets' => 'false',
	 	'slider_arrows' => 'true',
		'auto_slide' => 'false',
		'infinitive' => 'true',
		'depth' => '',
   		'depth_hover' => '',
		// Relay
		'relay' => '',
		'relay_control_result_count' => 'true',
		'relay_control_position' => 'bottom',
		'relay_control_align' => 'center',
		'relay_id' => '',
		'relay_class' => '',
		// posts
		'posts' => '8',
		'ids' => '', // Custom IDs
		'cat' => '',
		'category' => '', // Added for Flatsome v2 fallback
		'excerpt' => 'visible',
		'excerpt_length' => 15,
		'offset' => '',
		'orderby' => 'date',
		'order' => 'DESC',
		'tags' => '',
		'page_number' => '1',

		// Read more
		'readmore' => '',
		'readmore_color' => '',
		'readmore_style' => 'outline',
		'readmore_size' => 'small',

		// div meta
		'post_icon' => 'true',
		'comments' => 'true',
		'show_date' => 'badge', // badge, text
		'badge_style' => '',
		'show_category' => 'false',

		//Title
		'title_size' => 'large',
		'title_style' => '',

		// Box styles
		'animate' => '',
		'text_pos' => 'bottom',
	  	'text_padding' => '',
	  	'text_bg' => '',
	  	'text_size' => '',
	 	'text_color' => '',
	 	'text_hover' => '',
	 	'text_align' => 'center',
	 	'image_size' => 'medium',
	 	'image_width' => '',
	 	'image_radius' => '',
	 	'image_height' => '56%',
	    'image_hover' => '',
	    'image_hover_alt' => '',
	    'image_overlay' => '',
	    'image_depth' => '',
	    'image_depth_hover' => '',

	), $atts));

	// Stop if visibility is hidden
  if($visibility == 'hidden') return;

	ob_start();

	$classes_box = array();
	$classes_image = array();
	$classes_text = array();

	// Fix overlay color
    if($style == 'text-overlay'){
      $image_hover = 'zoom';
    }
    $style = str_replace('text-', '', $style);

	// Fix grids
	if($type == 'grid'){
	  if(!$text_pos) $text_pos = 'center';
	  $columns = 0;
	  $current_grid = 0;
	  $grid = flatsome_get_grid($grid);
	  $grid_total = count($grid);
	}

	// Fix overlay
	if($style == 'overlay' && !$image_overlay) $image_overlay = 'rgba(0,0,0,.25)';

	// Set box style
	if($style) $classes_box[] = 'box-'.$style;
	if($style == 'overlay') $classes_box[] = 'dark';
	if($style == 'shade') $classes_box[] = 'dark';
	if($style == 'badge') $classes_box[] = 'hover-dark';
	if($text_pos) $classes_box[] = 'box-text-'.$text_pos;

	if($image_hover)  $classes_image[] = 'image-'.$image_hover;
	if($image_hover_alt)  $classes_image[] = 'image-'.$image_hover_alt;
	if($image_height) $classes_image[] = 'image-cover';

	// Text classes
	if($text_hover) $classes_text[] = 'show-on-hover hover-'.$text_hover;
	if($text_align) $classes_text[] = 'text-'.$text_align;
	if($text_size) $classes_text[] = 'is-'.$text_size;
	if($text_color == 'dark') $classes_text[] = 'dark';

	$css_args_img = array(
	  array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%' ),
	  array( 'attribute' => 'width', 'value' => $image_width, 'unit' => '%' ),
	);

	$css_image_height = array(
      array( 'attribute' => 'padding-top', 'value' => $image_height),
  	);

	$css_args = array(
      array( 'attribute' => 'background-color', 'value' => $text_bg ),
      array( 'attribute' => 'padding', 'value' => $text_padding ),
  	);

    // Add Animations
	if($animate) {$animate = 'data-animate="'.$animate.'"';}

	$classes_text = implode(' ', $classes_text);
	$classes_image = implode(' ', $classes_image);
	$classes_box = implode(' ', $classes_box);

	// Repeater styles
	$repeater['id'] = $_id;
	$repeater['tag'] = $tag;
	$repeater['type'] = $type;
	$repeater['class'] = $class;
	$repeater['visibility'] = $visibility;
	$repeater['style'] = $style;
	$repeater['slider_style'] = $slider_nav_style;
	$repeater['slider_nav_position'] = $slider_nav_position;
	$repeater['slider_nav_color'] = $slider_nav_color;
	$repeater['slider_bullets'] = $slider_bullets;
    $repeater['auto_slide'] = $auto_slide;
	$repeater['infinitive'] = $infinitive;
	$repeater['row_spacing'] = $col_spacing;
	$repeater['row_width'] = $width;
	$repeater['columns'] = $columns;
	$repeater['columns__md'] = $columns__md;
	$repeater['columns__sm'] = $columns__sm;
	$repeater['depth'] = $depth;
	$repeater['depth_hover'] = $depth_hover;

	$args = array(
		'post_status' => 'publish',
		'post_type' => 'post',
		'offset' => $offset,
		'cat' => $cat,
		'tag__in' => $tags ? array_filter( array_map( 'trim', explode( ',', $tags ) ) ) : '',
		'posts_per_page' => $posts,
		'paged' => $page_number,
		'ignore_sticky_posts' => true,
		'orderby'             => $orderby,
		'order'               => $order,
	);

	// Added for Flatsome v2 fallback
	if ( get_theme_mod('flatsome_fallback', 0) && $category ) {
		$args['category_name'] = $category;
	}

	// If custom ids
	if ( !empty( $ids ) ) {
		$ids = explode( ',', $ids );
		$ids = array_map( 'trim', $ids );

		$args = array(
			'post__in' => $ids,
            'post_type' => array(
                'post',
                'featured_item', // Include for its tag archive listing.
            ),
			'numberposts' => -1,
			'orderby' => 'post__in',
			'posts_per_page' => 9999,
			'ignore_sticky_posts' => true,
		);

		// Include for search archive listing.
		if ( is_search() ) {
			$args['post_type'][] = 'page';
		}
	}

$recentPosts = new WP_Query( $args );

	Flatsome_Relay::render_container_open( $recentPosts, $tag, $defined_atts, $atts );

	if ( $type == 'grid' ) {
		flatsome_get_grid_height( $grid_height, $_id );
	}

get_flatsome_repeater_start($repeater);

$pos = 0;

while ( $recentPosts->have_posts() ) : $recentPosts->the_post();
			$pos ++;
			$id = (int)get_the_ID();
			$col_class    = array( 'post-item' );
			$show_excerpt = $excerpt;

			$icon = get_field('service_icon',$id);
			$position = get_field('position',$id);
			

			if(get_post_format() == 'video') $col_class[] = 'has-post-icon';

			if($type == 'grid'){
	        if($grid_total > $current_grid) $current_grid++;
	        $current = $current_grid-1;

	        $col_class[] = 'grid-col';
	        if($grid[$current]['height']) $col_class[] = 'grid-col-'.$grid[$current]['height'];

	        if($grid[$current]['span']) $col_class[] = 'large-'.$grid[$current]['span'];
	        if($grid[$current]['md']) $col_class[] = 'medium-'.$grid[$current]['md'];

	        // Set image size
	        if($grid[$current]['size']) $image_size = $grid[$current]['size'];

	        // Hide excerpt for small sizes
	        if($grid[$current]['size'] == 'thumbnail') $show_excerpt = 'false';
	    }
		?>

		<?php if($style=='daotao'):
			$categories = get_the_category($id);
			$row_category = (isset($categories)) ? $categories[0] : null;
		?>
			<a href="<?php the_permalink() ?>" class="col <?php echo implode(' ', $col_class); ?> s-truyenthong-box khoadaotao-box" <?php echo $animate;?> >
				<div class="box <?php echo $classes_box; ?> s-truyenthong-item khoadaotao-item">

					<?php if(isset($row_category)):?><span class="khoadaotao-category-title"><?=$row_category->name;?></span><?php endif;?>

					<?php if(has_post_thumbnail()) { ?>
						<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
							<?php the_post_thumbnail( $image_size ); ?>
						</div>
					<?php }else{ ?>
						<div class="box-image">
							<span class="box-noneImg">No image</span>
						</div>
					<?php }?>
					<!-- INFO -->
					<div class="s-truyenthong-date"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_1960)"> <path d="M9.99984 4.99984V9.99984L13.3332 11.6665M18.3332 9.99984C18.3332 14.6022 14.6022 18.3332 9.99984 18.3332C5.39746 18.3332 1.6665 14.6022 1.6665 9.99984C1.6665 5.39746 5.39746 1.6665 9.99984 1.6665C14.6022 1.6665 18.3332 5.39746 18.3332 9.99984Z" stroke="#6D6D6D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_296_1960"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg>20/12/2024</div>
					<h3 class="s-truyenthong-title"><?php the_title(); ?></h3>
					<?php if($show_excerpt !== 'false') { ?>
						<div class="s-truyenthong-title-excerpt">
							<?php echo the_excerpt(); ?>
						</div>
					<?php } ?>
				</div>
			</a>
		<?php elseif($style=='thuthach'):?>

			<a href="<?php the_permalink() ?>" class="col <?php echo implode(' ', $col_class); ?> s-thuthach-box" <?php echo $animate;?> >
				<div class="box <?php echo $classes_box; ?> s-thuthach-item">
					<?php if(has_post_thumbnail()) { ?>
						<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
							<?php the_post_thumbnail( $image_size ); ?>
						</div>
					<?php }else{ ?>
						<div class="box-image">
							<span class="box-noneImg">No image</span>					
						</div>
					<?php }?>
					<!-- INFO -->					
					<h3 class="s-thuthach-title"><?php the_title(); ?></h3>
					<?php if($show_excerpt !== 'false') { ?>
						<div class="s-thuthach-title-excerpt">
							<?php echo the_excerpt(); ?>
						</div>
					<?php } ?>					
				</div>
			</a>

		<?php elseif($style=='truyenthong'):?>
			<a href="<?php the_permalink() ?>" class="col <?php echo implode(' ', $col_class); ?> s-truyenthong-box" <?php echo $animate;?> >
				<div class="box <?php echo $classes_box; ?> s-truyenthong-item">
					<?php if(has_post_thumbnail()) { ?>
						<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
							<?php the_post_thumbnail( $image_size ); ?>
						</div>
					<?php }else{ ?>
						<div class="box-image">
							<span class="box-noneImg">No image</span>					
						</div>
					<?php }?>
					<!-- INFO -->
					<div class="s-truyenthong-date"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_1960)"> <path d="M9.99984 4.99984V9.99984L13.3332 11.6665M18.3332 9.99984C18.3332 14.6022 14.6022 18.3332 9.99984 18.3332C5.39746 18.3332 1.6665 14.6022 1.6665 9.99984C1.6665 5.39746 5.39746 1.6665 9.99984 1.6665C14.6022 1.6665 18.3332 5.39746 18.3332 9.99984Z" stroke="#6D6D6D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_296_1960"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg>20/12/2024</div>
					<h3 class="s-truyenthong-title"><?php the_title(); ?></h3>
					<?php if($show_excerpt !== 'false') { ?>
						<div class="s-truyenthong-title-excerpt">
							<?php echo the_excerpt(); ?>
						</div>
					<?php } ?>					
				</div>
			</a>
		<?php else: ?>
			<a href="<?php the_permalink() ?>" class=" col <?php echo implode(' ', $col_class); ?>" <?php echo $animate;?> title="<?php echo esc_attr( the_title() ); ?>">
				<div class="s-sach-box">
					<div class="s-sach-item">
						<div class="s-sach-img">
							<?php if(has_post_thumbnail()) { ?>
								<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
									<?php the_post_thumbnail( $image_size ); ?>
								</div>
							<?php }else{ ?>
								<div class="box-image">
									<span class="box-noneImg">No image</span>
								</div>
							<?php }?>
						</div>	
						<h3 class="s-sach-title"><?php echo esc_attr( the_title() ); ?></h3>
					</div>
				</div>
			</a>
		<?php endif;?>
			
	<?php endwhile;
wp_reset_query();

// Get repeater end.
get_flatsome_repeater_end($atts);

	Flatsome_Relay::render_container_close();

$content = ob_get_contents();
ob_end_clean();
return $content;
}

add_shortcode("blog_posts", "shortcode_latest_from_blog");
