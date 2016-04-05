<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $activities->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="activity_log.created_at" weight="0" type="desc">Time</th>
				<th column="users.username">User</th>
				<th column="people.last_name" class="hidden-xs hidden-ms">Name</th>
				<th column="companies.name" class="hidden-xs hidden-ms">Company</th>
				<th column="activity_log.path" class="hidden-xs hidden-ms">Path</th>
				<th column="activity_log.text">Text</th>
				<th column="activity_log.request" class="hidden-xs hidden-ms">Request</th>
				<th column="activity_log.ip_address" class="hidden-xs hidden-ms">IP Address</th>
			</tr>
		</thead>
		<tbody>
			@if ($activities->count())
				@foreach ($activities as $activity)

					<tr>
						<td> {!! $activity->date('created_at',true) !!} </td>
						<td> {!! count($activity->user) ? $activity->user->username : 'System' !!} </td>				
						<td class="hidden-xs hidden-ms"> 
							{!! count($activity->contact) ? 
								$activity->contact->person->last_name." ".$activity->contact->person->first_name : 
								'System' !!} 
						</td>
						<td class="hidden-xs hidden-ms"> {{ count($activity->contact) ? $activity->contact->company->name : 'System' }} </td>
						<td class="hidden-xs hidden-ms"> {{ $activity->path }} </td>
						<td> {{ $activity->text() }} </td>
						<td class="hidden-xs hidden-ms"> 

							@if ($activity->request != "[]") 

								<button type="button" class="btn btn-primary activity-request-btn" data-toggle="modal" data-target=".activity{{ $activity->id }}">
									Request
								</button>

								<div class="modal fade activity{{ $activity->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
								  <div class="modal-dialog modal-lg">
								    <div class="modal-content">
								    	<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Request Content</h4>
										</div>

										<pre>{{ json_encode(json_decode($activity->request),JSON_PRETTY_PRINT) }}</pre>

								      	<div class="modal-footer">
								        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								      	</div>
								    </div>
								  </div>
								</div>

							@else 

								<button type="button" class="btn btn-primary activity-request-btn" data-toggle="modal" data-target=".activity{{ $activity->id }}" disabled>
									Request
								</button>

							@endif

						</td>
						<td class="hidden-xs hidden-ms"> {{ $activity->ip_address }} </td>
					</tr>

				@endforeach
			@else
				<tr><td colspan="8">@include('includes.no-contents')</td></tr>
			@endif

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $activities->render() !!}
	</div>

</div>
