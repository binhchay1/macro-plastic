<?php
	/*	
	*	Goodlayers Menu Management File
	*	---------------------------------------------------------------------
	*	This file use to include a necessary script / function for the 
	* 	navigation area
	*	---------------------------------------------------------------------
	*/
	
	// add the custom walker menu script// check for wp5.4
	if( function_exists('apply_shortcodes') ){
		add_action('wp_nav_menu_item_custom_fields', 'totalbusiness_wp_nav_menu_item_custom_fields', 10, 4);
	}else{
		add_filter('wp_edit_nav_menu_walker', 'totalbusiness_add_custom_navigation_fields', 10, 2 );	
	}
	if( !function_exists('totalbusiness_wp_nav_menu_item_custom_fields') ){
		function totalbusiness_wp_nav_menu_item_custom_fields($item_id, $item, $depth, $args){
			?>
			<p class="field-menu-icon description-wide">
				<label for="edit-totalbusiness-menu-icon-<?php echo totalbusiness_escape_content($item_id); ?>">
					<?php esc_html_e( 'Menu Icon', 'totalbusiness' ); ?><br />
					<input type="text" id="edit-totalbusiness-menu-icon-<?php echo totalbusiness_escape_content($item_id); ?>" class="widefat edit-menu-item-attr-title" name="totalbusiness-menu-icon[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->totalbusiness_menu_icon ); ?>" />
				</label>
			</p>						
			<p class="field-menu-mega description">
				<label for="edit-totalbusiness-menu-item-mega-<?php echo totalbusiness_escape_content($item_id); ?>">
					<input type="checkbox" id="edit-totalbusiness-menu-item-mega-<?php echo totalbusiness_escape_content($item_id); ?>" value="mega_menu" name="totalbusiness-menu-item-mega[<?php echo totalbusiness_escape_content($item_id); ?>]"<?php checked($item->totalbusiness_mega_menu, 'mega_menu'); ?> />
					<?php esc_html_e( 'Enable Mega Menu', 'totalbusiness' ); ?>
				</label>
			</p>		
			<p class="field-menu-section description">
				<label for="edit-totalbusiness-menu-item-section-<?php echo totalbusiness_escape_content($item_id); ?>">
					<?php esc_html_e( 'Section Size ( *Only when parent mega menu option is checked )', 'totalbusiness' ); ?><br />
					<select id="edit-totalbusiness-menu-item-section-<?php echo totalbusiness_escape_content($item_id); ?>" name="totalbusiness-menu-item-section[<?php echo totalbusiness_escape_content($item_id); ?>]" >
						<option value="1/1" <?php selected($item->totalbusiness_mega_section, '1/1'); ?> >1/1</option>
						<option value="4/5" <?php selected($item->totalbusiness_mega_section, '4/5'); ?> >4/5</option>
						<option value="3/4" <?php selected($item->totalbusiness_mega_section, '3/4'); ?> >3/4</option>
						<option value="2/3" <?php selected($item->totalbusiness_mega_section, '2/3'); ?> >2/3</option>
						<option value="3/5" <?php selected($item->totalbusiness_mega_section, '3/5'); ?> >3/5</option>
						<option value="1/2" <?php selected($item->totalbusiness_mega_section, '1/2'); ?> >1/2</option>
						<option value="1/3" <?php selected($item->totalbusiness_mega_section, '1/3'); ?> >1/3</option>
						<option value="2/5" <?php selected($item->totalbusiness_mega_section, '2/5'); ?> >2/5</option>
						<option value="1/4" <?php selected($item->totalbusiness_mega_section, '1/4'); ?> >1/4</option>
						<option value="1/5" <?php selected($item->totalbusiness_mega_section, '1/5'); ?> >1/5</option>
					</select>
				</label>
			</p>	
			<?php
		}
	}
	if( !function_exists('totalbusiness_add_custom_navigation_fields') ){
		function totalbusiness_add_custom_navigation_fields($walker, $menu_id){
			return 'totalbusiness_edit_nav_menu_walker';
		}
	}	
	
	// initiate the custom data
	add_filter( 'wp_setup_nav_menu_item', 'totalbusiness_set_custom_navigation_fields' );
	if( !function_exists('totalbusiness_set_custom_navigation_fields') ){
		function totalbusiness_set_custom_navigation_fields($menu_item){
			$menu_item->totalbusiness_menu_icon = get_post_meta($menu_item->ID, '_gdlr_menu_icon', true);
			$menu_item->totalbusiness_mega_menu = get_post_meta($menu_item->ID, '_gdlr_mega_menu_item', true);
			$menu_item->totalbusiness_mega_section = get_post_meta($menu_item->ID, '_gdlr_mega_menu_section', true);
			return $menu_item;
		}
	}
	
	// add stylesheet to hide the menu base on the depth
	add_action( 'load-nav-menus.php', 'totalbusiness_enqueue_admin_navigation_style' );
	if( !function_exists('totalbusiness_enqueue_admin_navigation_style') ){
		function totalbusiness_enqueue_admin_navigation_style(){
			wp_enqueue_style('totalbusiness-nav-style', get_template_directory_uri() . '/include/stylesheet/gdlr-navigation-menu.css');
		}
	}
	
	// add action to save the data
	add_action( 'wp_update_nav_menu_item', 'totalbusiness_save_custom_navigation_fields', 10, 3 );
	if( !function_exists('totalbusiness_save_custom_navigation_fields') ){
		function totalbusiness_save_custom_navigation_fields($menu_id, $menu_item_db_id, $args){
			// menu icon section
			if( isset($_REQUEST['totalbusiness-menu-icon']) && is_array($_REQUEST['totalbusiness-menu-icon']) ){
				$menu_icon_value = $_REQUEST['totalbusiness-menu-icon'][$menu_item_db_id];
				update_post_meta($menu_item_db_id, '_gdlr_menu_icon', $menu_icon_value );				
			}		
		
			// mega menu 
			if( isset($_REQUEST['totalbusiness-menu-item-mega']) && 
				is_array($_REQUEST['totalbusiness-menu-item-mega']) &&
				isset($_REQUEST['totalbusiness-menu-item-mega'][$menu_item_db_id]) ) {
					$mega_menu_value = $_REQUEST['totalbusiness-menu-item-mega'][$menu_item_db_id];
					update_post_meta($menu_item_db_id, '_gdlr_mega_menu_item', $mega_menu_value );
			}else{
				update_post_meta($menu_item_db_id, '_gdlr_mega_menu_item', '' );
			}
			
			// mega menu section
			if( isset($_REQUEST['totalbusiness-menu-item-section']) && is_array($_REQUEST['totalbusiness-menu-item-section']) ){
				$mega_menu_value = $_REQUEST['totalbusiness-menu-item-section'][$menu_item_db_id];
				update_post_meta($menu_item_db_id, '_gdlr_mega_menu_section', $mega_menu_value );				
			}						
		}
	}
	
	// create totalbusiness_edit_nav_menu_walker class to add the custom field
	if( !class_exists('totalbusiness_edit_nav_menu_walker') ){
		
		// from wp-admin/includes/nav-menu.php file
		class totalbusiness_edit_nav_menu_walker extends Walker_Nav_Menu{

			function start_lvl( &$output, $depth = 0, $args = array() ) {}

			function end_lvl( &$output, $depth = 0, $args = array() ) {}		
		
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				global $_wp_nav_menu_max_depth;
				$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				ob_start();
				$item_id = esc_attr( $item->ID );
				$removed_args = array(
					'action',
					'customlink-tab',
					'edit-menu-item',
					'menu-item',
					'page-tab',
					'_wpnonce',
				);

				$original_title = '';
				if ( 'taxonomy' == $item->type ) {
					$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
					if ( is_wp_error( $original_title ) )
						$original_title = false;
				} elseif ( 'post_type' == $item->type ) {
					$original_object = get_post( $item->object_id );
					$original_title = $original_object->post_title;
				}

				$classes = array(
					'menu-item menu-item-depth-' . $depth,
					'menu-item-' . esc_attr( $item->object ),
					'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
				);

				$title = $item->title;

				if ( ! empty( $item->_invalid ) ) {
					$classes[] = 'menu-item-invalid';
					/* translators: %s: title of menu item which is invalid */
					$title = sprintf( esc_html__( '%s (Invalid)', 'totalbusiness' ), $item->title );
				} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
					$classes[] = 'pending';
					/* translators: %s: title of menu item in draft status */
					$title = sprintf( esc_html__('%s (Pending)', 'totalbusiness'), $item->title );
				}

				$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

				$submenu_text = '';
				if ( 0 == $depth )
					$submenu_text = 'style="display: none;"';

				?>
				<li id="menu-item-<?php echo totalbusiness_escape_content($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
					<dl class="menu-item-bar">
						<dt class="menu-item-handle">
							<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo totalbusiness_escape_content($submenu_text); ?>><?php esc_html_e( 'sub item', 'totalbusiness' ); ?></span></span>
							<span class="item-controls">
								<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
								<span class="item-order hide-if-js">
									<a href="<?php
										echo wp_nonce_url(
											add_query_arg(
												array(
													'action' => 'move-up-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
											),
											'move-menu_item'
										);
									?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'totalbusiness'); ?>">&#8593;</abbr></a>
									|
									<a href="<?php
										echo wp_nonce_url(
											add_query_arg(
												array(
													'action' => 'move-down-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
											),
											'move-menu_item'
										);
									?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'totalbusiness'); ?>">&#8595;</abbr></a>
								</span>
								<a class="item-edit" id="edit-<?php echo totalbusiness_escape_content($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item', 'totalbusiness'); ?>" href="<?php
									echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
								?>"><?php esc_html_e( 'Edit Menu Item', 'totalbusiness' ); ?></a>
							</span>
						</dt>
					</dl>

					<div class="menu-item-settings" id="menu-item-settings-<?php echo totalbusiness_escape_content($item_id); ?>">
						<?php if( 'custom' == $item->type ) : ?>
							<p class="field-url description description-wide">
								<label for="edit-menu-item-url-<?php echo totalbusiness_escape_content($item_id); ?>">
									<?php esc_html_e( 'URL', 'totalbusiness' ); ?><br />
									<input type="text" id="edit-menu-item-url-<?php echo totalbusiness_escape_content($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
								</label>
							</p>
						<?php endif; ?>
						<p class="description description-thin">
							<label for="edit-menu-item-title-<?php echo totalbusiness_escape_content($item_id); ?>">
								<?php esc_html_e( 'Navigation Label', 'totalbusiness' ); ?><br />
								<input type="text" id="edit-menu-item-title-<?php echo totalbusiness_escape_content($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
							</label>
						</p>
						<p class="description description-thin">
							<label for="edit-menu-item-attr-title-<?php echo totalbusiness_escape_content($item_id); ?>">
								<?php esc_html_e( 'Title Attribute', 'totalbusiness' ); ?><br />
								<input type="text" id="edit-menu-item-attr-title-<?php echo totalbusiness_escape_content($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
							</label>
						</p>
						<!-- new fields insertion starts here -->
						<p class="field-menu-icon description-wide">
							<label for="edit-totalbusiness-menu-icon-<?php echo totalbusiness_escape_content($item_id); ?>">
								<?php esc_html_e( 'Menu Icon', 'totalbusiness' ); ?><br />
								<input type="text" id="edit-totalbusiness-menu-icon-<?php echo totalbusiness_escape_content($item_id); ?>" class="widefat edit-menu-item-attr-title" name="totalbusiness-menu-icon[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->totalbusiness_menu_icon ); ?>" />
							</label>
						</p>						
						<p class="field-menu-mega description">
							<label for="edit-totalbusiness-menu-item-mega-<?php echo totalbusiness_escape_content($item_id); ?>">
								<input type="checkbox" id="edit-totalbusiness-menu-item-mega-<?php echo totalbusiness_escape_content($item_id); ?>" value="mega_menu" name="totalbusiness-menu-item-mega[<?php echo totalbusiness_escape_content($item_id); ?>]"<?php checked($item->totalbusiness_mega_menu, 'mega_menu'); ?> />
								<?php esc_html_e( 'Enable Mega Menu', 'totalbusiness' ); ?>
							</label>
						</p>		
						<p class="field-menu-section description">
							<label for="edit-totalbusiness-menu-item-section-<?php echo totalbusiness_escape_content($item_id); ?>">
								<?php esc_html_e( 'Section Size ( *Only when parent mega menu option is checked )', 'totalbusiness' ); ?><br />
								<select id="edit-totalbusiness-menu-item-section-<?php echo totalbusiness_escape_content($item_id); ?>" name="totalbusiness-menu-item-section[<?php echo totalbusiness_escape_content($item_id); ?>]" >
									<option value="1/1" <?php selected($item->totalbusiness_mega_section, '1/1'); ?> >1/1</option>
									<option value="4/5" <?php selected($item->totalbusiness_mega_section, '4/5'); ?> >4/5</option>
									<option value="3/4" <?php selected($item->totalbusiness_mega_section, '3/4'); ?> >3/4</option>
									<option value="2/3" <?php selected($item->totalbusiness_mega_section, '2/3'); ?> >2/3</option>
									<option value="3/5" <?php selected($item->totalbusiness_mega_section, '3/5'); ?> >3/5</option>
									<option value="1/2" <?php selected($item->totalbusiness_mega_section, '1/2'); ?> >1/2</option>
									<option value="1/3" <?php selected($item->totalbusiness_mega_section, '1/3'); ?> >1/3</option>
									<option value="2/5" <?php selected($item->totalbusiness_mega_section, '2/5'); ?> >2/5</option>
									<option value="1/4" <?php selected($item->totalbusiness_mega_section, '1/4'); ?> >1/4</option>
									<option value="1/5" <?php selected($item->totalbusiness_mega_section, '1/5'); ?> >1/5</option>
								</select>
							</label>
						</p>							
						<!-- new fields insertion ends here -->							
						<p class="field-link-target description">
							<label for="edit-menu-item-target-<?php echo totalbusiness_escape_content($item_id); ?>">
								<input type="checkbox" id="edit-menu-item-target-<?php echo totalbusiness_escape_content($item_id); ?>" value="_blank" name="menu-item-target[<?php echo totalbusiness_escape_content($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
								<?php esc_html_e( 'Open link in a new window/tab', 'totalbusiness' ); ?>
							</label>
						</p>
						<p class="field-css-classes description description-thin">
							<label for="edit-menu-item-classes-<?php echo totalbusiness_escape_content($item_id); ?>">
								<?php esc_html_e( 'CSS Classes (optional)', 'totalbusiness' ); ?><br />
								<input type="text" id="edit-menu-item-classes-<?php echo totalbusiness_escape_content($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
							</label>
						</p>
						<p class="field-xfn description description-thin">
							<label for="edit-menu-item-xfn-<?php echo totalbusiness_escape_content($item_id); ?>">
								<?php esc_html_e( 'Link Relationship (XFN)', 'totalbusiness' ); ?><br />
								<input type="text" id="edit-menu-item-xfn-<?php echo totalbusiness_escape_content($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
							</label>
						</p>
						<p class="field-description description description-wide">
							<label for="edit-menu-item-description-<?php echo totalbusiness_escape_content($item_id); ?>">
								<?php esc_html_e( 'Description', 'totalbusiness' ); ?><br />
								<textarea id="edit-menu-item-description-<?php echo totalbusiness_escape_content($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo totalbusiness_escape_content($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
								<span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.', 'totalbusiness'); ?></span>
							</label>
						</p>
						
						<p class="field-move hide-if-no-js description description-wide">
							<label>
								<span><?php esc_html_e( 'Move', 'totalbusiness' ); ?></span>
								<a href="#" class="menus-move-up"><?php esc_html_e( 'Up one', 'totalbusiness' ); ?></a>
								<a href="#" class="menus-move-down"><?php esc_html_e( 'Down one', 'totalbusiness' ); ?></a>
								<a href="#" class="menus-move-left"></a>
								<a href="#" class="menus-move-right"></a>
								<a href="#" class="menus-move-top"><?php esc_html_e( 'To the top', 'totalbusiness' ); ?></a>
							</label>
						</p>

						<div class="menu-item-actions description-wide submitbox">
							<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
								<p class="link-to-original">
									<?php printf( esc_html__('Original: %s', 'totalbusiness'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
								</p>
							<?php endif; ?>
							<a class="item-delete submitdelete deletion" id="delete-<?php echo totalbusiness_escape_content($item_id); ?>" href="<?php
							echo wp_nonce_url(
								add_query_arg(
									array(
										'action' => 'delete-menu-item',
										'menu-item' => $item_id,
									),
									admin_url( 'nav-menus.php' )
								),
								'delete-menu_item_' . $item_id
							); ?>"><?php esc_html_e( 'Remove', 'totalbusiness' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo totalbusiness_escape_content($item_id); ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
								?>#menu-item-settings-<?php echo totalbusiness_escape_content($item_id); ?>"><?php esc_html_e('Cancel', 'totalbusiness'); ?></a>
						</div>

						<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo totalbusiness_escape_content($item_id); ?>" />
						<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
						<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
						<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
						<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
						<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo totalbusiness_escape_content($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
					</div><!-- .menu-item-settings-->
					<ul class="menu-item-transport"></ul>
				<?php
				$output .= ob_get_clean();
			}
			
		}
		
	}
	
	// add action to enqueue superfish menu
	add_filter('totalbusiness_enqueue_scripts', 'totalbusiness_regiser_superfish');
	if( !function_exists('totalbusiness_regiser_superfish') ){
		function totalbusiness_regiser_superfish($array){	
			$array['style']['superfish'] = get_template_directory_uri() . '/plugins/superfish/css/superfish.css';
			
			$array['script']['superfish'] = get_template_directory_uri() . '/plugins/superfish/js/superfish.js';
			$array['script']['hoverIntent'] = get_template_directory_uri() . '/plugins/superfish/js/hoverIntent.js';
			
			return $array;
		}
	}
	
	// creating the class for outputing the custom navigation menu
	if( !class_exists('totalbusiness_menu_walker') ){
		
		// from wp-includes/nav-menu-template.php file
		class totalbusiness_menu_walker extends Walker_Nav_Menu{		

			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				$class_names = $value = $data_column = $data_size = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;

				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				if( $depth == 0 ){ 
					$class_names .= (empty($item->totalbusiness_mega_menu))? $class_names . ' totalbusiness-normal-menu': $class_names . ' totalbusiness-mega-menu';
				}else if( $depth == 1 && get_post_meta($item->menu_item_parent, '_gdlr_mega_menu_item', true) == 'mega_menu'){
					$data_size .= ' data-size="' . $item->totalbusiness_mega_section . '"';
					$data_column .= ' data-column="' . totalbusiness_get_column_class($item->totalbusiness_mega_section) . '"';
				}
				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li ' . $id . $value . $class_names . $data_column . $data_size .'>';
				
				$atts = array();
				$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
				$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
				$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
				$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
				$atts['class']  = ! empty( $args->walker->has_children )? 'sf-with-ul-pre' : '';
				
				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= empty($item->totalbusiness_menu_icon)? '': '<i class="fa ' . totalbusiness_fa_class($item->totalbusiness_menu_icon) . '"></i>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= '</a>';
				$item_output .= $args->after;
				
				$item_output .= (empty($item->totalbusiness_mega_menu) || $depth != 0)? '' : '<div class="sf-mega">'; // totalbusiness-modify
				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
			
			function end_el( &$output, $item, $depth = 0, $args = array() ) {
				$output .= (empty($item->totalbusiness_mega_menu) || $depth != 0)? '' : '</div>'; // totalbusiness-modify
				$output .= "</li>\n";
			}

		}
		
	}

?>