@extends('layouts.default')
@section('content')
	
	<div>
		
	@include('companies.company', array('company' => $company))

	</div>

	<hr>

	<div ajax-route="{{ route('companies.contacts',$company->id) }}">
		<div id="contacts" class="navb"> 
			<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Contacts </div>
		</div>
		<div>
		
			@if ($company->contacts->count())
				@include('company_person.contacts', array('contacts' => $company->contacts))
			@else
				@include('includes.no-contents')
			@endif
			
		</div>
	</div>

	<hr>

	<div ajax-route="{{ route('companies.tickets',$company->id) }}">
		<div id="tickets" class="navb"> 
			<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Tickets </div>
		</div>
		<div>

			@if ($company->tickets->count())
				@include('tickets.tickets', array('tickets' => $company->tickets))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>

	<hr>

	<div ajax-route="{{ route('companies.equipment',$company->id) }}">
		<div id="equipment" class="navb"> 
			<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Equipment </div>
		</div>
		<div>
			
			@if ($company->equipment->count())
				@include('equipment.equipment', array('equipment' => $company->equipment))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>
	
	<hr>

	<div ajax-route="{{ route('hotels.index') }}?where[]=companies.id|=|{{$company->id}}&paginate=10">
		<div id="hotels" class="navb"> 
			<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Hotels </div>
		</div>
		<div>
			
			@if ($company->hotels->count())
				@include('hotels.hotels', array('hotels' => $company->hotels))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>
	
	<hr>

	<div ajax-route="{{ route('services.index') }}?where[]=companies.id|=|{{$company->id}}&paginate=10">
		<div id="services" class="navb"> 
			<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Services </div>
		</div>
		<div>
			
			@if ($company->services->count())
				@include('services.services', array('services' => $company->services))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>

@endsection