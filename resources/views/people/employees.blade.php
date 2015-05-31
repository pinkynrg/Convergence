<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $employees->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="last_name">Name</th>
				<th column="departments.name">Department</th>
				<th column="titles.name" class="hidden-xs">Title</th>
				<th column="phone">Phone</th>
				<th column="email">Email</th>			
			</tr>
		</thead>
		<tbody>

			@foreach ($employees as $employee) 	

			<tr>
				<td> <a href="{{ route('people.show', $employee->person->id) }}"> {{ $employee->person->name() }} </a> </td>
				<td> {{ isset($employee->department) ? $employee->department->name : '' }} </td>
				<td class="hidden-xs"> {{ isset($employee->title) ? $employee->title->name : '' }} </td>
				<td> {{ $employee->person->phone() }} </td>
				<td> {{ $employee->person->email }} </td>
			</tr>

			@endforeach

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $employees->render() !!}
	</div>
</div>