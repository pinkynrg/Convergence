<?php namespace Convergence\Http\Controllers;

use Convergence\Http\Requests\CreateRoleRequest;
use Convergence\Http\Requests\UpdateRoleRequest;
use Convergence\Models\Role;
use Convergence\Models\Permission;
use Form;

class RolesController extends Controller {

	public function index() {
		$data['title'] = "Roles";
		$data['roles'] = Role::paginate(50);
		$data['menu_actions'] = [Form::addItem(route('roles.create'), 'Create new role')];
		return view('roles/index',$data);
	}

	public function show($id) {
		$data['role'] = Role::find($id);
		$data['title'] = "Role ".$data['role']->display_name;
		$data['menu_actions'] = [Form::editItem(route('roles.edit',$id), 'Edit this role')];
		return view('roles/show',$data);
	}

	public function edit($id) {
		$data['role'] = Role::find($id);
		$data['title'] = "Update Role ".$data['role']->display_name;
		return view('roles/edit',$data);
	}

	public function update($id, UpdateRoleRequest $request) {
		$role = Role::find($id);
        $role->update($request->all());
        return redirect()->route('roles.show',$id);
	}

	public function create() {
		$data['title'] = "Create Role";
		return view('roles/create',$data);
	}

	public function store(CreateRoleRequest $request) {
        $groupType = Role::create($request->all());
        return redirect()->route('roles.index');
	}

	public function roles_permissions() {
		$data['title'] = "Associate Permissions to Roles";
		$data['roles'] = Role::orderBy('display_name')->get();
		$data['permissions'] = Permission::orderBy('display_name')->paginate(50);
		return view('roles/roles_permissions',$data);
	}
}

?>
