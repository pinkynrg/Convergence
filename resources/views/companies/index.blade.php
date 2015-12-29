@extends('layouts.default')

@section('content')

	@include('companies.companies', array("companies" => $companies))
	
@endsection
