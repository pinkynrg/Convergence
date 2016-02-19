@extends('layouts.default')
@section('content')

	{!! Form::open( array('route' => 'tickets.store') ) !!}		

		<!-- <fieldset> -->
			<!-- <legend> Ticket </legend> -->
			@include('tickets.form')
			{!! Form::BSSubmit("Submit") !!}
		<!-- </fieldset> -->
			
	{!! Form::close() !!}

@endsection