{!! Form::BSGroup() !!}

	{!! Form::hidden("company_id", null, array("id" => "company_id")) !!}
	{!! Form::hidden("person_id", null, array("id" => "person_id")) !!}

	<!-- insert &thinsp; to strick browser autofilling -->
	{!! Form::BSLabel("person_fn", "F&thinsp;irst N&thinsp;ame") !!}
	{!! Form::BSText("person_fn", null, array("data-toggle" => "tooltip", "data-placement" => "bottom", "title" => "In case you find the name of the person in the drop list use that: a person can be a contact for multiple customers. If you don't find it feel free to insert a new one") ) !!}
	<span class="cancel"><i class="fa fa-times"></i></span>

	<!-- insert &thinsp; to strick browser autofilling -->
	{!! Form::BSLabel("person_ln", "L&thinsp;ast N&thinsp;ame") !!}
	{!! Form::BSText("person_ln") !!}
	<span class="cancel"><i class="fa fa-times"></i></span>

{!! Form::BSEndGroup() !!}