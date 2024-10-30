<?php defined('ABSPATH') || exit;
$args = (object)$args;
?>
<div id="heyrecruit_jobs" data-current-shortcode="<?php echo $args->currentShortcode;
?>" data-department-filter='<?php echo $args->departmentFilter;
?>' data-department-filter-activated='<?php echo $args->departmentFilterActivated;
?>' data-employment-filter='<?php echo $args->employmentFilter;
?>' data-employment-filter-activated='<?php echo $args->employmentFilterActivated;
?>' data-location-filter='<?php echo $args->locationFilter;
?>' data-location-filter-activated='<?php echo $args->locationFilterActivated;
?>' data-address-filter='<?php echo $args->addressFilter;
?>' data-address-filter-activated='<?php echo $args->addressFilterActivated;
?>' data-internal-title-filter='<?php echo $args->internalTitleFilter;
?>' data-internal-title-filter-activated='<?php echo $args->internalTitleFilterActivated; ?>'>
    <style>
        .primary-color, h3 a:where(:not(.wp-element-button)) {
            color: <?php echo esc_attr(HEYRECRUIT_CUSTOM_COLOR);?> !important;
        }

        .primary-background-color {
            background-color: <?php echo esc_attr(HEYRECRUIT_BACKGROUND_COLOR) ?>;
        }

        .primary-color-for-background {
            background-color: <?php echo esc_attr(HEYRECRUIT_CUSTOM_COLOR) ?>;
        }
    </style>
    <?php foreach ($args->jobs as $job): ?>

        <div class="heyrecruit_list_wrapper primary-background-color">
            <div>
                <h3 class="hr-job-title primary-color"><?php echo $job->titleWithJobUrl; ?></h3>
                <?php if ($args->options->jobTableColumnOptions->location): ?>
                    <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Pro 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path
                                    d="M352 192c0-88.4-71.6-160-160-160S32 103.6 32 192c0 15.6 5.4 37 16.6 63.4c10.9 25.9 26.2 54 43.6 82.1c34.1 55.3 74.4 108.2 99.9 140c25.4-31.8 65.8-84.7 99.9-140c17.3-28.1 32.7-56.3 43.6-82.1C346.6 229 352 207.6 352 192zm32 0c0 87.4-117 243-168.3 307.2c-12.3 15.3-35.1 15.3-47.4 0C117 435 0 279.4 0 192C0 86 86 0 192 0S384 86 384 192zm-240 0a48 48 0 1 0 96 0 48 48 0 1 0 -96 0zm48 80a80 80 0 1 1 0-160 80 80 0 1 1 0 160z"/></svg><?php echo esc_attr($job->locationTitle); ?></span>
                <?php endif;
                if ($args->options->jobTableColumnOptions->employment && !empty(esc_attr($job->employment))): ?>
                    <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Pro 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path
                                    d="M480 256A224 224 0 1 1 32 256a224 224 0 1 1 448 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM240 112V256c0 5.3 2.7 10.3 7.1 13.3l96 64c7.4 4.9 17.3 2.9 22.2-4.4s2.9-17.3-4.4-22.2L272 247.4V112c0-8.8-7.2-16-16-16s-16 7.2-16 16z"/></svg><?php echo esc_attr($job->employment); ?></span>
                <?php endif;
                if ($args->options->jobTableColumnOptions->department && !empty(esc_attr($job->department))): ?>
                    <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Pro 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path
                                    d="M207.6 51.6c2-8.6-3.4-17.2-12-19.2s-17.2 3.4-19.2 12l-23 99.6H48c-8.8 0-16 7.2-16 16s7.2 16 16 16h98L109.1 336H16c-8.8 0-16 7.2-16 16s7.2 16 16 16h85.7L80.4 460.4c-2 8.6 3.4 17.2 12 19.2s17.2-3.4 19.2-12l23-99.6H261.7l-21.3 92.4c-2 8.6 3.4 17.2 12 19.2s17.2-3.4 19.2-12l23-99.6H400c8.8 0 16-7.2 16-16s-7.2-16-16-16H302l36.9-160H432c8.8 0 16-7.2 16-16s-7.2-16-16-16H346.3l21.3-92.4c2-8.6-3.4-17.2-12-19.2s-17.2 3.4-19.2 12l-23 99.6H186.3l21.3-92.4zM178.9 176H306L269.1 336H142l36.9-160z"/></svg><?php echo esc_attr($job->department); ?></span>
                <?php endif; ?>
            </div>
            <a class="hr_jobdetail_button primary-color-for-background"
               href="<?php echo esc_url($job->jobDetailPageUrl); ?>"
               target="_blank"><?php echo __('Job details', HEYRECRUIT_OPTION_KEY_NAME); ?></a>
        </div>
    <?php endforeach ?>
    <?php if ($args->options->jobsPagination->hasPages) {
        load_template(HEYRECRUIT_PLUGIN_DIR . '/templates/content/pagination.php', true, [
            'pagination' => $args->options->jobsPagination
        ]);
    } ?>
</div>
