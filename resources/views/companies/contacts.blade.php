<div class="content">
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="people.last_name">Name</th>
				<th column="departments.name">Department</th>
				<th column="titles.name" class="hidden-xs">Title</th>
				<th column="company_person.phone" class="hidden-xs">Phone</th>
				<th column="company_person.cellphone" >Cellphone</th>
				<th column="company_person.email" class="hidden-xs">Email</th>
				<th column="company_main_contact.id" class="hidden-xs">Main Contact</th>
			</tr>
		</thead>
		<tbody>
		
		@foreach ($contacts as $contact)

			<tr>
				<td> <a href="{{ route('people.show', $contact->person->id) }}"> {{ $contact->person->name() }} </a> </td>
				<td> {{ isset($contact->department_id) ? $contact->department->name : '' }} </td>
				<td class="hidden-xs"> {{ isset($contact->title_id) ? $contact->title->name : '' }} </td>
				<td class="hidden-xs"> {!! $contact->phone() !!} </td>
				<td class="hidden-xs"> {!! $contact->cellphone() !!} </td>
				<td> {{ $contact->email }} </td>
				<td>
					@if (isset($company->main_contact->id) && $company->main_contact->main_contact_id == $contact->id) 
						<i class="fa fa-check-circle-o" style="color: #5EBA0A"></i> 
					@else
						<i class="fa fa-check-circle-o" style="color: #DDD"></i>
					@endif
				</td>
			</tr>

		@endforeach

		</tbody>
	</table>	

	<div class="ajax_pagination" scrollup="false">
		{!! $contacts->render() !!}
	</div>
</div>
