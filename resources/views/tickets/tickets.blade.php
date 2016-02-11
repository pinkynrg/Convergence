<div class="content">

	@if (Route::currentRouteName() == "tickets.index")
		<div class="ajax_pagination" scrollup="false">
			{!! $tickets->appends(Input::except('page'))->render() !!}
		</div>
	@endif

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="statuses.name" class="visible-xs"></th>
				<th column="tickets.id" type="desc">Ticket</th>
				<th column="tickets.title">Title</th>
				<th column="statuses.name" class="hidden-xs">Status</th>
				<th column="priorities.id" class="hidden-xs">Priority</th>
				<th column="assignees.last_name" class="hidden-xs">Asignee</th>
				
				@if (Route::currentRouteName() == "tickets.index")
					<th column="companies.name">Company</th>
				@endif

				<th column="divisions.name" class="hidden-xs">Division</th>
				<th column="last_operation_date" class="hidden-xs">Updated</th>
			</tr>
		</thead>
		<tbody>
			@if ($tickets->count())
				@foreach ($tickets as $ticket) 	

				<tr>
					<th column="statuses.name" class="visible-xs"><i class="{{ $ticket->status_icon() }}"></i></th>
					<td> <a href="{{ route('tickets.show', $ticket->id) }}"> {{ "#".$ticket->id }} </a> </td>
					<td> 
						<a href="{{ route('tickets.show', $ticket->id) }}"> {{ $ticket->title }} </a> 
						<div class="ticket_foot_details"> Reported by <a href="{{ route('people.show', $ticket->creator->person->id) }}"> {{ $ticket->creator->person->name() }} </a> on {{ date("m/d/Y",strtotime($ticket->created_at)) }} </div> 
					</td>
					<td class="hidden-xs"> {{ $ticket->status->name }} </td>
					<td class="hidden-xs"> {{ $ticket->priority->name }} </td>
					<td class="hidden-xs"> <a href="{{ route('people.show', $ticket->assignee->person->id) }}"> {{ $ticket->assignee->person->name() }} </a> </td>
					
					@if (Route::currentRouteName() == "tickets.index")
						<td> <a href="{{ route('companies.show', $ticket->company->id) }}"> {{ $ticket->company->name }} </a> </td>
					@endif

					<td class="hidden-xs"> {{ $ticket->division->name }} </td>
					<td class="hidden-xs"> 
						{{ date("m/d/Y",strtotime($ticket->last_operation_date )) }}
						<div class="ticket_foot_details"> by 
							<a href="{{ route('people.show', $ticket->last_operation_company_person->person_id) }}"> 
								{{ $ticket->last_operation_company_person->person->name() }} 
							</a> 
						</div>
					</td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="8">@include('includes.no-contents')</td></tr>
			@endif

		</tbody>
	</table>

	@if (Route::currentRouteName() == "tickets.index")
		<div class="ajax_pagination" scrollup="true">
			{!! $tickets->render() !!}
		</div>
	@endif 

	@if (Route::currentRouteName() == "companies.tickets" || Route::currentRouteName() == "companies.show")
		<div class="ajax_pagination" scrollup="false">
			{!! $tickets->render() !!}
		</div>
	@endif

</div>