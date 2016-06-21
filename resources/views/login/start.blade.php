@extends('layouts.light')
@section('content')

	<div id="starting_form">
	
		{!! Form::model($profile, array('route' => 'login.store_info', 'files' => 'true')) !!}

			<div id="personal_info">

				<div class="alert alert-info info_start" role="info"> 
					<div> <i class="fa fa-info-circle"></i>
						Before you start, please provide the following information.
					</div>
				</div>

				<h3> General Information </h3>

				<div id="profile_picture_form" class="row">
					<div class="col-xs-12">
						<div>
							{!! Form::BSFile("profile_picture","Upload Picture", $profile->profile_picture) !!}
						</div>
					</div>
				</div>

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
			</div>

			<div id="contact_details">

				<h3> Contact Details </h3>

				<div class="alert alert-info" role="info"> 
					<div> <i class="fa fa-info-circle"></i>
						Please fill out each tab -OR- Use the same information for all of your contacts by checking "Use info for all"
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
								{!! Form::BSHidden("contact[phone]") !!}
								{!! Form::BSText("contact[fake_phone]",null,['id'=>'contact[fake_phone]']) !!}
							{!! Form::BSEndGroup() !!}

							{!! Form::BSGroup() !!}
								{!! Form::BSLabel("contact[cellphone]", "Cellphone") !!}
								{!! Form::BSHidden("contact[cellphone]") !!}
								{!! Form::BSText("contact[fake_cellphone]",null,['id'=>'contact[fake_cellphone]']) !!}
							{!! Form::BSEndGroup() !!}

							{!! Form::BSGroup() !!}
								{!! Form::BSLabel("contact[department_id]", "Department") !!}
								{!! Form::BSMultiSelect("contact[department_id]", $departments, ["title" => "select department", "value" => "id", "data-size" => "10", "label" => "!name", "multiple" => "false", "selected" => []]) !!}	

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
								{!! Form::BSMultiSelect("contact[title_id]", $titles, ["title" => "select title", "value" => "id", "label" => "!name",  "data-size" => "10", "search" => "true", "multiple" => "false", "selected" => []]) !!}	
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

					<div class="tab-content">

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
											{!! Form::BSHidden("contacts[$key][phone]") !!}
											{!! Form::BSText("contacts[$key][fake_phone]",null,['id'=>"contacts[$key][fake_phone]"]) !!}
										{!! Form::BSEndGroup() !!}

										{!! Form::BSGroup() !!}
											{!! Form::BSLabel("contacts[$key][cellphone]", "Cellphone") !!}
											{!! Form::BSHidden("contacts[$key][cellphone]") !!}
											{!! Form::BSText("contacts[$key][fake_cellphone]",null,['id'=>"contacts[$key][fake_cellphone]"]) !!}
										{!! Form::BSEndGroup() !!}

										{!! Form::BSGroup() !!}
											{!! Form::BSLabel("contacts[$key][department_id]", "Department") !!}
											{!! Form::BSMultiSelect("contacts[$key][department_id]", $departments, ["title" => "select title", "value" => "id", "data-size" => "10", "label" => "!name", "multiple" => "false"]) !!}

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
											{!! Form::BSMultiSelect("contacts[$key][title_id]", $titles, ["title" => "select title", "value" => "id", "data-size" => "10", "label" => "!name", "multiple" => "false"]) !!}
										{!! Form::BSEndGroup() !!}

									</div>
								</div>

							</div>
						@endforeach
					</div>
				</div>
			</div>

			@if (!Session::get('start_session.safe_enough'))
			
				<div id="user_information">
					<h3> User Information </h3>

					<div class="alert alert-danger" role="info"> 
						<div> <i class="fa fa-info-circle"></i>
							Your current password is not safe. The password must contain a lower case character, an upper case character, at least a digit and it has to be at least 10 characters.
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
				</div>

			@endif

			{!! Form::BSSubmit("Submit") !!}

		{!! Form::close() !!}

	</div>

@endsection

