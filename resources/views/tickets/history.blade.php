@if (count($ticket->history))
	<table id="history" class="table table-striped table-condensed table-hover">
		<thead>
			<tr>
				<th class="hidden-xs hidden-ms">Date</th>
				<th class="hidden-xs hidden-ms">Changer</th>
				<th>Changes</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($ticket->history as $history)
				@if (!empty($ticket->diff($history->previous_id,$history->id)))
					<tr>
						<td class="hidden-xs hidden-ms nowrap"> {{ $history->date("created_at") }} </td>
						<td class="hidden-xs hidden-ms nowrap"> {{ $history->changer->person->name() }} </td>
						<td>
							<div class="changer visible-xs visible-ms"> 
								Updated by <b> {{ $history->changer->person->name() }} </b>
								the <b> {{ $history->date("created_at") }} </b>
							</div>
							@foreach ($ticket->diff($history->previous_id,$history->id) as $key => $change)
								<div class="history_change">
									<span class="label_change">{{ $key }}:</span>
									@if ($key == "Post" || $key == "Title") 
										<span class="read_more"> <code>Changed</code> Read more...</span>
										<span class="collapsed content_change">
											<code>{{ $change->old }}</code> → <code>{{ $change->new }}</code>
										</span>
									@else
										<span class="content_change">
											<code>{{ $change->old }}</code> → <code>{{ $change->new }}</code>
										</span>
									@endif 
								</div>
							@endforeach
						</td>
					</tr>
				@endif
			@endforeach
		</tbody>
	</table>

@else 

	@include('includes.no-contents')

@endif