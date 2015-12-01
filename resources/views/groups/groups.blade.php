<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $groups->render() !!}
	</div>

	<table class="table table-striped table-hover">
		<thead>
			<tr class="orderable">
				<th column="name">Display Name</th>
				<th column="name">Name</th>
				<th column="name">Group Type</th>
				<th column="name">Description </th>
			</tr>
		</thead>
		<tbody>
			
			@if ($groups->count())
				@foreach ($groups as $group) 	
				<tr>
					<td> <a href="{{route('groups.show', $group->id) }}"> {{  $group->display_name }} </a> </td>
					<td> <a href="{{route('groups.show', $group->id) }}"> {{  $group->name }} </a> </td>
					<td> <a href="{{route('groups.show', $group->id) }}"> {{  $group->group_type->display_name }} </a> </td>
					<td> <a href="{{route('groups.show', $group->id) }}"> {{  $group->description }} </a> </td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="4">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $groups->render() !!}
	</div>

</div>