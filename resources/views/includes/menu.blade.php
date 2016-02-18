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
		$object->icon = isset($item['icon']) ? "<i class='".$item['icon']."'></i>" : "<i class='".MISSING_ICON."'></i>";
		
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
	['type'=>'group','label'=>'Manage','icon'=>MANAGE_ICON,'menu'=>[
		['type'=>'item','label'=>'Tickets','icon'=>TICKETS_ICON,'link'=>route('tickets.index'),'show'=>Auth::user()->can('read-all-ticket')],
		['type'=>'item','label'=>'Companies','icon'=>COMPANIES_ICON,'link'=>route('companies.index'),'show'=>Auth::user()->can('read-all-company')],
		['type'=>'item','label'=>'Contacts','icon'=>CONTACTS_ICON,'link'=>route('company_person.index'),'show'=>Auth::user()->can('read-all-contact')],
		['type'=>'item','label'=>'Users','icon'=>USERS_ICON,'link'=>route('users.index'),'show'=>Auth::user()->can('read-all-user')],
		['type'=>'item','label'=>'Equipment','icon'=>EQUIPMENT_ICON,'link'=>route('equipment.index'),'show'=>Auth::user()->can('read-all-equipment')],
		['type'=>'item','label'=>'Services','icon'=>SERVICES_ICON,'link'=>route('services.index'),'show'=>Auth::user()->can('read-all-service')],
		['type'=>'item','label'=>'Escalation Profiles','icon'=>ESCALATIONS_ICON,'link'=>route('escalation_profiles.index'),'show'=>Auth::user()->can('read-all-escalation-profiles')]

	]],
	['type'=>'group','label'=>'Access','icon'=>ACCESS_ICON,'menu'=>[
		['type'=>'item','label'=>'Permissions','icon'=>PERMISSIONS_ICON,'link'=>route('permissions.index'),'show'=>Auth::user()->can('read-all-permission')],
		['type'=>'item','label'=>'Roles','icon'=>ROLES_ICON,'link'=>route('roles.index'),'show'=>Auth::user()->can('read-all-role')],
		['type'=>'item','label'=>'Groups','icon'=>USERS_ICON,'link'=>route('groups.index'),'show'=>Auth::user()->can('read-all-group')],
		['type'=>'item','label'=>'Groups Types','icon'=>GROUP_TYPES_ICON,'link'=>route('group_types.index'),'show'=>Auth::user()->can('read-all-group-type')]
	]],
	['type'=>'group','label'=>'Info','icon'=>INFO_ICON,'menu'=>[	
		['type'=>'item','label'=>'Dashboard','icon'=>DASHBOARD_ICON,'link'=>route('dashboard.logged'), 'show'=>true],
		['type'=>'item','label'=>'Statistics','icon'=>STATISTICS_ICON,'link'=>route('statistics.index'), 'show'=>true]
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
