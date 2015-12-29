@extends('layouts.default')
@section('content')

	{!! Form::open(array('route' => 'companies.store', 'class' => "form-horizontal")) !!}

		@include('companies.form')

		<div class="nav_form navb">
			<div class="title"> Contact Info </div>
		</div>	

		@include('people.create')
		@include('company_person.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSLabel("group_type_id", "Permission Group Type", ['bclass' => 'col-xs-2']) !!}
			{!! Form::BSSelect("group_type_id", $group_types, null, array('bclass' => 'col-xs-3', "key" => "id", "value" => "display_name")) !!}

		{!! Form::BSEndGroup() !!}
		
		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close() !!}

@endsection