<div class="form">

	{!! Form::BSGroup() !!}
		
		{!! Form::hidden("customer_id") !!}

		{!! Form::BSLabel("name", "Name") !!}
		{!! Form::BSText("name") !!}

		{!! Form::BSLabel("phone", "Phone") !!}
		{!! Form::BSText("phone") !!}

	{!! Form::BSEndGroup() !!}

	{!! Form::BSGroup() !!}

		{!! Form::BSLabel("cellphone", "Cellphone") !!}
		{!! Form::BSText("cellphone") !!}

		{!! Form::BSLabel("email", "Email") !!}
		{!! Form::BSText("email") !!}

	{!! Form::BSEndGroup() !!}

</div>