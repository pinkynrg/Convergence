@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'groups.store', 'class' => "form-horizontal")) !!}

		@include('groups.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection