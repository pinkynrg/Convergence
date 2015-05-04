@extends('layouts.default')
@section('content')

	<div class="nav_form navb">
		<div class="title"> Contact Info </div>
		{!! Form::back( URL::previous() ) !!}
	</div>	

	@include('includes.errors')

	{!! Form::open( array('route' => 'tickets.store', 'class' => 'form-horizontal' )) !!}

		<div class="form">
			{!! Form::BSGroup() !!}

				{!! Form::BSLabel('title','Title',1) !!}
				{!! Form::BSText('title', null, 1) !!}			

			{!! Form::BSEndGroup() !!}
		</div>

	{!! Form::close() !!}

@endsection