<?php
/**
 * A template for calling the right sidebar in everypage
 */
 
	global $totalbusiness_sidebar;
?>

<?php if( $totalbusiness_sidebar['type'] == 'right-sidebar' || $totalbusiness_sidebar['type'] == 'both-sidebar' ){ ?>
<div class="totalbusiness-sidebar totalbusiness-right-sidebar <?php echo esc_attr($totalbusiness_sidebar['right']); ?> columns">
	<div class="totalbusiness-item-start-content sidebar-right-item" >
	<?php 
		$sidebar_id = totalbusiness_get_sidebar_id($totalbusiness_sidebar['right-sidebar']);
		if( is_active_sidebar($sidebar_id) ){ 
			dynamic_sidebar($sidebar_id); 
		}
	?>
	</div>
</div>
<?php } ?>