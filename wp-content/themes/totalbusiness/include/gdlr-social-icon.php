<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the function that helps you print the social section
	*	---------------------------------------------------------------------
	*/
	
	$totalbusiness_header_social_icon = array(
		'delicious'		=> esc_html__('Delicius','totalbusiness'), 
		'deviantart'	=> esc_html__('Deviant Art','totalbusiness'), 
		'digg'			=> esc_html__('Digg','totalbusiness'),
		'email' 		=> esc_html__('Email','totalbusiness'),				
		'facebook'		=> esc_html__('Facebook','totalbusiness'), 
		'flickr'		=> esc_html__('Flickr','totalbusiness'),
		'google-plus' 	=> esc_html__('Google Plus','totalbusiness'),				
		'lastfm'       	=> esc_html__('Lastfm','totalbusiness'),
		'linkedin' 		=> esc_html__('Linkedin','totalbusiness'),
		'pinterest' 	=> esc_html__('Pinterest','totalbusiness'),
		'rss' 			=> esc_html__('Rss','totalbusiness'),
		'skype'			=> esc_html__('Skype','totalbusiness'),
		'stumble-upon' 	=> esc_html__('Stumble Upon','totalbusiness'),
		'tumblr' 		=> esc_html__('Tumblr','totalbusiness'),
		'twitter' 		=> esc_html__('Twitter','totalbusiness'),
		'vimeo' 		=> esc_html__('Vimeo','totalbusiness'),
		'youtube' 		=> esc_html__('Youtube','totalbusiness'),
		'instagram' 	=> esc_html__('Instagram','totalbusiness'),
	);	
	
	add_filter('totalbusiness_admin_option', 'totalbusiness_register_header_social_option');
	if( !function_exists('totalbusiness_register_header_social_option') ){
		function totalbusiness_register_header_social_option( $array ){		
			if( empty($array['overall-elements']['options']) ) return $array;
			
			global $totalbusiness_header_social_icon;
			$header_social = array( 									
				'title' => esc_html__('Header Social', 'totalbusiness'),
				'options' => array(
					'social-icon-color' => array(
						'title' => esc_html__('Social Icon Color', 'totalbusiness'),
						'type' => 'colorpicker',
						'default' => '#888888',
						'selector'=> '.top-social-wrapper .social-icon a{ color: #gdlr#; }'
					),
				)
			);
				
			foreach( $totalbusiness_header_social_icon as $social_slug => $social_name ){
				$header_social['options'][$social_slug . '-header-social'] = array(
					'title' => $social_name . ' ' . esc_html__('Header Social', 'totalbusiness'),
					'type' => 'text',				
				);
				
				if( $social_slug = 'email' ){
					$header_social['options'][$social_slug . '-header-social']['description'] =
						'Adding \'mailto:someone@example.com\' will allows users to click the icon to send an e-mail.';
				}
			}
			
			$array['overall-elements']['options']['header-social'] = $header_social;
			return $array;
		}
	}

	if( !function_exists('totalbusiness_print_header_social') ){
		function totalbusiness_print_header_social( $header_style = 'solid'){
			global $totalbusiness_header_social_icon, $theme_option;
			if( $header_style == 'solid' ){
				$type = empty($theme_option['header-social-type'])? 'dark': $theme_option['header-social-type'];
			}else{
				$type = empty($theme_option['transparent-header-social-type'])? 'light': $theme_option['transparent-header-social-type'];
			}
			
			foreach( $totalbusiness_header_social_icon as $social_slug => $social_name ){
				if( !empty($theme_option[$social_slug . '-header-social']) ){
?>
<div class="social-icon">
<a href="<?php echo esc_url($theme_option[$social_slug . '-header-social']); ?>" target="_blank" >
<?php 
	echo '<i class="fa fa-';
	switch ($social_slug){
		case 'delicious'	: echo 'delicious'; break;
		case 'deviantart'	: echo 'deviantart'; break;
		case 'digg'			: echo 'digg'; break;
		case 'email' 		: echo 'envelope'; break;	
		case 'facebook'		: echo 'facebook'; break;
		case 'flickr'		: echo 'flickr'; break;
		case 'google-plus' 	: echo 'google-plus'; break;			
		case 'lastfm'       : echo 'lastfm'; break;
		case 'linkedin' 	: echo 'linkedin'; break;
		case 'pinterest' 	: echo 'pinterest-p'; break;
		case 'rss' 			: echo 'rss'; break;
		case 'skype'		: echo 'skype'; break;
		case 'stumble-upon' : echo 'stumbleupon'; break;
		case 'tumblr' 		: echo 'tumblr'; break;
		case 'twitter' 		: echo 'twitter'; break;
		case 'vimeo' 		: echo 'vimeo'; break;
		case 'youtube' 		: echo 'youtube'; break;
		case 'instagram' 	: echo 'instagram'; break;
	}
	echo '" ></i>';
?>
</a>
</div>
<?php				
				}
			}
			echo '<div class="clear"></div>';
		}
	}	
	
	add_filter('totalbusiness_admin_option', 'totalbusiness_register_social_shares_option');
	if( !function_exists('totalbusiness_register_social_shares_option') ){
		function totalbusiness_register_social_shares_option( $array ){	
			if( empty($array['overall-elements']['options']) ) return $array;
			
			$totalbusiness_social_shares = array(
				'digg'			=> esc_html__('Digg','totalbusiness'),			
				'facebook'		=> esc_html__('Facebook','totalbusiness'), 
				'google-plus' 	=> esc_html__('Google Plus','totalbusiness'),	
				'linkedin' 		=> esc_html__('Linkedin','totalbusiness'),
				'my-space' 		=> esc_html__('My Space','totalbusiness'),
				'pinterest' 	=> esc_html__('Pinterest','totalbusiness'),
				'reddit' 		=> esc_html__('Reddit','totalbusiness'),
				'stumble-upon' 	=> esc_html__('Stumble Upon','totalbusiness'),
				'twitter' 		=> esc_html__('Twitter','totalbusiness'),
			);	
			$header_social = array( 									
				'title' => esc_html__('Social Shares', 'totalbusiness'),
				'options' => array(
					'enable-social-share'=> array(
						'title' => esc_html__('Enable Social Share' ,'totalbusiness'),
						'type' => 'checkbox',
						'description' => 'Enable this option to show the social shares below each post'
					)
				)
			);
				
			foreach( $totalbusiness_social_shares as $social_slug => $social_name ){
				$header_social['options'][$social_slug . '-share'] = array(
					'title' => $social_name,
					'type' => 'checkbox'				
				);
			}
			
			$array['overall-elements']['options']['social-shares'] = $header_social;
			return $array;
		}
	}	
	
	if( !function_exists('totalbusiness_get_social_shares') ){
		function totalbusiness_get_social_shares(){	
			global $theme_option;

			$page_title = get_the_title();
			$page_title = urlencode(html_entity_decode($page_title, ENT_COMPAT, 'UTF-8'));
			
			$current_url = HTTP_TYPE . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

			if($theme_option['enable-social-share'] == 'enable'){ ?>
<div class="totalbusiness-social-share">
<span class="social-share-title"><?php echo esc_html__('Share Post:', 'totalbusiness'); ?></span>
<?php if($theme_option['digg-share'] == 'enable'){ ?>
	<a href="http://digg.com/submit?url=<?php echo esc_url($current_url); ?>&#038;title=<?php echo $page_title; ?>" target="_blank">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/digg.png" alt="digg-share" width="32" height="32" />
	</a>
<?php } ?>

<?php if($theme_option['facebook-share'] == 'enable'){ ?>
	<a href="http://www.facebook.com/share.php?u=<?php echo esc_url($current_url); ?>" target="_blank">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/facebook.png" alt="facebook-share" width="32" height="32" />
	</a>
<?php } ?>

<?php if($theme_option['google-plus-share'] == 'enable'){ ?>
	<a href="https://plus.google.com/share?url=<?php echo esc_url($current_url); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=500');return false;">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/google-plus.png" alt="google-share" width="32" height="32" />
	</a>
<?php } ?>

<?php if($theme_option['linkedin-share'] == 'enable'){ ?>
	<a href="http://www.linkedin.com/shareArticle?mini=true&#038;url=<?php echo esc_url($current_url); ?>&#038;title=<?php echo $page_title; ?>" target="_blank">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/linkedin.png" alt="linked-share" width="32" height="32" />
	</a>
<?php } ?>

<?php if($theme_option['my-space-share'] == 'enable'){ ?>
	<a href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?php echo esc_url($current_url); ?>" target="_blank">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/my-space.png" alt="my-space-share" width="32" height="32" />
	</a>
<?php } ?>

<?php 
	if($theme_option['pinterest-share'] == 'enable'){ 
		$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
		$thumbnail = wp_get_attachment_image_src( $thumbnail_id , 'large' ); 
?>
	<a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url($current_url); ?>&media=<?php echo esc_url($thumbnail[0]); ?>" class="pin-it-button" count-layout="horizontal" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/pinterest.png" alt="pinterest-share" width="32" height="32" />
	</a>	
<?php } ?>

<?php if($theme_option['reddit-share'] == 'enable'){ ?>
	<a href="http://reddit.com/submit?url=<?php echo esc_url($current_url); ?>&#038;title=<?php echo $page_title; ?>" target="_blank">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/reddit.png" alt="reddit-share" width="32" height="32" />
	</a>
<?php } ?>

<?php if($theme_option['stumble-upon-share'] == 'enable'){ ?>
	<a href="http://www.stumbleupon.com/submit?url=<?php echo esc_url($current_url); ?>&#038;title=<?php echo $page_title; ?>" target="_blank">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/stumble-upon.png" alt="stumble-upon-share" width="32" height="32" />
	</a>
<?php } ?>

<?php if($theme_option['twitter-share'] == 'enable'){ ?>
	<a href="http://twitter.com/home?status=<?php echo $page_title . ' - ' . $current_url; ?>" target="_blank">
		<img src="<?php echo get_template_directory_uri(); ?>/images/dark/social-icon/twitter.png" alt="twitter-share" width="32" height="32" />
	</a>
<?php } ?>
<div class="clear"></div>
</div>
			<?php }
		}
	}	
	
?>