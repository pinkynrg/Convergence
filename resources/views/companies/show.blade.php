@extends('layouts.default')
@section('content')
	
	<div>
		
	@include('companies.company', array('company' => $company))

	</div>

	<div ajax-route="{{ route('ajax.companies.contacts',$company->id) }}">
		<div id="contacts" class="navb expander"> 
			<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Contacts </div>
		</div>
		<div class="to_expand">
		
			@if ($company->contacts->count())
				@include('companies.contacts', array('contacts' => $company->contacts))
			@else
				@include('includes.no-contents')
			@endif
			
		</div>
	</div>

	<div ajax-route="{{ route('ajax.companies.tickets',$company->id) }}">
		<div id="tickets" class="navb expander"> 
			<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Tickets </div>
		</div>
		<div class="to_expand">

			@if ($company->tickets->count())
				@include('companies.tickets', array('tickets' => $company->tickets))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>

	<div ajax-route="{{ route('ajax.companies.equipments',$company->id) }}">
		<div id="equipments" class="navb expander"> 
			<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Equipments </div>
		</div>
		<div class="to_expand">
			
			@if ($company->equipments->count())
				@include('companies.equipments', array('equipments' => $company->equipments))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>
	
	<div id="hotels" class="navb expander"> 
		<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Hotels </div>
	</div>
	<div class="to_expand">
		@include('includes.no-contents')
	</div>

	<div id="services" class="navb expander"> 
		<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Services </div>
	</div>
	<div class="to_expand">
		@include('includes.no-contents')
	</div>

@endsection