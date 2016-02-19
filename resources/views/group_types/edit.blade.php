@extends('layouts.default')
@section('content')
	
	{!! Form::model($group_type, array('method' => 'PATCH', 'route' => array('group_types.update',$group_type->id))) !!}

		@include('group_types.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection