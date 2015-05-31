@extends('layouts.default')
@section('content')
	
	{!! Form::model($company, array('method' => 'PATCH', 'route' => array('companies.update',$company->id), 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('companies.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection