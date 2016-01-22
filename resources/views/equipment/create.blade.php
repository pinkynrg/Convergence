@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'equipment.store', 'class' => "form-horizontal")) !!}

		@include('equipment.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection