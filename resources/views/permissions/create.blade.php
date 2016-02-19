@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'permissions.store')) !!}

		@include('permissions.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection