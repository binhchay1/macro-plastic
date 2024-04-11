<?php get_header(); ?>
<div class="totalbusiness-content">

	<?php 
		global $totalbusiness_sidebar, $theme_option, $totalbusiness_post_settings;
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
					<div class="totalbusiness-item totalbusiness-blog-full totalbusiness-item-start-content">
					<?php while ( have_posts() ){ the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="totalbusiness-standard-style">
								<header class="post-header">
									<?php 
										$thumbnail = totalbusiness_get_portfolio_thumbnail($totalbusiness_post_option, $theme_option['portfolio-thumbnail-size']);
										if(!empty($thumbnail)){
											echo '<div class="totalbusiness-blog-thumbnail ' . totalbusiness_get_portfolio_thumbnail_class($totalbusiness_post_option) . '">';
											echo $thumbnail;
											echo '</div>';
										}
									?>
									<div class="clear"></div>
								</header><!-- entry-header -->

								<?php 
									echo '<div class="totalbusiness-blog-content">';
									the_content();
									wp_link_pages( array( 
										'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'gdlr-portfolio' ) . '</span>', 
										'after' => '</div>', 
										'link_before' => '<span>', 
										'link_after' => '</span>' )
									);
									echo '</div>';
								?>
							</div>
						</article><!-- #post -->
						
						<?php totalbusiness_get_social_shares(); ?>
						
						<nav class="totalbusiness-single-nav">
							<?php previous_post_link('<div class="previous-nav">%link</div>', '<i class="icon-angle-left"></i><span>%title</span>', true); ?>
							<?php next_post_link('<div class="next-nav">%link</div>', '<span>%title</span><i class="icon-angle-right"></i>', true); ?>
							<div class="clear"></div>
						</nav><!-- .nav-single -->

						<!-- abou author section -->
						<?php if($theme_option['single-post-author'] != "disable"){ ?>
							<div class="totalbusiness-post-author">
							<h3 class="post-author-title" ><?php echo __('About Post Author', 'gdlr-portfolio'); ?></h3>
							<div class="post-author-avartar"><?php echo get_avatar(get_the_author_meta('ID'), 90); ?></div>
							<div class="post-author-content">
							<h4 class="post-author"><?php the_author_posts_link(); ?></h4>
							<?php echo get_the_author_meta('description'); ?>
							</div>
							<div class="clear"></div>
							</div>
						<?php } ?>						

						<?php comments_template( '', true ); ?>		
						
					<?php } ?>
					</div>
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