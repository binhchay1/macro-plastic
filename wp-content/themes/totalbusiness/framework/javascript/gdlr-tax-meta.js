(function($){
	$(document).ready(function(){

		// animate upload button
		$('.totalbusiness-option-input .totalbusiness-upload-box-input').change(function(){		
			$(this).siblings('.totalbusiness-upload-box-hidden').val($(this).val());
			if( $(this).val() == '' ){ 
				$(this).siblings('.totalbusiness-upload-img-sample').hide(); 
			}else{
				$(this).siblings('.totalbusiness-upload-img-sample').attr('src', $(this).val()).removeClass('blank');
			}
		});
		$('.totalbusiness-upload-media').click(function(){
			var upload_button = $(this);
		
			var custom_uploader = wp.media({
				title: upload_button.attr('data-title'),
				button: { text: upload_button.attr('data-button') },
				library : { type : 'image' },
				multiple: false
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				
				upload_button.siblings('.totalbusiness-preview').attr('src', attachment.url).show();
				upload_button.siblings('.totalbusiness-upload-box-input').val(attachment.url);
				upload_button.siblings('.totalbusiness-upload-box-hidden').val(attachment.id);
			}).open();			
		});	
		
		// datepicker
		$('.totalbusiness-date-picker').datepicker({
			dateFormat : 'yy-mm-dd',
			changeMonth: true,
			changeYear: true 
		});		
		
		// colorpicker
		$('.wp-color-picker').wpColorPicker();	
	
	});
})(jQuery);