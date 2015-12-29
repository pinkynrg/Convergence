@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'roles.store', 'class' => "form-horizontal")) !!}

		@include('roles.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection