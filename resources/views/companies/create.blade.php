@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'companies.store', 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('companies.form')

		<div class="nav_form navb">
			<div class="title"> Contact Info </div>
		</div>	

		@include('contacts.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection