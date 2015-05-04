<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>Name</th>
			<th>Customer</th>
			<th>Phone</th>
			<th>Cellphone</th>
			<th>Email</th>
		</tr>
	</thead>
	<tbody>

		@foreach ($contacts as $contact) 	

		<tr>
			<td> <a href="{{route('contacts.show', $contact->id) }}"> {{  $contact->name }} </a> </td>
			<td> {{ $contact->customer->company_name }} </td>
			<td> {{ $contact->phone }} </td>
			<td> {{ $contact->cellphone }} </td>
			<td> {{ $contact->email }} </td>
		</tr>

		@endforeach

	</tbody>
</table>

<div class="ajax_pagination" route="{{ route('contacts.contacts.ajax') }}">
	{!! $contacts->render() !!}
</div>
