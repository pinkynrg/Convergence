@extends('layouts.default')

@section('content')

	<div>
		@include('employees.employees', array("employees" => $employees))
	</div>
	
@endsection
