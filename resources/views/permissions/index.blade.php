@extends('layouts.default')

@section('content')

	@include('permissions.permissions', array("permissions" => $permissions))

@endsection