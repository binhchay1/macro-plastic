<?php 
	while ( have_posts() ){ the_post();
		$content = totalbusiness_content_filter(get_the_content(), true); 
		if(!empty($content)){
			?>
			<div class="main-content-container container totalbusiness-item-start-content">
				<div class="totalbusiness-item totalbusiness-main-content">
					<?php echo totalbusiness_escape_content($content); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}
	} 
?>