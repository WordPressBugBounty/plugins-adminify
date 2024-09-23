<?php

namespace WPAdminify\Inc\Classes\Notifications;

use WPAdminify\Inc\Classes\Notifications\Model\Notice;

if (!class_exists('Latest_Updates')) {
	/**
	 * Latest Pugin Updates Notice Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Latest_Updates extends Notice
	{

		/**
		 * Latest Updates Notice
		 *
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct();
		}


		/**
		 * Notice Content
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_content()
		{
			$jltwp_adminify_changelog_message = sprintf(
				__('%3$s %4$s %5$s %6$s %7$s %8$s <br> <strong>Check Changelogs for </strong> <a href="%1$s" target="__blank">%2$s</a>', 'adminify'),
				esc_url_raw('https://wpadminify.com/what-is-new-in-wp-adminify-v4-0/'),
				__('More about "WP Adminify" v4.0 ', 'adminify'),
				/** Changelog Items
				 * Starts from: %3$s
				 */

				'<h3 class="adminify-update-head">' . WP_ADMINIFY . ' <span><small><em>v' . esc_html(WP_ADMINIFY_VER) . '</em></small>' . __(' has some updates..', 'adminify') . '</span></h3><br>', // %3$s
				__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> Horizontal Menu hide on Gutenberg Editor, Style issues fixed </span><br>', 'adminify'),
				__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> RTL Accordion & Toggle Menu style issue fixed </span><br>', 'adminify'),
				__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> Admin Pages - User roles display issue fixed </span><br>', 'adminify'),
				__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> Google Fonts - Body Font not working issue fixed </span><br>', 'adminify'),
				__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> Mini Mode Icon option added </span><br>', 'adminify')
			);

			printf(wp_kses_post($jltwp_adminify_changelog_message));
		}

		/**
		 * Notice Header
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_header() { ?>
			<div class="hide-notice--ignored notice notice-wp-adminify is-dismissible notice-<?php echo esc_attr( $this->color ); ?> wp-adminify-notice-<?php echo esc_attr( $this->get_id() ); ?>">
				<button type="button" class="notice-dismiss wp-adminify-notice-dismiss"></button>
				<div class="notice-content-box">
			<?php
		}

		/**
		 * Intervals
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function intervals()
		{
			return array(0);
		}
	}
}
