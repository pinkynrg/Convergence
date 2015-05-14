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

	//scrollup effect
	function scrollUp(ms) {
		$("html, body").animate({
	        scrollTop: 0
	    }, ms);
	}

	// trigger ajax request when searching
	$("input[type='text'].search").live("keyup", function () {
		ajaxUpdate($(this).closest("div[ajax-route]"));
	});

	// trigger ajax request when ordering
	$("tr.orderable th").live("click", function () {
		toggleOrder($(this));
		ajaxUpdate($(this).closest("div[ajax-route]"));
	});

	// trigger ajax request when filtering
	$("select.selectpicker.multifilter").live("change", function () {
		ajaxUpdate($(this).closest("div[ajax-route]"));
	});

	// trigger ajax request when changing page
	$(".ajax_pagination li").live('click',function (e) {
		e.preventDefault();
		togglePage($(this));
		ajaxUpdate($(this).closest("div[ajax-route]"));
	});

	function ajaxUpdate($target) {
		params = getParams($target);
		url = $target.attr("ajax-route");
		url = getUrl(url,params);
		ajaxRequest(url,$target);
	}

	function toggleOrder($elem) {
		var desc_icon = '<i class="fa fa-sort-amount-desc"></i>';
		var asc_icon = '<i class="fa fa-sort-amount-asc"></i>';
		if ($elem.attr('type') == 'asc') {
			$elem.parent().find("th").each(function() {
				$(this).removeAttr("type");
				$(this).html($(this).html().replace(asc_icon,"").replace(desc_icon,""));
			});
			$elem.attr('type','desc');
			$elem.html(desc_icon+" "+$elem.html());
		} 
		else {
			$elem.parent().find("th").each(function() {
				$(this).removeAttr("type");
				$(this).html($(this).html().replace(asc_icon,"").replace(desc_icon,""));
			});
			$elem.attr('type','asc');
			$elem.html(asc_icon+" "+$elem.html());
		}
	}

	function togglePage($elem) {
		$elem.attr("selected","true");
	}

	function getParams($target) {
		var params = [];
		
		params['order'] = {};
		params['filters'] = {};
		params['search'] = "";
		params['page'] = 1;

		var table_order = $target.find("tr.orderable th[type]");
		var multifilter = $target.find("select.selectpicker.multifilter");

		params['search'] = $target.find("input[type='text'].search").length != 0 ? $target.find("input[type='text'].search").val() : "";

		if (table_order.length) {
			params['order'][table_order.attr('column')] = table_order.attr('type');
		}

		multifilter.each(function () {
			var that = $(this);
			if ($(this).find("option:selected").length) {
				params['filters'][that.attr('column')] = [];
				$(this).find("option:selected").each(function() {
					params['filters'][that.attr('column')].push($(this).val());
				});
			}

		});

		if ($(".ajax_pagination").find("li[selected]").length) {
			params['page'] = $(".ajax_pagination").find("li[selected]").find('a').attr('href').split('?page=')[1];
		}
		else if ($(".ajax_pagination").find("li.active").length) {
			params['page'] = $(".ajax_pagination").find("li.active").find('span').html();
		}
		else 
			params['page'] = 1;

		return params;
	}

	function getUrl(url) {

		url = url + "/";
		
		if (params['search'] != "") url = url + "&" + "search" + "=" + encodeURIComponent(params['search']);
		
		if ( params['order'] != {} ) {
			for (key in params['order']) {
				url = url + "&" + encodeURIComponent("order[column]=") + encodeURIComponent(key) + "&" + encodeURIComponent("order[type]") + "=" + encodeURIComponent(params['order'][key]);
			}
		}

		if ( params['filters'] != {} ) {
			for (key in params['filters']) {
				for (subkey in params['filters'][key]) {
					url = url + "&" + encodeURIComponent("filters["+key+"]["+subkey+"]") + "=" + encodeURIComponent(params['filters'][key][subkey]);
				}
			}
		}

		if ( params['page'] != 1 ) {
			url = url + "?page=" + params['page'];
		}
		
		return url;
	}

	function ajaxRequest(url,$target) {

		$.get(url, function (data) {
			console.log(url);
			$target.find(".content table tbody").html($(data).find('tbody').html());
			$target.find(".ajax_pagination").html($(data).find('.ajax_pagination').html());
		});

	}

});