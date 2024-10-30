<?php defined('ABSPATH') || exit; ?>
<div class="hr_section_form_content">
    <script type="text/javascript">let confirmPage = '<?php echo $args['confirmPageUrl'];?>'</script>
    <form id="hrJobApplication" class="hr_form">
        <input type="hidden" name="job_id" value="<?php echo $args['jobId']; ?>" id="jobId"/>
        <input type="hidden" name="company_location_id" value="<?php echo $args['jobLocationId']; ?>" id="locationId"/>
        <?php foreach ($args['formSections'] as $formSection) {
            echo $formSection['formSectionTitle'];

            foreach ( $formSection['formSectionElements'] as $formSectionElement) {
                echo $formSectionElement;
            }
        }
        ?>
        <input type="button" id="saveApplicant" value="<?php echo __('Send', HEYRECRUIT_OPTION_KEY_NAME); ?>"/>
    </form>
</div>