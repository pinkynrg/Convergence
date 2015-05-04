@extends('layouts.default')
@section('content')
	
	{!! Form::model($customer, array('method' => 'PATCH', 'route' => array('customers.update',$customer->id), 'class' => "form-horizontal")) !!}

		@include('includes.errors')

		@include('customers.form')

		{!! Form::BSGroup() !!}

			{!! Form::BSFiller() !!}
			{!! Form::BSSubmit("Submit") !!}

		{!! Form::BSEndGroup() !!}

	{!! Form::close() !!}

@endsection