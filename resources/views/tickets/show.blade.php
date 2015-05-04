@extends('layouts.default')
@section('content')

	<table class="table table-striped table-condensed table-hover">
		<tr>
			<th>Title</th>
			<td colspan="3"> {{ $ticket->title }} </td>
		</tr>
		<tr>
			<th>Creator</th>
			<td> {!! $ticket->creator->name() != '' ? HTML::link(route('employees.show', $ticket->creator->id), $ticket->creator->name()) : '-' !!} </td>
			<th>Contact Name</th>
			<td> {!! isset($ticket->contact->name) ? HTML::link(route('contacts.show', $ticket->contact->id), $ticket->contact->name) : '-' !!} </td>
		</tr>
		<tr>
			<th>Customer</th>
			<td>{!! isset($ticket->customer->company_name) ? HTML::link(route('customers.show', $ticket->customer->id), $ticket->customer->company_name) : '-' !!}</td>
			<th>Phone</th>
			<td>{{ isset($ticket->contact->phone) ? $ticket->contact->phone : '-' }}</td>
		</tr>
		<tr>
			<th>Suport Type</th>
			<td>{{ isset($ticket->country) ? $ticket->country : '-' }}</td>
			<th>Cell</th>
			<td>{{ isset($ticket->contact->cellphone) ? $ticket->contact->cellphone : '-' }}</td>
		</tr>
		<tr>
			<th>Connection Option</th>
			<td>{{ isset($ticket->city) ? $ticket->city : '-' }}</td>
			<th>Email</th>
			<td>{{ isset($ticket->contact->email) ? $ticket->contact->email : '-' }}</td>
		</tr>
		<tr>
			<th>CC</th>
			<td>{{ isset($ticket->cc) ? $ticket->cc : '-' }}</td>
			<th>Group Email</th>
			<td>{{ isset($ticket->customer->group_email) ? $ticket->customer->group_email : '-' }}</td>
		</tr>
		<tr>
			<th>Division</th>
			<td>{{ isset($ticket->division->name) ? $ticket->division->name : '-' }}</td>
			<th>Account Manager</th>
			<td>{!! isset($ticket->customer->account_manager_id) ? HTML::link(route('employees.show', $ticket->customer->account_manager_id), $ticket->customer->account_manager->name()) : '-' !!}</td>
		</tr>
	</table>

	<div id="ticket_content">{{ $ticket->post }}</div>

	@foreach ($ticket->posts as $post) 

		<div class="media ticket_post">
		  <div class="media-left media-middle">
		    <a href="#">
		      <img class="thumbnail" src="/images/thumbnail.png" alt="/images/thumbnail.png">
		    </a>
		  </div>
		  <div class="media-body">
		    <h4 class="media-heading"> <a href="{{ route('employees.show', $post->author->id) }}"> {{ $post->author->name() }} </a> </h4>
		    <div> {{ date("d F Y",strtotime($post->created_at)) }} @ {{ date("g:ha",strtotime($post->created_at)) }} </div>
		    <div class="post_content"> {{ $post->post }} </div>
		  </div>
		</div>

	@endforeach

@endsection