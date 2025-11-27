<?php

// AJAX SHOW THÔNG TIN BÌNH CHỌN : KHI CLICK NÚT BÌNH CHỌN
add_action( 'wp_ajax_binhchon', 'binhchon_init' );
add_action( 'wp_ajax_nopriv_binhchon', 'binhchon_init' );
function binhchon_init() {
    global $post;

    $id = $post_id = (isset($_POST['id']))?esc_attr($_POST['id']) : 0;
    if (!$id) {
        wp_send_json_error(['message' => 'ID không hợp lệ']);
    }

    $post = get_post($id);
    if (!$post) {
        wp_send_json_error(['message' => 'Không tìm thấy bài viết']);
    }

    $icon = get_field('vnplay_icon',$id);
	$name = get_field('vnplay_name',$id);
    $title = get_the_title($id);
    $content = apply_filters('the_content', $post->post_content);


    // chọn kiểu hiển thị nội dung: gallery - video - none
    $kieuhienthi = get_field('binhchon_kieuhienthi',$id);
    $gallery = get_field('binhchon_gallery',$id);
    $gallery = array_chunk($gallery, 3);

    $video = get_field('binhchon_video',$id);
    $photovideo = get_field('binhchon_photovideo',$id);

    // DS users
    $user_ids = get_post_meta($id, 'luot_binh_chon', true);
    $user_ids = ($user_ids) ? $user_ids : null;


    // PREV and NEXT Post trong cùng danh mục
    // Lấy bài viết trước và sau

    $categories = wp_get_post_terms($post_id, 'category', ['parent' => 0]);
    if (empty($categories)) {
        wp_send_json_error('No category found');
    }

    $cat_id = $categories[0]->term_id;

    // Bài trước (ID nhỏ hơn, cùng chuyên mục)    
    $args = array(
        'posts_per_page' => -1,
        //'orderby'        => 'date',
        //'order'          => 'ASC',
        'category__in'   => array($cat_id),
        'fields'         => 'ids',
        'post_status'    => 'publish'
    );

    $posts_in_cat = get_posts($args);

    // Tìm vị trí bài hiện tại
    $current_index = array_search($post_id, $posts_in_cat);

    $prev_id = ($current_index > 0) ? $posts_in_cat[$current_index - 1] : null;
    $next_id = ($current_index < count($posts_in_cat) - 1) ? $posts_in_cat[$current_index + 1] : null;

    if($prev_id==null){
        $prev_id = $posts_in_cat[count($posts_in_cat) - 1];
    }
    if($next_id==null){
        $next_id = $posts_in_cat[0];
    }
 

if(isset($post)):
    ob_start();
?>

<div class="popup-binhchon-contain">
    <!-- CLOSE -->
    <div class="popup-binhchon-top">
        <h3>Topic - <?=$title;?></h3>
        <span class="popup-binhchon-close"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M18 6L6 18M6 6L18 18" stroke="#3D3D3D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
    </div>
    <!-- CONTENT -->
    <div class="popup-binhchon-middle scrollBar-cs">
        <div class="popup-binhchon-content"><?=$content;?></div>
        <!-- NẾU HIỂN THỊ THEO VIDEO -->
        <?php if($kieuhienthi==2 && $video !=''):?>
        <a class="popup-binhchon-video">
            <span class="popup-binhchon-iconplay"><img src="<?=IMG?>/images/icon_play.png" alt="icon-play"></span>
            <video id="popup-binhchon-videoBox" src="<?=$video?>" loop="" playsinline="" x-webkit-airplay="allow" x5-playsinline="" webkit-playsinline="" muted type="video/mp4"></video>
        </a>
        <?php endif;?>
        <!-- NẾU HIỂN THỊ THEO GALLERY -->
        <?php if($kieuhienthi==1 && $gallery):?>
            <?php foreach($gallery as $v_child):?>
            <div class="popup-binhchon-gallery">
                <?php foreach($v_child as $k=> $v_item):?>
                    <!-- LEFT -->
                    <?php if($k==0 && isset($v_child[0])):?>
                        <span class="popup-binhchon-gallery-left"><img src="<?=$v_child[0]?>" alt="gallery"></span>
                    <?php else:?>
                        <!-- RIGHT -->
                        <?php if($k==1):?><div class="popup-binhchon-gallery-right"><?php endif;?>
                            <?php if(isset($v_child[$k])):?>
                                <span><img src="<?=$v_child[$k]?>" alt="gallery"></span>
                            <?php endif;?>
                        <?php if($k==2 || count($v_child)==2):?></div><?php endif;?>
                    <?php endif;?>
                    
                <?php endforeach;?>
            </div>
            <?php endforeach;?>
        <?php endif;?>
    </div>
    <!-- INFO -->
    <div class="popup-binhchon-bottom">
        <div class="popup-binhchon-box">
            <p class="popup-binhchon-photo">
                <?php if($icon!='') { ?>
                    <img src="<?=$icon?>" alt="icon">
                <?php }else{ ?>
                    <span class="box-noIcon">64x64</span>
                <?php }?>
            </p>
            <div class="popup-binhchon-boxInfo">
                <h3 class="popup-binhchon-name"><?= $name; ?></h3>
                <p class="vnplay-luotbinhchon"><span><?=(isset($user_ids)) ? count($user_ids) : 0?></span> Lượt bình chọn</p>
            </div>
        </div>
        <p class="popup-binhchon-btn <?= (isset($user_ids) && in_array(USER_LOGGED, $user_ids)) ? 'has-binhchon' : '' ?>" data-id="<?=$id?>"><svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0.166626 5.61413C0.166626 9.66657 3.51615 11.8261 5.96807 13.7589C6.83329 14.441 7.66663 15.0832 8.49996 15.0832C9.33329 15.0832 10.1666 14.441 11.0318 13.7589C13.4838 11.8261 16.8333 9.66657 16.8333 5.61413C16.8333 1.56168 12.2498 -1.31224 8.49996 2.58374C4.75009 -1.31224 0.166626 1.56168 0.166626 5.61413Z" fill="#111111"/> </svg> <span><?= (isset($user_ids) && in_array(USER_LOGGED, $user_ids)) ? 'Đã bình chọn' : 'Bình chọn' ?></span></p>
    </div>

    <!-- ARRROW PREV - NEXT -->
    <?php if($prev_id):?>
        <span class="popup-binhchon-prev" data-id="<?=$prev_id?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M15 18L9 12L15 6" stroke="#005BAA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
    <?php endif;?>

    <?php if($next_id):?>
        <span class="popup-binhchon-next" data-id="<?=$next_id?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M15 18L9 12L15 6" stroke="#005BAA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg></span>
    <?php endif;?>
</div>

<?php else:?>
    <div>Không tìm thấy bài viết nào !</div>
<?php endif;?>

<?php
    $html = ob_get_clean();
    echo $html;
    wp_die();
}