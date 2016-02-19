@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'group_types.store')) !!}

		@include('group_types.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection