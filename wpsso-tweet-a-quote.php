<?php
/*
 * Plugin Name: WPSSO Tweet a Quote (WPSSO TAQ)
 * Plugin Slug: wpsso-tweet-a-quote
 * Text Domain: wpsso-tweet-a-quote
 * Domain Path: /languages
 * Plugin URI: https://surniaulula.com/extend/plugins/wpsso-tweet-a-quote/
 * Assets URI: https://surniaulula.github.io/wpsso-tweet-a-quote/assets/
 * Author: JS Morisset
 * Author URI: https://surniaulula.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: WPSSO extension to easily add Twitter-style quoted text with a Tweet share link in your post and page content.
 * Requires At Least: 3.7
 * Tested Up To: 4.6.1
 * Version: 1.0.0-dev1
 * 
 * Version Numbering Scheme: {major}.{minor}.{bugfix}-{stage}{level}
 *
 *	{major}		Major code changes / re-writes or significant feature changes.
 *	{minor}		New features / options were added or improved.
 *	{bugfix}	Bugfixes or minor improvements.
 *	{stage}{level}	dev < a (alpha) < b (beta) < rc (release candidate) < # (production).
 *
 * See PHP's version_compare() documentation at http://php.net/manual/en/function.version-compare.php.
 * 
 * Copyright 2016 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoTaq' ) ) {

	class WpssoTaq {

		public $p;			// Wpsso
		public $reg;			// WpssoTaqRegister
		public $filters;		// WpssoTaqFilters
		public $tweet;			// WpssoTaqTweet

		private static $instance = null;
		private static $have_min = true;

		public static function &get_instance() {
			if ( self::$instance === null )
				self::$instance = new self;
			return self::$instance;
		}

		public function __construct() {

			require_once ( dirname( __FILE__ ).'/lib/config.php' );
			WpssoTaqConfig::set_constants( __FILE__ );
			WpssoTaqConfig::require_libs( __FILE__ );	// includes the register.php class library
			$this->reg = new WpssoTaqRegister();		// activate, deactivate, uninstall hooks

			if ( is_admin() ) {
				load_plugin_textdomain( 'wpsso-tweet-a-quote', false, 'wpsso-tweet-a-quote/languages/' );
				add_action( 'admin_init', array( &$this, 'required_check' ) );
			}

			add_filter( 'wpsso_get_config', array( &$this, 'wpsso_get_config' ), 30, 2 );
			add_action( 'wpsso_init_options', array( &$this, 'wpsso_init_options' ), 10 );
			add_action( 'wpsso_init_objects', array( &$this, 'wpsso_init_objects' ), 10 );
			add_action( 'wpsso_init_plugin', array( &$this, 'wpsso_init_plugin' ), 10 );
		}

		public function required_check() {
			if ( ! class_exists( 'Wpsso' ) )
				add_action( 'all_admin_notices', array( &$this, 'required_notice' ) );
		}

		public static function required_notice( $deactivate = false ) {
			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];

			if ( $deactivate === true ) {
				require_once( ABSPATH.'wp-admin/includes/plugin.php' );
				deactivate_plugins( $info['base'] );

				wp_die( '<p>'.sprintf( __( '%1$s is an extension for the %2$s plugin &mdash; please install and activate the %3$s plugin before activating the %4$s extension.', 'wpsso-tweet-a-quote' ), $info['name'], $info['req']['name'], $info['req']['short'], $info['short'] ).'</p>' );

			} else echo '<div class="notice notice-error error"><p>'.
				sprintf( __( 'The %1$s extension requires the %2$s plugin &mdash; please install and activate the %3$s plugin.',
					'wpsso-tweet-a-quote' ), $info['name'], $info['req']['name'], $info['req']['short'] ).'</p></div>';
		}

		public function wpsso_get_config( $cf, $plugin_version ) {
			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];

			if ( version_compare( $plugin_version, $info['req']['min_version'], '<' ) ) {
				self::$have_min = false;
				return $cf;
			}

			return SucomUtil::array_merge_recursive_distinct( $cf, WpssoTaqConfig::$cf );
		}

		public function wpsso_init_options() {
			if ( method_exists( 'Wpsso', 'get_instance' ) )
				$this->p =& Wpsso::get_instance();
			else $this->p =& $GLOBALS['wpsso'];

			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			if ( self::$have_min === false )
				return;		// stop here

			$this->p->is_avail['taq'] = true;
		}

		public function wpsso_init_objects() {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			if ( self::$have_min === false )
				return;		// stop here

			$this->filters = new WpssoTaqFilters( $this->p );
			$this->tweet = new WpssoTaqTweet( $this->p );
		}

		public function wpsso_init_plugin() {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			if ( self::$have_min === false )
				return $this->min_version_notice();
		}

		private function min_version_notice() {
			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];
			$wpsso_version = WpssoConfig::get_version();

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( $info['name'].' requires '.$info['req']['short'].' v'.
					$info['req']['min_version'].' or newer ('.$wpsso_version.' installed)' );
			}

			if ( is_admin() ) {
				$this->p->notice->err( sprintf( __( 'The %1$s extension v%2$s requires %3$s v%4$s or newer (v%5$s currently installed).',
					'wpsso-tweet-a-quote' ), $info['name'], $info['version'], $info['req']['short'],
						$info['req']['min_version'], $wpsso_version ) );
			}
		}
	}

        global $wpssotaq;
	$wpssotaq =& WpssoTaq::get_instance();
}

?>