<?php

add_shortcode( 'gallery-item-shortcode', 'gallery_item_init' );

function gallery_item_init() {
    global $post;

    if(isset($post)):
        $idPost = 2417;//$post->ID;
        $gallery = get_field('list_box_gallery',$idPost);
        if(isset($gallery)):
            $max_gallery = count($gallery);
        ob_start();
?>


<div class="detailItem-gallery">
    <a href="<?=$gallery[0]?>" id="Zoom-gallery" data-image="<?=$gallery[0]?>" data-options="zoomWidth:400px; zoomHeight:400px;zoomMode: off;cssClass: mz-square" class="MagicZoom galleryItem"><img src="<?=$gallery[0]?>"></a>

    <?php for($i=0;$i<$max_gallery;$i++):?>
    <a href="<?=$gallery[$i]?>" data-zoom-id="Zoom-gallery" data-image="<?=$gallery[$i]?>" class="gallery-hidden galleryItem"><img src="<?=$gallery[$i]?>" alt="gallery-photo"></a>    
    <?php endfor;?>
</div>
    

<?php 
        endif;
        $output = ob_get_clean(); 
        return $output;
    endif;
}