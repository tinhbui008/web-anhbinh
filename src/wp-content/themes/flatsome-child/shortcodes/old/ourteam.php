<?php

add_shortcode( 'shortcode_ourteam', 'ourteam_data' );

function ourteam_data() {
        $getposts = get_posts(array(
                'showposts' => -1,
                'post_type' => 'our-team',
                'orderby'   => array(
                    'date' =>'ASC',
                )
            )
        );

        if(isset($getposts)):
            ob_start();

            $img_first = get_the_post_thumbnail_url($getposts[0]->ID, 'thumnail', array( 'class' =>'thumnail') );
    ?>
      
    <div class="ourteam-container">
        <div class="ourteam-left">
            <p class="ourteam-photo"><img src="<?=($img_first) ? $img_first : IMG.'/images/noavatar.png'?>" alt=""></p>
        </div>
        <div class="ourteam-right">
            <?php
                foreach($getposts as $k=>$v):
                    $id = (int)$v->ID;
                    $title = $v->post_title;
                    $excerpt = get_the_excerpt($id);
                    $content_box = $v->post_content;
                    $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
            ?>
            <div class="ourteam-box faq-item" data-id="<?=$id?>" data-photo="<?=($img) ? $img : IMG.'/images/noavatar.png'?>">
                <div class="ourteam-info"><h3><?=$title?></h3><p><?=$excerpt?></p></div>
                <div id="faq-content-<?=$id?>" class="ourteam-content faq-content" style="display:none"><?=$content_box;?></div>
            </div>
            <?php endforeach;?>
        </div>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }
?>