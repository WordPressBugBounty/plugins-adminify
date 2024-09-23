<?php

namespace WPAdminify\Inc\Admin\Frames;

// no direct access allowed
if (!defined('ABSPATH')) {
    exit;
}
/**
 * WP Adminify
 * Init Class
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */

if (!class_exists('Init')) {
    class Init
    {
        public static $instance;
        public $admin;
        public $frame;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct()
        {
            if (is_iframe()) {
                $this->frame = new Frames();
            } else {
                $this->admin = new Admin();
            }
        }
    }
}
