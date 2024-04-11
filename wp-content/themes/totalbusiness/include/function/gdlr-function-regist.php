<?php
	/*	
	*	Goodlayers Function Inclusion File
	*	---------------------------------------------------------------------
	*	This file contains the script to includes necessary function to the theme
	*	---------------------------------------------------------------------
	*/
	
	// include the shortcode support for the text widget
	add_filter('widget_text', 'do_shortcode');
	add_filter('widget_title', 'do_shortcode');
	
	// set up the content width based on the theme's design
	if ( !isset($content_width) ) $content_width = $theme_option['content-width'];	

	// rewrite permalink rule upon theme activation
	add_action( 'after_switch_theme', 'totalbusiness_flush_rewrite_rules' );
	if( !function_exists('totalbusiness_flush_rewrite_rules') ){
		function totalbusiness_flush_rewrite_rules() {
			global $pagenow, $wp_rewrite;
			if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
				$wp_rewrite->flush_rules();
			}
		}
	}
	
	// add tinymce editor style
	add_action( 'init', 'my_theme_add_editor_styles' );
	if( !function_exists('my_theme_add_editor_styles') ){
		function my_theme_add_editor_styles() {
			add_editor_style('/stylesheet/editor-style.css');
		}
	}
	
	// add script and style to header area
	add_action( 'wp_head', 'totalbusiness_head_script' );
	if( !function_exists('totalbusiness_head_script') ){
		function totalbusiness_head_script() {	
			global $theme_option;
			
			if( !function_exists( 'has_site_icon' ) || !has_site_icon() ){
				if( !empty($theme_option['favicon-id']) ){
					if( is_numeric($theme_option['favicon-id']) ){ 
						$favicon = wp_get_attachment_image_src($theme_option['favicon-id'], 'full');
						$theme_option['favicon-id'] = $favicon[0];
					}
					echo '<link rel="shortcut icon" href="' . $theme_option['favicon-id'] . '" type="image/x-icon" />';
				}
			}	

?>
<!-- load the script for older ie version -->
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri() . '/javascript/html5.js'; ?>" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri() . '/plugins/easy-pie-chart/excanvas.js'; ?>" type="text/javascript"></script>
<![endif]-->
<?php			
		}
	}
	
	// add the additional script to footer area
	add_action( 'wp_footer', 'totalbusiness_additional_script' );
	if( !function_exists('totalbusiness_additional_script') ){
		function totalbusiness_additional_script() {
			global $theme_option;
			echo '<script type="text/javascript">' . $theme_option['additional-script'] . '</script>';
		}
	}
	
	// init the theme_option value and customizer value upon activation	
	add_action( 'after_switch_theme', 'totalbusiness_get_default_admin_option' );
	if( !function_exists('totalbusiness_get_default_admin_option') ){
		function totalbusiness_get_default_admin_option() {
			
			add_action('admin_init', 'totalbusiness_load_default_theme_option');
			
			$sidebar_option = get_option('gdlr_sidebar_name', array());
			if(empty($sidebar_option)){
				update_option('gdlr_sidebar_name', 
					array( 'blog', 'blog-left', 'portfolio', 'portfolio-left', 'shortcodes',  'woocommerce',  'Features', 'Archives', 'contact')
				);
			}
			
			$google_font_list = get_option(THEME_SHORT_NAME . '_google_font_list', array());
			if( empty($google_font_list) ){
				$font_text  = 'a:3:{s:7:"Raleway";a:2:{s:7:"subsets";a:1:{i:0;s:5:"latin";}s:8:"variants";a:9:{i:0;s:3:"100";i:1;s:3:"200";i:2;s:3:"300";i:3;s:7:"regular";i:4;s:3:"500";i:5;s:3:"600";i:6;s:3:"700";i:7;s:3:"800";i:8;s:3:"900";}}s:4:"Lato";a:2:{s:7:"subsets";a:1:{i:0;s:5:"latin";}s:8:"variants";a:10:{i:0;s:3:"100";i:1;s:9:"100italic";i:2;s:3:"300";i:3;s:9:"300italic";i:4;s:7:"regular";i:5;s:6:"italic";i:6;s:3:"700";i:7;s:9:"700italic";i:8;s:3:"900";i:9;s:9:"900italic";}}s:11:"Crete Round";a:2:{s:7:"subsets";a:2:{i:0;s:5:"latin";i:1;s:9:"latin-ext";}s:8:"variants";a:2:{i:0;s:7:"regular";i:1;s:6:"italic";}}}';
				update_option(THEME_SHORT_NAME . '_google_font_list', unserialize($font_text));
			}
		}
	}
	if( !function_exists('totalbusiness_load_default_theme_option') ){
		function totalbusiness_load_default_theme_option() {
			$theme_option = get_option(THEME_SHORT_NAME . '_admin_option', array());
			if(empty($theme_option)){
				$current_page =  wp_nonce_url(admin_url('themes.php'),'totalbusiness-activation-init');
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));

				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);
			}
		}
	}
	// for printing default admin option
	// print_r( get_option('totalbusiness_sidebar_name', array()) );
	
	//$file_url = get_template_directory() . '/include/function/gdlr-admin-default.txt';
	//$file_stream = @fopen($file_url, 'w');
	//fwrite($file_stream, serialize($theme_option));
	//fclose($file_stream);
	
	//print_r( serialize(get_option(THEME_SHORT_NAME . '_google_font_list')) );
	
	// action to require the necessary wordpress function
 	add_action( 'after_setup_theme', 'totalbusiness_theme_setup' );
	if( !function_exists('totalbusiness_theme_setup') ){
		function totalbusiness_theme_setup(){
			
			// for translating the theme
			load_theme_textdomain( 'totalbusiness', get_template_directory() . '/languages' );
			
			// register main navigation menu 
			register_nav_menu( 'main_menu', esc_html__( 'Main Navigation Menu', 'totalbusiness' ) );

			// add support to title tag of wp 4.1
			add_theme_support( 'title-tag' );
			
			// adds RSS feed links to <head> for posts and comments.			
			add_theme_support( 'automatic-feed-links' );
			
			// This theme supports a variety of post formats.
			add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio' ) );			
		}
	}
	
	// turn the page comment off by default
	add_filter( 'wp_insert_post_data', 'page_default_comments_off' );
	if( !function_exists('page_default_comments_off') ){
		function page_default_comments_off( $data ) {
			if( $data['post_type'] == 'page' && $data['post_status'] == 'auto-draft' ) {
				$data['comment_status'] = 0;
			} 

			return $data;
		}
	}	
	
	// set the excerpt length of each item
	add_filter('excerpt_more', 'totalbusiness_excerpt_more');	
	if( !function_exists('totalbusiness_excerpt_more') ){
		function totalbusiness_excerpt_more( $more ) {
			global $totalbusiness_excerpt_read_more; if( !$totalbusiness_excerpt_read_more ) return '...';
			
			return '... <div class="clear"></div><a href="' . get_permalink() . '" class="excerpt-read-more">' . esc_html__( 'Read More', 'totalbusiness' ) . '</a>';
		}
	}
	add_filter('get_the_excerpt', 'totalbusiness_strip_excerpt_link');	
	if( !function_exists('totalbusiness_strip_excerpt_link') ){
		function totalbusiness_strip_excerpt_link( $excerpt ) {
			return preg_replace('#^https?://\S+#', '', $excerpt);
		}
	}	
	if( !function_exists('totalbusiness_set_excerpt_length') ){
		function totalbusiness_set_excerpt_length( $length ){
			global $totalbusiness_excerpt_length; return $totalbusiness_excerpt_length ;
		}
	}	

	// modify a wordpress gallery style
	add_filter('gallery_style', 'totalbusiness_gallery_style');
	if( !function_exists('totalbusiness_gallery_style') ){
		function totalbusiness_gallery_style( $style ){
			return str_replace('border: 2px solid #cfcfcf;', 'border-width: 1px; border-style: solid;', $style);
		}
	}
	
	// a comment callback function to create comment list
	if ( !function_exists('totalbusiness_comment_list') ){
		function totalbusiness_comment_list( $comment, $args, $depth ){
			global $theme_option;

			$GLOBALS['comment'] = $comment;
			switch ( $comment->comment_type ){
				case 'pingback' :
				case 'trackback' :
?>	
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	<p><?php esc_html_e( 'Pingback :', 'totalbusiness' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'totalbusiness' ), '<span class="edit-link">', '</span>' ); ?></p>
<?php break; ?>

<?php default : global $post; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<article id="comment-<?php comment_ID(); ?>" class="comment-article">
		<div class="comment-avatar"><?php echo get_avatar( $comment, 60 ); ?></div>
		<div class="comment-body">
			<header class="comment-meta">
				<div class="comment-author totalbusiness-title"><?php echo get_comment_author_link(); ?></div>
				<div class="comment-time">
					<i class="fa fa-clock-o"></i>
					<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
						<time datetime="<?php echo get_comment_time('c'); ?>">
						<?php echo get_comment_date() . ' ' . esc_html__('at', 'totalbusiness') . ' ' . get_comment_time(); ?>
						</time>
					</a>
				</div>
			<div class="comment-reply">
				<?php comment_reply_link( array_merge($args, array('before' => ' <i class="fa fa-mail-reply"></i>', 'reply_text' => esc_html__('Reply', 'totalbusiness'), 'depth' => $depth, 'max_depth' => $args['max_depth'])) ); ?>
			</div><!-- reply -->					
			</header>

			<?php if( '0' == $comment->comment_approved ){ ?>
				<p class="comment-awaiting-moderation"><?php echo esc_html__( 'Your comment is awaiting moderation.', 'totalbusiness' ); ?></p>
			<?php } ?>

			<section class="comment-content">
				<?php comment_text(); ?>
				<?php edit_comment_link( esc_html__( 'Edit', 'totalbusiness' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- comment-content -->

		</div><!-- comment-body -->
	</article><!-- comment-article -->
<?php
				break;
			}
		}
	}	
	
	// add login form to top left area
	// add_action('totalbusiness_top_left_menu', 'totalbusiness_create_login_form', 3);
	if ( !function_exists('totalbusiness_create_login_form') ){
		function totalbusiness_create_login_form(){
		global $theme_option; if($theme_option['top-bar-login'] == 'disable') return;
		
?>
<li class="totalbusiness-mega-menu">
	<?php 
		if(is_user_logged_in()){
			echo '<a href="' . esc_url(wp_logout_url(get_permalink())) . '"><i class="fa ' . totalbusiness_fa_class('icon-lock') . '"></i>' . esc_html__('Logout', 'totalbusiness') . '</a>';
		}else{
			echo '<a href="#"><i class="fa ' . totalbusiness_fa_class('icon-lock') . '"></i>' . esc_html__('Login', 'totalbusiness') . '</a>';
	?>
	<div class="sf-mega">
		<div class="sf-mega-section totalbusiness-login-form">
		<?php 
			wp_login_form(array(
				'label_username' => esc_html__('Username', 'totalbusiness'),
				'label_password' => esc_html__('Password', 'totalbusiness'),
				'label_remember' => esc_html__('Remember Me', 'totalbusiness'),
				'label_log_in' => esc_html__('Log In', 'totalbusiness')
			));
		?>
		</div>
	</div>
	<?php }?>
</li>
<?php
		}
	}
	
	// add subscription form to top left area
	// add_action('totalbusiness_top_left_menu', 'totalbusiness_create_subscription_form', 2);
	if ( !function_exists('totalbusiness_create_subscription_form') ){
		function totalbusiness_create_subscription_form(){
		global $theme_option; if(empty($theme_option['top-bar-subscribtion'])) return;
		
?>
<li class="totalbusiness-mega-menu">
	<a href="#"><i class="fa <?php echo totalbusiness_fa_class('icon-envelope'); ?>"></i><?php echo esc_html__('Subscribe', 'totalbusiness'); ?></a>
	<div class="sf-mega">
		<div class="sf-mega-section totalbusiness-mailchimp-form">
		<?php echo do_shortcode($theme_option['top-bar-subscribtion']); ?>
		</div>
	</div>
</li>
<?php
		}
	}	
	
?>