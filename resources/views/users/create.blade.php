@extends('layouts.default')
@section('content')

	{!! Form::model($user, array('route' => 'users.store')) !!}

		<div class="row">
			<div class="col-xs-6">
				
				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("person_id", "Person") !!}
					{!! Form::BSSelect("person_id", $people, null, ["key" => "id", "value" => "name"]) !!}
				{!! Form::BSEndGroup() !!}
				
				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("password", "Password") !!}
					{!! Form::BSPassword("password") !!}
				{!! Form::BSEndGroup() !!}

			</div>
			<div class="col-xs-6">
				
				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("username", "Username") !!}
					{!! Form::BSText("username") !!}
				{!! Form::BSEndGroup() !!}

				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("password2", "Repeat Password") !!}
					{!! Form::BSPassword("password2") !!}
				{!! Form::BSEndGroup() !!}

			</div>
		</div>

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection