@extends('layouts.default')

@section('content')
	<div id="dashboard_container">
		<div class="row">
			<div class="col-xs-2">
				<div id="dashboard_contact_info">
					<div id="dashboard_thumb" class="thumbnail thumb-bg">
						<img src="{{ $contact->person->image() }}">
					</div>
					<div id="dashboard_contact_details">
						<div> <b>Name: </b> {{ $contact->person->name() }} </div>
						<div> <b>Username: </b> {{ $contact->person->user->username }} </div>
						<div> <b>Department: </b> {{ isset($contact->department_id) ? $contact->department->name : '' }} </div>
						<div> <b>Title: </b> {{ isset($contact->title_id) ? $contact->title->name : '' }}</div>
					</div>
				</div>
			</div>
			<div class="col-xs-10">
				<div class="row">
					<div class="col-xs-7">
						<div id="user_tickets_chart"> <!-- here goes the chart --> </div>
					</div>
					<div class="col-xs-5">
						
						<table class="table table-hover table-striped table-condensed" id="user_tickets_table">
							<thead>
								<tr>
									<th>Ticket Status</th>
									<th>N. Tickets</th>
									<th>Percentage</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($user_tickets_status_data as $value)
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
					<div class="col-xs-7">
						<div class="" id="user_tickets_involvement_chart"> <!-- here goes the chart --> </div>
					</div>
					<div class="col-xs-5">
						<table class="table table-hover table-striped table-condensed" id="user_tickets_involvement_table">
							<thead>
								<tr>
									<th>Ticket Status</th>
									<th>N. Tickets</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($user_tickets_involvement_data as $value)
									<tr>
										<td> {{ $value->name }} </td>
										<td> {{ $value->number }} </td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>		
				</div>
			</div>
		</div>
	</div>
	

	<script type="text/javascript">
		$('#user_tickets_chart').highcharts({!! $user_tickets_status !!});
		$('#user_tickets_involvement_chart').highcharts({!! $user_tickets_involvement !!});
	</script>

@endsection