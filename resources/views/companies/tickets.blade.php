<div class="content">
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="tickets.id">Ticket</th>
				<th column="tickets.title">Title</th>
				<th column="statuses.name">Status</th>
				<th column="assignees.last_name"class="hidden-xs">Asignee</th>
				<th column="creators.last_name" class="hidden-xs">Creator</th>
			</tr>
		</thead>
		<tbody>
		
		@foreach ($tickets as $ticket)

			<tr>
				<td> <a href="{{ route('tickets.show', $ticket->id) }}"> {{ "#".$ticket->id }} </a> </td>
				<td> {{ $ticket->title }} </td>
				<td> {{ $ticket->status->name }} </td>
				<td class="hidden-xs"> <a href="{{ route('people.show', $ticket->assignee->id) }}"> {{ $ticket->assignee->name() }} </a> </td>
				<td class="hidden-xs"> <a href="{{ route('people.show', $ticket->creator->id) }}"> {{ $ticket->creator->name() }} </a> </td>

			</tr>

		@endforeach

		</tbody>
	</table>	

	<div class="ajax_pagination" scrollup="false">
		{!! $tickets->render() !!}
	</div>
</div>