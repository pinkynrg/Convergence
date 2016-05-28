<h3> The following changes were made: </h3>

<table class="table" id="ticket_changes">
	@foreach ($ticket->diff() as $key => $change)
		<tr>
			<td class="bold"> {{ $key }}: </td>
			@if ($key == "Title" || $key == 'Post')
				<td> <span class="remarked"> Content was changed </span> </td>
			@else
				<td>
					<span class="remarked"> 
						{{ $change->old }} 
					</span>
					&nbsp;&nbsp;→&nbsp;&nbsp;
					<span class="remarked"> 
						{{ $change->new }} 
					</span>
				</td>
			@endif
		</tr>
	@endforeach
</table>