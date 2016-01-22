@extends('layouts.default')

@section('content')

	@include('equipment.equipment', array("equipment" => $equipment))

@endsection
