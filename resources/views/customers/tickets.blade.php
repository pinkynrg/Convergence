<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>Ticket</th>
			<th>Title</th>
			<th>Status</th>
			<th class="hidden-xs">Asignee</th>
			<th class="hidden-xs">Creator</th>
		</tr>
	</thead>
	<tbody>
	
	@foreach ($tickets as $ticket)

		<tr>
			<td> <a href="{{ route('tickets.show', $ticket->id) }}"> {{ "#".$ticket->id }} </a> </td>
			<td> {{ $ticket->title }} </td>
			<td> {{ $ticket->status->name }} </td>
			<td class="hidden-xs"> <a href="{{ route('employees.show', $ticket->assignee->id) }}"> {{ $ticket->assignee->first_name." ".$ticket->assignee->last_name }} </a> </td>
			<td class="hidden-xs"> <a href="{{ route('employees.show', $ticket->creator->id) }}"> {{ $ticket->creator->first_name." ".$ticket->creator->last_name }} </a> </td>

		</tr>

	@endforeach

	</tbody>
</table>	

<div class="ajax_pagination" route="{{ route('customers.tickets.ajax',$ticket->customer_id) }}">
	{!! $tickets->render() !!}
</div>