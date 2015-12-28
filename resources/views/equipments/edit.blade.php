@extends('layouts.default')
@section('content')
	
	{!! Form::model($equipment, array('method' => 'PATCH', 'route' => array('equipments.update',$equipment->id), 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('equipments.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection