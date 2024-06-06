<?php get_header(); ?>
<div class="totalbusiness-content">

	<?php 
		global $totalbusiness_sidebar, $theme_option;
		if( empty($totalbusiness_post_option['sidebar']) || $totalbusiness_post_option['sidebar'] == 'default-sidebar' ){
			$totalbusiness_sidebar = array(
				'type'=>$theme_option['post-sidebar-template'],
				'left-sidebar'=>$theme_option['post-sidebar-left'], 
				'right-sidebar'=>$theme_option['post-sidebar-right']
			); 
		}else{
			$totalbusiness_sidebar = array(
				'type'=>$totalbusiness_post_option['sidebar'],
				'left-sidebar'=>$totalbusiness_post_option['left-sidebar'], 
				'right-sidebar'=>$totalbusiness_post_option['right-sidebar']
			); 				
		}
		$totalbusiness_sidebar = totalbusiness_get_sidebar_class($totalbusiness_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container">
			<div class="with-sidebar-left <?php echo esc_attr($totalbusiness_sidebar['outer']); ?> columns">
				<div class="with-sidebar-content <?php echo esc_attr($totalbusiness_sidebar['center']); ?> columns">
					<div class="totalbusiness-item totalbusiness-blog-full totalbusiness-item-start-content">
					<?php while ( have_posts() ){ the_post(); ?>
			<?php 
												 function get_the_breadcrumb() {
                            global $post;
                            $breadcrumb = '<a href="' . home_url() . '">Home</a>';
                            
                            if (is_single()) {
                                $categories = get_the_category($post->ID);
                                
                                if ($categories) {
                                    $category = $categories[0];
                                    $parents = get_ancestors($category->term_id, 'category');
                                    $parents = array_reverse($parents);
                                    
                                    foreach ($parents as $parent) {
                                        $parent_category = get_category($parent);
                                        $breadcrumb .= ' &raquo; <a href="' . get_category_link($parent_category->term_id) . '">' . $parent_category->name . '</a>';
                                    }
                                    
                                    $breadcrumb .= ' &raquo; <a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                                }
                                
                                $text =   get_the_title();
                                $breadcrumb .= ' &raquo; ' .  mb_strimwidth($text, 0, 20, "...");
                            }
                            
                            echo $breadcrumb;
                        }

// Call the function where you want to display the breadcrumb
                        get_the_breadcrumb();
						?>
					
						
						
						<!-- get the content based on post format -->
						<?php get_template_part('single/content'); ?>
						
						<?php totalbusiness_get_social_shares(); ?>
						
						<nav class="totalbusiness-single-nav">
							<?php previous_post_link('<div class="previous-nav">%link</div>', '<i class="icon-angle-left"></i><span>%title</span>', true); ?>
							<?php next_post_link('<div class="next-nav">%link</div>', '<span>%title</span><i class="icon-angle-right"></i>', true); ?>
							<div class="clear"></div>
						</nav><!-- .nav-single -->

						<!-- abou author section -->
						<?php if($theme_option['single-post-author'] != "disable"){ ?>
							<div class="totalbusiness-post-author">
							<h3 class="post-author-title" ><?php echo esc_html__('About Post Author', 'totalbusiness'); ?></h3>
							<div class="post-author-avartar"><?php echo get_avatar(get_the_author_meta('ID'), 90); ?></div>
							<div class="post-author-content">
							<h4 class="post-author"><?php the_author_posts_link(); ?></h4>
							<?php echo get_the_author_meta('description'); ?>
							</div>
							<div class="clear"></div>
							</div>
						<?php } ?>						

						<?php comments_template( '', true ); ?>		
						
					<?php } ?>
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