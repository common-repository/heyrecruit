<?php defined('ABSPATH') || exit; ?>
<fieldset class="hr_checkbox_formfield">
    <legend><?php echo $args['title'] . $args['requiredMark']; ?></legend>
    <?php
    foreach ($args['value'] as $key => $value) : ?>
        <div>
            <input type="checkbox" id="<?php echo $args['id'].'_'.$key; ?>" name="<?php echo $args['fieldName']; ?>"
                   value="<?php echo $value; ?>">
            <label for="<?php echo $args['id'].'_'.$key; ?>"><?php echo $value; ?></label>
        </div>
    <?php endforeach; ?>
</fieldset>