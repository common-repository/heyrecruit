<?php
use heyrecruit\Controller\HeyrecruitPluginController;

defined('WP_UNINSTALL_PLUGIN') || exit;

require_once __DIR__ . '/heyrecruit.php';

if (is_admin()) {
    HeyrecruitPluginController::uninstall();
}