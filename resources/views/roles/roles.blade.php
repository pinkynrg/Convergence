<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $roles->render() !!}
	</div>

	<table class="table table-striped table-hover">
		<thead>
			<tr class="orderable">
				<th column="name" weight="0" type="asc">Display Name</th>
				<th column="name">Name</th>
				<th column="description" class="hidden-xs">Description</th>
				<th column="created_at" class="hidden-xs">Created</th>
				<th column="updated_at" class="hidden-xs">Updated</th>
			</tr>
		</thead>
		<tbody>
			
			@if ($roles->count())
				@foreach ($roles as $role) 	
				<tr>
					<td> <a href="{{route('roles.show', $role->id) }}"> {{  $role->display_name }} </a> </td>
					<td> <a href="{{route('roles.show', $role->id) }}"> {{  $role->name }} </a> </td>
					<td class="hidden-xs"> <a href="{{route('roles.show', $role->id) }}"> {{  $role->description }} </a> </td>
					<td class="hidden-xs"> {{ $role->date("created_at") }} </td>
					<td class="hidden-xs"> {{ $role->date("updated_at") }} </td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="5">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $roles->render() !!}
	</div>

</div>