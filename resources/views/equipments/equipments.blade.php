<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $equipments->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="cc_number">CC</th>
				<th column="equipments.name" class="hidden-xs">Name</th>
				<th column="companies.company_name" class="hidden-xs">Customer</th>
				<th column="serial_number" class="hidden-xs">Serial Number</th>
				<th column="equipment_types.name" class="hidden-xs">Equipment Type</th>
				<th column="warranty_expiration" class="hidden-xs">Warranty Expiration</th>
			</tr>
		</thead>
		<tbody>

			@if ($equipments->count())
				@foreach ($equipments as $equipment) 	

				<tr>
					<td> <a href="{{ route('equipments.show', $equipment->id) }}"> {{ '#'.$equipment->cc_number }} </a> </td>
					<td class="hidden-xs"> <a href="{{ route('equipments.show', $equipment->id) }}"> {{ $equipment->name }} </a> </td>
					<td class="hidden-xs"> <a href="{{ route('companies.show', $equipment->company->id) }}"> {{ $equipment->company->name }} </a> </td>
					<td class="hidden-xs"> <a href="{{ route('equipments.show', $equipment->id) }}"> {{ $equipment->serial_number }} </a> </td>
					<td class="hidden-xs"> {{ $equipment->equipment_type->name }} </td>
					<td class="hidden-xs"> {{ $equipment->warranty_expiration }} </td>
				</tr>
				@endforeach
			@else 
				<tr><td colspan="7">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $equipments->render() !!}
	</div>
</div>
