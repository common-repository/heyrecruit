<?php defined('ABSPATH') || exit; ?>
<label for="<?php echo $args['id'] ?? null; ?>">
    <input type="password"
           id="<?php echo $args['id'] ?? null; ?>"
           class="<?php echo $args['class'] ?? null; ?>"
           name="<?php echo $args['name'] ?? null; ?>"
           value="<?php echo $args['value'] ?? null; ?>"
    />
</label>