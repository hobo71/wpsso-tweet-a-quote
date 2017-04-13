<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2016-2017 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoTaqConfig' ) ) {

	class WpssoTaqConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssotaq' => array(
					'version' => '1.1.7-b.1',		// plugin version
					'opt_version' => '10',		// increment when changing default options
					'short' => 'WPSSO TAQ',		// short plugin name
					'name' => 'WPSSO Tweet a Quote (WPSSO TAQ)',
					'desc' => 'WPSSO extension to add CSS Twitter-style quoted text with a Tweet share link to post and page content (uses easily customized CSS).',
					'slug' => 'wpsso-tweet-a-quote',
					'base' => 'wpsso-tweet-a-quote/wpsso-tweet-a-quote.php',
					'update_auth' => '',
					'text_domain' => 'wpsso-tweet-a-quote',
					'domain_path' => '/languages',
					'req' => array(
						'short' => 'WPSSO',
						'name' => 'WordPress Social Sharing Optimization (WPSSO)',
						'min_version' => '3.40.13-b.1',
					),
					'img' => array(
						'icons' => array(
							'low' => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						// submenu items must have unique keys
						'submenu' => array (
							'taq-general' => 'Tweet a Quote',
						),
						'shortcode' => array(
							'taq' => 'Tweet a Quote Shortcode',
						),
						'gpl' => array(
						),
						'pro' => array(
						),
					),
				),
			),
		);

		public static function get_version() { 
			return self::$cf['plugin']['wpssotaq']['version'];
		}

		public static function set_constants( $plugin_filepath ) { 
			define( 'WPSSOTAQ_FILEPATH', $plugin_filepath );						
			define( 'WPSSOTAQ_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOTAQ_PLUGINSLUG', self::$cf['plugin']['wpssotaq']['slug'] );	// wpsso-tweet-a-quote
			define( 'WPSSOTAQ_PLUGINBASE', self::$cf['plugin']['wpssotaq']['base'] );	// wpsso-tweet-a-quote/wpsso-tweet-a-quote.php
			define( 'WPSSOTAQ_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
			self::set_variable_constants();
		}

		public static function set_variable_constants() { 
			foreach ( self::get_variable_constants() as $name => $value )
				if ( ! defined( $name ) )
					define( $name, $value );
		}

		public static function get_variable_constants() { 
			$var_const = array();
			$var_const['WPSSOTAQ_TWEET_SHORTCODE_NAME'] = 'taq';
			$var_const['WPSSOTAQ_TWEET_SHORTCODE_CLASS'] = 'wpsso_taq';

			foreach ( $var_const as $name => $value )
				if ( defined( $name ) )
					$var_const[$name] = constant( $name );	// inherit existing values
			return $var_const;
		}

		public static function require_libs( $plugin_filepath ) {
			require_once WPSSOTAQ_PLUGINDIR.'lib/register.php';
			require_once WPSSOTAQ_PLUGINDIR.'lib/filters.php';
			require_once WPSSOTAQ_PLUGINDIR.'lib/tweet.php';	// static methods

			add_filter( 'wpssotaq_load_lib', array( 'WpssoTaqConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( $ret === false && ! empty( $filespec ) ) {
				$filepath = WPSSOTAQ_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once $filepath;
					if ( empty( $classname ) )
						return SucomUtil::sanitize_classname( 'wpssotaq'.$filespec, false );	// $underscore = false
					else return $classname;
				}
			}
			return $ret;
		}
	}
}

?>
