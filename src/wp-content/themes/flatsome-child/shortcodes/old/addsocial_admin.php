<?php 

function add_social_share_column($columns) {
    $columns['social_share'] = 'Share';
    return $columns;
}
add_filter('manage_posts_columns', 'add_social_share_column');

function add_social_share_buttons($column, $post_id) {
    if ($column === 'social_share') {
        $post_url = urlencode(get_permalink($post_id));
        $post_title = urlencode(get_the_title($post_id));

        echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" width="20">
              </a> ';
        echo '<a href="https://www.linkedin.com/sharing/share-offsite/?url=' . $post_url . '" target="_blank">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png" width="20">
              </a>';
    }
}
add_action('manage_posts_custom_column', 'add_social_share_buttons', 10, 2);