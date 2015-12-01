{!! Form::BSGroup() !!}

	{!! Form::BSLabel("name", "Name", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("name", null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("display_name", "Display Name", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("display_name", null, ['bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("description", "Description", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("description", null, ['bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}