@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'companies.store', 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('companies.form')

		<div class="nav_form navb">
			<div class="title"> Contact Info </div>
		</div>	

		@include('people.create')
		@include('company_person.form')
		
		{!! Form::BSGroup() !!}

			{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection