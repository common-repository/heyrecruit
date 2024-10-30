<?php defined('ABSPATH') || exit; ?>
<div class="hr_checkbox_with_modal_formfield">
    <div class="hr_checkbox_with_modal_formfield_inner-wrapper">
        <input type="checkbox" id="<?php echo $args['id']; ?>" name="<?php echo $args['fieldName']; ?>" value="1"/>
        <label for="<?php echo $args['id']; ?>">
            <?php echo $args['value'] . $args['requiredMark']; ?>
        </label>
    </div>
</div>
<div id="modal<?php echo $args['id']; ?>" class="hr_checkbox_modal" style="display:none">
    <div class="modal-dialog modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $args['title']; ?></h4>
                <a class="closeModal" data-id="<?php echo $args['id']; ?>"> <i class="fal fa-times"
                                                                               aria-hidden="true"></i> </a>
            </div>
            <div class="modal-body">
                <p><?php echo $args['modalBody']; ?></p>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>