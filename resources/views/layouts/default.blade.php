<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	@include('includes.head')
</head>
<body>

@include('includes.header')

<div id="loading"></div>

<div class="container wrapper" ajax-route= {{ "http://$_SERVER[HTTP_HOST]"."$_SERVER[REQUEST_URI]" }} >
	
	@include('includes.menu')

	<h2 class="title"> {!! isset($title) ? $title : "[missing page title]" !!} </h2> 
	<hr>

	<div class="inner_container">
		@include('includes.errors')
		@yield('content')
		<hr>
	</div>
</div>

@include('includes.footer')

</body>
</html>