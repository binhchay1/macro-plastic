<?php
/**
 * The default template for displaying standard post format
 */
	global $totalbusiness_post_settings; 
	if( $totalbusiness_post_settings['excerpt'] < 0 ){ global $more; $more = 0; }
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="totalbusiness-standard-style">
		<?php get_template_part('single/thumbnail', get_post_format()); ?>
		
		<div class="totalbusiness-blog-content-wrapper">
			<header class="post-header">
				<?php echo totalbusiness_get_blog_info(array('date', 'author', 'comment'), true, '<span class="totalbusiness-sep">/</span>'); ?>		
				
				<?php if( is_single() ){ ?>
					<h1 class="totalbusiness-blog-title"><?php the_title(); ?></h1>
				<?php }else{ ?>
					<h3 class="totalbusiness-blog-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php } ?>
				<div class="clear"></div>
			</header><!-- entry-header -->

			<?php 
				if( $totalbusiness_post_settings['excerpt'] < 0 ){
					echo '<div class="totalbusiness-blog-content">';
					echo totalbusiness_content_filter($totalbusiness_post_settings['content'], true);
					wp_link_pages( array(
						'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'totalbusiness' ) . '</span>', 
						'after' => '</div>', 
						'link_before' => '<span>', 
						'link_after' => '</span>' )
					);
					echo '</div>';
				}else if( $totalbusiness_post_settings['excerpt'] != 0 ){
					echo '<div class="totalbusiness-blog-content">' . get_the_excerpt() . '</div>';
				}
			?>
		</div>
		<div class="clear"></div>
	</div>
</article><!-- #post -->