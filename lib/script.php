<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaqScript' ) ) {

	class WpssoTaqScript {

		private $p;

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! SucomUtil::get_const( 'DOING_AJAX' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			}
		}

		public function wp_enqueue_scripts() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( empty( $this->p->options['taq_use_script'] ) ) {
				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'exiting early: taq_use_script option is disabled' );
				}
				return;
			}

			/**
			 * Do not use minified JS if the DEV constant is defined.
			 */
			$doing_dev      = SucomUtil::get_const( 'WPSSO_DEV' );
			$js_file_ext    = $doing_dev ? 'js' : 'min.js';
			$plugin_version = WpssoTaqConfig::get_version();

			wp_enqueue_script( 'jquery' );

			wp_enqueue_script( 'taq', 
				WPSSOTAQ_URLPATH . 'js/taq.' . $js_file_ext, 
					array( 'jquery' ), $plugin_version, true );

			$custom_script_js = '
				+(function(window, $, undefined) {

					var taqPopupCenterWindow = function(url, w, h) {

						var dualScreenLeft = window.screenLeft !== undefined ?
							window.screenLeft : screen.left;

						var dualScreenTop = window.screenTop !== undefined ?
							window.screenTop : screen.top;

						var width = window.innerWidth ?
							window.innerWidth : document.documentElement.clientWidth ?
								document.documentElement.clientWidth : screen.width;

						var height = window.innerHeight ?
							window.innerHeight : document.documentElement.clientHeight ?
								document.documentElement.clientHeight : screen.height;

						var left = ((width / 2) - (w / 2)) + dualScreenLeft;

						var top = ((height / 3) - (h / 3)) + dualScreenTop;

						var newWindow = window.open(url, "", "scrollbars=yes,width="+w+",height="+h+",top="+top+",left="+left);

						if (newWindow && newWindow.focus) {
							newWindow.focus();
						}
					};

					$(document).ready(function(){

						try {
							$(document).on("click", ".taq_link a.taq_popup", {}, function taq_popup_click(e) {

								var self = $(this);
								var url = self.attr(\'href\').replace(\'/+/\', \'/\');
								var popup_w = %%popup_width%%;
								var popup_h = %%popup_height%%;

								taqPopupCenterWindow(url, popup_w, popup_h);

								e.preventDefault();
							});
						}

						catch (e) {
						}
					});

				})(window, jQuery);
			';

			$custom_script_js = preg_replace(
				array( '/%%popup_width%%/', '/%%popup_height%%/' ),
				array( $this->p->options['taq_popup_width'], $this->p->options['taq_popup_height'] ),
				$custom_script_js
			);

			wp_add_inline_script( 'taq', $custom_script_js );
		}
	}
}
