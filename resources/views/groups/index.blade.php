@extends('layouts.default')

@section('content')

	@include('includes.errors')

	@include('groups.groups', array("groups" => $groups))

@endsection