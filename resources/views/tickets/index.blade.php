@extends('layouts.default')

@section('content')

	@if (Auth::user()->active_contact->isE80())

		<div class="visible-xs" id="expand_filters">
			<h4>Expand Filters</h4>
		</div>

		<div id="filters" class="row hidden-xs">
			
			<div class="col-xs-12 col-ms-4 col-md-2">
				{!! 
					Form::BSMultiSelect("tickets.company_id", $companies, 
					["title" => "companies", "selected_text" => "Companies", "class" => "multifilter", "search" => "true", "data-size" => "5", "value" => "id", "label" => "!name"]);
				!!}
			</div>

			<div class="col-xs-12 col-ms-4 col-md-2">
				{!! 
					Form::BSMultiSelect("tickets.assignee_id", $employees, 
					["title" => "assignees", "selected_text" => "Assignees", "class" => "multifilter", "search" => "true", "data-size" => "5", "value" => "id", "label" => ["!last_name"," ","!first_name"]]);
				!!}
			</div>

			<div class="col-xs-12 col-ms-4 col-md-2">
				{!! 
					Form::BSMultiSelect("tickets.creator_id", $employees, 
					["title" => "creators", "selected_text" => "Creators", "class" => "multifilter", "search" => "true", "data-size" => "5", "value" => "id", "label" => ["!last_name"," ","!first_name"]]) 
				!!}
			</div>

			<div class="col-xs-12 col-ms-4 col-md-2">
				{!! 
					Form::BSMultiSelect("tickets.division_id", $divisions, 
					["title" => "divisions", "selected_text" => "Divisions", "class" => "multifilter", "search" => "true", "data-size" => "5", "value" => "id", "label" => "!name"]) 
				!!}
			</div>

			<div class="col-xs-12 col-ms-4 col-md-2">
				{!! 
					Form::BSMultiSelect("tickets.status_id", $statuses, 
					["title" => "statuses", "selected_text" => "Statuses", "class" => "multifilter", "search" => "true", "data-size" => "5", "value" => "id", "label" => "!name"]) 
				!!}
			</div>

			<div class="col-xs-12 col-ms-4 col-md-2" id="reset_filters">
				<button type="button" class="btn btn-default">Reset Filters</button>
			</div>

		</div>

		<hr>

	@endif

	@include('tickets/tickets', array('tickets' => $tickets))
	
@endsection
