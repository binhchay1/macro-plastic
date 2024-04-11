<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the admin option setting 
	*	---------------------------------------------------------------------
	*/
	
	// save the style-custom.css file when the admin option is saved
	add_action('totalbusiness_save_' . THEME_SLUG, 'totalbusiness_generate_style_custom');
	if( !function_exists('totalbusiness_generate_style_custom') ){
		function totalbusiness_generate_style_custom( $options ){
			if( empty($options) ) return;
			
			// for multisite
			$file_url = get_template_directory() . '/stylesheet/style-custom.css';
			if( is_multisite() && get_current_blog_id() > 1 ){
				$file_url = get_template_directory() . '/stylesheet/style-custom' . get_current_blog_id() . '.css';
			}
			
			// store the data
			$data = '';
			
			// write file content
			$theme_option = get_option(THEME_SHORT_NAME . '_admin_option', array());
			
			// for updating google font list to use on front end
			global $totalbusiness_font_controller; $google_font_list = array(); 
			
			foreach( $options as $menu_key => $menu ){
				foreach( $menu['options'] as $submenu_key => $submenu ){
					if( !empty($submenu['options']) ){
						foreach( $submenu['options'] as $option_slug => $option ){
							if( !empty($option['selector']) ){
								// prevents warning message
								$option['data-type'] = (empty($option['data-type']))? 'color': $option['data-type'];
								
								if( !empty($theme_option[$option_slug]) ){
									$value = totalbusiness_check_option_data_type($theme_option[$option_slug], $option['data-type']);
								}else{
									$value = '';
								}
								if($value){
									if( $option['data-type'] == 'rgba' ){
										$option['selector'] = str_replace('#gdlra#', totalbusiness_convert_rgba($value) , $option['selector']);
									}
									
									$data .= str_replace('#gdlr#', $value, $option['selector']) . "\n";
								}
								
								// updating google font list
								if( $menu_key == 'font-settings' && $submenu_key == 'font-family' ){
									if( !empty($totalbusiness_font_controller->google_font_list[$theme_option[$option_slug]]) ){
										$google_font_list[$theme_option[$option_slug]] = $totalbusiness_font_controller->google_font_list[$theme_option[$option_slug]];
									}
								}
							}
						}
					}
				}
			}
			
			$data .= ".half-container{ max-width: " . (intval($theme_option['container-width'])/2) . "px; } \n";
			
			
			// update google font list
			update_option(THEME_SHORT_NAME . '_google_font_list', $google_font_list);			
			
			$skins = json_decode($theme_option['skin-settings'], true);
			$skins = empty($skins)? array(): $skins;
			foreach($skins as $skin){
				$class = '.' . totalbusiness_string_to_class($skin['skin-title']);

				$style  = '#class#, #class# .totalbusiness-skin-content{ color: ' . $skin['content'] . '; }' . "\n"; 
				$style .= '#class# i, #class# .totalbusiness-flex-prev, #class# .totalbusiness-flex-next{ color: ' . $skin['icon'] . '; }' . "\n"; 
				$style .= '#class# h1, #class# h2, #class# h3, #class# h4, #class# h5, #class# h6, ';
				$style .= '#class# .totalbusiness-skin-title, #class# .totalbusiness-skin-title a{ color: ' . $skin['title'] . '; }' . "\n"; 
				$style .= '#class# .totalbusiness-skin-title a:hover{ color: ' . $skin['title-hover'] . '; }' . "\n"; 
				$style .= '#class# .totalbusiness-skin-info, #class# .totalbusiness-skin-info a, #class# .totalbusiness-skin-info a:hover{ color: ' . $skin['info'] . '; }' . "\n"; 
				$style .= '#class# a, #class# .totalbusiness-skin-link, #class# .totalbusiness-skin-link-color{ color: ' . $skin['link'] . '; }' . "\n"; 
				$style .= '#class# a:hover, #class# .totalbusiness-skin-link:hover{ color: ' . $skin['link-hover'] . '; }' . "\n"; 
				$style .= '#class# .totalbusiness-skin-box, #class# .totalbusiness-column-service-item .totalbusiness-skin-box{ background-color: ' . $skin['element-background'] . '; }' . "\n"; 
				$style .= '#class# *, #class# .totalbusiness-skin-border{ border-color: ' . $skin['border'] . '; }' . "\n"; 
				$style .= '#class# .totalbusiness-button, #class# .totalbusiness-button:hover, #class# input[type="button"], #class# input[type="submit"]{ ';
				$style .= 'color: ' . $skin['button-text'] . '; background-color: ' . $skin['button-background'] . ';  }' . "\n";
				$style = str_replace('#class#', $class, $style);
				$data .= $style;
			}

			$end_of_file = apply_filters('totalbusiness_style_custom_end', '', $theme_option);
			if(!empty($end_of_file)){
				$data .= $end_of_file;
			}
			
			if( !empty($theme_option['additional-style']) ){
				$data .= str_replace("\r\n", "\n", $theme_option['additional-style']);
			}

			$current_page =  wp_nonce_url(admin_url('admin.php?import=goodlayers-importer'),'totalbusiness-importer');
			if( !totalbusiness_write_filesystem($current_page, $file_url, $data) ){
				$ret = array(
					'status'=>'failed', 
					'message'=> '<span class="head">' . esc_html__('Cannot write style-custom.css file', 'totalbusiness') . '</span> ' .
						esc_html__('Please contact the administrator.' ,'totalbusiness')
				);	
				
				die(json_encode($ret));		
			}
		}
	}	
	
	// create the main admin option
	add_action('after_setup_theme', 'totalbusiness_create_admin_option');
	if( !function_exists('totalbusiness_create_admin_option') ){
	
		function totalbusiness_create_admin_option(){
			global $theme_option, $totalbusiness_sidebar_controller;
			if( empty($totalbusiness_sidebar_controller) ){ return false; }
		
			new totalbusiness_admin_option( 
				
				// admin option attribute
				array(
					'page_title' => THEME_FULL_NAME . ' ' . esc_html__('Option', 'totalbusiness'),
					'menu_title' => THEME_FULL_NAME,
					'menu_slug' => THEME_SLUG,
					'save_option' => THEME_SHORT_NAME . '_admin_option',
					'role' => 'edit_theme_options'
				),
					  
				// admin option setting
				apply_filters('totalbusiness_admin_option',
					array(
					
						// general menu
						'general' => array(
							'title' => esc_html__('General', 'totalbusiness'),
							'icon' => get_template_directory_uri() . '/include/images/icon-general.png',
							'options' => array(
								
								'page-style' => array(
									'title' => esc_html__('Page Style', 'totalbusiness'),
									'options' => array(
										'enable-boxed-style' => array(
											'title' => esc_html__('Container Style', 'totalbusiness'),
											'type' => 'combobox',	
											'options' => array(
												'full-style' => esc_html__('Full Style', 'totalbusiness'),
												'boxed-style' => esc_html__('Boxed Style', 'totalbusiness')
											)
										),
										'boxed-background-image' => array(
											'title' => esc_html__('Background Image', 'totalbusiness'),
											'type' => 'upload',
											'wrapper-class'=> 'enable-boxed-style-wrapper boxed-style-wrapper'
										),	
										'container-width' => array(
											'title' => esc_html__('Container Width', 'totalbusiness'),
											'type' => 'text',	
											'default' => '1140', 
											'data-type' => 'pixel',
											'selector' => 'html.ltie9 body, body{ min-width: #gdlr#; } .container{ max-width: #gdlr#; } ' .
												'.totalbusiness-caption-wrapper .totalbusiness-caption-inner{ max-width: #gdlr#; }'
										),
										'boxed-style-frame' => array(
											'title' => esc_html__('Boxed Style Frame Width', 'totalbusiness'),
											'type' => 'text',	
											'data-type' => 'pixel',
											'default' => '1220',
											'selector' => '.body-wrapper.totalbusiness-boxed-style{ max-width: #gdlr#; overflow: hidden; } ' .
												'.body-wrapper.totalbusiness-boxed-style .totalbusiness-header-wrapper{ max-width: #gdlr#; margin: 0px auto; }', 
											'description' => esc_html__('Default value is container width + 80', 'totalbusiness')
										),
										'enable-responsive-mode' => array(
											'title' => esc_html__('Enable Responsive', 'totalbusiness'),
											'type' => 'checkbox',	
											'default' => 'enable'
										),
										'sidebar-size' => array(
											'title' => esc_html__('Sidebar Size', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'2'=>esc_html__('16 Percent', 'totalbusiness'),
												'3'=>esc_html__('25 Percent', 'totalbusiness'),
												'4'=>esc_html__('33 Percent', 'totalbusiness'),
												'5'=>esc_html__('41 Percent', 'totalbusiness'),
												'6'=>esc_html__('50 Percent', 'totalbusiness')
											),
											'default' => '4',
											'descripton' => '1 column equals to around 80px',
										),		
										'both-sidebar-size' => array(
											'title' => esc_html__('Both Sidebar Size', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'3'=>esc_html__('25 Percent', 'totalbusiness'),
												'4'=>esc_html__('33 Percent', 'totalbusiness')
											),
											'default' => '3',
											'descripton' => '1 column equals to around 80px',
										),	
										'date-format' => array(
											'title' => esc_html__('Date Format', 'totalbusiness'),
											'type' => 'text',				
											'default'=>'d M Y',
											'description'=>esc_html__('See more details about the date format here. http://codex.wordpress.org/Formatting_Date_and_Time', 'totalbusiness')
										),	
										'video-ratio' => array(
											'title' => esc_html__('Default Video Ratio', 'totalbusiness'),
											'type' => 'text',				
											'default'=>'16/9',
											'description'=>esc_html__('Please only fill number/number as default video ratio', 'totalbusiness')
										),		
										'additional-style' => array(
											'title' => esc_html__('Additional Style', 'totalbusiness'),
											'type' => 'textarea',	
											'class' => 'full-width',
										),	
										'additional-script' => array(
											'title' => esc_html__('Additional Script ( no &lt;script> tag ) ', 'totalbusiness'),
											'type' => 'textarea',	
											'class' => 'full-width',
										),											
									)
								),

								'privacy' => array(
									'title' => esc_html__('Privacy', 'totalbusiness'),
									'options' => array(
										'disable-cookie-youtube' => array(
											'title' => esc_html__('Disable Cookie On Youtube Videos', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'disable'
										)
									)
								),
								
								'favicon' => array(
									'title' => esc_html__('Favicon', 'totalbusiness'),
									'options' => array(			
										'favicon-id' => array(
											'title' => esc_html__('Upload Favicon ( .ico file )', 'totalbusiness'),
											'button' => esc_html__('Select Icon', 'totalbusiness'),
											'type' => 'upload'
										),		
									)
								),
								
								'blog-style' => array(),	
								
								'portfolio-style' => array(),		

								'search-archive-style' => array(
									'title' => esc_html__('Search - Archive Style', 'totalbusiness'),
									'options' => array(
										'archive-sidebar-template' => array(
											'title' => esc_html__('Search - Archive Sidebar Template', 'totalbusiness'),
											'type' => 'radioimage',
											'options' => array(
												'no-sidebar'=>get_template_directory_uri() . '/include/images/no-sidebar.png',
												'both-sidebar'=>get_template_directory_uri() . '/include/images/both-sidebar.png', 
												'right-sidebar'=>get_template_directory_uri() . '/include/images/right-sidebar.png',
												'left-sidebar'=>get_template_directory_uri() . '/include/images/left-sidebar.png'
											),
											'default' => 'no-sidebar'							
										),
										'archive-sidebar-left' => array(
											'title' => esc_html__('Search - Archive Sidebar Left', 'totalbusiness'),
											'type' => 'combobox',
											'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),		
											'wrapper-class'=>'left-sidebar-wrapper both-sidebar-wrapper archive-sidebar-template-wrapper',											
										),
										'archive-sidebar-right' => array(
											'title' => esc_html__('Search - Archive Sidebar Right', 'totalbusiness'),
											'type' => 'combobox',
											'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),
											'wrapper-class'=>'right-sidebar-wrapper both-sidebar-wrapper archive-sidebar-template-wrapper',
										),		
										'archive-blog-style' => array(
											'title' => esc_html__('Search - Archive Blog Style', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'blog-1-4' => '1/4 ' . esc_html__('Blog Grid', 'totalbusiness'),
												'blog-1-3' => '1/3 ' . esc_html__('Blog Grid', 'totalbusiness'),
												'blog-1-2' => '1/2 ' . esc_html__('Blog Grid', 'totalbusiness'),
												'blog-1-1' => '1/1 ' . esc_html__('Blog Grid', 'totalbusiness'),
												'blog-medium' => esc_html__('Blog Medium', 'totalbusiness'),
												'blog-full' => esc_html__('Blog Full', 'totalbusiness'),
											),
											'default' => 'blog-1-3'							
										),			
										'archive-num-excerpt'=> array(
											'title'=> esc_html__('Search - Archive Num Excerpt (Word)' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> '25',
											'description'=> esc_html__('This is a number of word (decided by spaces) that you want to show on the post excerpt. <strong>Use 0 to hide the excerpt, -1 to show full posts and use the wordpress more tag</strong>.', 'totalbusiness')
										),
										'archive-thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list(),
											'default'=> 'small-grid-size',
											'description'=> esc_html__('Only effects to <strong>standard and gallery post format</strong>','totalbusiness')
										),		
										'archive-portfolio-style'=> array(
											'title'=> esc_html__('Archive Portfolio Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'classic-portfolio' => esc_html__('Portfolio Classic Style', 'totalbusiness'),
												'modern-portfolio' => esc_html__('Portfolio Modern Style', 'totalbusiness')
											),
										),							
										'archive-portfolio-size'=> array(
											'title'=> esc_html__('Portfolio Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'1/4'=>'1/4',
												'1/3'=>'1/3',
												'1/2'=>'1/2',
												'1/1'=>'1/1'
											),
											'default'=>'1/3',
											'wrapper-class'=> 'classic-portfolio-wrapper modern-portfolio-wrapper archive-portfolio-style-wrapper'
										),	
										'archive-portfolio-num-excerpt'=> array(
											'title'=> esc_html__('Portfolio Num Excerpt (Word)' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> '25',
											'wrapper-class'=> 'classic-portfolio-wrapper archive-portfolio-style-wrapper',
											'description'=> esc_html__('This is a number of word (decided by spaces) that you want to show on the post excerpt. <strong>Use 0 to hide the excerpt, -1 to show full posts and use the wordpress more tag</strong>.', 'totalbusiness')
										),
										'archive-portfolio-thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list(),
											'default'=> 'small-grid-size',
											'description'=> esc_html__('Only effects to <strong>standard and gallery post format</strong>','totalbusiness')
										),	
										
									)
								),			

								'woocommerce-style' => array(
									'title' => esc_html__('Woocommerce Style', 'totalbusiness'),
									'options' => array(	
										'all-products-per-row' => array(
											'title' => esc_html__('Products Per Row', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'1'=> '1',
												'2'=> '2',
												'3'=> '3',
												'4'=> '4',
												'5'=> '5'
											),
											'default' => '3'							
										),
										'all-products-sidebar' => array(
											'title' => esc_html__('All Products Sidebar', 'totalbusiness'),
											'type' => 'radioimage',
											'options' => array(
												'no-sidebar'=>get_template_directory_uri() . '/include/images/no-sidebar.png',
												'both-sidebar'=>get_template_directory_uri() . '/include/images/both-sidebar.png', 
												'right-sidebar'=>get_template_directory_uri() . '/include/images/right-sidebar.png',
												'left-sidebar'=>get_template_directory_uri() . '/include/images/left-sidebar.png'
											),
											'default' => 'no-sidebar'							
										),
										'all-products-sidebar-left' => array(
											'title' => esc_html__('All Products Sidebar Left', 'totalbusiness'),
											'type' => 'combobox',
											'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),		
											'wrapper-class'=>'left-sidebar-wrapper both-sidebar-wrapper all-products-sidebar-wrapper',											
										),
										'all-products-sidebar-right' => array(
											'title' => esc_html__('All Products Sidebar Right', 'totalbusiness'),
											'type' => 'combobox',
											'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),
											'wrapper-class'=>'right-sidebar-wrapper both-sidebar-wrapper all-products-sidebar-wrapper',
										),		
										'single-products-sidebar' => array(
											'title' => esc_html__('Single Products Sidebar', 'totalbusiness'),
											'type' => 'radioimage',
											'options' => array(
												'no-sidebar'=>get_template_directory_uri() . '/include/images/no-sidebar.png',
												'both-sidebar'=>get_template_directory_uri() . '/include/images/both-sidebar.png', 
												'right-sidebar'=>get_template_directory_uri() . '/include/images/right-sidebar.png',
												'left-sidebar'=>get_template_directory_uri() . '/include/images/left-sidebar.png'
											),
											'default' => 'no-sidebar'							
										),
										'single-products-sidebar-left' => array(
											'title' => esc_html__('Single Products Sidebar Left', 'totalbusiness'),
											'type' => 'combobox',
											'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),		
											'wrapper-class'=>'left-sidebar-wrapper both-sidebar-wrapper single-products-sidebar-wrapper',											
										),
										'single-products-sidebar-right' => array(
											'title' => esc_html__('Single products Sidebar Right', 'totalbusiness'),
											'type' => 'combobox',
											'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),
											'wrapper-class'=>'right-sidebar-wrapper both-sidebar-wrapper single-products-sidebar-wrapper',
										),											
									)
								),									
								
								'footer-style' => array(
									'title' => esc_html__('Footer - Copyright Style', 'totalbusiness'),
									'options' => array(
										'show-footer' => array(
											'title' => esc_html__('Show Footer', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable'
										),											
										'footer-layout' => array(
											'title' => esc_html__('Footer Layout', 'totalbusiness'),
											'type' => 'radioimage',
											'options' => array(
												'1'=>get_template_directory_uri() . '/include/images/footer-style1.png',
												'2'=>get_template_directory_uri() . '/include/images/footer-style2.png', 
												'3'=>get_template_directory_uri() . '/include/images/footer-style3.png',
												'4'=>get_template_directory_uri() . '/include/images/footer-style4.png',
												'5'=>get_template_directory_uri() . '/include/images/footer-style5.png',
												'6'=>get_template_directory_uri() . '/include/images/footer-style6.png'
											),
											'default' => '2'
										),
										'show-copyright' => array(
											'title' => esc_html__('Show Copyright', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable'
										),
									)
								),		

								'import-export-option' => array(
									'title' => esc_html__('Import/Export Option', 'totalbusiness'),
									'options' => array(
										'export-option' => array(
											'title' => esc_html__('Export Option', 'totalbusiness'),
											'type' => 'custom',
											'option' => 
												'<input type="button" id="totalbusiness-export" class="gdl-button" value="' . esc_html__('Export', 'totalbusiness') . '" />' .
												'<textarea class="full-width"></textarea>'
										),
										'import-option' => array(
											'title' => esc_html__('Import Option', 'totalbusiness'),
											'type' => 'custom',
											'option' => 
												'<input type="button" id="totalbusiness-import" class="gdl-button" value="' . esc_html__('Import', 'totalbusiness') . '" />' .
												'<textarea class="full-width"></textarea>'
										),										
									)
								),									
							
							)
						),

						// overall elements menu
						'overall-elements' => array(
							'title' => esc_html__('Overall Elements', 'totalbusiness'),
							'icon' => get_template_directory_uri() . '/include/images/icon-overall-elements.png',
							'options' => array(
	
								'top-bar' => array(
									'title' => esc_html__('Top Bar', 'totalbusiness'),
									'options' => array(
										'enable-top-bar' => array(
											'title' => esc_html__('Enable Top Bar', 'totalbusiness'),
											'type' => 'checkbox',									
										),
										'top-bar-left-text' => array(
											'title' => esc_html__('Top Bar Left Text', 'totalbusiness'),
											'type' => 'textarea',
										),
									)
								),
	
								'header-logo' => array(
									'title' => esc_html__('Header - Logo', 'totalbusiness'),
									'options' => array(
										'header-style' => array(
											'title' => esc_html__('Header Style', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'header-style-1' => esc_html__('Header Style 1', 'totalbusiness'),
												'header-style-2' => esc_html__('Header Style 2', 'totalbusiness'),
												'header-style-3' => esc_html__('Header Style 3', 'totalbusiness'),
												'header-style-4' => esc_html__('Header Style 4', 'totalbusiness'),
												'header-style-5' => esc_html__('Header Style 5', 'totalbusiness'),
												'header-style-6' => esc_html__('Header Style 6', 'totalbusiness'),
												'header-style-7' => esc_html__('Header Style 7', 'totalbusiness'),
											)
										),	
										'enable-float-menu' => array(
											'title' => esc_html__('Enable Float Menu', 'totalbusiness'),
											'type' => 'checkbox'
										),
										'logo-id' => array(
											'title' => esc_html__('Upload Logo', 'totalbusiness'),
											'button' => esc_html__('Set As Logo', 'totalbusiness'),
											'type' => 'upload'
										),
										'logo-right-text' => array(
											'title' => esc_html__('Logo Right Text', 'totalbusiness'),
											'type' => 'textarea',
											'wrapper-class' => 'header-style-wrapper header-style-3-wrapper'
										),
										'logo-max-width' => array(
											'title' => esc_html__('Logo Width', 'totalbusiness'),
											'type' => 'text',
											'default' => '320',
											'data-type' => 'pixel',
											'selector' => '.totalbusiness-logo-inner{ max-width: #gdlr#; }',
											'description' => esc_html__('You may upload 2x size image and limit the logo width for retina display', 'totalbusiness')
										),											
										'logo-top-margin' => array(
											'title' => esc_html__('Logo Top Margin', 'totalbusiness'),
											'type' => 'text',
											'default' => '32',
											'selector' => '.totalbusiness-logo{ margin-top: #gdlr#; }',
											'data-type' => 'pixel'
										),
										'logo-bottom-margin' => array(
											'title' => esc_html__('Logo Bottom Margin', 'totalbusiness'),
											'type' => 'text',
											'default' => '37',
											'selector' => '.totalbusiness-logo{ margin-bottom: #gdlr#; }',
											'data-type' => 'pixel'
										),
										'navigation-top-margin' => array(
											'title' => esc_html__('Navigation / Logo Right Top Margin', 'totalbusiness'),
											'type' => 'text',
											'default' => '42',
											'selector' => '.totalbusiness-navigation-wrapper, .totalbusiness-logo-right-text{ margin-top: #gdlr#; }',
											'data-type' => 'pixel'
										),	
										'navigation-bottom-margin' => array(
											'title' => esc_html__('Navigation Bottom Margin', 'totalbusiness'),
											'type' => 'text',
											'default' => '45',
											'selector' => '.totalbusiness-navigation-wrapper .totalbusiness-main-menu > li > a{ padding-bottom: #gdlr#; }',
											'data-type' => 'pixel'
										),										
									)
								),		

								'page-title-background' => array(
									'title' => esc_html__('Page Title Background', 'totalbusiness'),
									'options' => array(		
										'default-page-title' => array(
											'title' => esc_html__('Default Page Title Background', 'totalbusiness'),
											'type' => 'upload',	
											'selector' => '.totalbusiness-page-title-wrapper { background-image: url(\'#gdlr#\'); }',
											'data-type' => 'upload'
										),	
										'default-post-title-background' => array(
											'title' => esc_html__('Default Post Title Background', 'totalbusiness'),
											'type' => 'upload',	
											'selector' => 'body.single .totalbusiness-page-title-wrapper { background-image: url(\'#gdlr#\'); }',
											'data-type' => 'upload'
										),
										'default-portfolio-title-background' => array(
											'title' => esc_html__('Default Portfolio Title Background', 'totalbusiness'),
											'type' => 'upload',	
											'selector' => 'body.single-portfolio .totalbusiness-page-title-wrapper { background-image: url(\'#gdlr#\'); }',
											'data-type' => 'upload'
										),
										'default-search-archive-title-background' => array(
											'title' => esc_html__('Default Search Archive Title Background', 'totalbusiness'),
											'type' => 'upload',	
											'selector' => 'body.archive .totalbusiness-page-title-wrapper, body.search .totalbusiness-page-title-wrapper { background-image: url(\'#gdlr#\'); }',
											'data-type' => 'upload'
										),
										'default-404-title-background' => array(
											'title' => esc_html__('Default 404 Title Background', 'totalbusiness'),
											'type' => 'upload',	
											'selector' => 'body.error404 .totalbusiness-page-title-wrapper { background-image: url(\'#gdlr#\'); }',
											'data-type' => 'upload'
										),
									)					
								),								
								
								'header-social' => array(),
								
								'social-shares' => array(),
								
								'copyright' => array(
									'title' => esc_html__('Copyright', 'totalbusiness'),
									'options' => array(		
									
										'copyright-left-text' => array(
											'title' => esc_html__('Copyright Left Text', 'totalbusiness'),
											'type' => 'textarea',	
											'class' => 'full-width',
										),		
										'copyright-right-text' => array(
											'title' => esc_html__('Copyright Right Text', 'totalbusiness'),
											'type' => 'textarea',	
											'class' => 'full-width',
										),											
									)					
								)
							)				
						),
						
						// font setting menu
						'font-settings' => array(
							'title' => esc_html__('Font Setting', 'totalbusiness'),
							'icon' => get_template_directory_uri() . '/include/images/icon-font-settings.png',
							'options' => array(

								'font-family' => array(),								

								'font-size' => array(
									'title' => esc_html__('Font Size', 'totalbusiness'),
									'options' => array(
										
										'content-font-size' => array(
											'title' => esc_html__('Content Size', 'totalbusiness'),
											'type' => 'sliderbar',
											'default' => '14',
											'selector' => 'body{ font-size: #gdlr#; }',
											'data-type' => 'pixel'											
										),				
										'h1-font-size' => array(
											'title' => esc_html__('H1 Size', 'totalbusiness'),
											'type' => 'sliderbar',
											'default' => '30',
											'selector' => 'h1{ font-size: #gdlr#; }',
											'data-type' => 'pixel'											
										),
										'h2-font-size' => array(
											'title' => esc_html__('H2 Size', 'totalbusiness'),
											'type' => 'sliderbar',
											'default' => '25',
											'selector' => 'h2{ font-size: #gdlr#; }',
											'data-type' => 'pixel'											
										),
										'h3-font-size' => array(
											'title' => esc_html__('H3 Size', 'totalbusiness'),
											'type' => 'sliderbar',
											'default' => '20',
											'selector' => 'h3{ font-size: #gdlr#; }',
											'data-type' => 'pixel'											
										),
										'h4-font-size' => array(
											'title' => esc_html__('H4 Size', 'totalbusiness'),
											'type' => 'sliderbar',
											'default' => '18',
											'selector' => 'h4{ font-size: #gdlr#; }',
											'data-type' => 'pixel'											
										),
										'h5-font-size' => array(
											'title' => esc_html__('H5 Size', 'totalbusiness'),
											'type' => 'sliderbar',
											'default' => '16',
											'selector' => 'h5{ font-size: #gdlr#; }',
											'data-type' => 'pixel'											
										),
										'h6-font-size' => array(
											'title' => esc_html__('H6 Size', 'totalbusiness'),
											'type' => 'sliderbar',
											'default' => '15',
											'selector' => 'h6{ font-size: #gdlr#; }',
											'data-type' => 'pixel'											
										),
										'navigation-font-size' => array(
											'title' => esc_html__('Navigation Font Size', 'totalbusiness'),
											'type' => 'sliderbar',
											'default' => '12',
											'selector' => '.totalbusiness-navigation-wrapper .totalbusiness-main-menu > li > a{ font-size: #gdlr#; }',
											'data-type' => 'pixel'											
										),
									)
								),								

								'upload-font' => array(
									'title' => esc_html__('Upload Font', 'totalbusiness'),
									'options' => array(
										'upload-font' => array(
											'type' => 'uploadfont'
										)
									)
								),									
								
							)					
						),
							
						// elements color menu
						'elements-color' => array(
							'title' => esc_html__('Elements Color', 'totalbusiness'),
							'icon' => get_template_directory_uri() . '/include/images/icon-elements-color.png',
							'options' => array(
							
								'skin-settings' => array(
									'title' => esc_html__('Cutom Skin', 'totalbusiness'),
									'options' => array(
											'skin-settings' => array(
											'title' => esc_html__('Skin Settings', 'totalbusiness'),
											'type' => 'skin-settings',
											'options' => array(
												'title'=>esc_html__('Title Color', 'totalbusiness'),
												'title-hover'=>esc_html__('Title (Link) Hover Color', 'totalbusiness'),
												'info'=>esc_html__('Caption / Info Color', 'totalbusiness'),
												'link'=>esc_html__('Link Color', 'totalbusiness'),
												'link-hover'=>esc_html__('Link Hover Color', 'totalbusiness'),
												'element-background'=>esc_html__('Element Background', 'totalbusiness'),
												'content'=>esc_html__('Content Color', 'totalbusiness'),
												'icon'=>esc_html__('Icon Color', 'totalbusiness'),
												'border'=>esc_html__('Border Color', 'totalbusiness'),
												'button-text'=>esc_html__('Button Text Color', 'totalbusiness'),
												'button-background'=>esc_html__('Button Background Color', 'totalbusiness'),
											)
										),	
									)
								),							

								'top-bar-color' => array(
									'title' => esc_html__('Header / Main Menu', 'totalbusiness'),
									'options' => array(						
										'top-bar-background' => array(
											'title' => esc_html__('Top Bar Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'selector'=> '.top-navigation-wrapper{ background-color: #gdlr#; }'
										),
										'top-bar-text-color' => array(
											'title' => esc_html__('Top Bar Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#959595',
											'selector'=> '.top-navigation-wrapper{ color: #gdlr#; }'
										),
										'top-bar-link-color' => array(
											'title' => esc_html__('Top Bar Link', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#333333',
											'selector'=> '.top-navigation-wrapper a{ color: #gdlr#; }'
										),
										'header-background-color' => array(
											'title' => esc_html__('Header Background Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'data-type' => 'rgba',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-header-inner, ' . 
												'.totalbusiness-header-wrapper.header-style-2-wrapper .totalbusiness-header-inner-overlay{ background-color: #gdlr#; }' .
												'.totalbusiness-header-wrapper.header-style-3-wrapper, .totalbusiness-header-wrapper.header-style-7-wrapper{ background-color: #gdlr#; }' .
												'.totalbusiness-header-wrapper.header-style-5-wrapper .totalbusiness-header-inner{ background-color: rgba(#gdlra#, 0.75); }' .
												'@media only screen and (max-width: 767px) { .totalbusiness-header-wrapper.header-style-4-wrapper{ background-color: #gdlr#; } ' .
												'.totalbusiness-header-wrapper.header-style-5-wrapper .totalbusiness-header-inner{ background-color: #gdlr#; } ' .
												'.totalbusiness-header-wrapper.header-style-6-wrapper{ background-color: #gdlr#; } }'
										),
										'main-navigation-text' => array(
											'title' => esc_html__('Main Navigation Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#7c7c7c',
											'selector'=> '.totalbusiness-main-menu > li > a, .totalbusiness-cart-item-count{ color: #gdlr#; }'
										),	
										'main-navigation-background' => array(
											'title' => esc_html__('Main Navigation Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f5f5f5',
											'selector'=> '.totalbusiness-header-wrapper.header-style-3-wrapper .totalbusiness-header-inner, ' .
												'.totalbusiness-header-wrapper.header-style-7-wrapper .totalbusiness-header-inner{ background-color: #gdlr#; }',
											'description' => esc_html__('Only for header style 3 and 8', 'totalbusiness')
											
										),	
										'main-navigation-text-hover' => array(
											'title' => esc_html__('Main Navigation Text Hover/Current', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-main-menu > li:hover > a, .totalbusiness-main-menu > li.current-menu-item > a, ' .
												'.totalbusiness-main-menu > li.current-menu-ancestor > a{ color: #gdlr#; opacity: 1; filter: alpha(opacity=100); }'
										),		
										'bucket-color' => array(
											'title' => esc_html__('Header Search/Cart Icon', 'totalbusiness'),
											'type' => 'combobox',
											'data-type' => 'text',
											'options' => array(
												'dark' => esc_html__('Dark', 'totalbusiness'), 
												'light' => esc_html__('Light', 'totalbusiness')
											),	
											'default' => 'dark',
										),
										'search-box-background-color' => array(
											'title' => esc_html__('Header Search Box Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'data-type' => 'rgba',
											'default' => '#2b2b2b',
											'selector'=> '.totalbusiness-top-woocommerce-inner, .totalbusiness-menu-search{ background: #gdlr#; background: rgba(#gdlra#, 0.8); }'
										),
										'search-box-text-color' => array(
											'title' => esc_html__('Header Search Box Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-top-woocommerce, .totalbusiness-top-woocommerce-inner > a, .totalbusiness-menu-search input[type="text"]{ color: #gdlr#; }'
										),
									)
								),		

								'main-navigation-color' => array(
									'title' => esc_html__('Sub Menu / Search', 'totalbusiness'),
									'options' => array(
										'sub-menu-top-border' => array(
											'title' => esc_html__('Sub Menu Top Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#1c1c1c',
											'selector'=> '.totalbusiness-main-menu > .totalbusiness-normal-menu .sub-menu, .totalbusiness-main-menu > .totalbusiness-mega-menu ' . 
												'.sf-mega{ border-top-color: #gdlr#; }'
										),			
										'sub-menu-background' => array(
											'title' => esc_html__('Sub Menu Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#2e2e2e',
											'selector'=> '.totalbusiness-main-menu > .totalbusiness-normal-menu li , .totalbusiness-main-menu > .totalbusiness-mega-menu .sf-mega{ background-color: #gdlr#; }'
										),		
										'sub-menu-text' => array(
											'title' => esc_html__('Sub Menu Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#bebebe',
											'selector'=> '.totalbusiness-main-menu > li > .sub-menu a, .totalbusiness-main-menu > li > .sf-mega a{ color: #gdlr#; }'
										),
										'sub-menu-text-hover' => array(
											'title' => esc_html__('Sub Menu Text Hover/Current', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-main-menu > li > .sub-menu a:hover, .totalbusiness-main-menu > li > .sub-menu .current-menu-item > a, ' .
												'.totalbusiness-main-menu > li > .sub-menu .current-menu-ancestor > a, .totalbusiness-main-menu > li > .sf-mega a:hover, ' .
												'.totalbusiness-main-menu > li > .sf-mega .current-menu-item > a, .totalbusiness-main-menu > li > .sf-mega .current-menu-ancestor > a{ color: #gdlr#; } ' .
												'.totalbusiness-main-menu .totalbusiness-normal-menu li > a.sf-with-ul:after { border-left-color: #gdlr#; } '
										),		
										'sub-mega-menu-text-hover' => array(
											'title' => esc_html__('Sub Mega Menu Background Hover', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#2a2a2a',
											'selector'=> '.totalbusiness-main-menu .sf-mega-section-inner > ul > li > a:hover, ' . 
												'.totalbusiness-main-menu .sf-mega-section-inner > ul > li.current-menu-item > a { background-color: #gdlr#; } '
										),										
										'sub-menu-divider' => array(
											'title' => esc_html__('Sub Menu Divider', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#373737',
											'selector'=> '.totalbusiness-main-menu > li > .sub-menu *, .totalbusiness-main-menu > li > .sf-mega *{ border-color: #gdlr#; }'
										),				
										'sub-menu-mega-title' => array(
											'title' => esc_html__('Sub (Mega) Menu Title', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-main-menu > li > .sf-mega .sf-mega-section-inner > a { color: #gdlr#; }'
										),			
										'sub-menu-mega-title-hover' => array(
											'title' => esc_html__('Sub (Mega) Menu Title Hover/Current', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-main-menu > li > .sf-mega .sf-mega-section-inner > a:hover, ' . 
												'.totalbusiness-main-menu > li > .sf-mega .sf-mega-section-inner.current-menu-item > a, ' .
												'.totalbusiness-main-menu > li > .sf-mega .sf-mega-section-inner.current-menu-ancestor > a { color: #gdlr#; }'
										),			
										'mobile-menu-background' => array(
											'title' => esc_html__('Mobile Menu Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#353535',
											'selector'=> '#totalbusiness-responsive-navigation.dl-menuwrapper button { background-color: #gdlr#; }'
										),
										'mobile-menu-background-hover' => array(
											'title' => esc_html__('Mobile Menu Background Hover', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#0a0a0a',
											'selector'=> '#totalbusiness-responsive-navigation.dl-menuwrapper button:hover, ' .
												'#totalbusiness-responsive-navigation.dl-menuwrapper button.dl-active, ' . 
												'#totalbusiness-responsive-navigation.dl-menuwrapper ul{ background-color: #gdlr#; }'
										),
									)
								),
								
								'body-color' => array(
									'title' => esc_html__('Body', 'totalbusiness'),
									'options' => array(
										'body-background' => array(
											'title' => esc_html__('Body Background ( for boxed style )', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#dddddd',
											'selector'=> 'body{ background-color: #gdlr#; }'
										),	
										'container-backgrond' => array(
											'title' => esc_html__('Container Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.body-wrapper, .totalbusiness-single-lightbox-container{ background-color: #gdlr#; }'
										),	
										'page-title-color' => array(
											'title' => esc_html__('Page Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-page-title, .totalbusiness-page-title-gimmick{ color: #gdlr#; }' . 
												'.totalbusiness-header-wrapper.header-style-4-wrapper .totalbusiness-header-inner-overlay{ border-color: #gdlr#; }'
										),		
										'page-caption-color' => array(
											'title' => esc_html__('Page Caption Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-page-caption{ color: #gdlr#; }'
										),	
										'heading-color' => array(
											'title' => esc_html__('Heading Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#454545',
											'selector'=> 'h1, h2, h3, h4, h5, h6, .totalbusiness-title, .totalbusiness-title a{ color: #gdlr#; }'
										),		
										'item-title-color' => array(
											'title' => esc_html__('Item Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#292929',
											'selector'=> '.totalbusiness-item-title-wrapper .totalbusiness-item-title{ color: #gdlr#; border-color: #gdlr#; }'
										),												
										'item-title-line' => array(
											'title' => esc_html__('Item Title Divider', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#333333',
											'selector'=> '.totalbusiness-item-title-divider{ border-color: #gdlr#; }'
										),	
										'item-title-caption-color' => array(
											'title' => esc_html__('Item Title Caption Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#9b9b9b',
											'selector'=> '.totalbusiness-item-title-wrapper .totalbusiness-item-caption{ color: #gdlr#; }'
										),	
										'body-text-color' => array(
											'title' => esc_html__('Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#808080',
											'selector'=> 'body{ color: #gdlr#; }'
										),		
										'body-link-color' => array(
											'title' => esc_html__('Link Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#7fcadb',
											'selector'=> 'a{ color: #gdlr#; }'
										),			
										'body-link-hover-color' => array(
											'title' => esc_html__('Link Hover Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> 'a:hover{ color: #gdlr#; }'
										),			
										'border-color' => array(
											'title' => esc_html__('Border Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#dddddd',
											'selector'=> 'body *{ border-color: #gdlr#; }'
										),		
										'404-box-background' => array(
											'title' => esc_html__('404 Box Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#d65938',
											'selector'=> '.page-not-found-block{ background-color: #gdlr#; }'
										),		
										'404-box-text' => array(
											'title' => esc_html__('404 Box Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.page-not-found-block{ color: #gdlr#; }'
										),		
										'404-search-background' => array(
											'title' => esc_html__('404 Search Box Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#963a20',
											'selector'=> '.page-not-found-search  .gdl-search-form input[type="text"]{ background-color: #gdlr#; }'
										),		
										'404-search-text' => array(
											'title' => esc_html__('404 Search Box Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#d57f5c',
											'selector'=> '.page-not-found-search  .gdl-search-form input[type="text"]{ color: #gdlr#; }'
										),											
									)	
								),	
								
								'sidebar-color' => array(
									'title' => esc_html__('Sidebar Color', 'totalbusiness'),
									'options' => array(
										'sidebar-title-color' => array(
											'title' => esc_html__('Sidebar Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#383838',
											'selector'=> '.totalbusiness-sidebar .totalbusiness-widget-title{ color: #gdlr#; }'
										),	
										'sidebar-border-color' => array(
											'title' => esc_html__('Sidebar Border Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#eeeeee',
											'selector'=> '.totalbusiness-sidebar *{ border-color: #gdlr#; }'
										),
										'sidebar-list-circle' => array(
											'title' => esc_html__('Sidebar List Circle', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#bdbdbd',
											'selector'=> '.totalbusiness-sidebar ul li:before { border-color: #gdlr#; }'
										),
										'search-form-background' => array(
											'title' => esc_html__('Search Form Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f7f7f7',
											'selector'=> '.gdl-search-form input{ background-color: #gdlr#; }'
										),
										'search-form-text-color' => array(
											'title' => esc_html__('Search Form Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#9d9d9d',
											'selector'=> '.gdl-search-form input{ color: #gdlr#; }'
										),
										'search-form-border-color' => array(
											'title' => esc_html__('Search Form Border Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ebebeb',
											'selector'=> '.gdl-search-form input{ border-color: #gdlr#; }'
										),
										'tag-cloud-background' => array(
											'title' => esc_html__('Tagcloud Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.tagcloud a{ background-color: #gdlr#; }'
										),
										'tag-cloud-text' => array(
											'title' => esc_html__('Tagcloud Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.tagcloud a, .tagcloud a:hover{ color: #gdlr#; }'
										),
									)
								),

								'content-item-1' => array(
									'title' => esc_html__('Content Elements', 'totalbusiness'),
									'options' => array(		
										'about-us-title' => array(
											'title' => esc_html__('About Us title', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#3a3a3a',
											'selector'=> '.about-us-title{ color: #gdlr#; }'
										),		
										'about-us-caption' => array(
											'title' => esc_html__('About Us Caption', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#3a3a3a',
											'selector'=> '.about-us-caption{ color: #gdlr#; }'
										),		
										'about-us-divider' => array(
											'title' => esc_html__('About Us Title Divider', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.about-us-title-divider{ border-color: #gdlr#; }'
										),
										'accordion-text' => array(
											'title' => esc_html__('Accordion (Style 1) Title Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#3c3c3c',
											'selector'=> '.totalbusiness-accordion-item.style-1 .pre-active .accordion-title{ color: #gdlr#; }'
										),		
										'accordion-title-active-color' => array(
											'title' => esc_html__('Accordion (Style 1) Title Active Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#8d8d8d',
											'selector'=> '.totalbusiness-accordion-item.style-1 .accordion-title{ color: #gdlr#; }'
										),										
										'accordion-icon-background' => array(
											'title' => esc_html__('Accordion (Style 1) Icon Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'selector'=> '.totalbusiness-accordion-item.style-1 .accordion-title i{ background-color: #gdlr#; }'
										),			
										'accordion-icon-color' => array(
											'title' => esc_html__('Accordion (Style 1) Icon Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#a8a8a8',
											'selector'=> '.totalbusiness-accordion-item.style-1 .accordion-title i{ color: #gdlr#; }'
										),	
										'accordion-icon-active-background' => array(
											'title' => esc_html__('Accordion (Style 1) Icon Active Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-accordion-item.style-1 .accordion-title i.icon-minus{ background-color: #gdlr#; }'
										),			
										'accordion-icon-active-color' => array(
											'title' => esc_html__('Accordion (Style 1) Icon Active Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-accordion-item.style-1 .accordion-title i.icon-minus{ color: #gdlr#; }'
										),	
										'banner-icon-color' => array(
											'title' => esc_html__('Banner Item Navigation Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#999999',
											'selector'=> '.totalbusiness-banner-item-wrapper .flex-direction-nav .flex-prev, ' . 
												'.totalbusiness-banner-item-wrapper .flex-direction-nav .flex-next{ color: #gdlr#; }'
										),										
										'box-with-icon-background' => array(
											'title' => esc_html__('Box With Icon Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f5f5f5',
											'selector'=> '.totalbusiness-box-with-icon-item{ background-color: #gdlr#; }'
										),	
										'box-with-icon-title' => array(
											'title' => esc_html__('Box With Icon Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#585858',
											'selector'=> '.totalbusiness-box-with-icon-item > i, ' . 
												'.totalbusiness-box-with-icon-item .box-with-icon-title{ color: #gdlr#; }'
										),											
										'box-with-icon-text' => array(
											'title' => esc_html__('Box With Icon Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#929292',
											'selector'=> '.totalbusiness-box-with-icon-item{ color: #gdlr#; }'
										),			
										'button-text-color' => array(
											'title' => esc_html__('Button Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-button, .totalbusiness-button:hover, input[type="button"], input[type="submit"], ' . 
												'.totalbusiness-top-menu > .totalbusiness-mega-menu .sf-mega a.totalbusiness-button{ color: #gdlr#; }'
										),	
										'button-background-color' => array(
											'title' => esc_html__('Button Background Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-button, input[type="button"], input[type="submit"]{ background-color: #gdlr#; }' .
												'.totalbusiness-border-button{ border-color: #gdlr#; color: #gdlr#; }'
										),											
										'button-border-color' => array(
											'title' => esc_html__('Button Border Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#e6e6e6',
											'selector'=> '.totalbusiness-button{ border-color: #gdlr#; }'
										),										
										'column-service-title-color' => array(
											'title' => esc_html__('Column Service Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#6fc6d9',
											'selector'=> '.column-service-title{ color: #gdlr#; }'
										),										
										'column-service-content-color' => array(
											'title' => esc_html__('Column Service Content Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#808080',
											'selector'=> '.column-service-content{ color: #gdlr#; }'
										),										
										'column-service-icon-color' => array(
											'title' => esc_html__('Column Service Icon Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.column-service-icon i{ color: #gdlr#; }' . 
												'.totalbusiness-column-service-item.totalbusiness-type-2 .column-service-icon{ border-color: #gdlr#; }'
										),									
										'list-with-icon-title-color' => array(
											'title' => esc_html__('List With Icon Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#333333',
											'selector'=> '.list-with-icon .list-with-icon-title{ color: #gdlr#; }'
										),									
										'list-with-icon-icon-color' => array(
											'title' => esc_html__('List With Icon Icon Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#333333',
											'selector'=> '.list-with-icon .list-with-icon-icon{ border-color: #gdlr#; color: #gdlr#; }'
										),									
										'list-menu-item-title' => array(
											'title' => esc_html__('Menu List Item Title', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-menu-title, .totalbusiness-menu-icon, .totalbusiness-menu-price{ color: #gdlr#; }'
										),										
										'list-menu-item-caption' => array(
											'title' => esc_html__('Menu List Item Caption', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#818181',
											'selector'=> '.totalbusiness-menu-ingredients-caption { color: #gdlr#; }'
										),											
										'list-menu-item-divider' => array(
											'title' => esc_html__('Menu List Item Caption', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#000000',
											'selector'=> '.totalbusiness-list-menu-gimmick{ border-color: #gdlr#; color: #gdlr#; }'
										),						
									)	
								),								

								'content-item-2' => array(
									'title' => esc_html__('Content Elements 2', 'totalbusiness'),
									'options' => array(	
										'pie-chart-title-color' => array(
											'title' => esc_html__('Pie Chart Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#313131',
											'selector'=> '.totalbusiness-pie-chart-item .pie-chart-title{ color: #gdlr#; }'
										),	
										'price-background' => array(
											'title' => esc_html__('Price Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f9f9f9',
											'selector'=> '.totalbusiness-price-inner-item{ background-color: #gdlr#; }'
										),
										'price-title-background' => array(
											'title' => esc_html__('Price Title Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#454545',
											'selector'=> '.totalbusiness-price-item .price-title-wrapper{ background-color: #gdlr#; }'
										),
										'price-title-text' => array(
											'title' => esc_html__('Price Title Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-price-item .price-title{ color: #gdlr#; }'
										),
										'price-tag-background' => array(
											'title' => esc_html__('Price Tag Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#838383',
											'selector'=> '.totalbusiness-price-item .price-tag{ background-color: #gdlr#; }'
										),
										'active-price-tag-background' => array(
											'title' => esc_html__('Active Price Tag Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-price-item .best-price .price-tag{ background-color: #gdlr#; }'
										),
										'price-tag-text' => array(
											'title' => esc_html__('Price Tag Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-price-item .price-tag{ color: #gdlr#; }'
										),	
										'process-icon-background' => array(
											'title' => esc_html__('Process Icon Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'selector'=> '.totalbusiness-process-tab .totalbusiness-process-icon{ background-color: #gdlr#; }'
										),
										'process-icon-border' => array(
											'title' => esc_html__('Process Icon Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#e3e3e3',
											'selector'=> '.totalbusiness-process-tab .totalbusiness-process-icon{ border-color: #gdlr#; }'
										),
										'process-icon-color' => array(
											'title' => esc_html__('Process Icon Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#aaaaaa',
											'selector'=> '.totalbusiness-process-tab .totalbusiness-process-icon i{ color: #gdlr#; }'
										),
										'process-line-color' => array(
											'title' => esc_html__('Process Line Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#e3e3e3',
											'selector'=> '.totalbusiness-process-tab .process-line .process-line-divider{ border-color: #gdlr#; } ' .
												'.totalbusiness-process-tab .process-line .icon-chevron-down, ' .
												'.totalbusiness-process-tab .process-line .icon-chevron-right{ color: #gdlr#; }'
										),
										'process-title-color' => array(
											'title' => esc_html__('Process Title', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#454545',
											'selector'=> '.totalbusiness-process-wrapper .totalbusiness-process-tab .totalbusiness-process-title{ color: #gdlr#; }'
										),
										'skill-text-color' => array(
											'title' => esc_html__('Skill Item Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#3a3a3a',
											'selector'=> '.totalbusiness-skill-item-wrapper{ color: #gdlr#; }'
										),
										'stunning-text-title-color' => array(
											'title' => esc_html__('Stunning Text Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#414141',
											'selector'=> '.stunning-text-title, .stunning-item-title{ color: #gdlr#; }'
										),
										'stunning-text-caption-color' => array(
											'title' => esc_html__('Stunning Text Caption Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#949494',
											'selector'=> '.stunning-text-caption, .stunning-item-caption{ color: #gdlr#; }'
										),
										'stunning-text-background' => array(
											'title' => esc_html__('Stunning Text Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'selector'=> '.totalbusiness-stunning-text-item.with-padding{ background-color: #gdlr#; }'
										),
										'stunning-text-border' => array(
											'title' => esc_html__('Stunning Text Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'selector'=> '.totalbusiness-stunning-text-item.with-border{ border-color: #gdlr#; }'
										),
										'tab-title-background' => array(
											'title' => esc_html__('Tab Title Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f9f9f9',
											'selector'=> '.tab-title-wrapper .tab-title{ background-color: #gdlr#; }'
										),			
										'tab-title-color' => array(
											'title' => esc_html__('Tab Title Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#3b3b3b',
											'selector'=> '.tab-title-wrapper .tab-title{ color: #gdlr#; }'
										),	
										'tab-title-content' => array(
											'title' => esc_html__('Tab Title Content Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.tab-title-wrapper .tab-title.active, .tab-content-wrapper{ background-color: #gdlr#; }'
										),										
										'table-head-background' => array(
											'title' => esc_html__('Table Head Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> 'table tr th{ background-color: #gdlr#; }'
										),			
										'table-head-text' => array(
											'title' => esc_html__('Table Head Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> 'table tr th{ color: #gdlr#; }'
										),	
										'table-style2-odd-background' => array(
											'title' => esc_html__('Table (Style2) Odd Row Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f9f9f9',
											'selector'=> 'table.style-2 tr:nth-child(odd){ background-color: #gdlr#; }'
										),			
										'table-style2-odd-text' => array(
											'title' => esc_html__('Table (Style2) Odd Row Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#949494',
											'selector'=> 'table.style-2 tr:nth-child(odd){ color: #gdlr#; }'
										),
										'table-style2-even-background' => array(
											'title' => esc_html__('Table (Style2) Even Row Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'selector'=> 'table.style-2 tr:nth-child(even){ background-color: #gdlr#; }'
										),			
										'table-style2-even-text' => array(
											'title' => esc_html__('Table (Style2) Even Row Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#949494',
											'selector'=> 'table.style-2 tr:nth-child(even){ color: #gdlr#; }'
										),		
									)
								),
								
								'blog-color' => array(
									'title' => esc_html__('Blog Color', 'totalbusiness'),
									'options' => array(
										'blog-title-color' => array(
											'title' => esc_html__('Blog Title Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#212121',
											'selector'=> '.totalbusiness-blog-title, .totalbusiness-blog-title a{ color: #gdlr#; }'
										),		
										'blog-title-hover-color' => array(
											'title' => esc_html__('Blog Title Hover Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-blog-title a:hover{ color: #gdlr#; }'
										),	
										'blog-info-color' => array(
											'title' => esc_html__('Blog Info Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#a8a8a8',
											'selector'=> '.blog-info, .blog-info a, .blog-info i{ color: #gdlr#; }'
										),
										'blog-sticky-background' => array(
											'title' => esc_html__('Blog Sticky Bar Backgrond', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#232323',
											'selector'=> '.totalbusiness-blog-thumbnail .totalbusiness-sticky-banner{ background-color: #gdlr#; }'
										),
										'blog-sticky-text' => array(
											'title' => esc_html__('Blog Sticky Bar Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-blog-thumbnail .totalbusiness-sticky-banner, .totalbusiness-blog-thumbnail .totalbusiness-sticky-banner i{ color: #gdlr#; }'
										),
										'blog-share-background' => array(
											'title' => esc_html__('Blog Social Share Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'selector'=> '.totalbusiness-social-share, .totalbusiness-social-share a{ background-color: #gdlr#; }'
										),			
										'blog-share-title' => array(
											'title' => esc_html__('Blog Social Share Title', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#353535',
											'selector'=> '.totalbusiness-social-share .social-share-title{ color: #gdlr#; }'
										),										
										'blog-tag-background' => array(
											'title' => esc_html__('Blog Tag Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-standard-style .totalbusiness-single-blog-tag a{ background-color: #gdlr#; }'
										),			
										'blog-tag-text-color' => array(
											'title' => esc_html__('Blog Tag Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-standard-style .totalbusiness-single-blog-tag a{ color: #gdlr#; }'
										),	
										'blog-grid-background' => array(
											'title' => esc_html__('Blog Grid Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-blog-grid, .totalbusiness-blog-widget .post-header{ background-color: #gdlr#; }'
										),
										'blog-aside-background' => array(
											'title' => esc_html__('Blog Aside Format Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.format-aside .totalbusiness-blog-content{ background-color: #gdlr#; }'
										),			
										'blog-aside-text' => array(
											'title' => esc_html__('Blog Aside Format Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.format-aside .totalbusiness-blog-content{ color: #gdlr#; }'
										),	
										'blog-quote-text-color' => array(
											'title' => esc_html__('Blog Quote Format Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#8d8d8d',
											'selector'=> '.format-quote .totalbusiness-top-quote blockquote{ color: #gdlr#; }'
										),
										'blog-quote-author-color' => array(
											'title' => esc_html__('Blog Quote Format Author', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.format-quote .totalbusiness-quote-author{ color: #gdlr#; }'
										),
										'blog-navigation-background' => array(
											'title' => esc_html__('Blog Navigation Background ( Prev / Next )', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'selector'=> '.totalbusiness-single-nav > div i{ background-color: #gdlr#; }'
										),		
										'blog-navigation-text' => array(
											'title' => esc_html__('Blog Navigation Icon ( Prev / Next )', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#8d8d8d',
											'selector'=> '.totalbusiness-single-nav > div i{ color: #gdlr#; }'
										),
									)
								),								

								'portfolio-color' => array(
									'title' => esc_html__('Portfolio / Pagination', 'totalbusiness'),
									'options' => array(
										'portfolio-filter-button-text' => array(
											'title' => esc_html__('Portfolio Filter Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#5b5b5b',
											'selector'=> '.portfolio-item-filter a{ color: #gdlr#; } '
										),					
										'portfolio-filter-button-active-text' => array(
											'title' => esc_html__('Portfolio Filter Active Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.portfolio-item-filter a.active{ color: #gdlr#; }'
										),	
										'portfolio-thumbnail-hover-background' => array(
											'title' => esc_html__('Portfolio Thumbnail Hover Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#000000',
											'selector'=> '.totalbusiness-image-link-shortcode .totalbusiness-image-link-overlay, ' .
												'.portfolio-thumbnail .portfolio-overlay{ background-color: #gdlr#; }'
										),
										'portfolio-thumbnail-hover-icon' => array(
											'title' => esc_html__('Portfolio Thumbnail Hover Icon/Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-image-link-shortcode .totalbusiness-image-link-icon, .portfolio-thumbnail .portfolio-icon i, .totalbusiness-image-link-shortcode .totalbusiness-image-frame-content,' . 
												'.totalbusiness-modern-portfolio .portfolio-title a, .totalbusiness-modern-portfolio .portfolio-title a:hover, .totalbusiness-modern-portfolio .totalbusiness-portfolio-info a{ border-color: #gdlr#; color: #gdlr#; }'
										),										
										'portfolio-title-color' => array(
											'title' => esc_html__('Portfolio Title', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#303030',
											'selector'=> '.portfolio-title a{ color: #gdlr#; }'
										),
										'portfolio-title-hover-color' => array(
											'title' => esc_html__('Portfolio Title Hover', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#7f7f7f',
											'selector'=> '.portfolio-title a:hover{ color: #gdlr#; }'
										),
										'portfolio-info-color' => array(
											'title' => esc_html__('Portfolio Info', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#a2a2a2',
											'selector'=> '.portfolio-info, ' .
												'.portfolio-info a{ color: #gdlr#; }'
										),	
										'pagination-background' => array(
											'title' => esc_html__('Pagination Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ebebeb',
											'selector'=> '.totalbusiness-pagination .page-numbers{ background-color: #gdlr#; }'
										),		
										'pagination-text-color' => array(
											'title' => esc_html__('Pagination Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#979797',
											'selector'=> '.totalbusiness-pagination .page-numbers{ color: #gdlr#; }'
										),
										'pagination-background-hover' => array(
											'title' => esc_html__('Pagination Background-hover', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-pagination .page-numbers:hover, ' . 
												'.totalbusiness-pagination .page-numbers.current{ background-color: #gdlr#; }'
										),		
										'pagination-text-color-hover' => array(
											'title' => esc_html__('Pagination Text Color Hover', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-pagination .page-numbers:hover, ' . 
												'.totalbusiness-pagination .page-numbers.current{ color: #gdlr#; }'
										),									
									)
								),
								
								'personnel-testimonial-color' => array(
									'title' => esc_html__('Personnel / Testimonial', 'totalbusiness'),
									'options' => array(
										'personnel-box-background' => array(
											'title' => esc_html__('Personnel Box Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f5f5f5',
											'selector'=> '.totalbusiness-personnel-item .personnel-item-inner{ background-color: #gdlr#; }'
										),	
										'round-personnel-hover-background' => array(
											'title' => esc_html__('Round Personnel Hover Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-personnel-item.round-style .personnel-item{ background-color: #gdlr#; }'
										),										
										'personnel-author-text' => array(
											'title' => esc_html__('Personnel Author Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#3b3b3b',
											'selector'=> '.totalbusiness-personnel-item .personnel-author{ color: #gdlr#; }'
										),			
										'personnel-author-border' => array(
											'title' => esc_html__('Personnel Author Image Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-personnel-item .personnel-author-image{ border-color: #gdlr#; }'
										),		
										'personnel-position-color' => array(
											'title' => esc_html__('Personnel Position Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#acacac',
											'selector'=> '.totalbusiness-personnel-item .personnel-position{ color: #gdlr#; }'
										),		
										'personnel-content-color' => array(
											'title' => esc_html__('Personnel Content Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#959595',
											'selector'=> '.totalbusiness-personnel-item .personnel-content{ color: #gdlr#; }'
										),		
										'personnel-social-icon-color' => array(
											'title' => esc_html__('Personnel Social Icon Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#3b3b3b',
											'selector'=> '.totalbusiness-personnel-item .personnel-social i{ color: #gdlr#; }'
										),			
										'testimonial-box-background' => array(
											'title' => esc_html__('Testimonial Box Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f5f5f5',
											'selector'=> '.totalbusiness-testimonial-item .testimonial-item-inner, ' . 
												'.totalbusiness-testimonial-item .testimonial-author-image{ background-color: #gdlr#; }'
										),			
										'testimonial-content-color' => array(
											'title' => esc_html__('Testimonial Content Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#9b9b9b',
											'selector'=> '.totalbusiness-testimonial-item .testimonial-content{ color: #gdlr#; }'
										),
										'testimonial-author-text' => array(
											'title' => esc_html__('Testimonial Author Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-testimonial-item .testimonial-author{ color: #gdlr#; }'
										),		
										'testimonial-author-position' => array(
											'title' => esc_html__('Testimonial Author Position', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#4d4d4d',
											'selector'=> '.totalbusiness-testimonial-item .testimonial-position{ color: #gdlr#; }'
										),	
										'testimonial-author-image-border' => array(
											'title' => esc_html__('Testimonial Author Image Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-testimonial-item .testimonial-author-image{ border-color: #gdlr#; }'
										),				
										'testimonial-boxed-style-shadow' => array(
											'title' => esc_html__('Testimonial Shadow ( Box Style )', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#dddddd',
											'selector'=> '.totalbusiness-testimonial-item.box-style .testimonial-item-inner:after' . 
												'{ border-top-color: #gdlr#; border-left-color: #gdlr#; }'
										),										
									)
								),

								'slider-input-color' => array(
									'title' => esc_html__('Slider / Gallery / Input', 'totalbusiness'),
									'options' => array(
										'gallery-thumbnail-frame' => array(
											'title' => esc_html__('Gallery ( Thumbnail ) Frame', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#e5e5e5',
											'selector'=> '.totalbusiness-gallery-thumbnail .gallery-item{ background-color: #gdlr#; }'
										),
										'gallery-caption-background' => array(
											'title' => esc_html__('Gallery ( Thumbnail ) Caption Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#000000',
											'selector'=> '.totalbusiness-gallery-thumbnail-container .gallery-caption{ background-color: #gdlr#; }'
										),	
										'gallery-caption-text' => array(
											'title' => esc_html__('Gallery ( Thumbnail ) Caption Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-gallery-thumbnail-container .gallery-caption{ color: #gdlr#; }'
										),
										'slider-bullet-background' => array(
											'title' => esc_html__('Slider Bullet Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#cecece',
											'selector'=> '.nivo-controlNav a, .flex-control-paging li a{ background-color: #gdlr#; }'
										),		
										'slider-bullet-background-hover' => array(
											'title' => esc_html__('Slider Bullet Background Hover', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#949494',
											'selector'=> '.nivo-controlNav a:hover, .nivo-controlNav a.active, ' . 
												'.flex-control-paging li a:hover, .flex-control-paging li a.flex-active { background-color: #gdlr#; }'
										),										
										'slider-bullet-border' => array(
											'title' => esc_html__('Slider Bullet Border Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.nivo-controlNav a, .flex-control-paging li a{ border-color: #gdlr# !important; }'
										),	
										'slider-navigation-background' => array(
											'title' => esc_html__('Slider Navigation Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#000000',
											'selector'=> '.nivo-directionNav a, .flex-direction-nav a, .ls-flawless .ls-nav-prev, ' .
												'.ls-flawless .ls-nav-next{ background-color: #gdlr#; }'
										),
										'slider-navigation-icon' => array(
											'title' => esc_html__('Slider Navigation Icon', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> 'body .nivo-directionNav a, body .flex-direction-nav a, body .flex-direction-nav a:hover, ' .
												'.ls-flawless .ls-nav-prev, .ls-flawless .ls-nav-next{ color: #gdlr#; }'
										),
										'slider-caption-background' => array(
											'title' => esc_html__('Slider Caption Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#000000',
											'selector'=> '.totalbusiness-caption{ background-color: #gdlr#; }'
										),
										'slider-caption-title' => array(
											'title' => esc_html__('Slider Caption Title', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-caption-title{ color: #gdlr#; }'
										),
										'slider-caption-text' => array(
											'title' => esc_html__('Slider Caption Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-caption-text{ color: #gdlr#; }'
										),
										'post-slider-caption-background' => array(
											'title' => esc_html__('Post Slider Caption Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#000000',
											'selector'=> '.totalbusiness-caption-wrapper.post-slider{ background-color: #gdlr#; }'
										),
										'post-slider-caption-title' => array(
											'title' => esc_html__('Post Slider Caption Title', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-caption-wrapper.post-slider .totalbusiness-caption-title{ color: #gdlr#; }'
										),
										'post-slider-caption-text' => array(
											'title' => esc_html__('Post Slider Caption Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#dddddd',
											'selector'=> '.totalbusiness-caption-wrapper.post-slider .totalbusiness-caption-text{ color: #gdlr#; }'
										),
										'post-slider-date-text' => array(
											'title' => esc_html__('Post Slider Date Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.totalbusiness-post-slider-item.style-no-excerpt .totalbusiness-caption-wrapper .totalbusiness-caption-date, ' .
												'.totalbusiness-post-slider-item.style-no-excerpt .totalbusiness-caption-wrapper .totalbusiness-title-link{ color: #gdlr#; }'
										),
										'post-slider-date-background' => array(
											'title' => esc_html__('Post Slider Date Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.totalbusiness-post-slider-item.style-no-excerpt .totalbusiness-caption-wrapper .totalbusiness-caption-date, ' .
												'.totalbusiness-post-slider-item.style-no-excerpt .totalbusiness-caption-wrapper .totalbusiness-title-link{ background-color: #gdlr#; }'
										),		
										'slider-outer-nav-icon' => array(
											'title' => esc_html__('Slider Outer Navigation Icon', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#6d6d6d',
											'selector'=> '.totalbusiness-item-title-wrapper .totalbusiness-flex-prev, .totalbusiness-item-title-wrapper .totalbusiness-flex-next{ color: #gdlr#; }',
											'description'=> esc_html__('The slider navigation that does not be inside the slider area.', 'totalbusiness')
										),										
										'input-box-background' => array(
											'title' => esc_html__('Input Box Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f7f7f7',
											'selector'=> 'input[type="text"], input[type="email"], input[type="password"], textarea{ background-color: #gdlr#; }'
										),
										'input-box-text' => array(
											'title' => esc_html__('Input Box Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#b5b5b5',
											'selector'=> 'input[type="text"], input[type="email"], input[type="password"], textarea{ color: #gdlr#; }' .
												'input::input-placeholder{ color:#gdlr#; } input::-webkit-input-placeholder{ color:#gdlr#; }' .
												'input::-moz-placeholder{ color:#gdlr#; } input:-moz-placeholder{ color:#gdlr#; }' .
												'input:-ms-input-placeholder{ color:#gdlr#; }' .
												'textarea::input-placeholder{ color:#gdlr#; } textarea::-webkit-input-placeholder{ color:#gdlr#; }' .
												'textarea::-moz-placeholder{ color:#gdlr#; } textarea:-moz-placeholder{ color:#gdlr#; }' .
												'textarea:-ms-input-placeholder{ color:#gdlr#; }'
										),
										
									)
								),
								
								'footer-color' => array(
									'title' => esc_html__('Footer', 'totalbusiness'),
									'options' => array(
										'footer-background-color' => array(
											'title' => esc_html__('Footer Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#262626',
											'selector'=> '.footer-wrapper{ background-color: #gdlr#; }'
										),	
										'footer-title-color' => array(
											'title' => esc_html__('Footer Title Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#dddddd',
											'selector'=> '.footer-wrapper .totalbusiness-widget-title, .footer-wrapper .totalbusiness-widget-title a{ color: #gdlr#; }'
										),
										'footer-text-color' => array(
											'title' => esc_html__('Footer Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#bfbfbf',
											'selector'=> '.footer-wrapper{ color: #gdlr#; }'
										),	
										'footer-link-color' => array(
											'title' => esc_html__('Footer Text Link Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#7f7f7f',
											'selector'=> '.footer-wrapper a{ color: #gdlr#; }'
										),
										'footer-link-hover-color' => array(
											'title' => esc_html__('Footer Text Link Hover Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#cecece',
											'selector'=> '.footer-wrapper a:hover{ color: #gdlr#; }'
										),
										'footer-border-color' => array(
											'title' => esc_html__('Footer Border Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#363636',
											'selector'=> '.footer-wrapper *{ border-color: #gdlr#; }'
										),
										'footer-input-box-background' => array(
											'title' => esc_html__('Footer Input Box Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#141414',
											'selector'=> '.footer-wrapper input[type="text"], .footer-wrapper input[type="email"], ' . 
												'.footer-wrapper input[type="password"], .footer-wrapper textarea{ background-color: #gdlr#; }'
										),
										'footer-input-box-text' => array(
											'title' => esc_html__('Footer Input Box Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#828282',
											'selector'=> '.footer-wrapper input[type="text"], .footer-wrapper input[type="email"], ' . 
												'.footer-wrapper input[type="password"], .footer-wrapper textarea{ color: #gdlr#; }'
										),		
										'footer-input-box-border' => array(
											'title' => esc_html__('Footer Input Box Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#313131',
											'selector'=> '.footer-wrapper input[type="text"], .footer-wrapper input[type="email"], ' . 
												'.footer-wrapper input[type="password"], .footer-wrapper textarea{ border-color: #gdlr#; }'
										),		
										'footer-button-text-color' => array(
											'title' => esc_html__('Button Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.footer-wrapper .totalbusiness-button, .footer-wrapper .totalbusiness-button:hover, ' . 
												'.footer-wrapper input[type="button"], .footer-wrapper input[type="submit"]{ color: #gdlr#; }'
										),	
										'footer-button-background-color' => array(
											'title' => esc_html__('Button Background Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.footer-wrapper .totalbusiness-button, .footer-wrapper input[type="button"], ' . 
												'.footer-wrapper input[type="submit"]{ background-color: #gdlr#; }'
										),											
										'footer-tag-cloud-background' => array(
											'title' => esc_html__('Footer Tagcloud Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> '.footer-wrapper .tagcloud a{ background-color: #gdlr#; }'
										),
										'footer-tag-cloud-text' => array(
											'title' => esc_html__('Footer Tagcloud Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> '.footer-wrapper .tagcloud a, .footer-wrapper .tagcloud a:hover{ color: #gdlr#; }'
										),
										'totalbusiness-copyright-background' => array(
											'title' => esc_html__('Copyright Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#0f0f0f',
											'selector'=> '.copyright-wrapper{ background-color: #gdlr#; }'
										),
										'totalbusiness-copyright-text-color' => array(
											'title' => esc_html__('Copyright Text Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#3f3f3f',
											'selector'=> '.copyright-wrapper{ color: #gdlr#; }'
										),
										'totalbusiness-copyright-top-border' => array(
											'title' => esc_html__('Copyright Top Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#0f0f0f',
											'selector'=> '.footer-wrapper .copyright-wrapper{ border-color: #gdlr#; }'
										),
									)	
								),
								
								'woocommerce-color' => array(
									'title' => esc_html__('Woocommerce Color', 'totalbusiness'),
									'options' => array(
										'woo-theme-color' => array(
											'title' => esc_html__('Woocommerce Theme Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'description'=> esc_html__('This color effects following elements : sales tag, stars, table, tab, woo message, price color(pruduct single)', 'totalbusiness'),
											'selector'=> 'html  .woocommerce span.onsale, html  .woocommerce-page span.onsale, html .woocommerce-message,' .
												'html .woocommerce div.product .woocommerce-tabs ul.tabs li.active, html .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,' .
												'html .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, html .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active {  background: #gdlr#; }' .
												'html .woocommerce .star-rating, html .woocommerce-page .star-rating, html .woocommerce .star-rating:before, ' .
												'html .woocommerce-page .star-rating:before, html .woocommerce div.product span.price, html .woocommerce div.product p.price, ' .
												'html .woocommerce #content div.product span.price, html .woocommerce #content div.product p.price, html .woocommerce-page div.product span.price, ' .
												'html .woocommerce-page div.product p.price, html .woocommerce-page #content div.product span.price, html .woocommerce-page #content div.product p.price {color: #gdlr#; }'
										),	
										'woo-text-in-element-color' => array(
											'title' => esc_html__('Woocommerce Text In Element Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'description'=> esc_html__('This color effects following elements : active tab, sale tag, th in table, message, notification, error, active pagination', 'totalbusiness'),
											'selector'=> 'html .woocommerce-message  a.button, html .woocommerce-error  a.button, html .woocommerce-info  a.button, ' .
												'html .woocommerce-message, html .woocommerce-error, html .woocommerce-info, ' .
												'html  .woocommerce span.onsale, html  .woocommerce-page span.onsale, html .woocommerce div.product .woocommerce-tabs ul.tabs li.active,' .
												'html .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, html .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, ' . 
												'html .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active, html .woocommerce nav.woocommerce-pagination ul li span.current, ' . 
												'html .woocommerce-page nav.woocommerce-pagination ul li span.current, html .woocommercenav.woocommerce-pagination ul li a:hover, ' . 
												'html .woocommerce-page nav.woocommerce-pagination ul li a:hover{ color: #gdlr#; }'
										),	
										'woo-notification-background' => array(
											'title' => esc_html__('Woocommerce Notification Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#389EC5',
											'selector'=> 'html .woocommerce-info{ background: #gdlr#; }'
										),
										'woo-error-background' => array(
											'title' => esc_html__('Woocommerce Error Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#C23030',
											'selector'=> 'html .woocommerce-error{ background: #gdlr#; }'
										),
										'woo-button-background' => array(
											'title' => esc_html__('Woocommerce Button Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#36bddb',
											'selector'=> 'html .woocommerce a.button.alt:hover, html .woocommerce button.button.alt:hover, html .woocommerce input.button.alt:hover, ' .
												'html .woocommerce #respond input#submit.alt:hover, html .woocommerce #content input.button.alt:hover, html .woocommerce-page a.button.alt:hover, ' .
												'html .woocommerce-page button.button.alt:hover, html .woocommerce-page input.button.alt:hover, html .woocommerce-page #respond input#submit.alt:hover, ' . 
												'html .woocommerce-page #content input.button.alt:hover, html .woocommerce a.button.alt, html .woocommerce button.button.alt, html .woocommerce input.button.alt, ' . 
												'html .woocommerce #respond input#submit.alt, html .woocommerce #content input.button.alt, html .woocommerce-page a.button.alt, html .woocommerce-page button.button.alt, ' . 
												'html .woocommerce-page input.button.alt, html .woocommerce-page #respond input#submit.alt, html .woocommerce-page #content input.button.alt, ' .
												'html .woocommerce a.button, html .woocommerce button.button, html .woocommerce input.button, html .woocommerce #respond input#submit, ' .
												'html .woocommerce #content input.button, html .woocommerce-page a.button, html .woocommerce-page button.button, html .woocommerce-page input.button, ' .
												'html .woocommerce-page #respond input#submit, html .woocommerce-page #content input.button, html .woocommerce a.button:hover, html .woocommerce button.button:hover, ' .
												'html .woocommerce input.button:hover, html .woocommerce #respond input#submit:hover, html .woocommerce #content input.button:hover, ' .
												'html .woocommerce-page a.button:hover, html .woocommerce-page button.button:hover, html .woocommerce-page input.button:hover, ' .
												'html .woocommerce-page #respond input#submit:hover, html .woocommerce-page #content input.button:hover, html .woocommerce ul.products li.product a.loading, ' .
												'html .woocommerce div.product form.cart .button, html .woocommerce #content div.product form.cart .button, html .woocommerce-page div.product form.cart .button, ' .
												'html .woocommerce-page #content div.product form.cart .button{ background: #gdlr#; }'
										),
										'woo-button-text' => array(
											'title' => esc_html__('Woocommerce Button Text', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> 'html .woocommerce a.button.alt:hover, html .woocommerce button.button.alt:hover, html .woocommerce input.button.alt:hover, ' . 
												'html .woocommerce #respond input#submit.alt:hover, html .woocommerce #content input.button.alt:hover, html .woocommerce-page a.button.alt:hover, ' .  
												'html .woocommerce-page button.button.alt:hover, html .woocommerce-page input.button.alt:hover, html .woocommerce-page #respond input#submit.alt:hover, ' .  
												'html .woocommerce-page #content input.button.alt:hover, html .woocommerce a.button.alt, html .woocommerce button.button.alt, html .woocommerce input.button.alt, ' .  
												'html .woocommerce #respond input#submit.alt, html .woocommerce #content input.button.alt, html .woocommerce-page a.button.alt, html .woocommerce-page button.button.alt, ' . 
												'html .woocommerce-page input.button.alt, html .woocommerce-page #respond input#submit.alt, html .woocommerce-page #content input.button.alt, ' . 
												'html .woocommerce a.button, html .woocommerce button.button, html .woocommerce input.button, html .woocommerce #respond input#submit, ' .  
												'html .woocommerce #content input.button, html .woocommerce-page a.button, html .woocommerce-page button.button, html .woocommerce-page input.button, ' . 
												'html .woocommerce-page #respond input#submit, html .woocommerce-page #content input.button, html .woocommerce a.button:hover, html .woocommerce button.button:hover, ' . 
												'html .woocommerce input.button:hover, html .woocommerce #respond input#submit:hover, html .woocommerce #content input.button:hover, ' . 
												'html .woocommerce-page a.button:hover, html .woocommerce-page button.button:hover, html .woocommerce-page input.button:hover, ' . 
												'html .woocommerce-page #respond input#submit:hover, html .woocommerce-page #content input.button:hover, html .woocommerce ul.products li.product a.loading, ' . 
												'html .woocommerce div.product form.cart .button, html .woocommerce #content div.product form.cart .button, html .woocommerce-page div.product form.cart .button, ' . 
												'html .woocommerce-page #content div.product form.cart .button{ color: #gdlr#; }'
										),
										'woo-button-bottom-border' => array(
											'title' => esc_html__('Woocommerce Button Bottom Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#20a9c1',
											'selector'=> 'html .woocommerce a.button.alt:hover, html .woocommerce button.button.alt:hover, html .woocommerce input.button.alt:hover, ' .
												'html .woocommerce #respond input#submit.alt:hover, html .woocommerce #content input.button.alt:hover, html .woocommerce-page a.button.alt:hover, ' .
												'html .woocommerce-page button.button.alt:hover, html .woocommerce-page input.button.alt:hover, html .woocommerce-page #respond input#submit.alt:hover, ' .
												'html .woocommerce-page #content input.button.alt:hover, html .woocommerce a.button.alt, html .woocommerce button.button.alt, html .woocommerce input.button.alt, ' .
												'html .woocommerce #respond input#submit.alt, html .woocommerce #content input.button.alt, html .woocommerce-page a.button.alt, html .woocommerce-page button.button.alt, ' .
												'html .woocommerce-page input.button.alt, html .woocommerce-page #respond input#submit.alt, html .woocommerce-page #content input.button.alt, ' .
												'html .woocommerce a.button, html .woocommerce button.button, html .woocommerce input.button, html .woocommerce #respond input#submit, ' .
												'html .woocommerce #content input.button, html .woocommerce-page a.button, html .woocommerce-page button.button, html .woocommerce-page input.button, ' .
												'html .woocommerce-page #respond input#submit, html .woocommerce-page #content input.button, html .woocommerce a.button:hover, html .woocommerce button.button:hover, ' .
												'html .woocommerce input.button:hover, html .woocommerce #respond input#submit:hover, html .woocommerce #content input.button:hover, ' .
												'html .woocommerce-page a.button:hover, html .woocommerce-page button.button:hover, html .woocommerce-page input.button:hover, ' .
												'html .woocommerce-page #respond input#submit:hover, html .woocommerce-page #content input.button:hover, html .woocommerce ul.products li.product a.loading, ' .
												'html .woocommerce div.product form.cart .button, html .woocommerce #content div.product form.cart .button, html .woocommerce-page div.product form.cart .button, ' .
												'html .woocommerce-page #content div.product form.cart .button{ border-bottom: 3px solid #gdlr#; }'
										),
										'woo-border-color' => array(
											'title' => esc_html__('Woocommerce Border Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ebebeb',
											'selector'=> 'html .woocommerce #reviews #comments ol.commentlist li img.avatar, html .woocommerce-page #reviews #comments ol.commentlist li img.avatar { background: #gdlr#; }' . 
												'html .woocommerce #reviews #comments ol.commentlist li img.avatar, html .woocommerce-page #reviews #comments ol.commentlist li img.avatar,' . 
												'html .woocommerce #reviews #comments ol.commentlist li .comment-text, html .woocommerce-page #reviews #comments ol.commentlist li .comment-text,' . 
												'html .woocommerce ul.products li.product a img, html .woocommerce-page ul.products li.product a img, html .woocommerce ul.products li.product a img:hover ,' .  
												'html .woocommerce-page ul.products li.product a img:hover, html .woocommerce-page div.product div.images img, html .woocommerce-page #content div.product div.images img,' . 
												'html .woocommerce form.login, html .woocommerce form.checkout_coupon, html .woocommerce form.register, html .woocommerce-page form.login,' .  
												'html .woocommerce-page form.checkout_coupon, html .woocommerce-page form.register, html .woocommerce table.cart td.actions .coupon .input-text,' .  
												'html .woocommerce #content table.cart td.actions .coupon .input-text, html .woocommerce-page table.cart td.actions .coupon .input-text,' .  
												'html .woocommerce-page #content table.cart td.actions .coupon .input-text { border: 1px solid #gdlr#; }' . 
												'html .woocommerce div.product .woocommerce-tabs ul.tabs:before, html .woocommerce #content div.product .woocommerce-tabs ul.tabs:before,' .  
												'html .woocommerce-page div.product .woocommerce-tabs ul.tabs:before, html .woocommerce-page #content div.product .woocommerce-tabs ul.tabs:before,' . 
												'html .woocommerce table.shop_table tfoot td, html .woocommerce table.shop_table tfoot th, html .woocommerce-page table.shop_table tfoot td,' .  
												'html .woocommerce-page table.shop_table tfoot th, html .woocommerce table.shop_table tfoot td, html .woocommerce table.shop_table tfoot th,' .  
												'html .woocommerce-page table.shop_table tfoot td, html .woocommerce-page table.shop_table tfoot th { border-bottom: 1px solid #gdlr#; }' . 
												'html .woocommerce .cart-collaterals .cart_totals table tr:first-child th, html .woocommerce .cart-collaterals .cart_totals table tr:first-child td,' .  
												'html .woocommerce-page .cart-collaterals .cart_totals table tr:first-child th, html .woocommerce-page .cart-collaterals .cart_totals table tr:first-child td { border-top: 3px #gdlr# solid; }' . 
												'html .woocommerce .cart-collaterals .cart_totals tr td, html .woocommerce .cart-collaterals .cart_totals tr th,' .  
												'html .woocommerce-page .cart-collaterals .cart_totals tr td, html .woocommerce-page .cart-collaterals .cart_totals tr th { border-bottom: 2px solid #gdlr#; }'
										),
										'woo-secondary-elements' => array(
											'title' => esc_html__('Woocommerce Secondary Element', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#f3f3f3',
											'description'=> esc_html__('This color effects following elements : inactive tab, input, textarea,romving product button, payment option box, inactive pagination', 'totalbusiness'),
											'selector'=> 'html .woocommerce div.product .woocommerce-tabs ul.tabs li, html .woocommerce #content div.product .woocommerce-tabs ul.tabs li, ' . 
												'html .woocommerce-page div.product .woocommerce-tabs ul.tabs li, html .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li ,' . 
												'html .woocommerce table.cart a.remove, html .woocommerce #content table.cart a.remove, html .woocommerce-page table.cart a.remove, ' . 
												'html .woocommerce-page #content table.cart a.remove, html .woocommerce #payment, html .woocommerce-page #payment, html .woocommerce .customer_details,' . 
												'html .woocommerce ul.order_details, html .woocommerce nav.woocommerce-pagination ul li a, html .woocommerce-page nav.woocommerce-pagination ul li a,' . 
												'html .woocommerce form .form-row input.input-text, html .woocommerce form .form-row textarea, html .woocommerce-page form .form-row input.input-text, ' . 
												'html .woocommerce-page form .form-row textarea, html .woocommerce .quantity input.qty, html .woocommerce #content .quantity input.qty, ' . 
												'html .woocommerce-page .quantity input.qty, html .woocommerce-page #content .quantity input.qty,' . 
												'html .woocommerce .widget_shopping_cart .total, html .woocommerce-page .widget_shopping_cart .total { background: #gdlr#; }' . 
												'html .woocommerce .quantity input.qty, html .woocommerce #content .quantity input.qty, html .woocommerce-page .quantity input.qty, ' . 
												'html .woocommerce-page #content .quantity input.qty { border: 1px solid #gdlr#; }'
										),	
										'woo-secondary-elements-border' => array(
											'title' => esc_html__('Woocommerce Secondary Element Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#e5e5e5',
											'selector'=> 'html .woocommerce .widget_shopping_cart .total, html .woocommerce-page .widget_shopping_cart .total { border-top: 2px solid #gdlr#; }' .
												'html .woocommerce table.cart a.remove:hover, html .woocommerce #content table.cart a.remove:hover, html .woocommerce-page table.cart a.remove:hover,' . 
												'html .woocommerce-page #content table.cart a.remove:hover, html #payment div.payment_box, html .woocommerce-page #payment div.payment_box { background: #gdlr#; }'
										),
										'woo-cart-summary-price' => array(
											'title' => esc_html__('Woocommerce Cart Summary Text / Price', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#333333',
											'selector'=> 'html .woocommerce table.shop_table tfoot td, html .woocommerce table.shop_table tfoot th, html .woocommerce-page table.shop_table tfoot td,' .
												'html .woocommerce-page table.shop_table tfoot th, .cart-subtotal th, .shipping th , .total th, html .woocommerce table.shop_attributes .alt th,' .
												'html .woocommerce-page table.shop_attributes .alt th, html .woocommerce ul.products li.product .price, html.woocommerce-page ul.products li.product .price { color: #gdlr#; }'
										),
										'woo-discount-price' => array(
											'title' => esc_html__('Discount Price / Product Arrow', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#888888',
											'selector'=> 'html .woocommerce ul.products li.product .price del, html .woocommerce-page ul.products li.product .price del,' .
												'html .woocommerce table.cart a.remove, html .woocommerce #content table.cart a.remove, html .woocommerce-page table.cart a.remove,' .
												'html .woocommerce-page #content table.cart a.remove { color: #gdlr#; }'
										),
										'woo-plus-minus-product-border' => array(
											'title' => esc_html__('Plus / Minus Product Border', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#a0a0a0',
											'selector'=> 'html .woocommerce .quantity .plus, html .woocommerce .quantity .minus, html .woocommerce #content .quantity .plus, html .woocommerce #content .quantity .minus, 
												html .woocommerce-page .quantity .plus, html .woocommerce-page .quantity .minus, html .woocommerce-page #content .quantity .plus, 
												html .woocommerce-page #content .quantity .minus { border: 1px solid #gdlr#; }'
										),
										'woo-plus-minus-product-sign' => array(
											'title' => esc_html__('Plus / Minus Product Sign', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#ffffff',
											'selector'=> 'html .woocommerce .quantity .plus, html .woocommerce .quantity .minus, html .woocommerce #content .quantity .plus, html .woocommerce #content .quantity .minus, 
												html .woocommerce-page .quantity .plus, html .woocommerce-page .quantity .minus, html .woocommerce-page #content .quantity .plus, 
												html .woocommerce-page #content .quantity .minus { color: #gdlr#; }'
										),
										'woo-plus-product-background' => array(
											'title' => esc_html__('Plus Product Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#9a9a9a',
											'selector'=> 'html .woocommerce .quantity .plus, html .woocommerce #content .quantity .plus,  html .woocommerce-page .quantity .plus,' .
												'html .woocommerce-page #content .quantity .plus, html .woocommerce .quantity .plus:hover, html .woocommerce #content .quantity .plus:hover,' .
												'html .woocommerce-page .quantity .plus:hover,  html .woocommerce-page #content .quantity .plus:hover{ background: #gdlr#; }'
										),
										'woo-minus-product-background' => array(
											'title' => esc_html__('Minus Product Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#b6b6b6',
											'selector'=> 'html .woocommerce .quantity .minus, html .woocommerce #content .quantity .minus,  html .woocommerce-page .quantity .minus,' .  
												'html .woocommerce-page #content .quantity .minus, html .woocommerce .quantity .minus:hover, html .woocommerce #content .quantity .minus:hover,' . 
												'html .woocommerce-page .quantity .minus:hover,  html .woocommerce-page #content .quantity .minus:hover{ background: #gdlr#; }'
										),
									)
								)
							
							)					
						),
						
						// plugin setting menu
						'plugin-settings' => array(
							'title' => esc_html__('Plugin / Slider', 'totalbusiness'),
							'icon' => get_template_directory_uri() . '/include/images/icon-plugin-settings.png',
							'options' => array(
								'general-plugins' => array(
									'title' => esc_html__('General Plugins', 'totalbusiness'),
									'options' => array(			
										'enable-plugin-recommendation' => array(
											'title' => esc_html__('Enable Plugin Recommendation', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable'
										),
										'enable-goodlayers-navigation' => array(
											'title' => esc_html__('Enable Goodlayers Navigation', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable',
											'description' => '<strong>*** ' . esc_html__('Do not turn this off, it can breaks both top and main menu.', 'totalbusiness') . '</strong>'
										),
										'enable-goodlayers-mobile-navigation' => array(
											'title' => esc_html__('Enable Mobile Navigation', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable',
											'description' => esc_html__('Turn this menu off when you use 3rd party menu plugin like Uber Menu', 'totalbusiness')
										),
										'enable-elegant-font' => array(
											'title' => esc_html__('Enable Elegant Font', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable',
										),
										'enable-flex-slider' => array(
											'title' => esc_html__('Enable Flex Slider', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable',
											'description' => '<strong>*** ' . esc_html__('Turn this option off will make slider shortcode / post slider widget unavailable', 'totalbusiness') . '</strong>'
										),		
										'enable-fancybox' => array(
											'title' => esc_html__('Enable Fancybox', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable',
											'description' => '<strong>*** ' . esc_html__('Turn this option off can make all lightbox option breaks', 'totalbusiness') . '</strong>'
										),		
										'enable-fancybox-thumbs' => array(
											'title' => esc_html__('Enable Fancybox Thumbnail ( Gallery Mode )', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable'
										),																			
									)
								),
								'flex-slider' => array(
									'title' => esc_html__('Flex Slider', 'totalbusiness'),
									'options' => array(		
										'flex-slider-effects' => array(
											'title' => esc_html__('Flex Slider Effect', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'fade' => esc_html__('Fade', 'totalbusiness'),
												'slide'	=> esc_html__('Slide', 'totalbusiness')
											)
										),
										'flex-pause-time' => array(
											'title' => esc_html__('Flex Slider Pause Time', 'totalbusiness'),
											'type' => 'text',
											'default' => '7000'
										),
										'flex-slide-speed' => array(
											'title' => esc_html__('Flex Slider Animation Speed', 'totalbusiness'),
											'type' => 'text',
											'default' => '600'
										),	
									)
								),
								'nivo-slider' => array(
									'title' => esc_html__('Nivo Slider', 'totalbusiness'),
									'options' => array(		
										'nivo-slider-effects' => array(
											'title' => esc_html__('Nivo Slider Effect', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'sliceDownRight'	=> esc_html__('sliceDownRight', 'totalbusiness'),
												'sliceDownLeft'		=> esc_html__('sliceDownLeft', 'totalbusiness'),
												'sliceUpRight'		=> esc_html__('sliceUpRight', 'totalbusiness'),
												'sliceUpLeft'		=> esc_html__('sliceUpLeft', 'totalbusiness'),
												'sliceUpDown'		=> esc_html__('sliceUpDown', 'totalbusiness'),
												'sliceUpDownLeft'	=> esc_html__('sliceUpDownLeft', 'totalbusiness'),
												'fold'				=> esc_html__('fold', 'totalbusiness'),
												'fade'				=> esc_html__('fade', 'totalbusiness'),
												'boxRandom'			=> esc_html__('boxRandom', 'totalbusiness'),
												'boxRain'			=> esc_html__('boxRain', 'totalbusiness'),
												'boxRainReverse'	=> esc_html__('boxRainReverse', 'totalbusiness'),
												'boxRainGrow'		=> esc_html__('boxRainGrow', 'totalbusiness'),
												'boxRainGrowReverse'=> esc_html__('boxRainGrowReverse', 'totalbusiness')
											)
										),
										'nivo-pause-time' => array(
											'title' => esc_html__('Nivo Slider Pause Time', 'totalbusiness'),
											'type' => 'text',
											'default' => '7000'
										),
										'nivo-slide-speed' => array(
											'title' => esc_html__('Nivo Slider Animation Speed', 'totalbusiness'),
											'type' => 'text',
											'default' => '600'
										),	
									)
								),
							)					
						),
						
					)
				), 
				
				$theme_option
			);
			
		}
		
	}

?>