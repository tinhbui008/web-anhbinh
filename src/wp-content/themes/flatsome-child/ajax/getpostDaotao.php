<?php

add_action( 'wp_ajax_getpostDaotao', 'getpostDaotao_init' );
add_action( 'wp_ajax_nopriv_getpostDaotao', 'getpostDaotao_init' );
function getpostDaotao_init() {
    $id_cat = (isset($_POST['id_cat']))?esc_attr($_POST['id_cat']) : '';
    $category_permalink = get_category_link( $id_cat );

    $getposts = get_posts(array(
            'showposts' => 4,
            'category__in' => $id_cat, // Lay bai viet theo category
            'orderby'   => array(
                'date' =>'ASC',
            )
        )
    );

$count_max = count($getposts);
if(isset($getposts) && $count_max>0):
    ob_start();
?>

<div class="s-daotao-contain">
    <!-- FIRST -->
    <?php if(isset($getposts[0])):
        $v = $getposts[0];
        $id = (int)$v->ID;
        $title = $v->post_title;
        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
        $permalink = get_the_permalink($id);
    ?>
    <a href="<?=$permalink?>" class="s-daotao-first">
        <img src="<?=($img) ? $img : 'https://placehold.co/606x606'?>" alt="<?=$title?>">
        <h3><?=$title?></h3>
    </a>
    <?php endif;?>

    <!-- LIST -->
    <div class="s-daotao-list">
        <?php for($i=1;$i<$count_max;$i++):?>
            <?php if(isset($getposts[$i])):
                $v = $getposts[$i];
                $id = (int)$v->ID;

                $title = $v->post_title;
                $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
                $permalink = get_the_permalink($id);
            ?>
            <a href="<?=$permalink?>" class="s-daotao-item">
                <img src="<?=($img) ? $img : 'https://placehold.co/606x606'?>" alt="<?=$title?>">
                <h3><?=$title?></h3>
            </a>
            <?php endif;?>
        <?php endfor;?>
    </div>

    <!-- SEE ALL -->
    <div class="s-daotao-btn"><a href="<?=$category_permalink?>" class="button primary button-style1" style="border-radius:99px;"><span>Xem tất cả</span></a></div>
</div>

<?php else:?>    
    <div class="s-daotao-nodata">Không tìm thấy bài viết nào !</div>
<?php endif;?>

<?php
    $html = ob_get_clean();
    echo $html;
    wp_die();
}