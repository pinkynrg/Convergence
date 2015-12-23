<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $permissions->render() !!}
	</div>

	<table class="table table-striped table-hover">
		<thead>
			<tr class="orderable">
				<th column="name">Display Name</th>
				<th column="name">Name</th>
				<th column="name">Description </th>
			</tr>
		</thead>
		<tbody>
			
			@if ($permissions->count())
				@foreach ($permissions as $permission) 	
				<tr>
					<td> <a href="{{route('permissions.show', $permission->id) }}"> {{  $permission->display_name }} </a> </td>
					<td> <a href="{{route('permissions.show', $permission->id) }}"> {{  $permission->name }} </a> </td>
					<td> <a href="{{route('permissions.show', $permission->id) }}"> {{  $permission->description }} </a> </td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="3">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $permissions->render() !!}
	</div>

</div>