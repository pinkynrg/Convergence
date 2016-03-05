<div class="row">
	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("group_type_id", "Group Type") !!}
			{!! Form::BSSelect("group_type_id", $group_types, null, ['key' => 'id', 'value' => '!display_name']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("display_name", "Display Name") !!}
			{!! Form::BSText("display_name") !!}
		{!! Form::BSEndGroup() !!}

	</div>
	<div class="col-xs-6">

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("name", "Name") !!}
			{!! Form::BSText("name") !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("description", "Description") !!}
			{!! Form::BSText("description") !!}
		{!! Form::BSEndGroup() !!}
		
	</div>
</div>