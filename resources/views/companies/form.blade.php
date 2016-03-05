<div class="row">
	<div class="col-xs-6">
		
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("name", "Company Name") !!}
			{!! Form::BSText("name") !!}
		{!! Form::BSEndGroup() !!}
		
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("state", "State") !!}
			{!! Form::BSText("state") !!}
		{!! Form::BSEndGroup() !!}
		
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("address", "Address") !!}
			{!! Form::BSText("address") !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("group_email", "Group Email") !!}
			{!! Form::BSText("group_email") !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("account_manager", "Account Manager") !!}
			{!! Form::BSSelect("account_manager_id", $account_managers, null, ["key" => "id", "value" => ["!person.last_name"," ","!person.first_name"]]) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("escalation_profile_id", "Escalation Profile") !!}
			{!! Form::BSSelect("escalation_profile_id", $escalation_profiles, isset($company->escalation_profile_id) ? 
			$company->escalation_profile_id : null, ["key" => "id", "value" => "!name"]) !!}
		{!! Form::BSEndGroup() !!}

	</div>

	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("country", "Country") !!}
			{!! Form::BSText("country") !!}
		{!! Form::BSEndGroup() !!}
		
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("city", "City") !!}
			{!! Form::BSText("city") !!}
		{!! Form::BSEndGroup() !!}
		
		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("zip_code", "Zip Code") !!}
			{!! Form::BSText("zip_code") !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("support_type_id", "Support Type") !!}
			{!! Form::BSSelect("support_type_id", $support_types, null, ["key" => "id", "value" => "!name"]) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			@if (Route::currentRouteName() == "companies.edit")
				{!! Form::BSLabel("main_contact", "Main Contact") !!}
				{!! Form::BSSelect("main_contact_id", $main_contacts, null, ["key" => "id", "value" => ["!person.last_name"," ","!person.first_name"]]) !!}
			@endif
		{!! Form::BSEndGroup() !!}

	</div>
</div>

