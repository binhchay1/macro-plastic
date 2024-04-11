<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the code to register the goodlayers plugin option
	*	to admin area
	*	---------------------------------------------------------------------
	*/

	// add an admin option to portfolio
	add_filter('totalbusiness_admin_option', 'totalbusiness_register_portfolio_admin_option');
	if( !function_exists('totalbusiness_register_portfolio_admin_option') ){
		
		function totalbusiness_register_portfolio_admin_option( $array ){		
			if( empty($array['general']['options']) ) return $array;
			
			$portfolio_option = array( 									
				'title' => esc_html__('Portfolio Style', 'totalbusiness'),
				'options' => array(
					'portfolio-slug' => array(
						'title' => esc_html__('Portfolio Slug ( Permalink )', 'totalbusiness'),
						'type' => 'text',
						'default' => 'portfolio',
						'description' => esc_html__('Only <strong>a-z (lower case), hyphen and underscore</strong> is allowed here <br><br>', 'totalbusiness') .
							esc_html__('After changing, you have to set the permalink at the setting > permalink to default (to reset the permalink rules) as well.', 'totalbusiness')
					),
					'portfolio-category-slug' => array(
						'title' => esc_html__('Portfolio Category Slug ( Permalink )', 'totalbusiness'),
						'type' => 'text',
						'default' => 'portfolio_category',
					),
					'portfolio-tag-slug' => array(
						'title' => esc_html__('Portfolio Tag Slug ( Permalink )', 'totalbusiness'),
						'type' => 'text',
						'default' => 'portfolio_tag',
					),					
					'portfolio-comment' => array(
						'title' => esc_html__('Enable Comment On Portfolio', 'totalbusiness'),
						'type' => 'checkbox',
						'default' => 'disable'
					),	
					'portfolio-related' => array(
						'title' => esc_html__('Enable Related Portfolio', 'totalbusiness'),
						'type' => 'checkbox',
						'default' => 'enable'
					),					
					'portfolio-page-style' => array(
						'title' => esc_html__('Portfolio Page Style', 'totalbusiness'),
						'type'=> 'combobox',
						'options'=> array(
							'style1'=> esc_html__('Portfolio Style 1', 'totalbusiness'),
							'style2'=> esc_html__('Portfolio Style 2', 'totalbusiness'),
							'blog-style'=> esc_html__('Blog Style', 'totalbusiness'),
						)
					),					
					'portfolio-thumbnail-size' => array(
						'title' => esc_html__('Single Portfolio Thumbnail Size', 'totalbusiness'),
						'type'=> 'combobox',
						'options'=> totalbusiness_get_thumbnail_list(),
						'default'=> 'post-thumbnail-size'
					),	
					'related-portfolio-style' => array(
						'title' => esc_html__('Related Portfolio Style', 'totalbusiness'),
						'type'=> 'combobox',
						'options'=> array(
							'classic-portfolio'=> esc_html__('Portfolio Classic Style', 'totalbusiness'),
							'modern-portfolio'=> esc_html__('Portfolio Modern Style', 'totalbusiness')
						)
					),
					'related-portfolio-size' => array(
						'title' => esc_html__('Related Portfolio Size', 'totalbusiness'),
						'type'=> 'combobox',
						'options'=> array(
							'2'=> esc_html__('1/2', 'totalbusiness'),
							'3'=> esc_html__('1/3', 'totalbusiness'),
							'4'=> esc_html__('1/4', 'totalbusiness'),
							'5'=> esc_html__('1/5', 'totalbusiness')
						),
						'default'=>'4'
					),					
					'related-portfolio-num-fetch' => array(
						'title' => esc_html__('Related Portfolio Num Fetch', 'totalbusiness'),
						'type'=> 'text',
						'default'=> '4'
					),					
					'related-portfolio-num-excerpt' => array(
						'title' => esc_html__('Related Portfolio Num Excerpt', 'totalbusiness'),
						'type'=> 'text',
						'default'=> '25'
					),
					'related-portfolio-thumbnail-size' => array(
						'title' => esc_html__('Related Portfolio Thumbnail Size', 'totalbusiness'),
						'type'=> 'combobox',
						'options'=> totalbusiness_get_thumbnail_list(),
						'default'=> 'small-grid-size'
					),					
				)
			);
			
			$array['general']['options']['portfolio-style'] = $portfolio_option;
			return $array;
		}
		
	}		

?>