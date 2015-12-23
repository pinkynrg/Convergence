@extends('layouts.default')

@section('content')

	<table class="table table-striped table-hover">
		<tr>
			<th> Name </th> <td> {{ $permission->name }} </td>
		</tr>
		<tr>
			<th> Display Name </th> <td> {{ $permission->display_name }} </td>
		</tr>
		<tr>
			<th> Description </th> <td> {{ $permission->description }} </td>
		</tr>
	</table>

@endsection