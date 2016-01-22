<div class="content">
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="cc_number">CC</th>
				<th column="name">Name</th>
				<th column="equipment_types.name">Equipment Type</th>
				<th column="serial_number">Serial Number</th>
				<th column="notes" class="hidden-xs">Notes</th>
				<th column="warranty_expiration" class="hidden-xs">Warranty Expiration</th>
			</tr>
		</thead>
		<tbody>
		
		@foreach ($equipment as $equipment_unit)

			<tr>
				<td> <a href="{{ route('equipment.show', $equipment_unit->id) }}"> {{ $equipment_unit->cc_number }} </a> </td>
				<td> {{ $equipment_unit->name }} </td>
				<td> {{ $equipment_unit->equipment_type->name }} </td>
				<td> {{ $equipment_unit->serial_number }} </td>
				<td class="hidden-xs"> {{ $equipment_unit->notes }} </td>
				<td class="hidden-xs"> {{ isset($equipment_unit->warranty_expiration) ? date("m/d/Y",strtotime($equipment_unit->warranty_expiration)) : '-' }} </td>
			</tr>

		@endforeach

		</tbody>
	</table>	

	<div class="ajax_pagination" scrollup="false">
		{!! $equipment->render() !!}
	</div>
</div>