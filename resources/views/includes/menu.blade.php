<?php $menu_voices = ['<i class="fa fa-users"></i> Companies' => route('companies.index'),
				'<i class="fa fa-suitcase"></i> Employees' => route('company_person.employees'),
				'<i class="fa fa-gear"></i> Equipments' => route('equipments.index'),
				'<i class="fa fa-ticket"></i> Tickets' => route('tickets.index'),
				'<i class="fa fa-tasks"></i> Queues' => '#',
				'<i class="fa fa fa-line-chart"></i> Statistics' => '#'];?>

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
			<span class="navbar-brand"> <b>ConVergence</b> </span> 
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<ul class="nav navbar-nav">

				@if (isset($menu_voices))
					
					@foreach ($menu_voices as $label => $link)

					@if (Request::url() === $link)
						<li class="active">
							<a href="{{ $link }}"> {!! $label !!} <span class="sr-only">(current)</span> </a>
						</li> 	
					@else 
						<li><a href="{{ $link }}"> {!! $label !!} <span class="sr-only">(current)</span> </a></li>
					@endif

					@endforeach

				@endif

			</ul>

			@if (isset($active_search))

				<div class="navbar-form navbar-right" role="search">
					<div class="form-group">
						<input type="text" class="form-control search" placeholder="search">
					</div>
				</div>

			@endif

			@if (isset($menu_actions) && count($menu_actions))

				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Actions <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">

							@foreach ($menu_actions as $menu_action)

				  				<li>{!! $menu_action !!}</li>

							@endforeach

						</ul>
					</li>
				</ul>

			@endif

		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
