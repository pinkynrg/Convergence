{!! Form::BSGroup() !!}

	{!! Form::BSLabel("group_type_id", "Group Type", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("group_type_id", $group_types, null, ['bclass' => 'col-xs-3', "key" => "id", "value" => "display_name"]) !!}

	{!! Form::BSLabel("name", "Name", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("name", null, ['bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}
	
	{!! Form::BSLabel("display_name", "Display Name", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("display_name", null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("description", "Description", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("description", null, ['bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}