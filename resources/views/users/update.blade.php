@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'users.store', 'class' => "form-horizontal")) !!}

		{!! Form::BSGroup() !!}
			
			{!! Form::BSLabel("password", "Password", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSPassword("password", array('bclass' => 'col-xs-3')) !!}

			{!! Form::BSLabel("password2", "Repeat Password", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSPassword("password2", array('bclass' => 'col-xs-3')) !!}

		{!! Form::BSEndGroup() !!}

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection