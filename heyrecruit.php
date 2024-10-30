<?php
use heyrecruit\Controller\HeyrecruitAdminSettingsController;
use heyrecruit\Controller\HeyrecruitJobsOverviewController;
use heyrecruit\Controller\HeyrecruitJobDetailController;
use heyrecruit\Controller\HeyrecruitPluginController;

defined('ABSPATH') || exit;

if (!session_id()) {
    session_start();
}

/**
 * @since             1.3.6
 * @package           heyrecruit_core
 * @wordpress-plugin
 * Plugin Name:       Heyrecruit
 * Version:           1.3.6
 * Author:            Heyrecruit
 * Author URI:        https://www.heyrecruit.de
 * Text Domain:       heyrecruit
 * Description:       Das offizielle Heyrecruit-Plugin lässt Dich mithilfe von Shortcodes die Karriereseite und Stellenanzeigen Deiner Firma in Deine Wordpress-Seite integrieren.
 */

const HEYRECRUIT_PLUGIN_FILE = __FILE__;

require_once __DIR__ . '/constConfig.php';

if (!function_exists('dd')) {

    function dd(...$debugData) {

        echo '<pre>';

        if (count($debugData) > 0) {
            foreach ($debugData as $data) {
                var_dump($data);
            }
        } else {
            var_dump($debugData);
        }
        echo '</pre>';

        exit;
    }
}
if (!function_exists('dump')) {

    function dump(...$debugData) {

        echo '<pre>';

        if (count($debugData) > 0) {
            foreach ($debugData as $data) {
                var_dump($data);
            }
        } else {
            var_dump($debugData);
        }
        echo '</pre>';

    }
}

spl_autoload_register(function ($class) {
    $prefix = 'heyrecruit\\Controller\\';
    $base_dir = HEYRECRUIT_PLUGIN_DIR . 'Controller/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

new HeyrecruitJobsOverviewController();
new HeyrecruitJobDetailController();

function initialize_admin_controllers() {

    new HeyrecruitAdminSettingsController();
}

if (is_admin()) {
    add_action('admin_menu', 'initialize_admin_controllers', 1);
    HeyrecruitPluginController::getInstance();
}

if (session_id()) {
    session_write_close();
}
