<?php defined('ABSPATH') || exit; ?>
<div class="hr_jobs_filters">
    <?php if ($args['showDepartmentList']): ?>
        <label for="department">
            <select id="department" name="department">
                <option value="all"><?= __('All departments', HEYRECRUIT_OPTION_KEY_NAME) ?></option>
                <?php foreach ($args['departmentList'] as $department) : ?>
                    <option value="<?php echo esc_attr($department); ?>">
                        <?php echo esc_attr($department); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    <?php endif; ?>
    <?php if ($args['showEmploymentList']): ?>
        <label for="employment">
            <select id="employment" name="employment">
                <option value="all"><?php echo __('All employment types', HEYRECRUIT_OPTION_KEY_NAME); ?></option>
                <?php foreach ($args['employmentList'] as $key => $employment) : ?>
                    <option value="<?php echo esc_attr($key); ?>">
                        <?php echo esc_attr($employment); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    <?php endif; ?>
    <?php if ($args['showLocationList']): ?>
        <label for="location">
            <select id="location" name="location">
                <option value="all"><?php echo __('All locations', HEYRECRUIT_OPTION_KEY_NAME); ?></option>
                <?php foreach ($args['locationList'] as $key => $location) : ?>
                    <option value="<?php echo esc_attr($key); ?>">
                        <?php echo esc_attr($location); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    <?php elseif ($args['showLocationSearch']): ?>
        <label for="address">
            <input type="text" id="address" name="address"
                   placeholder="<?php echo __('Location', HEYRECRUIT_OPTION_KEY_NAME); ?>"/>
        </label>
    <?php endif; ?>
    <input type="button" id="hrSendJobFilter" class="hr_search_button" value="<?php echo __('Search'); ?>"/>
</div>