@extends('layouts.default')

@section('content')

	@include('roles.roles', array("roles" => $roles))

@endsection