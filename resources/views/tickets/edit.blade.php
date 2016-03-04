@extends('layouts.default')
@section('content')

	{!! Form::model($ticket, array('method' => 'PATCH', 'route' => array('tickets.update',$ticket->id), 'id' => 'ticket_form')) !!}
	
		@include('tickets.form')

		{!! Form::BSSubmit("Submit") !!}
		
	{!! Form::close() !!}

@endsection