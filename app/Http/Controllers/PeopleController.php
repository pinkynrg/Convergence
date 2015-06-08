<?php namespace Convergence\Http\Controllers;

use Convergence\Models\Company;
use Convergence\Models\CompanyPerson;
use Convergence\Models\CompanyMainContact;
use Convergence\Models\CompanyAccountManager;
use Convergence\Models\Person;
use Convergence\Models\Department;
use Convergence\Models\Title;
use Convergence\Http\Requests\CreatePersonRequest;
use Convergence\Http\Requests\UpdatePersonRequest;
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
		return view('people/show', $data);
	}

	public function edit($id) {
		$data['employee'] = Person::find($id);
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