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
					<th>Name</th><td> {{ $person->name() }} </td>
				</tr>
				<tr>
					<th>Department</th><td> </td>
				</tr>
				<tr>
					<th>Title</th><td>   </td>
				</tr>
				<tr>
					<th>E-mail</th><td> {{ $person->email }} </td>
				</tr>
				<tr>
					<th>Phone</th><td> {{ $person->phone() }} </td>
				</tr>
			</table>

		</div>


@endsection