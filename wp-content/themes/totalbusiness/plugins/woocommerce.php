<?php
	/*	
	*	Goodlayers Woocommerce Support File
	*/
	
	add_theme_support( 'woocommerce' );
	
	if(!function_exists('totalbusiness_get_woocommerce_nav')){
		function totalbusiness_get_woocommerce_nav( $icon_style = 'dark' ){
			global $woocommerce;
			if(!empty($woocommerce)){
?>	
<div class="totalbusiness-top-woocommerce-wrapper">
	<div class="totalbusiness-top-woocommerce-button">
		<?php echo '<span class="totalbusiness-cart-item-count">' . $woocommerce->cart->cart_contents_count . '</span>'; ?>
		<img src="<?php echo get_template_directory_uri() . '/images/cart-' . $icon_style . '.png'; ?>" alt="" width="83" height="71" />
	</div>
	<div class="totalbusiness-top-woocommerce">
	<div class="totalbusiness-top-woocommerce-inner">
		<?php 
			echo '<div class="totalbusiness-cart-count" >';
			echo '<span class="head">' . esc_html__('Items : ', 'totalbusiness') . ' </span>';
			echo '<span class="totalbusiness-cart-item-count">' . $woocommerce->cart->cart_contents_count . '</span>'; 
			echo '</div>';
			
			echo '<div class="totalbusiness-cart-amount" >';
			echo '<span class="head">' . esc_html__('Subtotal :', 'totalbusiness') . ' </span>';
			echo '<span class="totalbusiness-cart-sum-amount">' . $woocommerce->cart->get_cart_total() . '</span>';
			echo '</div>';
		?>
		<a class="totalbusiness-cart-button" href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" >
			<?php echo esc_html__('View Cart', 'totalbusiness'); ?>
		</a>
		<a class="totalbusiness-checkout-button" href="<?php echo esc_url($woocommerce->cart->get_checkout_url()); ?>" >
			<?php echo esc_html__('Check Out', 'totalbusiness'); ?>
		</a>
	</div>
	</div>
</div>
<?php		
			}
		}
	}
	
	// filter for ajax content
	add_filter('woocommerce_add_to_cart_fragments', 'totalbusiness_woocommerce_cart_ajax');
	function totalbusiness_woocommerce_cart_ajax( $fragments ) {
		global $woocommerce;
		
		ob_start();
		$fragments['span.totalbusiness-cart-item-count'] = '<span class="totalbusiness-cart-item-count">' . $woocommerce->cart->cart_contents_count . '</span>'; 
		$fragments['span.totalbusiness-cart-sum-amount'] = '<span class="totalbusiness-cart-sum-amount">' . $woocommerce->cart->get_cart_total() . '</span>';
		ob_end_clean();
		
		return $fragments;
	}	
	
	// Change number or products per row to 3
	add_filter('loop_shop_columns', 'totalbusiness_woo_loop_columns');
	if (!function_exists('totalbusiness_woo_loop_columns')) {
		function totalbusiness_woo_loop_columns() {
			global $theme_option;
			return empty($theme_option['all-products-per-row'])? 3: $theme_option['all-products-per-row'];
		}
	}
	add_filter('post_class', 'totalbusiness_woo_column_class');
	if (!function_exists('totalbusiness_woo_column_class')) {
		function totalbusiness_woo_column_class($classes) {
			global $theme_option;
			$item_per_row = empty($theme_option['all-products-per-row'])? 3: $theme_option['all-products-per-row'];
			
			if( is_archive() && get_post_type() == 'product'){
				switch($item_per_row){
					case 1: $classes[] = 'totalbusiness-1-product-per-row'; break;
					case 2: $classes[] = 'totalbusiness-2-product-per-row'; break;
					case 3: $classes[] = 'totalbusiness-3-product-per-row'; break;
					case 4: $classes[] = 'totalbusiness-4-product-per-row'; break;
					case 5: $classes[] = 'totalbusiness-5-product-per-row'; break;
				}
			}
			return $classes;
		}
	}	
	
	// add action to enqueue woocommerce style
	add_filter('totalbusiness_enqueue_scripts', 'totalbusiness_regiser_woo_style');
	if( !function_exists('totalbusiness_regiser_woo_style') ){
		function totalbusiness_regiser_woo_style($array){	
			global $woocommerce;
			if( !empty($woocommerce) ){
				$array['style']['totalbusiness-woo-style'] = get_template_directory_uri() . '/stylesheet/gdlr-woocommerce.css';
			}
			return $array;
		}
	}
	
?>