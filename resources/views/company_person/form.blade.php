<div class="row">	
	<div class="col-xs-6">
		
		@if (Route::currentRouteName() == "company_person.edit")

			{!! Form::BSGroup() !!}
				{!! Form::BSLabel("first_name", "First Name") !!}
				{!! Form::BSText("first_name", $contact->person->first_name, ['disabled' => 'true']) !!}
			{!! Form::BSEndGroup() !!}

		@endif

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("department_id", "Department") !!}
			{!! Form::BSMultiSelect("department_id", $departments, ["title" => "select department", "value" => "id", "data-size" => "10", "label" => "!name", "multiple" => "false", "selected" => [$contact->department_id]]) !!}	
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("phone", "Phone") !!}
			{!! Form::BSHidden("phone") !!}
			{!! Form::BSText("fake_phone",null,['id' => "fake_phone"]) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("cellphone", "Cell Phone") !!}
			{!! Form::BSHidden("cellphone") !!}
			{!! Form::BSText("fake_cellphone",null,['id' => "fake_cellphone"]) !!}
		{!! Form::BSEndGroup() !!}

		@if (Route::currentRouteName() == "companies.create")
			{!! Form::BSGroup() !!}
				{!! Form::BSLabel("group_type_id", "Permission Group Type") !!}
				{!! Form::BSSelect("group_type_id", $group_types, null, array("key" => "id", "value" => "!display_name")) !!}
			{!! Form::BSEndGroup() !!}
		@endif

		@if (Route::currentRouteName() == "company_person.edit")
			{!! Form::BSGroup() !!}
			@if (Auth::user()->can('update-group-contact'))
					{!! Form::BSLabel("group_id", "Permission Group") !!}
					{!! Form::BSSelect("group_id", $groups, null, array("key" => "id", "value" => "!display_name")) !!}
			@else
				<!-- we need an hidden field becuase the select is disabled so the data won't be submitted -->
				{!! Form::BSLabel("group_id", "Permission Group") !!}
				{!! Form::BSHidden("group_id", $contact['group_id']) !!}
				{!! Form::BSSelect("group_id", $groups, $contact['group_id'], ["key" => "id", "value" => "!display_name", 'disabled' => 'true']) !!}
			@endif
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

		@if (Route::currentRouteName() == "company_person.edit")

			{!! Form::BSGroup() !!}
				{!! Form::BSLabel("last_name", "Last Name") !!}
				{!! Form::BSText("last_name", $contact->person->last_name, ['disabled' => 'true']) !!}
			{!! Form::BSEndGroup() !!}

		@endif

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("title_id", "Title") !!}
			{!! Form::BSMultiSelect("title_id", $titles, ["title" => "select title", "value" => "id", "label" => "!name",  "data-size" => "10", "search" => "true", "multiple" => "false", "selected" => [$contact->title_id]]) !!}	
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("extension", "Phone Extension") !!}
			{!! Form::BSText("extension") !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("email", "Email") !!}
			{!! Form::BSText("email") !!}
		{!! Form::BSEndGroup() !!}

		@if (Route::currentRouteName() == "company_person.edit" && $contact->isE80())

			{!! Form::BSGroup() !!}
				{!! Form::BSLabel("divisions", "Divisions") !!}
				{!! Form::BSMultiSelect("division_ids[]", $divisions, 
					["title" => "select divisions", "selected_text" => "Division Active", "data-size" => "10", "value" => "id", "label" => "!name", "selected" => $contact->division_ids()]) !!}
			{!! Form::BSEndGroup() !!}

		@endif
	</div>
</div>