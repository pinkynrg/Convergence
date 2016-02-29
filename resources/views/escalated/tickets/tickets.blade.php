<?php 

function durationFormat($ss) {
	$s = $ss%60;
	$m = floor(($ss%3600)/60);
	$h = floor(($ss%86400)/3600);
	$d = floor($ss/86400);
	return "$d Days $h:$m:$s";
}

?>

<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $tickets->appends(Input::except('page'))->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover table-nowrap">
		<thead>
			<tr class="orderable">
				<th column="statuses.name" class="visible-xs"> </th>
				<th column="tickets.id">Ticket</th>
				<th column="tickets.title">Title</th>
				<th column="statuses.name" class="hidden-xs hidden-ms">Status</th>
				<th column="priorities.id" class="hidden-xs hidden-ms">Priority</th>
				<th column="assignees.last_name" class="hidden-xs hidden-ms">Asignee</th>
				<th column="companies.name" class="hidden-xs hidden-ms">Company</th>
				<th column="divisions.name" class="hidden-xs hidden-ms">Division</th>
				<th column="last_operation_date" class="hidden-xs">Updated</th>
				<th column="levels.name" weight="0" type="desc" class="hidden-xs hidden-ms">Level</th>
				<th column="last_escalation" weight="1" type="desc" class="hidden-xs hidden-ms">Last Escalation</th>
			</tr>
		</thead>
		<tbody>
			@if ($tickets->count())
				@foreach ($tickets as $ticket) 	

				<tr>
					<td class="visible-xs"><i class="ticket_status_icon {{ $ticket->status_icon() }}"></i></td>
					<td> <a href="{{ route('tickets.show', $ticket->id) }}"> {{ "#".$ticket->id }} </a> </td>
					<td> 
						<div class="visible-xs visible-ms ticket_head_details">
							<a href="{{ route('companies.show', $ticket->company->id) }}"> {{ $ticket->company->name }} </a>
						</div>
						<div>
							<b><a href="{{ route('tickets.show', $ticket->id) }}"> {{ $ticket->title() }} </a></b>
						</div>
						<div class="ticket_foot_details"> 
							Reported by <a href="{{ route('people.show', $ticket->creator->person->id) }}"> {{ $ticket->creator->person->name() }} </a> on {{ date("m/d/Y",strtotime($ticket->created_at)) }} 
						</div>
					</td>
					<td class="hidden-xs hidden-ms nowrap"> {{ $ticket->status->label }} </td>
					<td class="hidden-xs hidden-ms nowrap"> {{ $ticket->priority->label }} ({{ $ticket->priority->weight }}) </td>
					<td class="hidden-xs hidden-ms"> <a href="{{ route('people.show', $ticket->assignee->person->id) }}"> {{ $ticket->assignee->person->name() }} </a> </td>
					<td class="hidden-xs hidden-ms"> <a href="{{ route('companies.show', $ticket->company->id) }}"> {{ $ticket->company->name }} </a> </td>
					<td class="hidden-xs hidden-ms"> {{ $ticket->division->label }} </td>
					<td class="hidden-xs"> 
						{{ date("m/d/Y",strtotime($ticket->last_operation_date )) }}
						<div class="ticket_foot_details nowrap"> by 
							<a href="{{ route('people.show', $ticket->last_operation_company_person->person_id) }}"> 
								{{ $ticket->last_operation_company_person->person->name() }} 
							</a> 
						</div>
					</td>
					<td class="hidden-xs hidden-ms">
						{{ $ticket->level->name }}
					</td>
					<td class="hidden-xs hidden-ms nowrap"> 
						<b> {{ durationFormat($ticket->last_escalation) }}</b>
					</td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="10">@include('includes.no-contents')</td></tr>
			@endif

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $tickets->render() !!}
	</div>

</div>