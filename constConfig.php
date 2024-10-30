<?php defined('ABSPATH') || exit;

const
HEYRECRUIT_VERSION = '1.3.6',
HEYRECRUIT_OPTION_KEY_NAME = 'heyrecruit',
HEYRECRUIT_DEBUG_MODUS = false,
HEYRECRUIT_URL = 'https://app.heyrecruit.de/api/v2/';

$heyrecruitCustomColor = get_option(HEYRECRUIT_OPTION_KEY_NAME . 'CustomColor');
$heyrecruitBackgroundColor = get_option(HEYRECRUIT_OPTION_KEY_NAME . 'BackgroundColor');

define('HEYRECRUIT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HEYRECRUIT_PLUGIN_URL', plugins_url('/', __FILE__));

define('HEYRECRUIT_COMPANY_ID', get_option(HEYRECRUIT_OPTION_KEY_NAME . 'CompanyId'));
define('HEYRECRUIT_API_SECRET_KEY', get_option(HEYRECRUIT_OPTION_KEY_NAME . 'SecretKey'));

define('HEYRECRUIT_CUSTOM_COLOR', empty($heyrecruitCustomColor) ? '#1da9d9' : $heyrecruitCustomColor);
define('HEYRECRUIT_BACKGROUND_COLOR', empty($heyrecruitBackgroundColor) ? '#f7f7f7' : $heyrecruitBackgroundColor);
