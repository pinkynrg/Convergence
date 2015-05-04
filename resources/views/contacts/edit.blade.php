@extends('layouts.default')
@section('content')

	@include('includes.errors')

	<div class="form">

		{!! Form::model($contact, array('method' => 'PATCH', 'route' => array('contacts.update', $contact->id), 'class' => 'form-horizontal')) !!}

			@include('contacts.form')

			{!! Form::BSSubmit("Submit") !!}

		{!! Form::close() !!}

	</div>

@endsection