<div class="row">
	<div class="col-xs-6">

		@if (Route::currentRouteName() == "tickets.edit")
			{!! Form::hidden("creator_id", null, array('id' => 'creator_id') ) !!}
		@else
			{!! Form::hidden("creator_id", Auth::user()->active_contact_id, array('id' => 'creator_id') ) !!}
		@endif

		{!! Form::hidden("status_id", 1, array('id' => 'status_id') ) !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('company_id','Company') !!}
			{!! Form::BSSelect("company_id", $companies, null, ['key' => 'id', 'value' => 'name', 'class' => 'ajax_trigger']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('equipment_id','Equipment') !!}
			{!! Form::BSHidden('equipment_id',null,['id' => 'equipment_id']) !!}
			{!! Form::BSSelect("fake_equipment_id", array(), null, ['key' => 'id', 'value' => 'name', 'id' => 'fake_equipment_id']) !!}
		{!! Form::BSEndGroup() !!}

	</div>
	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('contact_id','Company Contact') !!}
			{!! Form::BSHidden('contact_id',null,['id' => 'contact_id']) !!}
			{!! Form::BSSelect("fake_contact_id", array(), null, ['key' => 'id', 'value' => 'person.name', 'id' => 'fake_contact_id']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('assignee_id','Assignee') !!}
			{!! Form::BSSelect("assignee_id", $assignees, null, ['key' => 'id', 'value' => 'person.name']) !!}
		{!! Form::BSEndGroup() !!}

	</div>
</div>

<div class="row">
	<div class="col-xs-12">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('title','Ticket Title') !!}
			{!! Form::BSText('title',null) !!}
		{!! Form::BSEndGroup() !!}

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
				Form::BSMultiSelect("link_tickets", [], 
				["id" => "fake_linked_tickets_id", "search" => "true"]) 
			!!}

		{!! Form::BSEndGroup() !!}

	</div>
</div>

<div class="row">
	<div class="col-xs-6">
		
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('division_id','Division') !!}
			{!! Form::BSSelect("division_id", $divisions, null, ['key' => 'id', 'value' => 'name']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('job_type_id','Job Type') !!}
			{!! Form::BSSelect("job_type_id", $job_types, null, ['key' => 'id', 'value' => 'name']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('priority_id','Priority') !!}
			{!! Form::BSSelect("priority_id", $priorities, null, ['key' => 'id', 'value' => 'name']) !!}
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
