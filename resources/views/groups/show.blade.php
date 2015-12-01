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

@endsection