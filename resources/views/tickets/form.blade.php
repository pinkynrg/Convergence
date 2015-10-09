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
			@if (Route::currentRouteName() == "tickets.edit")
				{!! Form::BSSelect("company_id", $companies, null, ['key' => 'id', 'value' => 'name', 'disabled']) !!}
			@else
				{!! Form::BSSelect("company_id", $companies, null, ['key' => 'id', 'value' => 'name', 'class' => 'ajax_trigger']) !!}
			@endif
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('equipment_id','Equipment') !!}
			@if (Route::currentRouteName() == "tickets.edit")
				{!! Form::BSSelect("equipment_id", $equipments, null, ['key' => 'id', 'value' => 'name']) !!}
			@else 
				{!! Form::BSSelect("equipment_id", array(), null, ['key' => 'id', 'value' => 'name']) !!}
			@endif
		{!! Form::BSEndGroup() !!}

	</div>
	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('contact_id','Company Contact') !!}
			@if (Route::currentRouteName() == "tickets.edit")
				{!! Form::BSSelect("contact_id", $contacts, null, ['key' => 'id', 'value' => 'person.name']) !!}
			@else
				{!! Form::BSSelect("contact_id", array(), null, ['key' => 'id', 'value' => 'person.name']) !!}
			@endif
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
			{!! Form::BSLabel('equipment_id','Ticket Title') !!}
			{!! Form::BSText('title',null) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSTextArea('post',null,['id' => 'post']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel('tagit','Tag it!') !!}
			
			@if (isset($tags))
				{!! Form::BSText('tagit',$tags) !!}
			@else 
				{!! Form::BSText('tagit') !!}
			@endif

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
			{!! Form::BSLabel('additional_emails','Additional Emails') !!}
			{!! Form::BSText('additional_emails',null) !!}
		{!! Form::BSEndGroup() !!}


	</div>
</div>
