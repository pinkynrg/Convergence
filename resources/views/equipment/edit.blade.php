@extends('layouts.default')
@section('content')
	
	{!! Form::model($equipment, array('method' => 'PATCH', 'route' => array('equipment.update',$equipment->id), 'class' => "form-horizontal")) !!}

		@include('equipment.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection