@extends('layouts.default')

@section('content')

	<div class="visible-xs" id="expand_filters">
		<h4>Expand Filters</h4>
	</div>

	<div id="filters" class="row hidden-xs">
		
		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("companies", $companies, 
				["id" => "tickets.company_id", "selected_text" => "Companies Active", "search" => "true", "value" => "id", "label" => "!name"]);
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("assignees", $employees, 
				["id" => "tickets.assignee_id", "selected_text" => "Assignees Active", "search" => "true", "value" => "id", "label" => ["!first_name"," ","!last_name"]]);
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("creators", $employees, 
				["id" => "tickets.creator_id", "selected_text" => "Creators Active", "search" => "true", "value" => "id", "label" => ["!first_name"," ","!last_name"]]) 
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("divisions", $divisions, 
				["id" => "tickets.division_id", "selected_text" => "Divisions Active", "search" => "true", "value" => "id", "label" => "!name"]) 
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2">
			{!! 
				Form::BSMultiSelect("statuses", $statuses, 
				["id" => "tickets.status_id", "selected_text" => "Statuses Active", "search" => "true", "value" => "id", "label" => "!name"]) 
			!!}
		</div>

		<div class="col-xs-12 col-ms-4 col-md-2" id="reset_filters">
			<button type="button" class="btn btn-default">Reset Filters</button>
		</div>

	</div>

	<hr>

	@include('tickets/tickets', array('tickets' => $tickets))
	
@endsection
