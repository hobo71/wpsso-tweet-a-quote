<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2015-2016 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoTaqFilters' ) ) {

	class WpssoTaqFilters {

		protected $p;

		public static $cf = array(
			'opt' => array(				// options
				'defaults' => array(
					'taq_add_via' => 1,
					'taq_rec_author' => 1,
					'taq_use_style' => 1,
					'taq_use_script' => 1,
					'taq_button_html' => '<div class="taq_button">
	<a href="https://twitter.com/intent/tweet?original_referer=%%sharing_url%%&amp;url=%%short_url%%&amp;text=%%twitter_text%%&amp;hashtags=%%twitter_hashtags%%&amp;via=%%twitter_via%%&amp;related=%%twitter_related%%" class="popup">
		<span class="taq_icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
				<path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62c-3.122.162-6.22-.646-8.86-2.32 2.702.18 5.375-.648 7.507-2.32-2.072-.248-3.818-1.662-4.49-3.64.802.13 1.62.077 2.4-.154-2.482-.466-4.312-2.586-4.412-5.11.688.276 1.426.408 2.168.387-2.135-1.65-2.73-4.62-1.394-6.965C5.574 7.816 9.54 9.84 13.802 10.07c-.842-2.738.694-5.64 3.434-6.48 2.018-.624 4.212.043 5.546 1.682 1.186-.213 2.318-.662 3.33-1.317-.386 1.256-1.248 2.312-2.4 2.942 1.048-.106 2.07-.394 3.02-.85-.458 1.182-1.343 2.15-2.48 2.71z" />
			</svg>
		</span>
	</a>
</div>',
					'taq_button_css' => 'div.wpsso_taq {
	margin:1.5em auto;
	padding:1.3em;
	max-width:600px;
	color:#444;
	border:#ddd 1px solid;
	border-top-color:#eee;
	border-bottom-color:#bbb;
	border-radius:5px;
	box-shadow:0 1px 3px rgba(0,0,0,0.15);
	background-color:#fff;
}
div.wpsso_taq .taq_row {
	display:table;
}
div.wpsso_taq .taq_open,
div.wpsso_taq .taq_close {
	display:table-cell;
	color:#eee;
	font-size:5em;
	line-height:0;
	padding:0.4em 0 0 0;
	vertical-align:top;
	quotes:"\201C""\201D""\2018""\2019";
}
div.wpsso_taq .taq_open:before {
	content:open-quote;
	margin:0 15px 0 -5px;
}
div.wpsso_taq .taq_close:after {
	content:close-quote;
	margin:0 5px 0 15px;
}
div.wpsso_taq .taq_text {
	display:table-cell;
	font-size:1.1em;
	line-height:1.3em;
}
div.wpsso_taq .taq_button {
	font:normal 12px/18px Helvetica, Arial, sans-serif;
	font-size:0.7em;
	font-style:normal;
	line-height:1em;
	text-align:right;
	margin-top:0.3em;
}
div.wpsso_taq .taq_button a {
	color:#1e9cc0;
	text-decoration:none;
	outline:0 none;
}
div.wpsso_taq .taq_button a .taq_icon svg {
	vertical-align:bottom;
	width:1.4em;
	height:auto;
	padding:0;
	margin:0;
}
div.wpsso_taq .taq_button a .taq_icon svg path {
	fill:#26c4f1;
}
div.wpsso_taq .taq_button a .taq_icon:after {
	display:inline-block;
	content:"Tweet This Quote";
	margin-bottom:0.1em;
}',
					'taq_button_js' => '+(function(window, $, undefined) {
	var popupCenter = function(url, title, w, h) {
		var dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : screen.left;
		var dualScreenTop = window.screenTop !== undefined ? window.screenTop : screen.top;
		var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
		var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
		var left = ((width / 2) - (w / 2)) + dualScreenLeft;
		var top = ((height / 3) - (h / 3)) + dualScreenTop;
		var newWindow = window.open(url, title, "scrollbars=yes,width="+w+",height="+h+",top="+top+",left="+left);
		if (newWindow && newWindow.focus) {
			newWindow.focus();
		}
	};
	$(document).ready(function(){
		try {
			$(document).on("click", ".taq_button a.popup", {}, function popUp(e) {
				var self = $(this);
				popupCenter(self.attr("href"), self.find(".taq_icon:after").html(), 580, 255);
				e.preventDefault();
			});
		}
		catch (e) {
		}
	});
})(window, jQuery);',
				),
			),
		);

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();

			$this->p->util->add_plugin_filters( $this, array( 
				'get_defaults' => 1,			// option defaults
			) );

			//if ( ! SucomUtil::get_const( 'DOING_AJAX' ) ) {
				if ( is_admin() ) {
					$this->p->util->add_plugin_filters( $this, array( 
						'messages_tooltip' => 2,		// tooltip messages filter
					) );
				}
				add_action( 'admin_head', array( &$this, 'add_tinymce_filters' ) );
				add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_styles' ) );
				add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
				add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ) );
				add_action( 'wp_head', array( &$this, 'add_button_style' ), 1000 );
				add_action( 'wp_footer', array( &$this, 'add_button_script' ), 1000 );
			//}
		}

		public function filter_get_defaults( $def_opts ) {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			return array_merge( $def_opts, self::$cf['opt']['defaults'] );
		}

		public function filter_messages_tooltip( $text, $idx ) {
			if ( strpos( $idx, 'tooltip-taq_' ) !== 0 )
				return $text;
			switch ( $idx ) {
				case 'tooltip-taq_add_via':
					$text = sprintf( __( 'Append the %1$s to the tweet (see <a href="%2$s">the Twitter options tab</a> in the %3$s settings page). The %1$s will be displayed and recommended after the webpage is shared.', 'wpsso-tweet-a-quote' ), _x( 'Twitter Business @username', 'option label', 'wpsso-tweet-a-quote' ), $this->p->util->get_admin_url( 'general#sucom-tabset_pub-tab_twitter' ), _x( 'General', 'lib file description', 'wpsso-tweet-a-quote' ) );
					break;
				case 'tooltip-taq_rec_author':
					$text = sprintf( __( 'Recommend following the author\'s Twitter @username after sharing a webpage. If the %1$s option (above) is also checked, the %2$s is suggested first.', 'wpsso-tweet-a-quote' ), _x( 'Add via Business @username', 'option label', 'wpsso-tweet-a-quote' ), _x( 'Twitter Business @username', 'option label', 'wpsso-tweet-a-quote' ) );
					break;
				case 'tooltip-taq_use_style':
					$text = __( 'Add the Tweet a Quote CSS to front-end webpages to style the Tweet text and link.', 'wpsso-tweet-a-quote' );
					break;
				case 'tooltip-taq_use_script':
					$text = __( 'Add the Tweet a Quote jQuery to front-end webpages to provide a popup share window.', 'wpsso-tweet-a-quote' );
					break;
				case 'tooltip-taq_button_html':
					$text = __( 'The Tweet a Quote share link / button HTML added to the Tweet text.', 'wpsso-tweet-a-quote' );
					break;
				case 'tooltip-taq_button_css':
					$text = __( 'The Tweet a Quote CSS to style the Tweet text and share link / button.', 'wpsso-tweet-a-quote' );
					break;
				case 'tooltip-taq_button_js':
					$text = __( 'The Tweet a Quote jQuery to provide a popup share window.', 'wpsso-tweet-a-quote' );
					break;
			}
			return $text;
		}

		public function add_tinymce_filters() {
			add_filter( 'mce_external_plugins', array( &$this, 'add_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( &$this, 'add_tinymce_button' ) );
		}

		public function add_tinymce_plugin( $plugin_array ) {
			$plugin_array['wpssotaq'] = WPSSOTAQ_URLPATH.'js/tinymce-plugin.min.js';
			return $plugin_array;
		}
		
		public function add_tinymce_button( $buttons ) {
			$buttons[] = 'wpssotaq';
			return $buttons;
		}

		public function admin_enqueue_styles( $hook_name ) {
			wp_register_style( 'sweetalert2.js', WPSSOTAQ_URLPATH.'css/ext/sweetalert2.min.css', array(), '6.1.1' );
			wp_enqueue_style( 'sweetalert2.js' );
		}

		public function admin_enqueue_scripts( $hook_name ) {
			wp_register_script( 'sweetalert2.js', WPSSOTAQ_URLPATH.'js/ext/sweetalert2.min.js', array(), '6.1.1' );
			wp_enqueue_script( 'sweetalert2.js' );

			wp_register_script( 'wpssotaq_admin_js_variables.js', WPSSOTAQ_URLPATH.'js/admin-variables.min.js', 
				array(), WpssoTaqConfig::get_version() );

			$data = array(
				'pluginUrl' => WPSSOTAQ_URLPATH,
				'maxTweetLen' => WpssoTaqTweet::get_max_len(),
			);

			wp_localize_script( 'wpssotaq_admin_js_variables.js', 'wpssotaq', $data );
			wp_enqueue_script( 'wpssotaq_admin_js_variables.js' );
		}

		public function wp_enqueue_scripts() {
			wp_enqueue_script( 'jquery' );	// just in case
		}

		public function add_button_style() {
			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
			if ( ! empty( $this->p->options['taq_use_style'] ) ) {
				$taq_button_css = $this->p->options['taq_button_css'];
				echo '<style type="text/css">'.$taq_button_css.'</style>'."\n";
			} elseif ( $this->p->debug->enabled )
				$this->p->debug->log( 'taq_use_style option is disabled' );
		}

		public function add_button_script() {
			if ( ! empty( $this->p->options['taq_use_script'] ) ) {
				$taq_button_js = $this->p->options['taq_button_js'];
				echo '<script type="text/javascript">'.$taq_button_js.'</script>'."\n";
			} elseif ( $this->p->debug->enabled )
				$this->p->debug->log( 'taq_use_script option is disabled' );
		}
	}
}

?>
