<h4> Technician Info # {{ $tech_num+1 }} </h4>

<hr>

<div class="row">
	<div class="col-xs-6">
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("technician_id[".$tech_num."]", "Technician Name") !!}
			{!! Form::BSSelect("technician_id[".$tech_num."]", $technicians, null, ['key' => 'id', 'value' => ['!person.last_name',' ','!person.first_name']]) !!}
		{!! Form::BSEndGroup() !!}
	</div>
	<div class="col-xs-6">
		{!! Form::BSGroup() !!}		
			{!! Form::BSLabel("tech_division_id[".$tech_num."]", "Division") !!}
			{!! Form::BSSelect("tech_division_id[".$tech_num."]", $divisions, null, ['key' => 'id', 'value' => '!name']) !!}
		{!! Form::BSEndGroup() !!}
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("work_description[".$tech_num."]", "Work Description") !!}
			{!! Form::BSTextArea("work_description[".$tech_num."]") !!}
		{!! Form::BSEndGroup() !!}
	</div>
</div>

<div class="row">
	<div class="col-xs-6">
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_has_internal[".$tech_num."]", "Has Internal Job #") !!}
			{!! Form::BSSelect("tech_has_internal[".$tech_num."]", ['No','Yes']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_internal_start[".$tech_num."]", "Internal Dev. Start Date", ['tech_internal_group['.$tech_num.']']) !!}
			{!! Form::BSDatePicker("tech_internal_start[".$tech_num."]",null, ['class' => 'datepicker tech_internal_group['.$tech_num.']', 'data-provider' => 'datepicker','disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_has_remote[".$tech_num."]", "Has Remote Job #") !!}
			{!! Form::BSSelect("tech_has_remote[".$tech_num."]", ['No','Yes']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_remote_start[".$tech_num."]", "Remote Dev. Start Date", ['class' =>  'tech_remote_group['.$tech_num.']']) !!}
			{!! Form::BSDatePicker("tech_remote_start[".$tech_num."]", null, ['class' => 'datepicker tech_remote_group['.$tech_num.']', 'data-provider' => 'datepicker', 'disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_remote_end[".$tech_num."]", "Remote Dev. End Date", ['class' =>  'tech_remote_group['.$tech_num.']']) !!}
			{!! Form::BSDatePicker("tech_remote_end[".$tech_num."]",null, ['class' => 'datepicker tech_remote_group['.$tech_num.']', 'data-provider' => 'datepicker','disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_has_onsite[".$tech_num."]", "Has Onsite Job #") !!}
			{!! Form::BSSelect("tech_has_onsite[".$tech_num."]", ['No','Yes']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_onsite_start[".$tech_num."]", "On Site Start Date", ['class' =>  'tech_onsite_group['.$tech_num.']']) !!}
			{!! Form::BSDatePicker("tech_onsite_start[".$tech_num."]",null, ['class' => 'datepicker tech_onsite_group['.$tech_num.']', 'data-provider' => 'datepicker', 'disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

	</div>

	<div class="col-xs-6">
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_internal_hours[".$tech_num."]", "Internal Dev. Hours", ['class' =>  'tech_internal_group['.$tech_num.']']) !!}
			{!! Form::BSText("tech_internal_hours[".$tech_num."]",null, ['class' =>  'tech_internal_group['.$tech_num.']', 'disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_internal_end[".$tech_num."]", "Internal Dev. End Date", ['class' =>  'tech_internal_group['.$tech_num.']']) !!}
			{!! Form::BSDatePicker("tech_internal_end[".$tech_num."]",null, ['class' => 'datepicker tech_internal_group['.$tech_num.']', 'data-provider' => 'datepicker', 'disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_remote_hours[".$tech_num."]", "Remote Dev. Hours", ['class' =>  'tech_remote_group['.$tech_num.']']) !!}
			{!! Form::BSText("tech_remote_hours[".$tech_num."]",null, ['class' =>  'tech_remote_group['.$tech_num.']', 'disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
				{!! Form::BSLabel("tech_remote_end[".$tech_num."]", "Remote Dev. End Date", ['class' =>  'tech_remote_group['.$tech_num.']']) !!}
				{!! Form::BSDatePicker("tech_remote_end[".$tech_num."]",null, ['class' => 'datepicker tech_remote_group['.$tech_num.']', 'data-provider' => 'datepicker', 'disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_onsite_hours[".$tech_num."]", "Onsite Hours", ['class' =>  'tech_onsite_group['.$tech_num.']', 'disabled' => 'disabled']) !!}
			{!! Form::BSText("tech_onsite_hours[".$tech_num."]",null, ['class' =>  'tech_onsite_group['.$tech_num.']', 'disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("tech_onsite_end[".$tech_num."]", "On Site End Date", ['class' =>  'tech_onsite_group['.$tech_num.']']) !!}
			{!! Form::BSDatePicker("tech_onsite_end[".$tech_num."]",null, ['class' => 'datepicker tech_onsite_group['.$tech_num.']', 'data-provider' => 'datepicker', 'disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}
	</div>
</div>

