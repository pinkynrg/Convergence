@extends('layouts.default')
@section('content')

	{!! Form::open(array('method' => 'PATCH', 'route' => array('users.update',$user->id))) !!}

		<div class="row">
			<div class="col-xs-6">
				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("password", "Password") !!}
					{!! Form::BSPassword("password") !!}
				{!! Form::BSEndGroup() !!}				
			</div>
			
			<div class="col-xs-6">
				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("password2", "Repeat Password") !!}
					{!! Form::BSPassword("password2") !!}
				{!! Form::BSEndGroup() !!}
			</div>
		</div>

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection