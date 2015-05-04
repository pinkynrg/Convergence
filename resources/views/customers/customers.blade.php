<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>Customer</th>
			<th>Main Contact</th>
			<th class="hidden-xs hidden-sm">Country</th>
			<th class="hidden-xs">City</th>
			<th class="hidden-xs">Address</th>
			<th class="hidden-xs">Zip Code</th>
		</tr>
	</thead>
	<tbody>

		@foreach ($customers as $customer) 	

		<tr>
			<td> <a href="{{route('customers.show', $customer->id) }}"> {{  $customer->company_name }} </a> </td>
			<td> {{ isset($customer->main_contact->id) ? $customer->main_contact->cellphone : '-' }} </td>
			<td class="hidden-xs hidden-sm"> {{ $customer->country }} </td>
			<td class="hidden-xs"> {{ $customer->city }} </td>
			<td class="hidden-xs"> {{ $customer->address }} </td>
			<td class="hidden-xs"> {{ $customer->zip_code }} </td>
		</tr>

		@endforeach

	</tbody>
</table>

<div class="ajax_pagination" scrollup="true" route="{{ route('customers.customers.ajax') }}">
	{!! $customers->render() !!}
</div>