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
	        $data['permissions'] = self::API()->all(Request::input());
			$data['active_search'] = implode(",",['display_name','name','description']);
			$data['title'] = "Permissions";
			$data['menu_actions'] = [Form::addItem(route('permissions.create'), 'Create new permission',Auth::user()->can('create-permission'))];
			return Request::ajax() ? view('permissions/permissions',$data) : view('permissions/index',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to permissions index page']);
	}

	public function show($id) {
		if (Auth::user()->can('read-permission')) {
			$data['permission'] = Permission::find($id);
			$data['title'] = "Permission \"".$data['permission']->display_name."\"";
			$data['menu_actions'] = [Form::editItem(route('permissions.edit',$id), 'Edit This Permission',Auth::user()->can('update-permission'))];
			return view('permissions/show',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to permissions show page']);
	}

	public function edit($id) {
		if (Auth::user()->can('update-permission')) {
			$data['permission'] = Permission::find($id);
			$data['title'] = "Update Permission \"".$data['permission']->display_name."\"";
			return view('permissions/edit',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to permissions edit page']);
	}

	public function update($id, UpdatePermissionRequest $request) {
		$permission = Permission::find($id);
        $permission->update($request->all());
        return redirect()->route('permissions.show',$id)->with('successes',['Permission updated successfully']);;
	}

	public function create() {
		if (Auth::user()->can('create-permission')) {
			$data['title'] = "Create Permission";
			return view('permissions/create',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to permissions create page']);
	}

	public function store(CreatePermissionRequest $request) {
        $groupType = Permission::create($request->all());
        return redirect()->route('permissions.index')->with('successes',['Permission created successfully']);;
	}
}

?>
