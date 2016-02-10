<div class="content">

	@if (Route::currentRouteName() == "services.index")
		<div class="ajax_pagination" scrollup="false">
			{!! $services->render() !!}
		</div>
	@endif

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="services.id" type="desc">Service #</th>
				
				@if (Route::currentRouteName() == "services.index")
					<th column="companies.name">Company</th>
				@endif

				<th column="internals.last_name">Internal Contact</th>
				<th column="externals.last_name">External Contact</th>
				<th column="services.created_at">Created</th>
				<th column="services.updated_at">Updated</th>
			</tr>
		</thead>
		<tbody>
		@if ($services->count())
			@foreach ($services as $service)
				<tr>
					<td><a href="{{ route('services.show', $service->id) }}"> {{ '#'.$service->id }} </a></td>
					
					@if (Route::currentRouteName() == "services.index")
						<td><a href="{{ route('companies.show', $service->company->id) }}"> {{ $service->company->name }} </a> </td>
					@endif

					<td> {{ isset($service->internal_contact_id) ? $service->internal_contact->person->name() : '' }} </td>
					<td> {{ isset($service->external_contact_id) ? $service->external_contact->person->name() : '' }} </td>
					<td> {{ $service->date("created_at") }} </td>
					<td> {{ $service->date("updated_at") }} </td>
				</tr>
			@endforeach
		@else 
			<tr><td colspan="6">@include('includes.no-contents')</td></tr>
		@endif 

		</tbody>
	</table>	

	@if (Route::currentRouteName() == "services.index")
		<div class="ajax_pagination" scrollup="true">
			{!! $services->render() !!}
		</div>
	@endif 

	@if (Route::currentRouteName() == "companies.services" || Route::currentRouteName() == "companies.show")
		<div class="ajax_pagination" scrollup="false">
			{!! $services->render() !!}
		</div>
	@endif

</div>