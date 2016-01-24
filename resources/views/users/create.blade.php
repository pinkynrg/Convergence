@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'users.store', 'class' => "form-horizontal")) !!}

		{!! Form::BSGroup() !!}

			{!! Form::BSLabel("person_id", "Person", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSSelect("person_id", $people, null, array('bclass' => 'col-xs-3', "key" => "id", "value" => "name")) !!}

			{!! Form::BSLabel("username", "Username", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSText("username", null, array('bclass' => 'col-xs-3')) !!}

		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			
			{!! Form::BSLabel("password", "Password", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSPassword("password", array('bclass' => 'col-xs-3')) !!}

			{!! Form::BSLabel("password2", "Repeat Password", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSPassword("password2", array('bclass' => 'col-xs-3')) !!}

		{!! Form::BSEndGroup() !!}

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection