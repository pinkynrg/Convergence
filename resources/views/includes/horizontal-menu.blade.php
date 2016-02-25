<nav class="navbar navbar-default">

	<div class="container-fluid">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span> <!-- hidden label for avoiding troubles with form -->
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<span class="navbar-brand"> <a href="/"><b>ConVergence</b></a> </span> 
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<ul class="nav navbar-nav">

				@foreach (Menu::build() as $elem)

					@if (isset($elem->menu)) 

						@if ($elem->show)

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
									<span class="icon">{!! $elem->icon !!}</span> {!! $elem->label !!} <span class="caret"></span></a>
          						<ul class="dropdown-menu">

          							@foreach ($elem->menu as $subelem)

          								@if ($subelem->show)

											<li><a href="{{ $subelem->link }}"> <span class="icon">{!! $subelem->icon !!}</span> {!! $subelem->label !!} </a></li>

										@endif

          							@endforeach

            					</ul>
							</li>

						@endif

					@else

						@if ($elem->show)

							@if (Request::url() === $elem->link)
								<li class="active">
									<a href="{{ $elem->link }}"> {!! $elem->icon !!} {!! $elem->label !!} <span class="sr-only">(current)</span> </a>
								</li> 	
							@else 
								<li><a href="{{ $elem->link }}"> {!! $elem->icon !!} {!! $elem->label !!} <span class="sr-only">(current)</span> </a></li>
							@endif

						@endif

					@endif

				@endforeach

			</ul>

			@if (isset($menu_actions) && count($menu_actions))

				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> 
						<span class="icon"><i class="fa fa-list"></i></span> Actions <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">

							@foreach ($menu_actions as $menu_action)

				  				<li><a href="{{ $menu_action['link'] }}">{!! $menu_action['icon'] !!} {{ $menu_action['label'] }}</a></li>

							@endforeach

						</ul>
					</li>
				</ul>

			@endif

		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
