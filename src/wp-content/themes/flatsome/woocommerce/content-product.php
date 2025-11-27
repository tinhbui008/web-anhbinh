<?php

// $start_date = new DateTime('11-03-2025'); // Ngày bắt đầu
// $end_date = new DateTime('13-03-2025');   // Ngày kết thúc
// //$end_date->modify('+1 day'); // Để bao gồm ngày kết thúc trong vòng lặp

// $interval = new DateInterval('P1D'); // Khoảng thời gian là 1 ngày
// $date_range = new DatePeriod($start_date, $interval, $end_date);
// foreach ($date_range as $date) {
//     echo $date->format('d-m-Y') . "<br>";
// }

// ### CONVERT DATE : functions.php
$dev_date = convertDate();

// ### QUERY PRODUCT LIST
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 20, // number of products to display
    'post_status' => 'publish',
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    //while ($query->have_posts()) : $query->the_post();
        global $product;

		$product_id = get_the_ID();
		$title = get_the_title();
		$permalink = get_the_permalink();
		//$price = $product->get_price_html();
		$price_int = $product->get_price();
		$excerpt = get_the_excerpt();
		$sku = $product->get_sku();
		$price = (isset($dev_date['number_of_nights']) && $dev_date['number_of_nights']>0) ? ($dev_date['number_of_nights']*$price_int) : $price_int;
		//$is_in_stock = ($product->is_in_stock() ? 'In stock' : 'Out of stock');

		// Get the main product thumbnail (featured image)
		$featured_image_id = get_post_thumbnail_id($product_id);

		// Get additional gallery image IDs
		$gallery_image_ids = $product->get_gallery_image_ids();

		// Combine the featured image and gallery images
		$image_ids = array_merge([$featured_image_id], $gallery_image_ids);

		$adults = get_field('adults',$product_id);
	?>

	<div class="dev-booking-box dev-booking-box-<?=$product_id?>" data-id="<?=$product_id?>">
		<div class="dev-booking-images">
			<?php foreach ($image_ids as $k=>$image_id): 
				$image_url = wp_get_attachment_image_url($image_id, 'large');
			?>
				<a href="<?=esc_url($image_url)?>" class="<?=($k==0) ? 'thumbnail-main' : 'thumbnail-hidden'?>" data-fancybox="gallery-<?=$product_id?>"><img src="<?=esc_url($image_url)?>" alt="thumbnail"></a>
			<?php endforeach;?>
			<span class="dev-booking-images-view"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" stroke="#fff" stroke-width="1.5"/> <circle cx="16" cy="8" r="2" stroke="#fff" stroke-width="1.5"/> <path d="M2 12.5001L3.75159 10.9675C4.66286 10.1702 6.03628 10.2159 6.89249 11.0721L11.1822 15.3618C11.8694 16.0491 12.9512 16.1428 13.7464 15.5839L14.0446 15.3744C15.1888 14.5702 16.7369 14.6634 17.7765 15.599L21 18.5001" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/> </svg><?=pll('写真を見る','See photo')?></span>
		</div>
		<div class="dev-booking-info">
			<!-- INFO -->
			<div class="dev-booking-topinfo">
				<div class="dev-booking-topinfoBox">
					<h2><?=$title?></h2>
					<p><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M2 6V18" stroke="#666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M22 18V15.6429C22 13.6479 22 12.6505 21.7194 11.8486C21.2169 10.4124 20.0876 9.28314 18.6514 8.78058C17.8495 8.5 16.8521 8.5 14.8571 8.5C14.0592 8.5 13.6602 8.5 13.3394 8.61223C12.7649 8.81326 12.3133 9.26495 12.1122 9.83944C12 10.1602 12 10.5592 12 11.3571V16" stroke="#666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M2 16H22" stroke="#666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M9.5 11C9.5 12.3807 8.38071 13.5 7 13.5C5.61929 13.5 4.5 12.3807 4.5 11C4.5 9.61929 5.61929 8.5 7 8.5C8.38071 8.5 9.5 9.61929 9.5 11Z" stroke="#666" stroke-width="1.5"/> </svg> <span>1 giường đôi lớn</span></p>
				</div>
				<span class="dev-booking-viewDetail"><?=pll('詳細情報','Detailed information')?></span>
			</div>
			<!-- EXCERPT -->
			<div class="dev-booking-excerpt"><?=$excerpt?></div>
			<!--  DETAIL -->
			<div class="dev-booking-boxDetail">
				<!-- BOX 1 -->
				<div class="dev-booking-boxDetail-Box1">
					<span class="boxRoom-adults"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="12" cy="6" r="4" stroke="#1C274C" stroke-width="1.5"/> <path d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" stroke="#1C274C" stroke-width="1.5"/> </svg><?=$adults?> <?=pll('大人','Adults')?></span>
				</div>
				<!-- BOX 2 -->

				<div class="dev-booking-price"><?=number_format($price,0,',','.')?>đ</div>
				<!-- BOX 3 -->
				<div class="dev-booking-buy" data-idProduct="<?=$product_id?>"><?=pll('選択','Select')?></span></div>
				<div class="dev-booking-cart dev-booking-cart-<?=$product_id?>"><?=do_shortcode('[add_to_cart id="'.$product_id.'"]')?></div>
				<input type="hidden" name="booking-price-product" value="<?=$price_int;?>">
				<input type="hidden" name="booking-quantity-product" value="0">
			</div>
		</div>
	</div>

	<?php //endwhile;	?>
	
    <?php wp_reset_postdata();
else :
    echo 'No products found';
endif;