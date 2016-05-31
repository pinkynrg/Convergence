<div class="row">
	<div class="col-xs-6">

		@if (Route::currentRouteName() == "tickets.edit")
			{!! Form::hidden("creator_id", null, array('id' => 'creator_id') ) !!}
		@else
			{!! Form::hidden("creator_id", Auth::user()->active_contact_id, array('id' => 'creator_id') ) !!}
		@endif

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('company_id','Company') !!}
			{!! Form::BSSelect("company_id", $companies, null, ['key' => 'id', 'value' => '!name', 'class' => 'ajax_trigger']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('equipment_id','Equipment') !!}
			{!! Form::BSHidden('equipment_id',null,['id' => 'equipment_id']) !!}
			{!! Form::BSSelect("fake_equipment_id", array(), null, ['id' => 'fake_equipment_id']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('level_id','Level') !!}
			{!! Form::BSSelect("level_id", $levels, null, ['key' => 'id', 'value' => '!name']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('title','Ticket Title') !!}
			{!! Form::BSText('title') !!}
		{!! Form::BSEndGroup() !!}


	</div>
	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('contact_id','Company Contact') !!}
			{!! Form::BSHidden('contact_id',null,['id' => 'contact_id']) !!}
			{!! Form::BSSelect("fake_contact_id", array(), null, ['id' => 'fake_contact_id']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('assignee_id','Assignee') !!}
			{!! Form::BSSelect("assignee_id", $assignees, null, ['key' => 'id', 'value' => ['!person.last_name',' ','!person.first_name']]) !!}
		{!! Form::BSEndGroup() !!}

		@if (Route::currentRouteName() == "tickets.create" || $ticket->status_id == TICKET_DRAFT_STATUS_ID || $ticket->status_id == TICKET_REQUESTING_STATUS_ID)

			{!! Form::BSGroup() !!}
				{!! Form::BSLabel('priority_id','Priority') !!}
				{!! Form::BSSelect("priority_id", $priorities, null, ['key' => 'id', 'value' => '!name']) !!}
			{!! Form::BSEndGroup() !!}

		@else

			{!! Form::hidden("priority_id", $ticket->priority_id) !!}

		@endif

	</div>
</div>

<div class="row">
	<div class="col-xs-12 tabindexed">

		{!! Form::BSGroup() !!}
			{!! Form::BSTextArea('post',null,['id' => 'post']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::dropZone() !!}		

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('tagit','Tag it!') !!}
			@if (isset($tags))
				{!! Form::BSText('tagit',$tags) !!}
			@else 
				{!! Form::BSText('tagit') !!}
			@endif
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}

			{!! Form::BSLabel('linked_tickets_id','Linked Tickets') !!}
			{!! Form::BSHidden('linked_tickets_id',null,['id' => 'linked_tickets_id']) !!}

			{!! 
				Form::BSMultiSelect("fake_linked_tickets_id", [], 
				["title" => "link_tickets", "search" => "true"]) 
			!!}

		{!! Form::BSEndGroup() !!}

	</div>
</div>

<div class="row">
	<div class="col-xs-6">
		
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('division_id','Division') !!}
			{!! Form::BSSelect("division_id", $divisions, null, ['key' => 'id', 'value' => '!name']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('job_type_id','Job Type') !!}
			{!! Form::BSSelect("job_type_id", $job_types, null, ['key' => 'id', 'value' => '!name']) !!}
		{!! Form::BSEndGroup() !!}

	</div>

	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('emails','Additional Emails') !!}
			@if (isset($emails))
				{!! Form::BSText('emails',$emails, ['data-role' => 'multiemail']) !!}
			@else
				{!! Form::BSText('emails',null, ['data-role' => 'multiemail']) !!}
			@endif
		{!! Form::BSEndGroup() !!}

	</div>

</div>