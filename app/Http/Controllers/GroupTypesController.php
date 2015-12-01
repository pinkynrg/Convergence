<?php namespace Convergence\Http\Controllers;

use Convergence\Http\Requests\CreateGroupTypeRequest;
use Convergence\Http\Requests\UpdateGroupTypeRequest;
use Convergence\Models\GroupType;
use Form;


class GroupTypesController extends Controller {

	public function index() {
		$data['title'] = "Group Types";
		$data['group_types'] = GroupType::paginate(50);
		$data['menu_actions'] = [Form::addItem(route('group_types.create'), 'Create new group type')];
		return view('group_types/index',$data);
	}

	public function show($id) {
		$data['group_type'] = GroupType::find($id);
		$data['title'] = "Group Type ".$data['group_type']->display_name;
		$data['menu_actions'] = [Form::editItem(route('group_types.edit',$id), 'Edit this group type')];
		return view('group_types/show',$data);
	}

	public function edit($id) {
		$data['group_type'] = GroupType::find($id);
		$data['title'] = "Update Group Type ".$data['group_type']->display_name;
		return view('group_types/edit',$data);
	}

	public function update($id, UpdateGroupTypeRequest $request) {
		$groupType = GroupType::find($id);
        $groupType->update($request->all());
        return redirect()->route('group_types.show',$id);
	}

	public function create() {
		$data['title'] = "Create Group Type";
		return view('group_types/create',$data);
	}

	public function store(CreateGroupTypeRequest $request) {
        $groupType = GroupType::create($request->all());
        return redirect()->route('group_types.index');
	}
}

?>
