<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2016 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoTaqShortcodeTaq' ) ) {

	class WpssoTaqShortcodeTaq {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			if ( ! is_admin() ) {
				if ( $this->p->is_avail['taq'] ) {
					$this->wpautop();
					$this->add();
				}
			}
		}

		public function wpautop() {
			// make sure wpautop() does not have a higher priority than 10, otherwise it will 
			// format the shortcode output (shortcode filters are run at priority 11).
			if ( ! empty( $this->p->options['plugin_shortcodes'] ) ) {
				$default_priority = 10;
				foreach ( array( 'get_the_excerpt', 'the_excerpt', 'the_content' ) as $filter_name ) {
					$filter_priority = has_filter( $filter_name, 'wpautop' );
					if ( $filter_priority !== false && $filter_priority > $default_priority ) {
						remove_filter( $filter_name, 'wpautop' );
						add_filter( $filter_name, 'wpautop' , $default_priority );
						$this->p->debug->log( 'wpautop() priority changed from '.$filter_priority.' to '.$default_priority );
					}
				}
			}
		}

		public function add() {
			if ( ! empty( $this->p->options['plugin_shortcodes'] ) ) {
        			add_shortcode( WPSSOTAQ_TWEET_SHORTCODE_NAME, array( &$this, 'shortcode' ) );
				$this->p->debug->log( '['.WPSSOTAQ_TWEET_SHORTCODE_NAME.'] sharing shortcode added' );
			}
		}

		public function remove() {
			if ( ! empty( $this->p->options['plugin_shortcodes'] ) ) {
				remove_shortcode( WPSSOTAQ_TWEET_SHORTCODE_NAME );
				$this->p->debug->log( '['.WPSSOTAQ_TWEET_SHORTCODE_NAME.'] sharing shortcode removed' );
			}
		}

		public function shortcode( $atts, $content = null ) { 

			$lca = $this->p->cf['lca'];
			$atts = (array) apply_filters( $lca.'_taq_shortcode_'.WPSSOTAQ_TWEET_SHORTCODE_NAME, $atts, $content );
			$class = empty( $atts['class'] ) ? WPSSOTAQ_TWEET_SHORTCODE_CLASS : $atts['class'];
			$content = trim( $content );	// just in case
			if ( isset( $atts['text'] ) )
				$atts['text'] = trim( $atts['text'] );	// just in case
			$atts['use_post'] = SucomUtil::sanitize_use_post( $atts, true );	// $default = true
			$mod = $this->p->util->get_page_mod( $atts['use_post'] );

			if ( empty( $atts['text'] ) && empty( $content ) )
				return $content;

			if ( ! isset( $atts['via'] ) ) {
				if ( ! empty( $this->p->options['taq_add_via'] ) ) {
					$atts['via'] = preg_replace( '/^@/', '', 
						SucomUtil::get_locale_opt( 'tc_site', $this->p->options ) );
				}
			}

			if ( ! isset( $atts['related'] ) ) {
				if ( ! empty( $this->p->options['taq_rec_author'] ) ) {
					if ( ! empty( $mod['post_author'] ) && $atts['use_post'] ) {
						$atts['related'] = preg_replace( '/^@/', '', 
							get_the_author_meta( $this->p->options['plugin_cm_twitter_name'], 
								$mod['post_author'] ) );
					}
				}
			}

			if ( empty( $atts['text'] ) )
				$atts['text'] = $content = $this->p->util->limit_text_length( $content, 
					WpssoTaqTweet::get_max_len( $atts ), '...' );

			$taq_text_html = '<span class="taq_row"><span class="taq_open"></span><span class="taq_text">'.
				$atts['text'].'</span><span class="taq_close"></span></span>';

			if ( $this->p->is_avail['amp_endpoint'] && is_amp_endpoint() )
				return '<div class="'.$class.' is_amp">'.$taq_text_html.'</div>';
			elseif ( is_feed() )
				return '<div class="'.$class.' is_feed">'.$taq_text_html.'</div>';

			if ( empty( $atts['url'] ) )
				$atts['url'] = $this->p->util->get_sharing_url( $mod );

			$extra_inline_vars = array();

			if ( ! empty( $this->p->options['taq_use_script'] ) &&
				strpos( $this->p->options['taq_button_js'], '/+/' ) !== false )	// just in case
					$taq_button_html = preg_replace( '/(\/intent)\/(tweet\?)/', '$1/+/$2',
						$this->p->options['taq_button_html'] );
			else $taq_button_html = $this->p->options['taq_button_html'];

			foreach ( array( 
				'text' => 'text',
				'hashtags' => 'hashtags',
				'via' => 'via',
				'related' => 'related',
			) as $query_key => $atts_key  ) {
				if ( ! empty( $atts[$atts_key] ) )
					$extra_inline_vars['twitter_'.$query_key] = rawurlencode( $atts[$atts_key] );
				else $taq_button_html = preg_replace( '/&(amp;)?'.$query_key.'=%%twitter_'.$query_key.'%%/', '', $taq_button_html );
			}

			return $this->p->util->replace_inline_vars( '<!-- Twitter Button -->'.
				'<div class="'.$class.' is_tweet">'.$taq_text_html.$taq_button_html.'</div>',
					$mod, $atts, $extra_inline_vars );
		}
	}
}

?>
