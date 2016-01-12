{!! Form::BSGroup() !!}

	{!! Form::BSHidden("company_id", $company_id) !!}
	{!! Form::BSLabel("company_id", "Company", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("company_id", $companies, $company_id, ['key' => 'id', 'value' => 'name', 'disabled', 'bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("hotel_id", "Hotel", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("hotel_id", $hotels, null, ['key' => 'id', 'value' => 'name_address', 'bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("external_contact_id", "Contact", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("external_contact_id", $contacts, null, ['key' => 'id', 'value' => 'person.name', 'bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("internal_contact_id", "Internal Contact", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("internal_contact_id", $technicians, null, ['key' => 'id', 'value' => 'person.name', 'bclass' => 'col-xs-3']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("has_internal", "Has Internal Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("has_internal", ['No','Yes'], null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("job_number_internal", "Internal Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("job_number_internal",null, ['bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("has_remote", "Has Remote Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("has_remote", ['No','Yes'], null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("job_number_remote", "Remote Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("job_number_remote",null, ['bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSGroup() !!}

	{!! Form::BSLabel("has_onsite", "Has Onsite Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSSelect("has_onsite", ['No','Yes'], null, ['bclass' => 'col-xs-3']) !!}

	{!! Form::BSLabel("job_number_onsite", "Onsite Job #", ['bclass' => 'col-xs-2']) !!}
	{!! Form::BSText("job_number_onsite",null, ['bclass' => 'col-xs-3', 'disabled' => 'disabled']) !!}

{!! Form::BSEndGroup() !!}

{!! Form::BSHidden("technician_number", $technician_number) !!}

<ul class="nav nav-tabs" role="tablist">

@for ($i = 0; $i < $technician_number; $i++)

  @if ($i == 0) 
  	<li role="presentation" class="tab active"><a href="#tech_{{$i+1}}" aria-controls="tech_{{$i+1}}" role="tab" data-toggle="tab">Technician #{{$i+1}}</a></li> 
  @else
  	<li role="presentation" class="tab"><a href="#tech_{{$i+1}}" aria-controls="tech_{{$i+1}}" role="tab" data-toggle="tab">Technician #{{$i+1}}</a></li> 
  @endif

@endfor

@if ($technician_number > 1)
	<li><a href="/services/create/{{$company_id}}/{{$technician_number-1}}" role="tab" class="danger"><i class="fa fa-minus-circle"></i> Remove a technician</a></li>
@endif

@if ($technician_number < 5)
	<li><a href="/services/create/{{$company_id}}/{{$technician_number+1}}" role="tab" class="info"><i class="fa fa-plus-circle"></i> Add a technician</a></li>
@endif

</ul>

<div class="tab-content">

@for ($i = 0; $i < $technician_number; $i++)

	@if ($i == 0)
		<div role="tabpanel" class="tab-pane active" id="tech_{{$i+1}}">
	@else 
		<div role="tabpanel" class="tab-pane" id="tech_{{$i+1}}">
	@endif

		@include('services.technician_form', array("tech_num" => $i) )
	</div>

@endfor

</div>