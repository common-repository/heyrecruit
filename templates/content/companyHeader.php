<?php defined('ABSPATH') || exit;?>
<div class="hr_jobs_company_info_wrap">
<!--    ToDo: @Isa alternative wenn overviewHeaderPicture nicht existiert? kann das jetzt auch schon vorher abfangen...-->
    <?php if(!empty($args['overviewHeaderPicture'])): ?>
        <div class="hr_jobs_header_image">
            <img src="<?php echo  $args['overviewHeaderPicture']; ?>" alt="<?php echo  $args['companyName']; ?>"/>
            <div class="hr_jobs_header_image_overlay"></div>
            <div class="hr_jobs_header_image_headlines">
                <h3><?php echo  $args['title']; ?></h3>
                <h2><?php echo  $args['subtitle']; ?></h2>
            </div>
        </div>
    <?php endif; ?>
</div>