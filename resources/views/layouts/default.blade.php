<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
</head>
<body>

@include('includes.header')

<div class="container wrapper" ajax-route= {{ "http://$_SERVER[HTTP_HOST]"."/ajax"."$_SERVER[REQUEST_URI]" }} >
	
	@include('includes.menu')

	@yield('content')
	
</div>

@include('includes.footer')

</body>
</html>