<?php namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Request;
use Form;
use Auth;

class PermissionsController extends BaseController {

	public function index() {
		if (Auth::user()->can('read-all-permission')) {
			return parent::index();
		}
		else return redirect()->back()->withErrors(['Access denied to permissions index page']);
	}

	protected function main() {
		$params = Request::input();
        $data['permissions'] = self::api($params);
		$data['active_search'] = implode(",",['display_name','name','description']);
		$data['title'] = "Permissions";
		$data['menu_actions'] = [Form::addItem(route('permissions.create'), 'Create new permission')];
		return view('permissions/index',$data);
	}

	protected function html() {
		$params = Request::input();
        $data['permissions'] = self::api($params);
        return view('permissions/permissions',$data);
	}

	public function show($id) {
		if (Auth::user()->can('read-permission')) {
			$data['permission'] = Permission::find($id);
			$data['title'] = "Permission \"".$data['permission']->display_name."\"";
			$data['menu_actions'] = [Form::editItem(route('permissions.edit',$id), 'Edit this permission')];
			return view('permissions/show',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to permissions show page']);
	}

	public function edit($id) {
		$data['permission'] = Permission::find($id);
		$data['title'] = "Update Permission \"".$data['permission']->display_name."\"";
		return view('permissions/edit',$data);
	}

	public function update($id, UpdatePermissionRequest $request) {
		$permission = Permission::find($id);
        $permission->update($request->all());
        return redirect()->route('permissions.show',$id)->with('successes',['Permission updated successfully']);;
	}

	public function create() {
		$data['title'] = "Create Permission";
		return view('permissions/create',$data);
	}

	public function store(CreatePermissionRequest $request) {
        $groupType = Permission::create($request->all());
        return redirect()->route('permissions.index')->with('successes',['Permission created successfully']);;
	}
}

?>
