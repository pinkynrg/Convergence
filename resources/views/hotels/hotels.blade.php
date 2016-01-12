<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $hotels->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="hotels.name">Name</th>
				<th column="hotels.address" class="hidden-xs">Address</th>
			</tr>
		</thead>
		<tbody>

			@if ($hotels->count())
				@foreach ($hotels as $hotel) 	

				<tr>
					<td> <a href=""> {{ $hotel->name }} </a> </td>
					<td> <a href=""> {{ $hotel->address }} </a> </td>
				</tr>
				@endforeach
			@else 
				<tr><td colspan="7">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $hotels->render() !!}
	</div>
</div>
