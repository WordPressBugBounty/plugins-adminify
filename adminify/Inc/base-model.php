<?php

namespace WPAdminify\Inc;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base_Model extends Base_Data {

	protected function init( $data ) {
		$this->set( array_merge( $this->get_defaults(), $data ) );
	}

	public function get_default_field( $field ) {
		$fields = $this->get_defaults();
		return isset( $fields[ $field ] ) ? $fields[ $field ] : null;
	}

	public function __construct( $data ) {
		if ( $data ) {
			$this->init( $data );
		}
	}
}
