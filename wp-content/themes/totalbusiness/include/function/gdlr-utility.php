<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains utility function in the theme
	*	---------------------------------------------------------------------
	*/
	
	// escape string
	if( !function_exists('totalbusiness_escape_string') ){
		function totalbusiness_escape_string($string){
			$string = wp_kses($string, array(
				'a' => array(
					'href' => array(),
					'target' => array(),
					'title' => array(),
					'style' => array()
				),
				'br' => array(),
				'div' => array(
					'class' => array(),
					'style' => array()
				),
				'i' => array(
					'class' => array(),
					'style' => array()
				),
				'em' => array(),
				'strong' => array(),
				'span' => array(
					'class' => array(),
					'style' => array()
				),
				'img' => array(
					'src' => array(),
					'width' => array(),
					'height' => array(),
					'alt' => array()
				),
			));
			return $string;
		}
	}
	
	// escape content
	if( !function_exists('totalbusiness_escape_content') ){
		function totalbusiness_escape_content($string){
			return apply_filters('totalbusiness_escape_content', $string);
		}
	}
	
	// get sidebar id from name
	if( !function_exists('totalbusiness_get_sidebar_id') ){
		function totalbusiness_get_sidebar_id($sidebar_name){
			global $wp_registered_sidebars;
			
			if( !empty($wp_registered_sidebars) && is_array($wp_registered_sidebars) ){
				foreach ( $wp_registered_sidebars as $key => $value ) {
					if($value['name'] == $sidebar_name) return $key;
				}
			}
			return '';
		}
	}	
	
	// page builder content/text filer to execute the shortcode	
	if( !function_exists('totalbusiness_content_filter') ){
		add_filter( 'totalbusiness_the_content', 'wptexturize'        ); add_filter( 'totalbusiness_the_content', 'convert_smilies'    );
		add_filter( 'totalbusiness_the_content', 'convert_chars'      ); add_filter( 'totalbusiness_the_content', 'wpautop'            );
		add_filter( 'totalbusiness_the_content', 'shortcode_unautop'  ); add_filter( 'totalbusiness_the_content', 'prepend_attachment' );	
		add_filter( 'totalbusiness_the_content', 'do_shortcode', 11   );
		function totalbusiness_content_filter( $content, $main_content = false ){
			if($main_content) return str_replace( ']]>', ']]&gt;', apply_filters('the_content', $content) );
			
			$content = preg_replace_callback('|(https?://[^\s"<]+)|im', 'totalbusiness_get_video_embed', $content );
			
			$content = str_replace("<br />", "\n", $content);
			return apply_filters('totalbusiness_the_content', $content);
		}		
	}
	if( !function_exists('totalbusiness_get_video_embed') ){
		function totalbusiness_get_video_embed($matches){
			if( strpos($matches[1], 'youtu') === false || strpos($matches[1], 'vimeo') === 'false' ){
				return $matches[1];
			} 
			
			if(!empty($matches[1])){
				return totalbusiness_get_video($matches[1]);
			}
			return '';
		}
	}
	
	if( !function_exists('totalbusiness_text_filter') ){
		add_filter( 'totalbusiness_text_filter', 'do_shortcode' );
		function totalbusiness_text_filter( $text ){
			return apply_filters('totalbusiness_text_filter', $text);
		}
	}	
	
	// filter shortcode out if the plugin is not activated
	if( !function_exists('totalbusiness_enable_shortcode_filter') ){
		add_filter( 'widget_text', 'totalbusiness_enable_shortcode_filter' );
		add_filter( 'the_content', 'totalbusiness_enable_shortcode_filter' ); 
		add_filter( 'totalbusiness_text_filter', 'totalbusiness_enable_shortcode_filter' ); 	
		add_filter( 'totalbusiness_the_content', 'totalbusiness_enable_shortcode_filter' ); 	
		function totalbusiness_enable_shortcode_filter( $text ){
			if( !function_exists('gdlr_add_tinymce_button') ){
				$text = preg_replace('#\[gdlr_[^\]]+]#', '', $text);
				$text = preg_replace('#\[/gdlr_[^\]]+]#', '', $text);
			}
			return $text;
		}
	}	
	
	if( !function_exists('totalbusiness_get_logo') ){
		function totalbusiness_get_logo(){
			global $theme_option;
?>
<!-- logo -->
<div class="totalbusiness-logo">
	<div class="totalbusiness-logo-inner">
		<a href="<?php echo esc_url(home_url('/')); ?>" >
			<?php
				if(empty($theme_option['logo-id']) || !wp_attachment_is_image($theme_option['logo-id'])){
					echo totalbusiness_get_image(get_template_directory_uri() . '/images/logo.png');
				}else{
					echo totalbusiness_get_image($theme_option['logo-id']);
				}
			?>
		</a>
	</div>
	<?php
		// mobile navigation
		if( class_exists('totalbusiness_dlmenu_walker') && has_nav_menu('main_menu') &&
			( empty($theme_option['enable-responsive-mode']) || $theme_option['enable-responsive-mode'] == 'enable' ) ){
			echo '<div class="totalbusiness-responsive-navigation dl-menuwrapper" id="totalbusiness-responsive-navigation" >';
			echo '<button class="dl-trigger">Open Menu</button>';
			wp_nav_menu( array(
				'theme_location'=>'main_menu',
				'container'=> '',
				'menu_class'=> 'dl-menu totalbusiness-main-mobile-menu',
				'walker'=> new totalbusiness_dlmenu_walker()
			) );
			echo '</div>';
		}
	?>
</div>
<?php			
		}
	}
	
	// use for generating the option from admin panel
	if( !function_exists('totalbusiness_check_option_data_type') ){
		function totalbusiness_check_option_data_type( $value, $data_type = 'color' ){
			if( $data_type == 'color' || $data_type == 'rgba' ){
				return (strpos($value, '#') === false)? '#' . $value: $value; 
			}else if( $data_type == 'text' ){
				return $value;
			}else if( $data_type == 'pixel' ){
				return (is_numeric($value))? $value . 'px': $value;
			}else if( $data_type == 'upload' ){
				if(is_numeric($value)){
					$image_src = wp_get_attachment_image_src($value, 'full');	
					return (!empty($image_src))? $image_src[0]: false;
				}else{
					return $value;
				}
			}else if( $data_type == 'font'){
				if( strpos($value, ',') === false ){
					return '"' . $value . '"';
				}
				return $value;
			}else if( $data_type == 'percent' ){
				return (is_numeric($value))? $value . '%': $value;
			}
		
		}
	}	
	if( !function_exists('totalbusiness_convert_rgba') ){
		function totalbusiness_convert_rgba($color){
			$color = str_replace('#', '', $color);
			if(strlen($color) == 3) {
				$r = hexdec(substr($color,0,1).substr($color,0,1));
				$g = hexdec(substr($color,1,1).substr($color,1,1));
				$b = hexdec(substr($color,2,1).substr($color,2,1));
			}else{
				$r = hexdec(substr($color,0,2));
				$g = hexdec(substr($color,2,2));
				$b = hexdec(substr($color,4,2));
			}
			return $r . ', ' . $g . ', ' . $b;
		}
	}
	
	// use for layouting the sidebar size
	if( !function_exists('totalbusiness_get_sidebar_class') ){
		function totalbusiness_get_sidebar_class( $sidebar = array() ){
			global $theme_option;
			
			if( $sidebar['type'] == 'no-sidebar' ){
				return array_merge($sidebar, array('right'=>'', 'outer'=>'twelve', 'left'=>'twelve', 'center'=>'twelve'));
			}else if( $sidebar['type'] == 'both-sidebar' ){
				if( $theme_option['both-sidebar-size'] == 3 ){
					return array_merge($sidebar, array('right'=>'three', 'outer'=>'nine', 'left'=>'four', 'center'=>'eight'));
				}else if( $theme_option['both-sidebar-size'] == 4 ){
					return array_merge($sidebar, array('right'=>'four', 'outer'=>'eight', 'left'=>'six', 'center'=>'six'));
				}
			}else{ 
			
				// determine the left/right sidebar size
				$size = ''; $center = '';
				switch ($theme_option['sidebar-size']){
					case 1: $size = 'one'; $center = 'eleven'; break;
					case 2: $size = 'two'; $center = 'ten'; break;
					case 3: $size = 'three'; $center = 'nine'; break;
					case 4: $size = 'four'; $center = 'eight'; break;
					case 5: $size = 'five'; $center = 'seven'; break;
					case 6: $size = 'six'; $center = 'six'; break;
				}

				if( $sidebar['type'] == 'left-sidebar'){
					$sidebar['outer'] = 'twelve';
					$sidebar['left'] = $size;
					$sidebar['center'] = $center;
					return $sidebar;
				}else if( $sidebar['type'] == 'right-sidebar'){
					$sidebar['outer'] = $center;
					$sidebar['right'] = $size;
					$sidebar['center'] = 'twelve';
					return $sidebar;			
				}
			}
		}
	}

	// retrieve all posts as a list
	if( !function_exists('totalbusiness_get_post_list') ){	
		function totalbusiness_get_post_list( $post_type ){
			$post_list = get_posts(array('post_type' => $post_type, 'numberposts'=>1000));

			$ret = array();
			if( !empty($post_list) ){
				foreach( $post_list as $post ){
					$ret[$post->post_name] = $post->post_title;
				}
			}
				
			return $ret;
		}	
	}	
	
	// retrieve all categories from each post type
	if( !function_exists('totalbusiness_get_term_list') ){	
		function totalbusiness_get_term_list( $taxonomy, $parent='' ){
			$term_list = get_categories( array('taxonomy'=>$taxonomy, 'hide_empty'=>0, 'parent'=>$parent) );

			$ret = array();
			if( !empty($term_list) && empty($term_list['errors']) ){
				foreach( $term_list as $term ){
					if( !empty($term->slug) ){
						$ret[$term->slug] = $term->name;
					}
				}
			}
				
			return $ret;
		}	
	}	
	
	// string to css class name
	if( !function_exists('totalbusiness_string_to_class') ){	
		function totalbusiness_string_to_class($string){
			$class = preg_replace('#[^\w\s]#','',strtolower(strip_tags($string)));
			$class = preg_replace('#\s+#', '-', trim($class));
			return 'totalbusiness-skin-' . $class;
		}
	}
	
	// calculate the size as a number ex "1/2" = 0.5
	if( !function_exists('totalbusiness_item_size_to_num') ){	
		function totalbusiness_item_size_to_num( $size ){
			if( preg_match('/^(\d+)\/(\d+)$/', $size, $size_array) )
			return $size_array[1] / $size_array[2];
			return 1;
		}	
	}		
	
	// get skin list
	if( !function_exists('totalbusiness_get_skin_list') ){	
		function totalbusiness_get_skin_list(){
			global $theme_option;
		
			$skin_list = array('no-skin'=>esc_html__('No Skin', 'totalbusiness'));
			if( !empty($theme_option['skin-settings']) ){
				$skins = json_decode($theme_option['skin-settings'], true);
				if( !empty($skins) ){
					foreach( $skins as $skin ){
						$skin_list[totalbusiness_string_to_class($skin['skin-title'])] = $skin['skin-title'];
					}
				}
			}
			return $skin_list;
		}
	}
	
	// create pagination link
	if( !function_exists('totalbusiness_get_pagination') ){	
		function totalbusiness_get_pagination($max_num_page, $current_page, $format = 'paged'){
			if( $max_num_page <= 1 ) return '';
		
			$big = 999999999; // need an unlikely integer
			return 	'<div class="totalbusiness-pagination">' . paginate_links(array(
				'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format' => '?' . $format . '=%#%',
				'current' => max(1, $current_page),
				'total' => $max_num_page,
				'prev_text'=> esc_html__('&lsaquo; Previous', 'totalbusiness'),
				'next_text'=> esc_html__('Next &rsaquo;', 'totalbusiness')
			)) . '</div>';
		}	
	}		
	if( !function_exists('totalbusiness_get_ajax_pagination') ){	
		function totalbusiness_get_ajax_pagination($max_num_page, $current_page){
			if( $max_num_page <= 1 ) return '';
		
			$ret  = '<div class="totalbusiness-pagination totalbusiness-ajax">';
			if($current_page > 1){ 
				$ret .= '<a class="prev page-numbers" data-paged="' . (intval($current_page) - 1) . '" >';
				$ret .= esc_html__('&lsaquo; Previous', 'totalbusiness') . '</a> '; 
			}
			for($i=1; $i<=$max_num_page; $i++){
				if( $i == $current_page ){
					$ret .= '<span class="page-numbers current" data-paged="' . $i . '" >' . $i . '</span> ';
				}else{
					$ret .= '<a class="page-numbers" data-paged="' . $i . '" >' . $i . '</a> ';
				}
			}
			if($current_page < $max_num_page){ 
				$ret .= '<a class="next page-numbers" data-paged="' . (intval($current_page) + 1) . '" > ';
				$ret .= esc_html__('Next &rsaquo;', 'totalbusiness') . '</a> '; 
			}
			$ret .= '</div>';
			return $ret;
		}	
	}	
	
	// convert font awesome class to new version
	if( !function_exists('totalbusiness_fa_class') ){	
		function totalbusiness_fa_class($class){
			global $theme_option;
			
			$class = str_replace('icon-', 'fa-', $class);
			return str_replace('-alt', '-o', $class);
		}
	}

?>