@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'services.store')) !!}

		@include('services.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection