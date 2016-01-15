<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $tickets->appends(Input::except('page'))->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="tickets.id" type="desc">Ticket</th>
				<th column="tickets.title">Title</th>
				<th column="statuses.name">Status</th>
				<th column="priorities.id" class="hidden-xs">Priority</th>
				<th column="assignees.last_name" class="hidden-xs">Asignee</th>
				<th column="companies.name">Company</th>
				<th column="divisions.name" class="hidden-xs">Division</th>
				<th column="tickets.updated_at" class="hidden-xs">Updated</th>
			</tr>
		</thead>
		<tbody>
			@if ($tickets->count())
				@foreach ($tickets as $ticket) 	

				<tr>
					<td> <a href="{{ route('tickets.show', $ticket->id) }}"> {{ "#".$ticket->id }} </a> </td>
					<td> 
						<a href="{{ route('tickets.show', $ticket->id) }}"> {{ $ticket->title }} </a> 
						<div class="ticket_foot_details"> Reported by <a href="{{ route('people.show', $ticket->creator->person->id) }}"> {{ $ticket->creator->person->name() }} </a> on {{ date("m/d/Y",strtotime($ticket->created_at)) }} </div> 
					</td>
					<td> {{ $ticket->status->name }} </td>
					<td class="hidden-xs"> {{ $ticket->priority->name }} </td>
					<td class="hidden-xs"> <a href="{{ route('people.show', $ticket->assignee->person->id) }}"> {{ $ticket->assignee->person->name() }} </a> </td>
					<td> <a href="{{ route('companies.show', $ticket->company->id) }}"> {{ $ticket->company->name }} </a> </td>
					<td class="hidden-xs"> {{ $ticket->division->name }} </td>
					<td class="hidden-xs"> 
						{{ date("m/d/Y",strtotime($ticket->last_operation_date)) }}
						<div class="ticket_foot_details"> by <a href="{{ route('people.show', $ticket->last_operation_company_person->person->id) }}"> {{ $ticket->last_operation_company_person->person->name() }} </a> </div>
					</td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="8">@include('includes.no-contents')</td></tr>
			@endif

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $tickets->appends(Input::except('page'))->render() !!}
	</div>

</div>