@extends('layouts.default')

@section('content')

	<table class="table table-striped table-hover">
		<tr>
			<th> Company </th> <td> {{ $service->company->name }} </td>
		</tr>
		<tr>
			<th> Internal Contact </th> <td> {{ isset($service->internal_contact_id) ? $service->internal_contact->person->name() : '' }} </td>
		</tr>
		<tr>
			<th> External Contact </th> <td> {{ isset($service->external_contact_id) ? $service->external_contact->person->name() : '' }} </td>
		</tr>
		<tr>
			<th> Internal Job # </th> <td> {{ isset($service->job_number_internal) ? $service->job_number_internal : '' }} </td>
		</tr>
		<tr>
			<th> Remote Job # </th> <td> {{ isset($service->job_number_remote) ? $service->job_number_remote : '' }} </td>
		</tr>
		<tr>
			<th> Onsite Job # </th> <td> {{ isset($service->job_number_onsite) ? $service->job_number_onsite : '' }} </td>
		</tr>
		<tr>
			<th> Hotel </th> <td> {{ isset($service->hotel_id) ?  $service->hotel->name : '' }} </td>
		</tr>
	</table>

	<h3> Technicians </h3>
	<hr>

	<table class="table table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Division</th>
					<th>Internal Start</th>
					<th>Internal End</th>
					<th>Internal Hours</th>
					<th>Remote Start</th>
					<th>Remote End</th>
					<th>Remote Hours</th>
					<th>Onsite Start</th>
					<th>Onsite End</th>
					<th>Onsite Hours</th>
				</tr>
			</thead>
			<tbody>

				@foreach ($service->service_technicians as $service_technician)

				<tr>
					<td> {{ $service_technician->technician->person->name() }} </td>
					<td> {{ $service_technician->division->name }} </td>

					<td> {{ isset($service_technician->internal_start) ? date("m/d/Y",strtotime($service_technician->internal_start)) : '' }} </td>
					<td> {{ isset($service_technician->internal_end) ? date("m/d/Y",strtotime($service_technician->internal_end)) : '' }} </td>
					
					<td> {{ $service_technician->internal_estimated_hours }} </td>
					
					<td> {{ isset($service_technician->remote_start) ? date("m/d/Y",strtotime($service_technician->remote_start)) : '' }} </td>
					<td> {{ isset($service_technician->remote_end) ? date("m/d/Y",strtotime($service_technician->remote_end)) : '' }} </td>
					
					<td> {{ $service_technician->remote_estimated_hours }} </td>
					
					<td> {{ isset($service_technician->onsite_start) ? date("m/d/Y",strtotime($service_technician->onsite_start)) : '' }} </td>
					<td> {{ isset($service_technician->onsite_end) ? date("m/d/Y",strtotime($service_technician->onsite_end)) : '' }} </td>
					
					<td> {{ $service_technician->onsite_estimated_hours }} </td>
				</tr>

				@endforeach

			</tbody>
		</table>

@endsection