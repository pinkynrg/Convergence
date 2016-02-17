@extends('layouts.default')
@section('content')

	<div id="ticket_wrapper">

		<div class="ticket_header">
			<div class="thumbnail thumb-md">
				<img src="{{ $ticket->creator->person->image() }}" alt=" {{ $ticket->creator->person->image() }} ">
				<div> {!! $ticket->creator_id ? HTML::link(route('people.show', $ticket->creator->person->id), $ticket->creator->person->name()) : '' !!} </div>
			</div>

			<div class="ticket_header_details">
				<div id="ticket_title"> 
					{{ $ticket->title }}
				</div>

				<div id="ticket_status" class="{{ $status_class }}"> 
					<span> <i class='fa fa-circle'></i> </span>
					<span> {{ $ticket->status->name }} </span>
				</div> 

				<div id="priority">
					{{ isset($ticket->priority_id) ? $ticket->priority->name : '' }}
				</div>

				<div> {{ $ticket->date("created_at") }} </div>
			</div>
		</div>

		<div id="ticket_post"> {!! $ticket->post !!} </div>

	</div>

	<div class="ticket_attachments">
		
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
	  <li class="nav active"><a target="ticket_details" href="#ticket_details" data-toggle="tab"><i class="fa fa-history"></i> Details </a></li>
	  <li class="nav"><a target="ticket_history" href="#ticket_history" data-toggle="tab"><i class="fa fa-history"></i> History </a></li>
	  <li class="nav"><a target="linked_tickets" href="#linked_tickets" data-toggle="tab"><i class="fa fa-link"></i> Links </a></li>
	</ul>

	<div class="tab-content mrg-brm-20">

		<div class="tab-pane fade in active" id="ticket_details">
			<div class="table-responsive">

				<table class="table table-striped table-condensed table-hover">
					<tbody>
						<tr>
							<th class="col-xs-6 col-md-3 col-lg-2">Customer Name: </th>
							<td>{!! HTML::link(route('companies.show', $ticket->company->id), $ticket->company->name) !!}</td>
						</tr>
						<tr>
							<th>Customer Contact: </th>
							<td> {!! $ticket->contact_id ? HTML::link(route('people.show', $ticket->contact->person->id), $ticket->contact->person->name()) : '' !!} </td>
						</tr>
						<tr>
							<th>Customer Contact Phone: </th>
							<td>{!! isset($ticket->contact_id) ? $ticket->contact->phone() : '' !!}</td>
						</tr>
						<tr>
							<th>Customer Contact Cell: </th>
							<td>{!! isset($ticket->contact_id) ? $ticket->contact->cellphone() : '' !!}</td>
						</tr>
						<tr>
							<th>Customer Support Type: </th>
							<td>{{ isset($ticket->company->support_type_id) ? $ticket->company->support_type->name : '' }}</td>
						</tr>
						<tr>
							<th>Customer Connection Type: </th>
							<td> <b> {{ $ticket->company->connection_type->name }} </b> {{ ": " . $ticket->company->connection_type->description }} </td>
						</tr>
						</tr>
							<th>Customer Group Email: </th>
							<td>{{ $ticket->company->group_email }}</td>
						</tr>
						<tr>
							<th>Customer Account Manager:</th>
							<td>{!! isset($ticket->company->account_manager->account_manager_id) ? HTML::link(route('people.show', $ticket->company->account_manager->company_person->person_id), $ticket->company->account_manager->company_person->person->name()) : '' !!}</td>
						</tr>
						<tr>
							<th>Ticket Assignee: </th>
							<td> {!! $ticket->assignee_id ? HTML::link(route('people.show', $ticket->assignee->person->id), $ticket->assignee->person->name()) : '' !!} </td>
						</tr>
						<tr>
							<th>Ticket Job Type: </th>
							<td>{{ count($ticket->job_type) ? $ticket->job_type->name : '' }}</td>
						</tr>
						<tr>
							<th>Ticket Division: </th>
							<td>{{ isset($ticket->division_id) ? $ticket->division->name : '' }}</td>
						</tr>
						<tr>
							<th>Customer CC Number: </th>
							<td>{{ count($ticket->equipment) ? $ticket->equipment->cc() : '' }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="tab-pane fade" id="ticket_history">

			@if (count($ticket->history))
			
			<table class="table table-striped">
				<thead>
					<th> Changed by </th>
					<th class="hidden-xs"> Assignee </th>
					<th class="hidden-xs"> Status </th>
					<th class="hidden-xs"> Priority </th>
					<th class="hidden-xs"> Division </th>
					<th class="hidden-xs"> Equipment </th>
					<th> Date </th>
				</thead>

				@foreach ($ticket->history as $history)

				<tr>
					<td> {{ isset($history->changer_id) ? $history->changer->person->name() : '' }} </td>
					<td class="hidden-xs"> {{ $history->assignee->person->name() }} </td>
					<td class="hidden-xs"> {{ $history->status->name }} </td>
					<td class="hidden-xs"> {{ $history->priority->name }} </td>
					<td class="hidden-xs"> {{ $history->division->name }} </td>
					<td class="hidden-xs"> - </td>
					<td> {{ $history->date("created_at") }} </td>
				</tr>

				@endforeach

			</table>

			@else 

				@include('includes.no-contents')

			@endif

		</div>

		<div class="tab-pane fade" id="linked_tickets">
			
			<h5>Linked To:</h5>
			@if (count($ticket->links))
				@include('tickets.tickets', array('tickets' => $ticket->links))
			@else
				@include('includes.no-contents')
			@endif

			<h5>Linked By:</h5>
			@if (count($ticket->linked_to))
				@include('tickets.tickets', array('tickets' => $ticket->linked_to))
			@else
				@include('includes.no-contents')
			@endif

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
				<div> <i class="fa fa-info-circle"></i> This is a draft post lastly updated the {{ $draft_post->date("updated_at") }} </div>
			</div>
		@endif
		
		{!! Form::hidden("ticket_id", $ticket->id) !!}
		{!! Form::hidden("author_id", Auth::user()->active_contact_id) !!}

		@include('posts.form',array('post' => $draft_post))

		<div class="col-xs-6">

			<div class="is_public"> 
				<label> <input type="checkbox" id="is_public" value="true" name="is_public" class="switch"> Public </label> 
			</div>

			<div class="status_checkbox"> 
				<label> <input type="radio" radioAllOff="true" id="set_waiting_for_feedback" value="{{TICKET_WFF_STATUS_ID}}" name="status_id" class="switch"> Waiting for feedback </label> 
			</div>

			<div class="status_checkbox"> 
				<label> <input type="radio" radioAllOff="true" id="is_public" value="{{TICKET_SOLVED_STATUS_ID}}" name="status_id" class="switch"> Solved </label> 
			</div>

		</div>

		<div class="col-xs-6">
			
			<div class="email_checkbox">
				@if (!isset($ticket->company->account_manager))
					<label data-toggle="tooltip" data-placement="right" title="Make sure account manager is selected for company. Also make sure the account manager  has a valid email address.">
						<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" disabled value=""> Email to account manager ~ Not Available
					</label>
				@else
					<label>
						<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" value=""> Send email to account manager ~ {{ $ticket->company->account_manager->company_person->email }}
					</label>
				@endif
			</div>
			
			<div class="email_checkbox">
				@if (!isset($ticket->company->group_email))
					<label data-toggle="tooltip" data-placement="right" title="Make sure the group company email is a valid email address.">
						<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" disabled value=""> Email to company group email ~ Not Available
					</label>
				@else
					<label>
						<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" value=""> Email to company group email ~ {{ $ticket->company->group_email }}
					</label>
				@endif
			</div>

			<div class="email_checkbox">
				@if (!isset($ticket->contact->email))
					<label data-toggle="tooltip" data-placement="right" title="Make sure there is a main contact setup for this ticket.">
						<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" disabled value=""> Email to contact reference ~ Not Available
					</label>
				@else
					<label>
						<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" value=""> Email to contact reference ~ {{ $ticket->contact->email }}
					</label>
				@endif
			</div>

			<div class="email_checkbox">
				@if (!isset($ticket->emails) || $ticket->emails == '')
					<label data-toggle="tooltip" data-placement="right" title="Make sure to have set other extra email address to this ticket.">
						<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" disabled value=""> Email to additional ticket emails ~ Not Available
					</label>
				@else
					<label>
						<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" value=""> Email to additional ticket emails ~ {{ $ticket->emails }}
					</label>
				@endif
			</div>

		</div>

		{!! Form::BSGroup() !!}
			{!! Form::BSSubmit("Submit") !!}
		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection


