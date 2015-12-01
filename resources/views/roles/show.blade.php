@extends('layouts.default')

@section('content')

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

@endsection