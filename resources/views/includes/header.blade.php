<div id="header">

	<div id="logo">
		<a href="{{ route('root') }}"><img src="/images/style/logo-elettric80.png"></a>
	</div>

	@if (Auth::check())
		<div id="login_panel">
			<div id="loginfo">
				<div> {{ 'Hello '.Auth::user()->owner->name() }} </div>
				<div> <a href="/logout"> Logout </a> </div>
			</div>
			<div id="login_panel_thumb">				
				<img src="{!! Auth::user()->owner->image() !!}">
			</div>
		</div>
	@endif

</div>