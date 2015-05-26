<div class="content">
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr class="orderable">
				<th column="name">Name</th>
				<th column="phone" class="hidden-xs">Phone</th>
				<th column="cellphone" >Cellphone</th>
				<th column="email" class="hidden-xs">Email</th>
				<th column="customers.main_contact_id" class="hidden-xs">Main Contact</th>
			</tr>
		</thead>
		<tbody>
		
		@foreach ($contacts as $contact)

			<tr>
				<td> <a href="{{ route('contacts.show', $contact->id) }}"> {{ $contact->name }} </a> </td>
				<td class="hidden-xs"> {{ $contact->phone() }} </td>
				<td class="hidden-xs"> {{ $contact->cellphone() }} </td>
				<td> {{ $contact->email }} </td>
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

	<div class="ajax_pagination" scrollup="false">
		{!! $contacts->render() !!}
	</div>
</div>
