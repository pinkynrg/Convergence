@extends('layouts.default')
@section('content')
	
	{!! Form::model($permission, array('method' => 'PATCH', 'route' => array('permissions.update',$permission->id), 'class' => "form-horizontal")) !!}

		@include('permissions.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection