@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'services.store', 'class' => "form-horizontal")) !!}

		@include('services.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection