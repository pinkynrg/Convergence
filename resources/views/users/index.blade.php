@extends('layouts.default')

@section('content')

	@include('users.users', array("users" => $users))

@endsection