<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the admin option setting 
	*	---------------------------------------------------------------------
	*/
	
	// add ability to search through page builder
	add_filter( 'posts_where', 'totalbusiness_search_page_builder_meta');
	if( !function_exists('totalbusiness_search_page_builder_meta') ){
		function totalbusiness_search_page_builder_meta( $where ) {
			if( is_search() && empty($_GET['post_type']) && !is_admin() ) {
				global $wpdb;
				$query = get_search_query();
				$query = $wpdb->esc_like( $query );

				$where .= " OR {$wpdb->posts}.ID IN (";
				$where .= "SELECT {$wpdb->postmeta}.post_id ";
				$where .= "FROM {$wpdb->posts}, {$wpdb->postmeta} ";
				$where .= "WHERE {$wpdb->posts}.post_type = 'page' ";
				$where .= "AND {$wpdb->posts}.post_status = 'publish' ";
				$where .= "AND {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ";
				$where .= "AND {$wpdb->postmeta}.meta_key IN('above-sidebar', 'content-with-sidebar', 'below-sidebar') ";
				$where .= "AND {$wpdb->postmeta}.meta_value LIKE '%$query%' )";
			}
			return $where;
		}
	}
	
	// returns array of options for title of each item
	if( !function_exists('totalbusiness_page_builder_title_option') ){
		function totalbusiness_page_builder_title_option( $read_more = false ) {
			$title = array(
				'title-type'=> array(	
					'title'=> esc_html__('Title Type' ,'totalbusiness'),
					'type'=> 'combobox',
					'options'=> array(
						'none'=> esc_html__('None' ,'totalbusiness'),
						'left'=> esc_html__('Left Align' ,'totalbusiness'),
						'center'=> esc_html__('Center Align' ,'totalbusiness'),
					)
				),
				'title-size'=> array(	
					'title'=> esc_html__('Title Size' ,'totalbusiness'),
					'type'=> 'combobox',
					'options'=> array(
						'small'=> esc_html__('Small' ,'totalbusiness'),
						'medium'=> esc_html__('Medium' ,'totalbusiness'),
						'large'=> esc_html__('Large' ,'totalbusiness'),
						'extra-large'=> esc_html__('Extra Large' ,'totalbusiness'),
					),
					'default'=>'medium',
					'wrapper-class'=>'title-type-wrapper left-wrapper center-wrapper'
				),										
				'title'=> array(	
					'title'=> esc_html__('Title' ,'totalbusiness'),
					'type'=> 'text',
					'wrapper-class'=>'title-type-wrapper left-wrapper center-wrapper'
				),			
				'caption'=> array(	
					'title'=> esc_html__('Caption' ,'totalbusiness'),
					'type'=> 'textarea',
					'wrapper-class'=>'title-type-wrapper left-wrapper center-wrapper'
				)			
			);
			
			if( $read_more ){
				$title['right-text'] = array(	
					'title'=> esc_html__('Titlte Link Text' ,'totalbusiness'),
					'type'=> 'text',
					'default'=> esc_html__('Read All News', 'totalbusiness'),
					'wrapper-class'=>'title-type-wrapper left-wrapper center-wrapper left-divider-wrapper center-divider-wrapper'
				);	
				$title['right-text-link'] = array(	
					'title'=> esc_html__('Title Link URL' ,'totalbusiness'),
					'type'=> 'text',
					'wrapper-class'=>'title-type-wrapper left-wrapper center-wrapper left-divider-wrapper center-divider-wrapper'
				);			
			}
			
			return $title;
		}
	}

	// create the page builder
	if( is_admin() ){ add_action('init', 'totalbusiness_create_page_builder_option'); }
	if( !function_exists('totalbusiness_create_page_builder_option') ){
	
		function totalbusiness_create_page_builder_option(){
			global $totalbusiness_spaces;
		
			new totalbusiness_page_builder( 
				
				// page builder option attribute
				array(
					'post_type' => array('page'),
					'meta_title' => esc_html__('Page Builder Options', 'totalbusiness'),
				),
					  
				// page builder option setting
				apply_filters('totalbusiness_page_builder_option',
					array(
						'column-wrapper-item' => array(
							'title' => esc_html__('Column Wrapper Item', 'totalbusiness'),
							'blank_option' => esc_html__('- Select Column Item -', 'totalbusiness'),
							'options' => array(
								'column1-5' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'1/5'), 
								'column1-4' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'1/4'), 
								'column2-5' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'2/5'), 
								'column1-3' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'1/3'), 
								'column1-2' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'1/2'), 
								'column3-5' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'3/5'), 
								'column2-3' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'2/3'), 
								'column3-4' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'3/4'), 
								'column4-5' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'4/5'), 
								'column1-1' => array('title'=> esc_html__('Column Item', 'totalbusiness'), 'type'=>'wrapper', 'size'=>'1/1'),
								
								'color-wrapper' => array(
									'title'=> esc_html__('Color Wrapper', 'totalbusiness'), 
									'type'=>'wrapper',
									'options'=>array(
										'background-type' => array(
											'title' => esc_html__('Background Type', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'color'=>esc_html__('Color', 'totalbusiness'),
												'transparent'=>esc_html__('Transparent', 'totalbusiness'),
											)
										),
										'background' => array(
											'title' => esc_html__('Background Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default'=> '#ffffff',
											'wrapper-class'=>'color-wrapper background-type-wrapper'
										),		
										'skin' => array(
											'title' => esc_html__('Skin', 'totalbusiness'),
											'type' => 'combobox',
											'options' => totalbusiness_get_skin_list(),
											'description' => esc_html__('Can be created at the Theme Options > Elements Color > Custom Skin section', 'totalbusiness')
										),
										'show-section'=> array(
											'title' => esc_html__('Show This Section In', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'gdlr-show-all' => esc_html__('All Devices', 'totalbusiness'),
												'totalbusiness-hide-in-tablet' => esc_html__('Hide This Section In Tablet', 'totalbusiness'),
												'totalbusiness-hide-in-mobile' => esc_html__('Hide This Section In Mobile', 'totalbusiness'),
												'totalbusiness-hide-in-tablet-mobile' => esc_html__('Hide This Section In Tablet and Mobile', 'totalbusiness'),
											),
										),										
										'border'=> array(
											'title' => esc_html__('Border', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'none' => esc_html__('None', 'totalbusiness'),
												'top' => esc_html__('Border Top', 'totalbusiness'),
												'bottom' => esc_html__('Border Bottom', 'totalbusiness'),
												'both' => esc_html__('Both Border', 'totalbusiness'),
											),
										),
										'border-top-color' => array(
											'title' => esc_html__('Border Top Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper top-wrapper both-wrapper'
										),
										'border-bottom-color' => array(
											'title' => esc_html__('Border Bottom Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper bottom-wrapper both-wrapper'
										),
										'padding-top' => array(
											'title' => esc_html__('Padding Top', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['top-wrapper'],
											'description' => esc_html__('Spaces before starting any content in this section', 'totalbusiness')
										),	
										'padding-bottom' => array(
											'title' => esc_html__('Padding Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-wrapper'],
											'description' => esc_html__('Spaces after ending of the content in this section', 'totalbusiness')
										),
									)
								),
								
								'parallax-bg-wrapper' => array(
									'title'=> esc_html__('Background/Parallax Wrapper', 'totalbusiness'), 
									'type'=>'wrapper',
									'options'=>array(
										'type' => array(
											'title' => esc_html__('Type', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'image'=> esc_html__('Background Image', 'totalbusiness'),
												'pattern'=> esc_html__('Predefined Pattern', 'totalbusiness'),
												'video'=> esc_html__('Video Background', 'totalbusiness'),
											),
											'default'=>'image'
										),								
										'background' => array(
											'title' => esc_html__('Background Image', 'totalbusiness'),
											'button' => esc_html__('Upload', 'totalbusiness'),
											'type' => 'upload',
											'wrapper-class' => 'type-wrapper image-wrapper'
										),								
										'background-mobile' => array(
											'title' => esc_html__('Background Mobile', 'totalbusiness'),
											'button' => esc_html__('Upload', 'totalbusiness'),
											'type' => 'upload',
											'wrapper-class' => 'type-wrapper image-wrapper'
										),	
										'background-speed' => array(
											'title' => esc_html__('Background Speed', 'totalbusiness'),
											'type' => 'text',
											'default' => '0',
											'wrapper-class' => 'type-wrapper image-wrapper',
											'description' => esc_html__('Fill 0 if you don\'t want the background to scroll and 1 when you want the background to have the same speed as the scroll bar', 'totalbusiness') .
												'<br><br><strong>' . esc_html__('*** only allow the number between -1 to 1', 'totalbusiness') . '</strong>'
										),		
										'pattern' => array(
											'title' => esc_html__('Pattern', 'totalbusiness'),
											'type' => 'radioimage',
											'options' => array(
												'1'=>get_template_directory_uri() . '/include/images/pattern/pattern-1.png',
												'2'=>get_template_directory_uri() . '/include/images/pattern/pattern-2.png', 
												'3'=>get_template_directory_uri() . '/include/images/pattern/pattern-3.png',
												'4'=>get_template_directory_uri() . '/include/images/pattern/pattern-4.png',
												'5'=>get_template_directory_uri() . '/include/images/pattern/pattern-5.png',
												'6'=>get_template_directory_uri() . '/include/images/pattern/pattern-6.png',
												'7'=>get_template_directory_uri() . '/include/images/pattern/pattern-7.png',
												'8'=>get_template_directory_uri() . '/include/images/pattern/pattern-8.png'
											),
											'wrapper-class' => 'type-wrapper pattern-wrapper',
											'default' => '1'
										),		
										'video' => array(
											'title' => esc_html__('Youtube URL', 'totalbusiness'),
											'type' => 'text',
											'wrapper-class' => 'type-wrapper video-wrapper'
										),
										'video-overlay' => array(
											'title' => esc_html__('Video Overlay Opacity', 'totalbusiness'),
											'type' => 'text',
											'default' => '0.5',
											'wrapper-class' => 'type-wrapper video-wrapper'
										),
										'video-player' => array(
											'title' => esc_html__('Video Control Bar', 'totalbusiness'),
											'type' => 'checkbox',
											'default' => 'enable',
											'wrapper-class' => 'type-wrapper video-wrapper'
										),
										'skin' => array(
											'title' => esc_html__('Skin', 'totalbusiness'),
											'type' => 'combobox',
											'options' => totalbusiness_get_skin_list(),
											'description' => esc_html__('Can be created at the Theme Options > Elements Color > Custom Skin section', 'totalbusiness')
										),
										'show-section'=> array(
											'title' => esc_html__('Show This Section In', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'gdlr-show-all' => esc_html__('All Devices', 'totalbusiness'),
												'totalbusiness-hide-in-tablet' => esc_html__('Hide This Section In Tablet', 'totalbusiness'),
												'totalbusiness-hide-in-mobile' => esc_html__('Hide This Section In Mobile', 'totalbusiness'),
												'totalbusiness-hide-in-tablet-mobile' => esc_html__('Hide This Section In Tablet and Mobile', 'totalbusiness'),
											),
										),										
										'border'=> array(
											'title' => esc_html__('Border', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'none' => esc_html__('None', 'totalbusiness'),
												'top' => esc_html__('Border Top', 'totalbusiness'),
												'bottom' => esc_html__('Border Bottom', 'totalbusiness'),
												'both' => esc_html__('Both Border', 'totalbusiness'),
											),
										),
										'border-top-color' => array(
											'title' => esc_html__('Border Top Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper top-wrapper both-wrapper'
										),
										'border-bottom-color' => array(
											'title' => esc_html__('Border Bottom Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper bottom-wrapper both-wrapper'
										),	
										'padding-top' => array(
											'title' => esc_html__('Padding Top', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['top-wrapper'],
											'description' => esc_html__('Spaces before starting any content in this section', 'totalbusiness')
										),	
										'padding-bottom' => array(
											'title' => esc_html__('Padding Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-wrapper'],
											'description' => esc_html__('Spaces after ending of the content in this section', 'totalbusiness')
										),
									)
								),
								
								'full-size-wrapper' => array(
									'title'=> esc_html__('Full Size Wrapper', 'totalbusiness'), 
									'type'=>'wrapper',
									'options'=>array(
										'skin' => array(
											'title' => esc_html__('Skin', 'totalbusiness'),
											'type' => 'combobox',
											'options' => totalbusiness_get_skin_list(),
											'description' => esc_html__('Can be created at the Theme Options > Elements Color > Custom Skin section', 'totalbusiness')
										),
										'background' => array(
											'title' => esc_html__('Background Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default'=> '#ffffff'
										),
										'show-section'=> array(
											'title' => esc_html__('Show This Section In', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'gdlr-show-all' => esc_html__('All Devices', 'totalbusiness'),
												'totalbusiness-hide-in-tablet' => esc_html__('Hide This Section In Tablet', 'totalbusiness'),
												'totalbusiness-hide-in-mobile' => esc_html__('Hide This Section In Mobile', 'totalbusiness'),
												'totalbusiness-hide-in-tablet-mobile' => esc_html__('Hide This Section In Tablet and Mobile', 'totalbusiness'),
											),
										),	
										'border'=> array(
											'title' => esc_html__('Border', 'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'none' => esc_html__('None', 'totalbusiness'),
												'top' => esc_html__('Border Top', 'totalbusiness'),
												'bottom' => esc_html__('Border Bottom', 'totalbusiness'),
												'both' => esc_html__('Both Border', 'totalbusiness'),
											),
										),
										'border-top-color' => array(
											'title' => esc_html__('Border Top Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper top-wrapper both-wrapper'
										),
										'border-bottom-color' => array(
											'title' => esc_html__('Border Bottom Color', 'totalbusiness'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper bottom-wrapper both-wrapper'
										),
										'padding-top' => array(
											'title' => esc_html__('Padding Top', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['top-full-wrapper'],
											'description' => esc_html__('Spaces before starting any content in this section', 'totalbusiness')
										),	
										'padding-bottom' => array(
											'title' => esc_html__('Padding Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-wrapper'],
											'description' => esc_html__('Spaces after ending of the content in this section', 'totalbusiness')
										),
									)
								)
							)
						),
						
						'content-item' => array(
							'title' => esc_html__('Content/Post Type Item', 'totalbusiness'),
							'blank_option' => esc_html__('- Select Content Item -', 'totalbusiness'),
							'options' => array(

								'about-us' => array(
									'title'=> esc_html__('About Us', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'style'=> array(
											'title'=> esc_html__('Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'plain'=> esc_html__('Plain' ,'totalbusiness'),	
												'plain-large'=> esc_html__('Plain Large' ,'totalbusiness'),	
												'title-left'=> esc_html__('Title Left' ,'totalbusiness'),	
												'normal'=> esc_html__('Caption Below Title' ,'totalbusiness'),	
											)
										),	
										'caption'=> array(
											'title'=> esc_html__('Caption' ,'totalbusiness'),
											'type'=> 'text',						
										),	
										'title'=> array(
											'title'=> esc_html__('Title' ,'totalbusiness'),
											'type'=> 'text',						
										),										
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'totalbusiness'),
											'type'=> 'tinymce',						
										),		
										'read-more-text'=> array(
											'title'=> esc_html__('Read More Text' ,'totalbusiness'),
											'type'=> 'text',					
											'default'=> esc_html__('Read More' ,'totalbusiness')
										),		
										'read-more-link'=> array(
											'title'=> esc_html__('Read More Link' ,'totalbusiness'),
											'type'=> 'text',						
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	 
									)
								),	
								
								'accordion' => array(
									'title'=> esc_html__('Accordion', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'accordion'=> array(
											'type'=> 'tab',
											'default-title'=> esc_html__('Accordion' ,'totalbusiness')											
										)
									), totalbusiness_page_builder_title_option(), array(
										'initial-state'=> array(
											'title'=> esc_html__('Initial Open', 'totalbusiness'),
											'type'=> 'text',
											'default'=> 1,
											'description'=> esc_html__('0 will close all tab as an initial state, 1 will open the first tab and so on.', 'totalbusiness')						
										),		
										'style'=> array(
											'title'=> esc_html__('Accordion Style' ,'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'style-1' => esc_html__('Style 1 ( Colored Background )', 'totalbusiness'),
												'style-2' => esc_html__('Style 2 ( Transparent Background )', 'totalbusiness')
											)
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),
									))
								), 					
								
								'blog' => array(
									'title'=> esc_html__('Blog', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(totalbusiness_page_builder_title_option(true), array(										
										'category'=> array(
											'title'=> esc_html__('Category' ,'totalbusiness'),
											'type'=> 'multi-combobox',
											'options'=> totalbusiness_get_term_list('category'),
											'description'=> esc_html__('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'totalbusiness')
										),	
										'tag'=> array(
											'title'=> esc_html__('Tag' ,'totalbusiness'),
											'type'=> 'multi-combobox',
											'options'=> totalbusiness_get_term_list('post_tag'),
											'description'=> esc_html__('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'totalbusiness')
										),	
										'num-excerpt'=> array(
											'title'=> esc_html__('Num Excerpt (Word)' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> '25',
											'description'=> esc_html__('This is a number of word (decided by spaces) that you want to show on the post excerpt. <strong>Use 0 to hide the excerpt, -1 to show full posts and use the wordpress more tag</strong>.', 'totalbusiness')
										),	
										'num-fetch'=> array(
											'title'=> esc_html__('Num Fetch' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> '8',
											'description'=> esc_html__('Specify the number of posts you want to pull out.', 'totalbusiness')
										),										
										'blog-style'=> array(
											'title'=> esc_html__('Blog Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'blog-widget-1-4' => '1/4 ' . esc_html__('Blog Widget', 'totalbusiness'),
												'blog-widget-1-3' => '1/3 ' . esc_html__('Blog Widget', 'totalbusiness'),
												'blog-widget-1-2' => '1/2 ' . esc_html__('Blog Widget', 'totalbusiness'),
												'blog-widget-1-1' => '1/1 ' . esc_html__('Blog Widget', 'totalbusiness'),
												'blog-1-4' => '1/4 ' . esc_html__('Blog Grid', 'totalbusiness'),
												'blog-1-3' => '1/3 ' . esc_html__('Blog Grid', 'totalbusiness'),
												'blog-1-2' => '1/2 ' . esc_html__('Blog Grid', 'totalbusiness'),
												'blog-1-1' => '1/1 ' . esc_html__('Blog Grid', 'totalbusiness'),
												'blog-medium' => esc_html__('Blog Medium', 'totalbusiness'),
												'blog-full' => esc_html__('Blog Full', 'totalbusiness'),
											),
											'default'=>'blog-1-1'
										),		
										'blog-layout'=> array(
											'title'=> esc_html__('Blog Layout Order' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'fitRows' =>  esc_html__('FitRows ( Order items by row )', 'totalbusiness'),
												'masonry' => esc_html__('Masonry ( Order items by spaces )', 'totalbusiness'),
												'carousel' => esc_html__('Carousel ( Only For Blog Grid )', 'totalbusiness'),
											),
											'wrapper-class'=> 'blog-1-4-wrapper blog-1-3-wrapper blog-1-2-wrapper blog-style-wrapper',
											'description'=> esc_html__('You can see an example of these two layout here', 'totalbusiness') . 
												'<br>http://isotope.metafizzy.co/demos/layout-modes.html'
										),
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list(),
											'description'=> esc_html__('Only effects to <strong>standard and gallery post format</strong>.','totalbusiness')
										),	
										'orderby'=> array(
											'title'=> esc_html__('Order By' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'date' => esc_html__('Publish Date', 'totalbusiness'), 
												'title' => esc_html__('Title', 'totalbusiness'), 
												'rand' => esc_html__('Random', 'totalbusiness'), 
											)
										),
										'order'=> array(
											'title'=> esc_html__('Order' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'desc'=>esc_html__('Descending Order', 'totalbusiness'), 
												'asc'=> esc_html__('Ascending Order', 'totalbusiness'), 
											)
										),	
										'offset'=> array(
											'title'=> esc_html__('Offset' ,'totalbusiness'),
											'type'=> 'text',
											'description'=> esc_html__('Fill in number of the posts you want to skip. Please noted that this will not works well with pagination', 'totalbusiness')
										),										
										'pagination'=> array(
											'title'=> esc_html__('Enable Pagination' ,'totalbusiness'),
											'type'=> 'checkbox'
										),	
										'enable-sticky'=> array(
											'title'=> esc_html__('Prepend Sticky Post' ,'totalbusiness'),
											'type'=> 'checkbox',
											'default'=> 'disable'
										),											
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-blog-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),										
									))
								),								

								'box-icon-item' => array(
									'title'=> esc_html__('Box Icon', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'icon'=> array(
											'title'=> esc_html__('Icon Class' ,'totalbusiness'),
											'type'=> 'text',						
										),		
										'icon-position'=> array(
											'title'=> esc_html__('Icon Position' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'left'=> esc_html__('Left', 'totalbusiness'),
												'top'=> esc_html__('Top', 'totalbusiness')
											)
										),			
										'icon-type'=> array(
											'title'=> esc_html__('Icon Type' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'normal'=> esc_html__('Normal', 'totalbusiness'),
												'circle'=> esc_html__('Circle Background', 'totalbusiness')
											)					
										),	
										'icon-color'=> array(
											'title'=> esc_html__('Icon Color' ,'totalbusiness'),
											'type'=> 'colorpicker',		
											'default'=> '#5e5e5e'
										),	
										'icon-background'=> array(
											'title'=> esc_html__('Icon Background' ,'totalbusiness'),
											'type'=> 'colorpicker',		
											'default'=> '#91d549',
											'wrapper-class'=> 'icon-type-wrapper circle-wrapper'
										),
										'title'=> array(
											'title'=> esc_html__('Title' ,'totalbusiness'),
											'type'=> 'text',						
										),										
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'totalbusiness'),
											'type'=> 'tinymce',						
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	 
									)
								),								
								
								'column-service' => array(
									'title'=> esc_html__('Column Service', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'type'=> array(
											'title'=> esc_html__('Type' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'image'=> esc_html__('Image' ,'totalbusiness'),	
												'icon'=> esc_html__('Icon' ,'totalbusiness'),	
											)
										),						
										'image' => array(
											'title' => esc_html__('Image', 'totalbusiness'),
											'button' => esc_html__('Upload', 'totalbusiness'),
											'type' => 'upload',
											'wrapper-class' => 'type-wrapper image-wrapper'
										),	
										'icon'=> array(
											'title'=> esc_html__('Icon Class' ,'totalbusiness'),
											'type'=> 'text',
											'wrapper-class' => 'type-wrapper icon-wrapper'
										),		
										'title'=> array(
											'title'=> esc_html__('Title' ,'totalbusiness'),
											'type'=> 'text',						
										),	
										'style'=> array(
											'title'=> esc_html__('Item Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'type-1'=> esc_html__('Media Left' ,'totalbusiness'),	
												'type-3'=> esc_html__('Content Left' ,'totalbusiness'),	
												'type-2'=> esc_html__('Center' ,'totalbusiness'),	
												'type-2-hover'=> esc_html__('Center With Hover' ,'totalbusiness'),	
											)
										),		
										'hover-bg'=> array(
											'title'=> esc_html__('Hover Background Color' ,'totalbusiness'),
											'type'=> 'colorpicker',	
											'wrapper-class'=> 'type-2-hover-wrapper style-wrapper'
										),		
										'hover-text'=> array(
											'title'=> esc_html__('Hover Text Color' ,'totalbusiness'),
											'type'=> 'colorpicker',	
											'wrapper-class'=> 'type-2-hover-wrapper style-wrapper'						
										),										
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'totalbusiness'),
											'type'=> 'tinymce',						
										),		
										'read-more-text'=> array(
											'title'=> esc_html__('Read More Text' ,'totalbusiness'),
											'type'=> 'text',					
											'default'=> esc_html__('Read More' ,'totalbusiness')
										),		
										'read-more-link'=> array(
											'title'=> esc_html__('Read More Link' ,'totalbusiness'),
											'type'=> 'text',						
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	 
									)
								),								
								
								'content' => array(
									'title'=> esc_html__('Content', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(totalbusiness_page_builder_title_option(true), array(	
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'totalbusiness'),
											'type'=> 'tinymce',						
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),														
									))
								), 	

								'divider' => array(
									'title'=> esc_html__('Divider', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'type'=> array(
											'title'=> esc_html__('Divider', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'solid' => esc_html__('Solid', 'totalbusiness'),
												'double' => esc_html__('Double', 'totalbusiness'),
												'dotted' => esc_html__('Dotted', 'totalbusiness'),
												'double-dotted' => esc_html__('Double Dotted', 'totalbusiness'),
												'thick' => esc_html__('Thick', 'totalbusiness'),
												'with-icon' => esc_html__('With Icon', 'totalbusiness'),
											)
										),	
										'divider-color'=> array(	
											'title'=> esc_html__('Icon Background Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#333333',
											'wrapper-class'=> 'type-wrapper with-icon-wrapper',
										),	
										'icon-class'=> array(	
											'title'=> esc_html__('Icon Class' ,'totalbusiness'),
											'type'=> 'text',
											'wrapper-class'=> 'type-wrapper with-icon-wrapper',
										),	
										'size'=> array(	
											'title'=> esc_html__('Divider Width' ,'totalbusiness'),
											'type'=> 'text',
											'description'=> esc_html__('Specify the divider size. Ex. 50%, 200px', 'totalbusiness')
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-divider-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),										
									)
								),

								'feature-media' => array(
									'title'=> esc_html__('Feature Media', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'type'=> array(
											'title'=> esc_html__('Media Type' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'image'=> esc_html__('Image' ,'totalbusiness'),
												'video'=> esc_html__('Video' ,'totalbusiness')
											)
										),
										'video-url'=> array(
											'title'=> esc_html__('Video URL' ,'totalbusiness'),
											'type'=> 'text',
											'wrapper-class'=> 'type-wrapper video-wrapper'
										),
										'image'=> array(
											'title'=> esc_html__('Service Image' ,'totalbusiness'),
											'type'=> 'upload',						
											'button'=> esc_html__('upload' ,'totalbusiness'),	
											'wrapper-class'=> 'type-wrapper image-wrapper'
										),	
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list(),	
											'wrapper-class'=> 'type-wrapper image-wrapper'
										)
									), totalbusiness_page_builder_title_option(), array(								
										'align'=> array(
											'title'=> esc_html__('Alignment' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'left' => esc_html__('Left' ,'totalbusiness'),
												'center' => esc_html__('Center' ,'totalbusiness'),
											),
											'default'=> 'left'
										),										
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'totalbusiness'),
											'type'=> 'tinymce',						
										),	
										'button-text'=> array(
											'title'=> esc_html__('Learn More Button Text' ,'totalbusiness'),
											'type'=> 'text',
											'default'=> esc_html__('Learn More', 'totalbusiness')
										),		
										'button-link'=> array(
											'title'=> esc_html__('Learn More Button Link' ,'totalbusiness'),
											'type'=> 'text'
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	 
									))
								),
								
								'icon-with-list' => array(
									'title'=> esc_html__('List With Icon', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'icon-with-list'=> array(
											'type'=> 'icon-with-list',
											'default-title'=> esc_html__('Icon With List' ,'totalbusiness')
										),	
									), totalbusiness_page_builder_title_option(), array(
										'align'=> array(	
											'title'=> esc_html__('Text Align' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'left'=> esc_html__('Left Aligned' ,'totalbusiness'),
												'right'=> esc_html__('Right Aligned' ,'totalbusiness')
											)
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),										
									))
								), 		
								
								'menu' => array(
									'title'=> esc_html__('Menu List', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'content'=> array(
											'default-title'=> esc_html__('Menu', 'totalbusiness'),
											'type'=> 'custom_chart',
											'options'=> array(
												'gdl-tab-title' => esc_html__('Title', 'totalbusiness') . ':text',
												'caption' => esc_html__('Caption', 'totalbusiness') . ':text',
												'price' => esc_html__('Price', 'totalbusiness') . ':text',
												'icon' => esc_html__('Icon', 'totalbusiness') . ':text'
											)
										),
									), totalbusiness_page_builder_title_option(), array(											
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									))
								), 
								
								'notification' => array(
									'title'=> esc_html__('Notification', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'icon'=> array(	
											'title'=> esc_html__('Icon Class', 'totalbusiness'),
											'type'=> 'text'										
										),
										'type'=> array(	
											'title'=> esc_html__('Type', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'color-background'=> esc_html__('Color Background', 'totalbusiness'),
												'color-border'=> esc_html__('Color Border', 'totalbusiness'),
											)											
										),
										'content'=> array(	
											'title'=> esc_html__('Content', 'totalbusiness'),
											'type'=> 'textarea'										
										),
										'color'=> array(	
											'title'=> esc_html__('Text Color', 'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#000000'											
										),
										'background'=> array(	
											'title'=> esc_html__('Background Color', 'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#99d15e',
											'wrapper-class'=> 'type-wrapper color-background-wrapper'
										),
										'border'=> array(	
											'title'=> esc_html__('Border Color', 'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#99d15e',
											'wrapper-class'=> 'type-wrapper color-border-wrapper'											
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),
									)
								),

							'page'=> array(
									'title'=> esc_html__('Page', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(totalbusiness_page_builder_title_option(true), array(
										'category'=> array(
											'title'=> esc_html__('Category' ,'totalbusiness'),
											'type'=> 'multi-combobox',
											'options'=> totalbusiness_get_term_list('page_category'),
											'description'=> esc_html__('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'totalbusiness')
										),	
										'page-style'=> array(
											'title'=> esc_html__('Item Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'classic' => esc_html__('Classic Style', 'totalbusiness'),
												'modern' => esc_html__('Modern Style', 'totalbusiness'),
											),
										),	
										'item-size'=> array(
											'title'=> esc_html__('Item Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'1/4'=>'1/4',
												'1/3'=>'1/3',
												'1/2'=>'1/2',
												'1/1'=>'1/1'
											),
											'default'=>'1/3'
										),	
										'num-fetch'=> array(
											'title'=> esc_html__('Num Fetch' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> '8',
											'description'=> esc_html__('Specify the number of page you want to pull out.', 'totalbusiness')
										),																			
										'page-layout'=> array(
											'title'=> esc_html__('Page Layout Order' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'fitRows' =>  esc_html__('FitRows ( Order items by row )', 'totalbusiness'),
												'masonry' => esc_html__('Masonry ( Order items by spaces )', 'totalbusiness'),
											),
											'description'=> esc_html__('You can see an example of these two layout here', 'totalbusiness') . 
												'<br><br> http://isotope.metafizzy.co/demos/layout-modes.html'
										),					
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list(),
											'description'=> esc_html__('Only effects to <strong>standard and gallery post format</strong>','totalbusiness')
										),		
										'pagination'=> array(
											'title'=> esc_html__('Enable Pagination' ,'totalbusiness'),
											'type'=> 'checkbox'
										),					
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-blog-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),				
									))
								),	
								
								'personnel' => array(
									'title'=> esc_html__('Personnel', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'personnel'=> array(	
											'type'=> 'authorinfo',
											'default-title'=> esc_html__('Personnel' ,'totalbusiness')											
										)
									), totalbusiness_page_builder_title_option(), array(											
										'personnel-columns'=> array(
											'title'=> esc_html__('Personnel Columns' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5'),
											'default'=> '3'
										),				
										'personnel-type'=> array(
											'title'=> esc_html__('Personnel Type' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'static'=>esc_html__('Static Personnel', 'totalbusiness'),
												'carousel'=>esc_html__('Carousel Personnel', 'totalbusiness'),
											)
										),		
										'personnel-style'=> array(
											'title'=> esc_html__('Personnel Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'box-style'=>esc_html__('Box Style', 'totalbusiness'),
												'plain-style'=>esc_html__('Plain Style', 'totalbusiness'),
												'totalbusiness-left plain-style'=>esc_html__('Plain Left Style', 'totalbusiness'),
												'round-style'=>esc_html__('Round Style', 'totalbusiness'),
											)
										),	
										'thumbnail-size'=> array(
											'title'=> esc_html__('Author Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list(),
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),												
									))
								),		

								'pie-chart' => array(
									'title'=> esc_html__('Pie Chart', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'progress'=> array(
											'title'=> esc_html__('Progress (Percent)' ,'totalbusiness'),
											'type'=> 'text',
											'default'=> '50',
											'description'=> esc_html__('Accept integer value between 0 - 100', 'totalbusiness')
										),		
										'color'=> array(
											'title'=> esc_html__('Progress Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#f5be3b'
										),
										'bg-color'=> array(
											'title'=> esc_html__('Progress Track Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#f2f2f2'						
										),										
										'icon'=> array(
											'title'=> esc_html__('Icon Class' ,'totalbusiness'),
											'type'=> 'text',						
										),	
										'title'=> array(
											'title'=> esc_html__('Title' ,'totalbusiness'),
											'type'=> 'text',						
										),			
										'learn-more-link'=> array(
											'title'=> esc_html__('Learn More Link' ,'totalbusiness'),
											'type'=> 'text',						
										),											
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'totalbusiness'),
											'type'=> 'tinymce',						
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),											
									)
								),		
						
								'portfolio' => array(),
								
								'price-item' => array(
									'title'=> esc_html__('Price Item', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'image'=> array(
											'title'=> esc_html__('Image' ,'totalbusiness'),
											'type'=> 'upload',						
											'button'=> esc_html__('upload' ,'totalbusiness'),				
										),	
										'title' => array(
											'title' => esc_html__('Title', 'totalbusiness'),
											'type' => 'text',
										),	
										'price' => array(
											'title' => esc_html__('Price', 'totalbusiness'),
											'type' => 'text',
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									)
								),
								
								'price-table' => array(
									'title'=> esc_html__('Price Table', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'price-table'=> array(	
											'type'=> 'price-table',
											'default-title'=> esc_html__('Price Table' ,'totalbusiness')											
										),
										'columns'=> array(	
											'title' => esc_html__('Columns', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6),
											'default'=> 3
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									)
								),
								
								'service-half-background' => array(
									'title'=> esc_html__('Service Half Background', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'left-bg-image'=> array(
											'title'=> esc_html__('Left Service Background Image' ,'totalbusiness'),
											'type'=> 'upload',						
											'button'=> esc_html__('upload' ,'totalbusiness'),				
										),
										'left-title'=> array(
											'title'=> esc_html__('Left Service Title' ,'totalbusiness'),
											'type'=> 'text',						
										),													
										'left-content'=> array(
											'title'=> esc_html__('Left Service Content' ,'totalbusiness'),
											'type'=> 'textarea',						
										),													
										'left-read-more-text'=> array(
											'title'=> esc_html__('Left Read More Text' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> esc_html__('Read More', 'totalbusiness')
										),													
										'left-read-more-link'=> array(
											'title'=> esc_html__('Left Read More Link' ,'totalbusiness'),
											'type'=> 'text',						
										),
										'right-bg-color'=> array(
											'title'=> esc_html__('Right Service Background Color' ,'totalbusiness'),
											'type'=> 'colorpicker'			
										),
										'right-title'=> array(
											'title'=> esc_html__('Right Service Title' ,'totalbusiness'),
											'type'=> 'text',						
										),													
										'right-content'=> array(
											'title'=> esc_html__('Right Service Content' ,'totalbusiness'),
											'type'=> 'textarea',						
										),													
										'right-read-more-text'=> array(
											'title'=> esc_html__('Right Read More Text' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> esc_html__('Read More', 'totalbusiness')						
										),													
										'right-read-more-link'=> array(
											'title'=> esc_html__('Right Read More Link' ,'totalbusiness'),
											'type'=> 'text',						
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	 
									)
								),
								
								'service-with-image' => array(
									'title'=> esc_html__('Service With Image', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'image'=> array(
											'title'=> esc_html__('Service Image' ,'totalbusiness'),
											'type'=> 'upload',						
											'button'=> esc_html__('upload' ,'totalbusiness'),				
										),	
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list()
										),		
										'align'=> array(
											'title'=> esc_html__('Item Alignment' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'left'=> esc_html__('Left Aligned' ,'totalbusiness'),
												'right'=> esc_html__('Right Aligned' ,'totalbusiness')
											)
										),
										'title'=> array(
											'title'=> esc_html__('Title' ,'totalbusiness'),
											'type'=> 'text',						
										),													
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'totalbusiness'),
											'type'=> 'tinymce',						
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	 
									)
								),
								
								'stunning-text' => array(
									'title'=> esc_html__('Stunning Text', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'style'=> array(
											'title'=> esc_html__('Stunning Text Style', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'left' => esc_html__('Left', 'totalbusiness'),
												'small-left' => esc_html__('Small Left', 'totalbusiness'),
												'center' => esc_html__('Center', 'totalbusiness')
											)
										),
										'title'=> array(
											'title'=> esc_html__('Stunning Text title', 'totalbusiness'),
											'type'=> 'text'
										),		
										'caption'=> array(
											'title'=> esc_html__('Stunning Text Caption' ,'totalbusiness'),
											'type'=> 'textarea',
											'wrapper-class'=> 'style-wrapper left-wrapper center-wrapper'
										),	
										'button-text'=> array(
											'title'=> esc_html__('Stunning Button Text' ,'totalbusiness'),
											'type'=> 'text',
											'default'=> esc_html__('Learn More', 'totalbusiness')
										),		
										'button-link'=> array(
											'title'=> esc_html__('Stunning Button Link' ,'totalbusiness'),
											'type'=> 'text'
										),	
										'button2-text'=> array(
											'title'=> esc_html__('Stunning Button 2 Text' ,'totalbusiness'),
											'type'=> 'text',
											'wrapper-class'=> 'style-wrapper center-wrapper',
											'default'=> esc_html__('Buy Now!', 'totalbusiness')
										),		
										'button2-link'=> array(
											'title'=> esc_html__('Stunning Button 2 Link' ,'totalbusiness'),
											'type'=> 'text',
											'wrapper-class'=> 'style-wrapper center-wrapper',
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),										
									)
								),			

								'skill-bar' => array(
									'title'=> esc_html__('Skill Bar', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'content'=> array(
											'title'=> esc_html__('Title' ,'totalbusiness'),
											'type'=> 'text',
										),
										'percent'=> array(
											'title'=> esc_html__('Percent' ,'totalbusiness'),
											'type'=> 'text',
											'default'=> '0',
											'description'=> esc_html__('Fill only number here', 'totalbusiness')
										),	
										'size'=> array(
											'title'=> esc_html__('Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'small'=> esc_html__('Small' ,'totalbusiness'),
												'medium'=> esc_html__('Medium' ,'totalbusiness'),
												'large'=> esc_html__('Large' ,'totalbusiness'),
											)
										),	
										'icon'=> array(
											'title'=> esc_html__('Icon Class' ,'totalbusiness'),
											'type'=> 'text',
											'wrapper-class'=> 'size-wrapper medium-wrapper large-wrapper'
										),	
										'text-color'=> array(
											'title'=> esc_html__('Text Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#ffffff'
										),
										'background-color'=> array(
											'title'=> esc_html__('Background Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#e9e9e9'
										),												
										'progress-color'=> array(
											'title'=> esc_html__('Progress Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#f5be3b'
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									)
								),
								
								'skill-item' => array(
									'title'=> esc_html__('Skill Item', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'style'=> array(
											'title'=> esc_html__('Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'style-1'=> esc_html__('Style 1' ,'totalbusiness'),
												'style-2'=> esc_html__('Style 2' ,'totalbusiness'),
											)
										),
										'icon-color'=> array(
											'title'=> esc_html__('Icon Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#ffffff'
										),
										'title-color'=> array(
											'title'=> esc_html__('Title Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#ffffff'
										),
										'caption-color'=> array(
											'title'=> esc_html__('Caption Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#ffffff'
										),
										'icon-class'=> array(
											'title'=> esc_html__('Icon Class' ,'totalbusiness'),
											'type'=> 'text',
											'wrapper-class'=> 'style-2-wrapper style-wrapper'
										),
										'title'=> array(
											'title'=> esc_html__('Title' ,'totalbusiness'),
											'type'=> 'text',
										),
										'caption'=> array(
											'title'=> esc_html__('Caption' ,'totalbusiness'),
											'type'=> 'text',
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),
									)
								),
								
								'styled-box' => array(
									'title'=> esc_html__('Styled Box', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'type'=> array(
											'title'=> esc_html__('Background Type', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'color'=> esc_html__('Color Background' ,'totalbusiness'),
												'image'=> esc_html__('Image Background' ,'totalbusiness'),
											)
										),	
										'flip-corner'=> array(
											'title'=> esc_html__('Flip Corner' ,'totalbusiness'),
											'type'=> 'checkbox',
											'wrapper-class'=> 'type-wrapper color-wrapper'
										),										
										'background-color'=> array(
											'title'=> esc_html__('Background Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#9ada55',
											'wrapper-class'=> 'type-wrapper color-wrapper'
										),												
										'corner-color'=> array(
											'title'=> esc_html__('Corner Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#3d6817',
											'wrapper-class'=> 'type-wrapper color-wrapper'
										),		
										'content-color'=> array(
											'title'=> esc_html__('Content Color' ,'totalbusiness'),
											'type'=> 'colorpicker',
											'default'=> '#dddddd'
										),											
										'background-image'=> array(
											'title'=> esc_html__('Image URL' ,'totalbusiness'),
											'type'=> 'upload',
											'button' => esc_html__('Upload', 'totalbusiness'),
											'wrapper-class'=> 'type-wrapper image-wrapper'
										),										
										'content'=> array(
											'title'=> esc_html__('Content' ,'totalbusiness'),
											'type'=> 'tinymce'
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),										
									)
								),									
								
								'testimonial' => array(
									'title'=> esc_html__('Testimonial', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'testimonial'=> array(	
											'type'=> 'authorinfo',
											'enable-social'=> 'false',
											'default-title'=> esc_html__('Testimonial' ,'totalbusiness')											
										),
									), totalbusiness_page_builder_title_option(), array(													
										'testimonial-columns'=> array(
											'title'=> esc_html__('Testimonial Columns' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5'),
											'default'=> '3'
										),				
										'testimonial-type'=> array(
											'title'=> esc_html__('Testimonial Type' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'static'=>esc_html__('Static Testimonial', 'totalbusiness'),
												'carousel'=>esc_html__('Carousel Testimonial', 'totalbusiness'),
											)
										),		
										'testimonial-style'=> array(
											'title'=> esc_html__('Testimonial Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'box-style'=>esc_html__('Box Style', 'totalbusiness'),
												'round-style'=>esc_html__('Round Style', 'totalbusiness'),
												'plain-style'=>esc_html__('Plain Style', 'totalbusiness'),
												'large plain-style'=>esc_html__('Large Plain Style', 'totalbusiness'),
												'totalbusiness-left plain-style'=>esc_html__('Plain Left Style', 'totalbusiness'),
												'totalbusiness-left large plain-style'=>esc_html__('Large Plain Left Style', 'totalbusiness'),
											)
										),		
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),											
									))
								),								
								
								'tab' => array(
									'title'=> esc_html__('Tab', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'tab'=> array(
											'type'=> 'tab',
											'default-title'=> esc_html__('Tab' ,'totalbusiness')
										),					
										'initial-state'=> array(
											'title'=> esc_html__('Initial Tab', 'totalbusiness'),
											'type'=> 'text',
											'default'=> 1,
											'description'=> esc_html__('1 will open the first tab, 2 for second tab and so on.', 'totalbusiness')						
										),		
										'style'=> array(
											'title'=> esc_html__('Tab Style' ,'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'horizontal' => esc_html__('Horizontal Tab', 'totalbusiness'),
												'vertical' => esc_html__('Vertical Tab', 'totalbusiness'),
												'vertical right' => esc_html__('Vertical Right Tab', 'totalbusiness')
											)
										)	
									), totalbusiness_page_builder_title_option(), array(											
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									))
								), 								

								'title' => array(
									'title'=> esc_html__('Title', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(totalbusiness_page_builder_title_option(true), array(						
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									))
								),
								
								'toggle-box' => array(
									'title'=> esc_html__('Toggle Box', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'toggle-box'=> array(
											'type'=> 'toggle-box',
											'default-title'=> esc_html__('Toggle Box' ,'totalbusiness')							
										),
									), totalbusiness_page_builder_title_option(), array(										
										'style'=> array(
											'title'=> esc_html__('Accordion Style' ,'totalbusiness'),
											'type' => 'combobox',
											'options' => array(
												'style-1' => esc_html__('Style 1 ( Colored Background )', 'totalbusiness'),
												'style-2' => esc_html__('Style 2 ( Transparent Background )', 'totalbusiness')
											)
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),										
									))
								), 								
								 					
							)
						),
						
						'media-item' => array(
							'title' => esc_html__('Media Item', 'totalbusiness'),
							'blank_option' => esc_html__('- Select Media Item -', 'totalbusiness'),
							'options' => array(
							
								'banner' => array(
									'title'=> esc_html__('Banner', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(									
										'slider'=> array(	
											'overlay'=> 'false',
											'caption'=> 'false',
											'type'=> 'slider',
										),										
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list()
										),
										'banner-columns'=> array(
											'title'=> esc_html__('Banner Image Columns' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6'),
											'default'=> '4'
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),										
									)	
								),								

								'gallery' => array(
									'title'=> esc_html__('Gallery', 'totalbusiness'), 
									'type'=>'item',
									'options'=> array_merge(array(								
										'slider'=> array(	
											'overlay'=> 'false',
											'caption'=> 'false',
											'type'=> 'slider',
										),				
									), totalbusiness_page_builder_title_option(), array(												
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list()
										),
										'gallery-style'=> array(
											'title'=> esc_html__('Gallery Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'grid' => esc_html__('Grid Gallery', 'totalbusiness'),
												'thumbnail' => esc_html__('Thumbnail Gallery', 'totalbusiness')
											)
										),
										'gallery-columns'=> array(
											'title'=> esc_html__('Gallery Image Columns' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6'),
											'default'=> '4'
										),	
										'num-fetch'=> array(
											'title'=> esc_html__('Num Fetch (Per Page)' ,'totalbusiness'),
											'type'=> 'text',
											'description'=> esc_html__('Leave this field blank to fetch all image without pagination.', 'totalbusiness'),
											'wrapper-class'=>'gallery-style-wrapper grid-wrapper'
										),
										'show-caption'=> array(
											'title'=> esc_html__('Show Caption' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array('yes'=>'Yes', 'no'=>'No')
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									))	
								),		
								
								'image-frame' => array(
									'title'=> esc_html__('Image / Frame', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'image-id'=> array(
											'title'=> esc_html__('Upload Image', 'totalbusiness'),
											'type'=> 'upload',
											'button'=> esc_html__('Upload', 'totalbusiness')
										),	
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list()
										),
										'link-type'=> array(
											'title'=> esc_html__('Image Link', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'none'=> esc_html__('None', 'totalbusiness'),
												'content'=> esc_html__('Content', 'totalbusiness'),
												'url'=> esc_html__('Link to Url', 'totalbusiness'),
												'current'=> esc_html__('Lightbox to Current Image', 'totalbusiness'),
												'image'=> esc_html__('Lightbox to Image', 'totalbusiness'),
												'video'=> esc_html__('Lightbox to Video', 'totalbusiness'),
											)
										),
										'title' => array(
											'title' => esc_html__('Title', 'totalbusiness'),
											'type' => 'text',
											'wrapper-class' => 'link-type-wrapper content-wrapper'
										),
										'content' => array(
											'title' => esc_html__('Content', 'totalbusiness'),
											'type' => 'textarea',
											'wrapper-class' => 'link-type-wrapper content-wrapper'
										),
										'url' => array(
											'title' => esc_html__('URL', 'totalbusiness'),
											'type' => 'text',
											'wrapper-class' => 'link-type-wrapper image-wrapper video-wrapper url-wrapper'
										),
										'frame-type'=> array(
											'title'=> esc_html__('Frame Type', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'none'=> esc_html__('none', 'totalbusiness'),
												'border'=> esc_html__('Border', 'totalbusiness'),
												'solid'=> esc_html__('Solid', 'totalbusiness'),
												'rounded'=> esc_html__('Round', 'totalbusiness'),
												'circle'=> esc_html__('Circle', 'totalbusiness')
											)
										),
										'frame-background' => array(
											'title' => esc_html__('Frame Background', 'totalbusiness'),
											'type' => 'colorpicker',
											'default' => '#dddddd',
											'wrapper-class' => 'frame-type-wrapper solid-wrapper'
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),
									)
								),
								
								'layer-slider' => array(
									'title'=> esc_html__('Layer Slider', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'id'=> array(
											'title'=> esc_html__('Slider Type', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_layerslider_list(),
											'description'=> esc_html__('Please update layerslider to latest version to make this item work properly too', 'totalbusiness')
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									)
								),

								'master-slider' => array(
									'title'=> esc_html__('Master Slider', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'id'=> array(
											'title'=> esc_html__('Slider Type', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_masterslider_list(),
											'description'=> esc_html__('Please update layerslider to latest version to make this item work properly too', 'totalbusiness')
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									)
								),								

								'post-slider' => array(
									'title'=> esc_html__('Post Slider', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(	
										'category'=> array(
											'title'=> esc_html__('Category' ,'totalbusiness'),
											'type'=> 'multi-combobox',
											'options'=> totalbusiness_get_term_list('category'),
											'description'=> esc_html__('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'totalbusiness')
										),	
										'num-excerpt'=> array(
											'title'=> esc_html__('Num Excerpt (Word)' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> '25',
											'description'=> esc_html__('This is a number of word (decided by spaces) that you want to show on the post excerpt. <strong>Use 0 to hide the excerpt, -1 to show full posts and use the wordpress more tag</strong>.', 'totalbusiness')
										),	
										'num-fetch'=> array(
											'title'=> esc_html__('Num Fetch' ,'totalbusiness'),
											'type'=> 'text',	
											'default'=> '8',
											'description'=> esc_html__('Specify the number of posts you want to pull out.', 'totalbusiness')
										),										
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list()
										),	
										'style'=> array(
											'title'=> esc_html__('Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'no-excerpt'=>esc_html__('No Excerpt', 'totalbusiness'),
												'with-excerpt'=>esc_html__('With Excerpt', 'totalbusiness'),
											)
										),
										'caption-style'=> array(
											'title'=> esc_html__('Caption Style' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'post-bottom post-slider'=>esc_html__('Bottom Caption', 'totalbusiness'),
												'post-right post-slider'=>esc_html__('Right Caption', 'totalbusiness'),
												'post-left post-slider'=>esc_html__('Left Caption', 'totalbusiness')
											),
											'wrapper-class' => 'style-wrapper with-excerpt-wrapper'
										),											
										'orderby'=> array(
											'title'=> esc_html__('Order By' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'date' => esc_html__('Publish Date', 'totalbusiness'), 
												'title' => esc_html__('Title', 'totalbusiness'), 
												'rand' => esc_html__('Random', 'totalbusiness'), 
											)
										),
										'order'=> array(
											'title'=> esc_html__('Order' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'desc'=>esc_html__('Descending Order', 'totalbusiness'), 
												'asc'=> esc_html__('Ascending Order', 'totalbusiness'), 
											)
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),											
									)
								),
								
								'slider' => array(
									'title'=> esc_html__('Slider', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'slider'=> array(	
											'overlay'=> 'false',
											'caption'=> 'true',
											'type'=> 'slider'						
										),	
										'slider-type'=> array(
											'title'=> esc_html__('Slider Type', 'totalbusiness'),
											'type'=> 'combobox',
											'options'=> array(
												'flexslider' => esc_html__('Flex slider', 'totalbusiness'),
												'nivoslider' => esc_html__('Nivo Slider', 'totalbusiness')
											)
										),		
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'totalbusiness'),
											'type'=> 'combobox',
											'options'=> totalbusiness_get_thumbnail_list()
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),											
									)
								),

								'video' => array(
									'title'=> esc_html__('Video', 'totalbusiness'), 
									'type'=>'item',
									'options'=>array(
										'url'=> array(	
											'title'=> esc_html__('Video Url', 'totalbusiness'),
											'type'=> 'text',
											'descirption'=> esc_html__('Youtube / Vimeo / Self Hosted Video Is allowed Here', 'totalbusiness')
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'totalbusiness'),
											'type' => 'text',
											'default' => $totalbusiness_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'totalbusiness')
										),	
									)
								),															
								
							)
						)
					)
				)
			);
			
		}
		
	}
	
?>