<?php
/**
 * The template for displaying video post format
 */
 
	if( !is_single() ){ 
		global $totalbusiness_post_settings; 
	}else{
		global $totalbusiness_post_settings, $theme_option;
	}

	$post_format_data = '';
	$content = trim(get_the_content(esc_html__('Read More', 'totalbusiness')));	
	if(preg_match('#^https?://\S+#', $content, $match)){ 		
		if( is_single() || $totalbusiness_post_settings['blog-style'] == 'blog-full' ){
			$post_format_data = totalbusiness_get_video($match[0], 'full');
		}else{
			$post_format_data = totalbusiness_get_video($match[0], $totalbusiness_post_settings['thumbnail-size']);
			
		}
		$totalbusiness_post_settings['content'] = substr($content, strlen($match[0]));				
	}else if(preg_match('#^\[video\s.+\[/video\]#', $content, $match)){ 
		$post_format_data = do_shortcode($match[0]);
		$totalbusiness_post_settings['content'] = substr($content, strlen($match[0]));
	}else if(preg_match('#^\[embed.+\[/embed\]#', $content, $match)){ 
		global $wp_embed; 
		$post_format_data = $wp_embed->run_shortcode($match[0]);
		$totalbusiness_post_settings['content'] = substr($content, strlen($match[0]));
	}else{
		$totalbusiness_post_settings['content'] = $content;
	}

	if ( !empty($post_format_data) ){
		echo '<div class="totalbusiness-blog-thumbnail totalbusiness-video">' . $post_format_data . '</div>';
	} 
?>