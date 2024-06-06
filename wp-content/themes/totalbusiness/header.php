<!DOCTYPE html>
<!--[if IE 7]><html class="ie ie7 ltie8 ltie9" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="ie ie8 ltie9" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php
		global $theme_option, $totalbusiness_post_option;
		$body_wrapper = '';
		if(empty($theme_option['enable-responsive-mode']) || $theme_option['enable-responsive-mode'] == 'enable'){
			echo '<meta name="viewport" content="initial-scale=1.0" />';
		}else{
			$body_wrapper .= 'totalbusiness-no-responsive ';
		}
	?>
	
	<?php if( !function_exists( '_wp_render_title_tag' ) ){ ?>
		<title><?php wp_title(); ?></title>
	<?php } ?>
	
	<link rel="pingback" href="<?php esc_url(bloginfo( 'pingback_url' )); ?>" />
	<?php
		if( !empty($totalbusiness_post_option) ){ $totalbusiness_post_option = json_decode($totalbusiness_post_option, true); }

		wp_head();
	?>
</head>

<body <?php body_class(); ?>>
<?php

	if($theme_option['enable-boxed-style'] == 'boxed-style'){
		$body_wrapper  .= 'totalbusiness-boxed-style';
		if( !empty($theme_option['boxed-background-image']) && is_numeric($theme_option['boxed-background-image']) ){
			$alt_text = get_post_meta($theme_option['boxed-background-image'] , '_wp_attachment_image_alt', true);
			$image_src = wp_get_attachment_image_src($theme_option['boxed-background-image'], 'full');
			if( !empty($image_src) ){
				echo '<img class="totalbusiness-full-boxed-background" src="' . $image_src[0] . '" alt="' . $alt_text . '" />';
			}
		}else if( !empty($theme_option['boxed-background-image']) ){
			echo '<img class="totalbusiness-full-boxed-background" src="' . $theme_option['boxed-background-image'] . '" />';
		}
	}
	$body_wrapper .= ($theme_option['enable-float-menu'] != 'disable')? ' float-menu': '';
	
	global $header_style;
	$header_style = empty($theme_option['header-style'])? 'header-style-1': $theme_option['header-style'];
?>
<div class="body-wrapper <?php echo esc_attr($body_wrapper); ?>" data-home="<?php echo esc_url(home_url('/')); ?>" >
	<?php
		// page style
		if( empty($totalbusiness_post_option) || empty($totalbusiness_post_option['page-style']) ||
			  $totalbusiness_post_option['page-style'] == 'normal' ||
			  $totalbusiness_post_option['page-style'] == 'no-footer'){
	?>
	<header class="totalbusiness-header-wrapper <?php echo esc_attr($header_style . '-wrapper'); echo (empty($theme_option['enable-top-bar']) || $theme_option['enable-top-bar'] == 'enable')? ' totalbusiness-header-with-top-bar': ' totalbusiness-header-no-top-bar' ?>">
		<!-- top navigation -->
		<?php if( empty($theme_option['enable-top-bar']) || $theme_option['enable-top-bar'] == 'enable' ){ ?>
		<div class="top-navigation-wrapper">
			<div class="top-navigation-container container">
				<div class="top-navigation-left">
					<div class="top-navigation-left-text">
					<?php
						if( !empty($theme_option['top-bar-left-text']) )
							echo totalbusiness_text_filter(totalbusiness_escape_string($theme_option['top-bar-left-text']));
					?>
					</div>
				</div>
				<div class="top-navigation-right">
					<div class="top-social-wrapper">
						<?php totalbusiness_print_header_social(); ?>
					</div>	
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php } ?>
		<?php 
			// logo for header style 3 & 7
			if( $header_style == 'header-style-3' || $header_style == 'header-style-7' ){
				echo '<div class="totalbusiness-logo-wrapper">';
				echo '<div class="totalbusiness-logo-container container">';
				totalbusiness_get_logo();	
				
				if( $header_style == 'header-style-3' && !empty($theme_option['logo-right-text']) ){
					echo '<div class="totalbusiness-logo-right-text">' . totalbusiness_text_filter($theme_option['logo-right-text']) . '</div>';
				}
				echo '<div class="clear"></div>';
				echo '</div>';
				echo '</div>';
			}
		?>
		<div id="totalbusiness-header-substitute" ></div>
		<div class="totalbusiness-header-inner header-inner-<?php echo esc_attr($header_style); ?>">
			<div class="totalbusiness-header-container container">
				<div class="totalbusiness-header-inner-overlay"></div>
				
				
				<?php 
					// logo for other header style
					if( !($header_style == 'header-style-3' || $header_style == 'header-style-7') ){
						totalbusiness_get_logo(); 
					}
				?>

				<!-- navigation -->
				<?php get_template_part( 'header', 'nav' ); ?>

				<div class="clear"></div>
			</div>
		</div>
	</header>
	<?php get_template_part( 'header', 'title' );

	} // page style ?>
	<div class="content-wrapper">
		
		