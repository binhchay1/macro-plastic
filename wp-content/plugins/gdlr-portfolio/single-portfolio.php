<?php 
	get_header(); 
	
	while( have_posts() ){ the_post();
?>
<div class="totalbusiness-content">

	<?php 
		global $totalbusiness_sidebar, $theme_option, $totalbusiness_post_option, $totalbusiness_is_ajax;
		
		if( empty($totalbusiness_post_option['sidebar']) || $totalbusiness_post_option['sidebar'] == 'default-sidebar' ){
			$totalbusiness_sidebar = array(
				'type'=>$theme_option['post-sidebar-template'],
				'left-sidebar'=>$theme_option['post-sidebar-left'], 
				'right-sidebar'=>$theme_option['post-sidebar-right']
			); 
		}else{
			$totalbusiness_sidebar = array(
				'type'=>$totalbusiness_post_option['sidebar'],
				'left-sidebar'=>$totalbusiness_post_option['left-sidebar'], 
				'right-sidebar'=>$totalbusiness_post_option['right-sidebar']
			); 				
		}
		$totalbusiness_sidebar = totalbusiness_get_sidebar_class($totalbusiness_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container totalbusiness-class-<?php echo $totalbusiness_sidebar['type']; ?>">
			<div class="with-sidebar-left <?php echo $totalbusiness_sidebar['outer']; ?> columns">
				<div class="with-sidebar-content <?php echo $totalbusiness_sidebar['center']; ?> columns">
					<div class="totalbusiness-item totalbusiness-portfolio-<?php echo $theme_option['portfolio-page-style']; ?> totalbusiness-item-start-content">
						<div id="portfolio-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php 
								$thumbnail = totalbusiness_get_portfolio_thumbnail($totalbusiness_post_option, $theme_option['portfolio-thumbnail-size']);
								if(!empty($thumbnail)){
									echo '<div class="totalbusiness-portfolio-thumbnail ' . totalbusiness_get_portfolio_thumbnail_class($totalbusiness_post_option) . '">';
									echo $thumbnail;
									echo '</div>';
								}
							?>
							<div class="totalbusiness-portfolio-content">
								<div class="totalbusiness-portfolio-info">
									<h4 class="head"><?php echo __('Project Info', 'gdlr-portfolio'); ?></h4>
									
									<nav class="totalbusiness-single-nav">
										<?php 
											if( !$totalbusiness_is_ajax ){
												previous_post_link('<div class="previous-nav">%link</div>', '<i class="icon-angle-left"></i>');
												next_post_link('<div class="next-nav">%link</div>', '<i class="icon-angle-right"></i>'); 
											}else{
												$prev_post = get_adjacent_post(false, '', true);
												if( !empty($prev_post) ){
													echo '<div class="previous-nav"><a href="#" data-lightbox="' . $prev_post->ID . '">';
													echo '<i class="icon-angle-left"></i>';
													echo '</a></div>';
												}
												
												$next_post = get_adjacent_post(false, '', false);
												if( !empty($next_post) ){
													echo '<div class="next-nav"><a href="#" data-lightbox="' . $next_post->ID . '">';
													echo '<i class="icon-angle-right"></i>';
													echo '</a></div>';
												}
											}
										?>
										<div class="clear"></div>
									</nav><!-- .nav-single -->		
									
									<div class="content">
									<?php 
										echo totalbusiness_get_portfolio_info(array('clients', 'skills', 'website', 'tag'), $totalbusiness_post_option, false); 
										totalbusiness_get_social_shares();
									?>							
									</div>
								</div>								
								<div class="totalbusiness-portfolio-description">
									<h4 class="head"><?php echo __('Project Description', 'gdlr-portfolio'); ?></h4>
									<div class="content">
									<?php 
										the_content(); 
										wp_link_pages( array( 
											'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'gdlr-portfolio' ) . '</span>', 
											'after' => '</div>', 
											'link_before' => '<span>', 
											'link_after' => '</span>' ));
									?>
									</div>
								</div>			
								<div class="clear"></div>
							</div>	
						</div><!-- #portfolio -->
						<?php //  ?>
						
						<div class="clear"></div>
						<?php 
							// print portfolio comment
							if( $theme_option['portfolio-comment'] == 'enable' ){
								comments_template( '', true ); 
							} 							
						?>		
					</div>
					
					<?php
						// print related portfolio
						if( !$totalbusiness_is_ajax && is_single() && $theme_option['portfolio-related'] == 'enable' ){	
							totalbusiness_include_portfolio_scirpt();
							
							global $totalbusiness_related_section; $totalbusiness_related_section = true;
						
							$args = array('post_type' => 'portfolio', 'suppress_filters' => false);
							$args['posts_per_page'] = (empty($theme_option['related-portfolio-num-fetch']))? '3': $theme_option['related-portfolio-num-fetch'];
							$args['post__not_in'] = array(get_the_ID());
							
							$portfolio_term = get_the_terms(get_the_ID(), 'portfolio_tag');
							$portfolio_tags = array();
							if( !empty($portfolio_term) ){
								foreach( $portfolio_term as $term ){
									$portfolio_tags[] = $term->term_id;
								}
								$args['tax_query'] = array(array('terms'=>$portfolio_tags, 'taxonomy'=>'portfolio_tag', 'field'=>'id'));
							} 
							$query = new WP_Query( $args );

							if( $query->have_posts() ){
								echo '<div class="totalbusiness-related-portfolio portfolio-item-holder">';
								echo '<h4 class="head">' . __('Related Projects', 'gdlr-portfolio') . '</h4>';
								if( $theme_option['related-portfolio-style'] == 'classic-portfolio' ){
									global $totalbusiness_excerpt_length; $totalbusiness_excerpt_length = $theme_option['related-portfolio-num-excerpt'];
									add_filter('excerpt_length', 'totalbusiness_set_excerpt_length');

									echo totalbusiness_get_classic_portfolio($query, $theme_option['related-portfolio-size'], 
										$theme_option['related-portfolio-thumbnail-size'], 'fitRows' );
									
									remove_filter('excerpt_length', 'totalbusiness_set_excerpt_length');	
								}else{
									echo totalbusiness_get_modern_portfolio($query, $theme_option['related-portfolio-size'], 
										$theme_option['related-portfolio-thumbnail-size'], 'fitRows' );								
								}
								echo '<div class="clear"></div>';
								echo '</div>'; 
							}
							$totalbusiness_related_section = false;
						}					
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
<?php
	}
	
	get_footer(); 
?>