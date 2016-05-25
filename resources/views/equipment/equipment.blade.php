<div class="content">

	@if (Route::currentRouteName() == "equipment.index")
		<div class="ajax_pagination" scrollup="false">
			{!! $equipment->render() !!}
		</div>
	@endif

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="cc_number" weight="0" type="desc">CC</th>
				<th column="equipment.name" class="hidden-xs hidden-ms">Name</th>
				<th column="serial_number">Serial Number</th>
				<th column="equipment_types.name" class="hidden-xs hidden-ms">Equipment Type</th>
				<th column="companies.name">Customer</th>
				<th column="warranty_expiration" class="hidden-xs hidden-ms">Warranty Expiration</th>
				<th column="equipment.created_at" class="hidden-xs hidden-ms">Created</th>
				<th column="equipment.updated_at" class="hidden-xs hidden-ms">Updated</th>
			</tr>
		</thead>
		<tbody>

			@if ($equipment->count())
				@foreach ($equipment as $equipment_unit) 	

				<tr>
					<td> <a href="{{ route('equipment.show', $equipment_unit->id) }}"> {{ '#'.$equipment_unit->cc_number }} </a> </td>
					<td class="hidden-xs hidden-ms"> <a href="{{ route('equipment.show', $equipment_unit->id) }}"> {{ $equipment_unit->name }} </a> </td>
					<td> <a href="{{ route('equipment.show', $equipment_unit->id) }}"> {{ $equipment_unit->serial_number }} </a> </td>
					<td class="hidden-xs hidden-ms"> {{ $equipment_unit->equipment_type->name }} </td>
					<td> <a href="{{ route('companies.show', $equipment_unit->company->id) }}"> {{ $equipment_unit->company->name }} </a> </td>
					<td class="hidden-xs hidden-ms"> {{ isset($equipment_unit->warranty_expiration) ? date("m/d/Y",strtotime($equipment_unit->warranty_expiration)) : '-' }} </td>
					<td class="hidden-xs hidden-ms"> {{ $equipment_unit->date("created_at") }} </td>
					<td class="hidden-xs hidden-ms"> {{ $equipment_unit->date("updated_at") }} </td>
				</tr>
				@endforeach
			@else 
				<tr><td colspan="100%">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	@if (Route::currentRouteName() == "equipment.index")
		<div class="ajax_pagination" scrollup="true">
			{!! $equipment->render() !!}
		</div>
	@endif 

	@if (Route::currentRouteName() == "companies.equipment" || Route::currentRouteName() == "companies.show")
		<div class="ajax_pagination" scrollup="false">
			{!! $equipment->render() !!}
		</div>
	@endif

</div>
