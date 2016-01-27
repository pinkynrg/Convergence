@extends('layouts.default')
@section('content')

	{!! Form::model($escalation_profile, array('method' => 'PATCH', 'route' => array('escalation_profiles.update',$escalation_profile->id), 'class' => "form-horizontal")) !!}

		@include('escalation_profiles.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection