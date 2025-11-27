<?php
add_shortcode( 'relativePost-shortcode', 'relativePost' );

function relativePost($attr) {
    $option = shortcode_atts(['post_id'=>''],$attr);
    ob_start();

    // Xử lý
    $post_id = $option['post_id'];
    $categories = get_the_category($post_id); // Thay $post_id bằng ID của bài viết

    // Khởi tạo biến để lưu ID của chuyên mục cha gần nhất
    $closest_parent_category_id = 0;
    $closest_parent_category_name = '';
    foreach ($categories as $category) {
        $closest_parent_category_id = $category->term_id;
        $closest_parent_category_name = $category->name;
    }

    // Truy van cac bai viet lien quan
    $related_posts = new WP_Query( array(
      'posts_per_page' => 9999, // So bai viet liên quan bạn muốn hiển thị
      'post__not_in' => array( $post_id ), // Loai tru bai viet hien tai
      'category__in' => $closest_parent_category_id, // Lay bai viet cung theo category
    ) );    

    $parentID = get_category($closest_parent_category_id)->parent;  
    $parentID = ($parentID==0) ? $closest_parent_category_id : $parentID;
   
    $parentName = ($parentID!=0) ? get_category($parentID)->name : 'article';
?>  
<?php if ( $related_posts->have_posts() && $parentID!=40 ) :?>
    <div class="realtivePost /section-page-detail section-relativePost">
        <p class="realtivePost-title"><?=pll('その他の記事','Other articles')?></p>

        <div class="relativeService-contain">
            <div class="servicesSwiper-nav-prev"><svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="22" cy="22" r="22" transform="matrix(-1 0 0 1 44 0)" fill="#111111"></circle> <path d="M25 28L19 22L25 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </svg></div>

            <div class="servicesSwiper-nav-next"><svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="22" cy="22" r="22" transform="matrix(-1 0 0 1 44 0)" fill="#111111"></circle> <path d="M25 28L19 22L25 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </svg></div>

            <div class="swiper swiper-relativeService">
                <div class="swiper-wrapper">
                    <?php  while( $related_posts->have_posts() ) : $related_posts->the_post(); 
                        $id = (int)get_the_ID();
                        $show_excerpt = $excerpt;
                        $icon = get_field('service_icon',$id);
                    ?>
                        <div class="swiper-slide">
                            <a href="<?php the_permalink() ?>" class="s-news-item"> 
                                <div>                       
                                    <div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
                                        <?php if(has_post_thumbnail()) { ?>
                                            <?php the_post_thumbnail( $image_size ); ?>
                                        <?php }else{ ?>
                                            <span class="box-noneImg">No image</span>
                                        <?php }?>	
                                    </div>
                                    
                                    <p class="s-news-clock">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_9_137)"> <path d="M10 5.00008V10.0001L13.3333 11.6667M18.3333 10.0001C18.3333 14.6025 14.6024 18.3334 10 18.3334C5.39762 18.3334 1.66666 14.6025 1.66666 10.0001C1.66666 5.39771 5.39762 1.66675 10 1.66675C14.6024 1.66675 18.3333 5.39771 18.3333 10.0001Z" stroke="#6D6D6D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_9_137"> <rect width="20" height="20" fill="white"/> </clipPath> </defs> </svg>
                                        <span><?php echo get_the_date('M d, Y'); ?></span>
                                        
                                    </p>
                                    <h3 class="s-news-title"><?php echo esc_attr( the_title() ); ?></h3>
                                    <div class="s-news-des"><?php echo flatsome_get_the_excerpt( 20 ); ?></div>
                                </div>
                                <span class="s-news-readmore"><?=pll('続きを読む','Read more');?><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1.16669 7.00008H12.8334M12.8334 7.00008L7.00002 1.16675M12.8334 7.00008L7.00002 12.8334" stroke="#86C61C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>  
        </div>
        
    </div>
<?php endif;?>

<?php 
        $output = ob_get_clean(); 
        return $output;
    }