<?php

add_shortcode( 'shortcode_ourwork', 'ourwork_data' );

function ourwork_data() {
        $getposts = get_posts(array(
                'showposts' => 5,
                'category__in' => 71, // Lay bai viet cung theo category
                'orderby'   => array(
                    'date' =>'ASC',
                )
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>
      
    <div class="ourwork-container">
        <?php
            foreach($getposts as $k=>$v):
                $id = (int)$v->ID;
                $title = $v->post_title;
                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                //$prefix_title = get_field('prefix_title',$id);
                //$main_title = get_field('main_title',$id);
                $permalink = get_the_permalink($id);
        ?>

            <a href="<?=$permalink;?>" class="ourwork-Box card-3d-wrap target-cursor">
                <div class="card-3d-wrapper">
                    <p class="card-front"><img src="<?=($img) ? $img : 'https://placehold.co/641x641'?>" alt="linh-vuc" class="img-object-cover"></p>       
                    <h3 class="card-back"><?=$title?></h3>
                </div>
            </a>

        <?php endforeach;?>
        <a href="<?=get_category_link(current_obj(71,'category'));?>" class="ourwork-Box ourwork-special">Discover</br>more</a>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }
?>