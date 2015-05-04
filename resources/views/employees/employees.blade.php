<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>Name</th>
			<th>Department</th>
			<th class="hidden-xs">Title</th>
			<th>Phone</th>
			<th>Email</th>			
		</tr>
	</thead>
	<tbody>

		@foreach ($employees as $employee) 	

		<tr>
			<td> <a href="{{ route('employees.show', $employee->id) }}"> {{ $employee->name() }} </a> </td>
			<td> {{ $employee->department->name }} </td>
			<td class="hidden-xs"> {{ $employee->title->name }} </td>
			<td> {{ $employee->phone }} </td>
			<td> {{ $employee->email }} </td>
		</tr>

		@endforeach

	</tbody>
</table>

<div class="ajax_pagination" scrollup="true" route="{{ route('employees.employees.ajax') }}">
	{!! $employees->render() !!}
</div>