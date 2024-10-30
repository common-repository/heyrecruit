<?php defined('ABSPATH') || exit;

wp_localize_script('hr_map_js', 'args', [
    'job_data'          => $args['jobsAsJson'],
    'marker_color' => esc_attr(HEYRECRUIT_CUSTOM_COLOR),
]);
?>
<div id="jobsInGoogleMap" class="hr_jobs_google_maps"></div>
<!--ToDo: @Isa bitte auslagern-->
<style>
    .hr-google-map-marker-info-window-content {
        max-height: 300px;
        overflow-y: auto;
        max-width: 300px;
    }

    .hr-google-map-marker-info-window-address-info {
        padding: 10px;
    }

    .hr-google-map-marker-info-window-job-info {
        padding: 10px;
    }

    .hr-google-map-marker-info-window-job-info h4 {
        margin: 0;
        font-size: 16px;
    }

    .hr-google-map-marker-info-window-address-info p,
    .hr-google-map-marker-info-window-job-info p {
        margin: 5px 0 0;
        font-size: 14px;
    }
</style>