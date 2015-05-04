@extends('layouts.default')

@section('content')

	@if (Session::has('message'))
	    <div class="alert alert-success">{{ Session::get('message') }}</div>
	@endif

	<div class="navb">

		<span class="title">Contacts</span>
		
	</div>

	<div>
		@include('contacts/contacts', array('contacts' => $contacts))
	</div>
	
@endsection
