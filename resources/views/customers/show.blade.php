@extends('layouts.default')
@section('content')
	
	<div>
		
	@include('customers.customer', array('customer' => $customer))

	</div>

	<div id="contacts" class="navb expander"> 
		<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Contacts </div>
	</div>
	<div class="to_expand">
	
		@if ($customer->contacts->isEmpty())
			@include('includes.no-contents')
		@else
			@include('customers.contacts', array('contacts' => $customer->contacts))
		@endif
		
	</div>

	<div id="tickets" class="navb expander"> 
		<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Tickets </div>
	</div>
	<div class="to_expand">

		@if ($customer->tickets->isEmpty())
			@include('includes.no-contents')
		@else
			@include('customers.tickets', array('tickets' => $customer->tickets))
		@endif

	</div>

	<div id="equipments" class="navb expander"> 
		<div class="title"><i class="fa fa-plus-square-o fa-2"></i> Equipments </div>
	</div>
	<div class="to_expand">
		
		@if ($customer->equipments->isEmpty())
			@include('includes.no-contents')
		@else
			@include('customers.equipments', array('equipments' => $customer->equipments))
		@endif

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