<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaqStyle' ) ) {

	class WpssoTaqStyle {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! SucomUtil::get_const( 'DOING_AJAX' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_styles' ) );
			}
		}

		public function wp_enqueue_styles() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( empty( $this->p->options['taq_use_style'] ) ) {
				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'taq_use_style option is disabled' );
				}
				return;
			}

			$css_file_ext = SucomUtil::get_const( 'WPSSO_DEV' ) ? 'css' : 'min.css';
			$plugin_version = WpssoTaqConfig::get_version();

			wp_enqueue_style( 'taq', 
				WPSSOTAQ_URLPATH . 'css/taq.' . $css_file_ext, 
					array(), $plugin_version );
		}
	}
}
