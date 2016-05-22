<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		@include('includes.head')
	</head>

	<body>

		@include('includes.header')

		<!-- used when ajax is executing -->
		<div id="loading"></div>

		@if (Route::currentRouteName() != "companies.show" && Route::currentRouteName() != "company_person.show")
			<div id="main_container" class="container" ajax-route= {{ CURRENT_URL }} >
		@else
			<div id="main_container" class="container">
		@endif
			
			<div id="horizontal_menu_container">
				<div id="horizontal_menu" class="hidden-lg">	
					@include('includes.horizontal-menu')
				</div>
			</div>

			<div id="vertical_menu_container" class="hidden-xs hidden-ms hidden-sm hidden-md">
				<div id="vertical_menu">
					@include('includes.vertical-menu')
				</div>
			</div>
			
			<div id="inner_container">
				<div id="header_container" class="row">
					@if (isset($active_search))
						<div class="col-lg-9 col-sm-8 col-ms-7 col-xs-12">
					@else
						<div class="col-xs-12">
					@endif
							<h2 id="title"> {!! isset($title) ? $title : "" !!} </h2>
						</div>
					
					@if (isset($active_search))
						<div id="search_wrapper" class="col-lg-3 col-sm-4 col-ms-5 col-xs-12 pull-right">
							<div class="input-group">
								<input id="search" type="text" columns="{{$active_search}}" class="form-control" placeholder="search">
								<span id="clear_search" class="input-group-addon"><i class="fa fa-times"></i></span>
							</div>
						</div>
					@endif

				</div>
				
				@include('includes.errors')
				
				@yield('content')

			</div>
		</div>

		@include('includes.footer')

	</body>
	
</html>