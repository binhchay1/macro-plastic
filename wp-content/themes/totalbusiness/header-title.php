<?php
/*
 * The template for displaying a header title section
 */
global $theme_option, $totalbusiness_post_option, $header_style;
$header_background = '';
if (!empty($totalbusiness_post_option['header-background'])) {
	if (is_numeric($totalbusiness_post_option['header-background'])) {
		$image_src = wp_get_attachment_image_src($totalbusiness_post_option['header-background'], 'full');
		$header_background = $image_src[0];
	} else {
		$header_background = $totalbusiness_post_option['header-background'];
	}
}
?>

<?php if (is_front_page() && !is_page()) { ?>
	<div class="totalbusiness-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>">
		<div class="totalbusiness-page-title-overlay"></div>
		<div class="totalbusiness-page-title-container container">
			<h1 class="totalbusiness-page-title"><?php esc_html_e('Home', 'totalbusiness') ?></h1>
		</div>
	</div>

<?php } else if (is_page() && (empty($totalbusiness_post_option['show-title']) || $totalbusiness_post_option['show-title'] != 'disable')) { ?>
	<?php $page_background = ''; ?>

	<div class="totalbusiness-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background) ? '' : 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?>>
		<div class="totalbusiness-page-title-overlay"></div>
		<div class="totalbusiness-page-title-container container">
			<h1 class="totalbusiness-page-title"><?php the_title(); ?></h1>
			<?php if (!empty($totalbusiness_post_option['page-caption'])) { ?>
				<span class="totalbusiness-page-caption"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($totalbusiness_post_option['page-caption'])); ?></span>
			<?php } ?>
		</div>
	</div>
<?php } else if (is_single() && $post->post_type == 'post') {

	if (!empty($totalbusiness_post_option['page-title'])) {
		$page_title = $totalbusiness_post_option['page-title'];
		$page_caption = $totalbusiness_post_option['page-caption'];
	} else {
		$page_title = $theme_option['post-title'];
		$page_caption = $theme_option['post-caption'];
	}

	$category_detail = get_the_category(get_the_ID());
	$cat_id = $category_detail[0]->term_id;
	$id_option = 'z_taxonomy_image' . $cat_id;
?>
	<?php if (get_option($id_option) != '') { ?>
		<?php var_dump(1); ?>
		<div class="totalbusiness-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo 'style="background-image: url(\'' . esc_url(get_option($id_option)) . '\'); "'; ?>>
			<div class="totalbusiness-page-title-overlay"></div>
			<div class="totalbusiness-page-title-container container">
				<h3 class="totalbusiness-page-title"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($page_title)); ?></h3>
				<?php if (!empty($page_caption)) { ?>
					<span class="totalbusiness-page-caption"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($page_caption)); ?></span>
				<?php } ?>
			</div>
		</div>
	<?php } else { ?>
		<div class="totalbusiness-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background) ? '' : 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?>>
			<div class="totalbusiness-page-title-overlay"></div>
			<div class="totalbusiness-page-title-container container">
				<h3 class="totalbusiness-page-title"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($page_title)); ?></h3>
				<?php if (!empty($page_caption)) { ?>
					<span class="totalbusiness-page-caption"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($page_caption)); ?></span>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
<?php } else if (is_single()) { // for custom post type

	$page_title = get_the_title();
	if (!empty($totalbusiness_post_option) && !empty($totalbusiness_post_option['page-caption'])) {
		$page_caption = $totalbusiness_post_option['page-caption'];
	} else if ($post->post_type == 'portfolio' && !empty($theme_option['page-caption'])) {
		$page_caption = $theme_option['portfolio-caption'];
	}
?>
	<div class="totalbusiness-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background) ? '' : 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?>>
		<div class="totalbusiness-page-title-overlay"></div>
		<div class="totalbusiness-page-title-container container">
			<h1 class="totalbusiness-page-title"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($page_title)); ?></h1>
			<?php if (!empty($page_caption)) { ?>
				<span class="totalbusiness-page-caption"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($page_caption)); ?></span>
			<?php } ?>
		</div>
	</div>
<?php } else if (is_404()) { ?>
	<div class="totalbusiness-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background) ? '' : 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?>>
		<div class="totalbusiness-page-title-overlay"></div>
		<div class="totalbusiness-page-title-container container">
			<h1 class="totalbusiness-page-title"><?php esc_html_e('404', 'totalbusiness'); ?></h1>
			<span class="totalbusiness-page-caption"><?php esc_html_e('Page not found', 'totalbusiness'); ?></span>
		</div>
	</div>
<?php } else if (is_archive() || is_search()) {
	if (is_search()) {
		$title = esc_html__('Search Results', 'totalbusiness');
		$caption = get_search_query();
	} else if (is_category() || is_tax('portfolio_category') || is_tax('product_cat')) {
		$title = esc_html__('Category', 'totalbusiness');
		$caption = single_cat_title('', false);
	} else if (is_tag() || is_tax('portfolio_tag') || is_tax('product_tag')) {
		$title = esc_html__('Tag', 'totalbusiness');
		$caption = single_cat_title('', false);
	} else if (is_day()) {
		$title = esc_html__('Day', 'totalbusiness');
		$caption = get_the_date('F j, Y');
	} else if (is_month()) {
		$title = esc_html__('Month', 'totalbusiness');
		$caption = get_the_date('F Y');
	} else if (is_year()) {
		$title = esc_html__('Year', 'totalbusiness');
		$caption = get_the_date('Y');
	} else if (is_author()) {
		$title = esc_html__('By', 'totalbusiness');

		$author_id = get_query_var('author');
		$author = get_user_by('id', $author_id);
		$caption = $author->display_name;
	} else if (is_post_type_archive('product')) {
		$title = esc_html__('Shop', 'totalbusiness');
		$caption = '';
	} else if (is_post_type_archive('portfolio')) {
		$title = esc_html__('Porfolio', 'totalbusiness');
		$caption = '';
	} else {
		$title = get_the_title();
		$caption = '';
	}
?>
	<?php
	$category = get_category(get_query_var('cat'));
	$cat_id = $category->cat_ID;
	$id_option = 'z_taxonomy_image' . $cat_id ?>
	<?php if (get_option($id_option) != '') { ?>
		<div class="totalbusiness-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo 'style="background-image: url(\'' . esc_url(get_option($id_option)) . '\'); "'; ?>>
			<div class="totalbusiness-page-title-overlay"></div>
			<div class="totalbusiness-page-title-container container">
				<span class="totalbusiness-page-title"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($title)); ?></span>
				<?php if (!empty($caption)) { ?>
					<h1 class="totalbusiness-page-caption"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($caption)); ?></h1>
				<?php } ?>
			</div>
		</div>
	<?php } else { ?>
		<div class="totalbusiness-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background) ? '' : 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?>>
			<div class="totalbusiness-page-title-overlay"></div>
			<div class="totalbusiness-page-title-container container">
				<span class="totalbusiness-page-title"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($title)); ?></span>
				<?php if (!empty($caption)) { ?>
					<h1 class="totalbusiness-page-caption"><?php echo totalbusiness_text_filter(totalbusiness_escape_string($caption)); ?></h1>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
<?php } ?>
<!-- is search -->