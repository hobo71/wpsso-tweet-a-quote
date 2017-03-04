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
					'version' => '1.1.4-rc3',		// plugin version
					'opt_version' => '10',		// increment when changing default options
					'short' => 'WPSSO TAQ',		// short plugin name
					'name' => 'WPSSO Tweet a Quote (WPSSO TAQ)',
					'desc' => 'WPSSO extension to easily add Twitter-style quoted text &mdash; with a Tweet share link &mdash; in your post and page content.',
					'slug' => 'wpsso-tweet-a-quote',
					'base' => 'wpsso-tweet-a-quote/wpsso-tweet-a-quote.php',
					'update_auth' => '',
					'text_domain' => 'wpsso-tweet-a-quote',
					'domain_path' => '/languages',
					'req' => array(
						'short' => 'WPSSO',
						'name' => 'WordPress Social Sharing Optimization (WPSSO)',
						'min_version' => '3.40.2-rc3',
					),
					'img' => array(
						'icon_small' => 'images/icon-128x128.png',
						'icon_medium' => 'images/icon-256x256.png',
					),
					'url' => array(
						// wordpress
						'download' => 'https://wordpress.org/plugins/wpsso-tweet-a-quote/',
						'forum' => 'https://wordpress.org/support/plugin/wpsso-tweet-a-quote',
						'review' => 'https://wordpress.org/support/plugin/wpsso-tweet-a-quote/reviews/?rate=5#new-post',
						// github
						'readme_txt' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-tweet-a-quote/master/readme.txt',
						// wpsso
						'latest' => 'https://wpsso.com/extend/plugins/wpsso-tweet-a-quote/latest/',
						'update' => 'https://wpsso.com/extend/plugins/wpsso-tweet-a-quote/update/',
						'changelog' => 'https://wpsso.com/extend/plugins/wpsso-tweet-a-quote/changelog/',
						'codex' => 'https://wpsso.com/codex/plugins/wpsso-tweet-a-quote/',
						'faq' => '',
						'notes' => '',
						'support' => '',
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
			require_once( WPSSOTAQ_PLUGINDIR.'lib/register.php' );
			require_once( WPSSOTAQ_PLUGINDIR.'lib/filters.php' );
			require_once( WPSSOTAQ_PLUGINDIR.'lib/tweet.php' );	// static methods

			add_filter( 'wpssotaq_load_lib', array( 'WpssoTaqConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( $ret === false && ! empty( $filespec ) ) {
				$filepath = WPSSOTAQ_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once( $filepath );
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
