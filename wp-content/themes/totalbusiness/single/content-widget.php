<?php
/**
 * The default template for displaying standard post format
 */
	global $totalbusiness_post_settings; 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="totalbusiness-standard-style">
		<?php get_template_part('single/thumbnail', get_post_format()); ?>
		<header class="post-header">
			<h3 class="totalbusiness-blog-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>

			<?php echo totalbusiness_get_blog_info(array('date', 'author'), true, '<span class="totalbusiness-sep">/</span>'); ?>		
			<div class="clear"></div>
		</header><!-- entry-header -->
		<div class="clear"></div>
	</div>
</article><!-- #post -->