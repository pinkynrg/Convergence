@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'customers.store', 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('customers.form')

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