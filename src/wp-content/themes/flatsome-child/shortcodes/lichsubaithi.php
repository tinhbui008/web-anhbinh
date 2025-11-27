<?php
// ### CLICK TAB
function show_lichsubaithi_list() {
    $user_id = USER_LOGGED;

    $args = array(
        'post_type'      => 'lichsubaithi',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => 'ls_iduser',
                'value'   => $user_id,
                'compare' => '='
            )
        ),
        'meta_key'       => 'ls_idCategory',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    if($query->have_posts() && USER_LOGGED!=0):
        ob_start();
?>

<div class="gameplay-baithi-contain">
    <div class="gameplay-baithi-list">
        <?php
            while ( $query->have_posts() ) : $query->the_post();
                $id = (int)get_the_ID();
                
                $idPost = get_field('ls_idPost',$id);  
                $userID = get_field('ls_iduser',$id);   
                $score = get_field('ls_score',$id);
                $timeUsed =  get_field('ls_timeUsed',$id);  
                $idCategory = get_field('ls_idCategory',$id);
                $resultTest = get_field('ls_result_test',$id);
                $title = get_the_title($idPost);
                $resultTest = ($resultTest!='') ? json_decode($resultTest,true) : null;
                //dd($resultTest);
        ?>
        <a href="javascript:;" data-fancybox data-src="#gameplay-hidden-content-<?=$id?>" class="gameplay-baithi-item" data-id="<?=$id?>">
            <p class="gameplay-baithi-img"><img src="<?=IMG?>/images/baithi.png" alt="bai-thi" class="img-object-cover"></p>
            <div>
                <p><?=date('H:i d/m/Y',$idCategory)?></p>
                <h3><?=$title;?></h3>
            </div>
            <div id="gameplay-hidden-content-<?=$id?>" class="gameplay-show-content" style="display: none;">
                <div class="gameplay-baithi-content scrollBar-cs">
                    <p><?=$title;?></p>
                    <div class="gameplay-baithi-top">
                        <p><span>- Thời gian làm bài:</span> <strong><?=($timeUsed>0) ? convertSeconds($timeUsed) : 0?></strong></p>
                        <p><span>- Điểm số:</span> <strong><?=$score?></strong></p>
                    </div>

                    <!-- RESULT -->
                    <?php if($resultTest):?>
                    <div class="gameplay-baithi-bot">
                        <?php foreach($resultTest as $k => $v):
                            $options = $v['options'];
                            $selected = $v['selected'];
                            $count_selected = ($v['selected']!='') ? count($selected) : 0;
                        ?>
                            <div class="gameplay-baithi-box">
                                <div class="gameplay-baithi-box-question"><strong>Câu hỏi số <?=$k+1?> :</strong> "<?=$v['question']?>"</div>
                                <div class="gameplay-baithi-box-answer">
                                    <?php if($v['skip']==1):?>
                                        <span>- Người chơi bỏ qua câu hỏi</span>
                                    <?php else:?>
                                        <span>- Đáp án đã chọn:</span>
                                        <?php if($v['question_display_type']=='cauhoi_tuluan'):?>
                                            <span><?=$v['selected']?></span>
                                        <?php else:?>
                                            <?php for($i=0;$i<$count_selected;$i++):?>
                                                <span><?=$options[$selected[$i]]?> <?=($i!=($count_selected-1)) ? ', ':''?></span>
                                            <?php endfor;?>
                                        <?php endif;?>
                                    <?php endif;?>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </a>
        <?php endwhile;wp_reset_postdata();?>
    </div>
</div>

<?php   
    else:
?>

<div class="gameplay-baithi-contain">
    <div class="container-page gameplay-baithi-error">
        <?php if(USER_LOGGED==0):?>
            Bạn không có quyền được truy cập vào đây !
        <?php else:?>
            Không có dữ liệu để hiển thị !
        <?php endif;?>
    </div>
</div>

<?php
        $output = ob_get_clean(); 
        return $output;
    endif;
}
add_shortcode( 'lichsubaithi_shortcode', 'show_lichsubaithi_list' );