<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
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

			if ( ! isset( $atts[ 'via' ] ) ) {
				if ( ! empty( $wpsso->options[ 'taq_add_via' ] ) ) {
					$atts[ 'via' ] = preg_replace( '/^@/', '', SucomUtil::get_key_value( 'tc_site', $wpsso->options ) );
				}
			}

			/**
			 * Defined by twitter as being the shortest URL length.
			 */
			$short_url_len = 23;

			/**
			 * "via" word, plus 2 spaces, and the "@" character, makes 6.
			 */
			$site_via_len = empty( $atts[ 'via' ] ) ? 0 : strlen( $atts[ 'via' ] ) + 6;

			/**
			 * Changed from 140 to 280 on 2017/11/17.
			 */
			$tweet_max_len = 280;

			return ( $tweet_max_len - $short_url_len - $site_via_len );
		}
	}
}
