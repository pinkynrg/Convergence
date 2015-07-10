@extends('layouts.default')
@section('content')

	@include('includes.errors')

	{!! Form::open( array('route' => 'tickets.store') ) !!}		

		@include('tickets.form')

		<div class="row">
			{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-12']) !!}
		</div>
		
	{!! Form::close() !!}

@endsection