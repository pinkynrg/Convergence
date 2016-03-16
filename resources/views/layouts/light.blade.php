<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	@include('includes.head')
</head>
<body>

@include('includes.debug')

@include('includes.header')

<div class="container wrapper">
	<div class="inner_container">
		<div class="header_container row">
			<div class="col-sm-6 col-ms-6 col-xs-12">
				<h2 class="title"> {!! isset($title) ? $title : "[missing page title]" !!} </h2>
			</div>
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