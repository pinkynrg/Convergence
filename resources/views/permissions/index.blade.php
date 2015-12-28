@extends('layouts.default')

@section('content')

	@include('includes.errors')

	@include('permissions.permissions', array("permissions" => $permissions))

@endsection