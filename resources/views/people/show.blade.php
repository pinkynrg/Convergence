@extends('layouts.default')

@section('content')

	<div class="media">
		<div class="media-left media-middle">
			<a href="#">
				<img class="thumbnail" src="/images/avatar.png" alt="/images/avatar.png">
			</a>
		</div>
		
		<div class="media-body">

			<h3> {{ $person->name() }} </h3>

			<table class="table table-striped table-condensed table-hover">
				<thead>
					<tr>
						<th>Id</th>					
						<th>Company</th>
						<th>Title</th>
						<th>Department</th>
						<th>Phone</th>
						<th>Cell Phone</th>
					</tr>
				</thead>
				<tbody>

					@foreach ($person->company_person as $contact)

					<tr>
						<td> <a href="{{ route('company_person.show', $contact->id) }}"> {{ '#'.$contact->id }} </a> </td>					
						<td> {{ $contact->company->name }} </td>
						<td> {{ isset($contact->title_id) ? $contact->title->name : '' }} </td>
						<td> {{ isset($contact->department_id) ? $contact->department->name : '' }} </td>
						<td> {!! $contact->phone() !!} </td>
						<td> {!! $contact->cellphone() !!} </td>
					</tr>

					@endforeach

				</tbody>
			</table>

		</div>

@endsection