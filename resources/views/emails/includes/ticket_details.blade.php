<table class="table" id="ticket_details">
	<tr>
		<td class="bold" width="140">Ticket #</td>
		<td>
			<a href="{{SITE_URL."/tickets/".$ticket->id}}">
				{{ $ticket->id }}
			</a>
		</td>
	</tr>
	<tr>
		<td class="bold">Title</td><td>{{ $ticket->title }}</td>
	</tr>
	<tr>
		<td class="bold">Author</td><td>{{ $ticket->creator->person->name() }}</td>
	</tr>
	<tr>
		<td class="bold">Assignee</td><td>{{ $ticket->assignee->person->name() }}</td>
	</tr>
	<tr>
		<td class="bold">Status</td><td>{{ $ticket->status->name }}</td>
	</tr>
	<tr>
		<td class="bold">Priority</td><td>{{ $ticket->priority->name }}</td>
	</tr>
	<tr>
		<td class="bold">Contact Name:</td><td>{{ isset($ticket->contact_id) ? $ticket->contact->person->name() : '-' }}</td>
	</tr>
	<tr>
		<td class="bold">Contact Phone:</td><td>{!! isset($ticket->contact_id) && isset($ticket->contact->phone) ? $ticket->contact->phone() : '-' !!}</td>
	</tr>
	<tr>
		<td class="bold">Contact Cellphone:</td><td>{!! isset($ticket->contact_id) && isset($ticket->contact->cellphone) ? $ticket->contact->cellphone() : '-' !!}</td>
	</tr>
	<tr>
		<td class="bold">Contact Email:</td><td>{!! isset($ticket->contact_id) && isset($ticket->contact->email) ? $ticket->contact->email() : '-' !!}</td>
	</tr>
</table>
