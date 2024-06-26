<?php
	/*	
	*	Goodlayers Admin Panel
	*	---------------------------------------------------------------------
	*	This file create the class that help you create the controls admin  
	*	option for custom theme
	*	---------------------------------------------------------------------
	*/	
	
	if( !class_exists('totalbusiness_admin_option_html') ){
		
		class totalbusiness_admin_option_html{
			
			// decide to generate each option by type
			function generate_admin_option($settings = array()){
				echo '<div class="totalbusiness-option-wrapper ';
				echo (isset($settings['wrapper-class']))? $settings['wrapper-class'] : '';
				echo '">';
				
				$description_class = empty($settings['description'])? '': 'with-description';
				echo '<div class="totalbusiness-option ' . $description_class . '">';
				
				// option title
				if( !empty($settings['title']) ){
					echo '<div class="totalbusiness-option-title">' . $settings['title'] . '</div>';
				}
				
				// input option
				switch ($settings['type']){
					case 'text': $this->print_text_input($settings); break;
					case 'textarea': $this->print_textarea($settings); break;
					case 'combobox': $this->print_combobox($settings); break;
					case 'font-combobox': $this->print_font_combobox($settings); break;
					case 'multi-combobox': $this->print_multi_combobox($settings); break;
					case 'checkbox': $this->print_checkbox($settings); break;
					case 'radioimage': $this->print_radio_image($settings); break;
					case 'colorpicker': $this->print_color_picker($settings); break;
					case 'skin-settings': $this->print_skin_settings($settings); break;
					case 'sliderbar': $this->print_slider_bar($settings); break;
					case 'slider': $this->print_slider($settings); break;
					case 'upload': $this->print_upload_box($settings); break;
					case 'uploadfont': $this->print_upload_font($settings); break;
					case 'custom': $this->print_custom_option($settings); break;
					case 'date-picker': $this->print_date_picker($settings); break;
				}
				
				// input descirption
				if( !empty($settings['description']) ){
					echo '<div class="totalbusiness-input-description"><span>' . $settings['description'] . '<span></div>';
					echo '<div class="clear"></div>';
				}
				
				echo '</div>'; // totalbusiness-option
				echo '</div>'; // totalbusiness-option-wrapper				
			}

			// print custom option
			function print_custom_option($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				echo totalbusiness_escape_content($settings['option']);
				echo '</div>';
			}
			
			// print the input text
			function print_text_input($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				echo '<input type="text" class="gdl-text-input" name="' . $settings['name'] . '" data-slug="' . $settings['slug'] . '" ';
				if( isset($settings['value']) ){
					echo 'value="' . esc_attr($settings['value']) . '" ';
				}else if( !empty($settings['default']) ){
					echo 'value="' . esc_attr($settings['default']) . '" ';
				}
				echo '/>';
				echo '</div>';
			}
			
			// print the date picker
			function print_date_picker($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				echo '<input type="text" class="gdl-text-input totalbusiness-date-picker" name="' . $settings['name'] . '" data-slug="' . $settings['slug'] . '" ';
				if( isset($settings['value']) ){
					echo 'value="' . esc_attr($settings['value']) . '" ';
				}else if( !empty($settings['default']) ){
					echo 'value="' . esc_attr($settings['default']) . '" ';
				}
				echo '/>';
				echo '</div>';
			}			
			
			// print the textarea
			function print_textarea($settings = array()){
				echo '<div class="totalbusiness-option-input ';
				echo (!empty($settings['class']))? $settings['class']: '';
				echo '">';
				
				echo '<textarea name="' . $settings['slug'] . '" data-slug="' . $settings['slug'] . '" ';
				echo (!empty($settings['class']))? 'class="' . $settings['class'] . '"': '';
				echo '>';
				if( isset($settings['value']) ){
					echo totalbusiness_escape_content($settings['value']);
				}else if( !empty($settings['default']) ){
					echo totalbusiness_escape_content($settings['default']);
				}
				echo '</textarea>';
				echo '</div>';
			}		

			// print the combobox
			function print_combobox($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				
				$value = '';
				if( !empty($settings['value']) ){
					$value = $settings['value'];
				}else if( !empty($settings['default']) ){
					$value = $settings['default'];
				}
				
				echo '<div class="totalbusiness-combobox-wrapper">';
				echo '<select name="' . $settings['name'] . '" data-slug="' . $settings['slug'] . '" >';
				foreach($settings['options'] as $slug => $name ){
					echo '<option value="' . $slug . '" ';
					echo ($value == $slug)? 'selected ': '';
					echo '>' . $name . '</option>';
				
				}
				echo '</select>';
				echo '</div>'; // totalbusiness-combobox-wrapper
				
				echo '</div>';
			}	

		
			// print the font combobox
			function print_font_combobox($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				
				$value = '';
				if( !empty($settings['value']) ){
					$value = $settings['value'];
				}else if( !empty($settings['default']) ){
					$value = $settings['default'];
				}
				
				echo '<input class="totalbusiness-sample-font" ';
				echo 'value="' . esc_attr( esc_html__('Sample Font', 'totalbusiness') ) . '" ';
				echo (!empty($value))? 'style="font-family: ' . $value . ';" />' : '/>';
				
				echo '<div class="totalbusiness-combobox-wrapper">';
				echo '<select name="' . $settings['name'] . '" data-slug="' . $settings['slug'] . '" class="totalbusiness-font-combobox" >';
				do_action('totalbusiness_print_all_font_list', $value);
				echo '</select>';
				echo '</div>'; // totalbusiness-combobox-wrapper
				
				echo '</div>';
			}	
			
			// print the combobox
			function print_multi_combobox($settings = array()){
				echo '<div class="totalbusiness-option-input">';

				if( !empty($settings['value']) ){
					$value = $settings['value'];
				}else if( !empty($settings['default']) ){
					$value = $settings['default'];
				}else{
					$value = array();
				}

				echo '<div class="totalbusiness-multi-combobox-wrapper">';
				echo '<select name="' . $settings['name'] . '[]" data-slug="' . $settings['slug'] . '" multiple >';
				foreach($settings['options'] as $slug => $name ){
					echo '<option value="' . $slug . '" ';
					echo (in_array($slug, $value))? 'selected ': '';
					echo '>' . $name . '</option>';
				
				}
				echo '</select>';
				echo '</div>'; // totalbusiness-combobox-wrapper
				
				echo '</div>';
			}			

			
			// print the checkbox ( enable / disable )
			function print_checkbox($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				
				$value = 'enable';
				if( !empty($settings['value']) ){
					$value = $settings['value'];
				}else if( !empty($settings['default']) ){
					$value = $settings['default'];
				}
				
				echo '<label for="' . $settings['slug'] . '-id" class="checkbox-wrapper">';
				echo '<div class="checkbox-appearance ' . $value . '" > </div>';
				
				echo '<input type="hidden" name="' . $settings['name'] . '" value="disable" />';
				echo '<input type="checkbox" name="' . $settings['name'] . '" id="' . $settings['slug'] . '-id" data-slug="' . $settings['slug'] . '" ';
				echo ($value == 'enable')? 'checked': '';
				echo ' value="enable" />';	
				
				echo '</label>';		
				
				echo '</div>';
			}		

			// print the radio image
			function print_radio_image($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				
				$value = '';
				if( !empty($settings['value']) ){
					$value = $settings['value'];
				}else if( !empty($settings['default']) ){
					$value = $settings['default'];
				}
				
				$i = 0;
				foreach($settings['options'] as $slug => $name ){
					echo '<label for="' . $settings['slug'] . '-id' . $i . '" class="radio-image-wrapper ';
					echo ($value == $slug)? 'active ': '';
					echo '">';
					echo '<img src="' . $name . '" alt="" />';
					echo '<div class="selected-radio"></div>';

					echo '<input type="radio" name="' . $settings['slug'] . '" data-slug="' . $settings['slug'] . '" ';
					echo 'id="' . $settings['slug'] . '-id' . $i . '" value="' . $slug . '" ';
					echo ($value == $slug)? 'checked ': '';
					echo ' />';
					
					echo '</label>';
					
					$i++;
				}
				
				echo '<div class="clear"></div>';
				echo '</div>';
			}

			// print color picker
			function print_color_picker($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				
				echo '<input type="text" class="wp-color-picker" name="' . $settings['name'] . '" data-slug="' . $settings['slug'] . '" ';
				if( !empty($settings['value']) ){
					echo 'value="' . $settings['value'] . '" ';
				}else if( !empty($settings['default']) ){
					echo 'value="' . $settings['default'] . '" ';
				}
				
				if( !empty($settings['default']) ){
					echo 'data-default-color="' . $settings['default'] . '" ';
				}
				echo '/>';
				
				echo '</div>';
			}	
			
			// print skin settings
			function print_skin_settings($settings = array()){
				echo '<div class="totalbusiness-option-input" id="skin-setting-wrapper">';	

				// head section
				echo '<div class="totalbusiness-add-skin-wrapper">';
				echo '<input type="text" class="gdl-text-input" />';				
				echo '<div class="totalbusiness-add-more-skin"></div>';
				echo '<div class="totalbusiness-default-skin">';
				echo json_encode($settings['options']);
				echo '</div>';
				echo '<div class="clear"></div>';
				echo '</div>';
				
				echo '<div class="totalbusiness-add-skin-description">';
				echo esc_html__('The skin you created can be used in Color / Background Wrapper Section', 'totalbusiness');
				echo '</div>';
				echo '<div class="clear"></div>';
				
				// container section
				echo '<div class="totalbusiness-skin-container" ></div>';
				
				// input section
				echo '<textarea class="totalbusiness-skin-input" name="' . $settings['name'] . '">';
				echo !empty($settings['value'])? $settings['value']: '';
				echo '</textarea>';
				
				echo '</div>';		
			}			

			// print slider bar
			function print_slider_bar($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				if( !empty($settings['value']) ){
					$value = $settings['value'];
				}else if( !empty($settings['default']) ){
					$value = $settings['default'];
				}
				
				// create a blank box for javascript
				echo '<div class="totalbusiness-sliderbar" data-value="' . $value . '" ></div>';
				
				echo '<input type="text" class="totalbusiness-sliderbar-text-hidden" name="' . $settings['name'] . '" ';
				echo 'data-slug="' . $settings['slug'] . '" value="' . $value . '" />';
				
				// this will be the box that shows the value
				echo '<div class="totalbusiness-sliderbar-text">' . $value . 'px</div>';
				
				echo '<div class="clear"></div>';
				echo '</div>';			
			}

			// print slider
			function print_slider($settings = array()){
				echo '<div class="totalbusiness-option-input ';
				echo (!empty($settings['class']))? $settings['class']: '';
				echo '">';
				
				echo '<textarea name="' . $settings['slug'] . '" data-slug="' . $settings['slug'] . '" ';
				echo 'class="totalbusiness-input-hidden totalbusiness-slider-selection" data-overlay="true" data-caption="true" >';
				if( isset($settings['value']) ){
					echo totalbusiness_escape_content($settings['value']);
				}else if( !empty($settings['default']) ){
					echo totalbusiness_escape_content($settings['default']);
				}
				echo '</textarea>';
				echo '</div>';
			}				
			
			// print upload box
			function print_upload_box($settings = array()){
				echo '<div class="totalbusiness-option-input">';
				
				$value = ''; $file_url = '';
				$settings['data-type'] = empty($settings['data-type'])? 'image': $settings['data-type'];
				$settings['data-type'] = ($settings['data-type']=='upload')? 'image': $settings['data-type'];
				
				if( !empty($settings['value']) ){
					$value = $settings['value'];
				}else if( !empty($settings['default']) ){
					$value = $settings['default'];
				}
				
				if( is_numeric($value) ){ 
					$file_url = wp_get_attachment_url($value);
				}else{
					$file_url = $value;
				}
				
				// example image url
				echo '<img class="totalbusiness-upload-img-sample ';
				echo (empty($file_url) || $settings['data-type'] != 'image')? 'blank': '';
				echo '" ';
				echo (!empty($file_url) && $settings['data-type'] == 'image')? 'src="' . esc_url($file_url) . '" ': ''; 
				echo '/>';
				echo '<div class="clear"></div>';
				
				// input link url
				echo '<input type="text" class="totalbusiness-upload-box-input" value="' . esc_url($file_url) . '" />';					
				
				// hidden input
				echo '<input type="hidden" class="totalbusiness-upload-box-hidden" ';
				echo 'name="' . $settings['name'] . '" data-slug="' . $settings['slug'] . '" ';
				echo 'value="' . $value . '" />';
				
				// upload button
				echo '<input type="button" class="totalbusiness-upload-box-button gdl-button" ';
				echo 'data-title="' . $settings['title'] . '" ';
				echo 'data-type="' . $settings['data-type'] . '" ';				
				echo 'data-button="';
				echo (empty($settings['button']))? esc_html__('Insert Image', 'totalbusiness'):$settings['button'];
				echo '" ';
				echo 'value="' . esc_html__('Upload', 'totalbusiness') . '"/>';
				
				echo '<div class="clear"></div>';
				echo '</div>';
			}			

			// print upload font
			function print_upload_font($settings = array()){
				echo '<div class="totalbusiness-option-input" id="upload-font-wrapper">';	
				
				// head section
				echo '<div class="totalbusiness-upload-font-title-wrapper">';
				echo '<div class="totalbusiness-upload-font-title">' . esc_html__('Upload font', 'totalbusiness') . '</div>';
				echo '<div class="totalbusiness-add-more-font"></div>';
				$this->print_font_item();
				echo '<div class="clear"></div>';
				echo '</div>';
				
				// container section
				echo '<div class="totalbusiness-upload-font-container" >';
				if( isset( $settings['value'] ) ){
					$font_list = json_decode($settings['value'], true);
					if( !empty($font_list) ){
						foreach( $font_list as $font_item ){
							$this->print_font_item( $font_item ); 
						}
					}
				}
				echo '</div>';
				
				// input section
				echo '<textarea class="totalbusiness-upload-font-input" name="' . $settings['name'] . '">';
				echo !empty($settings['value'])? $settings['value']: '';
				echo '</textarea>';
				
				echo '</div>';
			}
			function print_font_item($value = array()){
				echo '<div class="totalbusiness-font-item-wrapper">';
				
				// font name section
				echo '<div class="totalbusiness-font-item">';
				echo '<span class="totalbusiness-font-input-label" >' . esc_html__('Font Name', 'totalbusiness') . '</span>';
				echo '<input class="totalbusiness-font-input" data-type="font-name" type="text" ';
				echo (!empty($value['font-name']))? 'value="' . $value['font-name'] . '" ' : '';
				echo '/>';
				echo '<div class="clear"></div>';
				echo '</div>';				
				
				// eot type
				echo '<div class="totalbusiness-font-item">';
				echo '<span class="totalbusiness-font-input-label" >' . esc_html__('EOT Font', 'totalbusiness') . '</span>';
				echo '<input class="totalbusiness-font-input" data-type="font-eot" type="text" ';
				if( !empty($value['font-eot']) ){
					if( is_numeric($value['font-eot']) ){
						echo 'value="' . esc_url(wp_get_attachment_url($value['font-eot'])) . '" ';				
					}else{
						echo 'value="' . $value['font-eot'] . '" ';
					}
				}
				echo '/>';
				echo '<input class="totalbusiness-upload-font-button gdl-button" type="button" value="' . esc_html__('Upload', 'totalbusiness') . '" />';
				echo '<div class="clear"></div>';
				echo '</div>';
				
				// ttf format
				echo '<div class="totalbusiness-font-item last">';
				echo '<span class="totalbusiness-font-input-label" >' . esc_html__('TTF Font', 'totalbusiness') . '</span>';
				echo '<input class="totalbusiness-font-input" data-type="font-ttf" type="text" ';
				if( !empty($value['font-ttf']) ){
					if( is_numeric($value['font-ttf']) ){
						echo 'value="' . esc_url(wp_get_attachment_url($value['font-ttf'])) . '" ';
					}else{
						echo 'value="' . $value['font-ttf'] . '" ';
					}
				}
				echo '/>';
				echo '<input class="totalbusiness-upload-font-button gdl-button" type="button" value="' . esc_html__('Upload', 'totalbusiness') . '" />';
				echo '<div class="clear"></div>';
				echo '</div>';
				
				// delete font button
				echo '<div class="totalbusiness-delete-font-item"></div>';
				echo '<div class="clear"></div>';
				echo '</div>'; // totalbusiness-font-item-wrapper
			
			}
			
		}

	}
		
?>