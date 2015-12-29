@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'permissions.store', 'class' => "form-horizontal")) !!}

		@include('permissions.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection