@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'escalation_profiles.store')) !!}

		@include('escalation_profiles.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection