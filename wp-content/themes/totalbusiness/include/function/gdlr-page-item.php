<?php
	/*	
	*	Goodlayers Theme File
	*	---------------------------------------------------------------------
	*	This file contains the function use to print the elements of the theme
	*	---------------------------------------------------------------------
	*/
	
	// print title
	if( !function_exists('totalbusiness_get_item_title') ){
		function totalbusiness_get_item_title( $atts ){
			$ret = '';
			
			$atts['type'] = (empty($atts['type']))? '': $atts['type'];
			$atts['title-type'] = (empty($atts['title-type']))? 'center': $atts['title-type'];
			$atts['title-size'] = (empty($atts['title-size']))? 'large': $atts['title-size'];
			$atts['carousel'] = (empty($atts['carousel']))? '': $atts['carousel'];
			
			$item_class  = (!empty($atts['carousel']) && $atts['title-type'] == 'left')? ' totalbusiness-nav-container': '';
			$item_class .= ' totalbusiness-' . $atts['title-type'];
			$item_class .= ' totalbusiness-' . $atts['title-size'];
			$item_class .= (!empty($atts['item-class']))? ' ' . $atts['item-class']: '';
		
			if( !empty($atts['title-type']) && $atts['title-type'] != 'none' && (!empty($atts['title']) || !empty($atts['caption'])) ){

				$ret .= '<div class="totalbusiness-item-title-wrapper totalbusiness-item ' . $item_class . ' ">';
				$ret .= '<div class="totalbusiness-item-title-container container">';
				$ret .= '<div class="totalbusiness-item-title-head">';
				if(!empty($atts['title'])){
					$ret .= '<h3 class="totalbusiness-item-title totalbusiness-skin-title totalbusiness-skin-border">' . $atts['title'] . '</h3>';
				}
				if(!empty($atts['caption'])){
					$ret .= '<div class="totalbusiness-item-title-caption totalbusiness-skin-info">' . $atts['caption'] . '</div>';
				}
				if(!empty($atts['right-text']) && !empty($atts['right-text-link'])){
					$ret .= '<a class="totalbusiness-item-title-link" href="' . esc_url($atts['right-text-link']) . '" >' . $atts['right-text'] . '</a>';
				}
				if( !empty($atts['carousel']) && $atts['title-type'] == 'left' ){
					$ret .= '<span class="totalbusiness-nav-title">';
					$ret .= totalbusiness_get_item_title_nav( $atts['carousel'] );
					$ret .= '</span>';
				}
				$ret .= '</div>';
				$ret .= '</div>'; // container
				$ret .= '</div>'; // totalbusiness-item-title-wrapper
			}
			
			if( !empty($atts['carousel']) && $atts['title-type'] == 'center' ){
				$ret .= '<div class="totalbusiness-item-title-nav totalbusiness-nav-container ' . $item_class . '">';
				$ret .= totalbusiness_get_item_title_nav( $atts['carousel'] );
				$ret .= '</div>';
			}
			
			return $ret;
		}
	}		

	if( !function_exists('totalbusiness_get_item_title_nav') ){
		function totalbusiness_get_item_title_nav( $carousel ){
			$ret  = '<i class="icon-angle-left totalbusiness-flex-prev"></i>';
			if( $carousel !== true ){ $ret .= $carousel; }
			$ret .= '<i class="icon-angle-right totalbusiness-flex-next"></i>';	
			return $ret;
		}
	}	
	
	// title item
	if( !function_exists('totalbusiness_get_title_item') ){
		function totalbusiness_get_title_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
	
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';		
			
			$ret  = '<div class="totalbusiness-title-item" ' . $item_id . $margin_style . ' >';
			$ret .= totalbusiness_get_item_title($settings);			
			$ret .= '</div>';
			return $ret;
		}
	}
	
	// accordion item
	if( !function_exists('totalbusiness_get_accordion_item') ){
		function totalbusiness_get_accordion_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
	
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$accordion = is_array($settings['accordion'])? $settings['accordion']: json_decode($settings['accordion'], true);

			$ret  = totalbusiness_get_item_title($settings);				
			$ret .= '<div class="totalbusiness-item totalbusiness-accordion-item '  . $settings['style'] . '" ' . $item_id . $margin_style . ' >';
			$current_tab = 0;
			foreach( $accordion as $tab ){  $current_tab++;
				$ret .= '<div class="accordion-tab';
				$ret .= ($current_tab == intval($settings['initial-state']))? ' active pre-active" >': '" >';
				$ret .= '<h4 class="accordion-title" ';
				$ret .= empty($tab['gdl-tab-title-id'])? '': 'id="' . $tab['gdl-tab-title-id'] . '" ';
				$ret .= '><i class="';
				$ret .= ($current_tab == intval($settings['initial-state']))? 'icon-minus': 'icon-plus';
				$ret .= '" ></i><span>' . totalbusiness_text_filter($tab['gdl-tab-title']) . '</span></h4>';
				$ret .= '<div class="accordion-content">' . totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
				$ret .= '</div>';				
			}
			$ret .= '</div>';
			
			return $ret;
		}
	}	

	// toggle box item
	if( !function_exists('totalbusiness_get_toggle_box_item') ){
		function totalbusiness_get_toggle_box_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';

			$accordion = is_array($settings['toggle-box'])? $settings['toggle-box']: json_decode($settings['toggle-box'], true);

			$ret  = totalbusiness_get_item_title($settings);	
			$ret .= '<div class="totalbusiness-item totalbusiness-accordion-item totalbusiness-multiple-tab '  . $settings['style'] . '" ' . $item_id . $margin_style . ' >';
			foreach( $accordion as $tab ){ 
				$ret .= '<div class="accordion-tab';
				$ret .= ($tab['gdl-tab-active'] == 'yes')? ' active pre-active" >': '" >';
				$ret .= '<h4 class="accordion-title" ';
				$ret .= empty($tab['gdl-tab-title-id'])? '': 'id="' . $tab['gdl-tab-title-id'] . '" ';
				$ret .= '><i class="';
				$ret .= ($tab['gdl-tab-active'] == 'yes')? 'icon-minus': 'icon-plus';
				$ret .= '" ></i><span>' . totalbusiness_text_filter($tab['gdl-tab-title']) . '</span></h4>';
				$ret .= '<div class="accordion-content">' . totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
				$ret .= '</div>';
			}
			$ret .= '</div>';
			
			return $ret;
		}
	}		

	// about us item
	if( !function_exists('totalbusiness_get_about_us_item') ){
		function totalbusiness_get_about_us_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['style'] = empty($settings['style'])? 'plain': $settings['style'];
			$ret  = '<div class="totalbusiness-item totalbusiness-about-us-item totalbusiness-' . $settings['style'] . '" ' . $item_id . $margin_style . '>';
			$ret .= '<div class="about-us-title-wrapper">';
				if( !empty($settings['caption']) && $settings['style'] != 'normal' ){
					$ret .= '<div class="about-us-caption totalbusiness-title-font totalbusiness-skin-info">' . totalbusiness_text_filter($settings['caption']) . '</div>';
				}
				if( !empty($settings['title']) ){
					$ret .= '<h3 class="about-us-title">' . totalbusiness_text_filter($settings['title']) . '</h3>';
				}
				if( !empty($settings['caption']) && $settings['style'] == 'normal' ){
					$ret .= '<div class="about-us-caption totalbusiness-title-font totalbusiness-skin-info">' . totalbusiness_text_filter($settings['caption']) . '</div>';
				}
				$ret .= '<div class="about-us-title-divider"></div>';
			$ret .= '</div>'; // about-us-title-wrapper
			
			$ret .= '<div class="about-us-content-wrapper">';
			$ret .= '<div class="about-us-content totalbusiness-skin-content">';
			$ret .= totalbusiness_content_filter($settings['content']);
			$ret .= '</div>'; // about-us-content 
			if( !empty($settings['read-more-text']) && !empty($settings['read-more-link']) ){
				$button_class = '';
				if( $settings['style'] == 'title-left' || $settings['style'] == 'normal' ){
					$button_class = 'totalbusiness-button';
				}else if( $settings['style'] == 'plain-large' ){
					$button_class = 'totalbusiness-border-button';
				}
				
				$ret .= '<a class="about-us-read-more ' . $button_class . '" href="' . $settings['read-more-link'] . '" >' . $settings['read-more-text'] . '</a>';
			}
			$ret .= '</div>'; // about-us-content-wrapper		
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // about-us-item
			return $ret;
		}
	}
	
	// tab item
	if( !function_exists('totalbusiness_get_menu_item') ){
		function totalbusiness_get_menu_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$tabs = is_array($settings['content'])? $settings['content']: json_decode($settings['content'], true);			
			$current_tab = 0;
			
			$ret  = '<div class="totalbusiness-list-menu" ' . $item_id . $margin_style . '>';
			$ret .= totalbusiness_get_item_title($settings);	
			foreach( $tabs as $tab ){
				$ret .= '<div class="totalbusiness-menu-item-content totalbusiness-item" >';
				$ret .= '<h4 class="totalbusiness-menu-title" >' . totalbusiness_text_filter($tab['gdl-tab-title']) . '</h4>';				
				if( !empty($tab['caption']) ){
					$ret .= '<div class="totalbusiness-menu-ingredients-caption totalbusiness-skin-info">';
					if( !empty($tab['icon']) ){
						$ret .= '<i class="totalbusiness-skin-title totalbusiness-menu-icon fa ' . $tab['icon'] . '" ></i>';
					}
					$ret .= $tab['caption'] . '</div>';
				}
				if( !empty($tab['price']) ){
					$ret .= '<div class="totalbusiness-menu-price totalbusiness-skin-title">' . $tab['price'] . '</div>';
				}
				$ret .= '<div class="totalbusiness-list-menu-gimmick"></div>';
				$ret .= '</div>';
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // totalbusiness-tab-item 
			
			return $ret;
		}
	}		
	
	// price item
	if( !function_exists('totalbusiness_get_price_item') ){
		function totalbusiness_get_price_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['style'] = empty($settings['style'])? 'type-1': $settings['style'];
			$ret  = '<div class="totalbusiness-item totalbusiness-price-item" ' . $item_id . $margin_style . '>';
			if( !empty($settings['image']) ){
				$ret .= '<div class="price-item-image" >' . totalbusiness_get_image($settings['image']) . '</div>';
			}
			$ret .= '<h3 class="price-item-title">' . totalbusiness_text_filter($settings['title']) . '</h3>';
			$ret .= '<div class="price-item-price totalbusiness-skin-info">' . totalbusiness_text_filter($settings['price']) . '</div>';
			$ret .= '</div>'; // totalbusiness-price-item
			return $ret;
		}
	}	
	
	// column service item
	if( !function_exists('totalbusiness_get_column_service_item') ){
		function totalbusiness_get_column_service_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces, $column_service_id;
			if( empty($item_id) && $settings['style'] == 'type-2-hover' ){
				$column_service_id = empty($column_service_id)? 1: $column_service_id + 1;
				$item_id = 'id="totalbusiness-column-service-' . $column_service_id . '" ';
			}
			
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['style'] = empty($settings['style'])? 'type-1': $settings['style'];
			$ret  = '<div class="totalbusiness-ux column-service-ux">';
			
			if( !empty($settings['hover-bg']) || !empty($settings['hover-text']) ){
				$ret .= '<style type="text/css" scoped >';
				if( !empty($settings['hover-bg']) ){
					$ret .= "#totalbusiness-column-service-{$column_service_id}:hover{ background-color: {$settings['hover-bg']}; } ";
				}
				if( !empty($settings['hover-text']) ){
					$ret .= "#totalbusiness-column-service-{$column_service_id}:hover .column-service-title,";
					$ret .= "#totalbusiness-column-service-{$column_service_id}:hover .column-service-content,";
					$ret .= "#totalbusiness-column-service-{$column_service_id}:hover .column-service-icon,";
					$ret .= "#totalbusiness-column-service-{$column_service_id}:hover .column-service-read-more{ color: {$settings['hover-text']}; } ";
				}
				$ret .= '</style>';
			}
			
			$ret .= '<div class="totalbusiness-item totalbusiness-column-service-item totalbusiness-' . $settings['style'] . '" ' . $item_id . $margin_style . '>';
			if( $settings['type'] == 'image' && !empty($settings['image']) ){
				$ret .= '<div class="column-service-image" >' . totalbusiness_get_image($settings['image']) . '</div>';
			}else if( $settings['type'] == 'icon' && !empty($settings['icon']) ){
				$ret .= '<div class="column-service-icon totalbusiness-skin-box"><i class="fa ' . $settings['icon'] . '" ></i></div>';
			} 
			$ret .= '<div class="column-service-content-wrapper">';
			$ret .= '<h3 class="column-service-title">' . totalbusiness_text_filter($settings['title']) . '</h3>';
			$ret .= '<div class="column-service-content totalbusiness-skin-content">';
			$ret .= totalbusiness_content_filter($settings['content']);
			$ret .= '</div>'; // column-service-content 
			if( !empty($settings['read-more-text']) && !empty($settings['read-more-link']) ){
				$ret .= '<a class="column-service-read-more" href="' . $settings['read-more-link'] . '" >' . $settings['read-more-text'] . '</a>';
			}
			$ret .= '</div>'; // column-service-content-wrapper			
			$ret .= '</div>'; // column-service-item
			$ret .= '</div>'; // column-service-ux
			return $ret;
		}
	}
	
	// service with image item
	if( !function_exists('totalbusiness_get_service_with_image_item') ){
		function totalbusiness_get_service_with_image_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="totalbusiness-item totalbusiness-service-with-image-item totalbusiness-' . $settings['align'] . '" ';
			$ret .= $item_id . $margin_style . '>';
			if( !empty($settings['image']) ){
				$ret .= '<div class="service-with-image-thumbnail totalbusiness-skin-box">';
				$ret .= totalbusiness_get_image($settings['image'], $settings['thumbnail-size']);
				$ret .= '</div>';
			}
			
			$ret .= '<div class="service-with-image-content-wrapper">';
			$ret .= '<h3 class="service-with-image-title">' . totalbusiness_text_filter($settings['title']) . '</h3>';
			$ret .= '<div class="service-with-image-content">' . totalbusiness_content_filter($settings['content']) . '</div>'; 
			$ret .= '</div>'; // service with image content wrapper
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // totalbusiness-item
			return $ret;
		}
	}	

	// service half background item
	if( !function_exists('totalbusiness_get_service_half_background') ){
		function totalbusiness_get_service_half_background( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$style = '';
			
			$ret  = '<div class="totalbusiness-service-half-background-item" ' . $item_id . $margin_style . '>';

			if( !empty($settings['left-bg-image']) ){
				if( is_numeric($settings['left-bg-image']) ){
					$image_src = wp_get_attachment_image_src($settings['left-bg-image'], 'full');
					$style = 'style="background: url(\'' . $image_src[0] . '\') center 0px;"';
				}else{
					$style = 'style="background: url(\'' . $settings['left-bg-image'] . '\') center 0px;"';
				}
			}else{
				$style = '';
			}
			$ret .= '<div class="totalbusiness-half-left" ' . $style . ' >';
			$ret .= '<div class="half-container">';
			$ret .= '<div class="totalbusiness-item-margin">';
			if( !empty($settings['right-title']) ){
				$ret .= '<h3 class="totalbusiness-left-service-title" >' . totalbusiness_text_filter($settings['left-title']) . '</h3>';
			}
			if( !empty($settings['left-content']) ){
				$ret .= '<div class="totalbusiness-left-service-content" >' . totalbusiness_content_filter($settings['left-content']) . '</div>';
			}
			if( !empty($settings['left-read-more-text']) && $settings['left-read-more-link'] ){
				$ret .= '<a class="totalbusiness-left-service-read-more" href="' . $settings['left-read-more-link'] . '" >' . $settings['left-read-more-text'] . '</a>';
			}
			$ret .= '</div>'; // totalbusiness-item
			$ret .= '</div>'; // half-container
			$ret .= '</div>'; // half-left
			
			if( !empty($settings['right-bg-color']) ){
				$style = 'style="background: ' . $settings['right-bg-color'] . ';"';
			}
			$ret .= '<div class="totalbusiness-half-right" ' . $style . ' >';
			$ret .= '<div class="half-container">';
			$ret .= '<div class="totalbusiness-item-margin">';
			if( !empty($settings['right-title']) ){
				$ret .= '<h3 class="totalbusiness-right-service-title" >' . totalbusiness_text_filter($settings['right-title']) . '</h3>';
			}
			if( !empty($settings['right-content']) ){
				$ret .= '<div class="totalbusiness-right-service-caption" >' . totalbusiness_content_filter($settings['right-content']) . '</div>';
			}
			if( !empty($settings['right-read-more-text']) && $settings['right-read-more-link'] ){
				$ret .= '<a class="totalbusiness-right-service-read-more" href="' . $settings['right-read-more-link'] . '" >' . $settings['right-read-more-text'] . '</a>';
			}
			$ret .= '</div>'; // totalbusiness-item
			$ret .= '</div>'; // half-container
			$ret .= '</div>'; // half-right
			$ret .= '<div class="clear"></div>';

			// $ret .= '<div class="service-with-image-content-wrapper">';
			// $ret .= '<h3 class="service-with-image-title">' . totalbusiness_text_filter($settings['title']) . '</h3>';
			// $ret .= '<div class="service-with-image-content">' . totalbusiness_content_filter($settings['content']) . '</div>'; 
			// $ret .= '</div>'; // service with image content wrapper
			// $ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // totalbusiness-item
			return $ret;
		}
	}	
	
	// feature media item
	if( !function_exists('totalbusiness_get_feature_media_item') ){
		function totalbusiness_get_feature_media_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['align'] = empty($settings['align'])? 'left': $settings['align'];
			$ret  = '<div class="totalbusiness-feature-media-ux totalbusiness-ux">';
			$ret .= '<div class="totalbusiness-item totalbusiness-feature-media-item totalbusiness-' . $settings['align'] . '" ' . $item_id . $margin_style . '>';
			
			if($settings['type'] == 'image' && !empty($settings['image'])){
				$ret .= '<div class="feature-media-thumbnail totalbusiness-image">';
				$ret .= totalbusiness_get_image($settings['image'], $settings['thumbnail-size']);
				$ret .= '</div>';
			}else if($settings['type'] == 'video' && !empty($settings['video-url'])){
				$ret .= '<div class="feature-media-thumbnail totalbusiness-video">';
				$ret .= totalbusiness_get_video($settings['video-url']);
				$ret .= '</div>';
			}
			
			$ret .= '<div class="feature-media-content-wrapper">';
			$ret .= totalbusiness_get_item_title($settings);	
			$ret .= '<div class="feature-media-content">';
			$ret .= totalbusiness_content_filter($settings['content']);
			$ret .= '</div>'; 
			if( !empty($settings['button-link']) ){
				$ret .= '<a class="feature-media-button totalbusiness-button with-border" href="' . esc_url($settings['button-link']) . '" target="_blank">';
				$ret .= $settings['button-text'];
				$ret .= '</a>';
			}			
			$ret .= '</div>'; // feature-media-content-wrapper
			$ret .= '</div>'; // totalbusiness-item
			$ret .= '</div>'; // totalbusiness-ux
			return $ret;
		}
	}		
	
	// content item
	if( !function_exists('totalbusiness_get_content_item') ){
		function totalbusiness_get_content_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = totalbusiness_get_item_title($settings);	
			$ret .= '<div class="totalbusiness-item totalbusiness-content-item" ' . $item_id . $margin_style . '>' . totalbusiness_content_filter($settings['content']) . '</div>';
			return $ret;
		}
	}	

	// notification item
	if( !function_exists('totalbusiness_get_notification_item') ){
		function totalbusiness_get_notification_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';	
		
			$style  = ' style="';
			if($settings['type'] == 'color-background'){
				$style .= !empty($settings['color'])? 'color:' . $settings['color'] . '; ': '';
				$style .= !empty($settings['background'])? 'background-color:' . $settings['background'] . '; ': '';
			}else if($settings['type'] == 'color-border'){
				$style .= !empty($settings['color'])? 'color:' . $settings['color'] . '; ': '';
				$style .= !empty($settings['border'])? 'border-color:' . $settings['border'] . '; ': '';	
			}	
			$style .= $margin . '" ';
			
			$ret  = '<div class="totalbusiness-notification totalbusiness-item ' . $settings['type'] . '" ' . $style . '>';
			$ret .= '<i class="fa ' . $settings['icon'] . '"></i>';
			$ret .= '<div class="notification-content">' . totalbusiness_text_filter($settings['content']) . '</div>';
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			return $ret;	
		}
	}
	
	// icon with list item
	if( !function_exists('totalbusiness_get_list_with_icon_item') ){
		function totalbusiness_get_list_with_icon_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';			
			
			$settings['icon-with-list'] = empty($settings['icon-with-list'])? array(): $settings['icon-with-list'];
			$list = is_array($settings['icon-with-list'])? $settings['icon-with-list']: json_decode($settings['icon-with-list'], true);

			$ret  = totalbusiness_get_item_title($settings);	
			$ret .= '<div class="totalbusiness-item totalbusiness-icon-with-list-item" ' . $item_id . $margin_style . '>';
			foreach( $list as $tab ){ 
				$ret .= '<div class="list-with-icon-ux totalbusiness-ux">';
				$ret .= '<div class="list-with-icon totalbusiness-' . $settings['align'] . '">';
				if( !empty($tab['gdl-tab-image']) ){
					$ret .= '<div class="list-with-icon-image">';
					$ret .= totalbusiness_get_image($tab['gdl-tab-image']);
					$ret .= '</div>';
				}else if( !empty($tab['gdl-tab-icon']) ){
					$ret .= '<div class="list-with-icon-icon">';
					$ret .= '<i class="fa ' . $tab['gdl-tab-icon'] . '"></i>';
					$ret .= '</div>';
				}
				$ret .= '<div class="list-with-icon-content">';
				$ret .= '<div class="list-with-icon-title totalbusiness-skin-title">';
				$ret .= totalbusiness_text_filter($tab['gdl-tab-title']);
				$ret .= '</div>';
				$ret .= '<div class="list-with-icon-caption">' . totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
				$ret .= '</div>'; // list-with-icon-content
				$ret .= '<div class="clear"></div>';
				$ret .= '</div>'; // icon-with-list
				$ret .= '</div>'; // totalbusiness-ux
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			return $ret;
		}
	}	

	// skill bar item
	if( !function_exists('totalbusiness_get_skill_bar_item') ){
		function totalbusiness_get_skill_bar_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';	
		
		
			$ret  = '<div class="totalbusiness-skill-bar-wrapper  totalbusiness-item totalbusiness-size-' . $settings['size'] . '" ' . $item_id . $margin_style . '>';
			if( $settings['size'] == 'small' && !empty($settings['content']) ){ 
				$ret .= '<span class="skill-bar-content" style="color: ' . $settings['text-color'] . ';" >';
				$ret .= $settings['content'];
				$ret .= '</span>';
				$ret .= '<span class="skill-bar-percent" style="color: ' . $settings['text-color'] . ';" >';
				$ret .= esc_attr($settings['percent']) . '%';
				$ret .= '</span>';
			}
			$ret .= '<div class="totalbusiness-skill-bar totalbusiness-ux" style="background-color: ' . $settings['background-color'] . ';" >';
			$ret .= '<div class="totalbusiness-skill-bar-progress" data-percent="' . esc_attr($settings['percent']) . '" ';
			$ret .= 'style="background-color: ' . $settings['progress-color'] . ';" >';
			if( $settings['size'] != 'small' && !empty($settings['content']) ){ 
				$ret .= '<span class="skill-bar-content" style="color: ' . $settings['text-color'] . ';" >';
				$ret .= empty($settings['icon'])? '': '<i class="fa ' . $settings['icon'] . '" ></i>';
				$ret .= $settings['content'];
				$ret .= '</span>';
			}		
			$ret .= '</div>'; // totalbusiness-skill-bar-progress
			$ret .= '</div>'; // totalbusiness-skill-bar
			$ret .= '</div>'; // totalbusiness-skill-bar-wrapper				
			
			return $ret;
		}
	}
	
	// skill round item
	if( !function_exists('totalbusiness_get_skill_item') ){
		function totalbusiness_get_skill_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';

			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';	
			$ret  = '<div class="totalbusiness-skill-item-wrapper totalbusiness-skin-content totalbusiness-item ' . (empty($settings['style'])?'totalbusiness-style-1': 'totalbusiness-' . $settings['style']) . '" ' . $item_id . $margin_style . '>';
			if( !empty($settings['style']) && $settings['style'] == 'style-2' && !empty($settings['icon-class']) ){
				$ret .= '<i class="fa ' . $settings['icon-class'] . '" style="color:' . $settings['icon-color'] .';" ></i>';
			}
			$ret .= '<div class="totalbusiness-skill-item-title" style="' . (empty($settings['title-color'])? '': "color: {$settings['title-color']};") . '">' . $settings['title'] . '</div>';
			if( empty($settings['style']) || $settings['style'] == 'style-1' ){
				$ret .= '<div class="totalbusiness-skill-item-divider" style="' . (empty($settings['title-color'])? '': "border-color: {$settings['title-color']};") . '" ></div>';
			}
			$ret .= '<div class="totalbusiness-skill-item-caption" style="' . (empty($settings['caption-color'])? '': "color: {$settings['caption-color']};") . '">' . $settings['caption'] . '</div>';
			$ret .= '</div>'; // totalbusiness-skill-item-wrapper		
			
			return $ret;
		}
	}	
	
	// price table item
	if( !function_exists('totalbusiness_get_price_table_item') ){
		function totalbusiness_get_price_table_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';			
			
			$settings['price-table'] = empty($settings['price-table'])? array(): $settings['price-table'];
			$list = is_array($settings['price-table'])? $settings['price-table']: json_decode($settings['price-table'], true);
			$ret  = '<div class="totalbusiness-item totalbusiness-price-table-item" ' . $item_id . $margin_style . '>';
			foreach( $list as $tab ){ 
				$best_price = ($tab['gdl-tab-active'] == 'yes')? ' best-price ': '';
				
				$ret .= '<div class="totalbusiness-price-item ' . totalbusiness_get_column_class('1/' . $settings['columns']) . '">';
				$ret .= '<div class="totalbusiness-price-inner-item ' . $best_price . '">';
				
				$ret .= '<div class="price-title-wrapper">';
				$ret .= '<h4 class="price-title">' . totalbusiness_text_filter($tab['gdl-tab-title']) . '</h4>';
				$ret .= '<div class="price-tag">' . totalbusiness_text_filter($tab['gdl-tab-price']) . '</div>';
				$ret .= '</div>';
				
				$ret .= '<div class="price-content">' . totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
				
				if(!empty($tab['gdl-tab-link'])){
					$ret .= '<div class="price-button">';
					$ret .= '<a class="totalbusiness-button without-border" href="' . esc_url($tab['gdl-tab-link']) . '">' . esc_html__('Buy Now', 'totalbusiness') . '</a>';
					$ret .= '</div>';
				}
				
				$ret .= '</div>'; // totalbusiness-price-inner-item
				$ret .= '</div>'; // totalbusiness-price-item
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			return $ret;
		}
	}
	
	// pie chart item
	if( !function_exists('totalbusiness_get_pie_chart_item') ){
		function totalbusiness_get_pie_chart_item( $settings ){	
			global $totalbusiness_spaces;
			wp_enqueue_script('jquery-easypiechart', get_template_directory_uri() . '/plugins/easy-pie-chart/jquery.easy-pie-chart.js', array(), '1.0', true);
			
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="totalbusiness-item totalbusiness-pie-chart-item" ' . $item_id . $margin_style . '>';
			
			$ret .= '<div class="totalbusiness-chart totalbusiness-ux" data-percent="' . esc_attr($settings['progress']) . '" data-size="155" data-linewidth="8" ';
			$ret .= 'data-color="' . esc_attr($settings['color']) . '" data-bg-color="' . esc_attr($settings['bg-color']) . '" >';
			$ret .= '<div class="chart-content-wrapper">';
			$ret .= '<div class="chart-content-inner">';
			$ret .= '<span class="chart-content" ><i class="fa ' . $settings['icon'] . '" ></i></span>';
			$ret .= '<span class="chart-percent-number" style="color:' . $settings['color'] . ';" >' . $settings['progress'] . '%' . '</span>';
			$ret .= '</div>';			
			$ret .= '</div>';			
			$ret .= '</div>';			
			
			$ret .= '<h4 class="pie-chart-title">' . totalbusiness_text_filter($settings['title']) . '</h4>';
			$ret .= '<div class="pie-chart-content">';
			$ret .= totalbusiness_content_filter($settings['content']);
			if( !empty($settings['learn-more-link']) ){
				$ret .= '<a href="' . esc_url($settings['learn-more-link']) . '" ';
				$ret .= 'class="pie-chart-learn-more">' . esc_html__('Learn More', 'totalbusiness') . '</a>';	
			}
			$ret .= '</div>'; // pie-chart-content
			
			$ret .= '</div>'; // totalbusiness-item
			return $ret;
		}
	}
	
	// tab item
	if( !function_exists('totalbusiness_get_tab_item') ){
		function totalbusiness_get_tab_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$tabs = is_array($settings['tab'])? $settings['tab']: json_decode($settings['tab'], true);			
			$current_tab = 0;

			$ret  = totalbusiness_get_item_title($settings);	
			$ret .= '<div class="totalbusiness-item totalbusiness-tab-item '  . $settings['style'] . '" ' . $item_id . $margin_style . '>';
			$ret .= '<div class="tab-title-wrapper" >';
			foreach( $tabs as $tab ){  $current_tab++;
				$ret .= '<h4 class="tab-title';
				$ret .= ($current_tab == intval($settings['initial-state']))? ' active" ': '" ';
				$ret .= empty($tab['gdl-tab-title-id'])? '>': 'id="' . $tab['gdl-tab-title-id'] . '" >';
				$ret .= empty($tab['gdl-tab-icon-title'])? '': '<i class="fa ' . $tab['gdl-tab-icon-title'] . '" ></i>';				
				$ret .= '<span>' . totalbusiness_text_filter($tab['gdl-tab-title']) . '</span></h4>';				
			}
			$ret .= '</div>';
			
			$current_tab = 0;
			$ret .= '<div class="tab-content-wrapper" >';
			foreach( $tabs as $tab ){  $current_tab++;
				$ret .= '<div class="tab-content';
				$ret .= ($current_tab == intval($settings['initial-state']))? ' active" >': '" >';
				$ret .= totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
							
			}	
			$ret .= '</div>';	
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // totalbusiness-tab-item 
			
			return $ret;
		}
	}		
	
	// stunning text item
	if( !function_exists('totalbusiness_get_stunning_text_item') ){
		function totalbusiness_get_stunning_text_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			$class  = empty($settings['button-link'])? '': ' totalbusiness-button-on';
			$class .= empty($settings['style'])? 'totalbusiness-stunning-left': ' totalbusiness-stunning-' . $settings['style']; 
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="totalbusiness-stunning-item-ux totalbusiness-ux">';
			$ret .= '<div class="totalbusiness-item totalbusiness-stunning-item' . $class . '" ' . $item_id . $margin_style . '>';
			$ret .= '<h2 class="stunning-item-title">' . totalbusiness_text_filter($settings['title']) . '</h2>';
			if( !empty($settings['caption']) && $settings['style'] != 'small-left' ){ 
				$ret .= '<div class="stunning-item-caption totalbusiness-skin-content">' . totalbusiness_content_filter($settings['caption']) . '</div>';
			}
			if( !empty($settings['button-link']) ){
				$ret .= '<a class="stunning-item-button totalbusiness-button ' . ($settings['style'] == 'small-left'? 'small': ''). '" href="' . esc_url($settings['button-link']) . '" target="_blank" >';
				$ret .= $settings['button-text'];
				$ret .= '</a>';
			}
			if( !empty($settings['button2-link']) && $settings['style'] == 'center' ){
				$ret .= '<a class="stunning-item-button totalbusiness-button" href="' . esc_url($settings['button2-link']) . '" target="_blank" >';
				$ret .= $settings['button2-text'];
				$ret .= '</a>';
			}
			$ret .= '</div>'; // totalbusiness-item
			$ret .= '</div>'; // totalbusiness-ux
			
			return $ret;
		}
	}	

	if( !function_exists('totalbusiness_get_divider_item') ){
		function totalbusiness_get_divider_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-divider-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$style = empty($settings['size'])? '': ' style="width: ' . $settings['size'] . ';" ';
			$ret  = '<div class="clear"></div>';
			$ret .= '<div class="totalbusiness-item totalbusiness-divider-item" ' . $item_id . $margin_style . ' >';
			if( $settings['type'] != 'with-icon' ){
				$ret .= '<div class="totalbusiness-divider ' . $settings['type'] . '" ' . $style . '></div>';
			}else{
				$ret .= '<div class="totalbusiness-divider-with-icon" ' . $style . '>';
				$ret .= '<div class="totalbusiness-divider-with-icon-left"></div>';
				$ret .= '<div class="totalbusiness-divider-icon-outer" style="border-color: ' . $settings['divider-color'] . '" >';
				$ret .= '<div class="totalbusiness-divider-icon" style="background-color: ' . $settings['divider-color'] . '" >';
				$ret .= '<i class="fa ' . $settings['icon-class'] . '" ></i>';
				$ret .= '</div>';
				$ret .= '</div>';
				$ret .= '<div class="totalbusiness-divider-with-icon-right"></div>';
				$ret .= '</div>';
			}
			$ret .= '</div>';					
			
			return $ret;
		}
	}
	
	// boxed icon item
	if( !function_exists('totalbusiness_get_box_icon_item') ){
		function totalbusiness_get_box_icon_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="totalbusiness-box-with-icon-ux totalbusiness-ux">';
			$ret .= '<div class="totalbusiness-item totalbusiness-box-with-icon-item pos-' . $settings['icon-position'];
			$ret .=	' type-' . $settings['icon-type'] . '" ' . $item_id . $margin_style . '>';
			
			
			$ret .= ($settings['icon-type'] == 'circle')? '<div class="box-with-circle-icon" style="background-color: ' . $settings['icon-background'] . '">': '';
			$style = empty($settings['icon-color'])? '': ' style="color:' . $settings['icon-color'] . ';" ';
			$ret .= '<i class="fa ' . $settings['icon'] . '" ' . $style . '></i><br>';
			$ret .= ($settings['icon-type'] == 'circle')? '</div>': '';
			
			$ret .= '<h4 class="box-with-icon-title">' . totalbusiness_text_filter($settings['title']) . '</h4>';
			$ret .= '<div class="clear"></div>';
			$ret .= '<div class="box-with-icon-caption">' . totalbusiness_content_filter($settings['content']) . '</div>';
			$ret .= '</div>'; // totalbusiness-item	
			$ret .= '</div>'; // totalbusiness-ux
			
			return $ret;
		}
	}
	
	
	// styled box item
	if( !function_exists('totalbusiness_get_styled_box_item') ){
		function totalbusiness_get_styled_box_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			$style  = 'color: ' . $settings['content-color'] . '; ';
			$style .= empty($settings['height'])? '': 'height: ' . $settings['height'] . '; ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="totalbusiness-styled-box-item-ux totalbusiness-ux" >';
			$ret .= '<div class="totalbusiness-item totalbusiness-styled-box-item" ' . $item_id . $margin_style . '>';
			if($settings['type'] == 'color'){
				if(!empty($settings['flip-corner']) && $settings['flip-corner'] == 'enable'){
					$ret .= '<div class="totalbusiness-styled-box-head-wrapper" >';
					$ret .= '<div class="totalbusiness-styled-box-corner" style="border-bottom-color:' . $settings['corner-color'] . ';" ></div>';
					$ret .= '<div class="totalbusiness-styled-box-head" style="background-color:' . $settings['background-color'] . ';" ></div>';
					$ret .= '</div>';
					$ret .= '<div class="totalbusiness-styled-box-body with-head" style="background-color:' . $settings['background-color'] . '; ' . $style . '" >';
				}else{
					$ret .= '<div class="totalbusiness-styled-box-body" style="background-color:' . $settings['background-color'] . '; ' . $style . '" >';
				}
				
			}else if( $settings['type'] == 'image' ){
				if( is_numeric($settings['background-image']) ){ 
					$thumbnail = wp_get_attachment_image_src($settings['background-image'], 'full');
					$file_url = $thumbnail[0];
				}else{
					$file_url = $settings['background-image'];
				}			
				$ret .= '<div class="totalbusiness-styled-box-body" style="background-image: url(\'' . esc_url($file_url) . '\'); ' . $style . '" >';
			}
			$ret .= totalbusiness_content_filter($settings['content']);
			$ret .= '</div>'; // totalbusiness-styled-box-body
			$ret .= '</div>'; // totalbusiness-item
			$ret .= '</div>'; // totalbusiness-ux
			return $ret;
		}
	}		
	
	// testimonial item
	if( !function_exists('totalbusiness_get_testimonial_item') ){
		function totalbusiness_get_testimonial_item( $settings ){
			if( $settings['testimonial-type'] == 'carousel' ){
				return totalbusiness_get_carousel_testimonial_item($settings);
			}else{
				return totalbusiness_get_static_testimonial_item($settings);
			}
		}
	}		
	if( !function_exists('totalbusiness_get_static_testimonial_item') ){
		function totalbusiness_get_static_testimonial_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';	

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['testimonial'] = empty($settings['testimonial'])? array(): $settings['testimonial'];
			$list = is_array($settings['testimonial'])? $settings['testimonial']: json_decode($settings['testimonial'], true);
			$item_size = intval($settings['testimonial-columns']);
			
			$current_size = 0;

			$ret  = totalbusiness_get_item_title($settings);	
			$ret .= '<div class="totalbusiness-testimonial-item-wrapper" ' . $item_id . $margin_style . '>';
			foreach( $list as $tab ){ 
				if( $current_size % $item_size == 0 ){
					$ret .= '<div class="clear"></div>';
				}	
				
				$ret .= '<div class="' . totalbusiness_get_column_class('1/' . $item_size) . '">';
				$ret .= '<div class="totalbusiness-item totalbusiness-testimonial-item ' . $settings['testimonial-style'] . '">';
				$ret .= '<div class="totalbusiness-ux totalbusiness-testimonial-ux">';
				$ret .= '<div class="testimonial-item">';

				if( strpos($settings['testimonial-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="testimonial-item-inner totalbusiness-skin-box">';
				}

				$ret .= '<div class="testimonial-content totalbusiness-skin-content">' . totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
				$ret .= '<div class="testimonial-info">';
				if( !empty($tab['gdl-tab-title'] ) ){
					$ret .= '<span class="testimonial-author totalbusiness-skin-link-color">' . totalbusiness_text_filter($tab['gdl-tab-title']);
					$ret .= (!empty($tab['gdl-tab-position']))? '<span>, </span>': '';
					$ret .= '</span>';
				}
				if( !empty($tab['gdl-tab-position']) ){
					$ret .= '<span class="testimonial-position totalbusiness-skin-info">' . totalbusiness_text_filter($tab['gdl-tab-position']) . '</span>';
				}
				$ret .= '</div>'; // testimonial-info
				
				if( strpos($settings['testimonial-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="testimonial-author-image totalbusiness-skin-border" >';
					$ret .= totalbusiness_get_image($tab['gdl-tab-author-image'], 'thumbnail');
					$ret .= '</div>';
					
					$ret .= '</div>'; // testimonial-item-inner
				}
				
				$ret .= '</div>'; // testimonial-item
				$ret .= '</div>'; // totalbusiness-ux
				$ret .= '</div>'; // totalbusiness-item
				$ret .= '</div>'; // totalbusiness-get-column-class
				$current_size ++;
			}
			
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			return $ret;
		}
	}
	if( !function_exists('totalbusiness_get_carousel_testimonial_item') ){
		function totalbusiness_get_carousel_testimonial_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';			
			
			$settings['testimonial'] = empty($settings['testimonial'])? array(): $settings['testimonial'];
			$list = is_array($settings['testimonial'])? $settings['testimonial']: json_decode($settings['testimonial'], true);
			$ret  = '<div class="totalbusiness-testimonial-item-wrapper" ' . $item_id . $margin_style . '>';
			
			if( empty($settings['title-type']) || $settings['title-type'] != 'center' ){ 
				$settings['carousel'] = true; 
			}
			$ret .= totalbusiness_get_item_title($settings);							
			$ret .= '<div class="totalbusiness-item totalbusiness-testimonial-item carousel ' . $settings['testimonial-style'] . '">';
			$ret .= '<div class="totalbusiness-ux totalbusiness-testimonial-ux">';
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="totalbusiness-testimonial-item" ';
			$ret .= 'data-columns="' . $settings['testimonial-columns'] . '" >';
			$ret .= '<ul class="slides" >';
			foreach( $list as $tab ){ 
				$ret .= '<li class="testimonial-item">';
				if( strpos($settings['testimonial-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="testimonial-item-inner totalbusiness-skin-box">';
				}

				$ret .= '<div class="testimonial-content totalbusiness-skin-content">' . totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
				$ret .= '<div class="testimonial-info">';
				if( !empty($tab['gdl-tab-title'] ) ){
					$ret .= '<span class="testimonial-author totalbusiness-skin-link-color">' . totalbusiness_text_filter($tab['gdl-tab-title']);
					$ret .= (!empty($tab['gdl-tab-position']))? '<span>, </span>': '';
					$ret .= '</span>';
				}
				if( !empty($tab['gdl-tab-position']) ){
					$ret .= '<span class="testimonial-position totalbusiness-skin-info">' . totalbusiness_text_filter($tab['gdl-tab-position']) . '</span>';
				}
				$ret .= '</div>'; // testimonial-info
				
				if( strpos($settings['testimonial-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="testimonial-author-image totalbusiness-skin-border" >';
					$ret .= totalbusiness_get_image($tab['gdl-tab-author-image'], 'thumbnail');
					$ret .= '</div>';
					
					$ret .= '</div>'; // testimonial-item-inner
				}
				$ret .= '</li>';
			}
			$ret .= '</ul>';
			$ret .= '</div>'; // flexslider
			$ret .= '</div>'; // totalbusiness-ux
			$ret .= '</div>'; // totalbusiness-testimonial-item
			
			if( !empty($settings['title-type']) && $settings['title-type'] == 'center' ){ 
				$ret .= totalbusiness_get_item_title(array('carousel'=>true));	
			}
			$ret .= '</div>'; // totalbusiness-testimonial-item-wrapper
			
			return $ret;
		}
	}	
	
	// personnel item
	if( !function_exists('totalbusiness_get_personnel_item') ){
		function totalbusiness_get_personnel_item( $settings ){
			if( $settings['personnel-style'] == 'box-style' ){
				$settings['thumbnail-size'] == 'thumbnail';
			}
		
			if( $settings['personnel-type'] == 'carousel' ){
				return totalbusiness_get_carousel_personnel_item($settings);
			}else{
				return totalbusiness_get_static_personnel_item($settings);
			}
		}
	}		
	if( !function_exists('totalbusiness_get_static_personnel_item') ){
		function totalbusiness_get_static_personnel_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';			
			
			$settings['personnel'] = empty($settings['personnel'])? array(): $settings['personnel'];
			$list = is_array($settings['personnel'])? $settings['personnel']: json_decode($settings['personnel'], true);
			$item_size = intval($settings['personnel-columns']);
			
			$current_size = 0; 
			
			$ret  = totalbusiness_get_item_title($settings);	
			$ret .= '<div class="totalbusiness-personnel-item-wrapper" ' . $item_id . $margin_style . '>';
			foreach( $list as $tab ){ 
				if( $current_size % $item_size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
				
				$ret .= '<div class="' . totalbusiness_get_column_class('1/' . $item_size) . '">';
				$ret .= '<div class="totalbusiness-item totalbusiness-personnel-item ' . $settings['personnel-style'] . '">';
				$ret .= '<div class="totalbusiness-ux totalbusiness-personnel-ux">';
				$ret .= '<div class="personnel-item">';
				
				if( $settings['personnel-style'] == 'round-style' ){
					$ret .= '<div class="personnel-author-image" >';
					$ret .= totalbusiness_get_image($tab['gdl-tab-author-image'], $settings['thumbnail-size']);
					$ret .= '</div>';	
				}
				
				if( strpos($settings['personnel-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="personnel-item-inner totalbusiness-skin-box">';
				}
				if( $settings['personnel-style'] != 'round-style' ){
					$ret .= '<div class="personnel-author-image totalbusiness-skin-border" >';
					$ret .= totalbusiness_get_image($tab['gdl-tab-author-image'], $settings['thumbnail-size']);
					$ret .= '</div>';		
				}
				
				$ret .= '<div class="personnel-info">';
				if( !empty($tab['gdl-tab-title'] ) ){
					$ret .= '<div class="personnel-author totalbusiness-skin-title">' . totalbusiness_text_filter($tab['gdl-tab-title']) . '</div>';
				}
				if( !empty($tab['gdl-tab-position']) ){
					$ret .= '<div class="personnel-position totalbusiness-skin-info">' . totalbusiness_text_filter($tab['gdl-tab-position']) . '</div>';
				}
				$ret .= '</div>'; // personnel-info
				
				if( !empty($tab['gdl-tab-content']) ){
					$ret .= '<div class="personnel-content totalbusiness-skin-content">' . totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
				}
				
				if( !empty($tab['gdl-tab-social-list']) ){
					$ret .= '<div class="personnel-social">';
					$ret .= totalbusiness_text_filter($tab['gdl-tab-social-list']);
					$ret .= '</div>';
				}
				if( strpos($settings['personnel-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '</div>'; // personnel-item-inner
				}
				
				$ret .= '</div>'; // personnel-item
				$ret .= '</div>'; // totalbusiness-ux
				$ret .= '</div>'; // totalbusiness-item
				$ret .= '</div>'; // totalbusiness-get-column-class
				$current_size ++;
			}
			
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			return $ret;
		}
	}
	if( !function_exists('totalbusiness_get_carousel_personnel_item') ){
		function totalbusiness_get_carousel_personnel_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['carousel'] = true;
			$settings['personnel'] = empty($settings['personnel'])? array(): $settings['personnel'];
			$list = is_array($settings['personnel'])? $settings['personnel']: json_decode($settings['personnel'], true);

			$ret  = '<div class="totalbusiness-personnel-item-wrapper" ' . $item_id . $margin_style . '>';
			$ret .= totalbusiness_get_item_title($settings);		
			$ret .= '<div class="totalbusiness-item totalbusiness-personnel-item carousel ' . $settings['personnel-style'] . '">';
			$ret .= '<div class="totalbusiness-ux totalbusiness-personnel-ux">';
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="totalbusiness-personnel-item" ';
			$ret .= 'data-columns="' . $settings['personnel-columns'] . '" >';
			$ret .= '<ul class="slides" >';
			foreach( $list as $tab ){ 
				$ret .= '<li class="personnel-item">';
				
				if( $settings['personnel-style'] == 'round-style' ){
					$ret .= '<div class="personnel-author-image" >';
					$ret .= totalbusiness_get_image($tab['gdl-tab-author-image'], $settings['thumbnail-size']);
					$ret .= '</div>';	
				}				
				
				if( strpos($settings['personnel-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="personnel-item-inner totalbusiness-skin-box">';
				}
				
				if( $settings['personnel-style'] != 'round-style' ){
					$ret .= '<div class="personnel-author-image totalbusiness-skin-border" >';
					$ret .= totalbusiness_get_image($tab['gdl-tab-author-image'], $settings['thumbnail-size']);
					$ret .= '</div>';				
				}

				$ret .= '<div class="personnel-info">';
				if( !empty($tab['gdl-tab-title'] ) ){
					$ret .= '<div class="personnel-author totalbusiness-skin-title">' . totalbusiness_text_filter($tab['gdl-tab-title']) . '</div>';
				}
				if( !empty($tab['gdl-tab-position']) ){
					$ret .= '<div class="personnel-position totalbusiness-skin-info">' . totalbusiness_text_filter($tab['gdl-tab-position']) . '</div>';
				}
				$ret .= '</div>'; // personnel-info
				
				if( !empty($tab['gdl-tab-content']) ){
					$ret .= '<div class="personnel-content totalbusiness-skin-content">' . totalbusiness_content_filter($tab['gdl-tab-content']) . '</div>';
				}
				
				if( !empty($tab['gdl-tab-social-list']) ){
					$ret .= '<div class="personnel-social">';
					$ret .= totalbusiness_text_filter($tab['gdl-tab-social-list']);
					$ret .= '</div>';
				}
				if( strpos($settings['personnel-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '</div>'; // personnel-item-inner
				}
				$ret .= '</li>';
			}
			$ret .= '</ul>';
			$ret .= '</div>'; // flexslider
			$ret .= '</div>'; // totalbusiness-ux
			$ret .= '</div>'; // totalbusiness-personnel-item
			$ret .= '</div>'; // totalbusiness-personnel-item-wrapper
			
			return $ret;
		}
	}			
	
	// page list item
	if( !function_exists('totalbusiness_get_page_list_item') ){
		function totalbusiness_get_page_list_item( $settings ){	
			if(function_exists('totalbusiness_include_portfolio_scirpt')){ totalbusiness_include_portfolio_scirpt(); }
		
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
		
			global $totalbusiness_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $totalbusiness_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';

			$ret  = totalbusiness_get_item_title($settings);	
			$ret .= '<div class="portfolio-item-wrapper type-' . $settings['page-style'] . '" ' . $item_id . $margin_style . '>'; 
			
			// query section
			$args = array('post_type' => 'page', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = 'menu_order';
			$args['order'] = 'asc';
			$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : get_query_var('page');
			$args['paged'] = empty($args['paged'])? 1: $args['paged'];
			if( !empty($settings['category']) ){
				$args['tax_query'] = array( 
					array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'page_category', 'field'=>'slug')
				);		
			}		

			$query = new WP_Query( $args );	
				
			// print item section
			$settings['item-size'] = str_replace('1/', '', $settings['item-size']);
			
			$ret .= '<div class="portfolio-item-holder">';
			if($settings['page-style'] == 'classic'){
				$ret .= totalbusiness_get_classic_page_list($query, $settings['item-size'], 
							$settings['thumbnail-size'], $settings['page-layout'] );
			}else if($settings['page-style'] == 'modern'){	
				$ret .= totalbusiness_get_modern_page_list($query, $settings['item-size'], 
							$settings['thumbnail-size'], $settings['page-layout'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';	

			if($settings['pagination'] == 'enable'){
				$ret .= totalbusiness_get_pagination($query->max_num_pages, $args['paged']);
			}
			
			$ret .= '</div>'; // portfolio-item-wrapper
			return $ret;
			
		}
	}
	
	// print classic page list
	if( !function_exists('totalbusiness_get_classic_page_list') ){
		function totalbusiness_get_classic_page_list($query, $size, $thumbnail_size, $layout = 'fitRows'){
			$current_size = 0;
			$ret  = '<div class="totalbusiness-isotope" data-type="portfolio" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
    
				$ret .= '<div class="' . totalbusiness_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="totalbusiness-item totalbusiness-portfolio-item totalbusiness-classic-portfolio">';
				
				$ret .= '<div class="portfolio-thumbnail totalbusiness-image">';
				$ret .= totalbusiness_get_image(get_post_thumbnail_id(), $thumbnail_size);
				$ret .= '<a class="portfolio-overlay-wrapper" href="' . get_permalink() . '" >';
				$ret .= '<span class="portfolio-overlay" ></span>';
				$ret .= '<span class="portfolio-icon" ><i class="fa ' . totalbusiness_fa_class('icon-link') . '" ></i></span>';
				$ret .= '</a>';	
				$ret .= '</div>'; // portfolio-thumbnail
 
				$ret .= '<div class="portfolio-content-wrapper">';
				$ret .= '<h3 class="portfolio-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '</div>';
				
				$ret .= '</div>';				
				$ret .= '</div>';
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	

	// print modern page list
	if( !function_exists('totalbusiness_get_modern_page_list') ){
		function totalbusiness_get_modern_page_list($query, $size, $thumbnail_size, $layout = 'fitRows'){
			$current_size = 0;
			$ret  = '<div class="totalbusiness-isotope" data-type="portfolio" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}	
    
				$ret .= '<div class="' . totalbusiness_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="totalbusiness-item totalbusiness-portfolio-item totalbusiness-modern-portfolio">';
				
				// overlay
				$ret .= '<div class="portfolio-thumbnail totalbusiness-image">';
				$ret .= totalbusiness_get_image(get_post_thumbnail_id(), $thumbnail_size);
				$ret .= '<a class="portfolio-overlay-wrapper" href="' . get_permalink() . '" >';
				$ret .= '<span class="portfolio-overlay" >';
				$ret .= '<span class="portfolio-icon" ><i class="fa ' . totalbusiness_fa_class('icon-link') . '" ></i></span>';
				$ret .= '</span>';
				$ret .= '<div class="portfolio-thumbnail-bar"></div>';
				$ret .= '</a>';	
				
				// content
				$ret .= '<div class="portfolio-content-wrapper">';
				$ret .= '<div class="portfolio-content-overlay"></div>';
				$ret .= '<h3 class="portfolio-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '</div>'; // portfolio-content-wrapper
				$ret .= '</div>'; // portfolio-thumbnail	
				
				$ret .= '</div>'; // totalbusiness-item				
				$ret .= '</div>'; // column class
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}		
?>