<div class="content">

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="company_name">Customer</th>
				<th column="contacts.cellphone">Main Contact</th>
				<th column="country" class="hidden-xs hidden-sm">Country</th>
				<th column="city" class="hidden-xs">City</th>
				<th column="address" class="hidden-xs">Address</th>
				<th column="zip_code" class="hidden-xs">Zip Code</th>
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

	<div class="ajax_pagination" scrollup="true">
		{!! $customers->render() !!}
	</div>

</div>