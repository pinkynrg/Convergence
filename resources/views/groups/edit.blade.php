@extends('layouts.default')
@section('content')
	
	{!! Form::model($group, array('method' => 'PATCH', 'route' => array('groups.update',$group->id), 'class' => "form-horizontal")) !!}

		@include('groups.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection