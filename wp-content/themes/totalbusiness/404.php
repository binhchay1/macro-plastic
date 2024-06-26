<?php
/**
 * The template for displaying 404 pages (Not Found).
 */

get_header(); ?>

	<div class="page-not-found-container container">
		<div class="totalbusiness-item page-not-found-item">
			<div class="page-not-found-block" >
				<div class="page-not-found-icon">
					<i class="fa fa-frown-o"></i>
				</div>
				<div class="page-not-found-title">
					<?php esc_html_e('Error 404', 'totalbusiness'); ?>
				</div>
				<div class="page-not-found-caption">
					<?php esc_html_e('Sorry, we couldn\'t find the page you\'re looking for.', 'totalbusiness'); ?>
				</div>
				<div class="page-not-found-search">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>