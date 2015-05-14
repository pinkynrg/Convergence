<div class="content">
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="name">Name</th>
				<th column="customers.company_name">Customer</th>
				<th column="phone">Phone</th>
				<th column="cellphone">Cellphone</th>
				<th column="email">Email</th>
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

	<div class="ajax_pagination">
		{!! $contacts->render() !!}
	</div>
</div>