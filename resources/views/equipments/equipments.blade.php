<div class="content">
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="cc_number">CC</th>
				<th column="name" class="hidden-xs">Name</th>
				<th column="customers.company_name" class="hidden-xs">Customer</th>
				<th column="serial_number" class="hidden-xs">Serial Number</th>
				<th column="equipment_types.name" class="hidden-xs">Equipment Type</th>
				<th column="notes">Notes</th>
				<th column="warranty_expiration" class="hidden-xs">Warranty Expiration</th>
			</tr>
		</thead>
		<tbody>

			@foreach ($equipments as $equipment) 	

			<tr>
				<td> <a href="{{ route('equipments.show', $equipment->id) }}"> {{ '#'.$equipment->cc_number }} </a> </td>
				<td class="hidden-xs"> <a href="{{ route('equipments.show', $equipment->id) }}"> {{ $equipment->name }} </a> </td>
				<td class="hidden-xs"> <a href="{{ route('customers.show', $equipment->customer->id) }}"> {{ $equipment->customer->company_name }} </a> </td>
				<td class="hidden-xs"> <a href="{{ route('equipments.show', $equipment->id) }}"> {{ $equipment->serial_number }} </a> </td>
				<td class="hidden-xs"> {{ $equipment->equipment_type->name }} </td>
				<td> {{ $equipment->notes }} </td>
				<td class="hidden-xs"> {{ $equipment->warranty_expiration }} </td>
			</tr>

			@endforeach

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true" route="{{ route('ajax.equipments') }}">
		{!! $equipments->render() !!}
	</div>
</div>
