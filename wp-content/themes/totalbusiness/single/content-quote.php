<?php
/**
 * The template for displaying quote post format
 */
 
	if( !is_single() ){ 
		global $totalbusiness_post_settings;
	}

	$post_format_data = '';
	$content = trim(get_the_content(esc_html__( 'Read More', 'totalbusiness' )));	
	if(preg_match('#^\[gdlr_quote[\s\S]+\[/gdlr_quote\]#', $content, $match)){ 
		$post_format_data = totalbusiness_content_filter($match[0]);
		$content = substr($content, strlen($match[0]));
	}else if(preg_match('#^<blockquote[\s\S]+</blockquote>#', $content, $match)){ 
		$post_format_data = totalbusiness_content_filter($match[0]);
		$content = substr($content, strlen($match[0]));
	}		
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="totalbusiness-blog-content">
		<div class="totalbusiness-top-quote">
			<?php echo totalbusiness_escape_content($post_format_data); ?>
		</div>
		<div class="totalbusiness-quote-author">
		<?php 
			if( is_single() || $totalbusiness_post_settings['excerpt'] < 0 ){
				echo totalbusiness_content_filter($content, true); 
			}else{
				echo totalbusiness_content_filter($content); 
			}
		?>	
		</div>
	</div>
</article><!-- #post -->