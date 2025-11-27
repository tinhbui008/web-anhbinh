<?php

add_shortcode( 'numberIntro-shortcode', 'numberIntro' );

function numberIntro($attr) {
    $option = shortcode_atts(['number'=>''],$attr);
    ob_start();
?>  

    <div class="numberIntro-box">
        <span><?=$option['number']?></span>
    </div>
   
<?php 
        $output = ob_get_clean(); 
        return $output;
    }
?>