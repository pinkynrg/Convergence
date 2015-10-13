@extends('layouts.default')
@section('content')

	@include('includes.errors')

	{!! Form::open( array('route' => 'tickets.store') ) !!}		

		@include('tickets.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-12']) !!}
		
	{!! Form::close() !!}

@endsection