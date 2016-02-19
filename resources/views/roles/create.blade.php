@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'roles.store')) !!}

		@include('roles.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection