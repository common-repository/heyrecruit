<?php defined('ABSPATH') || exit;

function hr_jquery_js(): void {
    if (!wp_script_is('jquery', 'enqueued')) {
        wp_enqueue_script(
            'hr_jquery_js',
            HEYRECRUIT_PLUGIN_URL . 'js/' . 'jquery-3.7.0.min.js',
            [],
            HEYRECRUIT_DEBUG_MODUS ? time() : esc_attr(HEYRECRUIT_VERSION),
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'hr_jquery_js');

wp_enqueue_script(
    'hr_main_js',
    HEYRECRUIT_PLUGIN_URL . 'js/' . (HEYRECRUIT_DEBUG_MODUS ? 'main.js' : 'main.min.js'),
    ['jquery'],
    HEYRECRUIT_DEBUG_MODUS ? time() : esc_attr(HEYRECRUIT_VERSION)
);

$googleMapsApiUrl = add_query_arg([
    'loading'   => 'async',
    'callback'  => 'initJobsInGoogleMap',
    'libraries' => 'marker',
    'key'       => $args['heyrecruitGoogleMapsApiKey'],
], 'https://maps.googleapis.com/maps/api/js');

wp_enqueue_script(
    'hr_googleapi_js',
    $googleMapsApiUrl,
    [],
    HEYRECRUIT_DEBUG_MODUS ? time() : esc_attr(HEYRECRUIT_VERSION),
    true
);

wp_enqueue_script(
    'hr_map_js',
    HEYRECRUIT_PLUGIN_URL . 'js/' . HEYRECRUIT_OPTION_KEY_NAME . '-map.js',
    ['hr_googleapi_js'],
    HEYRECRUIT_DEBUG_MODUS ? time() : esc_attr(HEYRECRUIT_VERSION),
    true
);

wp_localize_script('hr_main_js', 'args', [
    'hr_ajax_url'          => admin_url('admin-ajax.php'),
    'hr_loading_info_text' => esc_attr(__('loading', HEYRECRUIT_OPTION_KEY_NAME)) . '...',
]);
