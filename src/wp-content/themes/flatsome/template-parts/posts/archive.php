<?php
/**
 * Posts archive.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

function getCurrentCatID(){
	global $wp_query;
	if(is_category() || is_single()){
		$cat_ID = get_query_var('cat');
	}
	return $cat_ID;
}

$category_id = getCurrentCatID();	
$checssearch = isset($_GET['s']) ? true : false;
$list_cat = get_the_category();
$parentID = get_category($category_id)->parent;

$banner = get_field('banner', 'category_'.$category_id);

if($banner!=''):?>
<div class="banner-detail">
	<img src="<?=$banner?>" alt="banner">
</div>
<?php endif;

if ( have_posts() ) : ?>

<div id="post-list" class="<?=($category_id==71) ? 'section-page-detail-ourwork' : 'section-page-detail'?>">

<?php /* Start the Loop */ ?>

<?php if($checssearch):?>
<div class="search-container">
	<div class="container-page">
		<h2 class="search-titleDev">Kết quả tìm kiếm</h2>
		<!-- DEFINE PAGE -->
		<div class="news-detail-contain">
			<?php while ( have_posts() ) : the_post(); 
				$id = (int)get_the_ID();
				$category_detail=get_the_category($id); // $post->ID
				$show_excerpt = $excerpt;
			?>
				<a href="<?php the_permalink() ?>" class="news-detail-box news-detail-box-w" data-aos="fade-up">
					<?php if(has_post_thumbnail()) { ?>
						<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
							<?php the_post_thumbnail( $image_size ); ?>
						</div>
					<?php }else{ ?>
						<div class="box-image">
							<span class="box-noneImg">No image</span>
						</div>
					<?php }?>
					<div class="news-detail-info">
						<?php
							/*$catname = array();
							foreach($category_detail as $cd){
								if($cd->cat_ID!=$activeID){$catname[] = $cd->cat_name;}
							}*/
						?>						
						<h2><?php the_title(); ?></h2>
						<div class="news-detail-excerpt"><?php the_excerpt(); ?></div>
					</div>
					<?php /*
					<div class="news-detail-date"><?php echo '<p>'.get_the_date('d').'</p>'.' <span>Th'.get_the_date('m').'</span>'; ?></div>*/ ?>
				</a>
			<?php endwhile; ?>
		</div>
	</div>
</div>
<?php else:?>

    <?php if($parentID==96):			
		$now = time(); // Thời gian hiện tại theo server | đã được thiết lập theo múi giờ VN

		//Chỉnh lại múi giờ cho custom field time
		$thoigian_binhchon = get_field('thoigian_binhchon','category_'.$category_id);
		$wp_timezone = wp_timezone(); // Lấy timezone đang được cấu hình trong WordPress admin
		$thoigian_binhchon = new DateTime($thoigian_binhchon, new DateTimeZone('UTC'));
		$thoigian_binhchon->setTimezone($wp_timezone);
		$thoigian_binhchon = $thoigian_binhchon->format('Y-m-d H:i');
		$endTime = strtotime($thoigian_binhchon);

		//dd($thoigian_binhchon);
		//dd(date('d/m/Y H:i:s', $now));
		
	?>
		<input type="hidden" value="<?=$endTime?>" id="thoigianbinhchon">
		<input type="hidden" value="<?=$now?>" id="timeserver">

        <div class="vnplay-container">
			<div class="vnplay-time">
				<div class="vnplay-time-clock">
					<img src="<?=IMG?>/images/clock.png" alt="clock">
					<span id="countdown" class="vnplay-time-count">Kết thúc sau ... </span>
				</div>
			</div>
			<div class="vnplay-contain container-page">
				<div class="vnplay-list">
					<?php while ( have_posts() ) : the_post(); 
						$id = (int)get_the_ID();
						$icon = get_field('vnplay_icon',$id);
						$name = get_field('vnplay_name',$id);
						// DS users						
    					$user_ids = get_post_meta($id, 'luot_binh_chon', true);
						$user_ids = ($user_ids) ? $user_ids : null;
					?>
						<div class="vnplay-item vnplay-item-<?=$id?>">
							<div class="box-image">
								<?php if(has_post_thumbnail()) { ?>
									<?php the_post_thumbnail( $image_size ); ?>
								<?php }else{ ?>
									<span class="box-noneImg">No image</span>
								<?php }?>
							</div>
							<div class="vnplay-info">
								<div class="vnplay-smallInfo">
									<p class="vnplay-photo">
										<?php if($icon!='') { ?>
											<img src="<?=$icon?>" alt="icon">
										<?php }else{ ?>
											<span class="box-noIcon">64x64</span>
										<?php }?>
									</p>
									<h3 class="vnplay-name"><?= $name; ?></h3>
								</div>
								<div>
									<p class="vnplay-luotbinhchon"><span><?=(isset($user_ids)) ? count($user_ids) : 0?></span> Lượt bình chọn</p>
									<p class="vnplay-btn vnplay-btn-<?=$id?> <?= (isset($user_ids) && in_array(USER_LOGGED, $user_ids)) ? 'has-binhchon' : '' ?>" data-id="<?=$id?>"><svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0.166626 5.61413C0.166626 9.66657 3.51615 11.8261 5.96807 13.7589C6.83329 14.441 7.66663 15.0832 8.49996 15.0832C9.33329 15.0832 10.1666 14.441 11.0318 13.7589C13.4838 11.8261 16.8333 9.66657 16.8333 5.61413C16.8333 1.56168 12.2498 -1.31224 8.49996 2.58374C4.75009 -1.31224 0.166626 1.56168 0.166626 5.61413Z" fill="#111111"/> </svg> <span><?= (isset($user_ids) && in_array(USER_LOGGED, $user_ids)) ? 'Đã bình chọn' : 'Bình chọn' ?></span></p>
								</div>							
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				<div class="vnplay-pagination"><?php flatsome_posts_pagination(); ?></div>
			</div>
		</div>
		
	<?php elseif($category_id==72 || $category_id==72): // PAGE: blog page ?>
		<!-- UX BLOG -->
		<?=do_shortcode('[block id="our-blog"]')?>

		<!-- DEFINE PAGE -->
		<div class="blog-detail-contain">
			<?php while ( have_posts() ) : the_post(); 
				$id = (int)get_the_ID();
				$excerpt = get_the_excerpt($id);				
			?>
				<a href="<?php the_permalink() ?>" class="blog-detail-box target-cursor" data-aos="fade-up">
					<div>
						<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
							<?php if(has_post_thumbnail()) { ?>						
								<?php the_post_thumbnail( $image_size ); ?>
							<?php }else{ ?>
								<span class="box-noneImg">No image</span>						
							<?php }?>
						</div>
						<div class="blog-detail-info">
							<h3><?php the_title(); ?></h3>
							<div class="blog-detail-des"><?=$excerpt?></div>
						</div>
					</div>
					<div class="blog-detail-datebtn">
						<span><?php echo get_the_date('M d Y'); ?></span>
						<p>Read more</p>
					</div>
				</a>
			<?php endwhile; ?>
		</div>

	<?php elseif($category_id==9999 ): // PAGE: tuyển dụng ?>

		<div class="tuyendung-detail-contain">
			<?php while ( have_posts() ) : the_post(); 
				$id = (int)get_the_ID();
				$quocgia = get_field('tuyendung_quocgia',$id);
				$tinhthanh = get_field('tuyendung_tinhthanh',$id);
			?>
				<div class="tuyendung-detail-item " data-aos="fade-up">
					<h3><?php the_title(); ?></h3>
					<div class="tuyendung-detail-info">
						<div class="tuyendung-detail-position">
							<p class="tuyendung-detail-country"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <path d="M4 10.1433C4 5.64588 7.58172 2 12 2C16.4183 2 20 5.64588 20 10.1433C20 14.6055 17.4467 19.8124 13.4629 21.6744C12.5343 22.1085 11.4657 22.1085 10.5371 21.6744C6.55332 19.8124 4 14.6055 4 10.1433Z" stroke="#0042A5" stroke-width="1.5"/> <circle cx="12" cy="10" r="3" stroke="#0042A5" stroke-width="1.5"/> </svg><?=$quocgia?></p>
							<p class="tuyendung-detail-country"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <path d="M10.721 21H13.358C15.5854 21 16.6992 21 17.6289 20.4672C18.5586 19.9345 19.1488 18.958 20.3294 17.005L21.0102 15.8787C22.0034 14.2357 22.5 13.4142 22.5 12.5C22.5 11.5858 22.0034 10.7643 21.0102 9.12126L20.3294 7.99501C19.1488 6.04203 18.5586 5.06554 17.6289 4.53277C16.6992 4 15.5854 4 13.358 4H10.721C6.84561 4 4.90789 4 3.70394 5.2448C2.5 6.48959 2.5 8.49306 2.5 12.5C2.5 16.5069 2.5 18.5104 3.70394 19.7552C4.90789 21 6.8456 21 10.721 21Z" stroke="#0042A5" stroke-width="1.5" stroke-linecap="round"/> <path d="M7.5 7.99512V17" stroke="#0042A5" stroke-width="1.5" stroke-linecap="round"/> </svg><?=$tinhthanh?></p>
							<div class="tuyendung-detail-excerpt"><?php the_excerpt(); ?></div>
						</div>
						<a href="<?php the_permalink() ?>" class="tuyendung-detail-apply button-style1">Ứng tuyển</a>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	<?php elseif($category_id==74 || $parentID==74): // PAGE HAS CATEGORY LIST	
		
	?>

	<div class="daotao-detail-page">
		<div class="daotao-detail-main container-page">
			<?php while ( have_posts() ) : the_post(); 
				$id = (int)get_the_ID();
				$category_detail=get_the_category($id); // $post->ID
				$show_excerpt = $excerpt;
			?>
				<a href="<?php the_permalink() ?>" class="daotao-detail-item">
					<?php if(has_post_thumbnail()) { ?>
						<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
							<?php the_post_thumbnail( $image_size ); ?>
						</div>
					<?php }else{ ?>
						<div class="box-image">
							<span class="box-noneImg">No image</span>
						</div>
					<?php }?>
					<div class="s-truyenthong-date"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_1960)"> <path d="M9.99984 4.99984V9.99984L13.3332 11.6665M18.3332 9.99984C18.3332 14.6022 14.6022 18.3332 9.99984 18.3332C5.39746 18.3332 1.6665 14.6022 1.6665 9.99984C1.6665 5.39746 5.39746 1.6665 9.99984 1.6665C14.6022 1.6665 18.3332 5.39746 18.3332 9.99984Z" stroke="#6D6D6D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g> <defs> <clipPath id="clip0_296_1960"> <rect width="20" height="20" fill="white"></rect> </clipPath> </defs> </svg><?=get_the_date('d/m/y')?></div>
					<h3 class="s-truyenthong-title"><?php the_title(); ?></h3>
					<div class="s-truyenthong-title-excerpt"><?php the_excerpt(); ?></div>
				</a>
			<?php endwhile; ?>
		</div>
		<span class="page-tuyendung-icon5"><img src="<?=IMG?>/images/tuyendung/td_i5.png" alt="icon"></span>
		<span class="page-tuyendung-icon6"><img src="<?=IMG?>/images/tuyendung/td_i6.png" alt="icon"></span>
		<span class="page-tuyendung-icon7"><img src="<?=IMG?>/images/tuyendung/td_i7.png" alt="icon" width="100%"></span>
	</div>

	<?php elseif($category_id==79 || $parentID==79): // PAGE HAS CATEGORY LIST	
		$category_id = ($category_id==$parentID) ? $category_id : $parentID;
		$list_cat = get_the_category();
		$activeID = 79;
		
		$args = array(
    	    'style'      => 'list',
    	    'hide_empty' => 0,
    	    'parent' => $activeID
    	);
        $list_childs = get_categories($args);
		$cateID_active = get_queried_object();
	?>

	<div class="s-truyenthong-container">
		<!-- BÀI VIẾT GẦN ĐÂY -->
		<?php echo do_shortcode('[tintuc_sukien_shortcode]'); ?>

		<?php if($list_childs && $category_id!=79): foreach($list_childs as $k_child=>$child):
			// Lấy permalink của danh mục
    		$category_link = get_category_link($child->term_id);

    		// Lấy 3 bài viết mới nhất trong danh mục
		    $post_args = array(
		        'cat'            => $child->term_id,
		        'posts_per_page' => 3,
		        'orderby'        => 'date',
		        'order'          => 'DESC'
		    );
		    $posts = get_posts($post_args);
		?>
		<div class="container-page s-tintucsukien-container">
			<!-- TOP TITLE -->
			<div class="s-tintucsukien-top">
				<h2><?=$child->name?></h2>
				<a href="<?=$category_link?>" class="button primary button-style1"><span>Xem tất cả</span></a>
			</div>
			<!-- LIST -->
			<div class="s-tintucsukien-list">
				<?php foreach ($posts as $post) :
					$id = (int)$post->ID;
			        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
			        $title = $post->post_title;
			        $content = $post->post_excerpt;
			        $permalink = get_the_permalink($id);
			        $date = get_the_date('d/m/Y H:i', $post);
				?>
					<a href="<?=$permalink?>" class="s-tintucsukien-box s-tintucsukien-cat-box s-truyenthong-item">
						<div class="box-image">
							<?php if($img!=''):?><img src="<?=$img?>" alt="photo">
							<?php else:?><span class="box-noneImg">No image</span><?php endif;?>
						</div>
						<div class="s-truyenthong-date"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_1960)"> <path d="M9.99984 4.99984V9.99984L13.3332 11.6665M18.3332 9.99984C18.3332 14.6022 14.6022 18.3332 9.99984 18.3332C5.39746 18.3332 1.6665 14.6022 1.6665 9.99984C1.6665 5.39746 5.39746 1.6665 9.99984 1.6665C14.6022 1.6665 18.3332 5.39746 18.3332 9.99984Z" stroke="#6D6D6D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g> <defs> <clipPath id="clip0_296_1960"> <rect width="20" height="20" fill="white"></rect> </clipPath> </defs> </svg><?=$date?></div>
						<h3 class="s-truyenthong-title"><?=$title?></h3>
						<div class="s-truyenthong-title-excerpt"><?=$content?></div>
					</a>
				<?php endforeach;?>
			</div>
		</div>		
		<?php endforeach; else:
			// Lấy trang hiện tại (paged)
			$paged = max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page'));
			// Lấy 3 bài viết mới nhất trong danh mục
		    $post_args = array(
		        'cat'            => $cateID_active->cat_ID,
		        'posts_per_page' => 12,
		        'orderby'        => 'date',
		        'order'          => 'DESC',
		        'paged'          => $paged
		    );
		    $posts = get_posts($post_args);
		    $wp_query = new WP_Query($post_args);
		?>
		<div class="container-page s-tintucsukien-container">
			<div class="s-tintucsukien-top text-center">
				<h2><?=$cateID_active->name?></h2>
			</div>
			<!-- LIST -->
			<?php if($wp_query->have_posts()):?>
			<div class="s-tintucsukien-list">
				<?php while ($wp_query->have_posts()) : $wp_query->the_post();
					$id = (int)get_the_ID();
			        $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
			        $title = $post->post_title;
			        $content = $post->post_excerpt;
			        $permalink = get_the_permalink($id);
			        $date = get_the_date('d/m/Y H:i', $post);
				?>
					<a href="<?=$permalink?>" class="s-tintucsukien-box s-truyenthong-item">
						<div class="box-image">
							<?php if($img!=''):?><img src="<?=$img?>" alt="photo">
							<?php else:?><span class="box-noneImg">No image</span><?php endif;?>							
						</div>
						<div class="s-truyenthong-date"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_296_1960)"> <path d="M9.99984 4.99984V9.99984L13.3332 11.6665M18.3332 9.99984C18.3332 14.6022 14.6022 18.3332 9.99984 18.3332C5.39746 18.3332 1.6665 14.6022 1.6665 9.99984C1.6665 5.39746 5.39746 1.6665 9.99984 1.6665C14.6022 1.6665 18.3332 5.39746 18.3332 9.99984Z" stroke="#6D6D6D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g> <defs> <clipPath id="clip0_296_1960"> <rect width="20" height="20" fill="white"></rect> </clipPath> </defs> </svg><?=$date?></div>
						<h3 class="s-truyenthong-title"><?=$title?></h3>
						<div class="s-truyenthong-title-excerpt"><?=$content?></div>
					</a>
				<?php endwhile;?>
			</div>
			<?php else:?>
				<p>Không có bài viết nào.</p>
			<?php endif;?>
		</div>
		<?php endif;?>
	</div>

		<?php /*
		<div class="news-detail-main">
			<?php if($list_childs):?>
				<div class="news-detail-tabs" data-aos="fade-up">
					<a href="<?=get_category_link($category_id) ?>" class="news-detail-tab <?=($cateID_active->cat_ID==$activeID) ? 'news-detail-tabactive' : ''?>">Tất cả</a>
					<?php foreach($list_childs as $child):?>
						<a href="<?=get_category_link($child->cat_ID) ?>" class="news-detail-tab <?=($cateID_active->cat_ID==$child->cat_ID) ? 'news-detail-tabactive' : ''?>"><?=$child->cat_name?></a>	
					<?php endforeach;?>
				</div>
			<?php endif;?>

			<div class="news-detail-contain container-page">
				<?php while ( have_posts() ) : the_post(); 
					$id = (int)get_the_ID();
					$category_detail=get_the_category($id); // $post->ID
					$show_excerpt = $excerpt;
				?>
					<a href="<?php the_permalink() ?>" class="news-detail-box news-detail-box-w">
						<?php if(has_post_thumbnail()) { ?>
							<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>">
								<?php the_post_thumbnail( $image_size ); ?>
							</div>
						<?php }else{ ?>
							<div class="box-image">
								<span class="box-noneImg">No image</span>
							</div>
						<?php }?>
						<div class="news-detail-info">													
							<h2><?php the_title(); ?></h2>
							<div class="news-detail-excerpt"><?php the_excerpt(); ?></div>
						</div>
						<!-- <div class="news-detail-date"><?php echo '<p>'.get_the_date('d').'</p>'.' <span>Th'.get_the_date('m').'</span>'; ?></div> -->
					</a>
				<?php endwhile; ?>
			</div>
			<span class="page-tuyendung-icon5"><img src="<?=IMG?>/images/tuyendung/td_i5.png" alt="icon"></span>
			<span class="page-tuyendung-icon6"><img src="<?=IMG?>/images/tuyendung/td_i6.png" alt="icon"></span>
			<span class="page-tuyendung-icon7"><img src="<?=IMG?>/images/tuyendung/td_i7.png" alt="icon" width="100%"></span>
		</div>
		*/ ?>

	<?php else:?>
		<!-- DEFINE PAGE -->
		<div class="news-detail-contain">
			<?php while ( have_posts() ) : the_post(); 
				$id = (int)get_the_ID();
				$excerpt = get_the_excerpt($id);
				$photo2 = get_field('photo2',$id);
			?>
				<a href="<?php the_permalink() ?>" class="news-detail-box grid-masonry-item target-cursor" data-aos="fade-up">
					<?php if($photo2!='') { ?>
						<div class="box-image" aria-label="<?php echo esc_attr( the_title() ); ?>"><img src="<?=$photo2?>" alt="<?php echo esc_attr( the_title() ); ?>"></div>
					<?php }else{ ?>
						<span class=""><img src="<?=IMG?>/images/noimage.png" alt=""></span>
					<?php }?>					
					<h3><?php the_title(); ?></h3>
					<div class="news-detail-des"><?=$excerpt?></div>
				</a>
			<?php endwhile; ?>
		</div>
	<?php endif;?>

<?php endif;?>

	<?php if($parentID!=96):?><?php flatsome_posts_pagination(); ?><?php endif;?>

</div>

<?php else : ?>
	<div class="section-page-detail">
		<?php if($category_id==37 || $parentID==37): 
			$list_childs = get_categories(array( 'parent' => 37, 'hide_empty'=>0, 'orderby' => 'name', 'order'=> 'ASC'));
			$cateID_active = get_queried_object();
		?>
			<?php if($list_childs):?>
				<div class="news-detail-tabs" data-aos="fade-up">
					<a href="<?=get_category_link($category_id) ?>" class="news-detail-tab <?=($cateID_active->cat_ID==37) ? 'news-detail-tabactive' : ''?>">Tất cả</a>
					<?php foreach($list_childs as $child):?>
						<a href="<?=get_category_link($child->cat_ID) ?>" class="news-detail-tab <?=($cateID_active->cat_ID==$child->cat_ID) ? 'news-detail-tabactive' : ''?>"><?=$child->cat_name?></a>
					<?php endforeach;?>
				</div>
			<?php endif;?>
		<?php endif;?>	

		<?php get_template_part( 'template-parts/posts/content','none'); ?>
	</div>

<?php endif; ?>



<!-- VNPLAY GAME -->
<?php if(HIENTHIGAME==1 && $parentID==96):?>
	<div class="vnplay-gameBox"><?=do_shortcode('[block id="do-vui-vung-vnplay"]');?></div>
	<?=do_shortcode('[bangxephang_shortcode]');?>
	<?=do_shortcode('[gameplay]');?>
	<input type="hidden" name="hienthigame" value="1">
<?php endif;?>