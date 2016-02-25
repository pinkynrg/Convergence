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
	
	<div class="horizontal_menu hidden-lg">	
		@include('includes.horizontal-menu')
	</div>

	<div class="vertical_menu_container hidden-xs hidden-ms hidden-sm hidden-md">
		<div class="vertical_menu">
			@include('includes.vertical-menu')
		</div>
	</div>
	
	<div class="inner_container">
		<div class="header_container row">
			<div class="col-sm-6 col-ms-12">
				<h2 class="title"> {!! isset($title) ? $title : "[missing page title]" !!} </h2>
			</div>
			@if (isset($active_search))
				<div class="col-lg-3 col-sm-1"></div>
				<div class="col-lg-3 col-sm-5 hidden-ms hidden-sm hidden-xs form-group">
					<input type="text" columns="{{$active_search}}" class="form-control search" placeholder="search">
				</div>
			@endif
		</div>
		<hr>
		@include('includes.errors')
		@yield('content')
		<hr>
	</div>
</div>

@include('includes.footer')

</body>
</html>