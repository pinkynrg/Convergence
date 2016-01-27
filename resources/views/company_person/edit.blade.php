@extends('layouts.default')
@section('content')

	{!! Form::model($contact, array('method' => 'PATCH', 'route' => array('company_person.update',$contact->id), 'class' => "form-horizontal")) !!}

		<h3 class="subtitle"> Contact Details </h2>

		{!! Form::BSGroup() !!}

			{!! Form::BSLabel("name", "Person Name", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSText("name", $contact->person->name(), ['bclass' => 'col-xs-3','disabled' => 'true']) !!}

		{!! Form::BSEndGroup() !!}

		@include('company_person.form')

		<h3 class="subtitle"> Contact Permissions </h2>

		{!! Form::BSGroup() !!}

			{!! Form::BSLabel("group_id", "Permission Group", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSSelect("group_id", $groups, null, array('bclass' => 'col-xs-3', "key" => "id", "value" => "display_name")) !!}

		{!! Form::BSEndGroup() !!}

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection