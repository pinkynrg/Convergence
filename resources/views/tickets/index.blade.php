@extends('layouts.default')

@section('content')
	
	<div class="filters">
		
		<select column="company_id" class="selectpicker multifilter" multiple title="Filter Company" data-count-selected-text="Company Filter Active" data-selected-text-format="count>0">			
			@foreach ($companies as $company)
				<option value="{{ $company->id }}"> {{ $company->name }} </option>
			@endforeach
		</select>

		<select column="assignee_id" class="selectpicker multifilter"  multiple title="Filter Assignee" data-count-selected-text="Assignee Filter Active" data-selected-text-format="count>0">
			@foreach ($employees as $employee)
				<option value="{{ $employee->id }}"> {{ $employee->person->name() }} </option>
			@endforeach
		</select>

		<select column="creator_id" class="selectpicker multifilter"  multiple title="Filter Creators" data-count-selected-text="Creators Filter Active" data-selected-text-format="count>0">
			@foreach ($employees as $employee)
				<option value="{{ $employee->id }}"> {{ $employee->person->name() }} </option>
			@endforeach
		</select>

		<select column="division_id" class="selectpicker multifilter"  multiple title="Filter Divisions" data-count-selected-text="Divisions Filter Active" data-selected-text-format="count>0">
			@foreach ($divisions as $division)
				<option value="{{ $division->id }}"> {{ $division->name }} </option>
			@endforeach
		</select>

		<select column="status_id" class="selectpicker multifilter"  multiple title="Filter Statuses" data-count-selected-text="Statuses Filter Active" data-selected-text-format="count>0">
			@foreach ($statuses as $status)
				<option value="{{ $status->id }}"> {{ $status->name }} </option>
			@endforeach
		</select>

		<input type="button" class="btn btn-default" id="reset_filters" value="Reset Filters">

	</div>

	@include('tickets/tickets', array('tickets' => $tickets))
	
@endsection
