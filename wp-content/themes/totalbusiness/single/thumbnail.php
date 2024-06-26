<?php 
	if( !is_single() ){ 
		global $totalbusiness_post_settings; 
	}else{
		global $totalbusiness_post_settings, $theme_option, $totalbusiness_post_option;
	}
	$totalbusiness_post_settings['content'] = get_the_content();
	
	if ( has_post_thumbnail() && ! post_password_required() ){ ?>
		<div class="totalbusiness-blog-thumbnail">
			<?php 
				if( is_single() ){
					echo totalbusiness_get_image(get_post_thumbnail_id(), $theme_option['post-thumbnail-size'], true);	
				}else{
					$temp_option = json_decode(get_post_meta(get_the_ID(), 'post-option', true), true);
					echo '<a href="' . get_permalink() . '"> ';
					echo totalbusiness_get_image(get_post_thumbnail_id(), $totalbusiness_post_settings['thumbnail-size']);
					echo '</a>';
					
					if( is_sticky() ){
						echo '<div class="totalbusiness-sticky-banner">';
						echo '<i class="fa ' . totalbusiness_fa_class('icon-bullhorn') . '" ></i>';
						echo esc_html__('Sticky Post', 'totalbusiness');
						echo '</div>';
					}
				}
			?>
		</div>
<?php 
	} 
?>	