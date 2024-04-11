<?php
/**
 * A template for calling the left sidebar on everypage
 */
 
	global $totalbusiness_sidebar;
?>

<?php if( $totalbusiness_sidebar['type'] == 'left-sidebar' || $totalbusiness_sidebar['type'] == 'both-sidebar' ){ ?>
<div class="totalbusiness-sidebar totalbusiness-left-sidebar <?php echo esc_attr($totalbusiness_sidebar['left']); ?> columns">
	<div class="totalbusiness-item-start-content sidebar-left-item" >
	<?php 
		$sidebar_id = totalbusiness_get_sidebar_id($totalbusiness_sidebar['left-sidebar']);
		if( is_active_sidebar($sidebar_id) ){ 
			dynamic_sidebar($sidebar_id); 
		}
	?>
	</div>
</div>
<?php } ?>