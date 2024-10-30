<?php

namespace heyrecruit\Controller;

class HeyrecruitPluginController extends HeyrecruitAdminController {

    /**
     * @var HeyrecruitPluginController|null
     */
    private static ?HeyrecruitPluginController $instance = null;


    private function __construct() {

        parent::__construct();

        register_activation_hook(HEYRECRUIT_PLUGIN_FILE, [
            $this,
            'activate'
        ]);

        register_deactivation_hook(HEYRECRUIT_PLUGIN_FILE, [
            $this,
            'deactivate'
        ]);

//        register_uninstall_hook(HEYRECRUIT_PLUGIN_FILE, [
//            'heyrecruit\Controller\HeyrecruitPluginController',
//            'uninstall'
//        ]);

//        add_action('plugin_updated', function ($plugin, $version) {
//            if ($plugin === 'heyrecruit/heyrecruit.php') {
//                do_action('heyrecruit_after_update');
//            }
//        }, 10, 2);
    }


    public static function getInstance(): ?HeyrecruitPluginController {

        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->loadTextdomain();
        }

        return self::$instance;
    }

    /**
     * activate
     *
     * @return void
     */
    public function activate() {

        $optionKeyPrefix = HEYRECRUIT_OPTION_KEY_NAME;

        update_option($optionKeyPrefix . 'ConfirmResetPageIds', 0, 'no');

        foreach ($this->defaultPosts as $defaultPostKey => $defaultPost) {

            $postId = $this->getOption($defaultPost['optionPageId'], false);
            $postExists = get_post($postId) !== null;

            if ($postId && $postExists) {
                $createOrUpdateDefaultPagesAndOptions = true;
                continue;
            }

            $postId = $this->createPage($defaultPostKey,
                __($defaultPost['pageTitle'], $optionKeyPrefix), $defaultPost['pageStatus']);

            if (isset($postId['postId']) && is_int($postId['postId']) && empty($postId['createPostError'])) {
                update_option($optionKeyPrefix . $defaultPost['optionPageId'], $postId['postId'], 'no');
                $createOrUpdateDefaultPagesAndOptions = true;
            } else {
                $createOrUpdateDefaultPagesAndOptions = false;
                break;
            }
        }

        if ($createOrUpdateDefaultPagesAndOptions ?? false) {

            update_option(HEYRECRUIT_OPTION_KEY_NAME . 'PluginActivated', 1, 'no');

        } else {

            $this->performUninstall();
        }
    }

    /**
     * deactivate
     * @return void
     */
    public function deactivate() {

        update_option(HEYRECRUIT_OPTION_KEY_NAME . 'PluginActivated', 0, 'no');
    }

    public static function uninstall() {

        $instance = self::getInstance();

        $instance->performUninstall();
    }

    /**
     * performUninstall
     * @return void
     */
    private function performUninstall() {

        foreach ($this->defaultOptions as $option) {
            delete_option(HEYRECRUIT_OPTION_KEY_NAME . $option);
        }
    }
}