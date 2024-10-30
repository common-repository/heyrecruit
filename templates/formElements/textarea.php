<?php defined('ABSPATH') || exit; ?>
<div class="hr_textarea_formfield">
    <label for="<?php echo $args['id']; ?>">
        <?php echo $args['title'] . $args['requiredMark']; ?>
    </label>
    <textarea id="<?php echo $args['id']; ?>"
              name="<?php echo $args['fieldName']; ?>"
              placeholder="<?php echo $args['placeholder']; ?>"></textarea>
</div>