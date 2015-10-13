@extends('layouts.default')
@section('content')
	
	{!! Form::model($company, array('method' => 'PATCH', 'route' => array('companies.update',$company->id), 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('companies.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection