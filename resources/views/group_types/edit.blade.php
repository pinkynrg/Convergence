@extends('layouts.default')
@section('content')
	
	{!! Form::model($group_type, array('method' => 'PATCH', 'route' => array('group_types.update',$group_type->id), 'class' => "form-horizontal")) !!}

		@include('group_types.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection