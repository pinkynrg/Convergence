@extends('layouts.default')
@section('content')

	<table class="table table-striped table-condensed table-hover">
		<tr>
			<th>Contact</th><td> {{ $contact->name }} </td>
		</tr>
		<tr>
			<th>Phone</th><td> {{ $contact->phone }} </td>
		</tr>
		<tr>
			<th>Cellphone</th><td>  {{ $contact->cellphone }}  </td>
		</tr>
		<tr>
			<th>Email</th><td>  {{ $contact->email }}  </td>
		</tr>
	</table>

@endsection
