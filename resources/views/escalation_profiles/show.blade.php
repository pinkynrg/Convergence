@extends('layouts.default')

@section('content')
	<div id="escalation_profile_wrapper">
		<table class="table table-striped table-hover">
			<tr>
				<th> Name </th> <td> {{ $escalation_profile->name }} </td>
			</tr>
			<tr>
				<th> Description </th> <td> {{ $escalation_profile->description }} </td>
			</tr>
			<tr>
				<th> Created </th> <td> {{ $escalation_profile->date("created_at") }} </td>
			</tr>
			<tr>
				<th> Updated </th> <td> {{ $escalation_profile->date("updated_at") }} </td>
			</tr>
		</table>
	</div>

	<h3>Escalation Events</h3>	

	<div class="alert alert-info" role="alert"> 
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div> 
			<i class="fa fa-info-circle"></i>
			Here you can add escalation rules: Escalation Events. 
			Every time a ticket hasn't been resolved within the set Time Limit, 
			a selected Target will receive an email reminding him that our customers need more care. 
		</div>
	</div>

	{!! Form::BSButton("Add New Custom Event",['id' => 'add_escalation_event','bclass' => 'col-xs-offset-2']) !!}

	{!! Form::model($escalation_profile_events, array('method' => 'POST', 'route' => array('escalation_profiles.update_events',$escalation_profile->id), 'class' => 'form-horizontal')) !!}
		
		{!! Form::hidden("num", $rows, array('id' => "num")) !!}

		<table id="escalation_events_table" class="table">
		
			<tr>
				<th></th>
				<th>Time Limit</th>
				<th>Target</th>
				<th>Fallback</th>
				<th></th>
			</tr>

			@for ($k=0; $k<$rows; $k++)
				@include("escalation_profiles.form_event",["counter" => $k])
			@endfor

		</table>

		{!! Form::BSSubmit("Submit",['bclass' => 'col-xs-offset-2']) !!}

	{!! Form::close(); !!}


@endsection