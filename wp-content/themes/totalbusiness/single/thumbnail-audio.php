<?php
/**
 * The template for displaying audio post format
 */
	global $totalbusiness_post_settings; 
	
	$post_format_data = '';
	$content = trim(get_the_content(esc_html__('Read More', 'totalbusiness')));		
	if(preg_match('#^https?://\S+#', $content, $match)){ 				
		$post_format_data = do_shortcode('[audio src="' . $match[0] . '" ][/audio]');
		$totalbusiness_post_settings['content'] = substr($content, strlen($match[0]));					
	}else if(preg_match('#^\[audio\s.+\[/audio\]#', $content, $match)){ 
		$post_format_data = do_shortcode($match[0]);
		$totalbusiness_post_settings['content'] = substr($content, strlen($match[0]));
	}else{
		$totalbusiness_post_settings['content'] = $content;
	}	

	if ( !empty($post_format_data) ){
		echo '<div class="totalbusiness-blog-thumbnail totalbusiness-audio">' . $post_format_data . '</div>';
	} 
			
			
?>