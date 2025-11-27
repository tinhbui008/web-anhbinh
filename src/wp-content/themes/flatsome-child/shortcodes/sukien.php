<?php
function sukien_theo_ngay_shortcode($attr) {
    ob_start();

    $option = shortcode_atts(['type'=>''],$attr);
    $type = $option['type'];

    $today = current_time('Y-m-d');
    $today_start = $today . ' 00:00:00';
    $today_end = date('Y-m-d', strtotime('+6 days'));
    $seven_days_later = $today_end . ' 23:59:59';

    $args = array(
        'post_type' => $type,
        'posts_per_page' => -1,
        'meta_key' => 'ngaybatdau',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key'     => 'ngaybatdau',
                'value'   => array($today_start, $seven_days_later),
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME'
            )
        ),
    );

    $query = new WP_Query($args);
    $events_by_date = [];


    //### Get time tomorow
    $now = current_time('timestamp'); 
    $tomorrow = strtotime('tomorrow', $now);
    $year_current = date('Y', $now);
    $year_tomorrow = date('Y', strtotime('+6 days'));


    //### Get array 7 days
    function getDatesFromRange($start, $end, $format = 'Y-m-d'){
        // Declare an empty array
        $array = array();        
        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
        
        // Use loop to store date into array
        foreach($period as $date){
            //$timestamp = strtotime($date->date);
            $date_info = $date->format($format);
            $date_arr = explode('-', $date_info);

            $array[$date_info] = [
                'date-title' => GetCurrentWeekday(strtotime($date_info)),
                'date-month' => $date_arr[2].'/'.$date_arr[1],
            ];
        }
        
        // Return the array elements
        return $array;

    }
    
    //### Function call with passing the start date and end date
    $Date = getDatesFromRange($today, date('Y-m-d', strtotime('+6 days')));


    //### Get data
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $event_datetime = get_field('ngaybatdau');
            $wp_timezone = wp_timezone(); // Lấy timezone đang được cấu hình trong WordPress admin

            $event_datetime = new DateTime($event_datetime, new DateTimeZone('UTC'));
            $event_datetime->setTimezone($wp_timezone);
            $event_datetime = $event_datetime->format('Y-m-d H:i');

            if (!$event_datetime) continue;

            $timestamp = strtotime($event_datetime);
            $date = date('Y-m-d', $timestamp);
            $date_text = date('d/m/Y', $timestamp);
            $time = date('H:i', $timestamp);

            $id = (int)get_the_ID();
            $img = get_the_post_thumbnail_url($id, 'thumnail', array( 'class' =>'thumnail') );
            $linkdieuhuong = get_field('linkdieuhuong',$id);

            $events_by_date[$date][] = array(
                'id' => $id,
                'title' => get_the_title(),
                'content' => get_the_excerpt(),
                'diachi' => get_field('diachi',$id),
                'linkdieuhuong' => ($linkdieuhuong!='') ? $linkdieuhuong : get_the_permalink($id),
                'date' => $date_text,
                'time'  => $time,
                'photo' => $img,
            );

        }
        wp_reset_postdata();
    }


    //### Tìm ngày có nhiều sự kiện nhất
    $max_count = 0;
    $top_day = '';

    foreach ($events_by_date as $date => $events) {
        $count = count($events);
        if ($count > $max_count) {
            $max_count = $count;
            $top_day = $date;
        }
    }


    //### Lấy ngày trước đó
    $ngayTruocDo = date('Y-m-d', strtotime($today . ' -7 day'));

    //### Lấy ngày kế tiếp
    $ngayKeTiep = date('Y-m-d', strtotime($today_end . ' +1 day'));
?>


<div class="s-sukien-container">
    <div class="s-sukien-contain">
        <h2 class="s-sukien-title">Cùng xem sự kiện tháng nhé VNPAY-ers</h2>
        <div id="ajax-show-sukien" class="s-sukien-main">
            <!-- TABS -->
            <div class="s-sukien-tab">
                <!-- PREV DATE -->
                <span class="s-sukien-prev s-sukien-btn-submit" data-date="<?=$ngayTruocDo?>" data-type="<?=$type?>"><svg width="11" height="16" viewBox="0 0 11 16" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1.11201 7.19126C0.562907 7.59061 0.562907 8.40939 1.11201 8.80874L9.41183 14.845C10.0728 15.3257 11 14.8535 11 14.0362L11 1.96377C11 1.14648 10.0728 0.674329 9.41183 1.15503L1.11201 7.19126Z" fill="#005BAA"/> </svg></span>
                <div class="s-sukien-timeNow"><?=current_time('d/m')?><?=($year_tomorrow>$year_current) ? '/'.$year_current : ''?> - <?=date('d/m/Y', strtotime('+6 days'))?></div>
                <!-- NEXT DATE -->
                <span class="s-sukien-next s-sukien-btn-submit" data-date="<?=$ngayKeTiep?>" data-type="<?=$type?>"><svg width="11" height="16" viewBox="0 0 11 16" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M9.88799 7.19126C10.4371 7.59061 10.4371 8.40939 9.88799 8.80874L1.58817 14.845C0.927203 15.3257 3.97836e-08 14.8535 6.8144e-08 14.0362L4.87065e-07 1.96377C5.15426e-07 1.14648 0.927203 0.674329 1.58817 1.15503L9.88799 7.19126Z" fill="#005BAA"/> </svg></span>
            </div>
            <div id="s-sukien-containBox">
                <!-- DAYS -->
                <div class="s-sukien-topMain">
                    <div class="s-sukien-dates s-sukien-datesTop">
                        <?php foreach($Date as $k_date => $v_date):?>
                            <div class="s-sukien-box">
                                <!-- TAB DATE -->
                                <div class="s-sukien-date">
                                    <?=$v_date['date-title']?>
                                    <span><?=$v_date['date-month']?></span>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="<?=(!empty($events_by_date)) ? 's-sukien-dates-main' : 's-sukien-dates-main'?>" id="gridContainer">
                    <div class="s-sukien-dates s-sukien-datesBottom <?=($max_count>4) ? 'scrollBar-cs' : 'fix-scrollBar-cs'?>" id="scrollDiv">
                        <?php foreach($Date as $k_date => $v_date):
                            $event_items = (isset($events_by_date[$k_date])) ? $events_by_date[$k_date] : null;
                        ?>
                            <div class="s-sukien-box">
                                <!-- TAB LIST EVENT -->
                                <div class="s-sukien-list">
                                    <?php if(isset($event_items)): 
                                        foreach($event_items as $event):?>
                                        <div class="s-sukien-item" data-tooltip-id="tooltip-<?=$event['id']?>">
                                            <span class="s-sukien-item-time"><?=$event['time']?></span>
                                            <h3 class="s-sukien-item-title"><?=$event['title']?></h3>
                                            <!-- INFO -->
                                            <div class="s-sukien-itemsub-info" style="display:none">
                                                <div class="s-sukien-itemsub-infoCopy" id="tooltip-<?=$event['id']?>">
                                                    <div class="s-sukien-itemsub-top scrollBar-cs2">
                                                        <div class="s-sukien-itemsub-photo">
                                                            <?php if($event['photo']!='') { ?>
                                                                <img src="<?=$event['photo']?>" alt="photo">
                                                            <?php }else{ ?>
                                                                <span class="box-noneImg">No image</span>
                                                            <?php }?>
                                                        </div>
                                                        <p class="s-sukien-itemsub-title"><?=$event['title']?></p>
                                                        <p class="s-sukien-itemsub-date"><?=$event['time']?>, <?=$event['date']?></p>
                                                        <p class="s-sukien-itemsub-address"><?=$event['diachi']?></p>
                                                        <div class="s-sukien-itemsub-content"><?=$event['content']?></div>
                                                    </div>
                                                    <a href="<?=$event['linkdieuhuong']?>" target="_blank" class="s-sukien-itemsub-btn"><span>Xem chi tiết</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; 
                                        if(count($event_items)<$max_count):
                                            $un_max = $max_count - count($event_items);
                                            for($i=0;$i<$un_max;$i++): ?>
                                            <div class="s-sukien-item"></div>
                                        <?php endfor;endif;?>

                                    <?php else: for($i=0;$i<$max_count;$i++): ?>
                                        <div class="s-sukien-item"></div>
                                    <?php endfor;endif;?>
                                        
                                    <!-- NO EVENT -->
                                    <?php if(empty($events_by_date)):?>
                                        <?php for($i=0;$i<4;$i++): ?>
                                            <div class="s-sukien-item"></div>
                                        <?php endfor;?>
                                    <?php endif;?>

                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <!-- TOOLTIP -->
                    <div id="tooltipDev" class="tooltipDev">
                        <p class="s-sukien-itemsub-close"><span id="tooltip-close"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_323_2563)"> <path d="M13 1L1 13M1 1L13 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </g> <defs> <clipPath id="clip0_323_2563"> <rect width="14" height="14" fill="white"/> </clipPath> </defs> </svg></span></p>
                        <div id="tooltip-show-content"></div>
                    </div>
                    <span id="overlay-tooltip"></span>                  
                </div>
            </div>
        </div>
    </div>
</div>

<?php

    return ob_get_clean();
}
add_shortcode('sukien_theo_ngay', 'sukien_theo_ngay_shortcode');