@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'equipments.store', 'class' => "form-horizontal")) !!}

		@include('equipments.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection