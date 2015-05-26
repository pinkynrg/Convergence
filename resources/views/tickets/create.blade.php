@extends('layouts.default')
@section('content')

	@include('includes.errors')

	{!! Form::open( array('route' => 'tickets.store', 'class' => 'form-horizontal' )) !!}

		<div class="form">
			{!! Form::BSGroup() !!}

				{!! Form::BSLabel('title','Title') !!}
				{!! Form::BSText('title', null) !!}			

			{!! Form::BSEndGroup() !!}
		</div>

	{!! Form::close() !!}

@endsection