<?php
/**
 * Plugin Name: WPSSO Tweet a Quote
 * Plugin Slug: wpsso-tweet-a-quote
 * Text Domain: wpsso-tweet-a-quote
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-tweet-a-quote/
 * Assets URI: https://surniaulula.github.io/wpsso-tweet-a-quote/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: WPSSO Core extension to add CSS Twitter-style quoted text with a Tweet share link to post and page content (uses easily customized CSS).
 * Requires PHP: 5.4
 * Requires At Least: 3.8
 * Tested Up To: 4.9.4
 * Version: 1.2.0
 * 
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes / re-writes or incompatible API changes.
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 * 
 * Copyright 2016-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaq' ) ) {

	class WpssoTaq {

		/**
		 * Class Object Variables
		 */
		public $p;			// Wpsso
		public $reg;			// WpssoTaqRegister
		public $filters;		// WpssoTaqFilters
		public $tweet;			// WpssoTaqTweet

		/**
		 * Reference Variables (config, options, modules, etc.).
		 */
		private $have_req_min = true;	// Have minimum wpsso version.

		private static $instance;

		public function __construct() {

			require_once ( dirname( __FILE__ ) . '/lib/config.php' );
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

			$error_msg = __( 'The %1$s extension requires the %2$s plugin &mdash; install and activate the %3$s plugin or <a href="%4$s">deactivate the %5$s extension</a>.', 'wpsso-tweet-a-quote' );

			if ( true === $deactivate ) {

				if ( ! function_exists( 'deactivate_plugins' ) ) {
					require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/plugin.php';
				}

				deactivate_plugins( $info['base'], true );	// $silent = true

				wp_die( '<p>' . sprintf( $die_msg, $info['name'], $info['req']['name'], $info['req']['short'], $info['short'] ) . '</p>' );

			} else {

				$deactivate_url = html_entity_decode( wp_nonce_url( add_query_arg( array(
					'action' => 'deactivate',
					'plugin' => $info['base'],
					'plugin_status' => 'all',
					'paged' => 1,
					's' => '',
				), admin_url( 'plugins.php' ) ), 'deactivate-plugin_' . $info['base'] ) );

				echo '<div class="notice notice-error error"><p>';
				echo sprintf( $error_msg, $info['name'], $info['req']['name'], $info['req']['short'], $deactivate_url, $info['short'] );
				echo '</p></div>';
			}
		}

		public static function wpsso_init_textdomain() {
			load_plugin_textdomain( 'wpsso-tweet-a-quote', false, 'wpsso-tweet-a-quote/languages/' );
		}

		public function wpsso_get_config( $cf, $plugin_version ) {

			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];

			if ( version_compare( $plugin_version, $info['req']['min_version'], '<' ) ) {
				$this->have_req_min = false;
				return $cf;
			}

			return SucomUtil::array_merge_recursive_distinct( $cf, WpssoTaqConfig::$cf );
		}

		public function wpsso_init_options() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! $this->have_req_min ) {
				$this->p->avail['p_ext']['taq'] = false;	// just in case
				return;	// stop here
			}

			$this->p->avail['p_ext']['taq'] = true;
		}

		public function wpsso_init_objects() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! $this->have_req_min ) {
				return;	// stop here
			}

			$this->filters = new WpssoTaqFilters( $this->p );
			$this->tweet = new WpssoTaqTweet( $this->p );
		}

		public function wpsso_init_plugin() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! $this->have_req_min ) {
				$this->min_version_notice();
				return;	// stop here
			}

			if ( empty( $this->p->options['plugin_shortcodes'] ) ) {
				$this->sc_disabled_notice();
				return;	// stop here
			}
		}

		private function min_version_notice() {

			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];
			$have_version = $this->p->cf['plugin']['wpsso']['version'];

			$error_msg = sprintf( __( 'The %1$s version %2$s extension requires %3$s version %4$s or newer (version %5$s is currently installed).',
				'wpsso-tweet-a-quote' ), $info['name'], $info['version'], $info['req']['short'], $info['req']['min_version'], $have_version );

			if ( is_admin() ) {

				$this->p->notice->err( $error_msg );

				if ( method_exists( $this->p->admin, 'get_check_for_updates_link' ) ) {
					$this->p->notice->inf( $this->p->admin->get_check_for_updates_link() );
				}
			}

			// translators: %s is the short plugin name
			trigger_error( sprintf( __( '%s warning:', 'wpsso-tweet-a-quote' ), $info['short'] ).' '.rtrim( $error_msg, '.' ), E_USER_WARNING );
		}

		private function sc_disabled_notice() {

			$info = WpssoTaqConfig::$cf['plugin']['wpssotaq'];

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( $info['name'] . ' requires the shortcodes option to be enabled' );
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

