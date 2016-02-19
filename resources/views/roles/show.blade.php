@extends('layouts.default')

@section('content')

	<h3 class="subtitle"> Role Details </h2>

	<table class="table table-striped table-hover">
		<tr>
			<th> Name </th> <td> {{ $role->name }} </td>
		</tr>
		<tr>
			<th> Display Name </th> <td> {{ $role->display_name }} </td>
		</tr>
		<tr>
			<th> Description </th> <td> {{ $role->description }} </td>
		</tr>
	</table>

	<h3 class="subtitle"> Role Permissions </h2>

	{!! Form::open(array('method' => 'POST', 'route' => array('roles.update_permissions',$role->id))) !!}

		<div class="role_update_permissions">
			<select multiple="multiple" name="permissions[]" size="10" style="display: none;">
				@foreach ($permissions as $permission)
		    		<option value="{{ $permission->id }}" @if ($permission->is_in_role) selected @endif>{{ $permission->display_name }} [{{ $permission->name }}] </option>
		    	@endforeach
		    </select>
		</div>

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection