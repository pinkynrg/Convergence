@extends('layouts.default')

@section('content')

	<table class="table table-striped table-condensed table-hover">
		<tr>
			<th> Name </th> <td> {{ $company_person->person->name() }} </td>
		</tr>
		<tr>
			<th> Company </th> <td> {{ $company_person->company->name }} </td>
		</tr>
		<tr>
			<th>Title</th> <td> {{ isset($company_person->title_id) ? $company_person->title->name : '' }} </td>
		</tr>
		<tr>
			<th>Department</th> <td> {{ isset($company_person->department_id) ? $company_person->department->name : '' }} </td>
		</tr>
		<tr>
			<th>Phone</th> <td> {!! $company_person->phone() !!} </td>
		</tr>
		<tr>
			<th>Cell Phone</th> <td> {!! $company_person->cellphone() !!} </td>
		</tr>
	</table>

@endsection