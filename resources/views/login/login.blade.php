<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
</head>
<body>

	<div id="login_panel">

		<!-- E80 Logo -->
		<div id="header" class="row">
			<div id="logo">
				<a href="{{ route('root') }}"><img src="/resources/style/logo-elettric80.png"></a>
			</div>
		</div>
		
		<div id="content">

			<div id="login_thumb">
				<img src="/resources/style/login.png">
			</div>

			<div id="form_wrapper">
				{!! Form::open(array('method' => 'POST', 'route' => array('login.login'))) !!}

					@include('includes.errors')
				
					<div class="form-group">
						<input class="form-control" name="username" type="text" id="username" placeholder="username" autofocus="on">
					</div>
					<div class="form-group">
						<input class="form-control" name="password" type="password" id="password" placeholder="password">
					</div>
					<!-- <div id="remember" class="checkbox">
		                <label>
		                    <input type="checkbox" value="remember-me"> Remember me
		                </label>
		            </div> -->
					
					<div class="form-group">
		            	{!! Form::BSSubmit("Sign in",["id" => "login_btn"]) !!}
		            </div>

		            <!-- <a href="#" class="forgot-password">
		                Forgot the password?
		            </a> -->

		        {!! Form::close() !!}
			</div>
		</div>
	</div>

	@include('includes.footer')

</body>
</html>