<?php
/**
 * The template for displaying image post format
 */
	global $totalbusiness_post_settings; 

	$post_format_data = '';
	$content = trim(get_the_content(esc_html__( 'Read More', 'totalbusiness' )));
	if(preg_match('#^<a.+<img.+/></a>|^<img.+/>#', $content, $match)){ 
		$post_format_data = $match[0];
		$totalbusiness_post_settings['content'] = substr($content, strlen($match[0]));
	}else if(preg_match('#^https?://\S+#', $content, $match)){
		$post_format_data = totalbusiness_get_image($match[0], 'full', true);
		$totalbusiness_post_settings['content'] = substr($content, strlen($match[0]));					
	}else{
		$totalbusiness_post_settings['content'] = $content;
	}
	
	if ( !empty($post_format_data) ){
		echo '<div class="totalbusiness-blog-thumbnail">';
		echo totalbusiness_escape_content($post_format_data); 
		
		if( !is_single() && is_sticky() ){
			echo '<div class="totalbusiness-sticky-banner">';
			echo '<i class="fa ' . totalbusiness_fa_class('icon-bullhorn') . '" ></i>';
			echo esc_html__('Sticky Post', 'totalbusiness');
			echo '</div>';
		}					
		echo '</div>';
	} 
	?>