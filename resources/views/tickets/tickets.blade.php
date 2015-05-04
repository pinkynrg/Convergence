<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>Ticket</th>
			<th>Title</th>
			<th>Status</th>
			<th class="hidden-xs">Priority</th>
			<th class="hidden-xs">Asignee</th>
			<th>Customer</th>
			<th class="hidden-xs">Division</th>
			<th class="hidden-xs">Updated</th>
		</tr>
	</thead>
	<tbody>

		@foreach ($tickets as $ticket) 	

		<tr>
			<td> <a href="{{ route('tickets.show', $ticket->id) }}"> {{ "#".$ticket->id }} </a> </td>
			<td> 
				<a href="{{ route('tickets.show', $ticket->id) }}"> {{ $ticket->title }} </a> 
				<div class="ticket_foot_details"> Reported by <a href="{{ route('employees.show', $ticket->creator->id) }}"> {{ $ticket->creator->first_name." ".$ticket->creator->last_name }} </a> on {{ date("m/d/Y",strtotime($ticket->created_at)) }} </div> 
			</td>
			<td> {{ $ticket->status->name }} </td>
			<td class="hidden-xs"> {{ $ticket->priority->name }} </td>
			<td class="hidden-xs"> <a href="{{ route('employees.show', $ticket->assignee->id) }}"> {{ $ticket->assignee->first_name." ".$ticket->assignee->last_name }} </a> </td>
			<td> <a href="{{ route('customers.show', $ticket->customer->id) }}"> {{ $ticket->customer->company_name }} </a> </td>
			<td class="hidden-xs"> {{ $ticket->division->name }} </td>
			<td class="hidden-xs"> {{ date("m/d/Y",strtotime($ticket->updated_at)) }} </td>
		</tr>

		@endforeach

	</tbody>
</table>

<div class="ajax_pagination" scrollup="true" route="{{ route('tickets.tickets.ajax') }}">
	{!! $tickets->render() !!}
</div>