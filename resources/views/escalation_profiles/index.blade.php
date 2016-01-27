@extends('layouts.default')

@section('content')

	@include('escalation_profiles.escalation_profiles', array("escalation_profiles" => $escalation_profiles))

@endsection