<?php

namespace heyrecruit\Controller;
defined('ABSPATH') || exit;

use Exception;


require_once HEYRECRUIT_PLUGIN_DIR . '/Controller/HeyrecruitRestApiController.php';

class HeyrecruitMainController extends HeyrecruitRestApiController {


    /**
     * @var string
     */
    protected string $defaultLocale;
    /**
     * @var bool
     */
    private static bool $hookRegistered = false;

    /**
     * @var array
     */
    private array $company;

    /**
     * __construct
     * @throws Exception
     */
    public function __construct() {

        parent::__construct();

        $this->defaultLocale = $this->getDefaultLocale();

        add_filter('init', [
            $this,
            'loadTextdomain'
        ]);

        if (!self::$hookRegistered) {
            add_action('init', [
                $this,
                'initialize'
            ]);
            self::$hookRegistered = true;
        }

### universal shortcodes
        add_shortcode('hr_css_files', [
            $this,
            'getCssFiles'
        ]);
        add_shortcode('hr_js_files', [
            $this,
            'getJsFiles'
        ]);
        add_shortcode('hr_company_header', [
            $this,
            'displayCompanyHeader'
        ]);
        add_shortcode('hr_company_description', [
            $this,
            'displayCompanyDescription'
        ]);
        add_shortcode('hr_social_links', [
            $this,
            'displaySocialLinks'
        ]);
### universal shortcodes end
    }

    public function initialize() {

        add_action('wp_head', [
            $this,
            'setGoogleTagManagerToHead'
        ]);

        add_action('wp_footer', [
            $this,
            'setGoogleTagManagerToBody'
        ]);
    }

    /**
     * loadTextdomain
     * @return void
     */
    public function loadTextdomain() {

        $languageFile = HEYRECRUIT_PLUGIN_DIR . '/languages/' . $this->defaultLocale . '.mo';

        if (file_exists($languageFile)) {

            load_textdomain(HEYRECRUIT_OPTION_KEY_NAME, $languageFile);

            load_plugin_textdomain(HEYRECRUIT_OPTION_KEY_NAME,
                false,
                HEYRECRUIT_PLUGIN_DIR . '/languages'
            );
        }
    }

    /**
     * getLanguage
     *
     * @return string
     */
    private function getDefaultLocale(): string {

        return function_exists('determine_locale')
            ? determine_locale()
            : (is_admin() ? get_user_locale() : get_locale());
    }

    /**
     * setGoogleTagManagerToHead
     *
     * @return void
     * @throws Exception
     */
    public function setGoogleTagManagerToHead(): void {

        if ($this->getOption('googleTagManager')) {
            echo $this->getGoogleTagCode()->head;
        }
    }

    /**
     * setGoogleTagManagerToBody
     *
     * @return void
     * @throws Exception
     */
    public function setGoogleTagManagerToBody(): void {

        if ($this->getOption('googleTagManager')) {
            echo $this->getGoogleTagCode()->body;
        }
    }

### universal methoden

    /**
     * getCssFiles
     * @return string
     */
    public function getCssFiles(): string {
        return $this->loadTemplateForShortcode('cssFiles');
    }

    /**
     * getJsFiles
     * @return string
     * @throws Exception
     */
    public function getJsFiles(): string {

        return $this->loadTemplateForShortcode('jsFiles', [
            'heyrecruitGoogleMapsApiKey' => $this->getOption('googleApiKey')
        ]);
    }

    /**
     * displayCompanyHeader
     *
     * @return string
     * @throws Exception
     */
    public function displayCompanyHeader(): string {

        $this->company = $this->company ?? $this->getCompany();

        if (count($this->company) > 0) {

            $overviewPage = $this->company['overview_page'];

            $template = 'companyHeaderDeactivated';

            if (($overviewPage['show_company_infos'] ?? false)) {

                $template = 'companyHeader';

                $args = [
                    'companyName'           => esc_attr(($this->company['name'] ?? '')),
                    'overviewHeaderPicture' => esc_url(($this->company['overview_header_picture'] ?? '')),
                    'title'                 => esc_attr(($overviewPage['overview_page_strings'][0]['title'] ?? '')),
                    'subtitle'              => esc_attr(($overviewPage['overview_page_strings'][0]['subtitle'] ?? '')),
                ];
            }

            return $this->loadTemplateForShortcode($template, $args ?? [
                'overviewHeaderPicture' => false
            ], 'content', false);
        }

        return '';
    }

    /**
     * displayCompanyDescription
     *
     * @return string
     * @throws Exception
     */
    public function displayCompanyDescription(): string {

        $this->company = $this->company ?? $this->getCompany();

        if (count($this->company) > 0) {

            $overviewPage = $this->company['overview_page'];

            $template = 'companyDescriptionDeactivated';

            if (($overviewPage['show_description'] ?? false)) {

                $template = 'companyDescription';

                $args = [
                    'companyDescription' => wp_kses_post(($overviewPage['overview_page_strings'][0]['description'] ?? null)),
                ];
            }

            return $this->loadTemplateForShortcode($template, $args ?? [], 'content', false);
        }

        return '';
    }

    /**
     * displaySocialLinks
     *
     * @return string
     * @throws Exception
     */

    public function displaySocialLinks(): string {

        $this->company = $this->company ?? $this->getCompany();

        if (count($this->company) > 0) {

            $company = $this->company;
            $template = 'companySocialLinksDeactivated';

            if (($this->company['overview_page']['show_social_links']) ?? false) {

                $template = 'companySocialLinks';

                $args = [
                    'facebook'  => esc_url($company['facebook'] ?? ''),
                    'instagram' => esc_url($company['instagram'] ?? ''),
                    'twitter'   => esc_url($company['twitter'] ?? ''),
                    'xing'      => esc_url($company['xing'] ?? ''),
                    'kununu'    => esc_url($company['kununu'] ?? ''),
                    'linkedin'  => esc_url($company['linkedin'] ?? '')
                ];
            }

            return $this->loadTemplateForShortcode($template, $args ?? [], 'content', false);
        }

        return '';
    }
### universal methoden end

    /**
     * getOption
     *
     * @param string $name
     * @param bool|null $default
     *
     * @return object|resource|array|string|float|int|bool|null
     */
    protected function getOption(string $name, bool $default = null) {

        $option = get_option(HEYRECRUIT_OPTION_KEY_NAME . ucfirst($name));

        return empty($option) ? $default :$option;
    }

    /**
     * loadTemplateForShortcode
     * @param string $templateFileName
     * @param array $args
     * @param string $templateDir
     * @param bool $loadOnce
     * @return string
     */
    protected function loadTemplateForShortcode(
        string $templateFileName,
        array  $args = [],
        string $templateDir = 'content',
        bool   $loadOnce = true): string {

        $templateDir = trim($templateDir, '/');

        $templatePath = HEYRECRUIT_PLUGIN_DIR . '/templates/' . $templateDir . '/' . $templateFileName . '.php';

        ob_start();

        if (file_exists($templatePath)) {
            load_template($templatePath, $loadOnce, $args);
        } else {

            load_template(
                HEYRECRUIT_PLUGIN_DIR . '/templates/content/template_not_found.php',
                $loadOnce,
                [
                    'templatePath' => str_replace([
                        ABSPATH,
                        '//'
                    ], [
                        '',
                        '/'
                    ], $templatePath)
                ]
            );
        }

        return ob_get_clean();
    }

    /**
     * jobLocation
     *
     * @param array $job
     * @param int $jobLocationKey
     * @return array
     */
    protected function jobLocation(array $job, int $jobLocationKey = -1): array {

        $formattedAddress = '';
        if ($jobLocationKey === -1) {

            $jobLocation = $job['company_location_jobs'] ?? false;
            $jobLocationKey = 0;
            $locationForOverView = true;

        } else {
            $locationForOverView = false;
            $jobLocation = $job;

        }

        if ($jobLocation) {

            $locationCount = count($jobLocation);

            $firstLocation = ($jobLocation[$jobLocationKey] ?? []);

            if ($locationForOverView) {

                $formattedAddress = $this->getFormattedAddress(
                    $firstLocation, false, true, false, false
                );
            }

            if (empty($formattedAddress)) {
                $formattedAddress = $this->getFormattedAddress($firstLocation);
            }

            $jobLocationId = $firstLocation['company_location']['id'];

            if ($locationForOverView && $locationCount == 2) {

                $formattedAddressTwo = $this->getFormattedAddress(
                    ($jobLocation[1] ?? []), false, true, false, false
                );

                if (empty($formattedAddressTwo)) {
                    $formattedAddressTwo = $this->getFormattedAddress(($jobLocation[1] ?? []));
                }

                $formattedAddress .= ', ' . $formattedAddressTwo;

            } elseif (($locationForOverView && $locationCount > 2) || (!$locationForOverView && $locationCount > 1)) {
                $formattedAddress .= ' ' . sprintf(__('and %s other', HEYRECRUIT_OPTION_KEY_NAME), ($locationCount - 1));
            }
        }

        return [
            'locationIdForUrl' => $jobLocationId ?? null,
            'jobLocationTitle' => esc_attr($formattedAddress)
        ];
    }

    /**
     * getFormattedAddress
     *
     * @param array $companyLocation
     * @param bool $street
     * @param bool $city
     * @param bool $state
     * @param bool $country
     * @param bool $title
     * @return string
     */
    public function getFormattedAddress(
        array $companyLocation = [],
        bool  $street = true,
        bool  $city = true,
        bool  $state = true,
        bool  $country = true,
        bool  $title = true
    ): string {

        $address = '';
        $hasAddress = false;

        if (!empty($companyLocation)) {

            if ($title && !empty($companyLocation['company_location']['title'])) {
                return $companyLocation['company_location']['title'];
            }

            if ($street) {
                $address .= $companyLocation['company_location']['street'] ?? '';
                $address .= ' ' . ($companyLocation['company_location']['street_number'] ?? '');
                $hasAddress = !empty(trim($address));
            }

            if ($city) {
                $address .= $hasAddress ? ', ' : '';
                $address .= $companyLocation['company_location']['city'] ?? '';
                $hasAddress = !empty(trim($address));
            }

            if ($state) {
                $address .= $hasAddress ? ', ' : '';
                $address .= $companyLocation['company_location']['state'] ?? '';
                $hasAddress = !empty(trim($address));
            }

            if ($country) {
                $address .= $hasAddress ? ', ' : '';
                $address .= $companyLocation['company_location']['country'] ?? '';
            }
        }

        return esc_attr(trim($address));
    }

    /**
     * containsSubstring
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function containsSubstring($haystack, $needle): bool {

        return function_exists('str_contains')
            ? str_contains($haystack, $needle)
            : $needle !== '' && strpos($haystack, $needle) !== false;
    }

    /**
     * emptyCheck
     *
     * @param string|null $value
     *
     * @return string|null
     */
    private function emptyCheck(?string $value): ?string {

        return empty($value) ? null : $value;
    }

}