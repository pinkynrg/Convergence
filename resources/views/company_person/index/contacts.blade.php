@extends('layouts.default')

@section('content')

	@include('company_person.contacts', array("contacts" => $contacts))
	
@endsection
