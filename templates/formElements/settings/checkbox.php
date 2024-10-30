<?php defined('ABSPATH') || exit; ?>
    <label for="<?php echo $args['id'] ?? null; ?>">
        <input type="checkbox"
               id="<?php echo $args['id'] ?? null; ?>"
               class="<?php echo $args['class'] ?? null; ?>"
               name="<?php echo $args['name'] ?? null; ?>"
               value="1"
            <?php if (($args['value'] ?? null) == 1): ?> checked<?php endif ?>
        />
    </label>
    <!--ToDo: @Isa mach mich hübsch-->
<?php if (!empty($args['infoText'])): ?>
    <div class="info"><?php echo $args['infoText']; ?></div>
<?php endif; ?>
<?php if (!empty($args['errorMessage'])): ?>
    <div class="error-message"><?php echo $args['errorMessage']; ?></div>
<?php endif; ?>