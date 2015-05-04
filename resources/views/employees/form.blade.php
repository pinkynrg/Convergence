<div class="form">

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("first_name", "First Name") !!}
		{!! Form::BSText("first_name") !!}

		{!! Form::BSLabel("last_name", "Last Name") !!}
		{!! Form::BSText("last_name") !!}

	{!! Form::BSEndGroup() !!}

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("phone", "Phone") !!}
		{!! Form::BSText("phone") !!}

		{!! Form::BSLabel("email", "Email") !!}
		{!! Form::BSText("email") !!}

	{!! Form::BSEndGroup() !!}

	{!! Form::BSGroup() !!}
		
		{!! Form::BSLabel("department_id", "Department") !!}
		{!! Form::BSSelect("department_id", $departments, null, array("key" => "id", "value" => "name")) !!}

		{!! Form::BSLabel("title_id", "Title") !!}
		{!! Form::BSSelect("title_id", $titles, null, array("key" => "id", "value" => "name")) !!}

	{!! Form::BSEndGroup() !!}

</div>