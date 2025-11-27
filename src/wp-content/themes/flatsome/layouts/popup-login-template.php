<?php
// Đảm bảo file chỉ được chạy trong môi trường WordPress
defined('ABSPATH') || exit;
?>

<!-- Popup Login Form -->
<div id="login-popup" class="login-popup">
  <div class="popup-content">
    <span class="close-popup">&times;</span>
    <form id="custom-login-form" method="post">
      <div class="custom-login-left"><img src="<?=IMG?>/images/gameplay_logo2.png" alt=""></div>
      <div class="custom-login-right">
        <h3>Đăng nhập</h3>
        <div class="custom-login-input">
          <input type="text" name="username" placeholder="Tên đăng nhập" required>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <circle cx="12" cy="9" r="3" stroke="#888" stroke-width="1.5"/> <circle cx="12" cy="12" r="10" stroke="#888" stroke-width="1.5"/> <path d="M17.9692 20C17.8101 17.1085 16.9248 15 12 15C7.07527 15 6.18997 17.1085 6.03082 20" stroke="#888" stroke-width="1.5" stroke-linecap="round"/> </svg>
        </div>
        <div class="custom-login-input">
          <input type="password" name="password" placeholder="Mật khẩu" required>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.75736 10 5.17157 10 8 10H16C18.8284 10 20.2426 10 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16Z" stroke="#888" stroke-width="1.5"/> <circle cx="12" cy="16" r="2" stroke="#888" stroke-width="1.5"/> <path d="M6 10V8C6 4.68629 8.68629 2 12 2C15.3137 2 18 4.68629 18 8V10" stroke="#888" stroke-width="1.5" stroke-linecap="round"/> </svg>
        </div>
        <button type="submit">Đăng nhập</button>
        <div id="login-message"></div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('custom-login-btn').addEventListener('click', function () {
    document.getElementById('login-popup').style.display = 'block';
  });

  document.querySelector('.close-popup').addEventListener('click', function () {
    document.getElementById('login-popup').style.display = 'none';
  });

  document.getElementById('custom-login-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('action', 'custom_login');
    // Hiện loading trước khi hiện thông tin điểm số
      document.getElementById('login-message').insertAdjacentHTML('beforeend', document.querySelector('.loading-contain').innerHTML);

    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
      method: 'POST',
      body: formData,
    })
    .then(res => res.text())
    .then(response => {
      document.getElementById('login-message').innerHTML = response;

      if (response.includes('thành công')) {
        // Lấy avatar qua AJAX sau đăng nhập thành công
        fetch('<?php echo admin_url("admin-ajax.php"); ?>?action=get_user_avatar')
          .then(res => res.text())
          .then(avatarHTML => {
            // ajax thành công ---> xóa loading
            var element = document.querySelector('.loading-svg');              
            if (element && element.closest('#login-message')) {
              element.remove();
            }
            document.getElementById('custom-login-btn').outerHTML = avatarHTML;            
            document.getElementById('login-popup').style.display = 'none';
            location.reload();
          });
      }
    });
  });
});
</script>

<script>
  document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('custom-login-view');
    if (wrapper && wrapper.contains(e.target)) {
      const menu = wrapper.querySelector('.dropdown-menu');
      if (menu) menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    } else {
      const menu = document.querySelector('.dropdown-menu');
      if (menu) menu.style.display = 'none';
    }
  });
</script>