<?php defined('ABSPATH') || exit; ?>
<div class="hr_job_header">
    <?php if ($args['jobHeaderImage']): ?>
        <div class="hr_job_header_image">
            <img src="<?php echo $args['jobHeaderImage']; ?>" alt="Header">
            <div class="hr_job_header_image_overlay"></div>
            <div class="hr_job_header_image_headlines">
                <h3><?php echo $args['jobSubTitle']; ?></h3>
                <h2><?php echo $args['jobTitle']; ?></h2>
            </div>
        </div>
    <?php else: ?>
        <div class="hr_job_header_only_headlines">
            <h3><?php echo $args['jobSubTitle']; ?></h3>
            <h2><?php echo $args['jobTitle']; ?></h2>
        </div>
    <?php endif; ?>
</div>