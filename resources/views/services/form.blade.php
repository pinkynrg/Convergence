<div class="row">

	<div class="col-xs-6">
		
		{!! Form::BSGroup() !!}
			{!! Form::BSHidden("company_id", $company_id) !!}
			{!! Form::BSLabel("company_id", "Company") !!}
			{!! Form::BSSelect("company_id", $companies, $company_id, ['key' => 'id', 'value' => 'name', 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("external_contact_id", "Contact") !!}
			{!! Form::BSSelect("external_contact_id", $contacts, null, ['key' => 'id', 'value' => 'person.name']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("has_internal", "Has Internal Job #") !!}
			{!! Form::BSSelect("has_internal", ['No','Yes']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("has_remote", "Has Remote Job #") !!}
			{!! Form::BSSelect("has_remote", ['No','Yes']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("has_onsite", "Has Onsite Job #") !!}
			{!! Form::BSSelect("has_onsite", ['No','Yes']) !!}
		{!! Form::BSEndGroup() !!}

	</div>

	<div class="col-xs-6">

		{!! Form::BSGroup() !!}		
			{!! Form::BSLabel("hotel_id", "Hotel") !!}
			{!! Form::BSSelect("hotel_id", $hotels, null, ['key' => 'id', 'value' => 'name_address']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("internal_contact_id", "Internal Contact") !!}
			{!! Form::BSSelect("internal_contact_id", $technicians, null, ['key' => 'id', 'value' => 'person.name']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("job_number_internal", "Internal Job #") !!}
			{!! Form::BSText("job_number_internal",null, ['disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("job_number_remote", "Remote Job #") !!}
			{!! Form::BSText("job_number_remote",null, ['disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

		{!! Form::BSGroup() !!}
			{!! Form::BSLabel("job_number_onsite", "Onsite Job #") !!}
			{!! Form::BSText("job_number_onsite",null, ['disabled' => 'disabled']) !!}
		{!! Form::BSEndGroup() !!}

	</div>
</div>
	
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
	<li><a href="/services/create/{{$company_id}}/{{$technician_number-1}}" role="tab" class="danger"><i class="fa fa-minus-circle"></i> <span class="hidden-xs">Remove a technician</span></a></li>
@endif

@if ($technician_number < 5)
	<li><a href="/services/create/{{$company_id}}/{{$technician_number+1}}" role="tab" class="info"><i class="fa fa-plus-circle"></i> <span class="hidden-xs">Add a technician</span></a></li>
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