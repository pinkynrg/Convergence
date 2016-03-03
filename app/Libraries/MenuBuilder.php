<?php namespace App\Libraries;

use Request;
use Auth;

class MenuBuilder {

	private $menu;

	public static function build() {

		$raw_menus = [
		
			"employee" => [
				['type'=>'group','label'=>'Manage','icon'=>MANAGE_ICON,'menu'=>[
					['type'=>'item','id'=>'tickets','label'=>'Tickets','icon'=>TICKETS_ICON,'link'=>route('tickets.index'),'show'=>Auth::user()->can('read-all-ticket')],
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
				['type'=>'group','label'=>'Statistics','icon'=>STATISTICS_ICON,'menu'=>[	
					['type'=>'item','label'=>'General Statistics','icon'=>STATISTICS_ICON,'link'=>'#', 'show'=>true],
					['type'=>'item','label'=>'Tickets Status','icon'=>STATISTICS_ICON,'link'=>'#', 'show'=>true],
					['type'=>'item','label'=>'Tickets Divisions','icon'=>STATISTICS_ICON,'link'=>'#', 'show'=>true],
					['type'=>'item','label'=>'Customer','icon'=>STATISTICS_ICON,'link'=>'#', 'show'=>true],
					['type'=>'item','label'=>'Date','icon'=>STATISTICS_ICON,'link'=>'#', 'show'=>true],
					['type'=>'item','label'=>'Employee','icon'=>STATISTICS_ICON,'link'=>'#', 'show'=>true],
					['type'=>'item','label'=>'Month','icon'=>STATISTICS_ICON,'link'=>'#', 'show'=>true]
				]]
			],

			"customer" => [
				['type'=>'group','label'=>'Manage','icon'=>MANAGE_ICON,'menu'=>[
					['type'=>'item','id'=>'tickets','label'=>'Tickets','icon'=>TICKETS_ICON,'link'=>route('tickets.index'),'show'=>Auth::user()->can('read-all-ticket')]
				]]
			]
		];

		if (Auth::user()->active_contact->isE80()) {
			$raw_menu = $raw_menus["employee"];
		}
		else {
			$raw_menu = $raw_menus["customer"];
		}

		return self::render($raw_menu);
	}

	static function render($items) {
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
			$object->menu = self::render($item['menu']);
		}
		
		return $object;
	}

	public static function getId($elem) {
		return str_replace(" ","",$elem->label);
	}

	public static function isExpanded($elem) 
	{
		$is_expanded = false;

		if (isset($elem->menu)) {
			foreach ($elem->menu as $elem) {
				$is_expanded = (isset($elem->link) && strpos(Request::url(), $elem->link) !== false) ? true : $is_expanded;
			}
		}

		return $is_expanded;
	}

	public static function isSelected($elem)
	{
		return strpos(Request::url(), $elem->link) !== false;
	}

	public static function showActions($actions) 
	{
		$show = false;
		
		foreach($actions as $action) {
			if ($action['show'] == true) {
				$show = true;
			}
		}

		return $show;
	}
}
?>