<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>CC</th>
			<th>Name</th>
			<th>Equipment Type</th>
			<th>Serial Number</th>
			<th class="hidden-xs">Notes</th>
			<th class="hidden-xs">Warranty Expiration</th>
		</tr>
	</thead>
	<tbody>
	
	@foreach ($equipments as $equipment)

		<tr>
			<td> <a href="{{ route('equipments.show', $equipment->id) }}"> {{ $equipment->cc_number }} </a> </td>
			<td> {{ $equipment->name }} </td>
			<td> {{ $equipment->equipment_type->name }} </td>
			<td> {{ $equipment->serial_number }} </td>
			<td class="hidden-xs"> {{ $equipment->notes }} </td>
			<td class="hidden-xs"> {{ $equipment->warranty_expiration }} </td>
		</tr>

	@endforeach

	</tbody>
</table>	

<div class="ajax_pagination" route="{{ route('customers.equipments.ajax',$equipments[0]->customer_id) }}">
	{!! $equipments->render() !!}
</div>
