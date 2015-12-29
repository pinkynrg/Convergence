{!! Form::BSGroup() !!}

	{!! Form::hidden("company_id", null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("phone", "Phone", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("phone", null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("extension", "Phone Extension", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("extension", null, ['bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("cellphone", "Cell Phone", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("cellphone", null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("email", "Email", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("email", null, ['bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}
	
	{!! Form::BSLabel("department_id", "Department", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("department_id", $departments, null, array('bclass' => 'col-xs-3', "key" => "id", "value" => "name")) !!}

	{!! Form::BSLabel("title_id", "Title", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("title_id", $titles, null, array('bclass' => 'col-xs-3', "key" => "id", "value" => "name")) !!}

{!! Form::BSEndGroup() !!}