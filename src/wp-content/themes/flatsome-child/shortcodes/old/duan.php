<?php

add_shortcode( 'duan_shortcode', 'duan_data' );

function duan_data() {
        $getposts = get_posts(array(
                'showposts' => 4,
                'category__in' => 36, // Lay bai viet cung theo category
                'orderby'   => array(
                    'date' =>'DESC',
                )
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>

    <div class="duan-titleBox">
        <h2>
            <div class="smallTitle"><p>Dự án</p></div>
            <div class="fullTitle"><p>Tiêu biểu</p></div>
        </h2>
        <a href="" class="duan-viewmore">Xem tất cả dự án<svg width="17" height="9" viewBox="0 0 17 9" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M16.3536 4.85355C16.5488 4.65829 16.5488 4.34171 16.3536 4.14645L13.1716 0.964466C12.9763 0.769204 12.6597 0.769204 12.4645 0.964466C12.2692 1.15973 12.2692 1.47631 12.4645 1.67157L15.2929 4.5L12.4645 7.32843C12.2692 7.52369 12.2692 7.84027 12.4645 8.03553C12.6597 8.2308 12.9763 8.2308 13.1716 8.03553L16.3536 4.85355ZM0 5H16V4H0V5Z" fill="#232323"/> </svg></a>
    </div>
      
    <div class="duan-container">
        <?php
            foreach($getposts as $k=>$v):
                $id = (int)$v->ID;
                $title = $v->post_title;
                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                $prefix_title = get_field('prefix_title',$id);
                $main_title = get_field('main_title',$id);
                $permalink = get_the_permalink($id);
        ?>

            <a href="<?=$permalink;?>" class="duan-Box">
                <img src="<?=($img) ? $img : 'https://placehold.co/2048x1800'?>" alt="linh-vuc" class="img-object-cover">                
                <h3 class="duan-Box-info">
                    <?=$prefix_title?> <p><?=$main_title?></p>
                </h3>
                <span class="duan-logo"><img src="<?=IMG?>/images/logo_duan.png" alt="logo-company"></span>
            </a>

        <?php endforeach;?>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }
?>