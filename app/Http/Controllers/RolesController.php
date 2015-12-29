<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UpdateRolePermissionsRequest;
use App\Models\Role;
use App\Models\Permission;
use Auth;
use Form;
use DB;

class RolesController extends Controller {

	public function index() {
		if (Auth::user()->can('read-all-role')) {
			$data['title'] = "Roles";
			$data['roles'] = Role::paginate(50);
			$data['menu_actions'] = [Form::addItem(route('roles.create'), 'Create new role')];
			return view('roles/index',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to roles index page']);
	}

	public function show($id) {
		if (Auth::user()->can('read-role')) {

			$data['role'] = Role::find($id);
			$data['title'] = "Role \"".$data['role']->display_name."\"";
			$data['menu_actions'] = [Form::editItem(route('roles.edit',$id), 'Edit this role')];
			
			$permissions = Permission::get();
	    	
	    	$permissions_in_role = Permission::whereHas('roles', function($q) use ($id) {
	    		$q->where('id', $id);
			})->get();

	    	$counter = 0;

			foreach ($permissions as $permission) {

				$is_in_role = false;
				
				foreach($permissions_in_role as $permission_in_role) {
					if ($permission->id == $permission_in_role->id) {
						$is_in_role = true;
					}
				}

				$data['permissions'][$counter] = $permission;
				$data['permissions'][$counter]['is_in_role'] = $is_in_role;

				$counter++;
			}

			return view('roles/show',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to roles show page']);		
	}

	public function edit($id) {
		$data['role'] = Role::find($id);
		$data['title'] = "Update Role ".$data['role']->display_name;
		return view('roles/edit',$data);
	}

	public function update($id, UpdateRoleRequest $request) {
		$role = Role::find($id);
        $role->update($request->all());
        return redirect()->route('roles.show',$id)->with('successes',['Role updated successfully']);
	}

	public function create() {
		$data['title'] = "Create Role";
		return view('roles/create',$data);
	}

	public function store(CreateRoleRequest $request) {
        $groupType = Role::create($request->all());
        return redirect()->route('roles.index')->with('successes',['Role created successfully']);
	}

	public function updateRolePermissions($id, UpdateRolePermissionsRequest $request) {
		DB::table('permission_role')->where('role_id', $id)->delete();
		Role::find($id)->permissions()->attach($request['permissions']);
        return redirect()->route('roles.show',$id)->with('successes',['Role persmissions updated successfully']);
	}
}

?>
