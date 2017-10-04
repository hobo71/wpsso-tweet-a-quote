<?php
/*
 * Plugin Name: WPSSO Tweet a Quote
 * Plugin Slug: wpsso-tweet-a-quote
 * Text Domain: wpsso-tweet-a-quote
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-tweet-a-quote/
 * Assets URI: https://surniaulula.github.io/wpsso-tweet-a-quote/assets/
 * Author: JS Morisset
 * Author URI: https://surniaulula.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: WPSSO extension to add CSS Twitter-style quoted text with a Tweet share link to post and page content (uses easily customized CSS).
 * Requires At Least: 3.7
 * Tested Up To: 4.8.2
 * Requires PHP: 5.3
 * Version: 1.1.11-dev.2
 * 
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *	{major}		Major structural code changes / re-writes or incompatible API changes.
 *	{minor}		New functionality was added or improved in a backwards-compatible manner.
 *	{bugfix}	Backwards-compatible bug fixes or small improvements.
 *	{stage}.{level}	Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 * 
 * Copyright 2016-2017 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaq' ) ) {

	class WpssoTaq {

		public $p;			// Wpsso
		public $reg;			// WpssoTaqRegister
		public $filters;		// WpssoTaqFilters
		public $tweet;			// WpssoTaqTweet

		private static $instance;
		private static $have_min = true;	// have minimum wpsso version

		public function __construct() {

			require_once ( dirname( __FILE__ ).'/lib/config.php' );
			WpssoTaqConfig::set_constants( __FILE__ );
			WpssoTaqConfig::require_libs( __FILE__ );	// includes the register.php class library
			$this->reg = new WpssoTaqRegister();		// activate, deactivate, uninstall hooks

			if ( is_admin() ) {
				add_action( 'admin_init', array( __CLASS__, 'required_check' ) );
				add_action( 'wpsso_init_textdomain', array( __CLASS__, 'wpsso_init_textdomain' ) );
			}

			add_filter( 'wpsso_get_config', array( &$this, 'wpsso_get_config' ), 30, 2 );
			add_action( 'wpsso_init_options', array( &$this, 'wpsso_init_options' ), 10 );
			add_action( 'wpsso_init_objects', array( &$this, 'wpsso_init_objects' ), 10 );
			add_action( 'wpsso_init_plugin', array( &$this, 'wpsso_init_plugin' ), 10 );
		}

		public static function &get_instance() {
			if ( ! isset( self::$instance ) )
				self::$instance = new self;
			return self::$instance;
		}

		public static function required_check() {
			if ( ! class_exists( 'Wpsso' ) ) {
				add_action( 'all_admin_notices', array( __CLASS__, 'required_notice' ) );
			}
		}

		// also called from the activate_plugin method with $deactivate = true
		public static function required_notice( $deactivate = false ) {
			self::wpsso_init_textdomain();
			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];
			$die_msg = __( '%1$s is an extension for the %2$s plugin &mdash; please install and activate the %3$s plugin before activating %4$s.', 'wpsso-tweet-a-quote' );
			$err_msg = __( 'The %1$s extension requires the %2$s plugin &mdash; install and activate the %3$s plugin or <a href="%4$s">deactivate the %5$s extension</a>.', 'wpsso-tweet-a-quote' );
			if ( $deactivate === true ) {
				if ( ! function_exists( 'deactivate_plugins' ) ) {
					require_once trailingslashit( ABSPATH ).'wp-admin/includes/plugin.php';
				}
				deactivate_plugins( $info['base'], true );	// $silent = true
				wp_die( '<p>'.sprintf( $die_msg, $info['name'], $info['req']['name'],
					$info['req']['short'], $info['short'] ).'</p>' );
			} else {
				$deactivate_url = wp_nonce_url( 'plugins.php?action=deactivate&amp;'.
					'plugin='.$info['base'].'&amp;plugin_status=active&amp;paged=1&amp;s=',
						'deactivate-plugin_'.$info['base'] );
				echo '<div class="notice notice-error error"><p>'.
					sprintf( $err_msg, $info['name'], $info['req']['name'],
						$info['req']['short'], $deactivate_url, $info['short'] ).'</p></div>';
			}
		}

		public static function wpsso_init_textdomain() {
			load_plugin_textdomain( 'wpsso-tweet-a-quote', false, 'wpsso-tweet-a-quote/languages/' );
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
			if ( method_exists( 'Wpsso', 'get_instance' ) ) {
				$this->p =& Wpsso::get_instance();
			} else {
				$this->p =& $GLOBALS['wpsso'];
			}

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( self::$have_min ) {
				$this->p->avail['p_ext']['taq'] = true;
			} else {
				$this->p->avail['p_ext']['taq'] = false;	// just in case
			}
		}

		public function wpsso_init_objects() {
			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( self::$have_min ) {
				$this->filters = new WpssoTaqFilters( $this->p );
				$this->tweet = new WpssoTaqTweet( $this->p );
			}
		}

		public function wpsso_init_plugin() {
			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! self::$have_min ) {
				return $this->min_version_notice();	// stop here
			}

			if ( empty( $this->p->options['plugin_shortcodes'] ) ) {
				return $this->sc_disabled_notice();	// stop here
			}
		}

		private function min_version_notice() {
			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];
			$wpsso_version = $this->p->cf['plugin']['wpsso']['version'];

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

		private function sc_disabled_notice() {
			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( $info['name'].' requires the shortcodes option to be enabled' );
			}

			if ( is_admin() ) {
				$this->p->notice->err( sprintf( __( 'The %1$s extension requires the %2$s option to be enabled.',
					'wpsso-tweet-a-quote' ), $info['name'], $this->p->util->get_admin_url( 'advanced#sucom-tabset_plugin-tab_settings', 
						_x( 'Enable Plugin Shortcode(s)', 'option label', 'wpsso-tweet-a-quote' ) ) ) );
			}
		}
	}

        global $wpssotaq;
	$wpssotaq =& WpssoTaq::get_instance();
}

?>
