<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateGroupTypeRequest;
use App\Http\Requests\UpdateGroupTypeRequest;
use App\Models\GroupType;
use Request;
use Auth;
use Form;


class GroupTypesController extends BaseController {

	public function index() {
		if (Auth::user()->can('read-all-group-type')) {
	        $data['group_types'] = self::API()->all(Request::input());
			$data['title'] = "Group Types";
			$data['active_search'] = implode(",",['display_name','name','description']);
			$data['menu_actions'] = [Form::addItem(route('group_types.create'), 'Create new group type')];
            return Request::ajax() ? view('group_types/group_types',$data) : view('group_types/index',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to group types index page']);
	}
	
	public function show($id) {
		if (Auth::user()->can('read-all-group-type')) {
			$data['group_type'] = GroupType::find($id);
			$data['title'] = "Group Type \"".$data['group_type']->display_name."\"";
			$data['menu_actions'] = [Form::editItem(route('group_types.edit',$id), 'Edit this group type')];
			return view('group_types/show',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to group types show page']);
	}

	public function edit($id) {
		$data['group_type'] = GroupType::find($id);
		$data['title'] = "Update Group Type \"".$data['group_type']->display_name."\"";
		return view('group_types/edit',$data);
	}

	public function update($id, UpdateGroupTypeRequest $request) {
		$groupType = GroupType::find($id);
        $groupType->update($request->all());
        return redirect()->route('group_types.show',$id)->with('successes',['Group Type updated successfully']);;
	}

	public function create() {
		$data['title'] = "Create Group Type";
		return view('group_types/create',$data);
	}

	public function store(CreateGroupTypeRequest $request) {
        $groupType = GroupType::create($request->all());
        return redirect()->route('group_types.index')->with('successes',['Group Type created successfully']);;
	}
}

?>
