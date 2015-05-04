@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'employees.store', 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('employees.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection