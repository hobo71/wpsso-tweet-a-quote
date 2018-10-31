<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2015-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaqFilters' ) ) {

	class WpssoTaqFilters {

		private $p;

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! SucomUtil::get_const( 'DOING_AJAX' ) ) {
				if ( is_admin() ) {
					$this->p->util->add_plugin_filters( $this, array( 
						'messages_tooltip' => 2,		// tooltip messages filter
					) );
				}
			}
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( strpos( $msg_key, 'tooltip-taq_' ) !== 0 ) {
				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-taq_add_via':

					$text = sprintf( __( 'Append the %1$s to the tweet (see <a href="%2$s">the Twitter options tab</a> in the %3$s settings page). The %1$s will be displayed and recommended after the webpage is shared.', 'wpsso-tweet-a-quote' ), _x( 'Twitter Business @username', 'option label', 'wpsso-tweet-a-quote' ), $this->p->util->get_admin_url( 'general#sucom-tabset_pub-tab_twitter' ), _x( 'General', 'lib file description', 'wpsso-tweet-a-quote' ) );

					break;

				case 'tooltip-taq_rec_author':

					$text = sprintf( __( 'Recommend following the author\'s Twitter @username after sharing a webpage. If the %1$s option (above) is also checked, the %2$s is suggested first.', 'wpsso-tweet-a-quote' ), _x( 'Add via Business @username', 'option label', 'wpsso-tweet-a-quote' ), _x( 'Twitter Business @username', 'option label', 'wpsso-tweet-a-quote' ) );

					break;

				case 'tooltip-taq_link_text':

					$text = __( 'Link the quoted text to click anywhere on the text to Tweet.', 'wpsso-tweet-a-quote' );

					break;

				case 'tooltip-taq_add_button':

					$text = __( 'Append a Tweet icon after the quoted text.', 'wpsso-tweet-a-quote' );

					break;

				case 'tooltip-taq_use_style':

					$text = __( 'Add the Tweet a Quote stylesheet to front-end webpages.', 'wpsso-tweet-a-quote' );

					break;

				case 'tooltip-taq_use_script':

					$text = __( 'Add the Tweet a Quote javascript to front-end webpages.', 'wpsso-tweet-a-quote' );

					break;

				case 'tooltip-taq_popup_size':

					$text = __( 'The width and height (in pixels) of the Tweet popup window.', 'wpsso-tweet-a-quote' );

					break;

			}

			return $text;
		}
	}
}
