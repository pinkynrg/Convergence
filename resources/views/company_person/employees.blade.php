<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $employees->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="people.last_name">Name</th>
				<th column="departments.name">Department</th>
				<th column="titles.name" class="hidden-xs">Title</th>
				<th column="company_person.phone">Phone</th>
				<th column="company_person.email">Email</th>			
			</tr>
		</thead>
		<tbody>

		@if ($employees->count())
				@foreach ($employees as $employee) 	

				<tr>
					<td> <a href="{{ route('people.show', $employee->person->id) }}"> {{ $employee->person->name() }} </a> </td>
					<td> {{ isset($employee->department_id) ? $employee->department->name : '' }} </td>
					<td class="hidden-xs"> {{ isset($employee->title_id) ? $employee->title->name : '' }} </td>
					<td> {!! $employee->phone() !!} </td>
					<td> {!! $employee->email() !!} </td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="5">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $employees->render() !!}
	</div>
</div>