<?php
	/*	
	*	Goodlayers Post Option file
	*	---------------------------------------------------------------------
	*	This file creates all post options to the post page
	*	---------------------------------------------------------------------
	*/
	
	// add a post admin option
	add_filter('totalbusiness_admin_option', 'totalbusiness_register_post_admin_option');
	if( !function_exists('totalbusiness_register_post_admin_option') ){
		function totalbusiness_register_post_admin_option( $array ){		
			if( empty($array['general']['options']) ) return $array;
			
			global $totalbusiness_sidebar_controller;
			if( empty($totalbusiness_sidebar_controller) ){ return false; }
			
			$post_option = array(
				'title' => esc_html__('Blog Style', 'totalbusiness'),
				'options' => array(
					'post-title' => array(
						'title' => esc_html__('Default Post Title', 'totalbusiness'),
						'type' => 'text',	
						'default' => 'Single Blog Title'
					),
					'post-caption' => array(
						'title' => esc_html__('Default Post Caption', 'totalbusiness'),
						'type' => 'textarea',
						'default' => 'This is a single blog caption'
					),			
					'post-thumbnail-size' => array(
						'title' => esc_html__('Single Post Thumbnail Size', 'totalbusiness'),
						'type'=> 'combobox',
						'options'=> totalbusiness_get_thumbnail_list(),
						'default'=> 'post-thumbnail-size'
					),
					'post-meta-data' => array(
						'title' => esc_html__('Disable Post Meta Data', 'totalbusiness'),
						'type'=> 'multi-combobox',
						'options'=> array(
							'date'=>'Date',
							'tag'=>'Tag',
							'category'=>'Category',
							'comment'=>'Comment',
							'author'=>'Author',
						),
						'description'=> esc_html__('Select this to remove the meta data out of the post.<br><br>', 'totalbusiness') .
							esc_html__('You can use Ctrl/Command button to select multiple option or remove the selected option.', 'totalbusiness')
					),
					'single-post-author' => array(
						'title' => esc_html__('Enable Single Post Author', 'totalbusiness'),
						'type'=> 'checkbox'
					),
					'post-sidebar-template' => array(
						'title' => esc_html__('Default Post Sidebar', 'totalbusiness'),
						'type' => 'radioimage',
						'options' => array(
							'no-sidebar'=>get_template_directory_uri() . '/include/images/no-sidebar.png',
							'both-sidebar'=>get_template_directory_uri() . '/include/images/both-sidebar.png', 
							'right-sidebar'=>get_template_directory_uri() . '/include/images/right-sidebar.png',
							'left-sidebar'=>get_template_directory_uri() . '/include/images/left-sidebar.png'
						),
						'default' => 'right-sidebar'							
					),
					'post-sidebar-left' => array(
						'title' => esc_html__('Default Post Sidebar Left', 'totalbusiness'),
						'type' => 'combobox',
						'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),		
						'wrapper-class'=>'left-sidebar-wrapper both-sidebar-wrapper post-sidebar-template-wrapper',											
					),
					'post-sidebar-right' => array(
						'title' => esc_html__('Default Post Sidebar Right', 'totalbusiness'),
						'type' => 'combobox',
						'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),
						'wrapper-class'=>'right-sidebar-wrapper both-sidebar-wrapper post-sidebar-template-wrapper',
					),										
				)
			);
			
			
			$array['general']['options']['blog-style'] = $post_option;
			return $array;
		}
	}		

	// add a post option to post page
	if( is_admin() ){ add_action('init', 'totalbusiness_create_post_options'); }
	if( !function_exists('totalbusiness_create_post_options') ){
	
		function totalbusiness_create_post_options(){
			global $totalbusiness_sidebar_controller;
			
			if( !class_exists('totalbusiness_page_options') ) return;
			new totalbusiness_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('post'),
					'meta_title' => esc_html__('Goodlayers Post Option', 'totalbusiness'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'page-layout' => array(
						'title' => esc_html__('Page Layout', 'totalbusiness'),
						'options' => array(
								'sidebar' => array(
									'title' => esc_html__('Sidebar Template' , 'totalbusiness'),
									'type' => 'radioimage',
									'options' => array(
										'default-sidebar'=>get_template_directory_uri() . '/include/images/default-sidebar-2.png',
										'no-sidebar'=>get_template_directory_uri() . '/include/images/no-sidebar-2.png',
										'both-sidebar'=>get_template_directory_uri() . '/include/images/both-sidebar-2.png', 
										'right-sidebar'=>get_template_directory_uri() . '/include/images/right-sidebar-2.png',
										'left-sidebar'=>get_template_directory_uri() . '/include/images/left-sidebar-2.png'
									),
									'default' => 'default-sidebar'
								),	
								'left-sidebar' => array(
									'title' => esc_html__('Left Sidebar' , 'totalbusiness'),
									'type' => 'combobox',
									'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),
									'wrapper-class' => 'sidebar-wrapper left-sidebar-wrapper both-sidebar-wrapper'
								),
								'right-sidebar' => array(
									'title' => esc_html__('Right Sidebar' , 'totalbusiness'),
									'type' => 'combobox',
									'options' => $totalbusiness_sidebar_controller->get_sidebar_array(),
									'wrapper-class' => 'sidebar-wrapper right-sidebar-wrapper both-sidebar-wrapper'
								),						
						)
					),
					
					'page-option' => array(
						'title' => esc_html__('Page Option', 'totalbusiness'),
						'options' => array(
							'page-title' => array(
								'title' => esc_html__('Post Title' , 'totalbusiness'),
								'type' => 'text',
								'description' => esc_html__('Leave this field blank to use the default title from admin panel > general > blog style section.', 'totalbusiness')
							),
							'page-caption' => array(
								'title' => esc_html__('Post Caption' , 'totalbusiness'),
								'type' => 'textarea'
							)						
						)
					),

				)
			);
			
		}
	}
	
	add_action('pre_post_update', 'totalbusiness_save_post_meta_option');
	if( !function_exists('totalbusiness_save_post_meta_option') ){
	function totalbusiness_save_post_meta_option( $post_id ){
			if( get_post_type() == 'post' && isset($_POST['post-option']) ){
				$post_option = totalbusiness_preventslashes(totalbusiness_stripslashes($_POST['post-option']));
				$post_option = json_decode(totalbusiness_decode_preventslashes($post_option), true);
			}
		}
	}
	
?>