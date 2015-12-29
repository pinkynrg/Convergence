@extends('layouts.default')

@section('content')

	@include('group_types.group_types', array("group_types" => $group_types))

@endsection