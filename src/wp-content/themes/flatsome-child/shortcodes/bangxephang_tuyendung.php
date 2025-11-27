<?php
// ### CLICK TAB
function bangxephang_tuyendung_init() {
            
    $args = array(
        'post_type'      => 'xephang_tuyendung',
        'posts_per_page' => -1,
        'meta_key'       => 'tuyendung_songuoigioithieu',
        'orderby'        => array(
            'score'    => 'DESC',
            'timeUsed' => 'ASC',
        )
    );

    $getposts = get_posts($args);
    
    if(isset($getposts)):
        ob_start();
?>

<section class="tuyendung-bxh-container">
    <!-- TITLE -->
    <h2 class="tuyendung-list-title fix-tuyendung-list-title"><p>BẢNG XẾP HẠNG</p></h2>
    <div class="tuyendung-bxh-contain">
        <!-- HEAD -->
        <div class="tuyendung-list-head">
            <span>STT</span>
            <span>Họ và tên</span>
            <span>Phòng ban</span>
            <span>Số người giới thiệu</span>
        </div>
        <!-- LIST -->
        <div class="tuyendung-bxh-list <?= (count($getposts)<=10) ? 'tuyendung-bxh-no-scroll' : 'scroll-style1'?> ">
            <?php foreach($getposts as $k=>$v):                
                $id = (int)$v->ID;
                $title = $v->post_title; 
                $phongban = get_field('tuyendung_phongban',$id);
                $songuoigioithieu = get_field('tuyendung_songuoigioithieu',$id);  

                $stt = $class_stt = $img = '';
                switch ($k) {
                    case 0:
                    case 1:
                    case 2:
                        $class_stt = 'tuyendung-bxh-item-'.($k+1);
                        $img = IMG.'/images/tuyendung/hc'.($k+1).'.png';
                        break;
                    default:
                        $stt = (($k+1)<10) ? '0'.($k+1) : $k+1;
                        break;
                }
            ?>

            <div class="tuyendung-list-body <?=$class_stt?>">
                <span>
                    <?php if($img!=''):?>
                        <img src="<?=$img?>" alt="huy-chuong" width="40">
                    <?php else:?><?=$stt?><?php endif;?>
                </span>
                <span><?=$title?></span>
                <span><?=$phongban?></span>
                <span><?=$songuoigioithieu?></span>
            </div>

            <?php endforeach;?>
        </div>
    </div>
    <span class="page-tuyendung-icon5"><img src="<?=IMG?>/images/tuyendung/td_i5.png" alt="icon"></span>
    <span class="page-tuyendung-icon6"><img src="<?=IMG?>/images/tuyendung/td_i6.png" alt="icon"></span>
    <span class="page-tuyendung-icon7"><img src="<?=IMG?>/images/tuyendung/td_i7.png" alt="icon" width="100%"></span>
</section>

<!-- Layout background -->
<span class="page-tuyendung-overlay"></span>
<span class="page-tuyendung-icon2"><img src="<?=IMG?>/images/tuyendung/td_i2.png" alt="icon" width="100%"></span>
<span class="page-tuyendung-icon4"><img src="<?=IMG?>/images/tuyendung/td_i4.png" alt="icon" width="100%"></span>
<span class="page-tuyendung-icon1"><img src="<?=IMG?>/images/tuyendung/td_i1.png" alt="icon"></span>
<span class="page-tuyendung-icon3"><img src="<?=IMG?>/images/tuyendung/td_i3.png" alt="icon"></span>
<span class="page-tuyendung-icon8"><img src="<?=IMG?>/images/tuyendung/td_i8.png" alt="icon"></span>

<?php
        $output = ob_get_clean(); 
        return $output;
    endif;
}
add_shortcode( 'bangxephang_tuyendung_shortcode', 'bangxephang_tuyendung_init' );