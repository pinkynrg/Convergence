@extends('layouts.default')
@section('content')

	<div id="ticket_container">

		<div id="ticket_content" class="media">
			<div class="media-left media-middle">
				<img class="thumbnail thumb-md" src="{{ $ticket->creator->person->image() }}" alt=" {{ $ticket->creator->person->image() }} ">
			</div>
			<div class="media-body">
				<div id="ticket_title" class="media-heading"> {{ $ticket->title }}  <span id="ticket_status" class="{{ $status_class }}"> <i class='fa fa-circle'></i> {{ $ticket->status->name }} </span> </div>
				<div> {{ $ticket->date("created_at") }} </div>
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
						<td>{!! isset($ticket->company->account_manager->account_manager_id) ? HTML::link(route('people.show', $ticket->company->account_manager->company_person->person_id), $ticket->company->account_manager->company_person->person->name()) : '' !!}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="ticket_media">
		<div class="row">

			@foreach ($ticket->attachments as $attachment)
				<div class="col-lg-1 col-xs-2">
					<a href="{{ $attachment->path() }}" class="thumbnail">
						<img src="{{ $attachment->thumbnail() }}" alt="...">
					</a>
				</div>
			@endforeach

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
	  <li class="nav active"><a target="ticket_history" href="#ticket_history" data-toggle="tab"><i class="fa fa-history"></i> Ticket History</a></li>
	  <li class="nav"><a target="time_history" href="#time_history" data-toggle="tab"><i class="fa fa-clock-o"></i> Time History</a></li>
	  <li class="nav"><a target="linked_tickets" href="#linked_tickets" data-toggle="tab"><i class="fa fa-link"></i> Linked Tickets</a></li>
	</ul>

	<div class="tab-content mrg-brm-20">
		<div class="tab-pane fade in active" id="ticket_history">

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
					<td> {{ $history->date("created_at") }} </td>
				</tr>

				@endforeach

			</table>

			@else 

				@include('includes.no-contents')

			@endif

		</div>

		<div class="tab-pane fade" id="time_history">
			time history
		</div>

		<div class="tab-pane fade" id="linked_tickets">
			linked tickets
		</div>

	</div>

	<div class="navb mrg-brm-20">
		<div class="title"><i class="fa fa-bars"></i> All Posts</div>
	</div>

	<div id="posts_container">

		@if (count($ticket->posts))

			@foreach ($ticket->posts as $post) 

				@include('posts.post', array("post" => $post))			
				<hr>

			@endforeach

		@else

			@include('includes.no-contents')

		@endif

	</div>

	{!! Form::model($draft_post, array('method' => 'POST', 'route' => 'posts.store') ) !!}

		<div>
			<h3 id="write_post"> Write a post </h3>
		</div>

		@if(isset($draft_post->id))
			<div class="alert alert-info" role="alert"> 
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div> <i class="fa fa-info-circle"></i> This is a draft post lastly updated the $draft_post->date("updated_at") }} </div>
			</div>
		@endif
		
		{!! Form::hidden("ticket_id", $ticket->id) !!}
		{!! Form::hidden("author_id", Auth::user()->active_contact_id) !!}

		@include('posts.form',array('post' => $draft_post))

		<div class="col-xs-6">

			<div class="checkbox is_public"> 
				<label> <input id="is_public" value="true" name="is_public" type="checkbox"> Show post to customer </label> 
			</div>

		</div>

		<div class="col-xs-6">
			<div class="checkbox">
	  			<label @if ((!isset($ticket->company->account_manager))) data-toggle="tooltip" data-placement="right" title="Make sure account manager is selected for company. Also make sure the account manager  has a valid email address." @endif><input type="checkbox" @if (!isset($ticket->company->account_manager)) disabled @endif value=""> Send email to account manager ~ {{ isset($ticket->company->account_manager) ? $ticket->company->account_manager->company_person->email : 'Not Available'}}</label>
			</div>
			<div class="checkbox">
	  			<label @if (!isset($ticket->company->group_email)) data-toggle="tooltip" data-placement="right" title="Make sure the group company email is a valid email address." @endif><input type="checkbox" @if (!isset($ticket->company->group_email)) disabled @endif value=""> Send email to company group email ~ {{ isset($ticket->company->group_email) ? $ticket->company->group_email : 'Not Available' }} </label>
			</div>
			<div class="checkbox">
	  			<label @if (!isset($ticket->contact_id) || !isset($ticket->contact->email)) data-toggle="tooltip" data-placement="right" title="Make sure there is a main contact setup for this ticket." @endif><input type="checkbox" @if (!isset($ticket->contact_id) || !isset($ticket->contact->email)) disabled @endif value=""> Send email to ticket contact reference ~ {{ isset($ticket->contact_id) ? $ticket->contact->email : 'Not Available' }} </label>
			</div>
			<div class="checkbox">
	  			<label @if (!isset($ticket->emails)) data-toggle="tooltip" data-placement="right" title="Make sure to have set other extra email address to this ticket." @endif><input type="checkbox" @if (!isset($ticket->emails)) disabled @endif value=""> Send email to additional ticket emails ~ {{ isset($ticket->emails) ? $ticket->emails : 'Not Available' }} </label>
			</div>
		</div>

		{!! Form::BSGroup() !!}
			{!! Form::BSSubmit("Submit") !!}
		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection


