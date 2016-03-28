@extends('layouts.default')

@section('content')

	<ul class="nav nav-tabs">
		@foreach ($company_person->person->company_person as $contact)
			@if ($contact->id == $company_person->id)
	  			<li class="nav active">
	  		@else
	  			<li class="nav">
	  		@endif
	  			<a href="{{ route('company_person.show', $contact->id) }}"> {{ $contact->company->name }} </a>
	  		</li>
	  	@endforeach
	</ul>

	<div class="tab-content">

		<div class="tab-pane fade in active" id="{{ $company_person->company->id }}">
			@include('company_person.contact', array("company_person" => $company_person))
		</div>

	</div>

@endsection