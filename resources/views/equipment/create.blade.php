@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'equipment.store')) !!}

		@include('equipment.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection