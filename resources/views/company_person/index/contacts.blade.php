@extends('layouts.default')

@section('content')

	@include('company_person.index', array("contacts" => $contacts))
	
@endsection
