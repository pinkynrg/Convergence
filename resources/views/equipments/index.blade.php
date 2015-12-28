@extends('layouts.default')

@section('content')

	@include('includes.errors')

	@include('equipments.equipments', array("equipments" => $equipments))

@endsection
