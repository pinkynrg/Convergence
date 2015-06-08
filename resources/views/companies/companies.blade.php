<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $companies->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="name">Company</th>
				<th column="people.last_name">Main Contact Name</th>
				<th column="company_person.cellphone">Main Contact Phone</th>
				<th column="country" class="hidden-xs">Country</th>
				<th column="city" class="hidden-xs">City</th>
				<th column="address" class="hidden-xs">Address</th>
				<th column="zip_code" class="hidden-xs">Zip Code</th>
			</tr>
		</thead>
		<tbody>
			
			@if ($companies->count())
				@foreach ($companies as $company) 	
				<tr>
					<td> <a href="{{route('companies.show', $company->id) }}"> {{  $company->name }} </a> </td>
					<td> {{ isset($company->main_contact->main_contact_id) ? $company->main_contact->company_person->person->name() : '' }} </td>
					<td> {!! isset($company->main_contact->main_contact_id) ? $company->main_contact->company_person->cellphone() : '' !!} </td>
					<td class="hidden-xs hidden-sm"> {{ $company->country }} </td>
					<td class="hidden-xs"> {{ $company->city }} </td>
					<td class="hidden-xs"> {{ $company->address }} </td>
					<td class="hidden-xs"> {{ $company->zip_code }} </td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="7">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $companies->render() !!}
	</div>

</div>