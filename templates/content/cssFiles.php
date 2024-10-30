<?php defined('ABSPATH') || exit;

wp_enqueue_style(
    'hr_main_css',
    HEYRECRUIT_PLUGIN_URL . 'css/' . (HEYRECRUIT_DEBUG_MODUS ? 'main.css' : 'main.min.css'),
    [],
    HEYRECRUIT_DEBUG_MODUS ? time() : esc_attr(HEYRECRUIT_VERSION)
);
