"use strict"

$(document).ready(function() {

var debug = true;
var regex = /\/([a-zA-Z\-]*)([\/]?)(create|[\d]*)([\/]?)([a-zA-Z\-]*)([\/]?)/g;
var timer;
var path = window.location.pathname;
var rxres = regex.exec(path);

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

var titleMarquee = function() {
    document.title = document.title.substring(1)+document.title.substring(0,1);
    setTimeout(titleMarquee, 200);
};

var scrollUp = function(ms) {
	$("html, body").animate({
        scrollTop: 0
    }, ms);
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
		var url = $target.attr("ajax-route");
		url = getUrl(url,params);
		ajaxRequest(url,$target);
	}, 500);
};

var toggleOrder = function($elem) {
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
};

var getParams = function($target) {
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
		params['page'] = $(".ajax_pagination").find("li[selected] a").html();
	}
	else 
		params['page'] = 1;

	return params;
};

var getUrl = function(url, params) {

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
	
	return url;
};

var ajaxRequest = function(url,$target) {

	$.get(url, function (data) {
		$target.find(".content table tbody").html($(data).find('tbody').html());
		$target.find(".ajax_pagination[scrollup='true']").html($(data).find(".ajax_pagination[scrollup='true']").html());
		$target.find(".ajax_pagination[scrollup='false']").html($(data).find(".ajax_pagination[scrollup='false']").html());
	});
};

var activateTicketDraftMode = function() {
	setInterval(function(){ 
		
		var dummy_id = 0;

		var data = {
			'company_id' 		: $("select#company_id").val() ? $("select#company_id").val() : dummy_id,
			'contact_id' 		: $("input#contact_id").val() ? $("input#contact_id").val() : dummy_id,
			'equipment_id' 		: $("input#equipment_id").val() ? $("input#equipment_id").val() : dummy_id,
			'title' 			: $("input#title").val() ? $("input#title").val() : '[undefined]',
			'assignee_id' 		: $("select#assignee_id").val() ? $("select#assignee_id").val() : dummy_id,
			'post' 				: CKEDITOR.instances['post'].getData() ? CKEDITOR.instances['post'].getData() : '[undefined]',
			'tagit' 			: $("input#tagit").val() ? $("input#tagit").val() : '',
			'division_id' 		: $("select#division_id").val() ? $("select#division_id").val() : dummy_id,
			'additional_emails' : $("input#additional_emails").val() ? $("input#additional_emails").val() : '[undefined]',
			'job_type_id' 		: $("select#job_type_id").val() ? $("select#job_type_id").val() : dummy_id,
			'priority_id' 		: $("select#priority_id").val() ? $("select#priority_id").val() : dummy_id,
			'status_id' 		: 9,
		}

		$.ajax({
			'headers': { "X-CSRF-Token": $('[name=_token').val() },
			'type': 'POST',
			'url': '/tickets',
			'data' : data,
			'success' : function(data) {
				consoleLog('ticket draft: '+data);
			},
			'error' : function (data){
				consoleLog('ticket draft: '+data);
			}
		});

	}, 1000);
};

var activatePostDraftMode = function() {
	setInterval(function(){ 
		

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
			'headers': { "X-CSRF-Token": $('[name=_token').val() },
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
	$.get('/ajax/tickets/contacts/'+company_id, function (data) {
		data = JSON.parse(data);
		$('select#fake_contact_id').html('');
		$('select#fake_contact_id').append('<option value="NULL">-</option>');
		for (var i = 0; i<data.length; i++)
			$('select#fake_contact_id').append('<option value="'+data[i].id+'">'+data[i].last_name+' '+data[i].first_name+'</option>');
		if (typeof callback === 'function') callback();
	});
};

var updateEquipment = function(company_id, callback) {
	$.get('/ajax/tickets/equipment/'+company_id, function (data) {
		data = JSON.parse(data);				
		$('select#fake_equipment_id').html('');
		$('select#fake_equipment_id').append('<option value="NULL">-</option>');
		for (var i = 0; i<data.length; i++)
			$('select#fake_equipment_id').append('<option value="'+data[i].id+'">'+data[i].notes+'</option>');
		if (typeof callback === 'function') callback();
	});
};

var fillSelectFields = function(callback) {

	var company_id = $(".ajax_trigger#company_id").val();

	if (company_id != '') {
		updateContacts(company_id, function () {
			updateEquipment(company_id, function () {
				setSelected();
			});
		});			
	}
};

var setSelected = function() {
	var fake_equipment = $("#equipment_id").val() == 0 || $("#equipment_id").val() == '' ? "NULL" : $("#equipment_id").val();
	var fake_contact = $("#contact_id").val() == 0 || $("#contact_id").val() == '' ? "NULL" : $("#contact_id").val();
	$("#fake_equipment_id").val(fake_equipment);
	$("#fake_contact_id").val(fake_contact);
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
		$(this).find("select.delay_time").attr("name", "delay_time["+counter+"]");
		$(this).find("select.event_id").attr("name", "event_id["+counter+"]");
		$(this).find("select.fallback_contact_id").attr("name", "fallback_contact_id["+counter+"]");
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

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@ boot-strap jquery tools
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

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

// title maquee
titleMarquee();

consoleLog(url);

Dropzone.autoDiscover = false;

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/tickets
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target_action == "index") {
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
		$(this).attr("selected","true");
		ajaxUpdate($target);
		if ($(this).closest(".ajax_pagination").attr('scrollup') == 'true') {
			scrollUp(500);
		}
	});
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/companies/{id}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "companies" && url.target_action == "show") {

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
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/contacts/create
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "companies" && url.target_action == "create") {
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

	// update fileds related to selection of company (like contacts, equipment, ...)
	$(".ajax_trigger#company_id").on("change",function () {
		$("#equipment_id").val("");
		$("#contact_id").val("");
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
			if (status_id == 9) {
				activateTicketDraftMode();
			}
		});
	}
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/tickets/{id}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if (url.target == "tickets" && url.target_action == "show") {
	CKEDITOR.instances['post'].on('change',function () {
		if (url.target == "tickets" && url.target_action == "show") {			
			savePostDraft();
		}
	});
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
@/tickets/create AND @/tickets/{id}/edit AND @/posts/{id}/edit
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

if ((url.target == "tickets" && (url.target_action == "show" || url.target_action == "create" || url.target_action == "edit")) ||
	(url.target == "posts" && url.target_action == "edit")) {

	$("#dZUpload").dropzone({
		url: "/files",
		addRemoveLinks: true,
		autoProcessQueue:false,
		maxFiles: 3,
		maxFileSize: 50,
		headers: { "X-CSRF-Token": $('[name=_token').val() },

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

			that.on("drop", function (){
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
				headers: { "X-CSRF-Token": $('[name=_token').val() },
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
			// consoleLog(response)
			file.previewElement.classList.add("dz-error");
		},

		// delete a file in the dropzone
		removedfile: function(file) {
			$.ajax({
		        type: 'DELETE',
		        url: '/files/'+file.id,
				headers: { "X-CSRF-Token": $('[name=_token').val() },
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

if (url.target == "role" && url.target_action == "show") {
	$('.role_update_permissions').bootstrapDualListbox({'infoText':""});
	$('.group_update_roles').bootstrapDualListbox({'infoText':""});
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
		form.find("option").prop("selected", false);
		$(".escalation_event_form").last().after(form);
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
