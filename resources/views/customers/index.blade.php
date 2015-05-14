@extends('layouts.default')

@section('content')

	@include('customers.customers', array("customers" => $customers))
	
@endsection
