@extends('layouts.email')
@section('content')

	<a class="title" href="{{SITE_URL."/tickets/".$post->ticket->id}}"> {{ $title }} </a>

	<hr>

	<table class="table" id="ticket_details">
		<tr>
			<td class="bold" width="200">Ticket #</td><td>{{ $post->ticket->id }}</td>
		</tr>
		<tr>
			<td class="bold">Author</td><td>{{ $post->ticket->creator->person->name() }}</td>
		</tr>
		<tr>
			<td class="bold">Assignee</td><td>{{ $post->ticket->assignee->person->name() }}</td>
		</tr>
		<tr>
			<td class="bold">Status</td><td>{{ $post->ticket->status->name }}</td>
		</tr>
		<tr>
			<td class="bold">Priority</td><td>{{ $post->ticket->priority->name }}</td>
		</tr>
		<tr>
			<td class="bold">Contact Name</td><td>{{ isset($post->ticket->contact_id) ? $post->ticket->contact->person->name() : '-' }}</td>
		</tr>
		<tr>
			<td class="bold">Contact Phone</td><td>{!! isset($post->ticket->contact_id) && isset($post->ticket->contact->phone) ? $post->ticket->contact->phone() : '-' !!}</td>
		</tr>
		<tr>
			<td class="bold">Contact Cellphone</td><td>{!! isset($post->ticket->contact_id) && isset($post->ticket->contact->cellphone) ? $post->ticket->contact->cellphone() : '-' !!}</td>
		</tr>
		<tr>
			<td class="bold">Contact Email</td><td>{!! isset($post->ticket->contact_id) && isset($post->ticket->contact->email) ? $post->ticket->contact->email() : '-' !!}</td>
		</tr>
	</table>

	<hr>

	
	<h3>{{ $post->ticket->title }}</h3>
	

	<table class="table" id="ticket_container">
		<tr>
			<td width="50" class="thumbnail" rowspan="3"><img width="50" src="{{ SITE_URL.$post->ticket->creator->person->profile_picture()->path() }}"/></td>
		</tr>
		
		<tr>
			<td> {{ $post->ticket->creator->person->name() }} </td>
		</tr>
		
		<tr>
			<td> {{ date("m/d/Y ~ h:i A",strtotime($post->ticket->created_at)) }} </td>
		</tr>
	</table>

	<div class="post">{!! $post->ticket->post('html') !!}</div>

	<hr>

	<table class="table" id="post_container">
		<tr>
			<td width="50" class="thumbnail" rowspan="3"><img width="50" src="{{ SITE_URL.$post->author->person->profile_picture()->path() }}"/></td>
		</tr>

		<tr>
			<td>{{ $post->author->person->name() }}</td>
		</tr>

		<tr>
			<td>{{ date("m/d/Y ~ h:i A",strtotime($post->created_at)) }}</td>
		</tr>
	</table>

	<div class="post">{!! trim($post->post('html')) !!}</div>

	@if ($ticket_updated)	

		<hr>

		<p> The following changes were made: </p>

		<table class="table" id="ticket_changes">
			@foreach ($ticket->getChanges() as $key => $change)
				<tr>
					<td class="bold"> {{ ucfirst($key) }} </td>
					@if ($key == 'post')
						<td> <span class="remarked"> Content was changed </span> </td>
					@else
						<td>
							<span class="remarked"> 
								{{ $change['old_value'] }} 
							</span>
							&nbsp;&nbsp;→&nbsp;&nbsp;
							<span class="remarked"> 
								{{ $change['new_value'] }} 
							</span>
						</td>
					@endif
				</tr>
			@endforeach
		</table>

	@endif

@endsection
