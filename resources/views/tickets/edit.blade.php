@extends('layouts.default')
@section('content')

	{!! Form::model($ticket, array('method' => 'PATCH', 'route' => array('tickets.update',$ticket->id) )) !!}
	
		@include('tickets.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-12']) !!}
		
	{!! Form::close() !!}

@endsection