@extends('layouts.default')
@section('content')

	@include('includes.errors')

	{!! Form::model($customer, array('route' => 'contacts.store', 'class' => "form-horizontal")) !!}

		@include('contacts.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection