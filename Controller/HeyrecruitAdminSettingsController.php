<?php

namespace heyrecruit\Controller;


use Exception;

class HeyrecruitAdminSettingsController extends HeyrecruitAdminController {


    /**
     * @var string
     */
    private string $secretKeyDefaultValue = 'SecretKey';

    public function __construct() {

        parent::__construct();

        add_action('admin_menu', [
            $this,
            'addMenuPage'
        ]);

        add_action('admin_init', [
            $this,
            'initializeSettings'
        ]);

        add_action('admin_enqueue_scripts', [
            $this,
            'enqueueScripts'
        ]);

        add_action('admin_enqueue_scripts', [
            $this,
            'enqueueStyles'
        ]);

        add_action('pre_update_option_' . HEYRECRUIT_OPTION_KEY_NAME . 'CompanyId', [
            $this,
            'preUpdateCompanyIdOrSecretKey'
        ], 10, 3);

        add_action('pre_update_option_' . HEYRECRUIT_OPTION_KEY_NAME . 'SecretKey', [
            $this,
            'preUpdateCompanyIdOrSecretKey'
        ], 10, 3);

        add_action('update_option_' . HEYRECRUIT_OPTION_KEY_NAME . 'ConfirmResetPageIds', [
            $this,
            'handleResetPageIdsOptions'
        ], 10, 2);


        //work only up to v. 6.4.0
        if (function_exists('wp_set_options_autoload'))
            wp_set_options_autoload(
                array_map(fn($optionId) => HEYRECRUIT_OPTION_KEY_NAME . $optionId, $this->defaultOptions), 'no'
            );

//        add_filter('after_update_option_' . HEYRECRUIT_OPTION_KEY_NAME . 'CompanyId', [
//            $this,
//            'afterUpdateOption'
//        ], 10, 3);
    }

    /**
     * addMenuPage
     * @return void
     */
    public function addMenuPage(): void {

        add_menu_page(
            'Heyrecruit',
            'Heyrecruit',
            'manage_options',
            HEYRECRUIT_OPTION_KEY_NAME . '-main-settings',
            [
                $this,
                'mainSettings'
            ],
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9Ii0yIC00IDIyIDIyIj48cGF0aCBmaWxsPSIjOWNhMmE3IiBkPSJtMTYuNzYsMEgxLjI0Qy41NiwwLDAsLjU2LDAsMS4yNHYxMS43NGMwLC42OC41NiwxLjI0LDEuMjQsMS4yNGg4LjIxbDMuNjgsMy40Yy4zNi4zMy45NS4wNy45NS0uNDJ2LTIuOThoMi42OGMuNjgsMCwxLjI0LS41NiwxLjI0LTEuMjRWMS4yNGMwLS42OC0uNTYtMS4yNC0xLjI0LTEuMjRabS0xLjcyLDYuNThjLTEuMDYsMi4zOS0zLjQ0LDMuOTMtNi4wNCwzLjkzcy00Ljk5LTEuNTUtNi4wNC0zLjkyYy0uMjMtLjUzLDAtMS4xNi41My0xLjQuNTMtLjIzLDEuMTYsMCwxLjQuNTMuNzMsMS42MiwyLjM0LDIuNjcsNC4xMSwyLjY3czMuMzgtMS4wNiw0LjExLTIuNjhjLjIzLS41My44Ni0uNzcsMS40LS41M3MuNzcuODYuNTMsMS40WiIvPjwvc3ZnPg'
        );

        add_submenu_page(
            HEYRECRUIT_OPTION_KEY_NAME . '-main-settings',
            'General Settings',
            __('General'),
            'manage_options',
            HEYRECRUIT_OPTION_KEY_NAME . '-main-settings',
            [
                $this,
                'mainSettings'
            ]
        );

        add_submenu_page(
            HEYRECRUIT_OPTION_KEY_NAME . '-main-settings',
            'Pages',
            __('Page Settings', HEYRECRUIT_OPTION_KEY_NAME),
            'manage_options',
            HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings',
            [
                $this,
                'pagesSettings'
            ]
        );

        add_submenu_page(
            HEYRECRUIT_OPTION_KEY_NAME . '-main-settings',
            'User Options',
            __('User Options', HEYRECRUIT_OPTION_KEY_NAME),
            'manage_options',
            HEYRECRUIT_OPTION_KEY_NAME . '-other-settings',
            [
                $this,
                'otherSettings'
            ]
        );
    }

    /**
     * initializeSettings
     *
     * @return void
     */
    public function initializeSettings() {

        $this->setDefaultOptions();

        # main
        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-main-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'CompanyId'
        );

        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-main-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'SecretKey'
        );

        #pages
        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'JobsPageId'
        );

        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'JobDetailPageId'
        );

        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'ConfirmPage'
        );

        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'ConfirmPageId'
        );

        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'ConfirmResetPageIds'
        );

        #other
        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-other-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'CustomColor'
        );

        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-other-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'BackgroundColor'
        );

        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-other-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'GoogleApiKey'
        );

        register_setting(
            HEYRECRUIT_OPTION_KEY_NAME . '-other-settings',
            HEYRECRUIT_OPTION_KEY_NAME . 'GoogleTagManager'
        );
    }

    /**
     * mainSettings
     * @return void
     * @throws Exception
     */
    public function mainSettings(): void {

        $settingId = HEYRECRUIT_OPTION_KEY_NAME . '-main-settings';

        add_settings_section(
            $settingId,
            __('General Settings', HEYRECRUIT_OPTION_KEY_NAME),
            '__return_false',
            $settingId,
        );

        $companyId = HEYRECRUIT_OPTION_KEY_NAME . 'CompanyId';
        $companyIdValue = $this->getOption('CompanyId');

        add_settings_field(
            $companyId,
            __('Your Heyrecruit client ID (CLIENT-ID)', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'  => 'text',
                'name'  => $companyId,
                'id'    => $companyId,
                'value' => $companyIdValue
            ]
        );

        $secretKey = HEYRECRUIT_OPTION_KEY_NAME . 'SecretKey';

        add_settings_field(
            $secretKey,
            __('Your Heyrecruit secret key (CLIENT-SECRET)', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'  => 'password',
                'name'  => $secretKey,
                'id'    => $secretKey,
                'value' => $this->secretKeyDefaultValue
            ]
        );

        load_template(
            HEYRECRUIT_PLUGIN_DIR . '/templates/admin/main_settings.php',
            true,
            [
                'error'     => !HeyrecruitRestApiController::checkAuthenticationData(),
                'settingId' => $settingId
            ]
        );
    }

    /**
     * pagesSettings
     * @return void
     */
    public function pagesSettings(): void {

        $settingId = HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings';

        add_settings_section(
            $settingId,
            __('Page Settings', HEYRECRUIT_OPTION_KEY_NAME),
            '__return_false',
            $settingId,
        );

        $jobsPageId = HEYRECRUIT_OPTION_KEY_NAME . 'JobsPageId';
        $jobsPageIdValue = $this->getOption('JobsPageId');
        $jobsPageIdPermaLink = get_permalink($jobsPageIdValue);

        add_settings_field(
            $jobsPageId,
            __('Jobs overview page id', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'         => 'text',
                'name'         => $jobsPageId,
                'id'           => $jobsPageId,
                'value'        => $jobsPageIdValue,
                'infoText'     => $jobsPageIdPermaLink,
                'errorMessage' => $this->getPageIdErrorMessage($jobsPageIdValue, $jobsPageIdPermaLink)
            ]
        );

        $jobDetailPageId = HEYRECRUIT_OPTION_KEY_NAME . 'JobDetailPageId';
        $jobDetailPageIdValue = $this->getOption('JobDetailPageId');
        $jobDetailPageIdPermaLink = get_permalink($jobDetailPageIdValue);

        add_settings_field(
            $jobDetailPageId,
            __('Job detail page id', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'         => 'text',
                'name'         => $jobDetailPageId,
                'id'           => $jobDetailPageId,
                'value'        => $jobDetailPageIdValue,
                'infoText'     => $jobDetailPageIdPermaLink,
                'errorMessage' => $this->getPageIdErrorMessage($jobDetailPageIdValue, $jobDetailPageIdPermaLink)
            ]
        );

        $confirmPagePageId = HEYRECRUIT_OPTION_KEY_NAME . 'ConfirmPageId';
        $confirmPagePageIdValue = $this->getOption('confirmPageId');
        $confirmPagePageIdPermaLink = get_permalink($confirmPagePageIdValue);

        add_settings_field(
            $confirmPagePageId,
            __('Confirm Page id', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'         => 'text',
                'name'         => $confirmPagePageId,
                'id'           => $confirmPagePageId,
                'value'        => $confirmPagePageIdValue,
                'infoText'     => $confirmPagePageIdPermaLink,
                'errorMessage' => $this->getPageIdErrorMessage($confirmPagePageIdValue, $confirmPagePageIdPermaLink)
            ]
        );

        $confirmPageId = HEYRECRUIT_OPTION_KEY_NAME . 'ConfirmPage';

        add_settings_field(
            $confirmPageId,
            __('URL for success page', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'     => 'external_url_text',
                'name'     => $confirmPageId,
                'id'       => $confirmPageId,
                'value'    => $this->getOption('confirmPage'),
                'infoText' => sprintf(__('Example: %s/application-successful-submitted', HEYRECRUIT_OPTION_KEY_NAME), home_url()),
            ]
        );

        $confirmResetPageIdsId = HEYRECRUIT_OPTION_KEY_NAME . 'ConfirmResetPageIds';

        add_settings_field(
            $confirmResetPageIdsId,
            '',
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'  => 'hidden',
                'id'    => $confirmResetPageIdsId,
                'name'  => $confirmResetPageIdsId,
                'value' => '0'
            ]
        );


        load_template(HEYRECRUIT_PLUGIN_DIR . '/templates/admin/pages_settings.php', true, [
            'settingId' => $settingId
        ]);
    }

    /**
     * User options
     * @return void
     */
    public function otherSettings(): void {

        $settingId = HEYRECRUIT_OPTION_KEY_NAME . '-other-settings';

        add_settings_section(
            $settingId,
            __('User Options', HEYRECRUIT_OPTION_KEY_NAME),
            '__return_false',
            $settingId,
        );

        $customColorId = HEYRECRUIT_OPTION_KEY_NAME . 'CustomColor';

        add_settings_field(
            $customColorId,
            __('CustomColor', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'        => 'text',
                'name'        => $customColorId,
                'id'          => $customColorId,
                'value'       => $this->getOption('CustomColor'),
                'placeholder' => HEYRECRUIT_CUSTOM_COLOR,
            ]
        );

        $backgroundColorId = HEYRECRUIT_OPTION_KEY_NAME . 'BackgroundColor';

        add_settings_field(
            $backgroundColorId,
            __('BackgroundColor', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'        => 'text',
                'name'        => $backgroundColorId,
                'id'          => $backgroundColorId,
                'value'       => $this->getOption('BackgroundColor'),
                'placeholder' => HEYRECRUIT_BACKGROUND_COLOR,
            ]
        );

        $googleMapApiKeyId = HEYRECRUIT_OPTION_KEY_NAME . 'GoogleApiKey';

        add_settings_field(
            $googleMapApiKeyId,
            __('Your Google Maps API key', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'  => 'text',
                'name'  => $googleMapApiKeyId,
                'id'    => $googleMapApiKeyId,
                'value' => $this->getOption('GoogleApiKey'),
            ]
        );

        $googleTagManagerActiveId = HEYRECRUIT_OPTION_KEY_NAME . 'GoogleTagManager';

        add_settings_field(
            $googleTagManagerActiveId,
            __('Activate Google Tag Manager', HEYRECRUIT_OPTION_KEY_NAME),
            [
                $this,
                'createFormElement'
            ],
            $settingId,
            $settingId, [
                'type'  => 'checkbox',
                'name'  => $googleTagManagerActiveId,
                'id'    => $googleTagManagerActiveId,
                'value' => $this->getOption('GoogleTagManager'),
            ]
        );

        load_template(HEYRECRUIT_PLUGIN_DIR . '/templates/admin/other_settings.php', true, [
            'settingId' => $settingId
        ]);
    }

    /**
     * createFormElement
     *
     * @param array $args
     * @return void
     */
    public function createFormElement(array $args): void {

        $type = $args['type'];

        if (in_array($type, [
            'text',
            'checkbox',
            'password',
            'hidden',
            'external_url_text'
        ])) {

            load_template(
                HEYRECRUIT_PLUGIN_DIR . '/templates/formElements/settings/' . $type . '.php',
                false,
                array_map('esc_attr', $args)
            );
        }
    }

    /**
     * preUpdateCompanyIdOrSecretKey
     *
     * @param $newValue
     * @param $oldValue
     * @param $optionName
     * @return string
     */
    public function preUpdateCompanyIdOrSecretKey($newValue, $oldValue, $optionName): string {

        if ($newValue !== $oldValue && $newValue !== $this->secretKeyDefaultValue) {
            $this->unsetHeyrecruitApiSessions();

            return $newValue;
        }

        return get_option($optionName);
    }

    /**
     * setDefaultOptions
     * @return void
     */
    private function setDefaultOptions(): void {

        if (!$this->getOption('ConfirmResetPageIds', false))
            add_option(HEYRECRUIT_OPTION_KEY_NAME . 'ConfirmResetPageIds', '0', '', 'no');

    }

    /**
     * handleResetPageIdsOptions
     *
     * @param string $oldValue
     * @param string $newValue
     * @return void
     */
    public function handleResetPageIdsOptions(string $oldValue, string $newValue): void {

        if ($newValue === '1') {

            $optionKeyPrefix = HEYRECRUIT_OPTION_KEY_NAME;

            foreach ($this->defaultPosts as $defaultPostKey => $defaultPost) {

                $postId = $this->createPage($defaultPostKey,
                    __($defaultPost['pageTitle'], $optionKeyPrefix), $defaultPost['pageStatus']);

                if (isset($postId['postId']) && is_int($postId['postId']) && empty($postId['createPostError'])) {
                    update_option($optionKeyPrefix . $defaultPost['optionPageId'], $postId['postId'], 'no');

                }
            }

            update_option($optionKeyPrefix . 'ConfirmPage', '', 'no');
            update_option($optionKeyPrefix . 'ConfirmResetPageIds', '0', 'no');
        }
    }

    /**
     * enqueueScripts
     *
     * @param string $hook
     * @return void
     */
    public function enqueueScripts(string $hook): void {

        if ($hook !== HEYRECRUIT_OPTION_KEY_NAME . '_page_' . HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings') {
            return;
        }

        wp_enqueue_script('heyrecruit-admin-handle', HEYRECRUIT_PLUGIN_URL . 'js/heyrecruit-admin.js',
            [],
            HEYRECRUIT_DEBUG_MODUS ? time() : esc_attr(HEYRECRUIT_VERSION), true
        );

        // wp_localize_script('heyrecruit-admin-handle', 'heyrecruitAdminData', []);
    }

    /**
     * enqueueStyles
     *
     * @param string $hook
     * @return void
     */
    public function enqueueStyles(string $hook): void {

        if ($hook !== HEYRECRUIT_OPTION_KEY_NAME . '_page_' . HEYRECRUIT_OPTION_KEY_NAME . '-pages-settings') {
            return;
        }

        wp_enqueue_style('heyrecruit-admin-styles', HEYRECRUIT_PLUGIN_URL . 'css/heyrecruit-admin-styles.css',
            [],
            HEYRECRUIT_DEBUG_MODUS ? time() : esc_attr(HEYRECRUIT_VERSION), 'all'
        );
    }

    /**
     * getPageIdErrorMessage
     *
     * @param string $pageIdValue
     * @param string $pageIdPermaLink
     * @return string|null
     */
    private function getPageIdErrorMessage(string $pageIdValue, string $pageIdPermaLink): ?string {
        if (!$pageIdValue) {
            return __('Page id must not be empty.', HEYRECRUIT_OPTION_KEY_NAME);
        } elseif (!$pageIdPermaLink) {

            return sprintf(
                __('No page with the ID %s found.', HEYRECRUIT_OPTION_KEY_NAME),
                $pageIdValue);
        }

        return null;
    }
}
