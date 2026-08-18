<?php
/**
 * Upgrade routine for v4.3.0 — Font Awesome icon values migrated to Dashicons.
 *
 * The Dashboard Widget and Pro Admin Page icon fields now use the shared WP
 * Adminify icon picker (Dashicons, Simple Line Icons, Custom Icons), which no
 * longer offers Font Awesome. Values saved by the old Font Awesome modal would
 * render as a blank square, so they are rewritten to the closest Dashicon here.
 *
 * Mapping is best-effort: anything without a sensible equivalent falls back to
 * `dashicons-admin-generic` rather than being left broken or emptied.
 *
 * This file is included by PXLBSAdminify\Inc\Classes\Upgrade::run_updates().
 *
 * @package Adminify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Font Awesome class (without the style prefix) => Dashicons class.
 *
 * @return array<string, string>
 */
function pxlbsadminify_fa_to_dashicons_map() {
	return array(
		'fa-address-book'     => 'dashicons dashicons-id',
		'fa-address-card'     => 'dashicons dashicons-id-alt',
		'fa-arrow-down'       => 'dashicons dashicons-arrow-down-alt',
		'fa-arrow-left'       => 'dashicons dashicons-arrow-left-alt',
		'fa-arrow-right'      => 'dashicons dashicons-arrow-right-alt',
		'fa-arrow-up'         => 'dashicons dashicons-arrow-up-alt',
		'fa-bars'             => 'dashicons dashicons-menu',
		'fa-bell'             => 'dashicons dashicons-bell',
		'fa-bolt'             => 'dashicons dashicons-performance',
		'fa-book'             => 'dashicons dashicons-book',
		'fa-briefcase'        => 'dashicons dashicons-portfolio',
		'fa-brush'            => 'dashicons dashicons-art',
		'fa-bug'              => 'dashicons dashicons-warning',
		'fa-business-time'    => 'dashicons dashicons-clock',
		'fa-calendar'         => 'dashicons dashicons-calendar-alt',
		'fa-calendar-alt'     => 'dashicons dashicons-calendar-alt',
		'fa-camera'           => 'dashicons dashicons-camera',
		'fa-cart-plus'        => 'dashicons dashicons-cart',
		'fa-chart-area'       => 'dashicons dashicons-chart-area',
		'fa-chart-bar'        => 'dashicons dashicons-chart-bar',
		'fa-chart-line'       => 'dashicons dashicons-chart-line',
		'fa-chart-pie'        => 'dashicons dashicons-chart-pie',
		'fa-check'            => 'dashicons dashicons-yes',
		'fa-check-circle'     => 'dashicons dashicons-yes-alt',
		'fa-clock'            => 'dashicons dashicons-clock',
		'fa-clone'            => 'dashicons dashicons-admin-page',
		'fa-code'             => 'dashicons dashicons-editor-code',
		'fa-cog'              => 'dashicons dashicons-admin-generic',
		'fa-cogs'             => 'dashicons dashicons-admin-settings',
		'fa-comment'          => 'dashicons dashicons-admin-comments',
		'fa-comments'         => 'dashicons dashicons-admin-comments',
		'fa-copyright'        => 'dashicons dashicons-admin-site',
		'fa-database'         => 'dashicons dashicons-database',
		'fa-desktop'          => 'dashicons dashicons-desktop',
		'fa-download'         => 'dashicons dashicons-download',
		'fa-edit'             => 'dashicons dashicons-edit',
		'fa-envelope'         => 'dashicons dashicons-email',
		'fa-exclamation'      => 'dashicons dashicons-warning',
		'fa-eye'              => 'dashicons dashicons-visibility',
		'fa-file'             => 'dashicons dashicons-media-default',
		'fa-file-alt'         => 'dashicons dashicons-media-text',
		'fa-fill-drip'        => 'dashicons dashicons-art',
		'fa-filter'           => 'dashicons dashicons-filter',
		'fa-flag'             => 'dashicons dashicons-flag',
		'fa-folder'           => 'dashicons dashicons-portfolio',
		'fa-gear'             => 'dashicons dashicons-admin-generic',
		'fa-gears'            => 'dashicons dashicons-admin-settings',
		'fa-globe'            => 'dashicons dashicons-admin-site',
		'fa-heart'            => 'dashicons dashicons-heart',
		'fa-home'             => 'dashicons dashicons-admin-home',
		'fa-image'            => 'dashicons dashicons-format-image',
		'fa-images'           => 'dashicons dashicons-format-gallery',
		'fa-info'             => 'dashicons dashicons-info',
		'fa-info-circle'      => 'dashicons dashicons-info',
		'fa-key'              => 'dashicons dashicons-admin-network',
		'fa-layer-group'      => 'dashicons dashicons-layout',
		'fa-link'             => 'dashicons dashicons-admin-links',
		'fa-list'             => 'dashicons dashicons-editor-ul',
		'fa-lock'             => 'dashicons dashicons-lock',
		'fa-map-marker'       => 'dashicons dashicons-location',
		'fa-map-marker-alt'   => 'dashicons dashicons-location-alt',
		'fa-microphone'       => 'dashicons dashicons-microphone',
		'fa-mobile'           => 'dashicons dashicons-smartphone',
		'fa-music'            => 'dashicons dashicons-format-audio',
		'fa-newspaper'        => 'dashicons dashicons-admin-post',
		'fa-outdent'          => 'dashicons dashicons-editor-outdent',
		'fa-paint-brush'      => 'dashicons dashicons-art',
		'fa-paperclip'        => 'dashicons dashicons-paperclip',
		'fa-pencil-alt'       => 'dashicons dashicons-edit',
		'fa-phone'            => 'dashicons dashicons-phone',
		'fa-play'             => 'dashicons dashicons-controls-play',
		'fa-plug'             => 'dashicons dashicons-admin-plugins',
		'fa-plus'             => 'dashicons dashicons-plus',
		'fa-plus-circle'      => 'dashicons dashicons-plus-alt',
		'fa-question-circle'  => 'dashicons dashicons-editor-help',
		'fa-quote-left'       => 'dashicons dashicons-format-quote',
		'fa-random'           => 'dashicons dashicons-randomize',
		'fa-redo'             => 'dashicons dashicons-update',
		'fa-rocket'           => 'dashicons dashicons-performance',
		'fa-rss'              => 'dashicons dashicons-rss',
		'fa-search'           => 'dashicons dashicons-search',
		'fa-server'           => 'dashicons dashicons-database',
		'fa-share'            => 'dashicons dashicons-share',
		'fa-shield-alt'       => 'dashicons dashicons-shield',
		'fa-shopping-cart'    => 'dashicons dashicons-cart',
		'fa-sign-out-alt'     => 'dashicons dashicons-exit',
		'fa-star'             => 'dashicons dashicons-star-filled',
		'fa-sync'             => 'dashicons dashicons-update',
		'fa-tag'              => 'dashicons dashicons-tag',
		'fa-tags'             => 'dashicons dashicons-tag',
		'fa-times'            => 'dashicons dashicons-no-alt',
		'fa-tools'            => 'dashicons dashicons-admin-tools',
		'fa-trash'            => 'dashicons dashicons-trash',
		'fa-trash-alt'        => 'dashicons dashicons-trash',
		'fa-upload'           => 'dashicons dashicons-upload',
		'fa-user'             => 'dashicons dashicons-admin-users',
		'fa-users'            => 'dashicons dashicons-groups',
		'fa-video'            => 'dashicons dashicons-format-video',
		'fa-wordpress'        => 'dashicons dashicons-wordpress',
		'fa-wrench'           => 'dashicons dashicons-admin-tools',
	);
}

/**
 * Convert a single stored icon value.
 *
 * @param mixed $value Stored icon value.
 * @return mixed Converted value, or the original when it is not a Font Awesome class.
 */
function pxlbsadminify_convert_fa_icon_value( $value ) {

	if ( ! is_string( $value ) || '' === $value ) {
		return $value;
	}

	// Only touch Font Awesome classes: "fa fa-gear", "fas fa-rocket", "far fa-clone".
	if ( ! preg_match( '/(?:^|\s)fa[bsrl]?\s+fa-([a-z0-9-]+)/i', $value, $matches ) ) {
		return $value;
	}

	$map  = pxlbsadminify_fa_to_dashicons_map();
	$slug = 'fa-' . strtolower( $matches[1] );

	return isset( $map[ $slug ] ) ? $map[ $slug ] : 'dashicons dashicons-admin-generic';
}

/**
 * Walk an array and convert every Font Awesome icon value found in it.
 *
 * @param mixed $data    Data to walk.
 * @param bool  $changed Set to true when anything was rewritten.
 * @return mixed
 */
function pxlbsadminify_convert_fa_icons_deep( $data, &$changed ) {

	if ( is_array( $data ) ) {
		foreach ( $data as $key => $item ) {
			$data[ $key ] = pxlbsadminify_convert_fa_icons_deep( $item, $changed );
		}

		return $data;
	}

	$converted = pxlbsadminify_convert_fa_icon_value( $data );

	if ( $converted !== $data ) {
		$changed = true;
	}

	return $converted;
}

/**
 * Run the one-time Font Awesome to Dashicons migration.
 *
 * Idempotent: guarded by the "pxlbsadminify_fa_icons_migrated" flag. Upgrade::run_updates()
 * does not advance the stored version, so every routine must guard itself.
 *
 * @return void
 */
function pxlbsadminify_migrate_fa_icons_to_dashicons() {

	if ( get_option( 'pxlbsadminify_fa_icons_migrated' ) ) {
		return;
	}

	// Dashboard widget icons (and any Font Awesome markup in widget bodies is left
	// alone — only stored icon field values are converted, and the deep walk only
	// rewrites values that are entirely a Font Awesome class).
	$widgets = get_option( 'pxlbsadminify_dasboard_widgets' );

	if ( ! empty( $widgets ) && is_array( $widgets ) ) {
		$changed = false;
		$updated = pxlbsadminify_convert_fa_icons_deep( $widgets, $changed );

		if ( $changed ) {
			update_option( 'pxlbsadminify_dasboard_widgets', $updated );
		}
	}

	// Pro admin page menu icons.
	$admin_pages = get_posts(
		array(
			'post_type'        => 'adminify_admin_page',
			'post_status'      => 'any',
			'numberposts'      => -1,
			'fields'           => 'ids',
			'suppress_filters' => false,
		)
	);

	if ( ! empty( $admin_pages ) ) {
		foreach ( $admin_pages as $page_id ) {
			$icon = get_post_meta( $page_id, 'pxlbsadminify_menu_icon', true );

			if ( empty( $icon ) || ! is_string( $icon ) ) {
				continue;
			}

			$converted = pxlbsadminify_convert_fa_icon_value( $icon );

			if ( $converted !== $icon ) {
				update_post_meta( $page_id, 'pxlbsadminify_menu_icon', $converted );
			}
		}
	}

	update_option( 'pxlbsadminify_fa_icons_migrated', true );
}

pxlbsadminify_migrate_fa_icons_to_dashicons();
