<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $roles->render() !!}
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
			
			@if ($roles->count())
				@foreach ($roles as $role) 	
				<tr>
					<td> <a href="{{route('roles.show', $role->id) }}"> {{  $role->display_name }} </a> </td>
					<td> <a href="{{route('roles.show', $role->id) }}"> {{  $role->name }} </a> </td>
					<td> <a href="{{route('roles.show', $role->id) }}"> {{  $role->description }} </a> </td>
				</tr>

				@endforeach
			@else 
				<tr><td colspan="3">@include('includes.no-contents')</td></tr>
			@endif 

		</tbody>
	</table>

	<div class="ajax_pagination" scrollup="true">
		{!! $roles->render() !!}
	</div>

</div>