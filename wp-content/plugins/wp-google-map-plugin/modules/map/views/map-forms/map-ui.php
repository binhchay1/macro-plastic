<?php
/**
 * Contro Positioning over google maps.
 *
 * @package Maps
 * @author Flipper Code <hello@flippercode.com>
 */


$form->add_element(
	'group',
	'map_theme_setting',
	array(
		'value'  => esc_html__('Map Theme Settings', 'wpgmp-google-map').WPGMP_PREMIUM_LINK,
		'before' => '<div class="fc-12">',
		'after'  => '</div>',
		'parent_class'		=> 'fc-locked',
	)
);

