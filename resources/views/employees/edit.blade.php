@extends('layouts.default')
@section('content')
	
	{!! Form::model($employee, array('method' => 'PATCH', 'route' => array('employees.update',$employee->id), 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('employees.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection