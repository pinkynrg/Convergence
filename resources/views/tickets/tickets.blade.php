<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $tickets->appends(Input::except('page'))->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="id">Ticket</th>
				<th column="title">Title</th>
				<th column="statuses.name">Status</th>
				<th column="priorities.name" class="hidden-xs">Priority</th>
				<th column="assignees.last_name" class="hidden-xs">Asignee</th>
				<th column="customers.company_name">Customer</th>
				<th column="divisions.name" class="hidden-xs">Division</th>
				<th column="updated_at" class="hidden-xs">Updated</th>
			</tr>
		</thead>
		<tbody>

			@foreach ($tickets as $ticket) 	

			<tr>
				<td> <a href="{{ route('tickets.show', $ticket->id) }}"> {{ "#".$ticket->id }} </a> </td>
				<td> 
					<a href="{{ route('tickets.show', $ticket->id) }}"> {{ $ticket->title }} </a> 
					<div class="ticket_foot_details"> Reported by <a href="{{ route('employees.show', $ticket->creator->id) }}"> {{ $ticket->creator->name() }} </a> on {{ date("m/d/Y",strtotime($ticket->created_at)) }} </div> 
				</td>
				<td> {{ $ticket->status->name }} </td>
				<td class="hidden-xs"> {{ $ticket->priority->name }} </td>
				<td class="hidden-xs"> <a href="{{ route('employees.show', $ticket->assignee->id) }}"> {{ $ticket->assignee->name() }} </a> </td>
				<td> <a href="{{ route('customers.show', $ticket->customer->id) }}"> {{ $ticket->customer->company_name }} </a> </td>
				<td class="hidden-xs"> {{ $ticket->division->name }} </td>
				<td class="hidden-xs"> {{ date("m/d/Y",strtotime($ticket->updated_at)) }} </td>
			</tr>

			@endforeach

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $tickets->appends(Input::except('page'))->render() !!}
	</div>

</div>