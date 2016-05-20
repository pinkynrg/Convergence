<table class="table" id="ticket_container">
	<tr>
		<td width="50" class="thumbnail" rowspan="4">
			<img width="50" src="{{ SITE_URL.$ticket->creator->person->profile_picture()->path() }}"/>
		</td>
	</tr>

	<tr>
		<td id="ticket_title"> {{ $ticket->title }}</td>
	</tr>

	<tr>
		<td> {{ $ticket->creator->person->name() }} </td>
	</tr>
	
	<tr>
		<td> {{ date("m/d/Y ~ h:i A",strtotime($ticket->created_at)) }} </td>
	</tr>
</table>

<div class="post">
	{!! $ticket->post('html') !!}
</div>

@if (count($ticket->attachments)) 
	<h4>Ticket Attachments</h4>
	@include('emails.includes.attachments',['attachments' => $ticket->attachments])
@endif