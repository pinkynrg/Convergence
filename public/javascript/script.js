"use strict"

$(document).ready(function() {

// tickets filters page ////////////////////////////////////////////////////////////////////////////////////////////////////

	//reset values of filters 
	$('.selectpicker').selectpicker('deselectAll');

	//reset values of search field
	$("input[type='text'].search").val("");

	// trigger ajax request when searching
	$("input[type='text'].search").on("keyup", function () {
		var $target = $(this).closest("div[ajax-route]");
		ajaxUpdate($target);
	});

	// trigger ajax request when ordering
	$("tr.orderable th").on("click", function () {
		var $target = $(this).closest("div[ajax-route]");
		toggleOrder($(this));
		ajaxUpdate($target);
	});

	// trigger ajax request when filtering
	$("select.selectpicker.multifilter").on("change", function () {
		var $target = $(this).closest("div[ajax-route]");
		ajaxUpdate($target);
	});

	// reset filters
	$("input#reset_filters").on("click", function() {
		var $target = $(this).closest("div[ajax-route]");
		resetFilter($target);
	});

	// trigger ajax request when changing page
	$(".ajax_pagination li").live('click',function (e) {
		var $target = $(this).closest("div[ajax-route]");
		e.preventDefault();
		togglePage($(this));
		ajaxUpdate($target);
		if ($(this).closest(".ajax_pagination").attr('scrollup') == 'true') {
			scrollUp(500);
		}
	});

	//scrollup effect
	function scrollUp(ms) {
		$("html, body").animate({
	        scrollTop: 0
	    }, ms);
	}

	function resetFilter($this) {
		var $target = $this.closest("div[ajax-route]");
		$target.find('.selectpicker').selectpicker('deselectAll');
		ajaxUpdate($target);
	}

	function ajaxUpdate($target) {
		var params = getParams($target);
		var url = $target.attr("ajax-route");
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
		var params = {};
		
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

	function getUrl(url, params) {

		url = url + "/";
		
		if (params['search'] != "") url = url + "&" + "search" + "=" + encodeURIComponent(params['search']);
		
		if ( params['order'] != {} ) {
			for (var key in params['order']) {
				url = url + "&" + encodeURIComponent("order[column]=") + encodeURIComponent(key) + "&" + encodeURIComponent("order[type]") + "=" + encodeURIComponent(params['order'][key]);
			}
		}

		if ( params['filters'] != {} ) {
			for (var key in params['filters']) {
				for (var subkey in params['filters'][key]) {
					url = url + "&" + encodeURIComponent("filters["+key+"]["+subkey+"]") + "=" + encodeURIComponent(params['filters'][key][subkey]);
				}
			}
		}

		if ( params['page'] != 1 ) {
			url = url + "?page=" + params['page'];
		}

		console.log(url);
		
		return url;
	}

	function ajaxRequest(url,$target) {

		$.get(url, function (data) {
			$target.find(".content table tbody").html($(data).find('tbody').html());
			$target.find(".ajax_pagination[scrollup='true']").html($(data).find(".ajax_pagination[scrollup='true']").html());
			$target.find(".ajax_pagination[scrollup='false']").html($(data).find(".ajax_pagination[scrollup='false']").html());
		});
	}

// ticket page /////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$(".nav-tabs li").click(function(e) {
		var target = $(this).find("a").attr("target");
		$(".nav-tabs li").removeClass("active");
		$(this).addClass("active");
		$("#tab_contents>div").css("display","none");
		if ($("#"+target).length) {
			$("#tab_contents #"+target).show(500);
		}
		return false;
	})

// company page /////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

// create contact ////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// popover
	$(function () {
	  $('[data-toggle="popover"]').popover();
	});

	// removes link contact->person
	$(".cancel").click(function () {
	    $("#person_fn, #person_ln").prop("readonly",false);
	    $("#person_id").prop("disabled",true);
	    $("#person_fn, #person_ln").val("");
	   	$(".input-group-addon").css("display","none");
	    $("#input_group_fn, #input_group_ln").removeClass("input-group");
	});

	// make popover disappear
	$("#person_fn").on("keydown", function () {
		$(this).popover('hide');
	});

	// autocomplete for first and last name
	$("#person_fn, #person_ln").devbridgeAutocomplete({

	    serviceUrl: "/ajax/people",

	    onSelect: function (suggestion) {
	    	$("#person_id").prop("disabled",false);
			$("#person_fn, #person_ln").prop("readonly",true);
	    	$("#person_id").val(suggestion.id);
	    	$("#person_fn").val(suggestion.first_name);		    	
	    	$("#person_ln").val(suggestion.last_name);
	    	$(".input-group-addon").css("display","table-cell");
	    	$("#input_group_fn, #input_group_ln").addClass("input-group");
	    },
	    formatResult: function (suggestion, currentValue) {
	    	 var returned = suggestion.last_name + " " + suggestion.first_name + " @ " + suggestion.company_name;
	        return returned;
	    }
	});

// create ticket page ////////////////////////////////////////////////////////////////////////////////////////////////////////

	//ckeditor
	if ($("textarea#post").length != 0) {
		CKEDITOR.replace('post');
	}

	// feature to add tag ticket 
	$('#tagit').tagsinput({
		typeahead: {
			displayText: function (item) {
       			return item;
      		},
			source: function(query) {
  				return $.getJSON('/ajax/tags?query='+query);
  			}
		}
	});

	// add bootstrap class to tag field in ticket form page
	$(".bootstrap-tagsinput").addClass("col-xs-12");

	// update fileds related to selection of company (like contacts, equipments, ...)
	$(".ajax_trigger#company_id").on("change",function () {
		var company_id = $(this).val();
		
		console.log(company_id);

		$.get('/ajax/tickets/contacts/'+company_id, function (data) {
			data = JSON.parse(data);
			$('select#contact_id').html('');
			$('select#contact_id').append('<option value="NULL">-</option>');
			for (var i = 0; i<data.length; i++)
				$('select#contact_id').append('<option value="'+data[i].id+'">'+data[i].last_name+' '+data[i].first_name+'</option>');
		});

		$.get('/ajax/tickets/equipments/'+company_id, function (data) {
			data = JSON.parse(data);				
			$('select#equipment_id').html('');
			$('select#equipment_id').append('<option value="NULL">-</option>');
			for (var i = 0; i<data.length; i++)
				$('select#equipment_id').append('<option value="'+data[i].id+'">'+data[i].notes+'</option>');
		});

	});

// show role page //////////////////////////////////////////////////////////////////////////////////////////////////////////////


	var role_update_permissions = $('.role_update_permissions').bootstrapDualListbox({
		'infoText':""
	});

	var group_update_roles = $('.group_update_roles').bootstrapDualListbox({
		'infoText':""
	});

// create equipment page ///////////////////////////////////////////////////////////////////////////////////////////////////////

	$('#warranty_expiration').datepicker();

});

