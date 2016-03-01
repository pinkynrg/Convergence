"use strict"

$(document).ready(function() {

var desc_icon = '<i class="fa fa-sort-amount-desc"></i>',
	asc_icon = '<i class="fa fa-sort-amount-asc"></i>',
	debug = true,
	regex = /\/([a-zA-Z\-]*)([\/]?)(create|[\d]*)([\/]?)([a-zA-Z\-]*)([\/]?)/g,
	timer,
	protocol = window.location.protocol,
	host = window.location.hostname,
	path = window.location.pathname,
	rxres = regex.exec(path),
	first_ordering = true,
	draft_ticket_id = 8;

var url = (function () {
	return {
		path : path,
		target : rxres[1] != '' ? rxres[1] : 'root',
		target_id : (rxres[3] != '' && rxres[3]) != 'create' ? rxres[3] : null,
		target_action : (rxres[5] == '') ? (rxres[3] != '') ? (rxres[3] == 'create') ? 'create' : 'show' : 'index' : rxres[5]
	}
})();

var consoleLog = function(string) {
	if (debug) {
		console.log(string);
	}
};

var scrollUp = function(ms) {
	$("html, body").animate({
        scrollTop: 0
    }, ms);
};

var getUrlParameter = function (sPageURL, sParam) {
    var sURLVariables = sPageURL.replace("?","&").split('&'),
        sParameterName,
        i;

    consoleLog("Parameters in href clicked page: ");
    consoleLog(sURLVariables);

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

var resetFilter = function($this) {
	var $target = $this.closest("div[ajax-route]");
	$target.find('.selectpicker').selectpicker('deselectAll');
	ajaxUpdate($target);
};

var ajaxUpdate = function($target) {
	if (timer) {
		clearTimeout(timer);
		timer = null;
	}
	timer = setTimeout(function () {
		var params = getParams($target);
		var url = getUrl(params,$target);
		ajaxRequest(url,$target);
	}, 500);
};

var toggleOrder = function($elem) {
	if (first_ordering && typeof $elem.attr('type') == 'undefined') {
		$elem.closest('tr').find('th').each(function () {
			$(this).removeAttr("type");
			$(this).removeAttr("weight");
			$(this).html($(this).html().replace(asc_icon,"").replace(desc_icon,""));
		});
		first_ordering = false;
	}

	var current_type = $elem.attr("type");
	var current_weight = $elem.attr("weight");
	
	var $ordered_columns = $elem.parent().find("th[type]");
	var new_weight = 0;

	$elem.removeAttr("type");
	$elem.removeAttr("weight");
	$elem.html($elem.html().replace(asc_icon,"").replace(desc_icon,""));

	if (typeof current_type == 'undefined') {
		$elem.attr('type','asc');
		$ordered_columns.each(function () {
			var weight = $(this).attr("weight");
			if (new_weight <= weight) new_weight = parseInt(weight)+1;
		});
		$elem.attr('weight',new_weight);
		$elem.html(asc_icon+"&nbsp;"+$elem.html());
	}
	else if (current_type == 'asc') {
		$elem.attr('type','desc');
		new_weight = current_weight;
		$elem.attr('weight',new_weight);
		$elem.html(desc_icon+"&nbsp;"+$elem.html());
	}
};

var splitSearch = function(search) {
	var list = [],
		keyword = "",
		open_quote = false;
	
	for (var i=0; i<search.length; i++) {

		if ((search[i] === '\'' || search[i] === '"') && open_quote === false) {
			if (keyword.length) list.push(keyword);
			keyword = "";
			open_quote = search[i];
			keyword+=search[i];
		}
		else if (open_quote === search[i]) {
			open_quote = false;
			keyword+=search[i];
			list.push(keyword);
			keyword = "";
		}
		else if (open_quote !== false) {
			keyword+=search[i];
		}
		else if (open_quote === false && search[i] != " ") {
			keyword+=search[i];
			if (i == search.length-1) {
				list.push(keyword);
			}
		}
		else if (keyword.length) {
			list.push(keyword);
			keyword = "";
		}
	}

	return list;
};

var getParams = function($target) {
	var params = {};
	
	params['order'] = [];
	params['where'] = [];
	params['page'] = 1;

	var $search = $target.find("input[type='text'].search:visible");
	var $table_order = $target.find("tr.orderable th[type]");
	var $multifilter = $target.find("select.selectpicker.multifilter");

	if ($search.val() != null && $search.val().length != 0) {
		var keywords = splitSearch($search.val());
		var columns = $search.attr('columns').replace(/,/g,':');
		consoleLog("search columns: " + columns);
		for (var i=0; i<keywords.length; i++) {

			var keyword = keywords[i];

			if ((keyword[0] == "'" && keyword[keyword.length-1] == "'") || (keyword[0] == '"' && keyword[keyword.length-1] == '"')) {
				keyword = keyword.substring(1,keyword.length-1);
				params['where'].push(columns + "|LIKE|"+keyword+':* '+keyword+' *:'+keyword+' *:* '+keyword);
			}
			else 
			{
        		keyword = '*'+keyword+'*';
				params['where'].push(columns + "|LIKE|" + keyword);
			}
		}
	}

	if ($table_order.length) {
		$table_order.each(function () {
			params['order'][$(this).attr('weight')] = $(this).attr('column')+"|"+$(this).attr('type');
		});
	}

	$multifilter.each(function () {
		if ($(this).val() != null && $(this).val().length > 0) {
			params['where'].push($(this).attr('id') + "|IN|" + $(this).val().join(":"));
		}
	});

	if ($(".ajax_pagination").find("li[selected]").length) {
		var url = $(".ajax_pagination").find("li[selected] a").attr("href");
		consoleLog("Href on clicked page: " + url);
		params['page'] = getUrlParameter(url,"page");

	}

	return params;
};

var getUrl = function(params, $target) {

	var url = $target.attr('ajax-route');

	var url_parts = [];
	
	if ( params['order'].length != 0 ) {
		for (var key in params['order']) {
			url_parts.push("order["+key+"]="+params['order'][key]);
		}
	}

	if ( params['where'].length != 0 ) {
		for (var key in params['where']) {
			url_parts.push("where[]="+params['where'][key]);
		}
	}

	if ( params['page'] != 1 ) {
		url_parts.push("page="+params['page']);
	}


	consoleLog("Index of ? in url: " + (url.indexOf('*')));

	for (var i=0; i<url_parts.length; i++) {
		if (i == 0 && url.indexOf('?') === -1) {
			url = url + "?" + url_parts[i];
		}
		else {
			url = url + "&" + url_parts[i];
		}
	}

	consoleLog("base path: " + path);
	consoleLog("params: " + params);
	consoleLog("url parts: " + url_parts);
	consoleLog("final url: " + url);

	return url;
};

var ajaxRequest = function(url,$target) {

	$.ajax({
		type: 'GET',
		url: url,
		success: function (data) {
			$target.find(".content table tbody").html($(data).find('tbody').html());
			$target.find(".ajax_pagination[scrollup='true']").html($(data).find(".ajax_pagination[scrollup='true']").html());
			$target.find(".ajax_pagination[scrollup='false']").html($(data).find(".ajax_pagination[scrollup='false']").html());
		},
		error: function (data) {
			console.log(data);
		}
	});

};

var activateTicketDraftMode = function() {
	setInterval(function(){ 
		
		var dummy_id = 0;

		var data = {
			'company_id' 		: $("select#company_id").val() ? $("select#company_id").val() : dummy_id,
			'contact_id' 		: $("input#contact_id").val() ? $("input#contact_id").val() : dummy_id,
			'equipment_id' 		: $("input#equipment_id").val() ? $("input#equipment_id").val() : dummy_id,
			'linked_tickets_id' : $("input#linked_tickets_id").val() ? $("input#linked_tickets_id").val() : '',
			'title' 			: $("input#title").val() ? $("input#title").val() : '[undefined]',
			'assignee_id' 		: $("select#assignee_id").val() ? $("select#assignee_id").val() : dummy_id,
			'post' 				: CKEDITOR.instances['post'].getData() ? CKEDITOR.instances['post'].getData() : '[undefined]',
			'tagit' 			: $("input#tagit").val() ? $("input#tagit").val() : '',
			'division_id' 		: $("select#division_id").val() ? $("select#division_id").val() : dummy_id,
			'additional_emails' : $("input#additional_emails").val() ? $("input#additional_emails").val() : '[undefined]',
			'job_type_id' 		: $("select#job_type_id").val() ? $("select#job_type_id").val() : dummy_id,
			'priority_id' 		: $("select#priority_id").val() ? $("select#priority_id").val() : dummy_id,
			'status_id' 		: draft_ticket_id,
		}

		$.ajax({
			'headers': { "X-CSRF-Token": $('[name=_token]').val() },
			'type': 'POST',
			'url': '/tickets',
			'data' : data,
			'success' : function(data) {
				consoleLog('success ticket draft:');
				consoleLog(data);
			},
			'error' : function (data){
				consoleLog('error ticket draft:');
				consoleLog(data.responseText);
			}
		});

	}, 1000);
};

var savePostDraft = function(callback) {
	if (timer) {
		clearTimeout(timer);
		timer = null;
	}
	
	timer = setTimeout(function () {
		var data = {
			'ticket_id' : url.target_id,
			'post' : CKEDITOR.instances['post'].getData() ? CKEDITOR.instances['post'].getData() : '[undefined]',
		}

		$.ajax({
			'headers': { "X-CSRF-Token": $('[name=_token]').val() },
			'type': 'POST',
			'url': '/posts',
			'data' : data,
			'success' : function () {
				if (typeof callback == "function") {
					callback();
				}	
			}
		});
	}, 1000);
};

var updateContacts = function(company_id, callback) {
	var target = $('select#fake_contact_id')
	$.get('/API/contacts/all?where[]=companies.id|=|'+company_id+'&paginate=false', function (data) {
		target.html('');
		target.append('<option value="NULL">-</option>');
		for (var i = 0; i<data.length; i++)
			target.append('<option value="'+data[i].id+'">'+data[i].last_name+' '+data[i].first_name+'</option>');
		if (typeof callback === 'function') callback();
	});
};

var updateEquipment = function(company_id, callback) {
	var target = $('select#fake_equipment_id');
	$.get('/API/equipment/all?where[]=companies.id|=|'+company_id+'&paginate=false', function (data) {
		target.html('');
		target.append('<option value="NULL">-</option>');
		for (var i = 0; i<data.length; i++)
			target.append('<option value="'+data[i].id+'">'+data[i].serial_number+" - "+data[i].notes+'</option>');
		if (typeof callback === 'function') callback();
	});
};

var updateLinkableTickets = function(company_id, callback) {
	var target = $('select#fake_linked_tickets_id');
	$.get('/API/tickets/all?where[]=companies.id|=|'+company_id+'&where[]=tickets.id|!=|'+url.target_id+'&paginate=false', function (data) {
		target.html('');
		for (var i = 0; i<data.length; i++)
			target.append('<option value="'+data[i].id+'">#'+data[i].id+" - "+data[i].title+'</option>');
			$('.selectpicker').selectpicker('refresh');
		if (typeof callback === 'function') callback();
	});
};

var fillSelectFields = function(callback) {

	var company_id = $(".ajax_trigger#company_id").val();

	if (company_id != '') {
		updateContacts(company_id, function () {
			updateEquipment(company_id, function () {
				updateLinkableTickets(company_id, function () {
					setSelected();					
				});
			});
		});			
	}
};

var setSelected = function() {
	var fake_equipment = $("#equipment_id").val() == 0 || $("#equipment_id").val() == '' ? "NULL" : $("#equipment_id").val();
	var fake_contact = $("#contact_id").val() == 0 || $("#contact_id").val() == '' ? "NULL" : $("#contact_id").val();
	var fake_linked_tickets = $("#linked_tickets_id").val() == 0 || $("#linked_tickets_id").val() == '' ? "" : $("#linked_tickets_id").val();
	$("#fake_equipment_id").val(fake_equipment);
	$("#fake_contact_id").val(fake_contact);
	$("#fake_linked_tickets_id").selectpicker('val',fake_linked_tickets.split(","));
};

var updateInternal = function() {
	if ($("#has_internal").val() == 0) {
		$("#job_number_internal, label[for='job_number_internal']").prop('disabled', true);
	}
	else { 
		$("#job_number_internal, label[for='job_number_internal']").prop('disabled', false);
	}
};

var updateRemote = function() {
	if ($("#has_remote").val() == 0) {
		$("#job_number_remote, label[for='job_number_remote']").prop('disabled', true);
	}
	else { 
		$("#job_number_remote, label[for='job_number_remote']").prop('disabled', false);
	}
};

var updateOnsite = function() {
	if ($("#has_onsite").val() == 0) {
		$("#job_number_onsite, label[for='job_number_onsite']").prop('disabled', true);
	} 
	else {
		$("#job_number_onsite, label[for='job_number_onsite']").prop('disabled', false);
	}
};

var updateTechInternal = function(that) {
	var index = that.id.substring(that.id.length-2,that.id.length-1);
	that.value == 0 ? $(".tech_internal_group\\["+index+"\\]").prop('disabled', true) : $(".tech_internal_group\\["+index+"\\]").prop('disabled', false);
};

var updateTechRemote = function(that) {
	var index = that.id.substring(that.id.length-2,that.id.length-1);
	that.value == 0 ? $(".tech_remote_group\\["+index+"\\]").prop('disabled', true) : $(".tech_remote_group\\["+index+"\\]").prop('disabled', false);
};

var updateTechOnsite = function(that) {
	var index = that.id.substring(that.id.length-2,that.id.length-1);		
	that.value == 0 ? $(".tech_onsite_group\\["+index+"\\]").prop('disabled', true) : $(".tech_onsite_group\\["+index+"\\]").prop('disabled', false);
};

var  updateRowLevel = function() {
	consoleLog("updated log level");
	consoleLog("Number of rows: " + $(".escalation_event_form").length);
	var counter = 0;
	$(".escalation_event_form").each(function () {
		// update label row
		$(this).find(".escalation_level").html("Event #"+parseInt(counter+1));
		// update form fields ids
		
		$(this).find(".level_id").attr("name", "level_id["+counter+"]");
		$(this).find(".delay_time").attr("name", "delay_time["+counter+"]");
		$(this).find(".event_id").attr("name", "event_id["+counter+"][]");
		$(this).find(".priority_id").attr("name", "priority_id["+counter+"]");

		// update record number holder
		$("#num").val(counter+1);
		counter++;
	});
};

var updateServicePage = function () {

	updateInternal();
	updateRemote();
	updateOnsite();

	$("[id^=tech_has_internal]").each(function () {
		updateTechInternal(this);
	});

	$("[id^=tech_has_remote]").each(function () {
		updateTechRemote(this);
	});

	$("[id^=tech_has_onsite]").each(function () {
		updateTechOnsite(this);
	});
};

var updateMenuPosition = function () {
	
	var wrapper_top = parseInt($('.wrapper').offset().top),
		wrapper_height = parseInt($('.wrapper').css('height')),
		wrapper_padding = parseInt($('.wrapper').css('padding-top')),
		menu_height = parseInt($('.vertical_menu .panel-group').css('height')),
		from_top = parseInt($(document).scrollTop()),
		left_over = wrapper_height - from_top,
		offset = (left_over + wrapper_top) - menu_height - wrapper_padding;

    if ($(document).scrollTop() > wrapper_top) {
    	if (offset < wrapper_padding) {
    		$('.vertical_menu').css('position','fixed').css('top',offset+'px');
    	}
    	else {
    		$('.vertical_menu').css('position','fixed').css('top',wrapper_padding+'px');
    	}

    } else {
        $('.vertical_menu').css('position','relative').css('top','0px');
    }
};

function setupStatusSlider() {
	$.get('/API/statuses/all?where[]=statuses.id|IN|2:3:4:6:7&paginate=false', function (data) {

		var item = [];
	
		item['labels'] = $.map(data, function(dataItem) { return dataItem.name; });
		item['ids'] = $.map(data, function(dataItem) { return dataItem.id; });

		$.get('/API/tickets/all?where[]=tickets.id|=|'+url.target_id+'&paginate=false', function (data) {
	
			var status_id = data[0].status_id;
	
			var slider = $("#status_id").slider({
				id: 'status_id',
			    ticks: item['ids'],
			    value: status_id,
			    ticks_labels: item['labels'],
			    ticks_snap_bounds: 30,
			    tooltip: 'hide',
			    ticks_positions: [0,25,50,75,100]
			});

			slider.on('slideStop',function() {
				var status_id = slider.slider('getValue');

				if (status_id == 3 || status_id == 6) {
					$("#is_public").bootstrapSwitch('state', true);						// set public true
					$("#email_company_contact").bootstrapSwitch('state', true);			// send to contacts if toggle public 
				}

			});
		});
	});
}

function setupPrioritySlider() {
	$.get('/API/priorities/all?paginate=false', function (data) {

		var item = [];
	
		item['labels'] = $.map(data, function(dataItem) { return dataItem.name; });
		item['ids'] = $.map(data, function(dataItem) { return dataItem.id; });

		$.get('/API/tickets/all?where[]=tickets.id|=|'+url.target_id+'&paginate=false', function (data) {
	
			var priority_id = data[0].priority_id;
	
			var slider = $("#priority_id").slider({
				id: 'status_id',
			    ticks: item['ids'],
			    value: priority_id,
			    ticks_labels: item['labels'],
			    ticks_snap_bounds: 30,
			    tooltip: 'hide',
			    reversed: true
			});

			slider.on('slideStop',function() {
				var priority_id = slider.slider('getValue');
			});
		});
	});
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@ boot-strap jquery tools
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

// url id, action, target
consoleLog(url);

$(window).scroll(function(){
	updateMenuPosition();
});

// set scrolling select list default phone browser
$('.selectpicker').selectpicker('mobile');

// date picker
$('.datepicker').datepicker({ autoclose: true });

// bootstrap tooltips
$('[data-toggle="tooltip"]').tooltip();   

	// bootstrap popover
$('[data-toggle="popover"]').popover();

// ckeditor
if ($("textarea#post").length != 0) { CKEDITOR.replace('post'); }

// bootstrap switch
$("input.switch").bootstrapSwitch({radioAllOff:true});

// ajax
$.ajaxSetup({ cache: false });

// prevent double submittion script
$('form').preventDoubleSubmission();

Dropzone.autoDiscover = false;

// trigger ajax request when changing page
$(".ajax_pagination li").live('click',function (e) {
	var $target = $(this).closest("div[ajax-route]");
	e.preventDefault();
	$(this).attr("selected","true");
	ajaxUpdate($target);
	if ($(this).closest(".ajax_pagination").attr('scrollup') == 'true') {
		scrollUp(500);
	}
});

// apply a loading overlay so when ajax request is in progress no new requests can be submitted
$('#loading').hide().ajaxStart(function() {
	if (url.target_action == "index") {
		$(this).show();
		$("body").addClass("in-progress");
	}
}).ajaxStop(function() {
	if (url.target_action == "index") {
		$(this).hide();
		$("body").removeClass("in-progress");
		$(".pagination").rPage();
	}
});

$('.panel-heading[data-toggle="collapse"]').click(function () {
	$('.panel-collapse').collapse('hide');
	updateMenuPosition();
})

// responsive pagination
$(".pagination").rPage();

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/{any}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (true) {

	//reset values of filters 
	$('.multifilter').selectpicker('deselectAll');

	//reset values of search field
	$("input[type='text'].search").val("");

	// trigger ajax request when searching
	$("input[type='text'].search").on("keyup", function () {
		var $target = $(this).closest("div[ajax-route]");
		ajaxUpdate($target);
	});

	// trigger ajax request when filtering
	$("select.selectpicker.multifilter").on("change", function () {
		var $target = $(this).closest("div[ajax-route]");
		ajaxUpdate($target);
	});

	// reset filters
	$("#reset_filters > button").on("click", function() {
		var $target = $(this).closest("div[ajax-route]");
		resetFilter($target);
	});

	$("#expand_filters").click(function() {
		$("#filters").style("display","block","important");
		$(this).remove();
	});

	$("tr.orderable th").on("click", function () {
		var $target = $(this).closest("div[ajax-route]");
		toggleOrder($(this));
		ajaxUpdate($target);
	});
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/contacts/create
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if ((url.target == "companies" || url.target == "contacts") && url.target_action == "create") {
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
	    serviceUrl: function (query) {
	    	return "/API/people/all?paginate=false&where[]=first_name:last_name|LIKE|*"+query+"*";
	    },
	    onSelect: function (suggestion) {
	    	console.log(suggestion);
	    	$("#person_id").prop("disabled",false);
			$("#person_fn, #person_ln").prop("readonly",true);
	    	$("#person_id").val(suggestion.data.id);
	    	$("#person_fn").val(suggestion.data.first_name);		    	
	    	$("#person_ln").val(suggestion.data.last_name);
	    	$(".input-group-addon").css("display","table-cell");
	    	$("#input_group_fn, #input_group_ln").addClass("input-group");
	    },
    	transformResult: function(response) {

        	var data = JSON.parse(response);

        	var returned = {
        		query: "Unit",
            	suggestions: $.map(data, function(dataItem) {
            		var value = dataItem.last_name+' '+dataItem.first_name;
            		var dataItem = dataItem;
                	return { value: value, data: dataItem};
        		})
        	};

    		return returned;
    	}
	});
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/tickets/create AND @/tickets/{id}/edit
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "tickets" && (url.target_action == "create" || url.target_action == "edit")) {

	fillSelectFields();

	$("#fake_equipment_id").on("change",function () {
		var equipment_id = $(this).val() == "NULL" ? "" : $(this).val();
		$("#equipment_id").val(equipment_id);
	});

	$("#fake_contact_id").on("change",function () {
		var contact_id = $(this).val() == "NULL" ? "" : $(this).val();
		$("#contact_id").val(contact_id);
	});

	$("#fake_linked_tickets_id").on("change",function () {
		var linked_tickets_id = $(this).val() ? $(this).val().join(",") : "";
		$("#linked_tickets_id").val(linked_tickets_id);
	});

	// update fileds related to selection of company (like contacts, equipment, ...)
	$(".ajax_trigger#company_id").on("change",function () {
		$("#equipment_id").val("");
		$("#contact_id").val("");
		$("#linked_tickets_id").val("");
		fillSelectFields();
	});

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

	if (url.target == "tickets" && url.target_action == "create") {
		activateTicketDraftMode();
	}
	else if (url.target == "tickets" && url.target_action == "edit") {
		$.get('/tickets/'+url.target_id, function (data) {
			var status_id = data.status_id;
			if (status_id == draft_ticket_id) {
				activateTicketDraftMode();
			}
		});
	}
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/tickets/{id}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "tickets" && url.target_action == "show") {
	if (typeof CKEDITOR.instances['post'] != 'undefined') {								// if it is supported
		CKEDITOR.instances['post'].on('change',function () {
			if (url.target == "tickets" && url.target_action == "show") {			
				savePostDraft();
			}
		});
	}

	$("#is_public").on('switchChange.bootstrapSwitch',function() {						// if toggle public
		var current = $(this).bootstrapSwitch('state');
		if (current) {
			$("#email_company_contact").bootstrapSwitch('state', true, current);		// send to contacts if toggle public 
		}
	});



	setupStatusSlider();

	setupPrioritySlider();
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/tickets/create AND @/tickets/{id}/edit AND @/posts/{id}/edit
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if ((url.target == "tickets" && (url.target_action == "show" || url.target_action == "create" || url.target_action == "edit")) ||
	(url.target == "posts" && url.target_action == "edit")) {

	$("#dZUpload").dropzone({
		url: "/files",
		addRemoveLinks: true,
		autoProcessQueue: false,
		maxFiles: 10,
		parallelUploads: 10,
		maxFileSize: 50,
		headers: { "X-CSRF-Token": $("[name=_token]").val() },

		// get list of files already loaded
		init: function () {

			var that = this;
			
			// dropzone for tickets
			if (url.target == "tickets" && (url.target_action == "create" || url.target_action == "edit")) {
				that.options.target = url.target;
				that.options.target_id = url.target_id;
				that.options.target_action = url.target_action == "edit" ? "edit" : "create";
			}
			// dropzone for posts
			if ((url.target == "posts" && url.target_action == "edit") || (url.target == "tickets" && url.target_action == "show")) {
				that.options.target = "posts";
				that.options.target_id = url.target_id;
				that.options.target_action = url.target_action == "edit" ? "edit" : "create";
			}

			that.on("addedfile", function (){
				if (that.options.target == "posts" && that.options.target_action == "create") {
	    			consoleLog("Ticket draft created triggered by attachment upload");
	    			savePostDraft(function () {
	    				that.processQueue();
	    			});
	    		}
	    		else {
	    			setTimeout(function () {
						that.processQueue();
	    			},500);
	    		}
			});

			$.ajax({
				type: 'GET',
				url: "/ajax/files/"+that.options.target+"/"+that.options.target_action+"/"+that.options.target_id,
				headers: { "X-CSRF-Token": $("[name=_token]").val() },
				success: function (data) {
		        	consoleLog(data);
					for (var c=0; c<data.length; c++) {
						var mockFile = { name: data[c].file_name, id: data[c].id };
            			that.options.addedfile.call(that, mockFile);
            			if (data[c].thumbnail_id != null) {
            				that.options.thumbnail.call(that, mockFile, "/files/"+data[c].thumbnail_id);
            			}
            			mockFile.previewElement.classList.add('dz-success');
   						mockFile.previewElement.classList.add('dz-complete');
            		}
				},
				error: function (data) {
					consoleLog(data.responseText);
				}
			});
        },
        // send a file 
    	sending: function(file, xhr, formData) {
    		formData.append("target", this.options.target);
    		formData.append("target_id", this.options.target_id);
    		formData.append("target_action", this.options.target_action);
		},
		success: function (file, response) {
			consoleLog(response)
			file.previewElement.classList.add("dz-success");
			file.id = response.id;
		},
		error: function (file, response) {
			consoleLog(response)
			file.previewElement.classList.add("dz-error");
		},

		// delete a file in the dropzone
		removedfile: function(file) {
			$.ajax({
		        type: 'DELETE',
		        url: '/files/'+file.id,
				headers: { "X-CSRF-Token": $('[name=_token]').val() },
		        success: function (data) {
		        	consoleLog(data);
		        	var _ref;
			        if (file.previewElement) {
			          if ((_ref = file.previewElement) != null) {
			            _ref.parentNode.removeChild(file.previewElement);
			          }
			        }
		        },
		        error: function (data) {
		        	consoleLog(data);
		        }
		    });
		}
	});
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/roles/{id}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "groups" && url.target_action == "show") {
	$(".group_update_roles").bootstrapDualListbox({'infoText':""});
	consoleLog("bootstrapDualListbox loaded");
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/groups/{id}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "roles" && url.target_action == "show") {
	$('.role_update_permissions').bootstrapDualListbox({'infoText':""});
	consoleLog("bootstrapDualListbox loaded");
}


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/profiles/create/{company_id}/{tech?}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "services" && url.target_action == "create") {

	updateServicePage();

	$("#has_internal").on('change', function () {
		updateInternal();
	});
	
	$("#has_remote").on('change', function () {
		updateRemote();
	});

	$("#has_onsite").on('change', function () {
		updateOnsite();
	});

	$("[id^=tech_has_internal]").on('change', function () {
		updateTechInternal(this);
	});

	$("[id^=tech_has_remote]").on('change', function () {
		updateTechRemote(this);
	});

	$("[id^=tech_has_onsite]").on('change', function () {
		updateTechOnsite(this);
	});
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/escalation-profiles/{id}/{num?}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "escalation-profiles" && typeof url.target_id != "undefined") {

	updateRowLevel();

	$("#add_escalation_event").on("click", function() {
		consoleLog("added escalation event");
		var form = $(".escalation_event_form").first().clone();
		$(".escalation_event_form").last().after(form);
		form.find('.bootstrap-select .dropdown-toggle').remove();
		form.find("option").prop("selected", false);
        form.find('select').selectpicker();
		updateRowLevel();
	});

	$(".delete_escalation_event").live("click", function () {
		consoleLog("delete escalation event");
		if ($(".escalation_event_form").length > 1) {
			$(this).closest('tr').remove();
			updateRowLevel();
		}
	});
}

});
