<?php
	/*	
	*	Goodlayers Blog Item Management File
	*	---------------------------------------------------------------------
	*	This file contains functions that help you get blog item
	*	---------------------------------------------------------------------
	*/
	
	if( !function_exists('totalbusiness_get_blog_item') ){
		function totalbusiness_get_blog_item( $settings = array() ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			if( strpos($settings['blog-style'], 'blog-1-') !== false && $settings['blog-layout'] == 'carousel' ){
				$settings['carousel'] = true;
			}
			
			$ret  = totalbusiness_get_item_title($settings);
			$ret .= '<div class="blog-item-wrapper"  ' . $item_id . $margin_style . '>';

			// query post and sticky post
			$args = array('post_type' => 'post', 'suppress_filters' => false);
			if( !empty($settings['category']) || !empty($settings['tag']) ){
				$args['tax_query'] = array('relation' => 'OR');
				
				if( !empty($settings['category']) ){
					array_push($args['tax_query'], array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'category', 'field'=>'slug'));
				}
				if( !empty($settings['tag']) ){
					array_push($args['tax_query'], array('terms'=>explode(',', $settings['tag']), 'taxonomy'=>'post_tag', 'field'=>'slug'));
				}				
			}

			if( $settings['enable-sticky'] == 'enable' ){
				if( get_query_var('paged') <= 1 ){
					$sticky_args = $args;
					$sticky_args['post__in'] = get_option('sticky_posts');
					if( !empty($sticky_args['post__in']) ){
						$sticky_query = new WP_Query($sticky_args);	
					}
				}
				$args['post__not_in'] = get_option('sticky_posts', '');
			}else{
				$args['ignore_sticky_posts'] = 1;
			}
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : get_query_var('page');
			$args['paged'] = empty($args['paged'])? 1: $args['paged'];
			
			$settings['offset'] = (empty($settings['offset']))? 0: intval($settings['offset']);
			$args['offset'] = (empty($settings['offset']))? "": ((intval($args['paged']) - 1) * intval($args['posts_per_page'])) + $settings['offset'];				
			$query = new WP_Query( $args );
			
			$query->max_num_pages = ceil(($query->found_posts - $settings['offset']) / intval($args['posts_per_page']));
			
			// merge query
			if( !empty($sticky_query) ){
				$query->posts = array_merge($sticky_query->posts, $query->posts);
				$query->post_count = $sticky_query->post_count + $query->post_count;
			}

			// set the excerpt length
			if( !empty($settings['num-excerpt']) ){
				global $totalbusiness_excerpt_length; $totalbusiness_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'totalbusiness_set_excerpt_length');
			} 
			
			// get blog by the blog style
			global $totalbusiness_post_settings, $totalbusiness_lightbox_id;
			$totalbusiness_lightbox_id++;
			$totalbusiness_post_settings['excerpt'] = intval($settings['num-excerpt']);
			$totalbusiness_post_settings['thumbnail-size'] = $settings['thumbnail-size'];			
			$totalbusiness_post_settings['blog-style'] = $settings['blog-style'];			
			
			$ret .= '<div class="blog-item-holder">';
			if($settings['blog-style'] == 'blog-full'){
				$ret .= totalbusiness_get_blog_full($query);
			}else if($settings['blog-style'] == 'blog-medium'){
				$ret .= totalbusiness_get_blog_medium($query);			
			}else if(strpos($settings['blog-style'], 'blog-widget') !== false){
				$blog_size = str_replace('blog-widget-1-', '', $settings['blog-style']);
				$ret .= totalbusiness_get_blog_widget($query, $blog_size, $settings['blog-layout']);			
			}else{
				$blog_size = str_replace('blog-1-', '', $settings['blog-style']);
				$ret .= totalbusiness_get_blog_grid($query, $blog_size, $settings['blog-layout']);
			}
			$ret .= '</div>';
			
			if( $settings['pagination'] == 'enable' ){
				$ret .= totalbusiness_get_pagination($query->max_num_pages, $args['paged']);
			}
			$ret .= '</div>'; // blog-item-wrapper
			
			remove_filter('excerpt_length', 'totalbusiness_set_excerpt_length');
			return $ret;
		}
	}

	if( !function_exists('totalbusiness_get_blog_info') ){
		function totalbusiness_get_blog_info( $array = array(), $wrapper = true, $sep = '' ){
			global $theme_option; $ret = '';
			if( empty($array) ) return $ret;
			$exclude_meta = empty($theme_option['post-meta-data'])? array(): $theme_option['post-meta-data'];

			foreach($array as $post_info){
				if( in_array($post_info, $exclude_meta) ) continue;

				switch( $post_info ){
					case 'date':
						$ret .= '<div class="blog-info blog-date totalbusiness-skin-info">';
						$ret .= empty($sep)? '': $sep;
						$ret .= '<a href="' . get_day_link( get_the_time('Y'), get_the_time('m'), get_the_time('d')) . '">';
						$ret .= get_the_time($theme_option['date-format']);
						$ret .= '</a>';
						$ret .= '</div>';
						break;
					case 'tag':
						$tag = get_the_term_list(get_the_ID(), 'post_tag', '', '<span class="sep">,</span> ' , '' );
						if(empty($tag)) break;					
					
						$ret .= '<div class="blog-info blog-tag totalbusiness-skin-info">';
						$ret .= empty($sep)? '': $sep;
						$ret .= $tag;						
						$ret .= '</div>';						
						break;
					case 'category':
						$category = get_the_term_list(get_the_ID(), 'category', '', '<span class="sep">,</span> ' , '' );
						if(empty($category)) break;
						
						$ret .= '<div class="blog-info blog-category totalbusiness-skin-info">';
						$ret .= empty($sep)? '': $sep;
						$ret .= $category;					
						$ret .= '</div>';					
						break;
					case 'comment':
						$comments_num = get_comments_number();
						$ret .= '<div class="blog-info blog-comment totalbusiness-skin-info">';
						$ret .= empty($sep)? '': $sep;
						$ret .= '<a href="' . get_permalink() . '#respond" >' .  $comments_num . ' ';
						if( $comments_num > 1 ){
							$ret .= esc_html__('Comments', 'totalbusiness');
						}else{
							$ret .= esc_html__('Comment', 'totalbusiness');
						}	
						$ret .= '</a>';						
						$ret .= '</div>';						
						break;
					case 'author':
						ob_start();
						the_author_posts_link();
						$author = ob_get_contents();
						ob_end_clean();
						
						$ret .= '<div class="blog-info blog-author totalbusiness-skin-info">';
						$ret .= empty($sep)? '': $sep;
						$ret .= esc_html__('By', 'totalbusiness') . ' ';
						$ret .= $author;
						$ret .= '</div>';						
						break;						
				}
			}
			
			
			if($wrapper && !empty($ret)){
				return '<div class="totalbusiness-blog-info">' . $ret . '<div class="clear"></div></div>';
			}else if( !empty($ret) ){
				return $ret . '<div class="clear"></div>';
			}
			return '';
		}
	}

	if( !function_exists('totalbusiness_get_blog_widget') ){
		function totalbusiness_get_blog_widget($query, $size){
			$ret = ''; $current_size = 0;
			
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}

				$ret .= '<div class="' . totalbusiness_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="totalbusiness-item totalbusiness-blog-widget">';
				$ret .= '<div class="totalbusiness-ux totalbusiness-blog-widget-ux">';
				ob_start();
				
				get_template_part('single/content');
				$ret .= ob_get_contents();
				
				ob_end_clean();			
				$ret .= '</div>'; // totalbusiness-ux			
				$ret .= '</div>'; // totalbusiness-item			
				$ret .= '</div>'; // column_class
				$current_size ++;
			}
			wp_reset_postdata();
			
			return $ret;
		}
	}
	
	if( !function_exists('totalbusiness_get_blog_grid') ){
		function totalbusiness_get_blog_grid($query, $size, $blog_layout = 'fitRows'){
			if($blog_layout == 'carousel'){ return totalbusiness_get_blog_grid_carousel($query, $size); }
		
			$ret = ''; $current_size = 0;
			
			$ret .= '<div class="totalbusiness-isotope" data-type="blog" data-layout="' . $blog_layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}

				$ret .= '<div class="' . totalbusiness_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="totalbusiness-item totalbusiness-blog-grid totalbusiness-skin-box">';
				$ret .= '<div class="totalbusiness-ux totalbusiness-blog-grid-ux">';
				ob_start();
				
				get_template_part('single/content');
				$ret .= ob_get_contents();
				
				ob_end_clean();			
				$ret .= '</div>'; // totalbusiness-ux			
				$ret .= '</div>'; // totalbusiness-item			
				$ret .= '</div>'; // column_class
				$current_size ++;
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // close the totalbusiness-isotope
			wp_reset_postdata();
			
			return $ret;
		}
	}		
	
	if( !function_exists('totalbusiness_get_blog_grid_carousel') ){
		function totalbusiness_get_blog_grid_carousel($query, $size){
			$ret = ''; 
			
			$ret .= '<div class="totalbusiness-blog-carousel-item totalbusiness-item" >';
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="blog-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';			
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="totalbusiness-item totalbusiness-blog-grid totalbusiness-skin-box">';
				ob_start();
				
				get_template_part('single/content');
				$ret .= ob_get_contents();
				
				ob_end_clean();					
				$ret .= '</li>'; // totalbusiness-item
			}
			$ret .= '</ul>';
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // close the flexslider
			$ret .= '</div>'; // close the totalbusiness-item
			wp_reset_postdata();
			
			return $ret;
		}
	}		
	
	if( !function_exists('totalbusiness_get_blog_medium') ){
		function totalbusiness_get_blog_medium($query){
			$ret = '';

			while($query->have_posts()){ $query->the_post();
				$ret .= '<div class="totalbusiness-item totalbusiness-blog-medium">';
				$ret .= '<div class="totalbusiness-ux totalbusiness-blog-medium-ux">';
				ob_start();
				
				get_template_part('single/content');
				$ret .= ob_get_contents();
				
				ob_end_clean();			
				$ret .= '</div>'; // totalbusiness-ux			
				$ret .= '</div>'; // totalbusiness-item
			}
			wp_reset_postdata();
			
			return $ret;
		}
	}		
	
	if( !function_exists('totalbusiness_get_blog_full') ){
		function totalbusiness_get_blog_full($query){
			$ret = '';

			while($query->have_posts()){ $query->the_post();
				$ret .= '<div class="totalbusiness-item totalbusiness-blog-full">';
				$ret .= '<div class="totalbusiness-ux totalbusiness-blog-full-ux">';
				ob_start();
				
				get_template_part('single/content');
				$ret .= ob_get_contents();
				
				ob_end_clean();			
				$ret .= '</div>'; // totalbusiness-ux
				$ret .= '</div>'; // totalbusiness-item
			}
			wp_reset_postdata();
			
			return $ret;
		}
	}	

?>