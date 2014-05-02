<?php
/*
Plugin Name: Aboutimizer
Plugin URI: https://github.com/seriouslysean/aboutimizer
Description: The Aboutimizer adds an about me widget that you can customize with a photo and description.
Author: Sean Kennedy
Author URI: http://devjunkyard.com
Version: 1.0.1
License: MIT
*/
if (!defined('ABSPATH')) exit;
if (!class_exists('Aboutimizer')):
    require_once 'inc/widget.php';
    class Aboutimizer {

        /*************************************************
         * VARIABLES
         ************************************************/

        const NAME = 'Aboutimizer';
        const NAME_LOWER = 'aboutimizer';
        const VERSION = '1.0.1';
        const SLUG = 'aboutimizer';
        const DOWNLOAD_URL = 'http://wordpress.org/plugins/aboutimizer';

        static $instance;

        protected $_wpVersion;
        protected $_options;
        protected $_pluginPath;
        protected $_pluginUrl;



        /*************************************************
         * INITIALIZE / CONFIGURE
         ************************************************/

        static function load() {
            if (!self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct() {
            $this->_init();
        }

        protected function _init() {
            $this->_setWpVersion();
            $this->_setOptions();
            $this->_setPluginPath();
            $this->_setPluginUrl();
            //
            add_action('admin_enqueue_scripts', array($this, 'styles'), 9000);
            add_action('admin_enqueue_scripts', array($this, 'scripts'), 9000);
            add_action('admin_footer', array($this, 'adminFooter'), 9000);
            //
            add_action('wp_enqueue_scripts', array($this, 'styles'), 9000);
        }

        public static function activate() {
            self::defaults();
        }

        public static function deactivate() {
        }

        public static function uninstall() {
            delete_option(self::SLUG);
        }

        public static function defaults() {
            if (!get_option(self::SLUG)) {
                // Options
                update_option(
                    self::SLUG,
                    array(
                        'version' => self::VERSION
                    )
                );
            }
        }

        public static function widget() {
            register_widget("Foo_Widget");
        }

        public function styles($hook) {
            if (is_admin()) {
                if ($hook == 'widgets.php')
                    wp_enqueue_style(self::SLUG.'-styles', $this->getPluginUrl().'css/styles.css', array(), self::VERSION);
            } else {
                wp_enqueue_style(self::SLUG.'-frontend', $this->getPluginUrl().'css/frontend.css', array(), self::VERSION);
            }
            
        }

        public function scripts($hook) {
            if ($hook == 'widgets.php') {
                wp_enqueue_script(self::SLUG.'-scripts', $this->getPluginUrl().'js/scripts.js', array(), self::VERSION);
                wp_enqueue_media();
            }
        }

        public function adminFooter() {
            require_once $this->getPluginPath().'templates/donate.php';
        }



        /*************************************************
         * SETTERS
         ************************************************/

        private function _setWpVersion() {
            $this->_wpVersion = floatval(get_bloginfo('version'));
        }

        private function _setOptions() {
            $options = get_option(self::SLUG);
            if (!$options)
                $this->defaults();
            $this->_options = $options;
        }

        private function _setOption($key, $value) {
            $this->_options[$key] = $value;
            return update_option(self::SLUG, $this->_options);
        }

        private function _setPluginUrl() {
            $this->_pluginUrl = plugin_dir_url(__FILE__);
        }

        private function _setPluginPath() {
            $this->_pluginPath = plugin_dir_path(__FILE__);
        }



        /*************************************************
         * GETTERS
         ************************************************/

        public function getWpVersion() {
            return $this->_wpVersion;
        }

        public function getOptions() {
            return $this->_options;
        }

        public function getOption($key) {
            if (!isset($this->_options[$key]))
                return false;
            return $this->_options[$key];
        }

        public function getPluginUrl() {
            return $this->_pluginUrl;
        }

        public function getPluginPath() {
            return $this->_pluginPath;
        }

    }
    register_activation_hook( __FILE__, array('Aboutimizer', 'activate'));
    register_deactivation_hook( __FILE__, array('Aboutimizer', 'deactivate'));
    register_uninstall_hook(__FILE__, array('Aboutimizer', 'uninstall'));
    add_action('plugins_loaded', array('Aboutimizer', 'load'));
endif;
