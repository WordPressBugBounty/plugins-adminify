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
		public $type  = 'notice';
		public $color = 'info';

		/**
		 * Latest Updates Notice
		 *
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct();
			if(is_admin()){
        add_action( 'admin_footer', array( $this, 'jltwp_handle_plugin_update_notice_dismiss' ),99999 );
				add_action( 'wp_ajax_jltwp_plugin_update_info', array( $this, 'jltwp_plugin_update_info' ) );
      }
		}

		/**
		 * Handles the AJAX request for the plugin update info notice dismissal.
		 *
		 * Triggered when the user clicks the Dismiss button in the notice.
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function jltwp_plugin_update_info() {
			if (!current_user_can('install_plugins')) {
				return;
			}

			// Verify nonce for security.
			check_ajax_referer( 'dismiss_notice_nonce', 'nonce' );
			wp_send_json_success( array( 'message' => 'Notice dismissed.', 'data' => update_option('_wpadminify_plugin_update_info_notice', "dismissed" ) ) );
		}


		/**
		 * Notice Content
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_content()
		{
			if("dismissed" !== get_option('_wpadminify_plugin_update_info_notice', true )){
				$jltwp_adminify_changelog_message = sprintf(
					__('%3$s %4$s %5$s %6$s %7$s %8$s <br> <strong>Check Changelogs for </strong> <a href="%1$s" target="__blank">%2$s</a>', 'adminify'),
					esc_url_raw('https://wpadminify.com/changelogs'),
					__('More about Updates ', 'adminify'),
					/** Changelog Items
					 * Starts from: %3$s
					 */

					'<h3 class="adminify-update-head">' . WP_ADMINIFY . ' <span><small><em>v' . esc_html(WP_ADMINIFY_VER) . '</em></small>' . __(' has some updates..', 'adminify') . '</span></h3><br>', // %3$s
					__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> <strong>Fixed:</strong> Addons install issue fixed. </span><br>', 'adminify'),
					__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> <strong>Fixed:</strong> Adminify update notice was not hiding issue fixed. </span><br>', 'adminify'),
					__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> <strong>Fixed:</strong> "Ctrl/cmd + click" to open new window issue fixed. </span><br>', 'adminify'),
					__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> <strong>Fixed:</strong> Subdomain with same origin click to open new window issue fixed. </span><br>', 'adminify'),
					__('<span class="dashicons dashicons-yes"></span> <span class="adminify-changes-list"> <strong>Fixed:</strong> An issue where clearing the cache via the Redis Object Cache plugin incorrectly triggered a redirect to its settings page. </span><br>', 'adminify')
				);
				printf(wp_kses_post($jltwp_adminify_changelog_message));
			}
		}

		/**
		 * Notice Header
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_header() {
			if("dismissed" !== get_option('_wpadminify_plugin_update_info_notice', true )){
				$border_colors = array(
					'info'    => '#72aee6',
					'success' => '#00a32a',
					'warning' => '#dba617',
					'error'   => '#d63638',
				);
				$border_color = isset( $border_colors[ $this->color ] ) ? $border_colors[ $this->color ] : $border_colors['info'];
				?>
				<div class="wp-adminify-notice--ignored wp-adminify-notice wp-adminify-notice-<?php echo esc_attr( $this->color ); ?> wp-adminify-notice-<?php echo esc_attr( $this->get_id() ); ?> wp-adminify-notice-plugin-update-info" style="background: #fff; border-left: 4px solid <?php echo esc_attr( $border_color ); ?>; padding: 10px 12px; margin: 5px 15px 2px 0; box-shadow: 0 1px 1px rgba(0,0,0,.04); position: relative;">
					<button type="button" class="wp-adminify-notice-dismiss" data-notice-type="plugin_update_notice" style="position: absolute; top: 0; right: 1px; border: none; margin: 0; padding: 9px; background: none; color: #787c82; cursor: pointer;">
						<span class="dashicons dashicons-no-alt"></span>
					</button>
					<div class="wp-adminify-notice-content">
				<?php
			}else{ echo '<div class="wp-adminify-notice-hidden" style="display:none;"><div>';}
		}

		public function jltwp_handle_plugin_update_notice_dismiss() { ?>

			<script>

				function jltwp_adminify_update_plugin_info_notice_action(evt, $this, action_type) {
					// if (evt) evt.preventDefault();
					$this.closest('.wp-adminify-notice-plugin-update-info').slideUp(200);
					jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
						action: 'jltwp_plugin_update_info',
						_wpnonce: '<?php echo esc_js( wp_create_nonce( 'dismiss_notice_nonce' ) ); ?>',
						action_type: action_type,
						plugin_name: $this.data('noticetype')
					}).then(function(response) {
						console.log(response);
					});
				}

				// Notice Dismiss
				jQuery(document).on('click', '.wp-adminify-notice-dismiss', function(evt) {
					evt.preventDefault();
					evt.stopImmediatePropagation();

					jltwp_adminify_update_plugin_info_notice_action(evt, jQuery(this), 'dismiss');
				});
			</script>

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
