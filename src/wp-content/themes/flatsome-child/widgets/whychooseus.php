<?php

    add_action('widgets_init', 'miko_widgets_whychooseus');
    if ( ! function_exists( 'miko_widgets_whychooseus' ) ) {
        function miko_widgets_whychooseus()
        {
            register_sidebar(array(
                'name'          => __('Homepage widget area', 'miko'),
                'id'            => 'hompage-widget-area',
                'description'   => __('Thêm widget vào Homepage', 'miko'),
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span class="widget-title">',
                'after_title'   => '</span>',
            ));
        }
    }