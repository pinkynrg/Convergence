@extends('layouts.default')
@section('content')
	
	{!! Form::model($group, array('method' => 'PATCH', 'route' => array('groups.update',$group->id))) !!}

		@include('groups.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection