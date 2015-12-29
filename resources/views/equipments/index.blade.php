@extends('layouts.default')

@section('content')

	@include('equipments.equipments', array("equipments" => $equipments))

@endsection
