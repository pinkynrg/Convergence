<h3> The following changes were made: </h3>

<table class="table" id="ticket_changes">
	@foreach ($ticket->getChanges() as $key => $change)
		<tr>
			<td class="bold"> {{ ucfirst($key) }} </td>
			@if ($key == 'post')
				<td> <span class="remarked"> Content was changed </span> </td>
			@else
				<td>
					<span class="remarked"> 
						{{ $change['old_value'] }} 
					</span>
					&nbsp;&nbsp;→&nbsp;&nbsp;
					<span class="remarked"> 
						{{ $change['new_value'] }} 
					</span>
				</td>
			@endif
		</tr>
	@endforeach
</table>