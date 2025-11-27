<?php

add_shortcode( 'partners_shortcode', 'partners_data' );

function partners_data() {
        $getposts = get_posts(array(
                'showposts' => -1,
                'post_type' => 'partners',
                //'category__in' => 47, // Lay bai viet cung theo category
                /*'orderby'   => array(
                    'date' =>'DESC',
                )*/
            )
        );

        if(isset($getposts)):
            ob_start();

            $max = count($getposts);
    ?>

    <!-- LIST -->
    <div class="h-partners-container">  
        <!-- SPLIDE TOP-->
        <div class="h-partners-top h-partners-box">
            <div class="splide splide-top" role="group" aria-label="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php
                            for($i=0;$i<3;$i++):
                                if(isset($getposts[$i])):
                                $v = $getposts[$i];
                                $id = (int)$v->ID;
                                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                        ?>
                        <li class="splide__slide">
                            <div class="h-partners-item">
                                <?php if($img):?><img src="<?=$img?>" alt="partner" class="h-partners-img img-white"><?php else:?><span class="no-image">No image</span><?php endif;?>
                            </div>
                        </li>
                        <?php endif; endfor;?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- SPLIDE MIDDLE-->
        <div class="h-partners-middle h-partners-box">
            <div class="splide splide-middle" role="group" aria-label="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php
                            for($i=3;$i<6;$i++):
                                if(isset($getposts[$i])):
                                $v = $getposts[$i];
                                $id = (int)$v->ID;
                                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                        ?>
                        <li class="splide__slide">
                            <div class="h-partners-item">
                                <?php if($img):?><img src="<?=$img?>" alt="partner" class="h-partners-img img-white"><?php else:?><span class="no-image">No image</span><?php endif;?>
                            </div>
                        </li>
                        <?php endif; endfor;?>
                    </ul>
                </div>
            </div>
        </div> 

        <!-- SPLIDE BOTTOM-->
        <div class="h-partners-bottom h-partners-box">
            <div class="splide splide-bottom" role="group" aria-label="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php
                            for($i=6;$i<$max;$i++):
                                if(isset($getposts[$i])):
                                $v = $getposts[$i];
                                $id = (int)$v->ID;
                                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                        ?>
                        <li class="splide__slide">
                            <div class="h-partners-item">
                                <?php if($img):?><img src="<?=$img?>" alt="partner" class="h-partners-img img-white"><?php else:?><span class="no-image">No image</span><?php endif;?>
                            </div>
                        </li>
                        <?php endif; endfor;?>
                    </ul>
                </div>
            </div>
        </div>

    </div>

<?php 
    $output = ob_get_clean(); 
    return $output;
    endif;
}