<?php defined('ABSPATH') || exit; ?>
<div class="hr_section_<?php echo $args['sectionType']; ?>">
    <?php foreach ($args['sections'] as $section) {
        echo $section;
    }
    ?>
</div>
