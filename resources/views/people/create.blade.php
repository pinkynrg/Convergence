@extends('layouts.default')
@section('content')

	@include('includes.errors')

	{!! Form::model($company, array('route' => 'people.store', 'class' => "form-horizontal")) !!}

		@include('people.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection