<?php defined('ABSPATH') || exit; ?>
<div class="hr_select_formfield">
    <label for="<?php echo $args['id']; ?>">
        <?php echo $args['title'] . $args['requiredMark']; ?>
    </label>
    <select id="<?php echo $args['id']; ?>" name="<?php echo $args['fieldName']; ?>">
        <option value=""><?php echo __('Select'); ?></option>
        <?php foreach ($args['value'] as $value): ?>
            <option value="<?php echo esc_attr($value); ?>"><?php echo esc_attr($value); ?></option>
        <?php endforeach; ?>
    </select>
</div>