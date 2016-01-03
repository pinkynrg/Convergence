@extends('layouts.default')
@section('content')

	<div id="ticket_container">

		<div id="ticket_content" class="media">
			<div class="media-left media-middle">
				<img class="thumbnail thumb-md" src="{{ $ticket->creator->person->image() }}" alt=" {{ $ticket->creator->person->image() }} ">
			</div>
			<div class="media-body">
				<div id="ticket_title" class="media-heading"> {{ $ticket->title }}  <span id="ticket_status" class="{{ $status_class }}"> <i class='fa fa-circle'></i> {{ $ticket->status->name }} </span> </div>
				<div> {{ date("d F Y",strtotime($ticket->created_at)) }} @ {{ date("g:ha",strtotime($ticket->created_at)) }} </div>
				<div id="ticket_post"> {!! $ticket->post !!} </div>
			</div>
		</div>

		<div id="ticket_bottom">
			<table class="table borderless table-striped-warm">
				<tbody>
					<tr>
						<th>Creator</th>
						<td> {!! $ticket->creator_id ? HTML::link(route('people.show', $ticket->creator->person->id), $ticket->creator->person->name()) : '' !!} </td>
						<th>Contact Name</th>
						<td> {!! $ticket->contact_id ? HTML::link(route('people.show', $ticket->contact->person->id), $ticket->contact->person->name()) : '' !!} </td>
					</tr>
					<tr>
						<th>Assignee</th>
						<td> {!! $ticket->assignee_id ? HTML::link(route('people.show', $ticket->assignee->person->id), $ticket->assignee->person->name()) : '' !!} </td>
						<th>Job Type</th>
						<td>{{ count($ticket->job_type) ? $ticket->job_type->name : '' }}</td>
					</tr>
					<tr>
						<th>Ticket Status</th>
						<td>{{ count($ticket->status) ? $ticket->status->name : '' }}</td>					
						<th>Ticket Priority</th>
						<td>{{ count($ticket->priority) ? $ticket->priority->name : '' }}</td>
					</tr>
					<tr>
						<th>Customer</th>
						<td>{!! HTML::link(route('companies.show', $ticket->company->id), $ticket->company->name) !!}</td>
						<th>Phone</th>
						<td>{!! count($ticket->contact) ? $ticket->contact->phone() : '' !!}</td>
					</tr>
					<tr>
						<th>Support Type</th>
						<td>{{ isset($ticket->company->support_type_id) ? $ticket->company->support_type->name : '' }}</td>
						<th>Cell</th>
						<td>{!! count($ticket->contact) ? $ticket->contact->cellphone() : '' !!}</td>
					</tr>
					<tr>
						<th>Connection</th>
						<td> <b> {{ $ticket->company->connection_type->name }} </b> {{ ": " . $ticket->company->connection_type->description }} </td>
						<th>Email</th>
						<td>{{ count($ticket->contact) ? $ticket->contact->email : '' }}</td>
					</tr>
					<tr>
						<th>CC</th>
						<td>{{ count($ticket->equipment) ? $ticket->equipment->cc() : '' }}</td>
						<th>Group Email</th>
						<td>{{ $ticket->company->group_email }}</td>
					</tr>
					<tr>
						<th>Division</th>
						<td>{{ isset($ticket->division_id) ? $ticket->division->name : '' }}</td>
						<th>Account Manager</th>
						<td>{!! isset($ticket->company->account_manager->account_manager_id) ? HTML::link(route('people.show', $ticket->company->account_manager->account_manager_id), $ticket->company->account_manager->company_person->person->name()) : '' !!}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	@if (count($ticket->tags)) 

		<div class="tags">
			TAGS: 
			@foreach ($ticket->tags as $tag)
				<span class="tag"> {{ strtoupper($tag->name) }}</span>
			@endforeach
		</div>

	@endif

	<ul class="nav nav-tabs">
	  <li class="active"><a target="ticket_history" href="#"><i class="fa fa-history"></i> Ticket History</a></li>
	  <li><a target="time_history" href="#"><i class="fa fa-clock-o"></i> Time History</a></li>
	  <li><a target="linked_tickets" href="#"><i class="fa fa-link"></i> Linked Tickets</a></li>
	</ul>

	<div id="tab_contents">
		<div id="ticket_history">

			@if (count($ticket->history))
			
			<table class="table table-striped">
				<thead>
					<th> Changed by </th>
					<th> Assignee </th>
					<th> Status </th>
					<th> Priority </th>
					<th> Division </th>
					<th> Equipment </th>
					<th> Date </th>
				</thead>

				@foreach ($ticket->history as $history)

				<tr>
					<td> {{ isset($history->changer_id) ? $history->changer->person->name() : '' }} </td>
					<td> {{ $history->assignee->person->name() }} </td>
					<td> {{ $history->status->name }} </td>
					<td> {{ $history->priority->name }} </td>
					<td> {{ $history->division->name }} </td>
					<td> - </td>
					<td> {{ date("d M Y",strtotime($history->created_at)) }} @ {{ date("H:i",strtotime($history->created_at)) }} </td>
				</tr>

				@endforeach

			</table>

			@else 

				@include('includes.no-contents')

			@endif

		</div>

		<div id="time_history" style="display:none">
			time history
		</div>

		<div id="linked_tickets" style="display:none">
			linked tickets
		</div>

	</div>
	
	{!! Form::open(array('method' => 'POST', 'route' => 'posts.store') ) !!}

		<div>
			<h3 id="write_post"> Write a post </h3>
			<div class="checkbox is_public"> <label> <input id="is_public" value="true" name="is_public" type="checkbox"> publish </label> </div>
		</div>
		
		{!! Form::hidden("ticket_id", $ticket->id) !!}
		{!! Form::hidden("author_id", Auth::user()->active_contact_id) !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSTextArea('post',null,['id' => 'post']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSSubmit("Submit") !!}
		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

	<div class="navb">
		<div class="title"><i class="fa fa-bars"></i> All Posts</div>
	</div>

	<div id="posts_container">

	@if (count($ticket->posts))

		@foreach ($ticket->posts as $post) 

			<div class="media post">
			  <div class="media-left media-middle">
			    <a href="#">
					<img class="thumbnail thumb-sm" src="{{ $post->author->person->image() }}" alt=" {{ $post->author->person->image() }} ">
			    </a>
			  </div>
			  <div class="media-body">
			    <h4 class="media-heading"> <a href="{{ route('people.show', $post->author->person->id) }}"> {{ $post->author->person->name() }} </a> </h4>
			    <div> 
			    	<span class="post_datetime"> {{ date("d F Y",strtotime($post->created_at)) }} @ {{ date("H:i",strtotime($post->created_at)) }} </span>
			    	<span class="post_edit"> <a href="{{ route('posts.show', $post->id) }}"> Edit </a></span>
			    </div>
			    <div class="post_content"> {!! $post->post !!} </div>
			  </div>
			</div>

		@endforeach

	@else

		@include('includes.no-contents')

	@endif

	</div>

@endsection


