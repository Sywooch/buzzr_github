$(document).ready(function (){
	$('.formstyler').styler();
});

$(document).ready(function (){
	setInterval(function (){
		$.get('/site/ping', function (data){
			var data = data.split('<!-- cabinet ajax -->');
			data = data[1];
			data = data.split('<!-- cabinet ajax end -->');
			data = data[0];
			$('header .cabinet').html(data);
		});
	}, 10000);
});
	
$(document).ready(function (){
	$('.cart-item input').change(function (){
		var val = $(this).val();
		$(this).closest('.cart-item').find('[data-multiple]').each(function (i,e){
			$(e).text(val * $(e).data('multiple'));
		});
	});
});

$(document).ready(function (){
	
	// отображение категорий в поиске
	$('#search_category').bind('keyup', refreshSearch);
	$('#search_category').bind('change', refreshSearch);
	
	function refreshSearch(){
		$('.lastcat-s, .midcat-s, .topcat-s').addClass('hidden');
		var found = false;
		var val = $(this).val();
		var expr = new RegExp(val, 'i');
		$('.lastcat-title').each(function (i,e){
			var matched = !val;
				matched = expr.test($(e).text());
				
			if(matched){
				$(this).closest('.lastcat-s').removeClass('hidden');
				$(this).closest('.midcat-s').removeClass('hidden');
				$(this).closest('.topcat-s').removeClass('hidden');
				found = true;
			}
		});
		
		if(!found)
			$('#cat-search').addClass('not-found');
		else
			$('#cat-search').removeClass('not-found');
			
		
	}
	
	// синхронизация чекбоксов на вкладке поиска категории по названию и на остальных вкладках
	
	$('[type=checkbox]').change(function (){
		var twin = false;
		var $t = $(this);
		
		// если аттрибут twin указывает на некий контрол
		if($t.data('twin'))
			twin = $('#'+$t.data('twin'));

		// если еще не найден, то продолжаем поиск
		if(!twin || !twin.length){
		
			// если есть некий контрол, аттрибут twin которого указывает на этот id
			var myId = $t.attr('id');
			if(myId){
				twin = $('[data-twin="'+myId+'"]');
			}
			
		}
		
		if(twin && twin.length){ // если близнец найден
			if($t.is(':checked'))
				twin.prop('checked', true);
			else
				twin.prop('checked', false);
		}
	});
});


$(document).ready(function (){
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	  var target = $(e.target).attr("href") // activated tab
	  var m = $(target).find('.js-masonry');
	  m.masonry('resize');
	});
});
	
$(document).ready(function (){
	
	var $cityControl = $('[name="Store[city_id]"]');
	var $regionControl = $('[name="Store[region_id]"]');
	
	$cityControl.change(updateRegion);
	
	if($regionControl.length > 0)
		updateRegion();
		
	function updateRegion(){
		$.getJSON('/site/regionsjson', function (data){
			var oldVal = $regionControl.val();
			$regionControl.empty();
			var city_id = $cityControl.val();
			for(var i = 0; i < data.length; i++)
				if(data[i].city_id == city_id)
					$regionControl.append('<option value="'+data[i].id+'">'+data[i].title+'</option>');

			if(oldVal){
				$regionControl.val(oldVal);
				if($regionControl.val() != oldVal){
					$regionControl.val($regionControl.children().first().attr('value'));
					$('#store-address').val($cityControl.children(':selected').text());
				}
			}
			
			
			
				
			$regionControl.trigger('refresh');
		});
	}
	
	$('.remove-photo-btn').click(function (ev){
		ev.preventDefault();
		$('input[name="Store[update_logo]"]').val('');
		$('input[name="Store[update_avatar]"]').val('');
		$('.single-picture-widget').removeClass('logo-loaded');
		var ias = $('.single-picture-widget .new-logo img').imgAreaSelect({ instance: true });
		ias.cancelSelection();
	});
	
	window.loguploaddone = function (data, elem, inputElem){
			if(data.result){
				var result = $.parseJSON(data.result);
				if(result.errors)
					alert(result.errors);
				else {
					elem.attr('src', result.files.url);
					inputElem.val(result.files.name);
					$('.single-picture-widget').addClass('logo-loaded');
					
					if(elem.data('imgcrop')){
						
						elem.on('load', function(){
							var params = elem.data('imgcrop');
							
							var ratio = result.files.geometry ? elem.width() / result.files.geometry[0] : 1;
							
							if(params.maxHeight)
								params.maxHeight = params.maxHeight * ratio;
								
							if(params.maxWidth)
								params.maxWidth = params.maxWidth * ratio;
							
							params.onSelectEnd = function (img, s){
									elem.closest('form').find('.x1').val(s.x1);
									elem.closest('form').find('.y1').val(s.y1);
									elem.closest('form').find('.width').val(s.width);
									elem.closest('form').find('.height').val(s.height);
									elem.closest('form').find('.ratio').val(ratio);
								};
								
							elem.imgAreaSelect(params);
						});
					}
				}
			}
	};
	
	window.fileuploaddone = function (data, elem, cropParams){
			if(data.result){
				var result = $.parseJSON(data.result);
				if(result.errors)
					alert(result.errors);
				else {
					var $template = elem.find('.template');
					var $newRow = $template.clone().appendTo(elem);
					$newRow.find('.filename-here').text(result.files.name);
					$newRow.find('.size-here').text(result.files.size);
					$newRow.find('.imgsrc-here').attr('src', result.files.url);
					$newRow.removeClass('template');
					var parent = elem.closest('.imagelist');

					if(cropParams){
						var imgCrop = $('#crop_modal img.crop-me');
						imgCrop.attr('src', result.files.url_full);
						$('#crop_modal').modal('show');
						
						imgCrop.on('load', function (){
							var params = cropParams;
							params.instance = true;
							var ratio = result.files.geometry ? imgCrop.width() / result.files.geometry[0] : 1;
							
							var text = cropParams.text || 'Выделите часть изображения';
							
							$('#crop_modal .hinttext').text(text);
	
							params.onSelectEnd = function (img, s){
									$newRow.find('.x1').val(s.x1);
									$newRow.find('.y1').val(s.y1);
									$newRow.find('.width').val(s.width);
									$newRow.find('.height').val(s.height);
									$newRow.find('.ratio').val(ratio);
									imagelistUpdateList(parent);
									
								};
	
	
							var ias = imgCrop.imgAreaSelect(params);
							$('#crop_modal').on('hide.bs.modal', function (){
								ias.cancelSelection();
							});
						});
					}
					
					imagelistUpdateList(parent);
				}
			}
	};
	
	function imagelistUpdateList(parent){
		var images = [];
		parent.find('.item').each(function (i,e){
			if(!$(e).is('.template'))
			images.push({
				'name': $(e).find('.filename-here').text(),
				'size': $(e).find('.filesize-here').text(),
				'url': $(e).find('.imgsrc-here').attr('src'),
				'x1': $(e).find('input.x1').val(),
				'y1': $(e).find('input.y1').val(),
				'width': $(e).find('input.width').val(),
				'height': $(e).find('input.height').val(),
				'ratio': $(e).find('input.ratio').val()
			});
		});
		parent.find('.image-list').val(JSON.stringify(images));
		if(parent.data('limit')){
			if(images.length >= parent.data('limit'))
				$(parent.data('limit-target')).hide();
			else
				$(parent.data('limit-target')).show();
		}
	}
	
	$('.imagelist').on('click', '.remove', function (ev){
		ev.preventDefault();
		var item = $(this).closest('.item');
		var parent = item.parent();
		item.remove();
		imagelistUpdateList(parent);
	});
	
	$('.imagelist').each(function (i,e){
		imagelistUpdateList($(e));
	});
	
	$('.product .gallery .enlarge-wrap').click(function (ev){
		ev.preventDefault();
		var href = $('.product .gallery img').data('full');
		
		$('section.product').toggleClass('enlarged-gallery');
		
	});
	
	$('.product .gallery a.photo-thumb').click(function (ev){
		ev.preventDefault();
		var src = $(this).data('large');
		var full = $(this).attr('href');
		$(this).addClass('active').siblings().removeClass('active');
		$(this).closest('.gallery').find('.photo-large img').attr('src', src).data('full', full);
	});
	
	setTimeout(function (){
		$('.list-type-selector .list-type input, .sort-order select, .category select').change(function (){
			$(this).closest('form').submit();
		});
	}, 100);
	
	$('.tabs .tab').click(function (ev){
		ev.preventDefault();
		var target = $(this).attr('href');
		$(this).addClass('active').siblings().removeClass('active');
		$(target).addClass('active').siblings().removeClass('active');
	});
	
	$('.cabinet').on('click', '.pulldn-trigger', function (ev){
		$(this).closest('.cabinet').toggleClass('pulldn');	
		ev.stopPropagation();
	});
	
	$('body').click(function (){
		$('header .cabinet').removeClass('pulldn');
	});
	
	var hash = location.href.match(/#.+/);
	if(hash)
		$('.tabs .tab[href="'+hash[0]+'"]').click();
	
});


$(document).ready(function (){

	$('a[data-target="#chat_modal"]').click(function (ev){
		ev.preventDefault();
		$("#chat_modal .modal-content").load($(this).attr('href'), function (){
			ajaxRefresh();
		});
		$("#chat_modal").modal('show');
		
		return false;
	});
	

	function ajaxRefresh(){
		$(".messages-area").mCustomScrollbar({
			scrollButtons:{enable:true},
			theme: 'dark'
		});
	}

	$('[data-pjax-container]').on('pjax:success', ajaxRefresh);
	ajaxRefresh();
	
});

// взаимодействие контролов в формах
$(document).ready(function (){
	function setControlsState(){
		var puc = $('#product-unavailable_comment');
		if(puc.length){
			if($('#product-available').is(':checked')){
				puc.attr('disabled', true);
				puc.attr('readonly', true);
				puc.val('');
			} else {
				puc.removeAttr('disabled');
				puc.removeAttr('readonly');
			}
		}
	}
	
	setControlsState();
	
	$('#product-available').change(setControlsState);
	
	// красивый инпут цены
	
	function correctPriceInputVal(){
		var currentVal = $(this).val();
		currentVal = currentVal.replace(/[^\d]/g, '');
		currentVal = parseInt(currentVal);
		if(isNaN(currentVal))currentVal = 0;
		currentVal = currentVal.toString();
		var cl = currentVal.length;
		if(cl > 6){
			currentVal = currentVal.substring(0, cl - 6) + ' ' + currentVal.substring(cl-6, cl - 3) + ' ' + currentVal.substring(cl - 3);
		}
		if( (cl <= 6) && (cl > 3) ){
			currentVal = currentVal.substring(0, cl - 3) + ' ' + currentVal.substring(cl - 3);
		}
		
		if(currentVal != $(this).val())
			$(this).val(currentVal);
	}
	
	$('#product-amount, #product-discount_amount').change(correctPriceInputVal);
	$('#product-amount, #product-discount_amount').keyup(correctPriceInputVal);


	$(window).resize(function() {
		unwrapTable();
		if ($(window).width() > 768) {
			$('section.menu').attr('style', '');
			if($('.header_cabinet').has('.avatar-round')) {
				$('.header_cabinet').css('margin-top', 0);
			} else {
				$('.header_cabinet').css('margin-top', '9px');
			}
		} else if ($(window).width() < 768) {
			if($('.header_cabinet').has('.avatar-round')) {
				$('.header_cabinet').css('margin-top', 0);
			} else {
				$('.header_cabinet').css('margin-top', 0);
			}
		}
	});
	if ($(window).width() > 768) {
		$('section.menu').attr('style', '');
		if($('.header_cabinet').has('.avatar-round')) {
			$('.header_cabinet').css('margin-top', 0);
		} else {
			$('.header_cabinet').css('margin-top', '9px');
		}
	} else if ($(window).width() < 768) {
		if($('.header_cabinet').has('.avatar-round')) {
			$('.header_cabinet').css('margin-top', 0);
		} else {
			$('.header_cabinet').css('margin-top', 0);
		}
	}
	unwrapTable();

	function unwrapTable () {
		if ($(window).width() < 976 ) {
			$('section.catalog .categories-top, .catalog .categories-inner').find('tr').contents().unwrap();
		} else {
			var td = $('section.catalog .categories-top td, .catalog .categories-inner td'),
			    cntGroup = 3;
			var arr = td.get();
			if (!td.parent('tr').hasClass('wrapped')) {
				for(var i = 0; i < td.length; i += cntGroup){
				    $(arr.slice(i,i+cntGroup)).wrapAll('<tr class="wrapped"></tr>');
				}
			}
		}
	}
	
	if ($(window).width() < 768) {
		$('.mobile_menu_btn_click').click(function() {
			$('section.menu').slideToggle();
		});
		$('.cabinet-person').click(function() {
			$('section.cabinet .submenu, .section-cabinet .submenu').slideToggle();
		});
	}

	if($.contains('.header_cabinet', '.avatar-round')) {
		$('.header_cabinet').css('top', 0);
		// if ($(window).width() > 768) {
		// 	$('.header_cabinet').css('margin-top', 0);
		// }
	}

	$('.submenu-area_mobile_btn').click(function() {
		$('section.store .submenu-area .submenu ul').slideToggle();
	});
	
});