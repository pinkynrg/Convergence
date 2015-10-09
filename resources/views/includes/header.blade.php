<div id="header">

	<div id="logo">
		<a href="{{ route('root') }}"><img src="/images/logo-elettric80.png"></a>
	</div>

	@if (Auth::check())
	       <div id="login_panel">
	               <div id="loginfo">
	                       <div> {{ 'Hello '.Auth::user()->owner->name() }} </div>
	                       <div> <a href="/logout"> Logout </a> </div>
	               </div>
	               <img src="/images/login.png">
	       </div>
	@endif
	
</div>
