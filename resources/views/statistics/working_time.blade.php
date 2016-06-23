@extends('layouts.default')

@section('content')

	<div class="alert alert-info" role="alert"> 
		<p> 
			<i class="fa fa-info-circle"></i>
			The following time values are considered to be only active working time. <br>
			(all the time spent on tickets when status was neither <b>waiting customer feedback</b>, <b>solved</b>, or <b>closed</b>).
		</p>
		<p> The following time values are expresses in hours. </p>
	</div>

	@foreach ($dataset as $type => $data)

		<h3> {{ ucfirst($type) }} </h3>

		<table id="working_time" class="table table-striped table-condensed table-hover table-bordered">
			<tr>
				<th class="nowrap"> Division </th>
				<th> Sum <span class="prev">(prev.)</span> </th>
				<th> Historical Sums </th>
				<th> Tickets <span class="prev">(prev.)</span> </th>
				<th> Historical Count </th>
				<th> Average <span class="prev">(prev.)</span> </th>
				<th> Historical Average </th>
			</tr>

			@foreach ($data as $division => $values)
				
				<tr>
					<td class="nowrap"> {{ str_replace("_"," ",$division) }} </td>

					<td>
						
						@if ($values['sum']['current'] < $values['sum']['previous']) 
							<i class="{{ DECREASED_VALUE_ICON }}"></i> 
						@else 
							@if ($values['average']['current'] > $values['average']['previous'])
								<i class="{{ INCREASED_VALUE_ICON }}"></i> 
							@endif
						@endif

						{{ $values['sum']['current'] }} 
						<span class="prev">({{ $values['sum']['previous'] }})</span>
					</td>
					
					<td>
						<div id="historical_sum_{{ $division }}">  </div>
					</td>

					<td>
						@if ($values['ticket_count']['current'] < $values['ticket_count']['previous']) 
							<i class="{{ DECREASED_VALUE_ICON }}"></i> 
						@else 
							@if ($values['ticket_count']['current'] > $values['ticket_count']['previous'])
								<i class="{{ INCREASED_VALUE_ICON }}"></i> 
							@endif
						@endif

						{{ $values['ticket_count']['current'] }} 
						<span class="prev">({{ $values['ticket_count']['previous'] }})</span>
					</td>
					
					<td>
						<div id="historical_ticket_count_{{ $division }}">  </div>
					</td>


					<td>
						
						@if ($values['average']['current'] < $values['average']['previous']) 
							<i class="{{ DECREASED_VALUE_ICON }}"></i> 
						@else 
							@if ($values['average']['current'] > $values['average']['previous'])
								<i class="{{ INCREASED_VALUE_ICON }}"></i> 
							@endif
						@endif

						{{ $values['average']['current'] }} 
						<span class="prev">({{ $values['average']['previous'] }})</span>
					</td>

					<td>
						<div id="historical_average_{{ $division }}">  </div>
					</td>

				</tr>
			@endforeach
		</table>

	@endforeach
	
	@foreach ($dataset as $type => $data)
		@foreach ($data as $division => $values)
			@foreach ($values as $type => $vals)
				<script type="text/javascript">
					$("#historical_{{$type}}_{{$division}}").highcharts({!! $vals['chart'] !!});
				</script>
			@endforeach
		@endforeach
	@endforeach
	
@endsection