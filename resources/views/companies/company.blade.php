<table class="table table-striped table-condensed table-hover">
	<tr>
		<th>Company</th>
		<td> {{ $company->name }} </td>
		<th>Country</th>
		<td>{{ $company->country }}</td>
	</tr>
	<tr>
		<th>State</th>
		<td>{{ $company->state }}</td>
		<th>City</th>
		<td>{{ $company->city }}</td>
	</tr>
	<tr>	
		<th>Address</th>
		<td>{{ $company->address }}</td>
		<th>Zip Code</th>
		<td>{{ $company->zip_code }}</td>
	</tr>
	<tr>
		<th>Airport</th>
		<td>{{ $company->airport }}</td>
		<th>Plant Requirment</th>
		<td>{{ $company->plant_requirment }}</td>
	</tr>
	<tr>
		<th>Support Type</th>
		<td>{{ $company->support_type }}</td>
		<th>Account Manager</th>
		<td> <a href="{{ route('people.show', $company->account_manager_id) }}"> {{ isset($company->account_manager[0]) ? $company->account_manager[0]->name() : '' }} </a> </td>
	</tr>
	<tr>
		<th>Connection Option</th>
		<td>{{ $company->connection_option }}</td>
		<th>Group Email</th>
		<td>{{ $company->group_email }}</td>
	</tr>
</table>