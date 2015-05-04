@extends('layouts.default')

@section('content')

	<div class="media">
		  <div class="media-left media-middle">
		    <a href="#">
		      <img class="thumbnail" src="/images/avatar.png" alt="/images/avatar.png">
		    </a>
		  </div>
		  <div class="media-body">


			<table class="table table-striped table-condensed table-hover">
				<tr>
					<th>Name</th><td> {{ $employee->name() }} </td>
				</tr>
				<tr>
					<th>Department</th><td> {{ $employee->department->name }} </td>
				</tr>
				<tr>
					<th>Title</th><td> {{ $employee->title->name }}  </td>
				</tr>
				<tr>
					<th>E-mail</th><td> {{ $employee->email }} </td>
				</tr>
				<tr>
					<th>Phone</th><td> {{ $employee->phone }} </td>
				</tr>
			</table>

		</div>


@endsection