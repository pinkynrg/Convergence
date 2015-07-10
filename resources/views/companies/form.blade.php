{!! Form::BSGroup() !!}
	{!! Form::BSLabel("name", "Company Name", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("name", null, ['bclass' => 'col-xs-3']) !!}
	{!! Form::BSLabel("country", "Country", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("country", null, ['bclass' => 'col-xs-3']) !!}
{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}
	{!! Form::BSLabel("state", "State", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("state", null, ['bclass' => 'col-xs-3']) !!}
	{!! Form::BSLabel("city", "City", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("city", null, ['bclass' => 'col-xs-3']) !!}
{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}
	{!! Form::BSLabel("address", "Address", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("address", null, ['bclass' => 'col-xs-3']) !!}
	{!! Form::BSLabel("zip_code", "Zip Code", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("zip_code", null, ['bclass' => 'col-xs-3']) !!}
{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}
	{!! Form::BSLabel("group_email", "Group Email", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("group_email", null, ['bclass' => 'col-xs-3']) !!}
	{!! Form::BSLabel("support_type_id", "Support Type", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("support_type_id", $support_types, isset($company) && count($company->support_type) ? $company->support_type->id : null , ['bclass' => 'col-xs-3', "key" => "id", "value" => "name"]) !!}
{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}
	{!! Form::BSLabel("account_manager", "Account Manager", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("account_manager_id", $account_managers, isset($company) && count($company->account_manager) ? $company->account_manager->company_person->id : null , ['bclass' => 'col-xs-3', "key" => "id", "value" => "person.name"]) !!}
	@if (Route::currentRouteName() == "companies.edit")
		{!! Form::BSLabel("main_contact", "Main Contact", ['bclass' => 'col-xs-2']) !!}
		{!! Form::BSSelect("main_contact_id", $main_contacts, isset($company) && count($company->main_contact) ? $company->main_contact->company_person->id : null , ['bclass' => 'col-xs-3', "key" => "id", "value" => "person.name"]) !!}
	@endif
{!! Form::BSEndGroup() !!}