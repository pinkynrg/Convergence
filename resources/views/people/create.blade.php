{!! Form::BSGroup() !!}

	{!! Form::hidden("company_id", null, array("id" => "company_id")) !!}
	{!! Form::hidden("person_id", null, array("id" => "person_id")) !!}

	<!-- insert &thinsp; to strick browser autofilling -->
	{!! Form::BSLabel("person_fn", "F&thinsp;irst N&thinsp;ame", ['bclass' => 'col-xs-2']) !!}
	<div class="col-lg-3 col-sm-4">
		<div id="input_group_fn">
			<input name="person_fn" type="text" id="person_fn" class="form-control" data-toggle="popover" data-placement="bottom" title="Let's avoid duplicate people" trigger="hover" data-content="In case you find the name of the person in the drop list use that: a person can be a contact for multiple customers. If you don't find it feel free to insert a new one" autocomplete="off">
			<span class="cancel input-group-addon" style="display:none"><i class="fa fa-unlock-alt"></i></span>
		</div>
	</div>

	<!-- insert &thinsp; to strick browser autofilling -->
	{!! Form::BSLabel("person_ln", "L&thinsp;ast N&thinsp;ame", ['bclass' => 'col-xs-2']) !!}
	<div class="col-lg-3 col-sm-4">
		<div id="input_group_ln">
			<input class="form-control" name="person_ln" type="text" id="person_ln" autocomplete="off">
			<span class="cancel input-group-addon" style="display:none"><i class="fa fa-unlock-alt"></i></span>
		</div>
	</div>

{!! Form::BSEndGroup() !!}