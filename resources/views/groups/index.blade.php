@extends('layouts.default')

@section('content')

	@include('groups.groups', array("groups" => $groups))

@endsection