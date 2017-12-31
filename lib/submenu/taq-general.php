<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2017 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTaqSubmenuTaqGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoTaqSubmenuTaqGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->menu_lib = $lib;
			$this->menu_ext = $ext;	// lowercase acronyn for plugin or extension
		}

		// called by the extended WpssoAdmin class
		protected function add_meta_boxes() {
			add_meta_box( $this->pagehook.'_taq_general', 
				_x( 'Tweet a Quote Settings', 'metabox title', 'wpsso-tweet-a-quote' ),
					array( &$this, 'show_metabox_taq_general' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_taq_general() {
			$metabox_id = 'taq';
			$this->p->util->do_table_rows( apply_filters( $this->p->cf['lca'].'_'.$metabox_id.'_general_rows', 
				$this->get_table_rows( $metabox_id, 'general' ), $this->form ), 'metabox-'.$metabox_id.'-general' );
		}

		protected function get_table_rows( $metabox_id, $key ) {
			$table_rows = array();
			switch ( $metabox_id.'-'.$key ) {
				case 'taq-general':

					$table_rows['taq_add_via'] = $this->form->get_th_html( _x( 'Add via Business @username',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_add_via' ).
					'<td>'.$this->form->get_checkbox( 'taq_add_via' ).'</td>';

					$table_rows['taq_rec_author'] = $this->form->get_th_html( _x( 'Recommend Author @username',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_rec_author' ).
					'<td>'.$this->form->get_checkbox( 'taq_rec_author' ).'</td>';

					$table_rows['taq_link_text'] = $this->form->get_th_html( _x( 'Link the Quote Text',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_link_text' ).
					'<td>'.$this->form->get_checkbox( 'taq_link_text' ).'</td>';

					$table_rows['taq_add_button'] = $this->form->get_th_html( _x( 'Append a Tweet Icon',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_add_button' ).
					'<td>'.$this->form->get_checkbox( 'taq_add_button' ).'</td>';

					$table_rows['taq_use_style'] = $this->form->get_th_html( _x( 'Use TAQ Stylesheet',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_use_style' ).
					'<td>'.$this->form->get_checkbox( 'taq_use_style' ).'</td>';

					$table_rows['taq_use_script'] = $this->form->get_th_html( _x( 'Use TAQ Javascript',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_use_script' ).
					'<td>'.$this->form->get_checkbox( 'taq_use_script' ).'</td>';

					$table_rows['taq_popup_size'] = $this->form->get_th_html( _x( 'Tweet Window Size',
						'option label', 'wpsso-tweet-a-quote' ), '', 'taq_popup_size' ).
					'<td>'.$this->form->get_input( 'taq_popup_width', 'short' ).' x '.
						$this->form->get_input( 'taq_popup_height', 'short' ).'</td>';

					break;
			}
			return $table_rows;
		}
	}
}

