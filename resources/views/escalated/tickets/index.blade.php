@extends('layouts.default')

@section('content')

	<div class="visible-xs" id="expand_filters">
		<h4>Expand Filters</h4>
	</div>

	<div id="filters" class="row hidden-xs">
		
		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("tickets.company_id", $companies, 
				["title" => "companies", "selected_text" => "Companies Active", "search" => "true", "data-size" => "5", "value" => "id", "label" => "!name"]);
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("tickets.assignee_id", $employees, 
				["title" => "assignees", "selected_text" => "Assignees Active", "search" => "true", "data-size" => "5", "value" => "id", "label" => ["!last_name"," ","!first_name"]]);
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("tickets.creator_id", $employees, 
				["title" => "creators", "selected_text" => "Creators Active", "search" => "true", "data-size" => "5", "value" => "id", "label" => ["!last_name"," ","!first_name"]]) 
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("tickets.division_id", $divisions, 
				["title" => "divisions", "selected_text" => "Divisions Active", "search" => "true", "data-size" => "5", "value" => "id", "label" => "!name"]) 
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("tickets.status_id", $statuses, 
				["title" => "statuses", "selected_text" => "Statuses Active", "search" => "true", "data-size" => "5", "value" => "id", "label" => "!name"]) 
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2" id="reset_filters">
			<button type="button" class="btn btn-default">Reset Filters</button>
		</div>

	</div>

	<hr>

	@include('escalated/tickets/tickets', array('tickets' => $tickets))
	
@endsection
