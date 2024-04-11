<?php
	/*	
	*	Goodlayers Portfolio Item Management File
	*	---------------------------------------------------------------------
	*	This file contains functions that help you create portfolio item
	*	---------------------------------------------------------------------
	*/
	
	// add action to check for portfolio item
	add_action('totalbusiness_print_item_selector', 'totalbusiness_check_portfolio_item', 10, 2);
	if( !function_exists('totalbusiness_check_portfolio_item') ){
		function totalbusiness_check_portfolio_item( $type, $settings = array() ){
			if($type == 'portfolio'){
				echo totalbusiness_print_portfolio_item( $settings );
			}
		}
	}

	// include portfolio script
	if( !function_exists('totalbusiness_include_portfolio_scirpt') ){
		function totalbusiness_include_portfolio_scirpt( $settings = array() ){
			wp_enqueue_script('isotope', get_template_directory_uri() . '/plugins/jquery.isotope.min.js', array(), '1.0', true);
			wp_enqueue_script('jquery.transit', get_template_directory_uri() . '/plugins/jquery.transit.min.js', array(), '1.0', true);	
			wp_enqueue_script('portfolio-script', plugins_url('gdlr-portfolio-script.js', __FILE__), array(), '1.0', true);			
		}
	}
	
	// print portfolio item
	if( !function_exists('totalbusiness_print_portfolio_item') ){
		function totalbusiness_print_portfolio_item( $settings = array() ){
			totalbusiness_include_portfolio_scirpt();
		
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			if( $settings['portfolio-layout'] == 'carousel' ){ 
				$settings['carousel'] = true;
			}
			
			$ret  = totalbusiness_get_item_title($settings);				
			$ret .= '<div class="portfolio-item-wrapper type-' . $settings['portfolio-style'] . '" ';
			$ret .= $item_id . $margin_style . ' data-ajax="' . AJAX_URL . '" >'; 
			
			// query posts section
			$args = array('post_type' => 'portfolio', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : 1;

			if( !empty($settings['category']) || (!empty($settings['tag']) && $settings['portfolio-filter'] == 'disable') ){
				$args['tax_query'] = array('relation' => 'OR');
				
				if( !empty($settings['category']) ){
					array_push($args['tax_query'], array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'portfolio_category', 'field'=>'slug'));
				}
				if( !empty($settings['tag']) && $settings['portfolio-filter'] == 'disable' ){
					array_push($args['tax_query'], array('terms'=>explode(',', $settings['tag']), 'taxonomy'=>'portfolio_tag', 'field'=>'slug'));
				}				
			}			
			$query = new WP_Query( $args );

			// create the portfolio filter
			$settings['num-excerpt'] = empty($settings['num-excerpt'])? 0: $settings['num-excerpt'];
			$settings['portfolio-size'] = str_replace('1/', '', $settings['portfolio-size']);
			$settings['thumbnail-size-featured'] = empty($settings['thumbnail-size-featured'])? $settings['thumbnail-size']: $settings['thumbnail-size-featured'];
			if( $settings['portfolio-filter'] == 'enable' ){
			
				// ajax infomation
				$ret .= '<div class="totalbusiness-ajax-info" data-num-fetch="' . $args['posts_per_page'] . '" data-num-excerpt="' . $settings['num-excerpt'] . '" ';
				$ret .= 'data-orderby="' . $args['orderby'] . '" data-order="' . $args['order'] . '" data-thumbnail-size-featured="' . $settings['thumbnail-size-featured'] . '" ';
				$ret .= 'data-thumbnail-size="' .  $settings['thumbnail-size'] . '" data-port-style="' . $settings['portfolio-style'] . '" ';
				$ret .= 'data-port-size="' . $settings['portfolio-size'] . '" data-port-layout="' .  $settings['portfolio-layout'] . '" ';
				$ret .= 'data-ajax="' . admin_url('admin-ajax.php') . '" data-category="' . $settings['category'] . '" data-pagination="' . $settings['pagination'] . '" ></div>';
			
				// category filter
				if( empty($settings['category']) ){
					$parent = array('totalbusiness-all'=>__('All', 'gdlr-portfolio'));
					$settings['category-id'] = '';
				}else{
					$term = get_term_by('slug', $settings['category'], 'portfolio_category');
					$parent = array($settings['category']=>$term->name);
					$settings['category-id'] = $term->term_id;
				}
				
				$filters = $parent + totalbusiness_get_term_list('portfolio_category', $settings['category-id']);
				$filter_active = 'active';
				$ret .= '<div class="portfolio-item-filter">';
				foreach($filters as $filter_id => $filter){
					$filter_id = ($filter_id == 'totalbusiness-all')? '': $filter_id;

					$ret .= '<a class="' . $filter_active . '" href="#" ';
					$ret .= 'data-category="' . $filter_id . '" >' . $filter . '</a>';
					$filter_active = '';
				}
				$ret .= '</div>';
			}
			
			$no_space  = (strpos($settings['portfolio-style'], 'no-space') > 0)? 'totalbusiness-item-no-space': '';
			$no_space .= ' totalbusiness-portfolio-column-' . $settings['portfolio-size'];
			$ret .= '<div class="portfolio-item-holder ' . $no_space . '">';
			if( $settings['portfolio-style'] == 'classic-portfolio' || 
				$settings['portfolio-style'] == 'classic-portfolio-no-space'){
				
				global $totalbusiness_excerpt_length; $totalbusiness_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'totalbusiness_set_excerpt_length');
				
				$ret .= totalbusiness_get_classic_portfolio($query, $settings['portfolio-size'], 
							$settings['thumbnail-size'], $settings['portfolio-layout'] );
							
				remove_filter('excerpt_length', 'totalbusiness_set_excerpt_length');
			}else if($settings['portfolio-style'] == 'modern-portfolio' || 
				$settings['portfolio-style'] == 'modern-portfolio-no-space'){	
				
				$ret .= totalbusiness_get_modern_portfolio($query, $settings['portfolio-size'], 
							$settings['thumbnail-size'], $settings['portfolio-layout'], $settings['thumbnail-size-featured'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			// create pagination
			if($settings['portfolio-filter'] == 'enable' && $settings['pagination'] == 'enable'){
				$ret .= totalbusiness_get_ajax_pagination($query->max_num_pages, $args['paged']);
			}else if($settings['pagination'] == 'enable'){
				$ret .= totalbusiness_get_pagination($query->max_num_pages, $args['paged']);
			}
			
			$ret .= '</div>'; // portfolio-item-wrapper
			return $ret;
		}
	}
	
	// ajax function for portfolio filter / pagination
	add_action('wp_ajax_totalbusiness_get_portfolio_ajax', 'totalbusiness_get_portfolio_ajax');
	add_action('wp_ajax_nopriv_totalbusiness_get_portfolio_ajax', 'totalbusiness_get_portfolio_ajax');
	if( !function_exists('totalbusiness_get_portfolio_ajax') ){
		function totalbusiness_get_portfolio_ajax(){
			$settings = $_POST['args'];

			$args = array('post_type' => 'portfolio', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (empty($settings['paged']))? 1: $settings['paged'];
				
			if( !empty($settings['category']) ){
				$args['tax_query'] = array(
					array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'portfolio_category', 'field'=>'slug')
				);
			}			
			$query = new WP_Query( $args );
			
			$no_space = (strpos($settings['portfolio-style'], 'no-space') > 0)? 'totalbusiness-item-no-space': '';
			$no_space .= ' totalbusiness-portfolio-column-' . $settings['portfolio-size'];
			$ret  = '<div class="portfolio-item-holder ' . $no_space . '">';
			if( $settings['portfolio-style'] == 'classic-portfolio' || 
				$settings['portfolio-style'] == 'classic-portfolio-no-space'){
				
				global $totalbusiness_excerpt_length; $totalbusiness_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'totalbusiness_set_excerpt_length');
				
				$ret .= totalbusiness_get_classic_portfolio($query, $settings['portfolio-size'], 
							$settings['thumbnail-size'], $settings['portfolio-layout'] );
							
				remove_filter('excerpt_length', 'totalbusiness_set_excerpt_length');
			}else if($settings['portfolio-style'] == 'modern-portfolio' || 
				$settings['portfolio-style'] == 'modern-portfolio-no-space'){	
				
				$ret .= totalbusiness_get_modern_portfolio($query, $settings['portfolio-size'], 
							$settings['thumbnail-size'], $settings['portfolio-layout'], $settings['thumbnail-size-featured'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			// pagination section
			if($settings['pagination'] == 'enable'){
				$ret .= totalbusiness_get_ajax_pagination($query->max_num_pages, $args['paged']);
			}
			die($ret);
		}
	}
	
	// get portfolio info
	if( !function_exists('totalbusiness_get_portfolio_info') ){
		function totalbusiness_get_portfolio_info( $array = array(), $option = array(), $wrapper = true ){
			$ret = '';
			
			foreach($array as $post_info){	
				switch( $post_info ){
					case 'clients':
						if(empty($option['clients'])) break;
					
						$ret .= '<div class="portfolio-info portfolio-clients">';
						$ret .= '<span class="info-head totalbusiness-title">' . __('Client', 'gdlr-portfolio') . ' </span>';
						$ret .= $option['clients'];						
						$ret .= '</div>';						
					
						break;	
					case 'skills':
						if(empty($option['skills'])) break;
					
						$ret .= '<div class="portfolio-info portfolio-skills">';
						$ret .= '<span class="info-head totalbusiness-title">' . __('Skills', 'gdlr-portfolio') . ' </span>';
						$ret .= $option['skills'];						
						$ret .= '</div>';						

						break;	
					case 'website':
						if(empty($option['website'])) break;
					
						$ret .= '<div class="portfolio-info portfolio-website">';
						$ret .= '<span class="info-head totalbusiness-title">' . __('Website', 'gdlr-portfolio') . ' </span>';
						$ret .= '<a href="' . $option['website'] . '" target="_blank" >' . $option['website'] . '</a>';					
						$ret .= '</div>';						
					
						break;
					case 'tag':
						$tag = get_the_term_list(get_the_ID(), 'portfolio_tag', '', '<span class="sep">,</span> ' , '' );
						if(empty($tag)) break;					
					
						$ret .= '<div class="portfolio-info portfolio-tag">';
						$ret .= '<span class="info-head totalbusiness-title">' . __('Tags', 'gdlr-portfolio') . ' </span>';
						$ret .= $tag;						
						$ret .= '</div>';						
						break;					
				}
			}

			if($wrapper && !empty($ret)){
				return '<div class="totalbusiness-portfolio-info">' . $ret . '<div class="clear"></div></div>';
			}else if( !empty($ret) ){
				return $ret . '<div class="clear"></div>';
			}
			return '';
		}
	}

	// get portfolio thumbnail class
	if( !function_exists('totalbusiness_get_portfolio_thumbnail_class') ){
		function totalbusiness_get_portfolio_thumbnail_class( $post_option ){
			global $totalbusiness_related_section;
			if( is_single() && $post_option['inside-thumbnail-type'] != 'thumbnail-type'
				&& empty($totalbusiness_related_section) ){ $type = 'inside-';
			}else{ $type = ''; }	

			switch($post_option[$type . 'thumbnail-type']){
				case 'feature-image': return 'totalbusiness-image' ;
				case 'image': return 'totalbusiness-image' ;
				case 'video': return 'totalbusiness-video' ;
				case 'slider': return 'totalbusiness-slider' ;		
				case 'stack-images': return 'totalbusiness-stack-images' ;
				default: return '';
			}			
		}
	}

	// get portfolio icon class
	if( !function_exists('totalbusiness_get_portfolio_icon_class') ){
		function totalbusiness_get_portfolio_icon_class($post_option){
			global $theme_option;
		
			switch($post_option['thumbnail-link']){
				case 'current-post': return 'fa fa-link' ;
				case 'current': return 'fa fa-search' ;
				case 'url': return 'fa fa-link' ;
				case 'image': return 'fa fa-search' ;
				case 'video': return 'fa fa-film' ;
				default: return 'fa fa-link';
			}			
		}
	}	
	
	// get portfolio link attribute
	if( !function_exists('totalbusiness_get_portfolio_thumbnail_link') ){
		function totalbusiness_get_portfolio_thumbnail_link($post_option, $location = 'media'){
			if($location == 'title'){  
				$link_type = (!empty($post_option['thumbnail-link']) && $post_option['thumbnail-link'] == 'url')? 'url': 'current-post';
			}else{
				$link_type = $post_option['thumbnail-link'];
			}
		
			switch($link_type){
				case 'current':
					$image_full = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
					return ' href="' . $image_full[0] . '" data-rel="fancybox" ';
				case 'url': 
					$ret  = ' href="' . $post_option['thumbnail-url'] . '" ';
					$ret .= ($post_option['thumbnail-new-tab'] == 'enable')? 'target="_blank" ': '';
					return $ret;
				case 'image': return ' href="' . $post_option['thumbnail-url'] . '" data-rel="fancybox" ';
				case 'video': return ' href="' . $post_option['thumbnail-url'] . '" data-rel="fancybox" data-fancybox-type="iframe" ';
				case 'current-post': default: return ' href="' . get_permalink() . '" ';
			}
			
		}
	}	
	
	// get portfolio thumbnail
	if( !function_exists('totalbusiness_get_portfolio_thumbnail') ){
		function totalbusiness_get_portfolio_thumbnail($post_option, $size = 'full', $modern_style = false){
			global $totalbusiness_related_section;
			if( is_single() && $post_option['inside-thumbnail-type'] != 'thumbnail-type'
				&& empty($totalbusiness_related_section)){ $type = 'inside-';
			}else{ $type = ''; }
			
			$ret = '';
			switch($post_option[$type . 'thumbnail-type']){
				case 'feature-image':
					$image_id = get_post_thumbnail_id();
					if( !empty($image_id) ){
						if( $modern_style ){
							$ret  = totalbusiness_get_image($image_id, $size);
							$ret .= '<span class="portfolio-overlay" >&nbsp;</span>';
							$ret .= '<div class="portfolio-overlay-content">';
							$ret .= '<h3 class="portfolio-title"><a ' . totalbusiness_get_portfolio_thumbnail_link($post_option) . '>' . get_the_title() . '</a></h3>';
							$ret .= totalbusiness_get_portfolio_info(array('tag'));
							$ret .= '</div>'; // portfolio-overlay-content	
							$ret .= '<a class="portfolio-overlay-icon" ' . totalbusiness_get_portfolio_thumbnail_link($post_option) . ' >';
							$ret .= '<span class="portfolio-icon" ><i class="' . totalbusiness_get_portfolio_icon_class($post_option) . '" ></i></span>';
							$ret .= '</a>';			
						}else if( !is_single() || $totalbusiness_related_section ){
							$ret  = totalbusiness_get_image($image_id, $size);
							$ret .= '<span class="portfolio-overlay" >&nbsp;</span>';
							$ret .= '<a class="portfolio-overlay-icon" ' . totalbusiness_get_portfolio_thumbnail_link($post_option) . ' >';
							$ret .= '<span class="portfolio-icon" ><i class="' . totalbusiness_get_portfolio_icon_class($post_option) . '" ></i></span>';
							$ret .= '</a>';			
						}else{
							$ret  = totalbusiness_get_image($image_id, $size, true);
						}
					}
					break;			
				case 'image':
					$ret = totalbusiness_get_image($post_option[$type . 'thumbnail-image'], $size, true);
					break;
				case 'video': 
					if( is_single() && empty($totalbusiness_related_section) ){
						$ret = totalbusiness_get_video($post_option[$type . 'thumbnail-video'], 'full');
					}else{
						$ret = totalbusiness_get_video($post_option[$type . 'thumbnail-video'], $size);
					}
					break;
				case 'slider': 
					$ret = totalbusiness_get_slider($post_option[$type . 'thumbnail-slider'], $size);
					break;					
				case 'stack-image': 
					$ret = totalbusiness_get_stack_images($post_option[$type . 'thumbnail-slider']);
					break;
				default :
					$ret = '';
			}			

			return $ret;
		}
	}	
	
	// print classic portfolio
	if( !function_exists('totalbusiness_get_classic_portfolio') ){
		function totalbusiness_get_classic_portfolio($query, $size, $thumbnail_size, $layout = 'fitRows'){
			if($layout == 'carousel'){ 
				return totalbusiness_get_classic_carousel_portfolio($query, $size, $thumbnail_size); 
			}		
		
			global $post;

			$current_size = 0;
			$ret  = '<div class="totalbusiness-isotope" data-type="portfolio" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
    
				$ret .= '<div class="' . totalbusiness_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="totalbusiness-item totalbusiness-portfolio-item totalbusiness-classic-portfolio">';
				$ret .= '<div class="totalbusiness-ux totalbusiness-classic-portfolio-ux">';
				
				$port_option = json_decode(totalbusiness_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="portfolio-thumbnail ' . totalbusiness_get_portfolio_thumbnail_class($port_option) . '">';
				$ret .= totalbusiness_get_portfolio_thumbnail($port_option, $thumbnail_size);
				$ret .= '</div>'; // portfolio-thumbnail
				
				$ret .= '<div class="portfolio-classic-content">';
				$ret .= '<h3 class="portfolio-title"><a ' . totalbusiness_get_portfolio_thumbnail_link($port_option, 'title') . ' >' . get_the_title() . '</a></h3>';
				$ret .= totalbusiness_get_portfolio_info(array('tag'));
				$ret .= '<div class="portfolio-excerpt">' . get_the_excerpt() . '</div>';
				$ret .= '</div>'; // portfolio-classic-content
				$ret .= '</div>'; // totalbusiness-ux
				$ret .= '</div>'; // totalbusiness-item
				$ret .= '</div>'; // column class
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	
	if( !function_exists('totalbusiness_get_classic_carousel_portfolio') ){
		function totalbusiness_get_classic_carousel_portfolio($query, $size, $thumbnail_size){	
			global $post;

			$ret  = '<div class="totalbusiness-portfolio-carousel-item totalbusiness-item" >';	
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="portfolio-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="totalbusiness-item totalbusiness-portfolio-item totalbusiness-classic-portfolio">';

				$port_option = json_decode(totalbusiness_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="portfolio-thumbnail ' . totalbusiness_get_portfolio_thumbnail_class($port_option) . '">';
				$ret .= totalbusiness_get_portfolio_thumbnail($port_option, $thumbnail_size);
				$ret .= '</div>'; // portfolio-thumbnail
				
				$ret .= '<div class="portfolio-classic-content">';
				$ret .= totalbusiness_get_portfolio_info(array('tag'));
				$ret .= '<div class="portfolio-excerpt">' . get_the_excerpt() . '</div>';
				$ret .= '</div>';
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>';
			$ret .= '</div>';
			
			return $ret;
		}		
	}	
	
	// print modern portfolio
	if( !function_exists('totalbusiness_get_modern_portfolio') ){
		function totalbusiness_get_modern_portfolio($query, $size, $thumbnail_size, $layout = 'fitRows', $thumbnail_size_featured = 'full'){
			if($layout == 'carousel'){ 
				return totalbusiness_get_modern_carousel_portfolio($query, $size, $thumbnail_size); 
			}else if($layout == 'masonry-style-1'){
				$layout = 'masonry';
				$featured_post = array(0);
			}else if($layout == 'masonry-style-2'){
				$layout = 'masonry';
				$featured_post = array(0,4,6,7,11,15,17,18);
			}
			
			global $post;

			$current_size = 0;
			$ret  = '<div class="totalbusiness-isotope" data-type="portfolio" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}	
    
				$ret .= '<div class="' . totalbusiness_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="totalbusiness-item totalbusiness-portfolio-item totalbusiness-modern-portfolio">';
				$ret .= '<div class="totalbusiness-ux totalbusiness-modern-portfolio-ux">';
				
				$port_option = json_decode(totalbusiness_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="portfolio-thumbnail ' . totalbusiness_get_portfolio_thumbnail_class($port_option) . '">';
				if( !empty($featured_post) && in_array($current_size, $featured_post) ){
					$ret .= totalbusiness_get_portfolio_thumbnail($port_option, $thumbnail_size_featured, true);
				}else{
					$ret .= totalbusiness_get_portfolio_thumbnail($port_option, $thumbnail_size, true);
				}
				$ret .= '</div>'; // portfolio-thumbnail	
				$ret .= '</div>'; // totalbusiness-ux
				$ret .= '</div>'; // totalbusiness-item
				$ret .= '</div>'; // totalbusiness-column-class
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	
	if( !function_exists('totalbusiness_get_modern_carousel_portfolio') ){
		function totalbusiness_get_modern_carousel_portfolio($query, $size, $thumbnail_size){	
			global $post;

			$ret  = '<div class="totalbusiness-portfolio-carousel-item totalbusiness-item" >';		
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="portfolio-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="totalbusiness-item totalbusiness-portfolio-item totalbusiness-modern-portfolio">';
				
				$port_option = json_decode(totalbusiness_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="portfolio-thumbnail ' . totalbusiness_get_portfolio_thumbnail_class($port_option) . '">';
				$ret .= totalbusiness_get_portfolio_thumbnail($port_option, $thumbnail_size, true);
				$ret .= '</div>'; // portfolio-thumbnail
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>'; // flexslider
			$ret .= '</div>'; // totalbusiness-item
			
			return $ret;
		}		
	}
	
?>