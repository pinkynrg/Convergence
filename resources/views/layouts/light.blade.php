<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	@include('includes.head')
</head>
<body>

@include('includes.header')

<div id="main_container" class="container">
	<div id="inner_container">
		<div id="header_container" class="row">
			<div class="col-sm-6 col-ms-6 col-xs-12">
				<h2 id="title"> {!! isset($title) ? $title : "[missing page title]" !!} </h2>
			</div>
		</div>
		@include('includes.errors')
		@yield('content')
	</div>
</div>

@include('includes.footer')

</body>
</html>