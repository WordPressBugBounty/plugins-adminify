<?php

namespace PXLBSAdminify;

use PXLBSAdminify\Libs\Featured;
use PXLBSAdminify\Inc\Admin\Admin;
use PXLBSAdminify\Inc\Classes\Assets;
use PXLBSAdminify\Inc\Classes\IconPicker_Assets;
use PXLBSAdminify\Inc\Classes\Upgrade;
use PXLBSAdminify\Inc\Classes\Feedback;
use PXLBSAdminify\Inc\Admin\AdminSettings;
use PXLBSAdminify\Inc\Classes\Pro_Upgrade;
use PXLBSAdminify\Inc\Classes\Addons_Plugins;
use PXLBSAdminify\Inc\Classes\GoogleFontsLocal;
use PXLBSAdminify\Inc\Classes\Notifications\Notifications;
// No, Direct access Sir !!!
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !class_exists( 'WP_Adminify' ) ) {
    class WP_Adminify {
        const VERSION = PXLBSADMINIFY_VER;

        private static $instance = null;

        private $was_active_before_update = false;

        public function __construct() {
            add_action( 'plugins_loaded', array($this, 'maybe_run_upgrades'), -100 );
            // This should run earlier
            add_action( 'plugins_loaded', array($this, 'maybe_loaded_addons'), -200 );
            // add_action('plugins_loaded', array($this, 'pxlbsadminify_plugins_loaded'), 999);
            add_filter( 'plugin_action_links_' . PXLBSADMINIFY_BASE, array($this, 'plugin_action_links') );
            add_filter( 'network_admin_plugin_action_links_' . PXLBSADMINIFY_BASE, array($this, 'plugin_action_links') );
            // Freemius drops this plugin from `active_plugins` when it updates itself.
            add_filter(
                'upgrader_pre_install',
                array($this, 'pxlbsadminify_remember_active_state'),
                1,
                2
            );
            add_filter(
                'upgrader_post_install',
                array($this, 'pxlbsadminify_restore_active_state'),
                20,
                3
            );
            add_filter( 'admin_body_class', array($this, 'pxlbsadminify_body_class'), 99 );
            // Load textdomain and include files on init for WP 6.7+ compatibility
            // Textdomain must be loaded before files that use translations
            // Priority order: 0=textdomain, 1=include files (registers options), 5=framework setup
            add_action( 'init', array($this, 'pxlbsadminify_load_textdomain'), 0 );
            add_action( 'init', array($this, 'pxlbsadminify_include_files'), 1 );
            add_action( 'init', array($this, 'pxlbsadminify_is_plugin_row_meta'), 1 );
            $is_finished = get_option( 'pxlbsadminify_setup_wizard_ran' );
            if ( $is_finished !== '1' ) {
                if ( apply_filters( 'pxlbsadminify_show_setup_wizard', true ) ) {
                    new \PXLBSAdminify\Inc\Classes\Wizard\Setup_Wizard();
                    set_transient( 'pxlbsadminify_activation_redirect', 1, 30 );
                }
            }
            jltwp_adminify()->add_filter( 'pricing_url', [$this, 'pxlbsadminify_pricing_url'] );
        }

        /**
         * Record whether this plugin was active when its own update starts.
         *
         * @param bool|\WP_Error $response   Install response.
         * @param array          $hook_extra Extra arguments passed to hooked filters.
         *
         * @return bool|\WP_Error
         */
        public function pxlbsadminify_remember_active_state( $response, $hook_extra ) {
            if ( !empty( $hook_extra['plugin'] ) && PXLBSADMINIFY_BASE === $hook_extra['plugin'] ) {
                $this->was_active_before_update = in_array( PXLBSADMINIFY_BASE, (array) get_option( 'active_plugins', array() ), true );
            }
            return $response;
        }

        /**
         * Put this plugin back into `active_plugins` after its own update.
         *
         * The Freemius SDK assumes the free and the premium build never run side by side,
         * so FS_Plugin_Updater::_maybe_update_folder_name() rewrites our `active_plugins`
         * entry to the premium basename once the free package is updated while Adminify Pro
         * is installed - which silently deactivates this plugin. Both packages have to stay
         * active here, so restore the entry the SDK removed.
         *
         * Network activations are unaffected: those live in `active_sitewide_plugins`, which
         * the SDK handler never touches.
         *
         * @param bool|\WP_Error $response   Install response.
         * @param array          $hook_extra Extra arguments passed to hooked filters.
         * @param array          $result     Installation result data.
         *
         * @return bool|\WP_Error
         */
        public function pxlbsadminify_restore_active_state( $response, $hook_extra, $result ) {
            if ( empty( $hook_extra['plugin'] ) || PXLBSADMINIFY_BASE !== $hook_extra['plugin'] || !$this->was_active_before_update ) {
                return $response;
            }
            $active_plugins = (array) get_option( 'active_plugins', array() );
            if ( !in_array( PXLBSADMINIFY_BASE, $active_plugins, true ) ) {
                $active_plugins[] = PXLBSADMINIFY_BASE;
                $active_plugins = array_values( array_unique( $active_plugins ) );
                sort( $active_plugins );
                update_option( 'active_plugins', $active_plugins );
            }
            return $response;
        }

        function pxlbsadminify_pricing_url( $pricing_url ) {
            $pricing_url = 'https://wpadminify.com/pricing';
            return $pricing_url;
        }

        public function pxlbsadminify_is_plugin_row_meta() {
            add_filter(
                'plugin_row_meta',
                array($this, 'pxlbsadminify_plugin_row_meta'),
                10,
                2
            );
            add_filter(
                'network_admin_plugin_row_meta',
                array($this, 'pxlbsadminify_plugin_row_meta'),
                10,
                2
            );
        }

        /**
         * Add Body Class
         */
        public function pxlbsadminify_body_class( $classes ) {
            $classes .= ' wp-adminify ';
            $adminify_ui = AdminSettings::get_instance()->get( 'admin_ui' );
            if ( !empty( $adminify_ui ) ) {
                $classes .= ' adminify-ui';
            }
            if ( is_rtl() ) {
                $classes .= ' adminify-rtl ';
            }
            return $classes;
        }

        /**
         * Plugin action links
         *
         * @param   array $links
         *
         * @return array
         */
        public function plugin_action_links( $links ) {
            $links['settings'] = apply_filters( 'adminify_settings_link', sprintf( '<a class="adminify-plugin-settings" href="%1$s">%2$s</a>', admin_url( 'admin.php?page=wp-adminify-settings' ), __( 'Settings', 'adminify' ) ) );
            $links['pricing'] = apply_filters( 'pxlbsadminify_upgrade_now_link', sprintf( '<a href="%1$s" class="adminify-upgrade-pro" target="_blank" style="color: orangered;font-weight: bold;">%2$s</a>', 'https://wpadminify.com/pricing', __( 'Upgrade Now', 'adminify' ) ) );
            return apply_filters( 'adminify_plugin_row_links', $links );
        }

        public function pxlbsadminify_plugin_row_meta( $plugin_meta, $plugin_file ) {
            if ( PXLBSADMINIFY_BASE === $plugin_file ) {
                $row_meta = array(
                    'docs'       => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url_raw( 'https://wpadminify.com/docs/adminify/getting-started' ), __( 'Docs', 'adminify' ) ),
                    'changelogs' => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url_raw( 'https://wpadminify.com/changelogs/' ), __( 'Changelogs', 'adminify' ) ),
                    'tutorials'  => '<a href="https://www.youtube.com/playlist?list=PLqpMw0NsHXV-EKj9Xm1DMGa6FGniHHly8" aria-label="' . esc_attr( __( 'View Adminify Video Tutorials', 'adminify' ) ) . '" target="_blank">' . __( 'Video Tutorials', 'adminify' ) . '</a>',
                );
                $plugin_meta = array_merge( $plugin_meta, $row_meta );
            }
            return $plugin_meta;
        }

        public function pxlbsadminify_plugins_loaded() {
            self::pxlbsadminify_activation_hook();
        }

        /**
         * Addons Loaded Method
         *
         * @return void
         */
        public function maybe_loaded_addons() {
            do_action( 'pxlbsadminify_plugin_loaded', WP_Adminify::get_instance() );
        }

        public function maybe_run_upgrades() {
            if ( !is_admin() && !current_user_can( 'manage_options' ) ) {
                return;
            }
            $upgrade = new Upgrade();
            if ( $upgrade->if_updates_available() ) {
                $upgrade->run_updates();
            }
        }

        public function pxlbsadminify_include_files() {
            new Assets();
            new IconPicker_Assets();
            new Admin();
            new Featured();
            new Feedback();
            new Notifications();
            new Pro_Upgrade();
            new Addons_Plugins();
            // Initialize Google Fonts Local early to catch option updates
            GoogleFontsLocal::get_instance();
        }

        public function pxlbsadminify_init() {
            // Backup for OLD Database
            $current_version = get_option( 'pxlbsadminify_version' );
            $is_backup = $old_data = get_option( 'pxlbsadminify_settings_backup' );
            if ( version_compare( $current_version, PXLBSADMINIFY_VER, '<' ) && empty( $is_backup ) ) {
                $old_data = get_option( 'pxlbsadminify_settings' );
                update_option( 'pxlbsadminify_settings_backup', $old_data );
            }
        }

        /**
         * Loads the text domain for localization.
         *
         * This function sets up the text domain for the WP Adminify plugin,
         * allowing it to load translation files for the specified locale.
         * It first attempts to load a custom translation file from the WordPress
         * languages directory and then loads the default translation file from
         * the plugin's languages directory.
         *
         * @return void
         */
        public function pxlbsadminify_load_textdomain() {
            $locale = apply_filters( 'plugin_locale', get_locale(), 'adminify' );
            load_textdomain( 'adminify', WP_LANG_DIR . '/adminify/adminify-' . $locale . '.mo' );
            // phpcs:ignore PluginCheck.CodeAnalysis.DiscouragedFunctions.load_plugin_textdomainFound -- retained for non-hosted / older-WP installs.
            load_plugin_textdomain( 'adminify', false, dirname( PXLBSADMINIFY_BASE ) . '/languages/' );
        }

        // Activation Hook
        public static function pxlbsadminify_activation_hook() {
            $current_adminify_version = get_option( 'pxlbsadminify_version', null );
            if ( get_option( 'pxlbsadminify_activation_time' ) === false ) {
                update_option( 'pxlbsadminify_activation_time', strtotime( 'now' ) );
            }
            if ( "dismissed" === get_option( 'pxlbsadminify_plugin_update_info_notice', true ) ) {
                update_option( 'pxlbsadminify_plugin_update_info_notice', '' );
            }
            if ( is_null( $current_adminify_version ) ) {
                update_option( 'pxlbsadminify_version', self::VERSION );
            }
            //database upgrade logic here
            $old_data = get_option( 'pxlbsadminify_settings' );
            update_option( 'pxlbsadminify_settings_backup', $old_data );
            //  Create term_order collumn in terms table, to support post type order
            global $wpdb;
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- direct query required for one-time schema inspection on activation; not cached intentionally.
            $check_term_order_column = $wpdb->query( "SHOW COLUMNS FROM {$wpdb->terms} LIKE 'term_order'" );
            if ( $check_term_order_column == 0 ) {
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange -- direct query required for one-time schema change on activation; not cached intentionally.
                $wpdb->query( "ALTER TABLE {$wpdb->terms} ADD term_order INT( 4 ) NULL DEFAULT '0'" );
            }
        }

        // Deactivation Hook
        public static function pxlbsadminify_deactivation_hook() {
            delete_option( 'pxlbsadminify_activation_time' );
            delete_option( 'pxlbsadminify_customizer_flush_url' );
        }

        /**
         * Returns the singleton instance of the class.
         */
        public static function get_instance() {
            if ( !isset( self::$instance ) && !self::$instance instanceof WP_Adminify ) {
                self::$instance = new WP_Adminify();
                self::$instance->pxlbsadminify_init();
            }
            return self::$instance;
        }

    }

    WP_Adminify::get_instance();
}