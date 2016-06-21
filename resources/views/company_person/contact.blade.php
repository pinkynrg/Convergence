<table class="table table-striped table-condensed table-hover">
	<tr>
		<th> Name </th> <td> <a href="{{ route('people.show', $company_person->person->id) }}"> {{ $company_person->person->name() }} </a> </td>
	</tr>
	<tr>
		<th> Company </th> <td>  <a href="{{ route('companies.show', $company_person->company->id) }}"> {{ $company_person->company->name }} </a> </td>
	</tr>
	<tr>
		<th>Title</th> <td> {{ isset($company_person->title_id) ? $company_person->title->name : '' }} </td>
	</tr>
	<tr>
		<th>Department</th> <td> {{ isset($company_person->department_id) ? $company_person->department->name : '' }} </td>
	</tr>
	<tr>
		<th>Phone</th> <td> {!! $company_person->phone() !!} </td>
	</tr>
	<tr>
		<th>Cell Phone</th> <td> {!! $company_person->cellphone() !!} </td>
	</tr>
	<tr>
		<th>Email</th> <td> {!! $company_person->email !!} </td>
	</tr>
	<!-- <tr> -->
		<!-- <th>Slack Token</th> <td> {!! $company_person->slack_token !!} </td> -->
	<!-- </tr> -->
	<tr>
		<th>Permission Group Type</th> <td> {!! isset($company_person->group_type_id) ? $company_person->group_type->display_name : '' !!} </td>
	</tr>
	<tr>
		<th>Permission Group</th> <td> {!! isset($company_person->group_id) ? $company_person->group->display_name : '' !!} </td>
	</tr>
</table>


@if ($company_person->isE80())
	<div ajax-route="{{ route('company_person.assignee_tickets',$company_person->id) }}">
		<div id="tickets" class="small-header"> 
			<div class="title"><i class="{{ TICKETS_ICON }}"></i> {{ $company_person->person->name() }}'s Active Tickets </div>
		</div>
		<div>

			@if ($company_person->assignee_tickets->count())
				@include('tickets.tickets', array('tickets' => $company_person->assignee_tickets))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>

	<div ajax-route="{{ route('company_person.division_tickets',$company_person->division_ids) }}">
		<div id="tickets" class="small-header"> 
			<div class="title"><i class="{{ TICKETS_ICON }}"></i> Divisions Active Tickets </div>
		</div>
		<div>

			@if ($company_person->assignee_tickets->count())
				@include('tickets.tickets', array('tickets' => $company_person->division_tickets))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>

@else

	<div ajax-route="{{ route('company_person.contact_tickets',$company_person->id) }}">
		<div id="tickets" class="small-header"> 
			<div class="title"><i class="{{ TICKETS_ICON }}"></i> {{ $company_person->person->name()}}'s Tickets </div>
		</div>
		<div>

			@if ($company_person->contact_tickets->count())
				@include('tickets.tickets', array('tickets' => $company_person->contact_tickets))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>
	
	<div ajax-route="{{ route('company_person.company_tickets',$company_person->company->id) }}">
		<div id="tickets" class="small-header"> 
			<div class="title"><i class="{{ TICKETS_ICON }}"></i> {{ $company_person->company->name }}'s Tickets </div>
		</div>
		<div>

			@if ($company_person->company_tickets->count())
				@include('tickets.tickets', array('tickets' => $company_person->company_tickets))
			@else
				@include('includes.no-contents')
			@endif

		</div>
	</div>

@endif