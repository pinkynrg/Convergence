<div class="content">

	<div class="ajax_pagination" scrollup="false">
		{!! $users->render() !!}
	</div>

	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="people.last_name" type="asc">Person Name</th>
				<th column="users.username" class="hidden-xs">Username</th>
				<th column="users.password" class="hidden-xs">Hashed Password</th>
				<th column="users.created_at" class="hidden-xs">Created</th>
				<th column="users.updated_at" class="hidden-xs">Updated</th>
			</tr>
		</thead>
		<tbody>
		@if ($users->count())
			@foreach ($users as $user)

				<tr>
					<td> <a href="{{ route('people.show', $user->owner->id) }}"> {{ $user->owner->name() }} </a> </td>
					<td> {{ $user->username }} </td>
					<td> {{ $user->password }} </td>
					<td> {{ $user->date("created_at") }} </td>
					<td> {!! $user->date("updated_at") !!} </td>
				</tr>

			@endforeach
		@else 
			<tr><td colspan="5">@include('includes.no-contents')</td></tr>
		@endif 

		</tbody>
	</table>	

	<div class="ajax_pagination" scrollup="true">
		{!! $users->render() !!}
	</div>
</div>
