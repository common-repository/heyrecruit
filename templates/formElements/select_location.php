<?php defined('ABSPATH') || exit; ?>
<div id="change_location_wrap">
    <label for="changeLocation">
        <?php echo __('Choose location', HEYRECRUIT_OPTION_KEY_NAME); ?>
    </label>
    <select id="changeLocation" name="location">
        <?php
        foreach ($args['selectLocationOptions'] as $option) {
            echo $option;
        }
        ?>
    </select>
</div>