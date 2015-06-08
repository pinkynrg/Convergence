@extends('layouts.default')
@section('content')
	
	{!! Form::model($employee, array('method' => 'PATCH', 'route' => array('people.update',$employee->id), 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		{!! Form::BSGroup() !!}

			{!! Form::hidden("company_id", null, array("id" => "company_id")) !!}
			{!! Form::hidden("person_id", null, array("id" => "person_id")) !!}

			<!-- insert &thinsp; to strick browser autofilling -->
			{!! Form::BSLabel("first_name", "First Name") !!}
			{!! Form::BSText("first_name") !!}

			<!-- insert &thinsp; to strick browser autofilling -->
			{!! Form::BSLabel("last_name", "Last Name") !!}
			{!! Form::BSText("last_name") !!}

		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection