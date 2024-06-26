<?php
/**
 * Plugin Name: Goodlayers Flickr Widget
 * Plugin URI: http://goodlayers.com/
 * Description: A widget that show flickr image.
 * Version: 1.0
 * Author: Goodlayers
 * Author URI: http://www.goodlayers.com
 *
 */

add_action( 'widgets_init', 'totalbusiness_flickr_widget' );
if( !function_exists('totalbusiness_flickr_widget') ){
	function totalbusiness_flickr_widget() {
		register_widget( 'Goodlayers_Flickr_Widget' );
	}
}

if( !class_exists('Goodlayers_Flickr_Widget') ){
	class Goodlayers_Flickr_Widget extends WP_Widget{

		// Initialize the widget
		function __construct() {
			parent::__construct(
				'gdlr-flickr-widget', 
				esc_html__('Goodlayers Flickr Widget','totalbusiness'), 
				array('description' => esc_html__('A widget that show image from flickr', 'totalbusiness')));  
		}

		// Output of the widget
		function widget( $args, $instance ) {
			global $theme_option;	
				
			$title = apply_filters( 'widget_title', $instance['title'] );
			$id = $instance['id'];
			$num_fetch = $instance['num_fetch'];
			$orderby = $instance['orderby'];
			
			// Opening of widget
			echo totalbusiness_escape_content($args['before_widget']);
			
			// Open of title tag
			if( !empty($title) ){ 
				echo totalbusiness_escape_content($args['before_title'] . $title . $args['after_title']); 
			}
				
			// Widget Content
			if(!empty($id)){ 
				$flickr  = '?count=' . $num_fetch;
				$flickr .= '&amp;display=' . $orderby;
				$flickr .= '&amp;user=' . $id;
				$flickr .= '&amp;size=s&amp;layout=x&amp;source=user';
				?>
					<div class="totalbusiness-flickr-widget">
					<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne<?php echo totalbusiness_escape_content($flickr); ?>"></script>
					<div class="clear"></div>
					</div>
				<?php
			}
					
			// Closing of widget
			echo totalbusiness_escape_content($args['after_widget']);	
		}

		// Widget Form
		function form( $instance ) {
			$title = isset($instance['title'])? $instance['title']: '';
			$id = isset($instance['id'])? $instance['id']: '';
			$num_fetch = isset($instance['num_fetch'])? $instance['num_fetch']: 6;
			$orderby = isset($instance['orderby'])? $instance['orderby']: 'latest';
			
			?>

			<!-- Text Input -->
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title :', 'totalbusiness'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo totalbusiness_escape_content($title); ?>" />
			</p>	
			
			<!-- ID --> 
			<p>
				<label for="<?php echo $this->get_field_id('id'); ?>"><?php esc_html_e('Flickr ID :', 'totalbusiness'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo totalbusiness_escape_content($id); ?>" />
			</p>			

			<!-- Show Num --> 
			<p>
				<label for="<?php echo $this->get_field_id('num_fetch'); ?>"><?php esc_html_e('Num Fetch :', 'totalbusiness'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('num_fetch'); ?>" name="<?php echo $this->get_field_name('num_fetch'); ?>" type="text" value="<?php echo totalbusiness_escape_content($num_fetch); ?>" />
			</p>			

			<!-- Order By -->
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php esc_html_e('Order By :', 'totalbusiness'); ?></label>		
				<select class="widefat" name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>">
					<option value="latest" <?php if(empty($orderby) || $orderby == 'latest') echo ' selected '; ?>><?php esc_html_e('Latest', 'totalbusiness') ?></option>
					<option value="random" <?php if($orderby == 'random') echo ' selected '; ?>><?php esc_html_e('Random', 'totalbusiness') ?></option>				
				</select> 
			</p>
				


		<?php
		}
		
		// Update the widget
		function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = (empty($new_instance['title']))? '': strip_tags($new_instance['title']);
			$instance['id'] = (empty($new_instance['id']))? '': strip_tags($new_instance['id']);
			$instance['num_fetch'] = (empty($new_instance['num_fetch']))? '': strip_tags($new_instance['num_fetch']);
			$instance['orderby'] = (empty($new_instance['orderby']))? '': strip_tags($new_instance['orderby']);

			return $instance;
		}	
	}
}
?>