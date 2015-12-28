@extends('layouts.default')

@section('content')

	@include('includes.errors')

	@include('companies.companies', array("companies" => $companies))
	
@endsection
