<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $equipment->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="cc_number">CC</th>
				<th column="equipment.name" class="hidden-xs">Name</th>
				<th column="companies.company_name" class="hidden-xs">Customer</th>
				<th column="serial_number" class="hidden-xs">Serial Number</th>
				<th column="equipment_types.name" class="hidden-xs">Equipment Type</th>
				<th column="warranty_expiration" class="hidden-xs">Warranty Expiration</th>
			</tr>
		</thead>
		<tbody>

			@if ($equipment->count())
				@foreach ($equipment as $equipment_unit) 	

				<tr>
					<td> <a href="{{ route('equipment.show', $equipment_unit->id) }}"> {{ '#'.$equipment_unit->cc_number }} </a> </td>
					<td class="hidden-xs"> <a href="{{ route('equipment.show', $equipment_unit->id) }}"> {{ $equipment_unit->name }} </a> </td>
					<td class="hidden-xs"> <a href="{{ route('companies.show', $equipment_unit->company->id) }}"> {{ $equipment_unit->company->name }} </a> </td>
					<td class="hidden-xs"> <a href="{{ route('equipment.show', $equipment_unit->id) }}"> {{ $equipment_unit->serial_number }} </a> </td>
					<td class="hidden-xs"> {{ $equipment_unit->equipment_type->name }} </td>
					<td class="hidden-xs"> {{ isset($equipment_unit->warranty_expiration) ? date("m/d/Y",strtotime($equipment_unit->warranty_expiration)) : '-' }} </td>
				</tr>
				@endforeach
			@else 
				<tr><td colspan="7">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $equipment->render() !!}
	</div>
</div>
