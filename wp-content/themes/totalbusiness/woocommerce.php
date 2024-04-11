<?php get_header(); ?>
	<div class="totalbusiness-content">

		<?php 
				global $totalbusiness_sidebar, $theme_option;
				$woo_page = (is_product())? 'single': 'all';
				
				$totalbusiness_sidebar = array(
					'type'=>$theme_option[$woo_page . '-products-sidebar'],
					'left-sidebar'=>$theme_option[$woo_page . '-products-sidebar-left'], 
					'right-sidebar'=>$theme_option[$woo_page . '-products-sidebar-right']
				); 
				$totalbusiness_sidebar = totalbusiness_get_sidebar_class($totalbusiness_sidebar);
		?>
		<div class="with-sidebar-wrapper">
			<div class="with-sidebar-container container">
				<div class="with-sidebar-left <?php echo esc_attr($totalbusiness_sidebar['outer']); ?> columns">
					<div class="with-sidebar-content <?php echo esc_attr($totalbusiness_sidebar['center']); ?> columns totalbusiness-item-start-content">
						<div class="totalbusiness-item woocommerce-content-item">
							<div class="woocommerce-breadcrumbs">
							<?php woocommerce_breadcrumb(); ?>
							</div>
				
							<div class="woocommerce-content">
							<?php woocommerce_content(); ?>
							</div>				
						</div>				
					</div>
					<?php get_sidebar('left'); ?>
					<div class="clear"></div>
				</div>
				<?php get_sidebar('right'); ?>
				<div class="clear"></div>
			</div>				
		</div>				
	</div><!-- totalbusiness-content -->
<?php get_footer(); ?>