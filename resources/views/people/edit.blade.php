@extends('layouts.default')
@section('content')
	
	{!! Form::model($person, array('method' => 'PATCH', 'route' => array('people.update',$person->id))) !!}

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

	{!! Form::close() !!}

@endsection