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
	
	<div class="horizontal_menu hidden-lg hidden-md hidden-sm">	
		@include('includes.horizontal-menu')
	</div>

	<div class="vertical_menu_container hidden-xs hidden-ms">
		<div class="vertical_menu">
			@include('includes.vertical-menu')
		</div>
	</div>
	<div class="inner_container">
		<h2 class="title"> {!! isset($title) ? $title : "[missing page title]" !!} </h2> 
		<hr>
		@include('includes.errors')
		@yield('content')
		<hr>
	</div>
</div>

@include('includes.footer')

</body>
</html>