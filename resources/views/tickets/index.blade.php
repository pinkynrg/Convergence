@extends('layouts.default')

@section('content')

	<div>
		@include('tickets/tickets', array('tickets' => $tickets))
	</div>
	
@endsection
