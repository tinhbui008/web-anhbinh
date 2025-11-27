<?php
// functions.php
/*add_shortcode('user_code_form', 'user_code_form_shortcode');
function user_code_form_shortcode() {
    ob_start();
?>

    <form id="userCodeForm" class="gameplay-box-1-topics">
        <h2>Điền mã PIN</h2>
        <input type="text" name="user_code" placeholder="Ex: 123456">
        <!-- <button type="submit">Gửi</button> -->
        <input type="hidden" value="<?=PUSHER_APP_KEY?>" id="pusher_app_key">
        <input type="hidden" value="<?=CLUSTER?>" id="pusher_cluster">
    </form>
    <ul id="userList" class="scrollBar-cs"></ul>

<?php
    return ob_get_clean();
}*/

add_shortcode('user_code_form', 'user_code_form_shortcode');
function user_code_form_shortcode() {
    $config = get_pusher_config(); // Lấy config mới nhất

    ob_start();
    ?>

    <form id="userCodeForm" class="gameplay-box-1-topics">
        <h2>Điền mã PIN</h2>
        <input type="text" name="user_code" placeholder="Ex: 123456">
        <input type="hidden" value="<?= esc_attr($config['key']) ?>" id="pusher_app_key">
        <input type="hidden" value="<?= esc_attr($config['cluster']) ?>" id="pusher_cluster">
    </form>
    <ul id="userList" class="scrollBar-cs"></ul>

    <?php
    return ob_get_clean();
}