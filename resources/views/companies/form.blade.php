<div class="form">

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("name", "Company Name") !!}
		{!! Form::BSText("name") !!}
		
		{!! Form::BSLabel("country", "Country") !!}
		{!! Form::BSText("country") !!}

	{!! Form::BSEndGroup() !!}

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("state", "State") !!}
		{!! Form::BSText("state") !!}

		{!! Form::BSLabel("city", "City") !!}
		{!! Form::BSText("city") !!}

	{!! Form::BSEndGroup() !!}

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("address", "Address") !!}
		{!! Form::BSText("address") !!}

		{!! Form::BSLabel("zip_code", "Zip Code") !!}
		{!! Form::BSText("zip_code") !!}

	{!! Form::BSEndGroup() !!}

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("group_email", "Group Email") !!}
		{!! Form::BSText("group_email") !!}

		{!! Form::BSLabel("airport", "Airport") !!}
		{!! Form::BSText("airport") !!}

	{!! Form::BSEndGroup() !!}

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("account_manager", "Account Manager") !!}
		
		{!! Form::BSSelect("account_manager_id", $account_managers, isset($company->account_manager[0]) ? $company->account_manager[0]->id : null , array("key" => "id", "value" => "name")) !!}

	{!! Form::BSEndGroup() !!}

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("plant_requirment", "Plant Requirments") !!}
		{!! Form::BSTextArea("plant_requirment") !!}

	{!! Form::BSEndGroup() !!}

</div>