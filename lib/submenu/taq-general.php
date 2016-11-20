<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2016 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoTaqSubmenuTaqGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoTaqSubmenuTaqGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {
			$this->p =& $plugin;
			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->menu_lib = $lib;
			$this->menu_ext = $ext;

			if ( $this->p->debug->enabled )
				$this->p->debug->mark();
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_taq_general', 
				_x( 'Tweet a Quote Settings', 'metabox title', 'wpsso-tweet-a-quote' ),
					array( &$this, 'show_metabox_taq_general' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_taq_general() {
			$metabox = 'taq';
			$this->p->util->do_table_rows( apply_filters( $this->p->cf['lca'].'_'.$metabox.'_general_rows', 
				$this->get_table_rows( $metabox, 'general' ), $this->form ), 'metabox-'.$metabox.'-general' );
		}

		protected function get_table_rows( $metabox, $key ) {
			$table_rows = array();
			switch ( $metabox.'-'.$key ) {
				case 'taq-general':

					$table_rows['taq_add_via'] = $this->form->get_th_html( _x( 'Add via Business @username',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_add_via' ).
					'<td>'.$this->form->get_checkbox( 'taq_add_via' ).'</td>';

					$table_rows['taq_rec_author'] = $this->form->get_th_html( _x( 'Recommend Author @username',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_rec_author' ).
					'<td>'.$this->form->get_checkbox( 'taq_rec_author' ).'</td>';

					$table_rows['taq_use_style'] = $this->form->get_th_html( _x( 'Include Tweet a Quote CSS',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_use_style' ).
					'<td>'.$this->form->get_checkbox( 'taq_use_style' ).'</td>';

					$table_rows['taq_use_script'] = $this->form->get_th_html( _x( 'Use Tweet a Quote jQuery',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_use_script' ).
					'<td>'.$this->form->get_checkbox( 'taq_use_script' ).'</td>';

					$table_rows['taq_button_html'] = '<tr class="hide_in_basic">'.
					$this->form->get_th_html( _x( 'Tweet a Quote Link HTML',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_button_html' ).
					'<td>'.$this->form->get_textarea( 'taq_button_html', 'average code' ).'</td>';

					$table_rows['taq_button_css'] = $this->form->get_th_html( _x( 'Tweet a Quote CSS',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_button_css' ).
					'<td>'.$this->form->get_textarea( 'taq_button_css', 'tall code' ).'</td>';

					$table_rows['taq_button_js'] = '<tr class="hide_in_basic">'.
					$this->form->get_th_html( _x( 'Tweet a Quote jQuery',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_button_js' ).
					'<td>'.$this->form->get_textarea( 'taq_button_js', 'average code' ).'</td>';

					break;
			}
			return $table_rows;
		}
	}
}

?>
