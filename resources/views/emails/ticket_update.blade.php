@extends('layouts.email')
@section('content')

	<h3><a href="{{SITE_URL."/tickets/".$ticket->id}}"> {{ $title }} </a></h3>

	<hr>

	<p> The following changes were made: </p>

	@foreach ($changes as $key => $change)
		<p>
			@if ($key == 'post')
				post: <span class="remarked"> Content was changed </span>
			@else
				{{ $key }}: <span class="remarked"> {{ $change['old_value'] }} </span>&nbsp;&nbsp;→&nbsp;&nbsp;<span class="remarked"> {{ $change['new_value'] }} </span>
			@endif
		</p>
	@endforeach

@endsection
	