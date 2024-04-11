<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the function that add and controls the font in the theme
	*	---------------------------------------------------------------------
	*/

	$totalbusiness_font_family = array( 									
		'title' => esc_html__('Font Family', 'totalbusiness'),
		'options' => array(
			
			'header-font-family' => array(
				'title' => esc_html__('Header Font', 'totalbusiness'),
				'type' => 'font-combobox',
				'default' => 'Arial, Helvetica, sans-serif',
				'data-type' => 'font',
				'selector' => 'h1, h2, h3, h4, h5, h6, .totalbusiness-title-font{ font-family: #gdlr#; }'
			),			
			'content-font-family' => array(
				'title' => esc_html__('Content Font', 'totalbusiness'),
				'type' => 'font-combobox',
				'default' => 'Arial, Helvetica, sans-serif',
				'data-type' => 'font',
				'selector' => 'body, input, textarea, select, .totalbusiness-content-font{ font-family: #gdlr#; }'
			),			
			'info-font' => array(
				'title' => esc_html__('Info Font', 'totalbusiness'),
				'type' => 'font-combobox',
				'default' => 'Crete Round',
				'data-type' => 'font',
				'selector' => '.totalbusiness-info-font, .totalbusiness-modern-portfolio .portfolio-info, .totalbusiness-plain .about-us-caption, .totalbusiness-normal .about-us-caption{ font-family: #gdlr#; }',
				'description' => esc_html__('For portfolio modern tag and plain about us caption', 'totalbusiness')
			),			
			'navigaiton-font-family' => array(
				'title' => esc_html__('Navigation Font', 'totalbusiness'),
				'type' => 'font-combobox',
				'default' => 'Arial, Helvetica, sans-serif',
				'data-type' => 'font',
				'selector' => '.totalbusiness-navigation{ font-family: #gdlr#; }'
			),			
			'slider-font-family' => array(
				'title' => esc_html__('Slider Font', 'totalbusiness'),
				'type' => 'font-combobox',
				'default' => 'Arial, Helvetica, sans-serif',
				'data-type' => 'font',
				'selector' => '.totalbusiness-slider-item{ font-family: #gdlr#; }'
			),
		)
	);	
	
	add_filter('totalbusiness_admin_option', 'totalbusiness_register_font_option');	
	if( !function_exists('totalbusiness_register_font_option') ){
		function totalbusiness_register_font_option( $array ){		
			if( empty($array['font-settings']['options']) ) return $array;
			
			global $totalbusiness_font_family;
			
			$array['font-settings']['options']['font-family'] = $totalbusiness_font_family;
			return $array;
		}
	}	
	
	// register the font script to embedding it when used.
	if( !function_exists('totalbusiness_register_font_location') ){
		function totalbusiness_register_font_location(){
			global $totalbusiness_font_family, $totalbusiness_font_controller;
			
			$font_location = array();
			foreach( $totalbusiness_font_family['options'] as $font_slug => $font_settings ){
				array_push($font_location, $font_slug);
			}

			$totalbusiness_font_controller->font_location = $font_location;
		}	
	}
	totalbusiness_register_font_location();

?>