@extends('layouts.email')
@section('content')

	<a class="title" href="{{SITE_URL."/tickets/".$ticket->id}}"> {{ $title }} </a>

	<hr>

	@include('emails.includes.ticket_details',$ticket)
	
	<hr>

	@include('emails.includes.ticket_changes',['ticket' => $ticket])

@endsection
	