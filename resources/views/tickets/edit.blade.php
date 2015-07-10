@extends('layouts.default')
@section('content')

	@include('includes.errors')

	{!! Form::model($ticket, array('method' => 'PATCH', 'route' => array('tickets.update',$ticket->id) )) !!}
	
		@include('tickets.form')

		<div class="row">
			{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-12']) !!}
		</div>
		
	{!! Form::close() !!}

@endsection