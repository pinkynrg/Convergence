@extends('layouts.default')

@section('content')

	<table class="table table-striped table-hover">
		<tr>
			<th> Name </th> <td> {{ $equipment->name }} </td>
		</tr>
		<tr>
			<th> CC Number </th> <td> {{ $equipment->cc_number }} </td>
		</tr>
		<tr>
			<th> Serial Number </th> <td> {{ $equipment->serial_number }} </td>
		</tr>
		<tr>
			<th> Equipment Type </th> <td> {{ $equipment->equipment_type->name }} </td>
		</tr>
		<tr>
			<th> Company </th> <td> {{ $equipment->company->name }} </td>
		</tr>
		<tr>
			<th> Notes </th> <td> {{ $equipment->notes }} </td>
		</tr>
		<tr>
			<th> Warranty Expiration </th> <td> {{ isset($equipment->warranty_expiration) ? date("m/d/Y",strtotime($equipment->warranty_expiration)) : '-' }} </td>
		</tr>
	</table>

@endsection