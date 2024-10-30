<?php defined('ABSPATH') || exit; ?>
<div class="wrap">
    <?php
    if ($args['error']): ?>
        <span class="error"><?php echo __('Error: Incorrect access data', HEYRECRUIT_OPTION_KEY_NAME); ?></span>
    <?php endif; ?>
    <form method="POST" action="options.php" autocomplete="off" id="<?php echo $args['settingId']?>>">
        <?php
        wp_nonce_field($args['settingId'], 'heyrecruit_nonce');
        do_settings_sections($args['settingId']);
        settings_fields($args['settingId']);
        submit_button();
        ?>
    </form>
</div>
