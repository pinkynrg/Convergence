<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	@include('includes.head')
</head>
<body>

@include('includes.debug')

@include('includes.header')

<div id="loading"></div>

@if (Route::currentRouteName() != "companies.show" && Route::currentRouteName() != "company_person.show")
	<div class="container wrapper" ajax-route= {{ "http://$_SERVER[HTTP_HOST]"."$_SERVER[REQUEST_URI]" }} >
@else
	<div class="container wrapper">
@endif
	
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
			<div class="col-sm-6 col-ms-6 col-xs-12">
				<h2 class="title"> {!! isset($title) ? $title : "[missing page title]" !!} </h2>
			</div>
			
			@if (isset($active_search))
				<div class="col-lg-3 col-sm-2 col-ms-1 col-xs-0"></div>
				<div class="col-lg-3 col-sm-4 col-ms-5 col-xs-12 form-group">
					<input id="search_field" type="text" columns="{{$active_search}}" class="form-control search" placeholder="search">
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