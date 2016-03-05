<?php 

	if (Session::get('debug') == true) {

		Debugbar::enable();

		// GROUP TYPE
		Debugbar::info(Auth::user()->active_contact->group_type->name);
		
		// GROUP
		Debugbar::info(Auth::user()->active_contact->group->name);
		
		// ROLES
		foreach (Auth::user()->active_contact->group->roles as $role) {
			$permissions = [];
			foreach ($role->permissions as $permission) {
				$permissions[] = $permission->name;
			}

			Debugbar::info(implode(" | ",$permissions)); 
		}
	}

	else {
		Debugbar::disable();
	}
?>