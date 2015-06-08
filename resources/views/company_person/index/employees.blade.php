@extends('layouts.default')

@section('content')

	@include('company_person.employees', array("employees" => $employees))
	
@endsection
