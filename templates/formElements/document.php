<?php defined('ABSPATH') || exit; ?>
<div class="hr_document_formfield">
    <label for="<?php echo $args['id']; ?>"
           class="hrUploadFormText"><?php echo $args['title'] . $args['requiredMark']; ?></label>
    <select class="select_file_upload" id="<?php echo $args['id']; ?>"
            name="<?php echo $args['fieldName']; ?>">
        <option value=""><?php echo __('Select'); ?></option>
        <?php
        foreach ($args['value'] as $text) {
            switch ($text) {
                case'Bewerbungsbild':
                    $value = 'picture';
                    break;
                case 'Anschreiben':
                    $value = 'covering_letter';
                    break;
                case 'Lebenslauf':
                    $value = 'cv';
                    break;
                case 'Zeugnis/Bescheinigung':
                    $value = 'certificate';
                    break;
                case 'Sonstiges':
                    $value = 'other';
                    break;
                default:
                    $value = 'other';
            }
            $text = esc_attr($text);
            ?>
            <option value="<?php echo $value . '_' . $args['id']; ?>"
                    data-name="<?php echo $args['fieldName']; ?>"
                    data-field-name="<?php echo $text; ?>"
                    data-form-id="<?php echo $args['id']; ?>"
                    data-value="<?php echo $value; ?>"><?php echo $text; ?>
            </option>
        <?php } ?>
    </select>
    <div id="upload_fields_<?php echo $args['id']; ?>"></div>
</div>
