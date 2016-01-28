@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'company_person.store', 'class' => "form-horizontal")) !!}

		@include('people.create')
		@include('company_person.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection