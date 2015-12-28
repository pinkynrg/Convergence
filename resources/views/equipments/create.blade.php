@extends('layouts.default')
@section('content')

	@include('includes.errors')

	{!! Form::open(array('route' => 'equipments.store', 'class' => "form-horizontal")) !!}

		{!! Form::hidden("company_id", $company->id, array("id" => "company_id")) !!}

		@include('equipments.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection