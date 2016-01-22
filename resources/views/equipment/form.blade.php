{!! Form::BSGroup() !!}
	
	{!! Form::hidden("company_id", $company->id, array("id" => "company_id")) !!}

	{!! Form::BSLabel("name", "Name", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("name", null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("cc_number", "CC Number", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("cc_number", null, ['bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("serial_number", "Serial Number", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("serial_number", null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("equipment_type_id", "Equipment Type", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("equipment_type_id", $equipment_types, null, ['key' => 'id', 'value' => 'name', 'bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("notes", "Notes", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("notes", null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("warranty_expiration", "Warranty Expiration", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText('warranty_expiration', isset($equipment) && $equipment->warranty_expiration ? date("m/d/Y",strtotime($equipment->warranty_expiration)) : '',['class' => 'datepicker', 'data-provider' => 'datepicker', 'bclass' => 'col-xs-3']); !!}

{!! Form::BSEndGroup() !!}