{!! Form::BSGroup() !!}

	{!! Form::BSLabel("phone", "Phone") !!}
	{!! Form::BSText("phone") !!}

	{!! Form::BSLabel("extension", "Phone Extension") !!}
	{!! Form::BSText("extension") !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("cellphone", "Cell Phone") !!}
	{!! Form::BSText("cellphone") !!}

	{!! Form::BSLabel("email", "Email") !!}
	{!! Form::BSText("email") !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}
	
	{!! Form::BSLabel("department_id", "Department") !!}
	{!! Form::BSSelect("department_id", $departments, null, array("key" => "id", "value" => "name")) !!}

	{!! Form::BSLabel("title_id", "Title") !!}
	{!! Form::BSSelect("title_id", $titles, null, array("key" => "id", "value" => "name")) !!}

{!! Form::BSEndGroup() !!}