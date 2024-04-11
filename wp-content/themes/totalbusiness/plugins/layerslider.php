<?php
	/*	
	*	Goodlayers Layerslider Support File
	*/
	
	if(!function_exists('totalbusiness_get_layerslider_list')){
		function totalbusiness_get_layerslider_list(){
			if( !function_exists('lsSliders') ) return;
		
			$ret = array();
			$sliders = lsSliders();
			foreach($sliders as $slider){
				$ret[$slider['id']] = $slider['name'];
			}
			return $ret;
		}
	}
	
	add_action('totalbusiness_print_item_selector', 'totalbusiness_check_layerslider_item', 10, 2);
	if( !function_exists('totalbusiness_check_layerslider_item') ){
		function totalbusiness_check_layerslider_item( $type, $settings = array() ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';	
		
			if($type == 'layer-slider'){
				echo '<div class="totalbusiness-layerslider-item totalbusiness-slider-item totalbusiness-item" ' . $item_id . $margin_style . ' >';
				echo '<div class="totalbusiness-ls-shadow totalbusiness-top" ></div>';
				echo do_shortcode('[layerslider id="' . $settings['id'] . '"]');
				echo '<div class="totalbusiness-ls-shadow totalbusiness-bottom" ></div>';
				echo '</div>';
			}
		}
	}	
	
	add_action('layerslider_ready', 'totalbusiness_layerslider_overrides');
	if( !function_exists('totalbusiness_layerslider_overrides') ){
		function totalbusiness_layerslider_overrides() {
			$GLOBALS['lsAutoUpdateBox'] = false;
		}
	}
	
?>