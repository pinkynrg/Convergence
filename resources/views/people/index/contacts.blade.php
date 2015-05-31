@extends('layouts.default')

@section('content')

	@include('people.contacts', array("contacts" => $contacts))
	
@endsection
