<?php
// Hook into WooCommerce order status change
add_action( 'woocommerce_order_status_changed', 'update_custom_post_type_after_order_status_change', 10, 4 );

function update_custom_post_type_after_order_status_change( $order_id, $old_status, $new_status, $order ) {
    // Check if the order status is the one you're interested in      
    // Get your custom post type - for example, let's assume it's 'my_custom_post_type'
    $args = array(
        'post_type' => 'history-order',
        'posts_per_page' => -1,  // Get all posts
        'meta_query' => array(
            array(
                'key'     => 'history_order_id', // Assuming you've saved the order ID in your custom post type
                'value'   => $order_id,
                'compare' => '='
            ),
        ),
    );
    
    // Get the custom post type posts related to the order
    $custom_posts = get_posts( $args );
    
    if ( ! empty( $custom_posts ) ) {
        foreach ( $custom_posts as $custom_post ) {
            // Update the custom post type as needed
            // For example, update a custom field or change the post status
            update_post_meta( $custom_post->ID, 'history_status', $new_status );
            
            // Or update other fields of the post
            // wp_update_post( array( 'ID' => $custom_post->ID, 'post_status' => 'publish' ) );
        }
    }
}
