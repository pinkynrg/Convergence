<div class="content">

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="hotels.name">Name</th>
				<th column="hotels.address" class="hidden-xs">Address</th>
				<th column="hotels.rating" type="desc">Rating</th>
				<th column="hotels.distance">Distance</th>
				<th column="hotels.walking_time" class="hidden-xs">Walking Time</th>
				<th column="hotels.driving_time" class="hidden-xs">Driving Time</th>
			</tr>
		</thead>
		<tbody>

			@if ($hotels->count())
				@foreach ($hotels as $hotel) 	

				<tr>
					<td> <a href=""> {{ $hotel->name }} </a> </td>
					<td class="hidden-xs"> <a href=""> {{ $hotel->address }} </a> </td>
					<td> <a href=""> {{ $hotel->rating() }} </a> </td>
					<td> <a href=""> {{ $hotel->distance() }} </a> </td>
					<td class="hidden-xs"> <a href=""> {{ $hotel->walking_time() }} </a> </td>
					<td class="hidden-xs"> <a href=""> {{ $hotel->driving_time() }} </a> </td>
				</tr>
				@endforeach
			@else 
				<tr><td colspan="7">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="false">
		{!! $hotels->render() !!}
	</div>
</div>
