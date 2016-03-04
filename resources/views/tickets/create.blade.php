@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'tickets.store', 'id' => 'ticket_form')) !!}

		@include('tickets.form')
		{!! Form::BSSubmit("Submit") !!}
			
	{!! Form::close() !!}

@endsection