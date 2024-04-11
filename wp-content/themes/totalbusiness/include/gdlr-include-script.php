<?php
	/*	
	*	Goodlayers Include Script File
	*	---------------------------------------------------------------------
	*	This file use to include a necessary script when it's requires
	*	---------------------------------------------------------------------
	*/
	
	// search the pagebuilder array to check whether the item is exists Ex. ('item', 'slider', ('slider-type', 'flexslider'))
	if( !function_exists('totalbusiness_search_page_builder') ){
		function totalbusiness_search_page_builder($array, $item_type, $type, $data = array()){
			foreach($array as $item){
				if($item['item-type'] == $item_type && $item['type'] == $type){
					if(empty($data)){
						return true;
					}else{	
						if( strpos($item['option'][$data[0]], $data[1]) !== false ) return true;
					}
				}
				if($item['item-type'] == 'wrapper'){
					if( totalbusiness_search_page_builder($item['items'], $item_type, $type) ) return true;
				}
			}
			return false;
		}
	}	
	
	// set the global variable based on the opened page, post, ...
	add_action('wp', 'totalbusiness_set_global_variable');
	if( !function_exists('totalbusiness_set_global_variable') ){
		function totalbusiness_set_global_variable(){
			global $post;
		
			if( is_page() ){
				global $above_sidebar_content, $with_sidebar_content, $below_sidebar_content, $totalbusiness_post_option;
				
				$above_sidebar_content = json_decode(totalbusiness_decode_preventslashes(get_post_meta(get_the_ID(), 'above-sidebar', true)), true);
				$above_sidebar_content = (empty($above_sidebar_content))? array(): $above_sidebar_content;
				
				$with_sidebar_content = json_decode(totalbusiness_decode_preventslashes(get_post_meta(get_the_ID(), 'content-with-sidebar', true)), true);
				$with_sidebar_content = (empty($with_sidebar_content))? array(): $with_sidebar_content;
				
				$below_sidebar_content = json_decode(totalbusiness_decode_preventslashes(get_post_meta(get_the_ID(), 'below-sidebar', true)), true);
				$below_sidebar_content = (empty($below_sidebar_content))? array(): $below_sidebar_content;
				
				$totalbusiness_post_option = totalbusiness_decode_preventslashes(get_post_meta($post->ID, 'post-option', true));
			}else if( is_single() || (!empty($post) && $post->post_type == 'portfolio') ){
				global $totalbusiness_post_option;
			
				$totalbusiness_post_option = totalbusiness_decode_preventslashes(get_post_meta($post->ID, 'post-option', true));
			}
			
			
		}
	}
	
	// register the necessary script depends on the condition of that page
	if( !is_admin() ){ add_filter('totalbusiness_enqueue_scripts', 'totalbusiness_regiser_use_script'); }
	if( !function_exists('totalbusiness_regiser_use_script') ){
		function totalbusiness_regiser_use_script($array){	
			global $theme_option, $totalbusiness_post_option;
		
			if( is_page() ){
				global $above_sidebar_content, $with_sidebar_content, $below_sidebar_content;
				$all_page_builder = array_merge($above_sidebar_content, $with_sidebar_content, $below_sidebar_content);
			}else{
				$all_page_builder = array();
			}
			
			// jquery easing
			$array['script']['jquery-easing'] = get_template_directory_uri() . '/plugins/jquery.easing.js';
			
			// font awesome
			$array['style']['font-awesome'] = get_template_directory_uri() . '/plugins/font-awesome-new/css/font-awesome.min.css';
			
			// elegant font
			if( empty($theme_option['enable-elegant-font']) || $theme_option['enable-elegant-font'] == 'enable' ){
				$array['style']['elegant-font'] = get_template_directory_uri() . '/plugins/elegant-font/style.css';
			}
			
			// fancybox
			if( empty($theme_option['enable-fancybox']) || $theme_option['enable-fancybox'] != 'disable' ){
				$array['style']['jquery-fancybox'] = get_template_directory_uri() . '/plugins/fancybox/jquery.fancybox.css';
				$array['script']['jquery-fancybox'] = get_template_directory_uri() . '/plugins/fancybox/jquery.fancybox.pack.js';
				$array['script']['jquery-fancybox-media'] = get_template_directory_uri() . '/plugins/fancybox/helpers/jquery.fancybox-media.js';
				
				if( empty($theme_option['enable-fancybox-thumbs']) || $theme_option['enable-fancybox-thumbs'] != 'disable' ){
					$array['script']['jquery-fancybox-thumbs'] = get_template_directory_uri() . '/plugins/fancybox/helpers/jquery.fancybox-thumbs.js';
				}
			}

			// flexslider
			if( is_search() || is_archive() || 
				( empty($theme_option['enable-flex-slider']) || $theme_option['enable-flex-slider'] != 'disable' ) ||
				( is_page() && totalbusiness_search_page_builder($all_page_builder, 'item', 'blog') ) ||
				( is_page() && totalbusiness_search_page_builder($all_page_builder, 'item', 'portfolio') ) ||
				( is_page() && totalbusiness_search_page_builder($all_page_builder, 'item', 'post-slider') ) ||
				( is_page() && totalbusiness_search_page_builder($all_page_builder, 'item', 'slider', array('slider-type', 'flexslider')) ) ||
				( is_page() && totalbusiness_search_page_builder($all_page_builder, 'item', 'personnel', array('personnel-type', 'carousel')) ) ||
				( is_page() && totalbusiness_search_page_builder($all_page_builder, 'item', 'testimonial', array('testimonial-type', 'carousel')) ) ||
				( is_single() && strpos($totalbusiness_post_option, '"inside-thumbnail-type":"slider"') ) ||
				( is_single() && strpos($totalbusiness_post_option, '"inside-thumbnail-type":"thumbnail-type"') && strpos($totalbusiness_post_option, '"thumbnail-type":"slider"') )
			){
				$array['style']['totalbusiness-flexslider'] = get_template_directory_uri() . '/plugins/flexslider/flexslider.css';
				$array['script']['totalbusiness-flexslider'] = get_template_directory_uri() . '/plugins/flexslider/jquery.flexslider.js';
			}
			
			// nivoslider
			if( is_page() && totalbusiness_search_page_builder($all_page_builder, 'item', 'slider', array('slider-type', 'nivoslider')) ){
				$array['style']['nivo-slider'] = get_template_directory_uri() . '/plugins/nivo-slider/nivo-slider.css';
				$array['script']['nivo-slider'] = get_template_directory_uri() . '/plugins/nivo-slider/jquery.nivo.slider.js';
			}
			
			// isotope
			if( is_page() && totalbusiness_search_page_builder($all_page_builder, 'item', 'blog', array('blog-style', 'blog-1-')) &&
				totalbusiness_search_page_builder($all_page_builder, 'item', 'blog', array('blog-layout', 'masonry')) ){
				$array['script']['isotope'] = get_template_directory_uri() . '/plugins/jquery.isotope.min.js';
			}
			
			// mbyt video background 
			if( is_page() && totalbusiness_search_page_builder($all_page_builder, 'wrapper', 'parallax-bg-wrapper', array('type', 'video')) ){
				$array['style']['mbytplayer'] = get_template_directory_uri() . '/plugins/mbyt-player/YTPlayer.css';
				$array['script']['mbytplayer'] = get_template_directory_uri() . '/plugins/mbyt-player/jquery.mb.YTPlayer.js';
			}	
				
			// include responsive style
			if( empty($theme_option['enable-responsive-mode']) || $theme_option['enable-responsive-mode'] == 'enable' ){
				$array['style']['style-responsive'] = get_template_directory_uri() . '/stylesheet/style-responsive.css';	
			}
			
			// include theme script / style
			$array['script']['totalbusiness-script'] = get_template_directory_uri() . '/javascript/gdlr-script.js';			
			
			// include custom style at the last
			$multisite = get_current_blog_id();
			if( empty($multisite) || $multisite == 1 ){
				$array['style']['style-custom']  = get_template_directory_uri() . '/stylesheet/style-custom.css';		
				$array['style']['style-custom'] .= '?' . filemtime(get_template_directory() . '/stylesheet/style-custom.css');		
			}else{
				$array['style']['style-custom']  = get_template_directory_uri() . '/stylesheet/style-custom' . $multisite . '.css';			
				$array['style']['style-custom'] .= '?' . filemtime(get_template_directory() . '/stylesheet/style-custom' . $multisite . '.css');			
			}
			
			return $array;
		}
	}	
 
?>