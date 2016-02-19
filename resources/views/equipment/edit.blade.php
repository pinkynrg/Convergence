@extends('layouts.default')
@section('content')
	
	{!! Form::model($equipment, array('method' => 'PATCH', 'route' => array('equipment.update',$equipment->id))) !!}

		@include('equipment.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection