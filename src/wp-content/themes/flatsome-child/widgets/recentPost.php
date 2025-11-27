<?php
// Create a custom widget
class Recent_Posts_By_Category_Widget extends WP_Widget {

    // Set up the widget
    public function __construct() {
        parent::__construct(
            'recent_posts_by_category', // Base ID
            'Recent Posts by Category', // Name
            array('description' => __('Displays recent posts from the current category.', 'text_domain'),) // Args
        );
    }

    // Frontend display of the widget
    public function widget($args, $instance) {
        // Get the current category ID
        $categories = get_the_category();
        if (!empty($categories)) {
            $category_id = $categories[0]->term_id;
        }

        // Get the widget title and posts_per_page from widget settings
        $widget_title = !empty($instance['post_widget_title']) ? $instance['post_widget_title'] : 'Recent Posts in this Category';
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;

        // If a category ID is found, proceed
        if (isset($category_id)) {
            // The query for recent posts in this category
            $recent_posts = new WP_Query(array(
                'category__in' => array($category_id),
                'posts_per_page' => $posts_per_page,
                'post_status' => 'publish',
            ));

            

            if ($recent_posts->have_posts()) {
                echo $args['before_widget'];
                echo $args['before_title'] . esc_html($widget_title) . $args['after_title'];

                echo '<ul>';
                while ($recent_posts->have_posts()) {
                    $recent_posts->the_post();    

                    $id = (int)get_the_ID();
                    $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );

                    echo '
                        <li class="recent-blog-posts-li">
                            <div class="flex-row recent-blog-posts align-center pt-half pb-half">
                                <a href="' . get_permalink() . '" class="flex-col mr-half recent-blog-posts-left">
                                    <img src="'.$img.'" alt="post" class="img-object-cover">  
                                </a>
                                <div class="flex-col flex-grow recent-blog-posts-right">
                                        <p class="recent-blog-posts-date">'. get_the_time('d F Y', get_the_ID()) .'</p>
                                        <a href="' . get_permalink() . '" title="Logistics Director">' . get_the_title() . '</a>
                                </div>
                            </div>
                        </li>
                    ';
                }
                echo '</ul>';

                echo $args['after_widget'];
            } else {
                echo $args['before_widget'];
                echo $args['before_title'] . 'No recent posts' . $args['after_title'];
                echo $args['after_widget'];
            }

            // Restore original post data
            wp_reset_postdata();
        }
    }

    // Backend form for widget options (display options in the admin panel)
    public function form($instance) {
        // Set default values for posts_per_page and post_widget_title
        $posts_per_page = !empty($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;
        $post_widget_title = !empty($instance['post_widget_title']) ? $instance['post_widget_title'] : '';

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('post_widget_title'); ?>"><?php _e('Widget Title:', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('post_widget_title'); ?>" name="<?php echo $this->get_field_name('post_widget_title'); ?>" type="text" value="<?php echo esc_attr($post_widget_title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of Posts to Display:', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" type="number" value="<?php echo esc_attr($posts_per_page); ?>" min="1" step="1" />
        </p>
        <?php
    }

    // Update widget settings (save the widget options)
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        // Save the widget title
        if (isset($new_instance['post_widget_title'])) {
            $instance['post_widget_title'] = sanitize_text_field($new_instance['post_widget_title']);
        }

        // Save the number of posts to display
        if (isset($new_instance['posts_per_page']) && is_numeric($new_instance['posts_per_page'])) {
            $instance['posts_per_page'] = intval($new_instance['posts_per_page']);
        }

        return $instance;
    }
}

// Register the widget
function register_recent_posts_by_category_widget() {
    register_widget('Recent_Posts_By_Category_Widget');
}

add_action('widgets_init', 'register_recent_posts_by_category_widget');
?>
