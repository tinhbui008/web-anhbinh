<?php

add_shortcode( 'get_gallery_shortcode', 'get_gallery' );

function get_gallery() {
        $gallery_list = get_field('gallery_list',1576);

        if(isset($gallery_list)):
            ob_start();
    ?>

    <div class="section-page s-gallery-contain">
        <?php foreach($gallery_list as $v):?>
            <a href="<?=$v?>" data-fancybox="gallery" class="s-gallery-item"><img src="<?=$v?>" alt="gallery"></a>
        <?php endforeach;?>
    </div>

<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }



add_shortcode( 'get_gallery_boxes_shortcode', 'get_gallery_boxes' );

    function get_gallery_boxes($attr) {
            $option = shortcode_atts(['page_id'=>''],$attr);
            $gallery_boxes = get_field('gallery_box',$option['page_id']);
    
            if(isset($gallery_boxes)):
                ob_start();
        ?>

        <?php foreach($gallery_boxes as $k=>$v):
            $title = $v['gallery_title'];
            $gallery_list = $v['gallery_photo'];
        ?>
            <div class="gallery-boxes-item">
                <h2 class="gallery-boxe-title"><?=$title?></h2>

                <div class="section-page s-gallery-contain">
                    <?php foreach($gallery_list as $photo):?>
                        <a href="<?=$photo?>" data-fancybox="gallery-<?=$k?>" class="s-gallery-item"><img src="<?=$photo?>" alt="gallery"></a>
                    <?php endforeach;?>
                </div>
            </div>
        <?php endforeach;?>
    
<?php 
        $output = ob_get_clean(); 
        return $output;
        endif;
    }