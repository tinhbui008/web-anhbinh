<?php

add_shortcode( 'getListRooms_shortcode', 'getProducts_data' );

function getProducts_data() {
        $getposts = get_posts(array(
                'showposts' => 2,
                'category__in' => 46, // Lay bai viet cung theo category
                'orderby'   => array(
                    'date' =>'DESC',
                )
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>


<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }