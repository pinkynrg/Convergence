<div class="content">
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
				<td> <a href="{{ route('employees.show', $employee->id) }}"> {{ $employee->name() }} </a> </td>
				<td> {{ $employee->department->name }} </td>
				<td class="hidden-xs"> {{ $employee->title->name }} </td>
				<td> {{ $employee->phone }} </td>
				<td> {{ $employee->email }} </td>
			</tr>

			@endforeach

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $employees->render() !!}
	</div>
</div>