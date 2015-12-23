<?php namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Form;

class PermissionsController extends Controller {

	public function index() {
		$data['title'] = "Roles";
		$data['permissions'] = Permission::paginate(50);
		$data['menu_actions'] = [Form::addItem(route('permissions.create'), 'Create new permission')];
		return view('permissions/index',$data);
	}

	public function show($id) {
		$data['permission'] = Permission::find($id);
		$data['title'] = "Permission ".$data['permission']->display_name;
		$data['menu_actions'] = [Form::editItem(route('permissions.edit',$id), 'Edit this permission')];
		return view('permissions/show',$data);
	}

	public function edit($id) {
		$data['permission'] = Permission::find($id);
		$data['title'] = "Update Permission ".$data['permission']->display_name;
		return view('permissions/edit',$data);
	}

	public function update($id, UpdatePermissionRequest $request) {
		$permission = Permission::find($id);
        $permission->update($request->all());
        return redirect()->route('permissions.show',$id);
	}

	public function create() {
		$data['title'] = "Create Permission";
		return view('permissions/create',$data);
	}

	public function store(CreatePermissionRequest $request) {
        $groupType = Permission::create($request->all());
        return redirect()->route('permissions.index');
	}
}

?>
