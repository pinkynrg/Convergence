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
		if (Auth::user()->can('read-person')) {
			$person = Person::find($id);
			$id = Auth::user()->owner->id == $id ? Auth::user()->active_contact : $person->company_person[0]->id;
			return redirect()->route('company_person.show',$id);
		}
		else return redirect()->back()->withErrors(['Access denied to people show page']);
	}

	public function edit($id) {
		if (Auth::user()->can('update-person')) {
			$data['person'] = Person::find($id);
			$data['title'] = $data['person']->name() . " - Edit";
			return view('people/edit', $data);	
		}
		else return redirect()->back()->withErrors(['Access denied to people edit page']);
	}

	public function update($id, UpdatePersonRequest $request) {
        $person = Person::find($id);
        $person->update($request->all());
        return redirect()->route('people.show',$id)->with('successes',['person updated successfully']);
	}
}