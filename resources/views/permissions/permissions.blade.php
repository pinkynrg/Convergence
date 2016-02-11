<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $permissions->render() !!}
	</div>

	<table class="table table-striped table-hover">
		<thead>
			<tr class="orderable">
				<th column="permissions.display_name">Display Name</th>
				<th column="permissions.name">Name</th>
				<th column="permissions.description" class="hidden-xs">Description</th>
				<th column="permissions.created_at" class="hidden-xs">Created</th>
				<th column="permissions.updated_at" class="hidden-xs">Updated</th>
			</tr>
		</thead>
		<tbody>
			
			@if ($permissions->count())
				@foreach ($permissions as $permission) 	
				<tr>
					<td> <a href="{{route('permissions.show', $permission->id) }}"> {{  $permission->display_name }} </a> </td>
					<td> <a href="{{route('permissions.show', $permission->id) }}"> {{  $permission->name }} </a> </td>
					<td class="hidden-xs"> <a href="{{route('permissions.show', $permission->id) }}"> {{  $permission->description }} </a> </td>
					<td class="hidden-xs"> {{ $permission->date("created_at") }} </td>
					<td class="hidden-xs"> {{ $permission->date("updated_at") }} </td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="5">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $permissions->render() !!}
	</div>

</div>