@extends('layouts.default')
@section('content')

	{!! Form::open( array('route' => 'tickets.store') ) !!}		

		@include('tickets.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-12']) !!}
		
	{!! Form::close() !!}

@endsection