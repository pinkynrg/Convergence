@include('includes.image-gallery')

<div id="header" class="row">

	@if (Auth::check())
		<div class="hidden-sm-down col-sm-6" id="logo">
	@else 
		<div class="col-sm-12" id="logo">
	@endif
		<a href="{{ route('root') }}"><img src="/images/style/logo-elettric80.png"></a>
	</div>

	@if (Auth::check())
		<div class="col-xs-12 col-sm-6" id="login_panel">
			<div id="login_panel_thumb">				
				<img src="{!! Auth::user()->owner->image() !!}">
			</div>
			<div id="loginfo">
				<div> {{ 'Hello '.Auth::user()->owner->name() }} </div>
				<div> <a href="/logout"> Logout </a> </div>
			</div>
		</div>
	@endif

</div>