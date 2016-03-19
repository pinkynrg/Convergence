@extends('layouts.default')
@section('content')

	{!! Form::model($ticket,array('route' => 'tickets-requests.store', 'id' => 'ticket_form')) !!}

		@if(isset($ticket->id))
			<div class="alert alert-info" role="alert"> 
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div> 
					<i class="fa fa-info-circle"></i> This is a draft ticket lastly updated the {{ $ticket->date('updated_at') }} ~ OR ~ <a class="clear_form">Clear Form</a>
				</div>
			</div>
		@endif
		
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("title","Title") !!}
			{!! Form::BSText("title") !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::hidden("post",null,["id" => "post"]) !!}

		<div class="row">
			<div class="col-xs-12">

				@foreach ($questions as $key => $question)

					{!! Form::BSGroup() !!}
						{!! Form::BSLabel($key,$question) !!}
						{!! Form::BSTextArea($key) !!}
					{!! Form::BSEndGroup() !!}

				@endforeach
		
				{!! Form::dropZone() !!}		

			</div>
		</div>


		{!! Form::BSSubmit("Submit") !!}
			
	{!! Form::close() !!}

@endsection