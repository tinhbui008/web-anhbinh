<?php
/**
 * Header main.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>
<div id="masthead" class="header-main <?php header_inner_class('main'); ?>">
      <div class="header-inner flex-row header-container <?php flatsome_logo_position(); ?> cs-header-menu" role="navigation">

          <!-- Logo -->
          <div id="logo" class="flex-col logo">
            <?php get_template_part('template-parts/header/partials/element','logo'); ?>
          </div>

          <!-- Mobile Left Elements -->
          <div class="flex-col show-for-medium flex-left">
            <ul class="mobile-nav nav nav-left">
              
            </ul>
          </div>

          <!-- Left Elements -->
          <div class="flex-col hide-for-medium flex-left
            <?php if(get_theme_mod('logo_position', 'left') == 'left') echo 'flex-grow'; ?>">
            <ul class="header-nav header-nav-main nav nav-left dev-custom-menu <?php flatsome_nav_classes('main'); ?>" >
              <?php flatsome_header_elements('header_elements_left'); ?>
            </ul>
          </div>

          <!-- Right Elements -->
          <div class="flex-col hide-for-medium flex-right cs-menu-main dev-hidden-menuHam">
            <ul class="header-nav header-nav-main nav nav-right <?php flatsome_nav_classes('main'); ?>" style="width:33px;">
              <?php flatsome_header_elements('header_elements_right'); ?>
            </ul>
            <!-- BTN LOGIN CUSTOME - WIDGET FOLDER -->
              <?php if (!wp_is_mobile()):?>
                <?=do_shortcode('[btn_login]');?>
              <?php endif;?>
          </div>

          <!-- Mobile Right Elements -->
          <div class="flex-col show-for-medium flex-right">
            <ul class="mobile-nav nav nav-right <?php flatsome_nav_classes('main-mobile'); ?>">
              <?php if (wp_is_mobile()):?>
                <li><?=do_shortcode('[btn_login]');?></li>
              <?php endif;?>
              <?php flatsome_header_elements('header_mobile_elements_right','mobile'); ?>
            </ul>
          </div>

      </div>

      <?php if(get_theme_mod('header_divider', 1)) { ?>
      <div class="container"><div class="top-divider full-width"></div></div>
      <?php }?>
</div>
