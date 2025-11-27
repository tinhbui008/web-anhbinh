<?php
// ### CLICK TAB
function bangxephang_init() {
            
    $args = array(
        'post_type'      => 'lichsubaithi',
        'posts_per_page' => -1,
        'meta_key'       => 'ls_score',
        'orderby'        => array(
            'score'    => 'DESC',
            'timeUsed' => 'ASC',
        ),
        'meta_query'     => array(
            'score_clause' => array(
                'key'     => 'ls_score',
                'type'    => 'NUMERIC',
            ),
            'time_clause' => array(
                'key'     => 'ls_timeUsed',
                'type'    => 'NUMERIC',
            ),
            'category_clause' => array(   // Thêm điều kiện mới ở đây
                'key'     => 'ls_idCategory',
                'value'   => get_option('idCategory'), // lấy ds theo thời điểm mới nhất
                'compare' => '=',          // So sánh bằng
                'type'    => 'NUMERIC',   // Nếu trường meta là số
            )
        ),
        'orderby' => array(
            'score_clause' => 'DESC',
            'time_clause'  => 'ASC',
        ),
    );

    $getposts = get_posts($args);
    
    if(isset($getposts)):
        ob_start();

        // $topics = get_terms( array(
        //     'taxonomy' => 'category_bobaithi', // tên taxonomy
        //     'hide_empty' => false, // lấy cả những term không có post
        // ) );
?>

<div class="vnplay-bangxephang">
    <!-- ICON -->
    <span class="bangxephang-i1"><img src="<?=IMG?>/images/ball1.png" alt=""></span>
    <span class="bangxephang-i2"><img src="<?=IMG?>/images/ball2.png" alt=""></span>

    <!-- TITLE -->
    <div class="bangxephang-title"><p>Bảng xếp hạng</p></div>

    <!-- LIST TOPICS -->
    <?php /*
    <div class="bangxephang-topic container-page">
        <?php foreach($topics as $topic):?>
            <span class="bangxephang-topic-item"><?= $topic->name ?></span>
        <?php endforeach;?>
    </div>
    */?>

    <!-- DESCRIBE -->
    <!-- <div class="bangxephang-week">
        <span>Tuần 1, 01/12 - 07/12/2024</span>
    </div> -->
    <!-- WINER -->
    <div class="bangxephang-winner">
        <div class="bangxephang-winner-top">
            <!-- WINNER SILVER -->
            <div class="bangxephang-winner-item">
                <?php if(isset($getposts[1])):
                    $id = (int)$getposts[1]->ID;
                    $userID= get_field('ls_iduser',$id);
                    $score= get_field('ls_score',$id);
                    $timeUsed= get_field('ls_timeUsed',$id);
                    $avatar_url = get_avatar_url($userID, array('size' => 270));
                    $user_info = get_userdata($userID);
                ?>
                <p class="bangxephang-winner-photo">
                    <img src="<?php echo $avatar_url; ?>" alt="silver" width="169px">
                    <span class="bangxephang-winner-badge"><img src="<?=IMG?>/images/silver_b.png" alt="silver"></span>
                </p>
                <h3 class="bangxephang-winner-name"><?=$user_info->display_name?></h3>
                <span class="bangxephang-winner-score"><?=$score?></span>
                <?php else: ?>
                    <p class="bangxephang-winner-photo">
                        <img src="<?=IMG?>/images/noavatar.jpg" alt="silver" width="169px">
                        <span class="bangxephang-winner-badge"><img src="<?=IMG?>/images/copper_b.png" alt="copper"></span>
                    </p>
                    <h3 class="bangxephang-winner-name">?????</h3>
                    <span class="bangxephang-winner-score">???</span>
                <?php endif; ?>
            </div>


            <!-- WINNER GOLD -->            
            <div class="bangxephang-winner-item">
                <?php if(isset($getposts[0])):
                    $id = (int)$getposts[0]->ID;
                    $userID= get_field('ls_iduser',$id);
                    $score= get_field('ls_score',$id);
                    $timeUsed= get_field('ls_timeUsed',$id);
                    $avatar_url = get_avatar_url($userID, array('size' => 243));
                    $user_info = get_userdata($userID);                                    
                ?>
                <p class="bangxephang-winner-photo">
                    <img src="<?php echo $avatar_url; ?>" alt="gold" width="243px">
                    <span class="bangxephang-winner-badge"><img src="<?=IMG?>/images/gold_b.png" alt="gold"></span>
                </p>
                <h3 class="bangxephang-winner-name"><?=$user_info->display_name?></h3>
                <span class="bangxephang-winner-score"><?=$score?></span>
                <?php else: ?>
                    <p class="bangxephang-winner-photo">
                        <img src="<?=IMG?>/images/noavatar.jpg" alt="silver" width="243px">
                        <span class="bangxephang-winner-badge"><img src="<?=IMG?>/images/copper_b.png" alt="copper"></span>
                    </p>
                    <h3 class="bangxephang-winner-name">?????</h3>
                    <span class="bangxephang-winner-score">???</span>
                <?php endif; ?>
            </div>
            

            <!-- WINNER COPPER -->            
            <div class="bangxephang-winner-item">
                <?php if(isset($getposts[2])):
                    $id = (int)$getposts[2]->ID;
                    $userID= get_field('ls_iduser',$id);
                    $score= get_field('ls_score',$id);
                    $timeUsed= get_field('ls_timeUsed',$id);
                    $avatar_url = get_avatar_url($userID, array('size' => 270));
                    $user_info = get_userdata($userID);
                ?>
                <p class="bangxephang-winner-photo">
                    <img src="<?php echo $avatar_url; ?>" alt="silver"  width="270px">
                    <span class="bangxephang-winner-badge"><img src="<?=IMG?>/images/copper_b.png" alt="copper"></span>
                </p>
                <h3 class="bangxephang-winner-name"><?=$user_info->display_name?></h3>
                <span class="bangxephang-winner-score"><?=$score?></span>
                <?php else: ?>
                    <p class="bangxephang-winner-photo">
                        <img src="<?=IMG?>/images/noavatar.jpg" alt="silver" width="270px">
                        <span class="bangxephang-winner-badge"><img src="<?=IMG?>/images/copper_b.png" alt="copper"></span>
                    </p>
                    <h3 class="bangxephang-winner-name">?????</h3>
                    <span class="bangxephang-winner-score">???</span>
                <?php endif; ?>
            </div>
        </div>
        <!-- TABLE WINNER -->
        <div><img src="<?=IMG?>/images/winner_table.png" alt="table"></div>
    </div>

    <!-- LIST WINNER -->
    <div class="bangxephang-list-container">
        <div id="bangxephang-list-winner" class="bangxephang-list-winner scrollBar-cs">
            <!-- BOX LIST -->
            <div id="bangxephang-items-container" class="bangxephang-list-box">
                <?php foreach($getposts as $k=>$v):
                    if($k>2):
                        $id = (int)$getposts[$k]->ID;
                        $userID= get_field('ls_iduser',$id);
                        $score= get_field('ls_score',$id);
                        $timeUsed= get_field('ls_timeUsed',$id);
                        $avatar_url = get_avatar_url($userID, array('size' => 270));
                        $user_info = get_userdata($userID);
                ?>
                    <div class="bangxephang-list-item">
                        <div class="bangxephang-list-left">
                            <span class="bangxephang-list-stt"><?=(($k+1)<10) ? '0'.($k+1) : ($k+1)?></span>
                            <span class="bangxephang-list-avatar"><img src="<?php echo $avatar_url; ?>" alt="avatar"></span>
                            <div class="bangxephang-list-name">
                                <h3><?=$user_info->display_name?></h3>
                                <div class="bangxephang-score-mb"><span><?=$score?></span></div>
                            </div>                            
                        </div>
                        <div class="bangxephang-list-right"><span><?=$score?></span></div>
                    </div>
                <?php endif; endforeach;?>
            </div>
        </div>
        <div id="loader" style="text-align:center; display:none;">Đang tải...</div>
        <div id="load-more-data" data-page="1" data-url="<?php echo admin_url('admin-ajax.php'); ?>"> </div>
    </div>
</div>

<!-- PAGINATION -->
<input type="hidden" value="0" id="bangxephang-page">

<?php
        $output = ob_get_clean(); 
        return $output;
    endif;
}
add_shortcode( 'bangxephang_shortcode', 'bangxephang_init' );