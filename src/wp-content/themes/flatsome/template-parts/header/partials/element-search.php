<?php
/**
 * Search element.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$icon_style = get_theme_mod('search_icon_style');
?>
<?php if(get_theme_mod('header_search_style') !== 'lightbox') { ?>
<li class="header-search header-search-dropdown has-icon has-dropdown menu-item-has-children">
	<?php if($icon_style) { ?><div class="header-button"><?php } ?>
	<a href="#" aria-label="<?php echo __('Search','woocommerce'); ?>" class="<?php echo get_flatsome_icon_class(flatsome_option('search_icon_style'), 'small'); ?>">
		<?php //echo get_flatsome_icon('icon-search'); ?>
		<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M17.5 17.5L13.875 13.875M15.8333 9.16667C15.8333 12.8486 12.8486 15.8333 9.16667 15.8333C5.48477 15.8333 2.5 12.8486 2.5 9.16667C2.5 5.48477 5.48477 2.5 9.16667 2.5C12.8486 2.5 15.8333 5.48477 15.8333 9.16667Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>
	</a>
	<?php if($icon_style) { ?></div><?php } ?>
	<ul class="nav-dropdown <?php flatsome_dropdown_classes(); ?>">
	 	<?php get_template_part('template-parts/header/partials/element-search-form'); ?>
	</ul>
</li>
<?php } else if(get_theme_mod('header_search_style') == 'lightbox') { ?>
<li class="header-search header-search-lightbox has-icon">
	<?php if($icon_style) { ?><div class="header-button"><?php } ?>
		<a href="#search-lightbox" aria-label="<?php echo __('Search','woocommerce'); ?>" data-open="#search-lightbox" data-focus="input.search-field"
		class="<?php echo get_flatsome_icon_class(get_theme_mod('search_icon_style'), 'small'); ?>">
			<?php //echo get_flatsome_icon('icon-search', '16px'); ?>
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M17.5 17.5L13.875 13.875M15.8333 9.16667C15.8333 12.8486 12.8486 15.8333 9.16667 15.8333C5.48477 15.8333 2.5 12.8486 2.5 9.16667C2.5 5.48477 5.48477 2.5 9.16667 2.5C12.8486 2.5 15.8333 5.48477 15.8333 9.16667Z" stroke="#3D3D3D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>
		</a>
		<?php if($icon_style) { ?></div>
	<?php } ?>

	<div id="search-lightbox" class="mfp-hide dark text-center">
		<?php echo do_shortcode('[search size="large" style="'.get_theme_mod('header_search_form_style').'"]'); ?>
	</div>
</li>
<?php } ?>
