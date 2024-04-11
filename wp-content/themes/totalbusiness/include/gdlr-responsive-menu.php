<?php
	/*	
	*	Goodlayers Menu Management File
	*	---------------------------------------------------------------------
	*	This file use to include a necessary script / function for the 
	* 	navigation area
	*	---------------------------------------------------------------------
	*/
	
	// add action to enqueue superfish menu
	add_filter('totalbusiness_enqueue_scripts', 'totalbusiness_register_dlmenu');
	if( !function_exists('totalbusiness_register_dlmenu') ){
		function totalbusiness_register_dlmenu($array){	
			$array['style']['dlmenu'] = get_template_directory_uri() . '/plugins/dl-menu/component.css';
			
			$array['script']['modernizr'] = get_template_directory_uri() . '/plugins/dl-menu/modernizr.custom.js';
			$array['script']['dlmenu'] = get_template_directory_uri() . '/plugins/dl-menu/jquery.dlmenu.js';

			return $array;
		}
	}
	
	// creating the class for outputing the custom navigation menu
	if( !class_exists('totalbusiness_dlmenu_walker') ){
		
		// from wp-includes/nav-menu-template.php file
		class totalbusiness_dlmenu_walker extends Walker_Nav_Menu{		

			function start_lvl( &$output, $depth = 0, $args = array() ) {
				$indent = str_repeat("\t", $depth);
				$output .= "\n$indent<ul class=\"dl-submenu\">\n";
			}

		}
		
	}

?>