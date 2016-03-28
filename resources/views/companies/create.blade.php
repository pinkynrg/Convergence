@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'companies.store')) !!}

		@include('companies.form')

		<hr>

		<h3>Contact Information</h3>

		@include('people.create')
		@include('company_person.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection