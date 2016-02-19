@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'companies.store')) !!}

		@include('companies.form')

		<div class="nav_form navb">
			<div class="title"> Contact Info </div>
		</div>	

		@include('people.create')
		@include('company_person.form')

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection