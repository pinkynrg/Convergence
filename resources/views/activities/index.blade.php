@extends('layouts.default')

@section('content')

	@include('activities.activities', array("activities" => $activities))

@endsection
