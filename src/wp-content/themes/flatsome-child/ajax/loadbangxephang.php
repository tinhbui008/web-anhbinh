<?php

// AJAX SHOW THÔNG TIN BÌNH CHỌN : KHI CLICK NÚT BÌNH CHỌN
add_action( 'wp_ajax_loadbangxephang', 'loadbangxephang_init' );
add_action( 'wp_ajax_nopriv_loadbangxephang', 'loadbangxephang_init' );
function loadbangxephang_init() {
    
    $offset = (isset($_POST['offset']))?esc_attr($_POST['offset']) : 0;

    $args = array(
        'post_type'      => 'lichsubaithi',
        'posts_per_page' => 20,
        //'offset'         => 20 * $offset, // lấy tiếp 50 items
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
        ),
        'orderby' => array(
            'score_clause' => 'DESC',
            'time_clause'  => 'ASC',
        ),
    );

    $getposts = get_posts($args);

    // GET DATA
    if(isset($getposts)):
        ob_start();
?>

    <!-- BOX LIST -->
    <!-- <div class="bangxephang-list-box"> -->
        <?php for($i=1;$i<=20;$i++):?>
            <div class="bangxephang-list-item">
                <div class="bangxephang-list-left">
                    <span class="bangxephang-list-stt"><?=($i<10) ? '0'.$i : $i?></span>
                    <span class="bangxephang-list-avatar"><img src="<?=IMG?>/images/gold.png" alt="avatar"></span>
                    <div class="bangxephang-list-name">
                        <h3>Phan Thị Mỹ Tiên</h3>
                        <div class="bangxephang-score-mb"><span>33333</span></div>
                    </div>
                </div>
                <div class="bangxephang-list-right"><span>33333</span></div>
            </div>
        <?php endfor;?>
    <!-- </div> -->

<?php else:?>
    <div class="nopage"></div>
<?php endif;?>

<?php
    $html = ob_get_clean();
    echo $html;
    wp_die();
}