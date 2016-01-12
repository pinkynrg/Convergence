@extends('layouts.default')

@section('content')

	@include('services.services', array("services" => $services))

@endsection
