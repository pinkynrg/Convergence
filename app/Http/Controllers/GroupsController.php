<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\GroupType;
use App\Models\Group;
use App\Models\Role;
use Form;


class GroupsController extends Controller {

	public function index() {
		$data['title'] = "Groups";
		$data['groups'] = Group::paginate(50);
		$data['menu_actions'] = [Form::addItem(route('groups.create'), 'Create new group')];
		return view('groups/index',$data);
	}

	public function show($id) {
		$data['group'] = Group::find($id);
		$data['title'] = "Group ".$data['group']->display_name;
		$data['menu_actions'] = [Form::editItem(route('groups.edit',$id), 'Edit this group')];
		return view('groups/show',$data);
	}

	public function edit($id) {
		$data['group'] = Group::find($id);
		$data['group_types'] = GroupType::all();
		$data['title'] = "Update Group ".$data['group']->display_name;
		return view('groups/edit',$data);
	}

	public function update($id, UpdateGroupRequest $request) {
		$group = Group::find($id);
        $group->update($request->all());
        return redirect()->route('groups.show',$id);
	}

	public function create() {
		$data['title'] = "Create Group";
		$data['group_types'] = GroupType::all();
		return view('groups/create',$data);
	}

	public function store(CreateGroupRequest $request) {
        $groupType = Group::create($request->all());
        return redirect()->route('groups.index');
	}

	public function groups_roles() {
		$data['title'] = "Associate Roles to Groups";
		$data['groups'] = Group::orderBy('display_name')->paginate(50);
		$data['roles'] = Role::orderBy('display_name')->get();
		return view('groups/groups_roles',$data);
	}
}

?>
