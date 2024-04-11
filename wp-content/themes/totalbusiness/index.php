<?php get_header(); ?>
<div class="totalbusiness-content">

	<?php 
		global $totalbusiness_sidebar, $theme_option;
		$totalbusiness_sidebar = array(
			'type'=>'no-sidebar',
			'left-sidebar'=>'', 
			'right-sidebar'=>''
		); 
		$totalbusiness_sidebar = totalbusiness_get_sidebar_class($totalbusiness_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container">
			<div class="with-sidebar-left <?php echo esc_attr($totalbusiness_sidebar['outer']); ?> columns">
				<div class="with-sidebar-content <?php echo esc_attr($totalbusiness_sidebar['center']); ?> totalbusiness-item-start-content columns">
					<?php		
						// set the excerpt length
						if( !empty($theme_option['archive-num-excerpt']) ){
							global $totalbusiness_excerpt_length; $totalbusiness_excerpt_length = 55;
							add_filter('excerpt_length', 'totalbusiness_set_excerpt_length');
						} 

						global $wp_query, $totalbusiness_post_settings;
						$totalbusiness_lightbox_id++;
						$totalbusiness_post_settings['excerpt'] = 55;
						$totalbusiness_post_settings['thumbnail-size'] = 'full';			
						$totalbusiness_post_settings['blog-style'] = 'blog-full';							
					
						echo '<div class="blog-item-holder">';
						if($totalbusiness_post_settings['blog-style'] == 'blog-full'){
							$totalbusiness_post_settings['blog-info'] = array('author', 'date', 'category', 'comment');
							echo totalbusiness_get_blog_full($wp_query);
						}else{
							$totalbusiness_post_settings['blog-info'] = array('date', 'comment');
							$totalbusiness_post_settings['blog-info-widget'] = true;
							
							$blog_size = str_replace('blog-1-', '', $theme_option['archive-blog-style']);
							echo totalbusiness_get_blog_grid($wp_query, $blog_size, 
								$theme_option['archive-thumbnail-size'], 'fitRows');
						}
						echo '<div class="clear"></div>';
						echo '</div>';
						remove_filter('excerpt_length', 'totalbusiness_set_excerpt_length');
						
						$paged = (get_query_var('paged'))? get_query_var('paged') : 1;
						echo totalbusiness_get_pagination($wp_query->max_num_pages, $paged);													
					?>
				</div>
				<?php get_sidebar('left'); ?>
				<div class="clear"></div>
			</div>
			<?php get_sidebar('right'); ?>
			<div class="clear"></div>
		</div>				
	</div>				

</div><!-- totalbusiness-content -->
<?php get_footer(); ?>