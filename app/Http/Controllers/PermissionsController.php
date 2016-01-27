<?php namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Form;
use Auth;

class PermissionsController extends Controller {

	public function index() {
		if (Auth::user()->can('read-all-permission')) {
			$data['active_search'] = true;
			$data['title'] = "Permissions";
			$data['permissions'] = Permission::paginate(50);
			$data['menu_actions'] = [Form::addItem(route('permissions.create'), 'Create new permission')];
			return view('permissions/index',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to permissions index page']);
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

	public function ajaxPermissionsRequest($params = "") {
		
        parse_str($params,$params);

		$permissions = Permission::select("permissions.*");

		// apply search
        if (isset($params['search'])) {
            $permissions->where('name','like','%'.$params['search'].'%');
        }

        // apply ordering
        if (isset($params['order'])) {
    		$permissions->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $permissions->orderBy($params['order']['column'],$params['order']['type']);
        }

		$permissions = $permissions->paginate(50);

		$data['permissions'] = $permissions;

        return view('permissions/permissions',$data);
	}
}

?>
