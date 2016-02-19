@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'groups.store')) !!}

		@include('groups.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection