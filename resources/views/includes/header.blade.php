@include('includes.image-gallery')

<div id="header" class="row">

	@if (Auth::check())
		<div id="logo" class="logo-hidden">
	@else
		<div id="logo">
	@endif
		<a href="{{ route('root') }}"><img src="/images/style/logo-elettric80.png"></a>
	</div>

	@if (Auth::check())
		<div id="login_panel">
			<div id="login_panel_thumb">				
				<img src="{!! Auth::user()->owner->image() !!}">
			</div>
			<div id="loginfo">
				<div> {{ Auth::user()->owner->name() }} </div>
				<div> <a href="/logout"> Logout </a> </div>
			</div>
		</div>
	@endif

</div>