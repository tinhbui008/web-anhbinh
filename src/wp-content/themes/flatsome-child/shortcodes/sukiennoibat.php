<?php
// ### CLICK TAB
function show_sukiennb_list() {
    $getposts = get_posts(array(
            'showposts' => 2,
            'post_type' => 'sukien',
            'orderby'   => array(
                'title' =>'ASC',
            )
        )
    );
    if(isset($getposts)):
        ob_start();
?>

<div class="sukiennb-container">
    <?php for($i=0;$i<2;$i++):?>
        <a href="###" class="sukiennb-item">
            <img src="<?=IMG?>/images/sukien/sk<?=$i+1?>.png" alt="su-kien">
            <div class="sukiennb-info">
                <p class="sukiensapden-date"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_2497)"> <path d="M10.0001 4.99984V9.99984L13.3334 11.6665M18.3334 9.99984C18.3334 14.6022 14.6025 18.3332 10.0001 18.3332C5.39771 18.3332 1.66675 14.6022 1.66675 9.99984C1.66675 5.39746 5.39771 1.6665 10.0001 1.6665C14.6025 1.6665 18.3334 5.39746 18.3334 9.99984Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g> <defs> <clipPath id="clip0_296_2497"> <rect width="20" height="20" fill="white"></rect> </clipPath> </defs> </svg>20/12/2024</p>
                <h3 class="sukiennb-name">Siêu hội Sinh Nhật - Rinh quà tiền tỷ</h3>
            </div>
        </a>
    <?php endfor;?>
</div>

<?php
        $output = ob_get_clean(); 
        return $output;
    endif;
}
add_shortcode( 'sukiennb_shortcode', 'show_sukiennb_list' );