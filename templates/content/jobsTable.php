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
    <table>
        <thead>
        <tr>
            <th><?php echo __('Job', HEYRECRUIT_OPTION_KEY_NAME); ?></th>
            <?php if ($args->options->jobTableColumnOptions->department): ?>
                <th><?php echo __('Department', HEYRECRUIT_OPTION_KEY_NAME); ?></th>
            <?php endif;
            if ($args->options->jobTableColumnOptions->employment): ?>
                <th><?php echo __('Type of employment', HEYRECRUIT_OPTION_KEY_NAME); ?></th>
            <?php endif;
            if ($args->options->jobTableColumnOptions->location): ?>
                <th><?php echo __('Location', HEYRECRUIT_OPTION_KEY_NAME); ?></th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($args->jobs as $job): ?>
            <tr>
                <td><?php echo $job->titleWithJobUrl; ?></td>
                <?php if ($args->options->jobTableColumnOptions->department): ?>
                    <td><?php echo esc_attr($job->department); ?></td>
                <?php endif;
                if ($args->options->jobTableColumnOptions->employment): ?>
                    <td><?php echo esc_attr($job->employment); ?></td>
                <?php endif;
                if ($args->options->jobTableColumnOptions->location): ?>
                    <td><?php echo esc_attr($job->locationTitle); ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <?php if ($args->options->jobsPagination->hasPages) {
        load_template(HEYRECRUIT_PLUGIN_DIR . '/templates/content/pagination.php', true, [
            'pagination' => $args->options->jobsPagination
        ]);
    } ?>
</div>
