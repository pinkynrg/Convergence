<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
</head>
<body>

@include('includes.header')

<div class="container wrapper" ajax-route= {{ "http://$_SERVER[HTTP_HOST]"."/ajax"."$_SERVER[REQUEST_URI]" }} >
	
	@include('includes.menu')

	<h2 class="title"> {!! isset($title) ? $title : "[missing page title]" !!} </h2> 

	<hr>

	@include('includes.errors')

	@yield('content')

	<hr>
	
</div>

@include('includes.footer')

</body>
</html>