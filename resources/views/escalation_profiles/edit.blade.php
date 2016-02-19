@extends('layouts.default')
@section('content')

	{!! Form::model($escalation_profile, array('method' => 'PATCH', 'route' => array('escalation_profiles.update',$escalation_profile->id))) !!}

		@include('escalation_profiles.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection