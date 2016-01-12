<link rel="stylesheet" href="http://local.convergence.it/css/bootstrap.min.css">
<style type="text/css">
	img#logo { height: 70px; }
	* { font-size: 11px; }
	.borderless td, .borderless th { border: none!important; }
	.wrapper { width: 800px; padding: 10px; }
</style>

<div class="wrapper">

	<img id="logo" src="http://local.convergence.it/images/style/logo-elettric80.png">

	<hr>

	<div class="row">
		<div class="col-xs-6">

			<h4> Assignment Information </h4>

			<table  class="table borderless table-condensed stripped">
				<tr> 
					<th>Internal Job #:</th><td> {{ $service->job_number_internal }} </td>
				</tr>
				<tr>
					<th>Remote Job #:</th><td> {{ $service->job_number_remote }} </td>
				</tr>
				<tr>
					<th>Onsite Job #:</th><td> {{ $service->job_number_onsite }} </td>
				</tr>
			</table>
		</div>

		<div class="col-xs-6">

			<h4> Internal Contact </h4>

			<table class="table borderless table-condensed">
				<tr>
					<th>Name:</th><td> {{ $service->internal_contact->person->name() }} </td>
				</tr>
				<tr>
					<th>Cellphone:</th><td> {!! $service->internal_contact->cellphone() !!} </td>
				</tr>
				<tr>
					<th>Phone:</th><td> {!! $service->internal_contact->phone() !!} </td>
				</tr>
				<tr>
					<th>E-mail:</th><td> {!! $service->internal_contact->email() !!} </td>
				</tr>
			</table>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-xs-6">
			<h4> Customer Information </h4>
			<table class="table borderless table-condensed">
				<tr>
					<th>Customer:</th> <td> {{ $service->company->name }} </td>
				</tr>
				<tr>
					<th>Address:</th> <td> {{ $service->company->address }} </td>
				</tr>
				<tr>
					<th>City:</th> <td> {{ $service->company->city }} </td>
				</tr>
				<tr>
					<th>State:</th> <td> {{ $service->company->state }} </td>
				</tr>
				<tr>
					<th>Zip Code:</th> <td> {{ $service->company->zip_code }} </td>
				</tr>
				<tr>
					<th>Country:</th> <td> {{ $service->company->country }} </td>
				</tr>
			</table>
		</div>
		<div class="col-xs-6">
			<h4> Customer Contact </h4>
			<table class="table borderless table-condensed">
				<tr>
					<th>Name:</th><td> {{ $service->external_contact->person->name() }} </td>
				</tr>
				<tr>
					<th>Title:</th><td> {!! $service->external_contact->title->name !!} </td>
				</tr>
				<tr>
					<th>Department:</th><td> {!! $service->external_contact->department->name !!} </td>
				</tr>
				<tr>
					<th>Cell:</th><td> {!! $service->external_contact->cellphone() !!} </td>
				</tr>
				<tr>
					<th>Phone:</th><td> {!! $service->external_contact->phone() !!} </td>
				</tr>
				<tr>
					<th>E-mail:</th><td> {!! $service->external_contact->email !!} </td>
				</tr>
			</table>
		</div>
	</div>

	<?php $counter = 1 ?>

	@foreach ($service->service_technicians as $service_technician)

	<hr>

	<div class="row">
		<div class="col-xs-12">

			<h4> Technician Information # {{ $counter }}</h4>

			<table class="table borderless table-condensed">
				<tr>
					<th>Employee:</th><td> {{ $service_technician->technician->person->name() }} </td>
				</tr>
				<tr>
					<th>Division:</th><td> {{ $service_technician->division->name }} </td>
				</tr>
				<tr>
					<th>Work Description:</th><td> {{ $service_technician->work_description }} </td>
				</tr>
			</table>

			<table class="table borderless table-condensed">
				<tr>
					<th>Internal Job Estimation:</th><td> {{ $service_technician->internal_estimated_hours.' hours' }} </td>
					<th>Internal Job Start:</th><td> {{ $service_technician->internal_start }} </td>
					<th>Internal Job End:</th><td> {{ $service_technician->internal_end }} </td>
				</tr>
				<tr>
					<th>Remote Job Estimation:</th><td> {{ $service_technician->remote_estimated_hours.' hours' }} </td>
					<th>Remote Job Start:</th><td> {{ $service_technician->remote_start }} </td>
					<th>Remote Job End:</th><td> {{ $service_technician->remote_end }} </td>
				</tr>
				<tr>
					<th>Onsite Job Estimation:</th><td> {{ $service_technician->onsite_estimated_hours.' hours' }} </td>
					<th>Onsite Job Start:</th><td> {{ $service_technician->onsite_start }} </td>
					<th>Onsite Job End:</th><td> {{ $service_technician->onsite_end }} </td>
				</tr>
			</table>

		</div>
	</div>

	<?php $counter++ ?>

	@endforeach

	<hr>

	<div class="row">
		<div class="col-xs-6">
			<h4> Hotel Details </h4>
			
			<table class="table borderless table-condensed">
				<tr>
					<th>Name:</th> <td> {{ $service->hotel->name }} </td>
				</tr>
				<tr>
					<th>Address:</th> <td> {{ $service->hotel->address }} </td>
				</tr>
				<tr>
					<th>Rating:</th> <td> {{ $service->hotel->rating() }} </td>
				</tr>
				<tr>
					<th>Driving Distance:</th> <td> {{ $service->hotel->driving_time() }} </td>
				</tr>	
			</table>

		</div>
	</div>	

</div>