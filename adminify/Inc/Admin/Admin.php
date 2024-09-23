<?php

namespace WPAdminify\Inc\Admin;

use WPAdminify\Inc\Utils;
use \WPAdminify\Inc\Classes\CustomAdminColumns;
use \WPAdminify\Inc\Classes\Tweaks;
use \WPAdminify\Inc\Classes\MenuStyle;
use \WPAdminify\Inc\Classes\AdminBar;
use \WPAdminify\Inc\Classes\OutputCSS;
use \WPAdminify\Inc\Classes\ThirdPartyCompatibility;
use \WPAdminify\Inc\Classes\AdminFooterText;
use \WPAdminify\Inc\Admin\Modules;
use \WPAdminify\Inc\Classes\Sidebar_Widgets;
use \WPAdminify\Inc\Classes\Remove_DashboardWidgets;
use WPAdminify\Inc\Classes\Adminify_Rollback;
use WPAdminify\Inc\Admin\AdminSettings;

// no direct access allowed
if (!defined('ABSPATH')) {
	exit;
}
/**
 * WP Adminify
 * Admin Class
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */

if (!class_exists('Admin')) {
	class Admin
	{
		public $options = [];

		public function __construct()
		{
			$this->options = AdminSettings::get_instance()->get();

			$this->jltwp_adminify_modules_manager();

			// Remove Page Header like - Dashboard, Plugins, Users etc
			// add_action('admin_head', [$this, 'remove_page_headline'], 99);


			// Freemius Hooks
			jltwp_adminify()->add_filter('freemius_pricing_js_path', array($this, 'jltwp_new_freemius_pricing_js'));
			jltwp_adminify()->add_filter('plugin_icon', array($this, 'jltwp_adminify_logo_icon'));

			jltwp_adminify()->add_filter('support_forum_url', [$this, 'jltwp_adminify_support_forum_url']);

			// Disable deactivation feedback form
			jltwp_adminify()->add_filter('show_deactivation_feedback_form', '__return_false');

			// Disable after deactivation subscription cancellation window
			jltwp_adminify()->add_filter('show_deactivation_subscription_cancellation', '__return_false');

			$this->disable_gutenberg_editor();
		}

		public function disable_gutenberg_editor()
		{
			// Sidebar Widgets Remove
			if (!empty($this->options['remove_widgets']['disable_gutenberg_editor'])) {
				// Disable Gutenberg for Block Editor
				add_filter('gutenberg_use_widgets_block_editor', '__return_false');
				// Disable Gutenberg for widgets.
				add_filter('use_widgets_block_editor', '__return_false');
			}

			// Disable Block Editor Gutenberg
			if ( !empty($this->options["disable_gutenberg"]['disable_for']) && in_array('block_editor', $this->options["disable_gutenberg"]['disable_for'] ) ) {
				add_filter('use_block_editor_for_post', '__return_false');
				add_action('wp_enqueue_scripts', [$this, 'remove_backend_gutenberg_scripts'], 20);
			}


			// Remove all scripts and styles added by Gutenberg
			if (!empty($this->options["disable_gutenberg"]['disable_for']) && in_array('remove_gutenberg_scripts', $this->options["disable_gutenberg"]['disable_for'])) {
				add_action('wp_enqueue_scripts', [$this, 'remove_gutenberg_scripts']);
				remove_action('enqueue_block_assets', 'wp_enqueue_registered_block_scripts_and_styles');
			}
		}


		// Dequeue all Frontend scripts and styles added by Gutenberg
		public function remove_gutenberg_scripts()
		{
			wp_dequeue_style('wp-block-library');
			wp_dequeue_style('wc-block-style');
		}

		/**
		 * Remove Gutenberg Scripts
		 *
		 * @return void
		 */
		public function remove_backend_gutenberg_scripts()
		{
			if(is_admin()){
				// Remove CSS on the front end.
				wp_dequeue_style('wp-block-library');

				// Remove Gutenberg theme.
				wp_dequeue_style('wp-block-library-theme');

				// Remove inline global CSS on the front end.
				wp_dequeue_style('global-styles');
			}
		}

		/**
		 * Adminify Logo
		 *
		 * @param [type] $logo
		 *
		 * @return void
		 */
		public function jltwp_adminify_logo_icon($logo)
		{
			$logo = WP_ADMINIFY_PATH . '/assets/images/adminify.svg';
			return $logo;
		}

		public function jltwp_new_freemius_pricing_js($default_pricing_js_path)
		{
			return WP_ADMINIFY_PATH . '/Libs/freemius-pricing/freemius-pricing.js';
		}

		/**
		 * WP Adminify: Modules
		 */
		public function jltwp_adminify_modules_manager()
		{
			// new MenuStyle();
			new Modules();
			new AdminBar();
			new Tweaks();
			new OutputCSS();
			new ThirdPartyCompatibility();
			new AdminFooterText();
			new Sidebar_Widgets();
			new Remove_DashboardWidgets();

			$not_load_frame = [
				'/wp-admin/post-new.php',
				'/wp-admin/customize.php',
				'/wp-admin/site-editor.php',
				'/wp-admin/post.php'
			];

			if ( !empty($this->options['admin_ui']) && empty( in_array($_SERVER['PHP_SELF'], $not_load_frame) ) ) {
				if (preg_match('/https:\/\//', site_url()) && is_ssl()) {
					\WPAdminify\Inc\Admin\Frames\Init::instance();
				}
			}

			// Version Rollback
			// Adminify_Rollback::get_instance();
		}


		/**
		 * Remove Page Headlines: Dashboard, Plugins, Users
		 *
		 * @return void
		 */
		public function remove_page_headline()
		{
			$screen = get_current_screen();
			if (empty($screen->id)) {
				return;
			}

			if (in_array(
				$screen->id,
				[
					'dashboard',
					'nav-menus',
					'edit-tags',
					'themes',
					'widgets',
					'plugins',
					'plugin-install',
					'users',
					'user',
					'profile',
					'tools',
					'import',
					'export',
					'export-personal-data',
					'erase-personal-data',
					'options-general',
					'options-writing',
					'options-reading',
					'options-discussion',
					'options-media',
					'options-permalink',
				]
			)) {
				echo '<style>#wpbody-content .wrap > h1,#wpbody-content .wrap > h1.wp-heading-inline{display:none;}</style>';
			}
		}


		/**
		 * Support Contact URL
		 *
		 * @param [type] $support_url and Pro Support
		 *
		 * @return void
		 */
		public function jltwp_adminify_support_forum_url($support_url)
		{
			if (jltwp_adminify()->is_premium()) {
				$support_url = 'https://wpadminify.com/contact';
			} else {
				$support_url = 'https://wordpress.org/support/plugin/adminify/';
			}
			return $support_url;
		}
	}
}
