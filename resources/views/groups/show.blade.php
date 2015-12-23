@extends('layouts.default')

@section('content')

	<table class="table table-striped table-hover">
		<tr>
			<th> Group Type </th> <td> {{ $group->group_type->display_name }} </td>
		</tr>
		<tr>
			<th> Name </th> <td> {{ $group->name }} </td>
		</tr>
		<tr>
			<th> Display Name </th> <td> {{ $group->display_name }} </td>
		</tr>
		<tr>
			<th> Description </th> <td> {{ $group->description }} </td>
		</tr>
	</table>

	<h3 class="subtitle"> Group Roles </h2>

	{!! Form::open(array('method' => 'POST', 'route' => array('groups.update_roles',$group->id), 'class' => "form-horizontal")) !!}

		<div class="group_update_roles">
			<select multiple="multiple" name="roles[]" size="10" style="display: none;">
				@foreach ($roles as $role)
		    		<option value="{{ $role->id }}" @if ($role->is_in_group) selected @endif>{{ $role->display_name }} [{{ $role->name }}] </option>
		    	@endforeach
		    </select>
		</div>

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection