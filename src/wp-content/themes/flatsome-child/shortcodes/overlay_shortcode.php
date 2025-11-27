<?php
// ### CLICK TAB
function overlay_init() { 
    ob_start();
?>

<!-- Layout background -->
<span class="page-tuyendung-icon5"><img src="<?=IMG?>/images/tuyendung/td_i5.png" alt="icon"></span>
<span class="page-tuyendung-icon6"><img src="<?=IMG?>/images/tuyendung/td_i6.png" alt="icon"></span>
<span class="page-tuyendung-icon7"><img src="<?=IMG?>/images/tuyendung/td_i7.png" alt="icon" width="100%"></span>
<span class="page-tuyendung-icon9"></span>

<span class="page-tuyendung-overlay"></span>
<span class="page-tuyendung-icon2"><img src="<?=IMG?>/images/tuyendung/td_i2.png" alt="icon" width="100%"></span>
<span class="page-tuyendung-icon4"><img src="<?=IMG?>/images/tuyendung/td_i4.png" alt="icon" width="100%"></span>
<span class="page-tuyendung-icon1"><img src="<?=IMG?>/images/tuyendung/td_i1.png" alt="icon"></span>
<span class="page-tuyendung-icon3"><img src="<?=IMG?>/images/tuyendung/td_i3.png" alt="icon"></span>
<span class="page-tuyendung-icon8"><img src="<?=IMG?>/images/tuyendung/td_i8.png" alt="icon"></span>

<?php
    $output = ob_get_clean(); 
    return $output;
}
add_shortcode( 'overlay_shortcode', 'overlay_init' );