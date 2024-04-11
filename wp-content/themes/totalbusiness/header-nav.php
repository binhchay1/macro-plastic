<?php 
	global $theme_option;

	echo '<div class="totalbusiness-navigation-wrapper">';

	// navigation
	if( has_nav_menu('main_menu') ){
		if( class_exists('totalbusiness_menu_walker') ){
			echo '<nav class="totalbusiness-navigation" id="totalbusiness-main-navigation" >';
			wp_nav_menu( array(
				'theme_location'=>'main_menu', 
				'container'=> '', 
				'menu_class'=> 'sf-menu totalbusiness-main-menu',
				'walker'=> new totalbusiness_menu_walker() 
			) );
		}else{
			echo '<nav class="totalbusiness-navigation" role="navigation">';
			wp_nav_menu( array('theme_location'=>'main_menu') );
		}
		
		$icon_style = empty($theme_option['bucket-color'])? 'dark': $theme_option['bucket-color'];
?>
<img id="totalbusiness-menu-search-button" src="<?php echo get_template_directory_uri() . '/images/magnifier-' . $icon_style . '.png'; ?>" alt="" width="58" height="59" />
<div class="totalbusiness-menu-search" id="totalbusiness-menu-search">
	<form method="get" id="searchform" action="<?php  echo esc_url(home_url('/')); ?>/">
		<?php
			$search_val = get_search_query();
			if( empty($search_val) ){
				$search_val = esc_html__("Type Keywords" , "totalbusiness");
			}
		?>
		<div class="search-text">
			<input type="text" value="<?php echo esc_attr($search_val); ?>" name="s" autocomplete="off" data-default="<?php echo esc_attr($search_val); ?>" />
		</div>
		<input type="submit" value="" />
		<div class="clear"></div>
	</form>	
</div>		
<?php		
		totalbusiness_get_woocommerce_nav($icon_style);
		echo '</nav>'; // totalbusiness-navigation
	}

	echo '<div class="clear"></div>';
	echo '</div>'; // totalbusiness-navigation-wrapper
?>