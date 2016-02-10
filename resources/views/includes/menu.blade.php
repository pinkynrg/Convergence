<?php 

class Menu {

	private $menu;

	static function items($items) {
		foreach ($items as $item) {
			$menu[] = self::add($item);
		}
		return $menu;
	}

	static function add($item) {
		$object = new \StdClass();
		$object->label = isset($item['label']) ? $item['label'] : 'label-missing';
		$object->icon = isset($item['icon']) ? "<i class='fa ".$item['icon']."'></i>" : "<i class='fa fa-question-circle'></i>";
		
		if ($item['type'] == 'item') {
			
			if (isset($item['show'])) {
				$object->show = $item['show'];
			}
			else {
				$object->show = true;
				$object->label .= ' [permission not defined]';
			}

			if (isset($item['link'])) {
				$object->link = $item['link'];
			}
			else {
				$object->label .= ' [link not defined]';
			}
		}

		elseif ($item['type'] == 'group') {
			$show = false;
			foreach ($item['menu'] as $elem) {
				if (!isset($elem['show']) || $elem['show'] == true) $show = true;
			}
			$object->show = $show;
			$object->menu = self::items($item['menu']);
		}
		
		return $object;
	}
}

$main = Menu::items([
	['type'=>'item','label'=>'Tickets','icon'=>'fa-ticket','link'=>route('tickets.index'),'show'=>Auth::user()->can('read-all-ticket')],
	['type'=>'group','label'=>'Manage','icon'=>'fa-cog','menu'=>[
		['type'=>'item','label'=>'Companies','icon'=>'fa-building','link'=>route('companies.index'),'show'=>Auth::user()->can('read-all-company')],
		['type'=>'item','label'=>'Contacts','icon'=>'fa-book','link'=>route('company_person.index'),'show'=>Auth::user()->can('read-all-contact')],
		['type'=>'item','label'=>'Users','icon'=>'fa-book','link'=>route('users.index'),'show'=>Auth::user()->can('read-all-user')],
		['type'=>'item','label'=>'Equipment','icon'=>'fa-wrench','link'=>route('equipment.index'),'show'=>Auth::user()->can('read-all-equipment')],
		['type'=>'item','label'=>'Services','icon'=>'fa-server','link'=>route('services.index'),'show'=>Auth::user()->can('read-all-service')],
		['type'=>'item','label'=>'Escalation Profiles','icon'=>'fa-bolt','link'=>route('escalation_profiles.index'),'show'=>Auth::user()->can('read-all-escalation-profiles')]

	]],
	['type'=>'group','label'=>'Access','icon'=>'fa-cog','menu'=>[
		['type'=>'item','label'=>'Permissions','icon'=>'fa fa-unlock','link'=>route('permissions.index'),'show'=>Auth::user()->can('read-all-permission')],
		['type'=>'item','label'=>'Roles','icon'=>'fa-male','link'=>route('roles.index'),'show'=>Auth::user()->can('read-all-role')],
		['type'=>'item','label'=>'Groups','icon'=>'fa-users','link'=>route('groups.index'),'show'=>Auth::user()->can('read-all-group')],
		['type'=>'item','label'=>'Groups Types','icon'=>'fa-bars','link'=>route('group_types.index'),'show'=>Auth::user()->can('read-all-group-type')]
	]],
	['type'=>'group','label'=>'Info','icon'=>'fa-cog','menu'=>[	
		['type'=>'item','label'=>'Dashboard','icon'=>'fa-dashboard','link'=>route('dashboard.logged'), 'show'=>true],
		['type'=>'item','label'=>'Statistics','icon'=>'fa-line-chart','link'=>route('statistics.index'), 'show'=>true]
	]]
]);

?>

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

				@if (isset($main))
					
					@foreach ($main as $elem)

						@if (isset($elem->menu)) 

							@if ($elem->show)

								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> {!! $elem->icon !!} {!! $elem->label !!} <span class="caret"></span></a>
	          						<ul class="dropdown-menu">

	          							@foreach ($elem->menu as $subelem)

	          								@if ($subelem->show)

												<li><a href="{{ $subelem->link }}"> {!! $subelem->icon !!} {!! $subelem->label !!} </a></li>

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

				@endif

			</ul>

			@if (isset($active_search))

				<div class="navbar-form navbar-right" role="search">
					<div class="form-group">
						<input type="text" columns="{{$active_search}}" class="form-control search" placeholder="search">
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
