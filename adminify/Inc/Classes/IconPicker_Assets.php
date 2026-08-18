<?php

namespace PXLBSAdminify\Inc\Classes;

use PXLBSAdminify\Inc\Utils;
use PXLBSAdminify\Inc\Admin\AdminSettings;

// no direct access allowed
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Assets for the shared WP Adminify icon picker outside the Menu Editor.
 *
 * The Menu Editor loads the picker itself (Inc/Modules/MenuEditor/MenuEditorAssets.php).
 * This class covers the other screens that render the `adminify_icon` options-framework
 * field (Inc/Fields/adminify_icon.php): the Dashboard Widget settings page and the Pro
 * Admin Pages editor.
 *
 * @package PXLBSAdminify
 */
class IconPicker_Assets
{

	/**
	 * Screens that render an `adminify_icon` field.
	 *
	 * Kept in one place so it stays in step with the two field declarations in
	 * DashboardWidget_Setttings and AdminPages_MetaBoxes.
	 */
	const OPTIONS_PAGE_SLUG = 'adminify-dashboard-widgets';
	const POST_TYPE         = 'adminify_admin_page';

	public function __construct()
	{
		// Priority 110: Assets::pxlbsadminify_admin_scripts() registers the
		// adminify-icon-picker handles at 100, so they exist by the time we enqueue.
		add_action('admin_enqueue_scripts', [$this, 'enqueue'], 110);
	}

	/**
	 * Whether the current screen renders an `adminify_icon` field.
	 *
	 * @param \WP_Screen|null $screen Current screen.
	 * @return bool
	 */
	private function is_icon_field_screen($screen)
	{
		if (empty($screen->id)) {
			return false;
		}

		// Framework options pages are suffixed with their menu slug.
		if (self::OPTIONS_PAGE_SLUG === substr($screen->id, -strlen(self::OPTIONS_PAGE_SLUG))) {
			return true;
		}

		// Pro admin page editor (metabox lives on the post edit screen only).
		if ('post' === $screen->base && self::POST_TYPE === $screen->post_type) {
			return true;
		}

		return false;
	}

	/**
	 * Enqueue the picker and hand it the settings it needs.
	 *
	 * @return void
	 */
	public function enqueue()
	{
		$screen = get_current_screen();

		if (!$this->is_icon_field_screen($screen)) {
			return;
		}

		// The Menu Editor already loads and localises the picker on its own screen;
		// enqueueing twice is a no-op, but localising twice would overwrite.
		wp_enqueue_style('dashicons');
		wp_enqueue_style('adminify-icon-picker');
		wp_enqueue_style('adminify-simple-line-icons');
		wp_enqueue_script('adminify-icon-picker');

		// Loaded here rather than in the constructor so unrelated admin pages
		// don't pay for it.
		$options = (array) AdminSettings::get_instance()->get();

		wp_localize_script(
			'adminify-icon-picker',
			'PXLBSADMINIFY_ICON_PICKER',
			[
				'is_elementor_active' => Utils::is_plugin_active('elementor/elementor.php'),
				'ajax_url'            => admin_url('admin-ajax.php'),
				// Same nonce the Menu Editor issues: the custom-icon list and upload
				// endpoints (MenuEditor::load_custom_icons_callback / file_upload_callback)
				// verify against this action.
				'security'            => wp_create_nonce('pxlbsadminify-menu-editor-security-nonce'),
				'max_upload_size'     => size_format(wp_max_upload_size()),
				// Uploaded icons land in the uploads folder; the picker builds their
				// URL from this base after an upload finishes.
				'baseurl'             => wp_upload_dir()['baseurl'],
				'icon_picker_logo'    => PXLBSADMINIFY_ASSETS_IMAGE . 'logos/menu-icon.svg',
				// Libraries whose stylesheet the user switched off in the Assets
				// Manager are dropped from the picker, as the Menu Editor does.
				'assets_manager'      => !empty($options['adminify_assets']) ? $options['adminify_assets'] : [],
			]
		);

		// Boots the picker and hands it the shared icon libraries. The picker only
		// ships Dashicons of its own; Simple Line Icons come from
		// dev/shared/icon-libraries.js, the same list the Menu Editor uses.
		wp_enqueue_script(
			'adminify-icon-picker-init',
			PXLBSADMINIFY_ASSETS . 'admin/js/icon-picker-init' . Utils::assets_ext('.js'),
			['jquery', 'adminify-icon-picker'],
			PXLBSADMINIFY_VER,
			true
		);
	}
}
