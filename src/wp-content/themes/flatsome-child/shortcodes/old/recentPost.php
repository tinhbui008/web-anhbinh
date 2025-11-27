<?php

function recent_posts_shortcode($atts) {
    // Set default attributes
    $atts = shortcode_atts(
        array(
            'posts' => 2, // Default number of posts to show
        ), $atts, 'recent_posts' );

    // Create a new query for recent posts
    $query = new WP_Query(array(
        'posts_per_page' => $atts['posts'],
        'post_status' => 'publish',
    ));

    if($query->have_posts()):
        ob_start();
        while ($query->have_posts()) {
        $query->the_post();
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
        $permalink = get_the_permalink();
    ?>

    <div class="s-recentPost-item">
        <a href="<?=$permalink?>" class="s-recentPost-img"><img src="<?=$image_url?>" alt="photo"></a>
        <div class="s-recentPost-info">
            <p class="s-recentPost-date"><?=get_the_date('d F Y')?></p>
            <h3><a href="<?=$permalink?>"><?=get_the_title();?></a></h3>
        </div>
    </div>

<?php 
    }
    endif;

    $output = ob_get_clean(); 
    return $output;
}

add_shortcode('recent_posts', 'recent_posts_shortcode');
