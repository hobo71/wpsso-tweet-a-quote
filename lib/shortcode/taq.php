<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaqShortcodeTaq' ) ) {

	class WpssoTaqShortcodeTaq {

		private $p;

		private $taq_tweet_url      = 'https://twitter.com/intent/tweet?original_referer=%%sharing_url%%&amp;url=%%short_url%%&amp;text=%%twitter_text%%&amp;hashtags=%%twitter_hashtags%%&amp;via=%%twitter_via%%&amp;related=%%twitter_related%%';
		private $taq_row_open_html  = '<span class="taq_row"><span class="taq_open"></span><span class="taq_text">';
		private $taq_row_close_html = '</span><span class="taq_close"></span></span>';
		private $taq_icon_html      = '<span class="taq_icon"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"><path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62c-3.122.162-6.22-.646-8.86-2.32 2.702.18 5.375-.648 7.507-2.32-2.072-.248-3.818-1.662-4.49-3.64.802.13 1.62.077 2.4-.154-2.482-.466-4.312-2.586-4.412-5.11.688.276 1.426.408 2.168.387-2.135-1.65-2.73-4.62-1.394-6.965C5.574 7.816 9.54 9.84 13.802 10.07c-.842-2.738.694-5.64 3.434-6.48 2.018-.624 4.212.043 5.546 1.682 1.186-.213 2.318-.662 3.33-1.317-.386 1.256-1.248 2.312-2.4 2.942 1.048-.106 2.07-.394 3.02-.85-.458 1.182-1.343 2.15-2.48 2.71z" /></svg></span>';

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( $this->p->avail['p_ext']['taq'] ) {

				$this->check_wpautop();

				$this->add_shortcode();

				$this->p->util->add_plugin_actions( $this, array( 
					'pre_apply_filters_text'   => 1,
					'after_apply_filters_text' => 1,
				) );
			}
		}

		/**
		 * Make sure wpautop() does not have a higher priority than 10, otherwise it will 
		 * format the shortcode output (shortcode filters are run at priority 11).
		 */
		public function check_wpautop() {

			$default_priority = 10;

			foreach ( array( 'get_the_excerpt', 'the_excerpt', 'the_content' ) as $filter_name ) {

				$filter_priority = has_filter( $filter_name, 'wpautop' );

				if ( false !== $filter_priority && $filter_priority > $default_priority ) {

					remove_filter( $filter_name, 'wpautop' );

					add_filter( $filter_name, 'wpautop' , $default_priority );

					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( 'wpautop() priority changed from '.$filter_priority.' to '.$default_priority );
					}
				}
			}
		}

		public function action_pre_apply_filters_text( $filter_name ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log_args( array( 
					'filter_name' => $filter_name,
				) );
			}

			$this->add_shortcode();
		}

		public function action_after_apply_filters_text( $filter_name ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log_args( array( 
					'filter_name' => $filter_name,
				) );
			}

			$this->remove_shortcode();
		}

		public function add_shortcode() {

			if ( shortcode_exists( WPSSOTAQ_TWEET_SHORTCODE_NAME ) ) {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'cannot add ['.WPSSOTAQ_TWEET_SHORTCODE_NAME.'] sharing shortcode - shortcode already exists' );
				}
	
				return false;
			}

        		add_shortcode( WPSSOTAQ_TWEET_SHORTCODE_NAME, array( $this, 'do_shortcode' ) );

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( '['.WPSSOTAQ_TWEET_SHORTCODE_NAME.'] sharing shortcode added' );
			}

			return true;
			
		}

		public function remove_shortcode() {

			if ( shortcode_exists( WPSSOTAQ_TWEET_SHORTCODE_NAME ) ) {

				remove_shortcode( WPSSOTAQ_TWEET_SHORTCODE_NAME );

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( '['.WPSSOTAQ_TWEET_SHORTCODE_NAME.'] sharing shortcode removed' );
				}

				return true;

			}
			
			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( 'cannot remove ['.WPSSOTAQ_TWEET_SHORTCODE_NAME.'] sharing shortcode - shortcode does not exist' );
			}

			return false;
		}

		public function do_shortcode( $atts = array(), $content = null, $tag = '' ) { 

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! is_array( $atts ) ) {	// empty string if no shortcode attributes
				$atts = array();
			}

			$atts = (array) apply_filters( $this->p->lca.'_taq_shortcode_'.WPSSOTAQ_TWEET_SHORTCODE_NAME, $atts, $content );
			$class = empty( $atts[ 'class' ] ) ? WPSSOTAQ_TWEET_SHORTCODE_CLASS : $atts[ 'class' ];
			$content = trim( $content );	// just in case

			if ( isset( $atts['tweet'] ) ) {
				$atts['tweet'] = trim( $atts['tweet'] );	// just in case
			}

			$atts[ 'use_post' ] = SucomUtil::sanitize_use_post( $atts, true );	// $default = true

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( 'required call to get_page_mod()' );
			}

			$mod = $this->p->util->get_page_mod( $atts[ 'use_post' ] );

			if ( empty( $atts['tweet'] ) && empty( $content ) ) {
				return $content;
			}

			if ( ! isset( $atts['via'] ) ) {
				if ( ! empty( $this->p->options['taq_add_via'] ) ) {
					$atts['via'] = preg_replace( '/^@/', '', SucomUtil::get_key_value( 'tc_site', $this->p->options ) );
				}
			}

			if ( ! isset( $atts['related'] ) ) {
				if ( ! empty( $this->p->options['taq_rec_author'] ) ) {
					if ( ! empty( $mod[ 'post_author' ] ) && $atts[ 'use_post' ] ) {
						$atts['related'] = preg_replace( '/^@/', '', 
							get_the_author_meta( $this->p->options['plugin_cm_twitter_name'], 
								$mod[ 'post_author' ] ) );
					}
				}
			}

			if ( empty( $atts['tweet'] ) ) {
				$atts['tweet'] = $this->p->util->limit_text_length( $content, WpssoTaqTweet::get_max_len( $atts ), '...' );
			}

			if ( SucomUtil::is_amp() ) {
				return '<div class="'.$class.' is_amp">'.$this->taq_row_open_html.$content.$this->taq_row_close_html.'</div>';
			} elseif ( is_feed() ) {
				return '<div class="'.$class.' is_feed">'.$this->taq_row_open_html.$content.$this->taq_row_close_html.'</div>';
			}

			if ( empty( $atts[ 'url' ] ) ) {
				$atts[ 'url' ] = $this->p->util->get_sharing_url( $mod );
			}

			$extra_inline_vars = array();

			if ( ! $this->p->avail[ '*' ]['vary_ua'] || SucomUtil::is_mobile() ) {
				$tweet_url = $this->taq_tweet_url;
			} elseif ( ! empty( $this->p->options['taq_use_script'] ) ) {
				$tweet_url = preg_replace( '/(\/intent)\/(tweet\?)/', '$1/+/$2', $this->taq_tweet_url );
			} else {
				$tweet_url = $this->taq_tweet_url;
			}

			foreach ( array( 
				'text'     => 'tweet',	// tweet text
				'hashtags' => 'hashtags',
				'via'      => 'via',
				'related'  => 'related',
			) as $query_key => $atts_key  ) {
				if ( ! empty( $atts[$atts_key] ) ) {
					$extra_inline_vars['twitter_'.$query_key] = rawurlencode( html_entity_decode( $atts[$atts_key] ) );
				} else {
					$tweet_url = preg_replace( '/&(amp;)?'.$query_key.'=%%twitter_'.$query_key.'%%/', '', $tweet_url );
				}
			}

			if ( ! empty( $this->p->options['taq_link_text'] ) ) {
				$quote_html = $this->taq_row_open_html.
					'<div class="taq_link"><a href="'.$tweet_url.'" class="taq_popup">'.
						$content.'</a></div>'.$this->taq_row_close_html;
			} else {
				$quote_html = $this->taq_row_open_html.$content.$this->taq_row_close_html;
			}

			if ( ! empty( $this->p->options['taq_add_button'] ) ) {
				$quote_html .= '<div class="taq_link taq_button"><a href="'.$tweet_url.'" class="taq_popup">'.
					$this->taq_icon_html.'</a></div>';
			}

			return $this->p->util->replace_inline_vars( '<div class="'.$class.' is_tweet">'.$quote_html.'</div>',
				$mod, $atts, $extra_inline_vars );
		}
	}
}
