<?php
/**
 * The template for displaying the footer.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

global $flatsome_opt;
?>

</main>

<footer id="footer" class="footer-wrapper">
	<?php do_action('flatsome_footer'); ?>
</footer>


<!-- LOADING -->
<input type="hidden" id="admin-url" value="<?php echo admin_url('admin-ajax.php');?>">
<input type="hidden" id="template-url" value="<?php bloginfo('template_directory'); ?>/js/">

<div class="loading-contain" style="display:none"><div class="loading-svg"><img src="<?=IMG?>/images/loading.svg" alt="loading"></div></div>
<div class="loading-contain2" style="display:none"><div class="loading-svg"><img src="<?=IMG?>/images/loading2.svg" alt="loading"></div></div>


<!-- EMBED SHORTCODE LOGIN POPUP -->
<?=do_shortcode('[popup_login]');?>


<!-- AJAX POPUP BOX -->
<div id="popup-binhchon">
	<div class="popup-binhchon-ajax"></div>
</div>

<!-- POPUP TEST WHEN ADMIN CLICK -->
<div id="popupBox" style="display:none; position:fixed; bottom:20px; right:20px; background:#fff; padding:10px; border:1px solid #ccc;">
    <h4>Thông báo từ Admin</h4>
    <div id="popupContent"></div>
    <button onclick="document.getElementById('popupBox').style.display='none'">Đóng</button>
</div>


<!-- FOOTER -->
<?php wp_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.fancybox.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.waypoints.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.countup.min.js"></script>



<?php /*
<script src="<?php bloginfo('template_directory'); ?>/js/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.marquee.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/flatpickr.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/splide.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/splide-extension-auto-scroll.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/quiz.js"></script>
*/ ?>


<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Gán vào window hoặc biến toàn cục
    window.USER_LOGGED = <?php echo USER_LOGGED; ?>;

    // Trạng thái người chơi game
    //window.IN_GAME = false;
</script>

<!-- TOOLTIP -->
<script src="<?php bloginfo('template_directory'); ?>/js/tooltip.js"></script>

<!-- APP FULL PAGE -->
<script src="<?php bloginfo('template_directory'); ?>/js/app.js"></script>

<!-- PUSHER CALL -->
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
    window.isGame = false;
    var pusher_app_key = jQuery('#pusher_app_key').val();
    var pusher_cluster = jQuery('#pusher_cluster').val();

    // Enable pusher logging - don't include this in production
    //Pusher.logToConsole = true;

    //### Gọi PUSHER
    const pusher = new Pusher(pusher_app_key, {
        cluster: pusher_cluster,
    });

    //### Lắng nghe sự kiện khi người chơi thêm vào phòng chờ
    const channel = pusher.subscribe('user-channel');
    channel.bind('user-added', function(data) {
        const userID = window.userID; // mã code tạm thời, sau này cập nhật lại thành ID user
        const userID_add = data.userID;
        const count_users = data.count;

        if(userID==userID_add){
            // Nếu người chơi hiện tại tham gia game --> thiết lập lại isGame
            window.isGame = true;
            // Cập nhật toàn bộ data từ pusher khi chưa tham gia            
            updateUserList(data);
        }else{
            // Cập nhật khi có 1 người chơi mới tham gia: không tải lại tất cả data từ pusher, mà chỉ tải thông tin người chơi mới
            addOtherUserToList(data);
        }

        jQuery('#userCodeForm h2').text('Đang có '+ count_users + ' người chơi');
    });

    // ### Lắng nghe sự kiện từ ADMIN khi admin xóa 1 người chơi
    channel.bind('user-removed', function(data) {
        removeUserFromList(data);
        //updateUserList(data);
    });

    // ### Lắng nghe sự kiện từ ADMIN: khi admin active (bắt đầu chơi game) ---> tất cả người chơi trong danh sách sẽ dc kích hoạt chơi game
    channel.bind('admin-activate', function(data) {
        const userID = window.userID; // mã code tạm thời, sau này cập nhật lại thành ID user
        if(data.users[userID]){
            loadBocauhoi(); // path: app.js
        }
    });

    // ### Lắng nghe sự kiện từ ADMIN: khi admin active (bắt đầu chơi game) ---> tất cả người chơi trong danh sách sẽ dc kích hoạt chơi game
    channel.bind('admin-reset', function(data) {
        //updateUserList(data); // đang lấy mẫu. Sau này thay đổi lại cơ chế
        adminResetGame();
    });


    //### Phương thức : Khi quản trị viên admin reset game --> xóa ds hiển thị người chơi
    function adminResetGame(data){
        const list = document.getElementById('userList');
        list.innerHTML = '';

        // Đóng khung game
        if(window.isGame==true){
            Swal.fire({
                title: "",
                text: "Bạn đã bị tước quyền thi đấu !",
                icon: "error",
                confirmButtonText: "Đã hiểu",
            });

            setTimeout(function() { 
                jQuery('#gameplay').removeClass('open-game').addClass('hidden-game');
                jQuery("#gameplay-middle").empty();
                jQuery('#topic-choosed').val(0);
                jQuery('#baithi-choosed').val(0);
                localStorage.clear();
                location.reload();
            }, 500);
        }
    }


    //### Phương thức : Xóa 1 người chơi --> cập nhật ds trong phòng chờ
    function removeUserFromList(data){
        const userID = window.userID; // mã code tạm thời, sau này cập nhật lại thành ID user
        const userID_removed = data.userID;
        const count_users = data.count;

        // Nếu người chơi hiện tại thoát game --> thiết lập lại isGame
        if(userID==userID_removed){
            window.isGame = false;
        }        

        jQuery('#userList').find('#user-'+userID_removed).remove();
        jQuery('#userCodeForm h2').text('Đang có '+ count_users + ' người chơi');

        // if(userID && userID==userID_removed){
        //     // Đóng khung game
        //     //alert('Bạn bị loại khỏi cuộc chơi!');

        //     setTimeout(function() { 
        //         jQuery('#gameplay').removeClass('open-game').addClass('hidden-game');
        //         jQuery("#gameplay-middle").empty();
        //         jQuery('#topic-choosed').val(0);
        //         jQuery('#baithi-choosed').val(0);
        //         localStorage.clear();
        //         location.reload();
        //     }, 500);
        // }else{
        //     jQuery('#userList').find('#user-'+userID_removed).remove();
        // }
    }


    //### Phương thức : Cập nhật thông tin 1 người chơi mới tham gia phòng chờ --> cập nhật ds trong phòng chờ
    function addOtherUserToList(data){
        const userID = window.userID; // mã code tạm thời, sau này cập nhật lại thành ID user
        const list = document.getElementById('userList');

        const code = data.userID;
        const user = data.user;

        if (!jQuery('#user-'+code).length && data.users[userID]) {
            const li = document.createElement('li');
            li.id = `user-${code}`;
            li.innerHTML = `
                <p><img src="${user.avatar}" width="30" height="30" style="border-radius:50%;"></p>
                <div><strong>${user.name}</strong><em>${user.status}</em></div>
            `;
            list.appendChild(li);
        }        
    }


    //### Phương thức : Cập nhật thông tin người chơi --> hiển thị ds trong phòng chờ
    function updateUserList(data) {
        const userID = window.userID; // mã code tạm thời, sau này cập nhật lại thành ID user
        const list = document.getElementById('userList');

        // Nếu tk ko còn hoặc admin xóa khỏi phòng ---> sẽ rời khỏi phòng chờ
        if (!userID || !data.users || !data.users[userID]) {
            list.innerHTML = '';
            return;
        }
        
        // Hiển thị ds người chơi
        list.innerHTML = '';

        Object.entries(data.users).forEach(([code, user]) => {
            const li = document.createElement('li');
            li.id = `user-${code}`;
            li.innerHTML = `
                <p><img src="${user.avatar}" width="30" height="30" style="border-radius:50%;"></p>
                <div><strong>${user.name}</strong><em>${user.status}</em></div>
            `;
            list.appendChild(li);
        });
    }


    // Trường hợp: đang trong phòng chờ nhưng người dùng reload page bằng cách enter trên thanh địa chỉ trình duyệt
    // Dựa vào session để kiểm tra và đẩy lên pusher ---> remove user trong danh sách tham gia
    jQuery(window).on('load', function () {
        const userPusher = sessionStorage.getItem("user_added_pusher");

        if(userPusher !== undefined && userPusher !== null && userPusher !== "" && !window.isGame && userPusher!=0){            
            if(userPusher!=0){
                var ajaxurl = jQuery('#admin-url').val();
                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=remove_user_code&user_code=' + encodeURIComponent(userPusher)
                })
                .then(res => res.json())
                .then(data => {
                    sessionStorage.setItem("user_added_pusher", 0);
                });
            } 
        }
    });


    //### Phương thức : vào game
    // function showPopup(data){
    //     const userID = window.userID; // mã code tạm thời, sau này cập nhật lại thành ID user

    //     if (!userID || !data.users || !data.users[userID]) {
    //         return;
    //     }

    //     const user = data.users[userID];
    //     const popup = document.getElementById('popupBox');
    //     const content = document.getElementById('popupContent');
    //     content.innerHTML = 'Thông tin game';
    //     //content.innerHTML = '<ul>' + user.map(u => `<li>${u.name} - ${u.status}</li>`).join('') + '</ul>';
    //     popup.style.display = 'block';
    // }


    function loadBocauhoi(pos=0){
        // Cần kiểm tra bài thi đã được chọn?
        //var baithi_choosed = parseInt($('#baithi-choosed').val());

        /*if(baithi_choosed==0){ // Chưa chọn bài thi ===> thông báo warning
            alert('Hãy chọn 1 bài thi để bắt đầu trò chơi bạn nhé!');
        }else{*/
            // Xử lý AJAX : load ds bài thi dựa theo id chủ đề (lấy từ input #topic-choosed)
            var admin_url = jQuery('#admin-url').val();
            jQuery.ajax({
                type : "post", //Phương thức truyền post hoặc get
                dataType : "json", //Dạng dữ liệu trả về xml, json, script, or html
                url : admin_url, //Đường dẫn chứa hàm xử lý dữ liệu. Mặc định của WP như vậy
                data : {
                    action: "loadbocauhoi", //Tên action
                    //id : baithi_choosed,//Biến truyền vào xử lý. $_POST['dateCurrent']
                },
                context: this,
                beforeSend: function(){
                    //Làm gì đó trước khi gửi dữ liệu vào xử lý
                    jQuery("#gameplay-middle").empty();
                    jQuery("#gameplay-middle").append( jQuery('.loading-contain2').html() );                         
                },
                success: function(response) {
                    //Làm gì đó khi dữ liệu đã được xử lý
                    if(response) {
                        jQuery("#gameplay-middle").html( jQuery('.gameplay-box-2').clone() );   
                        jQuery("#gameplay-bottom").html( jQuery('.gameplay-box-3 .quiz-buttons').clone() );                        

                        // XỬ LÝ : CHẠY GAME QUIZ
                        //Tải và thực thi file JS sau khi gọi AJAX thành công
                        window.totalTime = response.data.totalTime;
                        window.questions = response.data.questions;
                        window.idPost = response.data.idPost;
                        window.idCategory = jQuery('#topic-choosed').val(); 

                        var template_url = jQuery('#template-url').val();
                        jQuery.getScript(template_url+"quiz.js")
                            .done(function(script, textStatus) {
                                //console.log('File JS đã được tải và thực thi.');
                                jQuery('.gameplay-close').addClass('in-game');
                            })
                            .fail(function(jqxhr, settings, exception) {
                                //console.error('Không thể tải file JS:', exception);
                        });
                    }
                    else {
                        console.log('Đã có lỗi xảy ra');
                    }
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    //Làm gì đó khi có lỗi xảy ra
                    console.log( 'The following error occured: ' + textStatus, errorThrown );
                }
            })
        //}
    }
</script>

</body>
</html>
