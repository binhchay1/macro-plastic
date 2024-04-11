<?php
	/*	
	*	Goodlayers Import Variable Setting
	*/

	if( is_admin() ){
		add_filter('gdlr_nav_meta', 'totalbusiness_add_import_nav_meta');
		add_action('gdlr_import_end', 'totalbusiness_add_import_action');
	}
	
	if( !function_exists('totalbusiness_add_import_nav_meta') ){
		function totalbusiness_add_import_nav_meta( $array ){
			return array('_gdlr_menu_icon', '_gdlr_mega_menu_item', '_gdlr_mega_menu_section');
		}
	}
	
	if( !function_exists('totalbusiness_add_import_action') ){
		function totalbusiness_add_import_action(){
			
			// setting > reading area
			update_option( 'show_on_front', 'page' );
			update_option( 'page_for_posts', 0 );
			update_option( 'page_on_front', 5368 );
			
			$current_page =  wp_nonce_url(admin_url('admin.php?import=goodlayers-importer'),'totalbusiness-importer');
			
			// style-custom file
			if( $_POST['import-file'] == 'demo.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-normal.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}else if( $_POST['import-file'] == 'demo-plastic.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default-plastic.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-plastic.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}else if( $_POST['import-file'] == 'demo-steel.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default-steel.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-steel.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}else if( $_POST['import-file'] == 'demo-creative.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default-creative.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-creative.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}else if( $_POST['import-file'] == 'demo-creative2.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default-creative2.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-creative2.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}else if( $_POST['import-file'] == 'demo-creative3.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default-creative3.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-creative3.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}else if( $_POST['import-file'] == 'demo-coffee.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default-coffee.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-coffee.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}else if( $_POST['import-file'] == 'demo-cafe.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default-cafe.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-cafe.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}else if( $_POST['import-file'] == 'demo-wedding.xml' ){
				$default_file = get_template_directory() . '/include/function/gdlr-admin-default-wedding.txt';
				$default_admin_option = unserialize(totalbusiness_read_filesystem($current_page, $default_file));
				update_option(THEME_SHORT_NAME . '_admin_option', $default_admin_option);	

				$source = get_template_directory() . '/stylesheet/style-custom-wedding.css';
				$destination = get_template_directory() . '/stylesheet/style-custom.css';
				copy($source, $destination);				
			}
			
			// menu to themes location
			$nav_id = 0;
			$navs = get_terms('nav_menu', array( 'hide_empty' => true ));
			foreach( $navs as $nav ){
				if($nav->name == 'Main menu'){
					$nav_id = $nav->term_id; break;
				}
			}
			set_theme_mod('nav_menu_locations', array('main_menu' => $nav_id));		

			// import the widget
			$widget_file = get_template_directory() . '/plugins/goodlayers-importer-widget.txt';
			$widget_data = unserialize(totalbusiness_read_filesystem($current_page, $widget_file));
			
			// retrieve widget data
			foreach($widget_data as $key => $value){
				update_option( $key, $value );
			}
			
		}
	}
	
	//global $wpdb;
	//$vals = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'widget_%'");
	//echo '\'sidebars_widgets\', ';
	//foreach( $vals as $val ){
	//	echo '\'' . $val->option_name . '\', ';
	//}
	
	//$widget_data = array();
	//$widget_list = array('sidebars_widgets', 'widget_archives', 'widget_calendar', 'widget_categories', 'widget_totalbusiness-flickr-widget', 'widget_totalbusiness-popular-post-widget', 'widget_totalbusiness-port-slider-widget', 'widget_totalbusiness-post-slider-widget', 'widget_totalbusiness-recent-comment-widget', 'widget_totalbusiness-recent-portfolio-widget', 'widget_totalbusiness-recent-portfolio2-widget', 'widget_totalbusiness-recent-post-widget', 'widget_totalbusiness-top-rated-post-widget', 'widget_totalbusiness-twitter-widget', 'widget_totalbusiness-video-widget', 'widget_layerslider_widget', 'widget_master-slider-main-widget', 'widget_meta', 'widget_nav_menu', 'widget_otw_widget', 'widget_pages', 'widget_recent-comments', 'widget_recent-posts', 'widget_rss', 'widget_search', 'widget_soundcloud_is_gold_widget', 'widget_tag_cloud', 'widget_text', 'widget_tribe-events-list-widget', 'widget_woocommerce_layered_nav', 'widget_woocommerce_layered_nav_filters', 'widget_woocommerce_price_filter', 'widget_woocommerce_products', 'widget_woocommerce_product_categories', 'widget_woocommerce_product_search', 'widget_woocommerce_product_tag_cloud', 'widget_woocommerce_recently_viewed_products', 'widget_woocommerce_recent_reviews', 'widget_woocommerce_top_rated_products', 'widget_woocommerce_widget_cart', 'widget_wpgmp_google_map_widget');
	// foreach($widget_list as $widget){
	//	$widget_data[$widget] = get_option($widget);
	// }
	//$widget_file = get_template_directory() . '/plugins/goodlayers-importer-widget.txt';
	//$file_stream = @fopen($widget_file, 'w');
	//fwrite($file_stream, serialize($widget_data));
	//fclose($file_stream);	
	
	
?>