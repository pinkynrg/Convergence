<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Requests\UpdateGroupRolesRequest;
use App\Models\GroupType;
use App\Models\Group;
use App\Models\Role;
use Request;
use Form;
use Auth;
use DB;

class GroupsController extends BaseController {

	public function index() {
		if (Auth::user()->can('read-all-group')) {
	        $data['groups'] = self::API()->all(Request::input());
			$data['title'] = "Groups";
			$data['active_search'] = implode(",",['display_name','name','description']);
			$data['menu_actions'] = [Form::addItem(route('groups.create'), 'Create new group')];
            return Request::ajax() ? view('groups/groups',$data) : view('groups/index',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to groups index page']);
	}

	public function show($id) {
		if (Auth::user()->can('read-group')) {
			$data['group'] = Group::find($id);
			$data['title'] = "Group \"".$data['group']->display_name."\"";

			$roles = Role::get();
	    	
	    	$roles_in_group = Role::whereHas('groups', function($q) use ($id) {
	    		$q->where('groups.id', $id);
			})->get();

	    	$counter = 0;

			foreach ($roles as $role) {

				$is_in_group = false;
				
				foreach($roles_in_group as $role_in_group) {
					if ($role->id == $role_in_group->id) {
						$is_in_group = true;
					}
				}

				$data['roles'][$counter] = $role;
				$data['roles'][$counter]['is_in_group'] = $is_in_group;

				$counter++;
			}

			$data['menu_actions'] = [Form::editItem(route('groups.edit',$id), 'Edit this group')];
			return view('groups/show',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to groups show page']);
	}

	public function edit($id) {
		$data['group'] = Group::find($id);
		$data['group_types'] = GroupType::all();
		$data['title'] = "Update Group \"".$data['group']->display_name."\"";
		return view('groups/edit',$data);
	}

	public function update($id, UpdateGroupRequest $request) {
		$group = Group::find($id);
        $group->update($request->all());
        return redirect()->route('groups.show',$id)->with('successes',['Group updated successfully']);;
	}

	public function create() {
		$data['title'] = "Create Group";
		$data['group_types'] = GroupType::all();
		return view('groups/create',$data);
	}

	public function store(CreateGroupRequest $request) {
        $groupType = Group::create($request->all());
        return redirect()->route('groups.index')->with('successes',['Group created successfully']);;
	}

	public function groups_roles() {
		$data['title'] = "Associate Roles to Groups";
		$data['groups'] = Group::orderBy('display_name')->paginate(PAGINATION);
		$data['roles'] = Role::orderBy('display_name')->get();
		return view('groups/groups_roles',$data);
	}

	public function updateGroupRoles($id, UpdateGroupRolesRequest $request) {
		DB::table('group_role')->where('group_id', $id)->delete();
		Group::find($id)->roles()->attach($request['roles']);
        return redirect()->route('groups.show',$id)->with('successes',['Group roles updated successfully']);
	}
}

?>
