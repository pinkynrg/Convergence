@extends('layouts.default')
@section('content')

	@include('includes.errors')

	{!! Form::model($company, array('route' => 'company_person.store', 'class' => "form-horizontal")) !!}

		@include('people.create')
		@include('company_person.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection