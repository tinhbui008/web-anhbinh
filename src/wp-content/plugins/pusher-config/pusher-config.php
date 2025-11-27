<?php
/*
Plugin Name: Secure Pusher Config
Description: Cấu hình Pusher an toàn với mã hóa AES-256-CBC.
Version: 2.0
Author: Dev Team
*/

if (!defined('ABSPATH')) exit;

/*
|--------------------------------------------------------------------------
|  Tạo khóa mã hóa riêng cho plugin (chỉ tạo 1 lần)
|--------------------------------------------------------------------------
*/
function spc_get_encryption_key() {
    $key = get_option('spc_encryption_key');

    if (!$key) {
        $key = base64_encode(random_bytes(32)); // 256-bit key
        update_option('spc_encryption_key', $key);
    }

    return base64_decode($key);
}

/*
|--------------------------------------------------------------------------
|  HÀM MÃ HÓA / GIẢI MÃ AN TOÀN
|--------------------------------------------------------------------------
*/
function spc_encrypt($plaintext) {
    if (!$plaintext) return '';

    $key = spc_get_encryption_key();
    $iv  = random_bytes(16);

    $cipher = openssl_encrypt(
        $plaintext,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return base64_encode($iv . $cipher);
}

function spc_decrypt($encoded) {
    if (!$encoded) return '';

    $data = base64_decode($encoded);
    $iv   = substr($data, 0, 16);
    $ciphertext = substr($data, 16);

    $key = spc_get_encryption_key();

    return openssl_decrypt(
        $ciphertext,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );
}

/*
|--------------------------------------------------------------------------
|  MENU ADMIN
|--------------------------------------------------------------------------
*/
add_action('admin_menu', function () {
    add_options_page(
        'Secure Pusher Settings',
        'Pusher Settings',
        'manage_options',
        'secure-pusher-settings',
        'spc_settings_page'
    );
});

/*
|--------------------------------------------------------------------------
|  REGISTER SETTINGS + SANITIZE
|--------------------------------------------------------------------------
*/
add_action('admin_init', function () {
    register_setting('spc_group', 'spc_app_id', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);

    register_setting('spc_group', 'spc_app_key', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);

    register_setting('spc_group', 'spc_cluster', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);

    // Secret: chỉ mã hóa nếu người dùng nhập giá trị mới
    register_setting('spc_group', 'spc_app_secret', [
        'sanitize_callback' => function ($value) {
            $old = get_option('spc_app_secret');
            if (empty($value)) return $old; // giữ nguyên secret cũ
            return spc_encrypt($value);
        }
    ]);
});

/*
|--------------------------------------------------------------------------
|  GIAO DIỆN SETTINGS PAGE
|--------------------------------------------------------------------------
*/
function spc_settings_page() { ?>
    <div class="wrap">
        <h1>Secure Pusher Configuration</h1>

        <form method="post" action="options.php">
            <?php settings_fields('spc_group'); ?>

            <table class="form-table">

                <tr>
                    <th scope="row">Pusher App ID</th>
                    <td>
                        <input type="text" name="spc_app_id"
                               value="<?php echo esc_attr(get_option('spc_app_id')); ?>"
                               class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">Pusher App Key</th>
                    <td>
                        <input type="text" name="spc_app_key"
                               value="<?php echo esc_attr(get_option('spc_app_key')); ?>"
                               class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">Pusher App Secret</th>
                    <td>
                        <input type="password" name="spc_app_secret"
                               value="" class="regular-text">
                        <p class="description">
                            Nhập secret mới nếu muốn thay đổi — để trống nếu không thay đổi.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Cluster</th>
                    <td>
                        <input type="text" name="spc_cluster"
                               value="<?php echo esc_attr(get_option('spc_cluster')); ?>"
                               class="regular-text">
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>
        </form>
    </div>
<?php }

/*
|--------------------------------------------------------------------------
|  TRẢ VỀ GIÁ TRỊ GIẢI MÃ ĐỂ DÙNG TRONG CODE
|--------------------------------------------------------------------------
*/
function spc_get_pusher_config() {
    return [
        'app_id'  => get_option('spc_app_id'),
        'key'     => get_option('spc_app_key'),
        'secret'  => spc_decrypt(get_option('spc_app_secret')),
        'cluster' => get_option('spc_cluster'),
    ];
}
