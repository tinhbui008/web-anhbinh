<?php

function thebox_add_cf7_select_product_list( $tag, $unused ) {  
    if ( $tag['name'] != 'zipstate' && $tag['name'] != 'UMMC-specialty') {
        return $tag; 
    }else if($tag['name']=='zipstate'){
        $zip_states = wp_json_file_decode(WP_CONTENT_DIR . '/themes/flatsome-child/json/countries.json', true);

        $tag['labels'][] ="";  
        $tag['raw_values'][] = $tag['values'][] = '';
        
        if($zip_states){
            foreach ( $zip_states as $item ) {
                $tag['raw_values'][] = $tag['values'][] = $tag['labels'][] = $item->code;  
            }
        }
        
        return $tag; 
    }else if($tag['name'] == 'UMMC-specialty'){
        $items = get_posts(array(
                'showposts' => 9999,
                'post_type' => 'ummc-form',
                'orderby'   => array(
                    'date' =>'DESC',
                )
            )
        );

        $tag['labels'][] ="";  
        $tag['raw_values'][] = $tag['values'][] = '';
        
        if($items){
            foreach($items as $k=>$v) {
                $tag['raw_values'][] = $tag['values'][] = $tag['labels'][] = $v->post_title;  
            }
        }
        return $tag; 
    }
}  
add_filter( 'wpcf7_form_tag', 'thebox_add_cf7_select_product_list', 10, 2);  