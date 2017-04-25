$(document).ready(function (){
	$('.like[data-toggle-url]').click(function (){
		var url = $(this).data('toggle-url');
		var $control = $(this);
		$.getJSON(url, function (data){
			if(data.liked)
				$control.addClass('liked');
			else
				$control.removeClass('liked');
				
			$control.siblings('.like-counter').text(data.newCount);
		});
	});
});