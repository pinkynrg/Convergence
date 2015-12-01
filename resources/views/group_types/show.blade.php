@extends('layouts.default')

@section('content')

	<table class="table table-striped table-hover">
		<tr>
			<th> Name </th> <td> {{ $group_type->name }} </td>
		</tr>
		<tr>
			<th> Display Name </th> <td> {{ $group_type->display_name }} </td>
		</tr>
		<tr>
			<th> Description </th> <td> {{ $group_type->description }} </td>
		</tr>
	</table>

@endsection