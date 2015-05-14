@extends('layouts.default')

@section('content')

	@include('employees.employees', array("employees" => $employees))
	
@endsection
