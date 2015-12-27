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
		<tr>
			<th>Email</th> <td> {!! $company_person->email !!} </td>
		</tr>
		<tr>
			<th>Slack Token</th> <td> {!! $company_person->slack_token !!} </td>
		</tr>
		<tr>
			<th>Permission Group</th> <td> {!! isset($company_person->group_type_id) ? $company_person->group_type->display_name : '' !!} </td>
		</tr>
		<tr>
			<th>Permission Group</th> <td> {!! isset($company_person->group_id) ? $company_person->group->display_name : '' !!} </td>
		</tr>
	</table>

@endsection