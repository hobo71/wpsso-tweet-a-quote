<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2016-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaqConfig' ) ) {

	class WpssoTaqConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssotaq' => array(			// Plugin acronym.
					'version' => '1.3.0-b.1',		// Plugin version.
					'opt_version' => '10',		// Increment when changing default option values.
					'short' => 'WPSSO TAQ',		// Short plugin name.
					'name' => 'WPSSO Tweet a Quote',
					'desc' => 'WPSSO Core add-on to provide Twitter-style quoted text for your content, with a convenient Tweet share link and customizable CSS.',
					'slug' => 'wpsso-tweet-a-quote',
					'base' => 'wpsso-tweet-a-quote/wpsso-tweet-a-quote.php',
					'update_auth' => '',
					'text_domain' => 'wpsso-tweet-a-quote',
					'domain_path' => '/languages',
					'req' => array(
						'short' => 'WPSSO Core',
						'name' => 'WPSSO Core',
						'min_version' => '4.7.0-b.1',
					),
					'img' => array(
						'icons' => array(
							'low' => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						'submenu' => array(	// Note that submenu elements must have unique keys.
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
			'opt' => array(				// options
				'defaults' => array(
					'taq_add_via' => 1,
					'taq_rec_author' => 1,
					'taq_link_text' => 0,
					'taq_add_button' => 1,
					'taq_use_style' => 1,
					'taq_use_script' => 1,
					'taq_popup_width' => 580,
					'taq_popup_height' => 290,
				),
			),
		);

		public static function get_version( $add_slug = false ) {
			$ext = 'wpssotaq';
			$info =& self::$cf['plugin'][$ext];
			return $add_slug ? $info['slug'].'-'.$info['version'] : $info['version'];
		}

		public static function set_constants( $plugin_filepath ) { 

			if ( defined( 'WPSSOTAQ_VERSION' ) ) {	// Define constants only once.
				return;
			}

			define( 'WPSSOTAQ_VERSION', self::$cf['plugin']['wpssotaq']['version'] );						
			define( 'WPSSOTAQ_FILEPATH', $plugin_filepath );						
			define( 'WPSSOTAQ_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOTAQ_PLUGINSLUG', self::$cf['plugin']['wpssotaq']['slug'] );	// wpsso-tweet-a-quote
			define( 'WPSSOTAQ_PLUGINBASE', self::$cf['plugin']['wpssotaq']['base'] );	// wpsso-tweet-a-quote/wpsso-tweet-a-quote.php
			define( 'WPSSOTAQ_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );

			self::set_variable_constants();
		}

		public static function set_variable_constants( $var_const = null ) {

			if ( null === $var_const ) {
				$var_const = self::get_variable_constants();
			}

			foreach ( $var_const as $name => $value ) {
				if ( ! defined( $name ) ) {
					define( $name, $value );
				}
			}
		}

		public static function get_variable_constants() { 

			$var_const = array();

			$var_const['WPSSOTAQ_TWEET_SHORTCODE_NAME'] = 'taq';
			$var_const['WPSSOTAQ_TWEET_SHORTCODE_CLASS'] = 'wpsso_taq';

			foreach ( $var_const as $name => $value ) {
				if ( defined( $name ) ) {
					$var_const[$name] = constant( $name );	// inherit existing values
				}
			}

			return $var_const;
		}

		public static function require_libs( $plugin_filepath ) {

			require_once WPSSOTAQ_PLUGINDIR.'lib/filters.php';
			require_once WPSSOTAQ_PLUGINDIR.'lib/register.php';
			require_once WPSSOTAQ_PLUGINDIR.'lib/script.php';
			require_once WPSSOTAQ_PLUGINDIR.'lib/style.php';
			require_once WPSSOTAQ_PLUGINDIR.'lib/tweet.php';

			add_filter( 'wpssotaq_load_lib', array( 'WpssoTaqConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( false === $ret && ! empty( $filespec ) ) {
				$filepath = WPSSOTAQ_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once $filepath;
					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpssotaq'.$filespec, false );	// $underscore = false
					} else {
						return $classname;
					}
				}
			}
			return $ret;
		}
	}
}
