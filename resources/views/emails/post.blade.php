@extends('layouts.email')
@section('content')

	<a class="title" href="{{SITE_URL."/tickets/".$post->ticket->id}}"> {{ $title }} </a>

	<hr>

	@include('emails.includes.ticket_details',['ticket' => $post->ticket])

	<hr>

	<h3>New Post</h3>

	@include('emails.includes.post',['post' => $post])

	<hr>
	
	<h3>Related Ticket</h3>

	@include('emails.includes.ticket',['ticket' => $post->ticket])

	@if ($ticket_updated)	

		<hr>

		@include('emails.includes.ticket_changes',['ticket' => $post->ticket])

	@endif

@endsection
