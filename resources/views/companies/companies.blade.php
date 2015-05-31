<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $companies->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="name">Company</th>
				<th column="people.cellphone">Main Contact</th>
				<th column="country" class="hidden-xs hidden-sm">Country</th>
				<th column="city" class="hidden-xs">City</th>
				<th column="address" class="hidden-xs">Address</th>
				<th column="zip_code" class="hidden-xs">Zip Code</th>
			</tr>
		</thead>
		<tbody>

			@foreach ($companies as $company) 	

			<tr>
				<td> <a href="{{route('companies.show', $company->id) }}"> {{  $company->name }} </a> </td>
				<td> {{ isset($company->main_contact[0]) ? $company->main_contact[0]->cellphone() : '' }} </td>
				<td class="hidden-xs hidden-sm"> {{ $company->country }} </td>
				<td class="hidden-xs"> {{ $company->city }} </td>
				<td class="hidden-xs"> {{ $company->address }} </td>
				<td class="hidden-xs"> {{ $company->zip_code }} </td>
			</tr>

			@endforeach

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $companies->render() !!}
	</div>

</div>