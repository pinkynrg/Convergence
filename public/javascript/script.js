$(document).ready(function() {
	//activate tooltips
	$('[data-toggle="tooltip"]').tooltip();
	
	//for expandable divs
	$('.expander').click(function() {
		var minus_icon = "fa fa-minus-square-o fa-2";
		var plus_icon = "fa fa-plus-square-o fa-2";
		var to_expand = $(this).next('.to_expand'),
			expander = $(this);
		
		if (to_expand.css('display') == 'none')
			expander.html(expander.html().replace('<i class="'+plus_icon+'"></i>','<i class="'+minus_icon+'"></i>'));
		else 
			expander.html(expander.html().replace('<i class="'+minus_icon+'"></i>','<i class="'+plus_icon+'"></i>'))

		to_expand.toggle(500);
	});

	//for search panel 
	$('.search_button').click(function (e) {
		$('.search_box').toggle(500);
		e.preventDefault();
	});

	//ajax pagination
	$(".ajax_pagination li").live('click',function (e) {
		e.preventDefault();
		var that = $(this);
		var pag = that.find('a').attr('href').split('?page=')[1];
		var route = that.parent().parent().attr('route');
		var link = route+"?page="+pag;
		$.get(link, function (data) {
			$(window).scrollTop();
			that.parent().parent().parent().html(data);
			if (that.parent().parent().attr('scrollup') == 'true') {
				scrollUp(1000);
			}
		});
	});

	//scrollup effect
	function scrollUp(ms) {
		$("html, body").animate({
	        scrollTop: 0
	    }, ms);
	}

});