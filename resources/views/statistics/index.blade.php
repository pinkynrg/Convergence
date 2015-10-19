@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-xs-6">
			<div id="tickets_status_chart"> <!-- here goes the chart --> </div>
		</div>
		<div class="col-xs-6">
			
			<table class="table table-hover table-striped table-condensed" id="tickets_table">
				<thead>
					<tr>
						<th>Ticket Status</th>
						<th>N. Tickets</th>
						<th>Percentage</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($tickets_status_data as $value)
						<tr>
							<td> {{ $value->name }} </td>
							<td> {{ $value->number }} </td>
							<td> {{ $value->percentage.' %' }} </td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-xs-6">
			<div id="tickets_division_chart"> <!-- here goes the chart --> </div>
		</div>
		<div class="col-xs-6">
			
			<table class="table table-hover table-striped table-condensed" id="tickets_table">
				<thead>
					<tr>
						<th>Ticket Division</th>
						<th>N. Tickets</th>
						<th>Percentage</th>
						@foreach ($statuses as $status)
							<th>{{ $status->name }}</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@foreach ($tickets_division_data as $value)
						<tr>
							<td> {{ $value->name }} </td>
							<td> {{ $value->number }} </td>
							<td> {{ $value->percentage.' %' }} </td>
							@foreach ($value->details as $detail)
								<td> {{ $detail->percentage.'% ('.$detail->number.')' }} </td>
							@endforeach
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">
		$('#tickets_status_chart').highcharts({!! $tickets_status !!});
		$('#tickets_division_chart').highcharts({!! $tickets_division !!});
	</script>

@endsection