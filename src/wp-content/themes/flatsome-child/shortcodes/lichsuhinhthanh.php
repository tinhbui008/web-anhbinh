<?php
// ### CLICK TAB
function show_lichsuhinhthanh_list() {
    $getposts = get_posts(array(
            'showposts' => -1,
            'post_type' => 'lichsuhinhthanh',
            'orderby'   => array(
                'title' =>'ASC',
            )
        )
    );
    if(isset($getposts)):
        ob_start();
?>

<div class="lichsuhinhthanh-container">
    <!-- LIST -->
    <div class="lichsuhinhthanh-list">
        <?php $dem=0; foreach($getposts as $k=>$v):
            $id = (int)$v->ID;
            $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
            $title = $v->post_title;
            $content = $v->post_content;
            $dem = ($img!='') ? ($dem+1) : $dem;
            $noImg = ($img=='') ? 'lichsuhinhthanh-noImg' : '';
        ?>
            <div class="lichsuhinhthanh-item">
                <div class="lichsuhinhthanh-sub <?= ($dem%2==0) ? 'lichsuhinhthanh-reverse' : '' ?> <?=$noImg?>">
                    <div class="lichsuhinhthanh-left">
                        <?php if($img!=''):?><img src="<?=($img) ? $img : 'https://placehold.co/460x265'?>" alt="lich-su" class=""><?php endif;?>
                    </div>
                    <div class="lichsuhinhthanh-right">
                        <h3 class="lichsuhinhthanh-title"><?=$title?></h3>
                        <div class="lichsuhinhthanh-content"><?=$content?></div>
                        <div class="lichsuhinhthanh-photo-mb"><?php if($img!=''):?><img src="<?=($img) ? $img : 'https://placehold.co/460x265'?>" alt="lich-su" class=""><?php endif;?></div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="lichsuhinhthanh-btn"><a id="loadMore" class="button primary button-style1" style="border-radius:99px;"><span>Xem thêm</span></a></div>
</div>

<?php
        $output = ob_get_clean(); 
        return $output;
    endif;
}
add_shortcode( 'lichsuhinhthanh_shortcode', 'show_lichsuhinhthanh_list' );