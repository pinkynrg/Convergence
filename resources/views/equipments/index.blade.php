@extends('layouts.default')

@section('content')

	<div>
		@include('equipments.equipments', array("equipments" => $equipments))
	</div>

@endsection
