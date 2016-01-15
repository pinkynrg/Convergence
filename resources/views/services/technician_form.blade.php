<h4> Technician Info # {{ $tech_num+1 }} </h4>

<hr>

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("technician_id[".$tech_num."]", "Technician Name", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("technician_id[".$tech_num."]", $technicians, null, ['key' => 'id', 'value' => 'person.name', 'bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("tech_division_id[".$tech_num."]", "Division", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("tech_division_id[".$tech_num."]", $divisions, null, ['key' => 'id', 'value' => 'name', 'bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("work_description[".$tech_num."]", "Work Description", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSTextArea("work_description[".$tech_num."]",null,['bclass' => 'col-xs-8']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("tech_has_internal[".$tech_num."]", "Has Internal Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("tech_has_internal[".$tech_num."]", ['No','Yes'], null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("tech_internal_hours[".$tech_num."]", "Internal Dev. Estimated Hours", ['bclass' => 'col-xs-2', 'class' =>  'tech_internal_group['.$tech_num.']']) !!}
	{!! Form::BSText("tech_internal_hours[".$tech_num."]",null, ['bclass' => 'col-xs-3', 'class' =>  'tech_internal_group['.$tech_num.']', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("tech_internal_start[".$tech_num."]", "Internal Dev. Start Date", ['bclass' => 'col-xs-2', 'class' =>  'tech_internal_group['.$tech_num.']']) !!}
	{!! Form::BSDatePicker("tech_internal_start[".$tech_num."]",null, ['class' => 'datepicker tech_internal_group['.$tech_num.']', 'data-provider' => 'datepicker', 'bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

	{!! Form::BSLabel("tech_internal_end[".$tech_num."]", "Internal Dev. End Date", ['bclass' => 'col-xs-2', 'class' =>  'tech_internal_group['.$tech_num.']']) !!}
	{!! Form::BSDatePicker("tech_internal_end[".$tech_num."]",null, ['class' => 'datepicker tech_internal_group['.$tech_num.']', 'data-provider' => 'datepicker', 'bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("tech_has_remote[".$tech_num."]", "Has Remote Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("tech_has_remote[".$tech_num."]", ['No','Yes'], null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("tech_remote_hours[".$tech_num."]", "Remote Dev. Estimated Hours", ['bclass' => 'col-xs-2', 'class' =>  'tech_remote_group['.$tech_num.']']) !!}
	{!! Form::BSText("tech_remote_hours[".$tech_num."]",null, ['bclass' => 'col-xs-3', 'class' =>  'tech_remote_group['.$tech_num.']', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("tech_remote_start[".$tech_num."]", "Remote Dev. Start Date", ['bclass' => 'col-xs-2', 'class' =>  'tech_remote_group['.$tech_num.']']) !!}
	{!! Form::BSDatePicker("tech_remote_start[".$tech_num."]", null, ['class' => 'datepicker tech_remote_group['.$tech_num.']', 'data-provider' => 'datepicker', 'bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

	{!! Form::BSLabel("tech_remote_end[".$tech_num."]", "Remote Dev. End Date", ['bclass' => 'col-xs-2', 'class' =>  'tech_remote_group['.$tech_num.']']) !!}
	{!! Form::BSDatePicker("tech_remote_end[".$tech_num."]",null, ['class' => 'datepicker tech_remote_group['.$tech_num.']', 'data-provider' => 'datepicker', 'bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("tech_has_onsite[".$tech_num."]", "Has Onsite Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("tech_has_onsite[".$tech_num."]", ['No','Yes'], null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("tech_onsite_hours[".$tech_num."]", "Onsite Estimated Hours", ['bclass' => 'col-xs-2', 'class' =>  'tech_onsite_group['.$tech_num.']', 'disabled' => 'disabled']) !!}
	{!! Form::BSText("tech_onsite_hours[".$tech_num."]",null, ['bclass' => 'col-xs-3', 'class' =>  'tech_onsite_group['.$tech_num.']', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("tech_onsite_start[".$tech_num."]", "On Site Start Date", ['bclass' => 'col-xs-2', 'class' =>  'tech_onsite_group['.$tech_num.']']) !!}
	{!! Form::BSDatePicker("tech_onsite_start[".$tech_num."]",null, ['class' => 'datepicker tech_onsite_group['.$tech_num.']', 'data-provider' => 'datepicker', 'bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

	{!! Form::BSLabel("tech_onsite_end[".$tech_num."]", "On Site End Date", ['bclass' => 'col-xs-2', 'class' =>  'tech_onsite_group['.$tech_num.']']) !!}
	{!! Form::BSDatePicker("tech_onsite_end[".$tech_num."]",null, ['class' => 'datepicker tech_onsite_group['.$tech_num.']', 'data-provider' => 'datepicker', 'bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}