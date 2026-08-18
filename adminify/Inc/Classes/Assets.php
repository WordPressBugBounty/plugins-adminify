<?php

namespace PXLBSAdminify\Inc\Classes;

use PXLBSAdminify\Inc\Utils;
use PXLBSAdminify\Inc\Admin\AdminSettings;
use PXLBSAdminify\Inc\Admin\AdminSettingsModel;
use PXLBSAdminify\Inc\Classes\Addons_Plugins;



// no direct access allowed
if (!defined('ABSPATH')) {
	exit;
}

class Assets extends AdminSettingsModel
{

	public $classic_editor = true;
	public $block_editor = true;
	public  $dark_mode = true;

	public function __construct()
	{
		$this->options = (array) AdminSettings::get_instance()->get();
		$global_dark_mode = !empty($this->options['light_dark_mode']['admin_ui_mode']) ? $this->options['light_dark_mode']['admin_ui_mode'] : 'light';
		$this->dark_mode = empty(get_user_meta(get_current_user_id(), 'color_mode', true)) ? $global_dark_mode : get_user_meta(get_current_user_id(), 'color_mode', true);
		add_action('admin_enqueue_scripts', array($this, 'pxlbsadminify_admin_scripts'), 100);
		add_action('wp_ajax_pxlbsadminify_addons_install_active', array( $this, 'pxlbsadminify_addons_install_active' ) );
		add_action('wp_ajax_pxlbsadminify_ssl_check', array( $this, 'pxlbsadminify_ssl_check' ) );

		// Always hooked: header_scripts() prints the dark-mode loader, which the
		// topbar toggle needs even on pages that booted in light mode.
		add_action('admin_head', array($this, 'header_scripts'));
	}


	/**
	 * Resolved light/dark mode for the current user, normalised to
	 * 'light' | 'dark' | 'system'. The settings UI has used 'auto' as a
	 * synonym for 'system', so fold that in here.
	 *
	 * @return string
	 */
	public function color_mode()
	{
		$mode = !empty($this->dark_mode) ? $this->dark_mode : 'light';

		return ('auto' === $mode) ? 'system' : $mode;
	}


	/**
	 * URL of the dark mode (Darkreader) bundle.
	 *
	 * @return string
	 */
	private function dark_mode_url()
	{
		return PXLBSADMINIFY_ASSETS . 'admin/js/wp-adminify-dark-mode' . Utils::assets_ext('.js');
	}


	/**
	 * Function: Ajax Call for Install and Activate WP Adminify Plugin
	 */
	function pxlbsadminify_addons_install_active()
	{

		// Include necessary WordPress files
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		if (isset($_POST['plugin'])) {

			$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

			if (!wp_verify_nonce($nonce, 'pxlbsadminify_addons_nonce')) {
				wp_send_json_error(array('mess' => esc_html__('Nonce is invalid', 'adminify')));
			}

			if ((is_multisite() && is_network_admin()) || !current_user_can('install_plugins')) {
				wp_send_json_error(array('mess' => esc_html__('Invalid Access', 'adminify')));
			}

			$plugin = sanitize_text_field(wp_unslash($_POST['plugin']));

			if (empty($plugin)) {
				wp_send_json_error(array('mess' => esc_html__('Invalid plugin', 'adminify')));
			}

			// Validate the requested plugin against the trusted addons list
			// and replace the user-supplied value with the canonical
			// download URL from that list before passing it to the upgrader.
			$addons         = new Addons_Plugins();
			$plugins_list   = (array) $addons->get_adminify_plugins_lists();
			$requested_slug = '';
			$trusted_source = '';
			foreach ( $plugins_list as $slug => $entry ) {
				if ( ! empty( $entry['download_link'] ) && $entry['download_link'] === $plugin ) {
					$requested_slug = $slug;
					$trusted_source = $entry['download_link'];
					break;
				}
				if ( $slug === $plugin ) {
					$requested_slug = $slug;
					$trusted_source = ! empty( $entry['download_link'] ) ? $entry['download_link'] : '';
					break;
				}
			}
			if ( empty( $requested_slug ) || empty( $trusted_source ) ) {
				wp_send_json_error( array( 'mess' => esc_html__( 'Invalid plugin', 'adminify' ) ) );
			}

			$type     = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : 'install';
			$skin     = new \WP_Ajax_Upgrader_Skin();
			$upgrader = new \Plugin_Upgrader($skin);

			if ('install' === $type) {
				$result = $upgrader->install( $trusted_source );
				if (is_wp_error($result)) {
					wp_send_json_error(
						array(
							'mess' => $result->get_error_message(),
						)
					);
				}
				$args        = array(
					'slug'   => $upgrader->result['destination_name'],
					'fields' => array(
						'short_description' => true,
						'icons'             => true,
						'banners'           => false,
						'added'             => false,
						'reviews'           => false,
						'sections'          => false,
						'requires'          => false,
						'rating'            => false,
						'ratings'           => false,
						'downloaded'        => false,
						'last_updated'      => false,
						'added'             => false,
						'tags'              => false,
						'compatibility'     => false,
						'homepage'          => false,
						'donate_link'       => false,
					),
				);
				$plugin_data = plugins_api('plugin_information', $args);

				if ($plugin_data && !is_wp_error($plugin_data)) {
					$install_status = \install_plugin_install_status($plugin_data);
					activate_plugin($install_status['file']);
				}
				wp_die();  // die();
			}
		}
	}


	 /*
	 * Function is_dark_mode()
	 *
	 */
	public function is_dark_mode()
	{

		if (!empty($this->dark_mode) && $this->dark_mode == 'dark') {
			$adminify_dark_mode = true;
		} else {
			$adminify_dark_mode = false;
		}
		return $adminify_dark_mode;
	}

	/**
	 * Print the dark-mode loader and boot it for the resolved mode.
	 *
	 * The Darkreader bundle is ~110 KB, so it is no longer enqueued on every admin
	 * page. What ships on every page instead is `window.PXLBSADMINIFYDarkMode`, a few hundred
	 * bytes that know how to pull the bundle into a window on demand:
	 *
	 * - 'dark'   the bundle is already enqueued in the head by
	 *            pxlbsadminify_admin_scripts(), so this only calls enable().
	 * - 'system' nothing is enqueued; the browser decides. When the OS prefers dark
	 *            the loader fetches the bundle, with a pre-paint guard so the page
	 *            does not flash white while it streams in.
	 * - 'light'  nothing is fetched at all.
	 *
	 * The topbar toggle (classic admin bar and the React frame alike) calls
	 * PXLBSADMINIFYDarkMode.load() so switching to dark works without a page reload.
	 */
	public function header_scripts()
	{
		// Skip on excluded pages
		if ( $this->should_skip_adminify_scripts() ) {
			return;
		}

		$mode = $this->color_mode();
		?>
		<script id="adminify-dark-mode-loader">
			window.PXLBSADMINIFYDarkMode = window.PXLBSADMINIFYDarkMode || (function () {
				var url = <?php echo wp_json_encode( $this->dark_mode_url() ); ?>;

				// Pulls the bundle into `win` (defaults to this window) and hands
				// AdminifyDarkMode to `cb`. Repeat calls while a fetch is in flight
				// queue up behind it instead of requesting the file again.
				function load(win, cb) {
					win = win || window;
					cb = cb || function () {};

					var doc;
					try {
						doc = win.document;
					} catch (e) {
						return;
					}
					if (!doc) {
						return;
					}

					if (win.AdminifyDarkMode) {
						cb(win.AdminifyDarkMode);
						return;
					}

					if (win.__pxlbsadminifyDarkModeQueue) {
						win.__pxlbsadminifyDarkModeQueue.push(cb);
						return;
					}
					win.__pxlbsadminifyDarkModeQueue = [cb];

					var script = doc.createElement('script');
					script.src = url;
					script.async = false;
					script.onload = function () {
						var queue = win.__pxlbsadminifyDarkModeQueue || [];
						win.__pxlbsadminifyDarkModeQueue = null;
						for (var i = 0; i < queue.length; i++) {
							try {
								queue[i](win.AdminifyDarkMode);
							} catch (e) {}
						}
					};
					script.onerror = function () {
						win.__pxlbsadminifyDarkModeQueue = null;
					};
					(doc.head || doc.documentElement).appendChild(script);
				}

				function enable(win) {
					load(win, function (dm) {
						if (dm) {
							dm.enable({ brightness: 120 });
						}
					});
				}

				function disable(win) {
					win = win || window;
					// Nothing to undo when the bundle was never fetched.
					if (win.AdminifyDarkMode) {
						win.AdminifyDarkMode.disable();
					}
				}

				return { url: url, load: load, enable: enable, disable: disable };
			}());
		</script>
		<?php if ( 'dark' === $mode ) { ?>
			<script>
				window.PXLBSADMINIFYDarkMode.enable();
				addEventListener("load", function () {
					window.PXLBSADMINIFYDarkMode.enable();
				});
			</script>
		<?php } elseif ( 'system' === $mode ) { ?>
			<script>
				(function () {
					if (!(window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
						return;
					}

					// The bundle is fetched rather than blocking in the head, so paint
					// the page dark up front and drop the guard once Darkreader owns it.
					var guard = document.createElement('style');
					guard.textContent = 'html,body{background-color:#181a1b!important;color:#e8e6e3!important}';
					(document.head || document.documentElement).appendChild(guard);

					var dropGuard = function () {
						if (guard && guard.parentNode) {
							guard.parentNode.removeChild(guard);
						}
					};

					window.PXLBSADMINIFYDarkMode.load(window, function (dm) {
						if (dm) {
							dm.enable({ brightness: 120 });
						}
						dropGuard();
					});

					// Failsafe: never leave the guard behind if the fetch never lands.
					setTimeout(dropGuard, 5000);

					addEventListener("load", function () {
						window.PXLBSADMINIFYDarkMode.enable();
					});
				}());
			</script>
		<?php }

	}



	/**
	 * Check if current page should skip Adminify scripts
	 * Handles root, subdirectory, subdomain, and multisite installations
	 *
	 * @return bool True if scripts should be skipped
	 */
	private function should_skip_adminify_scripts() {
		global $pagenow;

		// Pages where Adminify scripts should not load
		$excluded_pages = [
			'customize.php',
			'wp-login.php',
			'wp-register.php',
		];

		// Check global $pagenow
		if ( in_array( $pagenow, $excluded_pages, true ) ) {
			return true;
		}

		// Fallback: Check PHP_SELF for subdirectory WordPress installs
		// Normalize path by extracting just the filename
		$php_self = isset( $_SERVER['PHP_SELF'] ) ? sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) : '';
		$current_file = basename( $php_self );

		if ( in_array( $current_file, $excluded_pages, true ) ) {
			return true;
		}

		// Additional check using REQUEST_URI for edge cases
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		foreach ( $excluded_pages as $page ) {
			if ( strpos( $request_uri, '/' . $page ) !== false ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Matches a Font Awesome class in any of the shapes Adminify stores:
	 * "fa fa-gear", "fas fa-rocket", and the URL-encoded "fas%20fa-rocket" that
	 * WordPress produces when an icon class is handed to add_menu_page().
	 */
	const FONTAWESOME_CLASS_PATTERN = '/(?:^|[^a-z0-9-])fa[bsrl]?(?:\s|%20|-)fa-/i';


	/**
	 * Whether Font Awesome has anything to paint on the current screen.
	 *
	 * The stylesheet plus its webfonts weigh well over a megabyte, so it is only
	 * worth loading where Adminify actually renders an icon with it:
	 *
	 * - Adminify's own screens, whose section headers and field UI use hardcoded
	 *   `fas fa-*` classes.
	 * - The dashboard, but only when a widget is actually configured with a Font
	 *   Awesome class.
	 * - Any screen at all when an admin menu item carries a Font Awesome icon,
	 *   because the sidebar renders on every screen.
	 *
	 * Framework option, metabox, taxonomy, widget, customizer, nav-menu, profile
	 * and comment screens are deliberately not listed: the framework scopes and
	 * enqueues Font Awesome itself in ADMINIFY::add_admin_enqueue_scripts().
	 *
	 * @param \WP_Screen|null $screen Current screen.
	 * @return bool
	 */
	private function needs_fontawesome( $screen ) {

		if ( isset( $screen->id ) && false !== strpos( $screen->id, 'wp-adminify' ) ) {
			return true;
		}

		if ( isset( $screen->id ) && 'dashboard' === $screen->id && $this->has_fontawesome_dashboard_widget() ) {
			return true;
		}

		// Falls through on the dashboard too: the admin menu renders there as well.
		return $this->has_fontawesome_menu_icon();
	}


	/**
	 * Whether any dashboard widget is configured with a Font Awesome class.
	 *
	 * Widget icons come from the framework icon field, whose list is still Font
	 * Awesome (Libs/adminify-framework/functions/actions.php), and the editor and
	 * script widget types can hold arbitrary markup — so scan the whole saved
	 * option rather than just the icon keys, or a widget whose body contains
	 * `<i class="fas fa-star">` would render a blank square.
	 *
	 * The option is the one read by DashboardWidgetModel::$prefix, which the
	 * Dashboard Widget module already loads on every admin page, so this is an
	 * options-cache hit rather than a query. Only ever called on the dashboard.
	 *
	 * @return bool
	 */
	private function has_fontawesome_dashboard_widget() {

		$settings = get_option( 'pxlbsadminify_dasboard_widgets' );

		if ( empty( $settings ) ) {
			return false;
		}

		// Arrays and objects come back as a serialized string; scalars pass through.
		$settings = maybe_serialize( $settings );

		if ( ! is_string( $settings ) || '' === $settings ) {
			return false;
		}

		return (bool) preg_match( self::FONTAWESOME_CLASS_PATTERN, $settings );
	}


	/**
	 * Look for a Font Awesome class stored as an admin menu icon.
	 *
	 * Pro admin pages pass the picked icon class straight to add_menu_page()
	 * (Pro/Modules/AdminPages/AdminPages_Output.php), where WordPress treats it as
	 * an image URL and prints `http://fas%20fa-rocket`; wp-adminify.js turns that
	 * back into a class. Either shape counts as a hit.
	 *
	 * $menu is built during admin_menu, which runs before admin_enqueue_scripts,
	 * so it is fully populated by the time this is called.
	 *
	 * @return bool
	 */
	private function has_fontawesome_menu_icon() {
		global $menu;

		if ( empty( $menu ) || ! is_array( $menu ) ) {
			return false;
		}

		foreach ( $menu as $item ) {

			if ( empty( $item[6] ) || ! is_string( $item[6] ) ) {
				continue;
			}

			if ( preg_match( self::FONTAWESOME_CLASS_PATTERN, $item[6] ) ) {
				return true;
			}
		}

		return false;
	}


	public function pxlbsadminify_admin_scripts()
	{
		// Skip loading scripts on excluded pages (customize.php, login, etc.)
		if ( $this->should_skip_adminify_scripts() ) {
			return;
		}

		$screen = get_current_screen();


		// Register Styles
		wp_register_style('adminify-admin', PXLBSADMINIFY_ASSETS . 'css/wp-adminify' . Utils::assets_ext('.css'), false, PXLBSADMINIFY_VER);
		wp_register_style('adminify-default-ui', PXLBSADMINIFY_ASSETS . 'css/wp-adminify-default-ui' . Utils::assets_ext('.css'), false, PXLBSADMINIFY_VER);


		// Register Scripts
		wp_register_script('adminify-admin', PXLBSADMINIFY_ASSETS . 'admin/js/wp-adminify' . Utils::assets_ext('.js'), array('jquery'), PXLBSADMINIFY_VER, true);

		// Adminify Icon Picker
		wp_register_style('adminify-simple-line-icons', PXLBSADMINIFY_ASSETS . 'vendors/font-icons/simple-line-icons/css/simple-line-icons' . Utils::assets_ext('.css'), false, PXLBSADMINIFY_VER);
		wp_register_style('adminify-icon-picker', PXLBSADMINIFY_ASSETS . 'vendors/adminify-icon-picker/css/style' . Utils::assets_ext('.css'), false, PXLBSADMINIFY_VER);
		wp_register_script('adminify-icon-picker', PXLBSADMINIFY_ASSETS . 'vendors/adminify-icon-picker/js/adminify-icon-picker' . Utils::assets_ext('.js'), array('jquery'), PXLBSADMINIFY_VER, true);

		// Dark Mode
		wp_register_script('adminify--dark-mode', $this->dark_mode_url(), array(), PXLBSADMINIFY_VER, false);

		// Styles Enqueue
		if (!empty($this->options['admin_ui'])) {
			// wp_enqueue_style('wp-adminify-animate');
			wp_enqueue_style('adminify-admin');
			// Commented on: 9-6-24
			// wp_enqueue_style('wp-adminify-admin-bar');
		} else {
			wp_enqueue_style('adminify-default-ui');
		}


		// RTL CSS
		if ( is_rtl() ) {
			wp_enqueue_style( 'adminify-rtl', PXLBSADMINIFY_URL . 'Libs/adminify-framework/assets/css/style-rtl'. Utils::assets_ext('.css'), array(), PXLBSADMINIFY_VER, 'all' );
		  }


		// Dark Mode Style
		// wp_enqueue_style('adminify-dark-mode');

		// Only a user who is actually in dark mode gets the ~110 KB bundle up front.
		// 'system' is resolved in the browser and 'light' never needs it at all; both
		// go through the loader printed by header_scripts().
		if ('dark' === $this->color_mode()) {
			wp_enqueue_script('adminify--dark-mode');
		}


		// Get local fonts data for frontend - download if not exists
		$local_fonts = GoogleFontsLocal::get_instance();
		$local_fonts_urls = [];

		// Process light mode logo font
		if (!empty($this->options['light_dark_mode']['admin_ui_light_mode']['admin_ui_light_logo_text_typo']['font-family'])) {
			$font_family = $this->options['light_dark_mode']['admin_ui_light_mode']['admin_ui_light_logo_text_typo']['font-family'];
			$font_weight = !empty($this->options['light_dark_mode']['admin_ui_light_mode']['admin_ui_light_logo_text_typo']['font-weight']) ? $this->options['light_dark_mode']['admin_ui_light_mode']['admin_ui_light_logo_text_typo']['font-weight'] : '400';

			if (!$local_fonts->is_font_local($font_family)) {
				$local_fonts->download_font($font_family, $font_weight);
			}

			$local_url = $local_fonts->get_local_font_url($font_family);
			if ($local_url) {
				$local_fonts_urls['light_logo'] = $local_url;
			}
		}

		// Process dark mode logo font
		if (!empty($this->options['light_dark_mode']['admin_ui_dark_mode']['admin_ui_dark_logo_text_typo']['font-family'])) {
			$font_family = $this->options['light_dark_mode']['admin_ui_dark_mode']['admin_ui_dark_logo_text_typo']['font-family'];
			$font_weight = !empty($this->options['light_dark_mode']['admin_ui_dark_mode']['admin_ui_dark_logo_text_typo']['font-weight']) ? $this->options['light_dark_mode']['admin_ui_dark_mode']['admin_ui_dark_logo_text_typo']['font-weight'] : '400';

			if (!$local_fonts->is_font_local($font_family)) {
				$local_fonts->download_font($font_family, $font_weight);
			}

			$local_url = $local_fonts->get_local_font_url($font_family);
			if ($local_url) {
				$local_fonts_urls['dark_logo'] = $local_url;
			}
		}

		$local_fonts_data = [
			'base_url' => $local_fonts->get_folder_url(),
			'urls' => $local_fonts_urls,
		];

		$localize_array_data = [
			'admin_ajax'  => admin_url('admin-ajax.php'),
			'settings'    => [
				'adminify_ui'  => !empty($this->options['admin_ui']) ? true : false,
			],
			'admin_nonce' => wp_create_nonce('pxlbsadminify_frame_nonce'),
			'is_pro'      => (class_exists('\\PXLBSAdminify\\Pro\\Adminify_Pro') && !empty(\PXLBSAdminify\Pro\Adminify_Pro::is_premium())) ? true : false,
			'local_fonts' => $local_fonts_data
		];

		// Pro-only settings flags (e.g. menu_search) attach via this
		// filter from Pro/Classes/Assets_Pro.php. Free leaves the
		// localize array untouched.
		$localize_array_data = (array) apply_filters( 'pxlbsadminify_admin_localize_data', $localize_array_data, $this->options );

		// Scripts Enqueue
		wp_enqueue_script('adminify-admin');
		wp_localize_script( 'adminify-admin', 'PXLBSADMINIFY_ADMIN', $localize_array_data );

		// Font Awesome, only on screens that actually paint an icon with it. The
		// old guard called wp_script_is() on what are styles, so it always passed
		// and every admin page paid for the font. wp_enqueue_style() is a no-op on
		// an already-enqueued handle, so no guard is needed against the framework
		// having enqueued these first.
		if ($this->needs_fontawesome($screen)) {
			if (apply_filters('adminify_fa4', false)) {
				wp_enqueue_style('adminify-fa', PXLBSADMINIFY_ASSETS . 'vendors/fontawesome/fa4/css/font-awesome.min.css', array(), '4.7.0', 'all');
			} else {
				wp_enqueue_style('adminify-fa5', PXLBSADMINIFY_ASSETS . 'vendors/fontawesome/fa5/css/all.min.css', array(), '5.15.4', 'all');
				wp_enqueue_style('adminify-fa5-v4-shims', PXLBSADMINIFY_ASSETS . 'vendors/fontawesome/fa5/css/v4-shims.min.css', array(), '5.15.4', 'all');
			}
		}
		wp_enqueue_style('adminify-simple-line-icons');

		// Adminify UI HTTPS gate — only on the main settings screen where the switcher lives.
		if (isset($screen->id) && false !== strpos($screen->id, 'wp-adminify-settings')) {
			wp_enqueue_script('adminify-ui-ssl-check', PXLBSADMINIFY_ASSETS . 'admin/js/adminify-ui-ssl-check.js', array('jquery'), PXLBSADMINIFY_VER, true);
			wp_localize_script(
				'adminify-ui-ssl-check',
				'PXLBSADMINIFY_SSL',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce'    => wp_create_nonce('pxlbsadminify_frame_nonce'),
					'field_id' => 'admin_ui',
					'i18n'     => array(
						'label'         => esc_html__('Adminify UI needs HTTPS:', 'adminify'),
						'generic_error' => esc_html__('Could not verify HTTPS right now. Please try again.', 'adminify'),
					),
				)
			);

			// Adminify UI live theme preset changer
			wp_enqueue_script('adminify-theme-presetter', PXLBSADMINIFY_ASSETS . 'admin/js/wp-adminify-theme-presetter' . Utils::assets_ext('.js'), ['jquery'], PXLBSADMINIFY_VER, true);
      wp_localize_script('adminify-theme-presetter', 'PXLBSADMINIFY_PRESET_THEMES', Utils::get_theme_presets());
		}

		if ($screen->id === 'adminify_page_wp-adminify-addons-plugins' || $screen->id === 'adminify-pro_page_wp-adminify-addons-plugins') {
			// JS Files .
			wp_enqueue_script('adminify-addons', PXLBSADMINIFY_ASSETS . 'admin/js/wp-adminify-addons' . Utils::assets_ext('.js'), array('jquery'), PXLBSADMINIFY_VER, true);
			wp_localize_script(
				'adminify-addons',
				'PXLBSADMINIFY_CORE',
				array(
					'admin_ajax'        => admin_url('admin-ajax.php'),
					'addons_nonce' 		=> wp_create_nonce('pxlbsadminify_addons_nonce'),
					'plugin_key' 		=> 'pxlbsadminify'
				)
			);
		}
	}

	/**
	 * AJAX: verify the site is HTTPS-ready before enabling the Adminify UI.
	 *
	 * Called from assets/admin/js/adminify-ui-ssl-check.js when the user flips
	 * the "Adminify UI" switcher on. Returns success only when the dashboard
	 * frame would actually load (see Utils::adminify_ui_https_status()).
	 */
	public function pxlbsadminify_ssl_check()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

		if (!wp_verify_nonce($nonce, 'pxlbsadminify_frame_nonce')) {
			wp_send_json_error(array(
				'https_ready' => false,
				'message'     => esc_html__('Security check failed. Please reload the page and try again.', 'adminify'),
			));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(array(
				'https_ready' => false,
				'message'     => esc_html__('You do not have permission to change this setting.', 'adminify'),
			));
		}

		$status = Utils::adminify_ui_https_status();

		if (!empty($status['ready'])) {
			wp_send_json_success(array('https_ready' => true));
		}

		wp_send_json_error(array(
			'https_ready' => false,
			'message'     => $status['message'],
		));
	}

	// WP Adminify Options Page Style
	public function pxlbsadminify_admin_script()
	{
		echo '<style>.wp-adminify-two-columns{ display: flex; flex-wrap: wrap; padding: 15px; } .wp-adminify .adminify-hightlight-field{ border: 2px solid #0347FF !important; font-weight: 600 !important;} .wp-adminify-two-columns .adminify-full-width-field{ width: 100% !important; flex-basis: 100% !important; } .wp-adminify-two-columns > .adminify-field{ width: 49%; flex-basis: 49%; margin-right: 1%; margin-top: -1px; border: 1px solid #eee; box-sizing: border-box; } .wp-adminify-two-columns.aminify-title-width-40 .adminify-title, .aminify-title-width-40 .adminify-title{ width: 40% !important;} .wp-adminify-two-columns.aminify-title-width-40 .adminify-fieldset, .aminify-title-width-40 .adminify-fieldset{ width: calc(60% - 20px) !important;} .wp-adminify-two-columns.aminify-title-width-65 .adminify-title{ width: 65%;} .wp-adminify-two-columns.aminify-title-width-65 .adminify-fieldset{ width: calc(35% - 20px);} .wp-adminify-two-columns .adminify-field-subheading{height:25px;box-sizing: content-box; width: 100%; flex-basis: 100%;} .wp-adminify-white-label-notice-content { background-color: #fff; box-shadow: 0px 0px 50px rgb(0 0 0 / 13%); position: absolute; top: 150px; left: 400px; width: 530px; padding: 32px; padding-bottom: 50px; -webkit-border-radius: 20px; border-radius: 20px; text-align: center; z-index: 2; } .wp-adminify-white-label-notice-logo img { height: 100px; width: 250px; padding: 10px; padding-top: 10px; } .wp-adminify-white-label-notice-content h2 span{ color: #6814cd; text-transform: uppercase; } .wp-adminify-white-label-notice-content em{ font-size: 13px; color: red; } .wp-adminify-white-label-notice .wp-adminify-get-pro{ background-image: -moz-linear-gradient( 0deg, rgb(223,29,198) 0%, rgb(106,20,209) 100%); background-image: -webkit-linear-gradient( 0deg , rgb(223,29,198) 0%, rgb(106,20,209) 100%); background-image: -ms-linear-gradient( 0deg, rgb(223,29,198) 0%, rgb(106,20,209) 100%); border: none; box-shadow: none; color: #fff; cursor: pointer; font-weight: 700; line-height: 35px; padding: 0 15px; text-transform: uppercase; text-decoration: none; display: inline-block; width: 180px; padding: 5px 15px !important; border-radius: 10px; font-size: 15px; font-weight: 800; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out; } .wp-adminify-white-label-notice{ position: absolute !important; top: 0; left: 0; width: 100% !important; height: 100%; background: rgba(200, 200, 200, 0.5); -js-display: flex; display: -webkit-box; display: -webkit-flex; display: -moz-box; display: -ms-flexbox; display: flex; -webkit-box-pack: center; -webkit-justify-content: center; -moz-box-pack: center; -ms-flex-pack: center; justify-content: center;z-index: 1; } .wp-adminify-white-label-notice .wp-adminify-get-pro:hover { color:#fff; background-image: -moz-linear-gradient(0deg, rgb(106, 20, 209) 0%, rgb(223, 29, 198) 100%); background-image: -webkit-linear-gradient( 0deg, rgb(106, 20, 209) 0%, rgb(223, 29, 198) 100%); background-image: -ms-linear-gradient(0deg, rgb(106, 20, 209) 0%, rgb(223, 29, 198) 100%);} .adminify-field-callback a.wp-adminify-rollback-button{font-family:inherit !important;} .wp-adminify-rollback-button.dashicons, .wp-adminify-rollback-button.dashicons-before:before{ width: inherit !important;}</style>';
	}

}
