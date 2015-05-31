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

	<div class="ajax_pagination" scrollup="false">
		{!! $equipments->render() !!}
	</div>
</div>