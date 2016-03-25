<div id="company_profile_picture">
	<img src="{{ $company->profile_picture()->path() }}">
</div>
<div id="company_details" class="table-responsive">
	<table class="table table-striped table-condensed table-hover">
		<tr>
			<th class="col-xs-6 col-md-3">Company</th>
			<td> {{ $company->name }} </td>			
		</tr>
		<tr>
			<th>Country</th>
			<td>{{ $company->country }}</td>
		</tr>
		<tr>
			<th>State</th>
			<td>{{ $company->state }}</td>
		</tr>
		<tr>
			<th>City</th>
			<td>{{ $company->city }}</td>
		</tr>
		<tr>	
			<th>Address</th>
			<td>{{ $company->address }}</td>
		</tr>
		<tr>
			<th>Zip Code</th>
			<td>{{ $company->zip_code }}</td>
		</tr>
		<tr>
			<th>Support Type</th>
			<td>{{ isset($company->support_type_id) ? $company->support_type->name : '' }}</td>
		</tr>
		<tr>
			<th>Account Manager</th>
			<td> @if (isset($company->account_manager->account_manager_id)) <a href="{{ route('people.show', $company->account_manager->company_person->person->id) }}"> {{ $company->account_manager->company_person->person->name()  }} @endif </a> </td>
		</tr>
		<tr>
			<th>Connection Option</th>
			<td> {!! isset($company->connection_type_id) ? "<b>" . $company->connection_type->name . "</b>: ". $company->connection_type->description : '' !!} </td>
		</tr>
		<tr>
			<th>Group Email</th>
			<td>{{ $company->group_email }}</td>
		</tr>
	</table>
</div>
