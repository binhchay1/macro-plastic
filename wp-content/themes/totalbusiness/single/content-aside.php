<?php
/**
 * The template for displaying posts in the Aside post format
 */
 
	if( !is_single() ){ 
		global $totalbusiness_post_settings;
	}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="totalbusiness-blog-content">
		<?php 
			if( is_single() || $totalbusiness_post_settings['excerpt'] < 0 ){
				echo totalbusiness_content_filter(get_the_content(esc_html__( 'Read More', 'totalbusiness' )), true); 
			}else{
				echo totalbusiness_content_filter(get_the_content(esc_html__( 'Read More', 'totalbusiness' ))); 
			}
		?>
	</div>
</article>