@if (Route::currentRouteName() == "company_person.edit")
	<div class="row">
		<div class="col-xs-6">
			{!! Form::BSGroup() !!}
				{!! Form::BSLabel("name", "Name") !!}
				{!! Form::BSText("name", $contact->person->name(), ['disabled' => 'true']) !!}
			{!! Form::BSEndGroup() !!}
		</div>
	</div>
@endif

<div class="row">	
	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("department_id", "Department") !!}
			{!! Form::BSSelect("department_id", $departments, null, array("key" => "id", "value" => "!name")) !!}	
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("phone", "Phone") !!}
			{!! Form::BSText("phone") !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("cellphone", "Cell Phone") !!}
			{!! Form::BSText("cellphone") !!}
		{!! Form::BSEndGroup() !!}

		@if (Route::currentRouteName() == "companies.create")
			{!! Form::BSGroup() !!}
				{!! Form::BSLabel("group_type_id", "Permission Group Type") !!}
				{!! Form::BSSelect("group_type_id", $group_types, null, array("key" => "id", "value" => "!display_name")) !!}
			{!! Form::BSEndGroup() !!}
		@endif

		@if (Route::currentRouteName() == "company_person.edit")
			{!! Form::BSGroup() !!}
				{!! Form::BSLabel("group_id", "Permission Group") !!}
				{!! Form::BSSelect("group_id", $groups, null, array("key" => "id", "value" => "!display_name")) !!}
			{!! Form::BSEndGroup() !!}
		@endif

		@if (Route::currentRouteName() != "companies.create")
			{!! Form::BSGroup() !!}
				{!! Form::BSLabel("company_id", "Company") !!}
				@if (is_null($contact['company_id']))
					{!! Form::BSSelect("company_id", $companies,  null, ["key" => "id", "value" => "!name"]) !!}
				@else
					<!-- we need an hidden field becuase the select is disabled so the data won't be submitted -->
					{!! Form::BSHidden("company_id", $contact['company_id']) !!}
					{!! Form::BSSelect("company_id", $companies,  $contact['company_id'], ["key" => "id", "value" => "!name", 'disabled' => 'true']) !!}
				@endif
			{!! Form::BSEndGroup() !!}
		@endif

	</div>

	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("title_id", "Title") !!}
			{!! Form::BSSelect("title_id", $titles, null, array("key" => "id", "value" => "!name")) !!}	
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("extension", "Phone Extension") !!}
			{!! Form::BSText("extension") !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("email", "Email") !!}
			{!! Form::BSText("email") !!}
		{!! Form::BSEndGroup() !!}

	</div>
</div>