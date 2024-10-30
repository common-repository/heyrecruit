<?php defined('ABSPATH') || exit; ?>
    <label for="<?php echo $args['id'] ?? null; ?>">
        <input type="text"
               id="<?php echo $args['id'] ?? null; ?>"
               class="<?php echo $args['class'] ?? null; ?>"
               name="<?php echo $args['name'] ?? null; ?>"
               value="<?php echo $args['value'] ?? null; ?>"
               placeholder="<?php echo $args['placeholder'] ?? null; ?>"
        />
    </label>
    <!--ToDo: @Isa mach mich hübsch-->
<?php if (!empty($args['infoText'])): ?>
    <div class="info"><?php echo $args['infoText']; ?></div>
<?php endif;?>
<?php if (!empty($args['errorMessage'])): ?>
<div class="error-message"><?php echo $args['errorMessage']; ?></div>
<?php endif;?>