@extends('layouts.default')
@section('content')

	{!! Form::model($contact, array('method' => 'PATCH', 'route' => array('company_person.update',$contact->id))) !!}

		@include('company_person.form')
		
		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection