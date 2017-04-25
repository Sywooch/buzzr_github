$(document).ready(function (){
	$('.subscribe[data-toggle-url]').click(function (){
		var url = $(this).data('toggle-url');
		var $control = $(this);
		$.getJSON(url, function (data){
			if(data.subscribed)
				$control.addClass('subscribed');
			else
				$control.removeClass('subscribed');
				
		});
	});
});