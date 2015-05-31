<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $contacts->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="people.last_name">Name</th>
				<th column="companies.name">Company</th>
				<th column="departments.name">Department</th>
				<th column="titles.name" class="hidden-xs">Title</th>
				<th column="people.phone">Phone</th>
				<th column="people.email">Email</th>			
			</tr>
		</thead>
		<tbody>

			@foreach ($contacts as $contact) 	

			<tr>
				<td> <a href="{{ route('people.show', $contact->person->id) }}"> {{ $contact->person->name() }} </a> </td>
				<td> <a href="{{ route('companies.show', $contact->company->id) }}"> {{ $contact->company->name }} </a> </td>
				<td> {{ isset($contact->department) ? $contact->department->name : '' }} </td>
				<td class="hidden-xs"> {{ isset($contact->title) ? $contact->title->name : '' }} </td>
				<td> {{ $contact->person->phone() }} </td>
				<td> {{ $contact->person->email }} </td>
			</tr>

			@endforeach

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $contacts->render() !!}
	</div>
</div>