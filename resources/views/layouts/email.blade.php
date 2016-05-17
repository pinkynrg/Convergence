<body>

	<div class="heading">{{ $title }}</div>

	<style>
		[style*="Open Sans"] {
	    	font-family: 'Open Sans', Arial, sans-serif !important
		}
	</style>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<center>
		<table height="30"><tr><td></td></tr></table> <!-- margin top -->
		<table width="650" class="wrapper">
			<tr>
				<td>

					<div><img width="200" class="logo" src="{{ SITE_URL."/resources/style/mini-logo-elettric80.png" }}"/></div>

					<hr>

					@yield('content')

					<hr>

					<table class="footer"><tr height="30"><td><center><a href="{{SITE_URL}}">{{ SITE_URL}}</a> | &#169; Elettric80 Inc - Convergence </center></td></tr></table> <!-- footer -->
				</td>
			</tr>

		</table>
		<table height="30"><tr><td></td></tr></table> <!-- margin bottom -->
	</center>
</body>