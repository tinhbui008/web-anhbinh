<?php

add_shortcode( 'getRooms_shortcode', 'getRooms_data' );

function getRooms_data() {
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
      
    <div class="boxRoom-contain">
        <?php
            foreach($getposts as $k=>$v):
                $id = (int)$v->ID;
                $title = $v->post_title;
                $excerpt = get_the_excerpt($id);
                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                $permalink = get_the_permalink($id);                
                $adults = get_field('adults',$id);
                
        ?>

        <a href="<?=$permalink;?>" class="boxRoom-item" title="<?=$title?>">
            <p class="boxRoom-img"><img src="<?=($img) ? $img : 'https://placehold.co/540x360'?>" alt="<?=$permalink;?>" class="img-object-cover"></p>
            <div class="boxRoom-info">
                <h3 class="boxRoom-name"><?=$title?></h3>
                <?php if((int)$adults>0):?><span class="boxRoom-adults"><?=$adults?> <?=pll('大人','Adults')?></span><?php endif;?>
                <div class="boxRoom-excerpt"><?=$excerpt?></div>
            </div>
        </a>

        <?php endforeach;?>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }