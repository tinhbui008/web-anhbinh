<?php

// STEP 1
function add_custom_order_meta($order_id) {
    if (!$order_id) {
        return;
    }
    
    // Example: Add a custom field
    update_post_meta($order_id, 'custom_order_nights', 'Number of nights');
    update_post_meta($order_id, 'custom_order_fromtime', 'Check-in time');
    update_post_meta($order_id, 'custom_order_fromdate', 'Check-in date');
    update_post_meta($order_id, 'custom_order_todate', 'Check-out date');
    update_post_meta($order_id, 'custom_order_comment', 'Personal request');
    update_post_meta($order_id, 'custom_order_checkintime', 'Check-in timestamp');
    update_post_meta($order_id, 'custom_order_checkouttime', 'Check-out timestamp');
}
add_action('woocommerce_checkout_update_order_meta', 'add_custom_order_meta');


// STEP 2
function display_custom_order_meta_in_admin($order){
    echo '<p><strong>Number of nights:</strong> ' . get_post_meta($order->get_id(), 'custom_order_nights', true) . '</p>';
    echo '<p><strong>Check-in date:</strong> ' . get_post_meta($order->get_id(), 'custom_order_fromtime', true) . ' ' .get_post_meta($order->get_id(), 'custom_order_fromdate', true) . '</p>';
    echo '<p><strong>Check-out date:</strong> ' . get_post_meta($order->get_id(), 'custom_order_todate', true) . '</p>';
    echo '<p><strong>Personal request:</strong> ' . get_post_meta($order->get_id(), 'custom_order_comment', true) . '</p>';
}
add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_order_meta_in_admin', 10, 1);


// STEP 3
function save_custom_checkout_field($order_id) {
    // if (!empty($_POST['booking-checkintime'])) {
    //     update_post_meta($order_id, 'custom_order_fromtime', sanitize_text_field($_POST['booking-checkintime']));
    // }
    // if (!empty($_POST['booking-loinhan'])) {
    //     update_post_meta($order_id, 'custom_order_comment', sanitize_text_field($_POST['booking-loinhan']));
    // }
}
add_action('woocommerce_checkout_update_order_meta', 'save_custom_checkout_field');