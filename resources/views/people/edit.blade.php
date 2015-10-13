@extends('layouts.default')
@section('content')
	
	{!! Form::model($person, array('method' => 'PATCH', 'route' => array('people.update',$person->id), 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		{!! Form::BSGroup() !!}
			{!! Form::hidden("company_id", null, array("id" => "company_id")) !!}
			{!! Form::hidden("person_id", null, array("id" => "person_id")) !!}
			{!! Form::BSLabel("first_name", "First Name", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSText("first_name", null, ['bclass' => 'col-xs-3']) !!}
			{!! Form::BSLabel("last_name", "Last Name", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSText("last_name", null, ['bclass' => 'col-xs-3']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSSubmit("Submit", ['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection