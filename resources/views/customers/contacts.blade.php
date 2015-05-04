<table class="table table-striped table-condensed table-hover">
	<thead>
		<tr>
			<th>Name</th>
			<th class="hidden-xs">Phone</th>
			<th>Cellphone</th>
			<th class="hidden-xs">Email</th>
			<th class="hidden-xs">Main Contact</th>
		</tr>
	</thead>
	<tbody>
	
	@foreach ($contacts as $contact)

		<tr>
			<td> <a href="{{ route('contacts.show', $contact->id) }}"> {{ $contact->name ? $contact->name : '-' }} </a> </td>
			<td class="hidden-xs"> {{ $contact->phone ? $contact->phone : '-' }} </td>
			<td class="hidden-xs"> {{ $contact->cellphone ? $contact->cellphone : '-' }} </td>
			<td> {{ $contact->email ? $contact->email : '-' }} </td>
			<td>
				@if ($customer->main_contact_id == $contact->id) 
					<i class="fa fa-check-circle-o" style="color: #5EBA0A"></i> 
				@else
					<i class="fa fa-check-circle-o" style="color: #DDD"></i>
				@endif
			</td>
		</tr>

	@endforeach

	</tbody>
</table>	

<div class="ajax_pagination" route="{{ route('customers.contacts.ajax',$contacts[0]->customer_id) }}">
	{!! $contacts->render() !!}
</div>
