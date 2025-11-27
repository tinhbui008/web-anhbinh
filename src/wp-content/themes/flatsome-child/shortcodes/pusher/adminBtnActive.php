<?php
add_action('admin_menu', function () {
    add_menu_page('Quản lý game', 'Quản lý game', 'manage_options', 'active-users', 'render_active_users_page');
});

function render_active_users_page() {
    $user_list = get_option('user_form_list', []);
    $config = get_pusher_config();

    // Kiểm tra trạng thái game
    $is_reset = get_option('is_reset');
    if($is_reset==null){
        $is_reset = false;
        //update_option('idCategory', 0); // khởi tạo khóa idCategory mặc định là 0
    }    
?>
    <div class="adminUserGame-wrap">
        <?php if($is_reset):?>
            <div class="adminUserGame-status-info">- Đang ở trạng thái phòng chờ<br>- Trò chơi sẽ bắt đầu khi quản trị viên nhấn nút 'Bắt đầu trò chơi'<br>- Chỉ có thành viên đang trong phòng chờ mới có thể tham gia trò chơi.</div>
        <?php else:?>
            <div class="adminUserGame-status-info">- ID: <?=date('h:i d/m/y',get_option('idCategory'))?><br>- Đang ở trạng thái chơi game<br>- Thành viên có thể tham gia phòng chờ sau khi quản trị viên nhấn nút 'Khởi tạo trò chơi'<br>- 'Khởi tạo trò chơi' sẽ làm mới lại danh sách thành viên tham gia</div>
        <?php endif;?>

        <div class="adminUserGame-top">
            <h3>Danh sách người chơi: <span id="count_user"><?=count($user_list)?></span></h3>
            <form method="post">
                <?php if (!$is_reset): ?>
                    <button type="submit" name="reset_game" class="<?=(count($user_list)<=0) ? '' : '/reset_game_hidden'?>" value="1">Khởi tạo trò chơi</button>
                    <!-- <p><strong>Đang trong quá trình chơi game</strong></p> -->
                <?php else: ?>
                    <?php if(count($user_list)<=0):?>
                        <button type="submit" name="reset_game" class="/reset_game_hidden" value="1">Khởi tạo trò chơi</button>
                    <?php endif;?>
                    <input type="submit" name="activate_popup" class="<?=(count($user_list)>0) ? '' : 'active_game_hidden'?>" value="Bắt đầu trò chơi">
                <?php endif; ?>            
                <input type="hidden" value="<?= esc_attr($config['key']) ?>" id="pusher_app_key">
                <input type="hidden" value="<?= esc_attr($config['cluster']) ?>" id="pusher_cluster">
            </form>
        </div>

        <ul id="adminUserList">
            <?php foreach ($user_list as $user): ?>
                <li id="user-<?php echo esc_html($user['id']); ?>">
                    <p><img src="<?php echo esc_html($user['avatar']); ?>" width="30" height="30" style="border-radius:50%;"></p>
                    <div><strong><?php echo esc_html($user['name']); ?></strong><em><?php echo esc_html($user['status']); ?></em></div>              
                    <?php /*if(!$game_status):?><button class="remove-user" data-user="<?php echo esc_attr($user['id']); ?>">❌</button><?php endif;*/?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php /*
        <p>Trạng thái game: <?= ($game_status) ? 'true' : 'false' ?></p>*/ ?>
    </div>

    <!-- STYLE -->
    <style>
        #adminUserList{display: flex;gap:16px;width: 100%;flex-wrap:wrap;max-height: 550px; overflow: auto;padding-right: 15px;align-items: start;margin: 0; margin-top: 16px;}
        #adminUserList li{ width: auto; background: #fff; border-radius: 6px; -webkit-border-radius: 6px; -moz-border-radius: 6px; -ms-border-radius: 6px; -o-border-radius: 6px; padding:8px;display: flex;gap:10px;position: relative;min-width: 200px;margin: 0;}
        #adminUserList li p{ overflow: hidden;display: flex ; align-items: center; justify-content: center;margin: 0;}
        #adminUserList li p img{width: 50px;height: 50px;border: 3px solid #fff;}
        #adminUserList li div{display: flex; align-items: center;justify-content: space-between;gap:8px;width:calc(100% - 60px - 10px);}
        #adminUserList li div strong{color: #3D3D3D; font-size: 16px;text-transform: capitalize; font-weight: 500;}
        #adminUserList li div em{color: #00D190; font-size: 13px;display: none;}
        #adminUserList .remove-user{position: absolute; top: 12px; right: 12px;background: #fff; border: none; display: inline-flex ; align-items: center; justify-content: center; border-radius: 999px; width: 26px; height: 26px; cursor: pointer;}
        input[name="activate_popup"], button[name="reset_game"]{border: none; height: 38px; padding: 0 15px; color: #fff; border-radius: 999px;cursor: pointer;}
        input[name="activate_popup"]{background:#ED1C24 !important;}
        button[name="reset_game"]{background:#005BAA !important;}
        .adminUserGame-wrap{margin: 20px 15px 2px;}
        .adminUserGame-top{display: flex;align-items:center;justify-content:space-between;padding: 10px 15px;border-radius:6px;background:#fff;margin-bottom:5px;}

        .reset_game_hidden{display: none;}
        .adminUserGame-status-info{font-size:15px;margin-bottom:20px;}

        .active_game_hidden{display: none;}
    </style>

    <!-- THƯ VIỆN PUSHER -->
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // lấy pusher và add item
            var pusher_app_key = jQuery('#pusher_app_key').val();
            var pusher_cluster = jQuery('#pusher_cluster').val();

            const pusher = new Pusher(pusher_app_key, {
                cluster: pusher_cluster
            });

            //### Lắng nghe sự kiện từ pusher : sau khi người dùng submit phòng chờ
            const channel = pusher.subscribe('user-channel');
            channel.bind('user-added', function(data) {
                //updateUserList(data);
                addOtherUserToList(data);
            });

            // ### Lắng nghe sự kiện từ ADMIN khi admin xóa 1 người chơi
            channel.bind('user-removed', function(data) {
                //updateUserList(data);
                removeUserFromList(data);
            });
        });


        //### Phương thức : Xóa 1 người chơi --> cập nhật ds trong phòng chờ
        function removeUserFromList(data){            
            const userID_removed = data.userID;

            if (jQuery('#user-'+userID_removed).length) {
                jQuery('#adminUserList').find('#user-'+userID_removed).remove();
            }

            // Hiển thị số lượng người chơi
            jQuery('#count_user').text(data.count);

            //Hiển thị nút reset game
            checkCount(data.count);
            // if(data.count<=0){
            //     jQuery('button[name="reset_game"]').removeClass('reset_game_hidden');
            // }
        }


        //### Phương thức : Cập nhật thông tin 1 người chơi mới tham gia phòng chờ --> cập nhật ds trong phòng chờ
        function addOtherUserToList(data){
            const list = document.getElementById('adminUserList');

            const userID = data.userID;
            const user = data.user;

            if (!jQuery('#user-'+userID).length && data.users[userID]) {
                const li = document.createElement('li');
                li.id = `user-${userID}`;
                li.innerHTML = `
                    <p><img src="${user.avatar}" width="30" height="30" style="border-radius:50%;"></p>
                    <div><strong>${user.name}</strong><em>${user.status}</em></div>
                `;
                //<button class="remove-user" data-user="${user.id}">❌</button>
                list.appendChild(li);
            }

            // Hiển thị số lượng người chơi
            jQuery('#count_user').text(data.count);

            //Hiển thị nút reset game
            checkCount(data.count);
        }


        // Kiểm tra số lượng người tham gia để hiển thị nút
        function checkCount(count=0){
            if(count>0){
                jQuery('input[name="activate_popup"]').removeClass('active_game_hidden');
                jQuery('input[name="reset_game"]').addClass('reset_game_hidden');
            }else{
                jQuery('input[name="activate_popup"]').addClass('active_game_hidden');
                jQuery('input[name="reset_game"]').removeClass('reset_game_hidden');
            }
        }


        //## Cập nhật ds người chơi
        function updateUserList(data){
            const list = document.getElementById('adminUserList');
            list.innerHTML = '';

            Object.entries(data.users).forEach(([code, user]) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <p><img src="${user.avatar}" width="30" height="30" style="border-radius:50%;"></p>
                    <div><strong>${user.name}</strong><em>${user.status}</em></div>                    
                `;
                // <button class="remove-user" data-user="${user.id}">❌</button>
                list.appendChild(li);
            });

            // Hiển thị số lượng người chơi
            jQuery('#count_user').text(data.count);
        }

        // Xoá user khi admin click nút X
        jQuery('body').on('click', '.remove-user', function(e) {
            const user = jQuery(this).attr('data-user');
           
            if (!confirm('Bạn có chắc muốn xoá người dùng ' + user + '?')) return;

            fetch(ajaxurl, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=remove_user_code&user_code=' + encodeURIComponent(user)
            })
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('adminUserList');
                list.innerHTML = '';

                Object.entries(data.users).forEach(([code, user]) => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <p><img src="${user.avatar}" width="30" height="30" style="border-radius:50%;"></p>
                        <div><strong>${user.name}</strong><em>${user.status}</em></div>
                        <button class="remove-user" data-user="${user.id}">❌</button>
                    `;
                    list.appendChild(li);
                });
            });
        });
    </script>

    <?php
    //### Cập nhật data lên pusher sau khi admin active game
    if (isset($_POST['activate_popup'])) {
        update_option('is_reset', false); // khi admin 'bắt đầu trò chơi' ---> tắt trạng thái reset
        update_option('is_game_active', true); // khi admin 'bắt đầu trò chơi' ---> bật trạng thái onGame
        update_option('idCategory', current_time('timestamp')); // khi admin 'bắt đầu trò chơi' ---> cập nhật thông số cho idCategory - mục đích để lưu lịch sử chơi game của các thành viên trong cùng 1 thời điểm chơi

        $user_list = get_option('user_form_list', []);

        $pusher = get_pusher_instance();
        $pusher->trigger('user-channel', 'admin-activate', ['users' => $user_list]);

        echo '<script>location.reload();</script>';
        exit;
        //echo '<div class="notice notice-success"><p>Popup đã được kích hoạt!</p></div>';
    }


    //### Cập nhật data null khi admin reset game
    if (isset($_POST['reset_game'])) {
        update_option('user_form_list', []);
        update_option('is_reset', true); // khi admin 'khởi tạo trò chơi' ---> bật trạng thái reset
        update_option('is_game_active', false); // khi admin 'khởi tạo trò chơi' ---> tắt trạng thái onGame - người chơi sẽ CHƯA thể chơi game cho tới khi admin 'bắt đầu trò chơi'
        update_option('idCategory', 0); // khi admin 'khởi tạo trò chơi' ---> khởi tạo khóa idCategory mặc định là 0

        $pusher = get_pusher_instance();
        $pusher->trigger('user-channel', 'admin-reset', ['users' => []]);

        echo '
            <script>location.reload();</script>
            <script>
                jQuery(".remove-user").remove();
            </script>        
        ';
        exit;
       // echo '<div class="updated"><p>Đã reset danh sách người dùng.</p></div>';
    }
}
