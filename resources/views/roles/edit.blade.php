@extends('layouts.default')
@section('content')
	
	{!! Form::model($role, array('method' => 'PATCH', 'route' => array('roles.update',$role->id))) !!}

		@include('roles.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection