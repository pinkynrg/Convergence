@extends('layouts.default')

@section('content')

	@include('people.employees', array("employees" => $employees))
	
@endsection
