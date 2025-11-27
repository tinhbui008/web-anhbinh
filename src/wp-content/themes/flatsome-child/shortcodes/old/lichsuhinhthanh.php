<?php

add_shortcode( 'lichsuhinhthanh_shortcode', 'lichsuhinhthanh_data' );

function lichsuhinhthanh_data() {
        $getposts = get_posts(array(
                'showposts' => -1,
                'post_type' => 'lichsuhinhthanh',
                /*'orderby'   => array(
                    'date' =>'DESC',
                )*/
            )
        );

        if(isset($getposts)):
            ob_start();
    ?>
    <div class="timer-desktop">
        <div class="timer-container ">
            <img src="<?=IMG?>/images/ls_line.png" alt="line">

            <!-- BOX VISIBLE -->
            <?php 
            for($i=0;$i<4;$i++):
            if($getposts[$i]):
                $v = $getposts[$i];
                $id = (int)$v->ID;
                $title = $v->post_title;
                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                $excerpt = get_the_excerpt($id);
            ?>
                <div class="timer-boxItem timer-boxItem-<?=$i+1?>" nextIndex="<?=(count($getposts)==4) ? 1 : ($i+1)?>" dataName="<?=$title?>" dataID="<?=$id?>">
                    <?php if($i==1 || $i==3):?><span class="timer-boxItem-dot"></span><?php endif;?>
                    <p class="timer-boxItem-title"><?=$title?></p>
                    <?php if($i==0 || $i==2):?><span class="timer-boxItem-dot"></span><?php endif;?>

                    <img class="time-boxItem-img time-hidden" src="<?=($img) ? $img : 'https://placehold.co/300x200'?>" alt="img">
                    <div class="time-boxItem-content time-hidden"><?=$excerpt;?></div>
                </div>
            <?php endif; endfor;?>

            <!-- BOX : HIDDEN -->
            <?php
                for($i=4;$i<count($getposts);$i++):
                    $v = $getposts[$i];
                    $id = (int)$v->ID;
                    $title = $v->post_title;
                    $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                    $excerpt = get_the_excerpt($id);
            ?>
                <div class="timer-boxItem timer-boxItem-hidden" nextIndex="<?=(count($getposts)==($i)) ? 0 : ($i+1)?>" dataName="<?=$title?>" dataID="<?=$id?>">
                    <span class="timer-boxItem-dot"></span>
                    <p class="timer-boxItem-title"><?=$title?></p>
                    <img class="time-boxItem-img time-hidden" src="<?=($img) ? $img : 'https://placehold.co/300x200'?>" alt="img">
                    <div class="time-boxItem-content time-hidden"><?=$excerpt;?></div>
                </div>
            <?php endfor;?>

            <!-- BOX : FIRST ORIGINAL -->
            <?php if($getposts[0]):
                $v = $getposts[0];
                $id = (int)$v->ID;
                $title = $v->post_title;
                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                $excerpt = get_the_excerpt($id);
            ?>
                <div class="timer-boxItem-hidden timer-boxItem-first">
                    <p class="timer-boxItem-title"><?=$title?></p>
                    <span class="timer-boxItem-dot"></span>
                </div>
            <?php endif; ?>
        </div>


        <?php if($getposts[2]):
            $v = $getposts[2];
            $id = (int)$v->ID;
            $title = $v->post_title;
            $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
            $excerpt = get_the_excerpt($id);
        ?>
        <?php endif;?>
        <div class="time-showBox">
            <div class="time-showBox-img"><img src="<?=($img) ? $img : 'https://placehold.co/300x200'?>" alt="linh-vuc" class="img-object-cover">   </div>
            <div class="time-showBox-content"><?=$excerpt;?></div>
        </div>
    </div>

    <div class="timer-mobile">
        <div class="swiper swiperTimer">
            <div class="swiper-wrapper">
                <?php
                    foreach($getposts as $k=>$v):
                        $id = (int)$v->ID;
                        $title = $v->post_title;
                        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                        $excerpt = get_the_excerpt($id);
                ?>
                <div class="swiper-slide">
                    <div class="timer-swiper-item">                        
                        <p class="timer-swiper-title"><?=$title?></p>
                        <span class="timer-swiper-dot"></span>
                        <img class="time-swiper-img time-hidden" src="<?=($img) ? $img : 'https://placehold.co/300x200'?>" alt="img">
                        <div class="time-swiper-content time-hidden"><?=$excerpt;?></div>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
        </div>

        <?php if($getposts[0]):
            $v = $getposts[0];
            $id = (int)$v->ID;
            $title = $v->post_title;
            $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
            $excerpt = get_the_excerpt($id);
        ?>
        <?php endif;?>
        <div class="time-showBox time-showBox-mobile">
            <div class="time-showBox-img-mobile"><img src="<?=($img) ? $img : 'https://placehold.co/300x200'?>" alt="linh-vuc" class="img-object-cover">   </div>
            <div class="time-showBox-content-mobile"><?=$excerpt;?></div>
        </div>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }
?>