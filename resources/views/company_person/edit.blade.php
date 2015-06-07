@extends('layouts.default')
@section('content')

	@include('includes.errors')
	{!! Form::model($contact, array('method' => 'PATCH', 'route' => array('company_person.update',$contact->id), 'class' => "form-horizontal")) !!}

		{!! Form::BSGroup() !!}

			{!! Form::BSLabel("name", "Person Name") !!}
			{!! Form::BSText("name", $contact->person->name(), array('disabled', 'true')) !!}

		{!! Form::BSEndGroup() !!}

		@include('company_person.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection