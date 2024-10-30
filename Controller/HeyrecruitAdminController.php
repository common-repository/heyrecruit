<?php

namespace heyrecruit\Controller;

class HeyrecruitAdminController extends HeyrecruitRestApiController {

    /**
     * @var string[]
     */
    protected array $defaultOptions = [
        'PluginActivated',
        'CompanyId',
        'SecretKey',
        'JobsPageId',
        'JobDetailPageId',
        'ConfirmPageId',
        'ConfirmPage',
        'ConfirmResetPageIds',
        'CustomColor',
        'BackgroundColor',
        'GoogleApiKey',
        'GoogleTagManager'
    ];

    /**
     * @var array|array[]
     */
    protected array $defaultPosts = [

        'overview' => [
            'optionPageId' => 'JobsPageId',
            'pageTitle'    => 'Heyrecruit – Career Page',
            'pageStatus'   => 'draft'
        ],

        'detail' => [
            'optionPageId' => 'JobDetailPageId',
            'pageTitle'    => 'Heyrecruit – Job Page',
            'pageStatus'   => 'draft'
        ],

        'confirm' => [
            'optionPageId' => 'ConfirmPageId',
            'pageTitle'    => 'Heyrecruit – Confirm Page',
            'pageStatus'   => 'draft'
        ]
    ];


    /**
     * __construct
     */
    public function __construct() {

        parent::__construct();

        add_filter('init', [
            $this,
            'loadTextdomain'
        ]);
    }

    /**
     * loadTextdomain
     * @return void
     */
    public function loadTextdomain() {

        $languageFile = HEYRECRUIT_PLUGIN_DIR . '/languages/' . $this->getDefaultLocale() . '.mo';

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
     * getDefaultTemplateContent
     * @param string $templateFileName
     * @param array $args
     * @return string
     */
    protected function getDefaultTemplateContent(
        string $templateFileName,
        array  $args = []
    ): string {

        $templatePath = HEYRECRUIT_PLUGIN_DIR . '/templates/defaultPages/' . $templateFileName . '.php';

        ob_start();

        if (file_exists($templatePath)) {
            load_template($templatePath, true, $args);
        } else {

            load_template(
                HEYRECRUIT_PLUGIN_DIR . '/templates/content/template_not_found.php',
                true,
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
     * @param string $templateFileName
     * @param string $pageTitle
     * @param string $postStatus
     * @return void
     */
    protected function createPage(
        string $templateFileName,
        string $pageTitle,
        string $postStatus = 'draft'
    ): array {

        $postData = [
            'post_title'   => $pageTitle,
            'post_content' => $this->getDefaultTemplateContent($templateFileName),
            'post_status'  => $postStatus,
            'post_type'    => 'page'
        ];

        $postPostId = wp_insert_post($postData);

        if (is_wp_error($postPostId)) {
            $createPostError = $postPostId->get_error_message();
        }

        return [
            'postId'          => $postPostId,
            'createPostError' => $createPostError ?? false
        ];
    }

    /**
     * getOption
     *
     * @param string $name
     * @param bool|null $default
     *
     * @return object|resource|array|string|float|int|bool|null
     */
    protected function getOption(string $name, bool $default = null) {

        return get_option(HEYRECRUIT_OPTION_KEY_NAME . ucfirst($name)) ?? $default;
    }

}
