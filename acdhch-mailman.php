<?php

namespace AcdhchMailman;

/**
 * Plugin Name: ACDHCH mailman
 * Version: 1.0
 * Description: GNU Mailman integration with ACDHCH Fundament WP Plugin
 * Author: Norbert Czirjak <norbert.czirjak@oeaw.ac.at>
 * Author URI: https://www.oeaw.ac.at/acdh/acdh-ch-home
 * Plugin URI:  https://www.oeaw.ac.at/acdh/acdh-ch-home
 */
//Define Dirpath for hooks
define('DIR_PATH', plugin_dir_path(__FILE__));

if (!defined('ABSPATH')) {
    return;
}

if (!class_exists('AcdhchMailman')) {

    class AcdhchMailman {

        /**
         * Constructor
         */
        public function __construct() {
            $this->setup_actions();
        }

        /**
         * Setting up Hooks
         */
        public function setup_actions() {
            //Main plugin hooks
            register_activation_hook(DIR_PATH, array('AcdhchMailman', 'activate'));
            register_deactivation_hook(DIR_PATH, array('AcdhchMailman', 'deactivate'));
            $this->autoloadFiles();
            $this->addMenu();
            add_action('widgets_init', array($this, 'acdhch_load_widget'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_widget_scripts'));
            add_action('wp_ajax_acdhch_nls_ajax_request', array($this, 'acdhch_nls_ajax_request'));
            // If you wanted to also use the function for non-logged in users (in a theme for example)
            add_action('wp_ajax_nopriv_acdhch_nls_ajax_request', array($this, 'acdhch_nls_ajax_request'));
        }

        private function autoloadFiles(): void {
            include_once 'inc/WordPressSettings.php';
            include_once 'inc/WordPressMenu.php';
            include_once 'inc/WordPressSubMenu.php';
            include_once 'inc/WordPressMenuTab.php';
            include_once 'widget/AcdhchNewsletterWidget.php';
        }

        /**
         * Activate callback
         */
        public static function activate() {
            
        }

        /**
         * Deactivate callback
         */
        public static function deactivate() {
            //Deactivation code in here
        }

        /**
         * Load the widget
         */
        public function acdhch_load_widget() {
            register_widget('AcdhchMailman\Widget\AcdhchNewsletterWidget');
        }

        public function enqueue_widget_scripts() {
            wp_enqueue_style('acdhch-nls-widget-style', plugin_dir_url(__FILE__) . '/css/acdhch-mailman.css', false, '1.1', 'all');
            wp_enqueue_script('acdhch-nls-widget-script', plugin_dir_url(__FILE__) . 'js/acdhch-mailman-widget.js', array('jquery'), false, true);

            wp_localize_script(
                    'acdhch-nls-widget-script',
                    'acdhch_nls_widget_obj',
                    array(
                        'ajaxurl' => admin_url('admin-ajax.php'),
                        'nonce' => wp_create_nonce('ajax-nonce')
            ));
        }

        /**
         * The AJAX request for the MAILMAN subscribe event
         */
        public function acdhch_nls_ajax_request() {
            $result = array();
            $result['status'] = false;

            // The $_REQUEST contains all the data sent via ajax
            if (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
                $email = $_REQUEST['email'];
                $list_url = $this->getOptionValues('acdhch-nl-menu', 'mailman_url') . "/members/";
                $adminpwd = $this->getOptionValues('acdhch-nl-menu', 'mailman_pwd');
                $fullurl = $list_url . "add?subscribe_or_invite=0&send_welcome_msg_to_this_batch=0&notification_to_list_owner=0&subscribees=" . $email . "&adminpw=" . $adminpwd;

                $ch = curl_init($fullurl);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);

                if (strpos(strtolower($response), strtolower("Successfully subscribed")) !== false) {
                    $result['status'] = true;
                }
            }
            $result = json_encode($result);
            echo $result;
            die();
        }

        /**
         * Get the option values from the DB
         * @param string $optionName
         * @param string $optionKey
         * @return string
         */
        private function getOptionValues(string $optionName, string $optionKey): string {
            $option = get_option($optionName);
            if (count((array) $option) > 0) {
                if (isset($option[$optionKey])) {
                    return (string) $option[$optionKey];
                }
            }
            return "";
        }

        /**
         * Add the WP Admin menus
         */
        private function addMenu() {
            $customWPMenu = new \AcdhchMailman\Inc\WordPressMenu(array(
                'slug' => 'acdhch-nl-menu',
                'title' => 'ACDHCH Mailman Menu',
                'desc' => 'Settings for the ACDHCH Mailman',
                'icon' => 'dashicons-welcome-widgets-menus',
                'position' => 99,
            ));

            $customWPMenu->add_field(array(
                'name' => 'mailman_url',
                'title' => 'Mailman URL',
                'desc' => 'F.e.: https://lists.oeaw.ac.at/mailman/admin/newsletter-name',
            ));

            $customWPMenu->add_field(array(
                'name' => 'mailman_pwd',
                'title' => 'Mailman Admin Password',
                'desc' => '',
                'type' => 'password'
            ));
        }

    }

    // instantiate the plugin class
    $wp_plugin_template = new AcdhchMailman();
}