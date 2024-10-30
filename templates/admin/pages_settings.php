<?php defined('ABSPATH') || exit; ?>
<div class="wrap">
    <form method="POST" action="options.php" autocomplete="off" id="<?php echo $args['settingId'] ?>>">
        <?php
        wp_nonce_field($args['settingId'], 'heyrecruit_nonce');
        do_settings_sections($args['settingId']);
        settings_fields($args['settingId']);
        submit_button();
        submit_button(__('Generate new pages', HEYRECRUIT_OPTION_KEY_NAME ), 'warning', 'reset_page_ids', true, ['id' => 'reset_page_ids']);
        ?>
    </form>
</div>