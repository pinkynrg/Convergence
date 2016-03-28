@extends('layouts.default')
@section('content')

	@if ($ticket)

		<div id="ticket_cotainer">

			<div id="ticket_wrapper">

				<div id="ticket_header">
					<div class="thumbnail thumb-md">
						<img src="{{ $ticket->creator->person->profile_picture()->path()}}" alt=" {{ $ticket->creator->person->profile_picture()->path() }} ">
						<div> {!! $ticket->creator_id ? HTML::link(route('people.show', $ticket->creator->person->id), $ticket->creator->person->short_name()) : '' !!} </div>
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
							{{ count($ticket->priority) ? $ticket->priority->name : 'TBA' }}
						</div>

						<div> {{ $ticket->date("created_at") }} </div>
					</div>
				</div>

				<div id="ticket_post"> {!! $ticket->post !!} </div>

				@include('files.attachments',['attachments' => $ticket->attachments])

			</div>

			@if (count($ticket->tags))
				<div class="tags">
					TAGS: 
					@foreach ($ticket->tags as $tag)
						<span class="tag"> {{ strtoupper($tag->name) }}</span>
					@endforeach
				</div>
			@endif

			@if (isset($important_post) && Auth::user()->can('read-all-post'))
				<div class="alert alert-{{ $important_post->alert_type }}" role="{{ $important_post->alert_type }}"> 
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<div> <i class="fa fa-info-circle"></i> <b>TICKET IS {{ strtoupper($ticket->status->name) }}</b>: <br> {{ $important_post->post_plain_text }} </div>
				</div>
			@endif

			<ul class="nav nav-tabs">
			  <li class="nav active"><a target="ticket_posts" href="#ticket_posts" data-toggle="tab"><i class="fa fa-list"></i> Posts </a></li>
			  <li class="nav"><a target="ticket_history" href="#ticket_history" data-toggle="tab"><i class="fa fa-history"></i> History </a></li>
			  <li class="nav"><a target="linked_tickets" href="#linked_tickets" data-toggle="tab"><i class="fa fa-link"></i> Links </a></li>
			  <li class="nav"><a target="ticket_details" href="#ticket_details" data-toggle="tab"><i class="fa fa-history"></i> Details </a></li>
			</ul>

			<div class="tab-content">

				<div class="tab-pane fade in active" id="ticket_posts">
					<div id="posts_container">

						@if (count($ticket->posts))
							@foreach ($ticket->posts as $post) 
								@include('posts.post', array("post" => $post))
							@endforeach
						@else
							@include('includes.no-contents')
						@endif

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
							<td> {{ isset($history->changer_id) ? $history->changer->person->name() : "" }} </td>
							<td class="hidden-xs hidden-ms"> {{ count($history->assignee) ? $history->assignee->person->name() : "TBA" }} </td>
							<td class="hidden-xs hidden-ms"> {{ $history->status->name }} </td>
							<td class="hidden-xs hidden-ms"> {{ count($history->priority) ? $history->priority->name : "TBA" }} </td>
							<td class="hidden-xs hidden-ms"> {{ count($history->division) ? $history->division->name : "TBA" }} </td>
							<td class="hidden-xs hidden-ms"> {{ count($history->equipment) ? $history->equipment->name : "TBA" }} </td>
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

				<div class="tab-pane fade" id="ticket_details">
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
									<td>{{ isset($ticket->division_id) && count($ticket->division) ? $ticket->division->name : '' }}</td>
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
			</div>

			@if (Auth::user()->can('create-post'))

				@if ($ticket->status_id == TICKET_REQUESTING_STATUS_ID)

					<div class="alert alert-info" role="alert"> 
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<div> <i class="fa fa-info-circle"></i> This ticket request has to be moderated before being able to write posts. </div>
					</div>

				@elseif ($ticket->status_id == TICKET_CLOSED_STATUS_ID)

					<div class="alert alert-danger" role="alert"> 
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<div> <i class="fa fa-info-circle"></i> This ticket is closed. </div>
					</div>

				@else 

					{!! Form::model($draft_post, array('method' => 'POST', 'route' => 'posts.store', 'id' => 'post_form') ) !!}

						<div>
							<h3 id="write_post"> Write a post </h3>
						</div>

						@if(isset($draft_post->id))
							<div class="alert alert-info" role="alert"> 
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<div> <i class="fa fa-info-circle"></i> This is a draft post lastly updated the {{ $draft_post->date("updated_at") }} ~ OR ~ <a class="clear_form">Clear Form</a></div>
							</div>
						@endif
						
						{!! Form::hidden("ticket_id", $ticket->id) !!}
						{!! Form::hidden("author_id", Auth::user()->active_contact_id) !!}

						@include('posts.form',array('post' => $draft_post))

						@if (Auth::user()->active_contact->isE80()) 

							<div class="slider_container col-xs-12">
								{!! Form::hidden("status_id",null,["id" => "status_id"]) !!}
								<input id="fake_status_id" type="text"/>
							</div>

							<div class="slider_container col-xs-12">
								<input id="priority_id" name="priority_id" type="text"/>
							</div>

							
							<div class="col-xs-12 col-sm-6">

								<h5> Post visibility: </h5>

								<div class="is_public"> 
									{!! Form::hidden("is_public",null,["id" => "is_public"]) !!}
									<label> <input type="checkbox" id="fake_is_public" value="true" class="switch"> Public </label> 
								</div>

							</div>

							<div class="col-xs-12 col-sm-6">

								<h5> Send email to: </h5>
								
								<div class="inline_switch">
									<div class="inline_switch_input"> 
										<input type="checkbox" id="email_account_manager" class="switch" data-off-text="Void" data-on-text="Send" value="" @if (!isset($ticket->company->account_manager)) disabled @endif> 
									</div>
									
									<div class="inline_switch_label" @if (!isset($ticket->company->account_manager)) data-toggle="tooltip" data-placement="right" title="Make sure account manager is selected for company. Also make sure the account manager  has a valid email address." @endif >
										<div>Account manager:</div>
										<div>{{ (!isset($ticket->company->account_manager)) ? "Not Available" : $ticket->company->account_manager->company_person->email }}</div>
									</div>
								</div>
								
								<div class="inline_switch">
									<div class="inline_switch_input"> 
										<input type="checkbox" id="email_company_group_email" class="switch" data-off-text="Void" data-on-text="Send" value="" @if (!isset($ticket->company->group_email)) disabled @endif>  
									</div>
									<div class="inline_switch_label" @if (!isset($ticket->company->group_email)) data-toggle="tooltip" data-placement="right" title="Make sure the group company email is a valid email address." @endif > 
										<div>Company group email:</div>
										<div>{{ (!isset($ticket->company->group_email)) ? "Not Available" : $ticket->company->group_email }} </div>
									</div>
								</div>

								<div class="inline_switch">
									<div class="inline_switch_input"> 
										{!! Form::hidden("email_company_contact",null,["id" => "email_company_contact"]) !!}
										<input type="checkbox"  id="fake_email_company_contact" class="switch" data-off-text="Void" data-on-text="Send" value="" @if (!isset($ticket->contact->email)) disabled @endif> 
									</div>
									<div class="inline_switch_label" @if (!isset($ticket->contact->email)) data-toggle="tooltip" data-placement="right" title="Make sure there is a main contact setup for this ticket." @endif > 
										<div>Contact reference:</div>
										<div>{{ (!isset($ticket->contact->email)) ? "Not Available" : $ticket->contact->email }} </div>
									</div>
								</div>

								<div class="inline_switch">
									<div class="inline_switch_input"> 
										<input type="checkbox" id="email_ticket_emails" class="switch" data-off-text="Void" data-on-text="Send" value="" @if (!isset($ticket->emails) || $ticket->emails == '') disabled @endif>
									</div>
									<div class="inline_switch_label" @if (!isset($ticket->emails) || $ticket->emails == '') data-toggle="tooltip" data-placement="right" title="Make sure to have set other extra email address to this ticket." @endif >
										<div>Additional emails:</div>
										<div>{{ (!isset($ticket->emails) || $ticket->emails == '') ? "Not Available" : $ticket->emails }} </div>
									</div>
								</div>

							</div>

						@else

							{!! Form::hidden("is_public","true",["id" => "is_public"]) !!}
							{!! Form::hidden("priority_id",$ticket->priority_id,["id" => "priority_id"]) !!}

							@if ($ticket->status_id == TICKET_WFF_STATUS_ID)
								{!! Form::hidden("status_id",TICKET_IN_PROGRESS_STATUS_ID,["id" => "status_id"]) !!}
							@else 
								{!! Form::hidden("status_id",$ticket->status_id,["id" => "status_id"]) !!}
							@endif

						@endif

						{!! Form::BSGroup() !!}
							{!! Form::BSSubmit("Submit") !!}
						{!! Form::BSEndGroup() !!}

					{!! Form::close() !!}

				@endif

			@endif

		</div>

	@endif

@endsection