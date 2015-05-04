@extends('layouts.default')

@section('content')

	<div>
		@include('customers.customers', array("customers" => $customers))
	</div>
	
@endsection
