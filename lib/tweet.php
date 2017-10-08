<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2017 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaqTweet' ) ) {

	class WpssoTaqTweet {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}
		}

		public static function get_max_len( $atts = array() ) { 
			$wpsso =& Wpsso::get_instance();

			if ( ! isset( $atts['via'] ) ) {
				if ( ! empty( $wpsso->options['taq_add_via'] ) ) {
					$atts['via'] = preg_replace( '/^@/', '', 
						SucomUtil::get_locale_opt( 'tc_site', $wpsso->options ) );
				}
			}

			$short_len = 23;			// defined by twitter as being the shortest url length
			$site_len = empty( $atts['via'] ) ? 
				0 : strlen( $atts['via'] ) + 6;	// "via" word, 2 spaces, and the "@", makes 6 characters

			return ( 140 - $short_len - $site_len );
		}
	}
}

?>
