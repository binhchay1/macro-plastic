<?php get_header(); ?>

	<div class="totalbusiness-content">

		<!-- Above Sidebar Section-->
		<?php global $totalbusiness_post_option, $above_sidebar_content, $with_sidebar_content, $below_sidebar_content; ?>
		<?php if(!empty($above_sidebar_content)){ ?>
			<div class="above-sidebar-wrapper"><?php totalbusiness_print_page_builder($above_sidebar_content); ?></div>
		<?php } ?>
		
		<!-- Sidebar With Content Section-->
		<?php 
			if( !empty($totalbusiness_post_option['sidebar']) && ($totalbusiness_post_option['sidebar'] != 'no-sidebar' )){
				global $totalbusiness_sidebar;
				
				$totalbusiness_sidebar = array(
					'type'=>$totalbusiness_post_option['sidebar'],
					'left-sidebar'=>$totalbusiness_post_option['left-sidebar'], 
					'right-sidebar'=>$totalbusiness_post_option['right-sidebar']
				); 
				$totalbusiness_sidebar = totalbusiness_get_sidebar_class($totalbusiness_sidebar);
		?>
			<div class="with-sidebar-wrapper">
				<div class="with-sidebar-container container">
					<div class="with-sidebar-left <?php echo esc_attr($totalbusiness_sidebar['outer']); ?> columns">
						<div class="with-sidebar-content <?php echo esc_attr($totalbusiness_sidebar['center']); ?> columns">
							<?php 
								if( !empty($with_sidebar_content) ){
									totalbusiness_print_page_builder($with_sidebar_content, false);
								}
								if( !empty($totalbusiness_post_option['show-content']) && $totalbusiness_post_option['show-content'] != 'disable' ){
									get_template_part('single/content', 'page');
								}
							?>							
						</div>
						<?php get_sidebar('left'); ?>
						<div class="clear"></div>
					</div>
					<?php get_sidebar('right'); ?>
					<div class="clear"></div>
				</div>				
			</div>				
		<?php 
			}else{ 
				if( !empty($with_sidebar_content) ){ 
					echo '<div class="with-sidebar-wrapper">';
					totalbusiness_print_page_builder($with_sidebar_content);
					echo '</div>';
				}
				if( empty($totalbusiness_post_option['show-content']) || $totalbusiness_post_option['show-content'] != 'disable' ){
					get_template_part('single/content', 'page');
				}
			} 
		?>

		<!-- Below Sidebar Section-->
		<?php if(!empty($below_sidebar_content)){ ?>
			<div class="below-sidebar-wrapper"><?php totalbusiness_print_page_builder($below_sidebar_content); ?></div>
		<?php } ?>

		<?php 
			if( comments_open() ){
				echo '<div class="comments-container container" ><div class="comment-item totalbusiness-item">';
				comments_template( '', true ); 
				echo '</div></div>';
			}
		?>
		
	</div><!-- totalbusiness-content -->
<?php get_footer(); ?>