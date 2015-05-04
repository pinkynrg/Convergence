<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
</head>
<body>

@include('includes.header')

<div class="container wrapper">
	
	@include('includes.menu')

	@yield('content')
	
</div>

@include('includes.footer')

</body>
</html>