<div class="content">
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="services.id">Service #</th>
				<th column="internal_contacts.name">Internal Contact</th>
				<th column="external_contacts.name">External Contact</th>
				<th column="job_number_internal">Internal Job #</th>
				<th column="job_number_onsite">Onsite Job #</th>
				<th column="job_number_remote">Remote Job #</th>
				<th column="hotels.name">Hotel</th>
			</tr>
		</thead>
		<tbody>
		
		@foreach ($services as $service)

			<tr>
				<td><a href="{{ route('services.show', $service->id) }}"> {{ '#'.$service->id }} </a></td>
				<td> {{ isset($service->internal_contact_id) ? $service->internal_contact->person->name() : '' }} </td>
				<td> {{ isset($service->external_contact_id) ? $service->external_contact->person->name() : '' }} </td>
				<td> {{ $service->job_number_internal }} </td>
				<td> {{ $service->job_number_onsite }} </td>
				<td> {{ $service->job_number_remote }} </td>
				<td> {{ isset($service->hotel_id) ? $service->hotel->name : '' }} </td>
			</tr>

		@endforeach

		</tbody>
	</table>	

	<div class="ajax_pagination" scrollup="false">
		{!! $services->render() !!}
	</div>
</div>