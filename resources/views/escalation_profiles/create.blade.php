@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'escalation_profiles.store', 'class' => "form-horizontal")) !!}

		@include('escalation_profiles.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection