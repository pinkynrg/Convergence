@extends('layouts.default')

@section('content')

	<div class="alert alert-info" role="alert"> 
		<p> 
			<i class="fa fa-info-circle"></i>
			The following time values are considered to be only active working time.
			(all the time spent on tickets when status was neither <b>waiting customer feedback</b>, <b>solved</b>, or <b>closed</b>).
		</p>
		<p>
			Only tickets <b>opened</b> and then <b>solved/closed</b> in the same time span are considered for the following data.
		</p>
		<p> The following time values are expresses in <b>hours</b> </p>
	</div>

	@foreach ($data as $key => $resolution_times)

		<h3>Last {{ $key }} </h3>

		<table class="table table-striped table-condensed table-hover">
			<tr>
				<th class="nowrap"> Division </th>
				<th> Sum <span class="prev">(Previous)</span> </th>
				<th> Average <span class="prev">(Previous)</span> </th>
				<th> Minimum <span class="prev">(Previous)</span> </th>
				<th> Maximum <span class="prev">(Previous)</span> </th>
			</tr>

			@foreach ($resolution_times as $division => $values)
				
				<tr>
					<td class="nowrap"> {{ $division }} </td>

					<td>
						@if ($values[0]->sum < $values[1]->sum) <i class="{{ DECREASED_VALUE_ICON }}"></i> @else <i class="{{ INCREASED_VALUE_ICON }}"></i> @endif
						{{ number_format($values[0]->sum/3600,2) }} 
						<span class="prev">({{ number_format($values[1]->sum/3600,2) }})</span>
					</td>
					
					<td>
						@if ($values[0]->sum < $values[1]->average) <i class="{{ DECREASED_VALUE_ICON }}"></i> @else <i class="{{ INCREASED_VALUE_ICON }}"></i> @endif
						{{ number_format($values[0]->average/3600,2) }} 
						<span class="prev">({{ number_format($values[1]->average/3600,2) }})</span>
					</td>
					
					<td>
						@if ($values[0]->min < $values[1]->min) <i class="{{ DECREASED_VALUE_ICON }}"></i> @else <i class="{{ INCREASED_VALUE_ICON }}"></i> @endif
						{{ number_format($values[0]->min/3600,2) }} 
						<span class="prev">({{ number_format($values[1]->min/3600,2) }})</span>
					</td>
					
					<td>
						@if ($values[0]->max < $values[1]->max) <i class="{{ DECREASED_VALUE_ICON }}"></i> @else <i class="{{ INCREASED_VALUE_ICON }}"></i> @endif
						{{ number_format($values[0]->max/3600,2) }} 
						<span class="prev">({{ number_format($values[1]->max/3600,2) }})</span>
					</td>
					
				</tr>
			@endforeach
		</table>
	@endforeach

@endsection