<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $activities->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="activity_log.created_at" weight="0" type="desc">Time</th>
				<th column="users.username">User</th>
				<th column="activity_log.text">Text</th>
				<th column="activity_log.ip_address">IP Address</th>
			</tr>
		</thead>
		<tbody>
			@if ($activities->count())
				@foreach ($activities as $activity)

					<tr>
						<td> {!! $activity->date('created_at',true) !!} </td>
						<td> {!! count($activity->user) ? $activity->user->username : 'System' !!} </td>				
						<td> {{ $activity->text }} </td>
						<td> {{ $activity->ip_address }} </td>
					</tr>

				@endforeach
			@else
				<tr><td colspan="4">@include('includes.no-contents')</td></tr>
			@endif

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $activities->render() !!}
	</div>

</div>