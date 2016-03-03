@extends('layouts.default')
@section('content')

	{!! Form::model($contact, array('route' => 'company_person.store')) !!}

		@include('people.create')
		@include('company_person.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection