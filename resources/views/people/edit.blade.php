@extends('layouts.default')
@section('content')
	
	{!! Form::model($person, array('method' => 'PATCH', 'route' => array('people.update',$person->id), 'files' => 'true')) !!}

		<div id="profile_picture_form" class="row">
			<div class="col-xs-12">
				<div>
					{!! Form::BSFile("profile_picture","Upload Picture", $person->profile_picture()->path()) !!}
				</div>
			</div>
		</div>

		<div class="row">
			{!! Form::hidden("company_id", null, array("id" => "company_id")) !!}
			{!! Form::hidden("person_id", null, array("id" => "person_id")) !!}

			<div class="col-xs-6">
				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("first_name", "First Name") !!}
					{!! Form::BSText("first_name") !!}
				{!! Form::BSEndGroup() !!}
			</div>
			<div class="col-xs-6">
				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("last_name", "Last Name") !!}
					{!! Form::BSText("last_name") !!}
				{!! Form::BSEndGroup() !!}
			</div>
		</div>

		{!! Form::BSSubmit("Submit", ['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection