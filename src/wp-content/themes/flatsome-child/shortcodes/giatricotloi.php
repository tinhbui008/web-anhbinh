<?php

// ### CLICK TAB
function show_giatricotloi_list() {
    $getposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'giatricotloi',
            /*'orderby'   => array(
                'date' =>'DESC',
            )*/
        )
    );
    if(isset($getposts)):
        ob_start();
?>

<div class="giatricotloi-container">
    <!-- LEFT -->
    <?php if(isset($getposts[0])):
        $v = $getposts[0];
        $id = (int)$v->ID;
        $photo1 = get_field('photo1',$id);
        $title = $v->post_title;
        $content = $v->post_content;
    ?>
    <div class="giatricotloi-left">
        <div class="giatricotloi-main-title">
            <h3><?=$title?></h3>
            <p><img src="<?=($photo1) ? $photo1 : 'https://placehold.co/64x64'?>" alt="gia-tri-cot-loi"></p>
        </div>
        <div class="giatricotloi-main-content"><?=$content?></div>
    </div>
    <?php endif; ?>

    <!-- RIGHT -->
    <div class="giatricotloi-right">
        <?php foreach($getposts as $k=>$v):
            $id = (int)$v->ID;
            $photo1 = get_field('photo1',$id);
            $photo2= get_field('photo2',$id);
            $title = $v->post_title;
            $content = $v->post_content;
        ?>
            <div class="giatricotloi-item">
                <p class="giatricotloi-photo" data-photo="<?=($photo1) ? $photo1 : 'https://placehold.co/64x64'?>"><img src="<?=($photo2) ? $photo2 : 'https://placehold.co/80x80'?>" alt="gia-tri-cot-loi"></p>
                <h3 class="giatricotloi-title"><?=$title?></h3>
                <div class="giatricotloi-content"><?=$content?></div>
            </div>
        <?php endforeach;?>
    </div>
</div>

<?php
        $output = ob_get_clean(); 
        return $output;
    endif;
}
add_shortcode( 'giatricotloi_shortcode', 'show_giatricotloi_list' );