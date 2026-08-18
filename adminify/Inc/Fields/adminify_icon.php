<?php
/**
 * Field: adminify_icon
 *
 * Options-framework field that renders the shared WP Adminify icon picker
 * (assets/vendors/adminify-icon-picker) — the same picker the Menu Editor uses —
 * instead of the framework's built-in Font Awesome modal.
 *
 * The framework resolves a field type by looking for a global
 * `ADMINIFY_Field_{type}` class before falling back to its own
 * `fields/{type}/{type}.php`, so declaring this class here adds the type without
 * modifying Libs/adminify-framework.
 *
 * Two value shapes are stored:
 *   - an icon class, e.g. "dashicons dashicons-chart-bar"
 *   - a custom (uploaded) icon as "{attachment_id},{url}"
 *
 * @package Adminify
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'ADMINIFY_Fields' ) ) {
	return;
}

if ( ! class_exists( 'ADMINIFY_Field_adminify_icon' ) ) {

	class ADMINIFY_Field_adminify_icon extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Whether a stored value is a custom uploaded icon ("{id},{url}").
		 *
		 * @param string $value Stored field value.
		 * @return bool
		 */
		public static function is_custom_icon( $value ) {
			return ( is_string( $value ) && (bool) preg_match( '/^[^,]*,\s*https?:\/\//i', $value ) );
		}

		/**
		 * URL part of a custom icon value.
		 *
		 * @param string $value Stored field value.
		 * @return string
		 */
		public static function custom_icon_url( $value ) {
			$parts = explode( ',', (string) $value, 2 );

			return isset( $parts[1] ) ? trim( $parts[1] ) : '';
		}

		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'button_title' => esc_html__( 'Icon Library', 'adminify' ),
					'remove_title' => esc_html__( 'None', 'adminify' ),
				)
			);

			echo wp_kses_post( $this->field_before() );

			$value  = (string) $this->value;
			$custom = self::is_custom_icon( $value );

			// Markup mirrors the Menu Editor's picker wrapper: the picker binds
			// delegated handlers to .select-icon / .icon-none and resolves the
			// owning field with .closest('.icon-picker-wrap').
			echo '<div class="icon-picker-wrap adminify-icon-picker-input icon-select-button is-clickable is-pulled-left">';
			echo '<ul class="icon-picker">';
			echo '<li class="icon-none" title="' . esc_attr( $args['remove_title'] ) . '"><i class="dashicons dashicons-dismiss"></i></li>';
			echo '<li class="select-icon' . ( $custom ? ' custom-icon' : '' ) . '" title="' . esc_attr( $args['button_title'] ) . '">';

			if ( $custom ) {
				echo '<i class=""><img width="24" height="24" src="' . esc_url( self::custom_icon_url( $value ) ) . '" alt="" /></i>';
			} elseif ( '' !== $value ) {
				echo '<i class="' . esc_attr( $value ) . '"></i>';
			} else {
				echo '<i>' . esc_html__( 'Select Icon', 'adminify' ) . '</i>';
			}

			echo '</li>';
			echo '</ul>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $value ) . '" class="adminify-icon-value"' . $this->field_attributes() . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- field_attributes() escapes each attribute via esc_attr().
			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
