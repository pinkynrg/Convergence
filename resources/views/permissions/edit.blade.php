@extends('layouts.default')
@section('content')
	
	{!! Form::model($permission, array('method' => 'PATCH', 'route' => array('permissions.update',$permission->id))) !!}

		@include('permissions.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection