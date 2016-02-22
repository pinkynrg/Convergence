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
				<div class="col-xs-3 col-ms-2 col-sm-2 col-md-1 col-lg-1">
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
							<th class="col-xs-6 col-md-3 col-lg-2">Cust. Name: </th>
							<td>{!! HTML::link(route('companies.show', $ticket->company->id), $ticket->company->name) !!}</td>
						</tr>
						<tr>
							<th>Cust. Contact: </th>
							<td> {!! $ticket->contact_id ? HTML::link(route('people.show', $ticket->contact->person->id), $ticket->contact->person->name()) : '' !!} </td>
						</tr>
						<tr>
							<th>Cust. Contact Phone: </th>
							<td>{!! isset($ticket->contact_id) ? $ticket->contact->phone() : '' !!}</td>
						</tr>
						<tr>
							<th>Cust. Contact Cell: </th>
							<td>{!! isset($ticket->contact_id) ? $ticket->contact->cellphone() : '' !!}</td>
						</tr>
						<tr>
							<th>Cust. Support Type: </th>
							<td>{{ isset($ticket->company->support_type_id) ? $ticket->company->support_type->name : '' }}</td>
						</tr>
						<tr>
							<th>Cust. Connection Type: </th>
							<td> <b> {{ $ticket->company->connection_type->name }} </b> {{ ": " . $ticket->company->connection_type->description }} </td>
						</tr>
						</tr>
							<th>Cust. Group Email: </th>
							<td>{{ $ticket->company->group_email }}</td>
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
							<th>Account Manager:</th>
							<td>{!! isset($ticket->company->account_manager->account_manager_id) ? HTML::link(route('people.show', $ticket->company->account_manager->company_person->person_id), $ticket->company->account_manager->company_person->person->name()) : '' !!}</td>
						</tr>
						<tr>
							<th>CC Number: </th>
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
					<th class="hidden-xs hidden-ms"> Assignee </th>
					<th class="hidden-xs hidden-ms"> Status </th>
					<th class="hidden-xs hidden-ms"> Priority </th>
					<th class="hidden-xs hidden-ms"> Division </th>
					<th class="hidden-xs hidden-ms"> Equipment </th>
					<th> Date </th>
				</thead>

				@foreach ($ticket->history as $history)

				<tr>
					<td> {{ isset($history->changer_id) ? $history->changer->person->name() : '' }} </td>
					<td class="hidden-xs hidden-ms"> {{ $history->assignee->person->name() }} </td>
					<td class="hidden-xs hidden-ms"> {{ $history->status->name }} </td>
					<td class="hidden-xs hidden-ms"> {{ $history->priority->name }} </td>
					<td class="hidden-xs hidden-ms"> {{ $history->division->name }} </td>
					<td class="hidden-xs hidden-ms"> - </td>
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

		<div class="col-xs-12 col-sm-6">

			<h5> Change ticket status: </h5>

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

		<div class="col-xs-12 col-sm-6">

			<h5> Send email to: </h5>
			
			<div class="email_checkbox">
				<div class="email_checkbox_input"> 
					<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" value="" @if (!isset($ticket->company->account_manager)) disabled @endif> 
				</div>
				
				<div class="email_checkbox_label" @if (!isset($ticket->company->account_manager)) data-toggle="tooltip" data-placement="right" title="Make sure account manager is selected for company. Also make sure the account manager  has a valid email address." @endif >
					<div>Account manager:</div>
					<div>{{ (!isset($ticket->company->account_manager)) ? "Not Available" : $ticket->company->account_manager->company_person->email }}</div>
				</div>
			</div>
			
			<div class="email_checkbox">
				<div class="email_checkbox_input"> 
					<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" value="" @if (!isset($ticket->company->group_email)) disabled @endif>  
				</div>
				<div class="email_checkbox_label" @if (!isset($ticket->company->group_email)) data-toggle="tooltip" data-placement="right" title="Make sure the group company email is a valid email address." @endif > 
					<div>Company group email:</div>
					<div>{{ (!isset($ticket->company->group_email)) ? "Not Available" : $ticket->company->group_email }} </div>
				</div>
			</div>

			<div class="email_checkbox">
				<div class="email_checkbox_input"> 
					<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" value="" @if (!isset($ticket->contact->email)) disabled @endif> 
				</div>
				<div class="email_checkbox_label" @if (!isset($ticket->contact->email)) data-toggle="tooltip" data-placement="right" title="Make sure there is a main contact setup for this ticket." @endif > 
					<div>Contact reference:</div>
					<div>{{ (!isset($ticket->contact->email)) ? "Not Available" : $ticket->contact->email }} </div>
				</div>
			</div>

			<div class="email_checkbox">
				<div class="email_checkbox_input"> 
					<input type="checkbox" class="switch" data-off-text="Void" data-on-text="Send" value="" @if (!isset($ticket->emails) || $ticket->emails == '') disabled @endif>
				</div>
				<div class="email_checkbox_label" @if (!isset($ticket->emails) || $ticket->emails == '') data-toggle="tooltip" data-placement="right" title="Make sure to have set other extra email address to this ticket." @endif >
					<div>Additional emails:</div>
					<div>{{ (!isset($ticket->emails) || $ticket->emails == '') ? "Not Available" : $ticket->emails }} </div>
				</div>
			</div>

		</div>

		{!! Form::BSGroup() !!}
			{!! Form::BSSubmit("Submit") !!}
		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection


