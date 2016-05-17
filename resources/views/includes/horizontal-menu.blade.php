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
			<span class="navbar-brand"> <a href="/"><b>Convergence</b></a> </span> 
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<ul class="nav navbar-nav">

				@foreach (Menu::build() as $elem)

					@if (isset($elem->menu)) 

						@if ($elem->show)

							@if(Menu::isExpanded($elem))
								<li class="dropdown active">
							@else 
								<li class="dropdown">
							@endif
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
									<span class="icon">{!! $elem->icon !!}</span> {!! $elem->label !!} <span class="caret"></span></a>
          						<ul class="dropdown-menu">

          							@foreach ($elem->menu as $subelem)

          								@if ($subelem->show)

          									@if(Menu::isSelected($subelem))
												<li class="active">
											@else
												<li>											
											@endif
												<a href="{{ $subelem->link }}"> <span class="icon"> {!! $subelem->icon !!}</span> {!! $subelem->label !!} <span class="sr-only">(current)</span> </a>
											</li>

										@endif

          							@endforeach

            					</ul>
							</li>

						@endif

					@else

						@if ($elem->show)

							@if(Menu::isSelected($elem))
								<li class="active">
							@else
								<li>											
							@endif
								<a href="{{ $elem->link }}"> <span class="icon"> {!! $elem->icon !!} </span> {!! $elem->label !!} <span class="sr-only">(current)</span> </a>
							</li> 	

						@endif

					@endif

				@endforeach

			</ul>

			@if (isset($menu_actions) && count($menu_actions) && Menu::showActions($menu_actions))

				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> 
						<span class="icon"><i class="fa fa-list"></i></span> Actions <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">

							@foreach ($menu_actions as $menu_action)

								@if ($menu_action['show'])

				  					<li><a href="{{ $menu_action['link'] }}">{!! $menu_action['icon'] !!} {{ $menu_action['label'] }}</a></li>

								@endif

							@endforeach

						</ul>
					</li>
				</ul>

			@endif

		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
