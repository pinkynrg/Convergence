<?php namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyPerson;
use App\Models\CompanyMainContact;
use App\Models\CompanyAccountManager;
use App\Models\Person;
use App\Models\User;
use App\Models\Department;
use App\Models\Title;
use App\Http\Requests\UpdatePersonRequest;
use Request;
use Auth;
use Input;
use Form;

class PeopleController extends BaseController {

	public function show($id) {
		$data['person'] = Person::find($id);
		if (Auth::user()->can('read-person')) {
	        
	        $data['menu_actions'] = [
	        	Form::editItem(route('people.edit',$id),'Edit This Person',Auth::user()->can('update-person'))
	        ];

	     	if (isset($data['person']->user->id)) {
	        	$data['menu_actions'][] = Form::editItem(route('users.edit',$data['person']->user->id),'Edit Associated User',Auth::user()->can('update-user'));
	     	}
	     	else {
	        	$data['menu_actions'][] = Form::addItem(route('users.create',$data['person']->id),'Create user',Auth::user()->can('create-user'));
	     	}
			
			$data['title'] = $data['person']->name() . " - Details";
			return view('people/show', $data);
		}
		else return redirect()->back()->withErrors(['Access denied to people show page']);
	}

	public function edit($id) {
		$data['person'] = Person::find($id);
		$data['title'] = $data['person']->name() . " - Edit";
		return view('people/edit', $data);	
	}

	public function update($id, UpdatePersonRequest $request) {
        $person = Person::find($id);
        $person->update($request->all());
        return redirect()->route('people.show',$id)->with('successes',['person updated successfully']);
	}
}