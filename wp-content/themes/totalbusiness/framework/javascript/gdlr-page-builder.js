(function($){
	
	// for undo / redo event
	var gdlrPageBuilderStack = new function() {
		
		var page_builder = $('#page-builder-content-item');
		var stack = [];
		var current_index = 0;

		this.init = function( page_builder_temp ){
			page_builder = page_builder_temp.children('#page-builder-content-item');
			
			// undo button
			page_builder.children('.page-builder-head-wrapper').find('.undo-button').click(function(){
				if( current_index > 1 ){	
					page_builder.find('.content-section-wrapper > .totalbusiness-sortable-wrapper').each(function(){
						$(this).html( stack[current_index-2][$(this).attr('data-type')] );
						$(this).children().gdlrSortable();
						$(this).find('.totalbusiness-draggable').gdlrDraggable();
					});				
					current_index--;
					
					gdlrUpdateInputBox();
				}
			});
			
			// redo button
			page_builder.children('.page-builder-head-wrapper').find('.redo-button').click(function(){
				if( current_index < stack.length  ){
					page_builder.find('.content-section-wrapper > .totalbusiness-sortable-wrapper').each(function(){
						$(this).html( stack[current_index][$(this).attr('data-type')] );
						$(this).children().gdlrSortable();
						$(this).find('.totalbusiness-draggable').gdlrDraggable();
					});								
					current_index++;
					
					gdlrUpdateInputBox();
				}		
			});		
			
			current_index = 0;
			this.pushStack();
		}
		
		this.pushStack = function(){
			var item = new Object();
			
			// collect each page builder section
			page_builder.find('.content-section-wrapper > .totalbusiness-sortable-wrapper').each(function(){
				eval("item['" + $(this).attr('data-type') + "']=$(this).html()");
			});
			
			stack[current_index] = item;
			current_index++;
			
			// clear after index
			while(stack[current_index]){
				stack.pop();
			}			
		}
		
	};
	
	// update each input when there're any changes
	function gdlrUpdateInputBox(){
		$('#page-builder-content-item').find('.content-section-wrapper').each(function(){
			var page_builder = [];
		
			$(this).find('.page-builder-item-area').children().each(function(){
				if( $(this).hasClass('totalbusiness-draggable-item') ){
					page_builder.push($(this).gdlrSaveItem());
				}else if( $(this).hasClass('totalbusiness-draggable-wrapper') ){
					page_builder.push($(this).gdlrSaveItemWrapper());
				}
			});

			// input area
			$(this).children('.totalbusiness-input-hidden').val(JSON.stringify(page_builder));
			
			// make item fit to rows
			$(this).children('.totalbusiness-sortable-wrapper').children('.totalbusiness-sortable').gdlrFitRows();
		});
	}
	$.fn.gdlrSaveItemWrapper = function(){
		var item = new Object();
		
		item['item-type'] = 'wrapper';
		item['item-builder-title'] = $(this).children('.totalbusiness-item').find('input.totalbusiness-draggable-text-input').val();
		item['type'] = $(this).attr('data-type');
		item['items'] = [];
		item['option'] = new Object();
		
		// assign the item size if any
		if( $(this).children('.totalbusiness-item').children('.totalbusiness-draggable-head').find('.totalbusiness-size-text').length > 0 ){
			item['size'] = $(this).find('.totalbusiness-size-text').html();
		}

		// add the option attribute
		$(this).children('.totalbusiness-item-option').children().each(function(){	
			if( $(this).attr('data-value') ){
				eval("item['option']['" + $(this).attr('data-name') + "']= $(this).attr('data-value')");
			}else if( $(this).attr('data-default') ){
				eval("item['option']['" + $(this).attr('data-name') + "']= $(this).attr('data-default')");
			}else{
				eval("item['option']['" + $(this).attr('data-name') + "']=''");
			}
		});
				
		// add the child item
		$(this).children('.totalbusiness-item').children('.totalbusiness-sortable').children().each(function(){
			if( $(this).hasClass('totalbusiness-draggable-item') ){
				item['items'].push($(this).gdlrSaveItem());
			}else if( $(this).hasClass('totalbusiness-draggable-wrapper') ){
				item['items'].push($(this).gdlrSaveItemWrapper());
			}			
			
		});
		
		return item;
	}
	$.fn.gdlrSaveItem = function(){
		var item = new Object();
		item['item-type'] = 'item';
		item['item-builder-title'] = $(this).children('.totalbusiness-item').find('input.totalbusiness-draggable-text-input').val();
		item['type'] = $(this).attr('data-type');
		item['option'] = new Object();

		$(this).children('.totalbusiness-item-option').children().each(function(){	
			if( $(this).attr('data-value') ){
				eval("item['option']['" + $(this).attr('data-name') + "']=$(this).attr('data-value')");
			}else if($(this).attr('data-default')){
				eval("item['option']['" + $(this).attr('data-name') + "']=$(this).attr('data-default')");
			}else{
				eval("item['option']['" + $(this).attr('data-name') + "']=''");
			}
		});
		
		return item;
	}	
		
	$.fn.gdlrFitRows = function(){
		//$(this).each(function(){
		//	var row_width = $(this).width();
		//	var current = 0;
		//	
		//	$(this).children().each(function(){		
		//		if( current == 0 || current + $(this).outerWidth(true) > row_width + 5 ){
		//			$(this).addClass('totalbusiness-first');
		//			current = $(this).outerWidth(true);
		//		}else{
		//			$(this).removeClass('totalbusiness-first');
		//			current += $(this).outerWidth(true);
		//		}
		//	});		
		//});
	}
	
	// make the elements sortable
	$.fn.gdlrSortable = function(){
		$(this).gdlrFitRows();
		$(this).sortable({
			revert: 100,
			opacity: 0.8,
			tolerance: "pointer",
			placeholder: 'totalbusiness-placeholder',
			connectWith: ".totalbusiness-sortable",
			start: function(e, ui){
				changed = true;
				
				var totalbusiness_placeholder = $('<div class="totalbusiness-placeholder"></div>');
				totalbusiness_placeholder.height(ui.item.outerHeight());

				ui.placeholder.removeClass();
				ui.placeholder.addClass(ui.item.attr('class')).append(totalbusiness_placeholder);
				
				if( ui.item.hasClass('totalbusiness-draggable-wrapper') ){
					ui.placeholder.addClass('totalbusiness-placeholder-wrapper');
					ui.placeholder.addClass(ui.item.attr('data-type') + '-placeholder');
				}
			},
			receive: function( event, ui ){
				if( $(this).hasClass('totalbusiness-inner-sortable') &&
				   ((ui.item.hasClass('totalbusiness-draggable-wrapper') &&
					!$(this).hasClass('color-wrapper-sortable') &&
					!$(this).hasClass('parallax-bg-wrapper-sortable') &&
					!$(this).hasClass('full-size-wrapper-sortable')) ||
				    (ui.item.attr('data-type') == 'parallax-bg-wrapper' ||
					ui.item.attr('data-type') == 'color-wrapper' ||
					ui.item.attr('data-type') == 'full-size-wrapper'))
				){
					changed = false;
					ui.sender.sortable("cancel");
				}else{
					ui.item.parents('.totalbusiness-sortable').removeClass('blank');
				}
			},
			remove: function( event, ui ){
				if( $(this).children().length <= 0 ){
					$(this).addClass('blank');
				}
			},
			stop: function( event, ui ){
				if( changed ){
					changed = false;
				
					gdlrUpdateInputBox();
					gdlrPageBuilderStack.pushStack();
				}
			}
		});
		
		
	}
	
	// make the sortable area toggle box
	$.fn.gdlrSortableToggle = function(){
		if( $(this).hasClass('active') ){
			$(this).removeClass('active');
			$(this).siblings('.totalbusiness-sortable-wrapper').slideUp(300);
		}else{
			$(this).addClass('active');
			$(this).siblings('.totalbusiness-sortable-wrapper').slideDown(300);
		}	
	}
	
	// add the action to draggable item
	$.fn.gdlrDraggable = function(){
		
		// bind the wrapper sortable
		$(this).find('.totalbusiness-sortable').gdlrSortable();
		
		// bind the item builder name button
		$(this).children('.totalbusiness-item').find('input.totalbusiness-draggable-text-input').change(function(){
			gdlrUpdateInputBox();
		});
		
		// bind the delete item button
		$(this).find('.totalbusiness-delete-item').click(function(){
			$(this).closest('.totalbusiness-draggable').slideUp(200, function(){
				var sortable_section = $(this).parent('.totalbusiness-sortable');				
				if( sortable_section.children().length <= 1 ){
					sortable_section.addClass('blank');
				}	
				
				$(this).remove();
				gdlrUpdateInputBox();
				gdlrPageBuilderStack.pushStack();
			});							
		});
		
		// bind the edit item button
		$(this).find('.totalbusiness-edit-item').click(function(){
			$(this).gdlrEditBox( function(){
				gdlrUpdateInputBox();
			});
		});
		
		// bind the increase / decrease size button
		var item_size = [
			{ key: '1/5', value: 'one-fifth column' },
			{ key: '1/4', value: 'three columns' },	
			{ key: '1/3', value: 'four columns' },
			{ key: '2/5', value: 'two-fifth column' },	
			{ key: '1/2', value: 'six columns' },	
			{ key: '3/5', value: 'three-fifth column' },
			{ key: '2/3', value: 'eight columns' },
			{ key: '3/4', value: 'nine columns' },	
			{ key: '4/5', value: 'four-fifth column' },
			{ key: '1/1', value: 'tweleve columns' }			
		];

		$(this).find('.totalbusiness-increase-size, .totalbusiness-decrease-size').click(function(){
			var draggable_item = $(this).closest('.totalbusiness-draggable');
			var size_text = $(this).parent('.totalbusiness-size-control').siblings('.totalbusiness-size-text');
			var current_size = size_text.html();
			
			// get the current size and remove the class out
			var index = 0;
			for (index = 0; index < item_size.length; index++) {
				if( item_size[index].key == current_size ){ break; }
			}
			draggable_item.removeClass(item_size[index].value);
			
			// change to next size
			if( $(this).hasClass('totalbusiness-increase-size') && (index <= item_size.length-2)  ){
				index++;
			}else if( $(this).hasClass('totalbusiness-decrease-size') && (index >= 1) ){
				index--;
			}
			size_text.html(item_size[index].key);
			draggable_item.addClass(item_size[index].value);
			
			gdlrUpdateInputBox();
			gdlrPageBuilderStack.pushStack();
		});

	}

	$(document).ready(function(){
		var page_builder = $('#totalbusiness-page-builder');
		
		// init the stack for undo-redo-action
		gdlrPageBuilderStack.init(page_builder);
		
		var add_item_section = page_builder.children('#page-builder-add-item');
		var default_item_section = page_builder.children('#page-builder-default-item');
		var content_item_section = page_builder.children('#page-builder-content-item');
		
		// add action to the saved items
		page_builder.find('.totalbusiness-draggable').gdlrDraggable();
		
		// make the wrapper elements sortable
		content_item_section.find('.totalbusiness-sortable').gdlrSortable();
		
		// add new item to the sortable area
		add_item_section.find('.gdl-add-item').click(function(){
			var selected_item = $(this).siblings('.totalbusiness-combobox-wrapper').children('select');
			var container_area = content_item_section.find('.with-sidebar-section .totalbusiness-sortable-wrapper')
									.children('.totalbusiness-sortable');
			
			if( selected_item[0].selectedIndex == 0 ) return;
	
			// add default item to the last sortable area
			var prototype_item = default_item_section
									.children('#' + selected_item.val() + '-default')
									.children('.totalbusiness-draggable')
									.clone()
									.css('display', 'none');
			prototype_item.appendTo(container_area.removeClass('blank'))
								.fadeIn(200)
								.gdlrDraggable();
								
			gdlrUpdateInputBox();
			gdlrPageBuilderStack.pushStack();
		});
		
		// add the script to toggle the sortable area 
		content_item_section.find('.content-section-head-wrapper').click(function(){
			$(this).gdlrSortableToggle();
		});
	
	
	});


})(jQuery);