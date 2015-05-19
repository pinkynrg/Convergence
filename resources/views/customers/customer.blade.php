<table class="table table-striped table-condensed table-hover">
	<tr>
		<th>Customer</th>
		<td> {{ $customer->company_name }} </td>
		<th>Country</th>
		<td>{{ $customer->country }}</td>
	</tr>
	<tr>
		<th>State</th>
		<td>{{ $customer->state }}</td>
		<th>City</th>
		<td>{{ $customer->city }}</td>
	</tr>
	<tr>	
		<th>Address</th>
		<td>{{ $customer->address }}</td>
		<th>Zip Code</th>
		<td>{{ $customer->zip_code }}</td>
	</tr>
	<tr>
		<th>Airport</th>
		<td>{{ $customer->airport }}</td>
		<th>Plant Requirment</th>
		<td>{{ $customer->plant_requirment }}</td>
	</tr>
	<tr>
		<th>Support Type</th>
		<td>{{ $customer->support_type }}</td>
		<th>Account Manager</th>
		<td> <a href="{{ route('employees.show', $customer->account_manager_id) }}"> {{ $customer->account_manager ? $customer->account_manager->name() : '' }} </a> </td>
	</tr>
	<tr>
		<th>Connection Option</th>
		<td>{{ $customer->connection_option }}</td>
		<th>Group Email</th>
		<td>{{ $customer->group_email }}</td>
	</tr>
</table>