@extends('layouts.default')
@section('content')

	@if(isset($ticket->id))
		<div class="alert alert-info" role="alert"> 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<div> 
				<i class="fa fa-info-circle"></i> This is a draft ticket lastly updated the {{ $ticket->date('updated_at') }} 
			</div>
		</div>
	@endif

	{!! Form::model($ticket,array('route' => 'tickets.store', 'id' => 'ticket_form')) !!}

		@include('tickets.form')
		{!! Form::BSSubmit("Submit") !!}
			
	{!! Form::close() !!}

@endsection