@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'company_person.store')) !!}

		@include('people.create')
		@include('company_person.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection