<div class="row">
	<div class="col-xs-6">

		{!! Form::hidden("company_id", $company->id, array("id" => "company_id")) !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("name", "Name") !!}
			{!! Form::BSText("name", null) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("serial_number", "Serial Number") !!}
			{!! Form::BSText("serial_number", null) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("notes", "Notes") !!}
			{!! Form::BSText("notes", null) !!}
		{!! Form::BSEndGroup() !!}

	</div>
	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("cc_number", "CC Number") !!}
			{!! Form::BSText("cc_number", null) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("equipment_type_id", "Equipment Type") !!}
			{!! Form::BSSelect("equipment_type_id", $equipment_types, null, ['key' => 'id', 'value' => 'name']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("warranty_expiration", "Warranty Expiration") !!}
			{!! Form::BSText('warranty_expiration', isset($equipment) && $equipment->warranty_expiration ? date("m/d/Y",strtotime($equipment->warranty_expiration)) : '',['class' => 'datepicker', 'data-provider' => 'datepicker']); !!}
		{!! Form::BSEndGroup() !!}

	</div>
</div>