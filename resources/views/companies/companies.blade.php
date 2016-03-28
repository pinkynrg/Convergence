<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $companies->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th id="thumb"></th>
				<th column="companies.name" weight="0" type="asc">Company</th>
				<th column="account_managers.last_name" class="hidden-xs hidden-ms">Account Manager</th>
				<th column="main_contacts.last_name" class="hidden-xs hidden-ms">Main Contact Name</th>
				<th column="company_person_main_contacts.cellphone" class="hidden-xs hidden-ms">Main Contact Phone</th>
				<th column="companies.country" class="hidden-xs hidden-ms">Country</th>
				<th column="companies.city" class="hidden-xs hidden-ms">City</th>
				<th column="companies.created_at" class="hidden-xs hidden-ms">Created</th>
				<th column="companies.updated_at" class="hidden-xs hidden-ms">Updated</th>
			</tr>
		</thead>
		<tbody>
			
			@if ($companies->count())
				@foreach ($companies as $company) 	
				<tr>
					<td>
						<div class="thumbnail thumb-xs">
							<img src="{{ $company->profile_picture()->thumbnail() }}">
						</div>
					</td>
					<td> 
						<a href="{{route('companies.show', $company->id) }}"> {{  $company->name }} </a> 
					</td>
					
					<td class="hidden-xs hidden-ms"> 
						@if (isset($company->account_manager->account_manager_id)) 
							<a href="{{route('people.show', $company->account_manager->company_person->person->id) }}"> {{  $company->account_manager->company_person->person->name() }} </a> 
						@endif 
					</td>
					
					<td class="hidden-xs hidden-ms"> 
						@if (isset($company->main_contact->main_contact_id)) 
							<a href="{{route('people.show', $company->main_contact->company_person->person->id) }}"> 
								{{ $company->main_contact->company_person->person->name() }} 
							</a> 
						@endif 
					</td>

					<td class="hidden-xs hidden-ms"> {!! isset($company->main_contact->main_contact_id) ? $company->main_contact->company_person->cellphone() : '' !!} </td>
					<td class="hidden-xs hidden-ms"> {{ $company->country }} </td>
					<td class="hidden-xs hidden-ms"> {{ $company->city }} </td>
					<td class="hidden-xs hidden-ms"> {{ $company->date("created_at") }} </td>
					<td class="hidden-xs hidden-ms"> {{ $company->date("updated_at") }} </td>
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