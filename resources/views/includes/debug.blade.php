<?php 
	if (DEBUG) {
		// GROUP TYPE
		$debug["Current Group Type"] = Auth::user()->active_contact->group_type->name;
		
		// GROUP
		$debug["Current Group"] = Auth::user()->active_contact->group->name;
		
		// ROLES
		foreach (Auth::user()->active_contact->group->roles as $role) {
			$permissions = [];
			foreach ($role->permissions as $permission) {
				$permissions[] = $permission->name;
			}

			$temp[$role->name] = implode(" | ",$permissions);

		}
		$debug["Allowed Roles"] = $temp;

		var_dump($debug);
	}
?>