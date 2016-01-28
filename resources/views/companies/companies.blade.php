<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $companies->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="name">Company</th>
				<th column="account_managers.last_name">Account Manager</th>
				<th column="people.last_name">Main Contact Name</th>
				<th column="company_person.cellphone">Main Contact Phone</th>
				<th column="company_person.country" class="hidden-xs">Country</th>
				<th column="company_person.city" class="hidden-xs">City</th>
				<th column="company_person.created_at" class="hidden-xs">Created</th>
				<th column="company_person.updated_at" class="hidden-xs">Updated</th>
			</tr>
		</thead>
		<tbody>
			
			@if ($companies->count())
				@foreach ($companies as $company) 	
				<tr>
					<td> <a href="{{route('companies.show', $company->id) }}"> {{  $company->name }} </a> </td>
					<td> @if (isset($company->account_manager->account_manager_id)) <a href="{{route('people.show', $company->account_manager->company_person->person->id) }}"> {{  $company->account_manager->company_person->person->name() }} </a> @endif </td>
					<td> @if (isset($company->main_contact->main_contact_id)) <a href="{{route('people.show', $company->main_contact->company_person->person->id) }}"> {{ $company->main_contact->company_person->person->name() }} </a> @endif </td>
					<td> {!! isset($company->main_contact->main_contact_id) ? $company->main_contact->company_person->cellphone() : '' !!} </td>
					<td class="hidden-xs hidden-sm"> {{ $company->country }} </td>
					<td class="hidden-xs"> {{ $company->zip_code }} </td>
					<td class="hidden-xs"> {{ $company->date("created_at") }} </td>
					<td class="hidden-xs"> {{ $company->date("updated_at") }} </td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="9">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $companies->render() !!}
	</div>

</div>