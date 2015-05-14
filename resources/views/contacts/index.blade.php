@extends('layouts.default')

@section('content')

	@if (Session::has('message'))
	    <div class="alert alert-success">{{ Session::get('message') }}</div>
	@endif

	@include('contacts/contacts', array('contacts' => $contacts))
	
@endsection
