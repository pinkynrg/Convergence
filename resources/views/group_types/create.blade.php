@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'group_types.store', 'class' => "form-horizontal")) !!}

		@include('group_types.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection