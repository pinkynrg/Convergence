@extends('layouts.light')
@section('content')

	{!! Form::model($profile, array('route' => array('login.store_info',$profile->id), 'files' => 'true')) !!}

		<div class="alert alert-info info_start" role="info"> 
			<div> <i class="fa fa-info-circle"></i>
				Before you start, it is neccessary you complete some information about yourself.
			</div>
		</div>

		<h3> General Information </h3>

		<div class="row">
			<div class="col-xs-6">

				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("first_name", "First Name") !!}
					{!! Form::BSText("first_name") !!}
				{!! Form::BSEndGroup() !!}

			</div>

			<div class="col-xs-6">

				{!! Form::BSGroup() !!}
					{!! Form::BSLabel("last_name", "Last Name") !!}
					{!! Form::BSText("last_name") !!}
				{!! Form::BSEndGroup() !!}	

			</div>
		</div>


		<div class="alert alert-warning info_start" role="info"> 
			<div> <i class="fa fa-info-circle"></i>
				Please fill out necessary information for each tab below ~ OR ~ use the same informations for all you contacts by checking `Use info for all`
			</div>
		</div>

		<p><b> Use info for all: </b></p>
		<input type="checkbox" id="use_info_all_contacts_fake" class="switch" data-off-text="No" data-on-text="Yes" value="true">
		{!! Form::hidden("use_info_all_contacts", null, array("id" => "use_info_all_contacts")) !!}

		<div id="form_all_contacts">
			<div class="row">
				<div class="col-xs-6">
					{!! Form::BSGroup() !!}
						{!! Form::BSLabel("contact[phone]", "Phone") !!}
						{!! Form::BSText("contact[phone]") !!}
					{!! Form::BSEndGroup() !!}

					{!! Form::BSGroup() !!}
						{!! Form::BSLabel("contact[cellphone]", "Cellphone") !!}
						{!! Form::BSText("contact[cellphone]") !!}
					{!! Form::BSEndGroup() !!}

					{!! Form::BSGroup() !!}
						{!! Form::BSLabel("contact[department_id]", "Department") !!}
						{!! Form::BSSelect("contact[department_id]",$departments, null, ['key' => 'id', 'value' => '!name']) !!}
					{!! Form::BSEndGroup() !!}

				</div>

				<div class="col-xs-6">	

					{!! Form::BSGroup() !!}
						{!! Form::BSLabel("contact[extension]", "Extension") !!}
						{!! Form::BSText("contact[extension]") !!}
					{!! Form::BSEndGroup() !!}

					{!! Form::BSGroup() !!}
						{!! Form::BSLabel("contact[email]", "Email") !!}
						{!! Form::BSText("contact[email]") !!}
					{!! Form::BSEndGroup() !!}

					{!! Form::BSGroup() !!}
						{!! Form::BSLabel("contact[title_id]", "Title") !!}
						{!! Form::BSSelect("contact[title_id]",$titles, null, ['key' => 'id', 'value' => '!name']) !!}
					{!! Form::BSEndGroup() !!}

				</div>
			</div>
		</div>

		<div id="form_contacts">
			<ul class="nav nav-tabs">
				
				@foreach ($profile->contacts as $contact)
					@if ($contact->id == $profile->first_contact)
						<li class="nav active">
					@else
						<li class="nav">
						@endif
			  			<a target="{{ $contact->company->id }}" href="#{{ $contact->company->id }}" data-toggle="tab"> 
			  				{{ $contact->company->name }} 
			  			</a>
			  		</li>
			  	@endforeach
			</ul>

			<div class="tab-content mrg-brm-20">

				@foreach ($profile->contacts as $key => $contact)
					
					@if ($contact->id == $profile->first_contact)
						<div class="tab-pane fade in active" id="{{ $profile->contacts->{$key}->company->id }}">
					@else
						<div class="tab-pane fade" id="{{ $profile->contacts->{$key}->company->id }}">
					@endif

						<div class="row">

							<div class="col-xs-6">
								{!! Form::BSGroup() !!}
									{!! Form::BSLabel("contacts[$key][phone]", "Phone") !!}
									{!! Form::BSText("contacts[$key][phone]") !!}
								{!! Form::BSEndGroup() !!}

								{!! Form::BSGroup() !!}
									{!! Form::BSLabel("contacts[$key][cellphone]", "Cellphone") !!}
									{!! Form::BSText("contacts[$key][cellphone]") !!}
								{!! Form::BSEndGroup() !!}

								{!! Form::BSGroup() !!}
									{!! Form::BSLabel("contacts[$key][department_id]", "Department") !!}
									{!! Form::BSSelect("contacts[$key][department_id]",$departments, null, ['key' => 'id', 'value' => '!name']) !!}
								{!! Form::BSEndGroup() !!}

							</div>

							<div class="col-xs-6">	

								{!! Form::BSGroup() !!}
									{!! Form::BSLabel("contacts[$key][extension]", "Extension") !!}
									{!! Form::BSText("contacts[$key][extension]") !!}
								{!! Form::BSEndGroup() !!}

								{!! Form::BSGroup() !!}
									{!! Form::BSLabel("contacts[$key][email]", "Email") !!}
									{!! Form::BSText("contacts[$key][email]") !!}
								{!! Form::BSEndGroup() !!}

								{!! Form::BSGroup() !!}
									{!! Form::BSLabel("contacts[$key][title_id]", "Title") !!}
									{!! Form::BSSelect("contacts[$key][title_id]",$titles, null, ['key' => 'id', 'value' => '!name']) !!}
								{!! Form::BSEndGroup() !!}

							</div>
						</div>

					</div>
				@endforeach
			</div>
		</div>

		@if (!Session::get('password'))

			<hr>

			<h3> User Information </h3>

			<div class="alert alert-danger info_start" role="info"> 
				<div> <i class="fa fa-info-circle"></i>
					Your current password is not safe. Please make sure to pick a password that respects the new safety requirments. 
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					{!! Form::BSGroup() !!}
						{!! Form::BSLabel("username", "Username") !!}
						{!! Form::BSText("username", null, array("disabled" => "true")) !!}
					{!! Form::BSEndGroup() !!}	
				</div>
			</div>

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

		@endif

		{!! Form::BSSubmit("Submit") !!}

	{!! Form::close() !!}

@endsection

