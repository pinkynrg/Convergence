@extends('layouts.email')
@section('content')

	<a class="title" href="{{SITE_URL."/tickets/".$ticket->id}}"> {{ $title }} </a>

	<hr>

	@if ($ticket->email_text) <p> {{ $ticket->email_text }} </p> @endif

	@include('emails.includes.ticket_details',['ticket' => $ticket])

	<hr>

	@include('emails.includes.ticket',['ticket' => $ticket])
	
@endsection