@extends('layouts.default')
@section('content')
	
	{!! Form::model($employee, array('method' => 'PATCH', 'route' => array('people.update',$employee->id), 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('people.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection