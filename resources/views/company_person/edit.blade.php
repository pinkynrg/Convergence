@extends('layouts.default')
@section('content')

	@include('includes.errors')
	{!! Form::model($contact, array('method' => 'PATCH', 'route' => array('company_person.update',$contact->id), 'class' => "form-horizontal")) !!}

		{!! Form::BSGroup() !!}

			{!! Form::BSLabel("name", "Person Name", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSText("name", $contact->person->name(), ['bclass' => 'col-xs-3','disabled' => 'true']) !!}

		{!! Form::BSEndGroup() !!}

		@include('company_person.form')

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection