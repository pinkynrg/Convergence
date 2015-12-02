<?php namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyPerson;
use App\Models\CompanyMainContact;
use App\Models\CompanyAccountManager;
use App\Models\Person;
use App\Models\User;
use App\Models\Department;
use App\Models\Title;
use App\Http\Requests\CreatePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use Request;
use Input;
use Form;

class PeopleController extends Controller {

	public function show($id) {
        $data['menu_actions'] = [
        	Form::editItem(route('people.edit',$id), 'Edit this person'),
			Form::deleteItem('people.destroy', $id, 'Remove this person')
        ];
		$data['person'] = Person::find($id);

		$data['title'] = $data['person']->name() . " - Details";

		return view('people/show', $data);
	}

	public function edit($id) {
		$data['person'] = Person::find($id);
		$data['title'] = $data['person']->name() . " - Edit";

		return view('people/edit', $data);	
	}

	public function update($id, UpdatePersonRequest $request) {
        $employee = Person::find($id);
        $employee->update($request->all());
        return redirect()->route('people.show',$id);
	}

	public function destroy($id) {
		echo 'people destroy method to be created';
	}
}