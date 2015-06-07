<?php namespace Convergence\Http\Controllers;

use Convergence\Models\Person;
use Convergence\Models\CompanyPerson;
use Convergence\Models\Department;
use Convergence\Models\Title;
use Convergence\Models\Company;
use Convergence\Http\Requests\CreateCompanyPersonRequest;
use Convergence\Http\Requests\UpdateCompanyPersonRequest;
use Input;
use Form;

class CompanyPersonController extends Controller {

	public function employees() {
        $data['menu_actions'] = [Form::addItem(route('company_person.create',1), 'Add employee')];
		$data['active_search'] = true;
		$data['employees'] = CompanyPerson::where('company_id','=',1)->paginate(50);
		return view('company_person/index/employees',$data);
	}

	public function contacts() {
        $data['menu_actions'] = [Form::addItem(route('company_person.create',1), 'Add contact')];
		$data['active_search'] = true;
		$data['contacts'] = CompanyPerson::where('company_id','!=',1)->paginate(50);
		return view('company_person/index/contacts',$data);
	}

	public function show($id) {
        $data['menu_actions'] = [
        	Form::editItem(route('company_person.edit',$id), 'Edit this contact'),
			Form::deleteItem('company_person.destroy', $id, 'Remove this contact')
        ];
		$data['company_person'] = CompanyPerson::find($id);
		return view('company_person/show', $data);
	}

	public function create($id) {
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		$data['company'] = Company::find($id);
		$data['company']->company_id = $data['company']->id;
		return view('company_person/create', $data);	
	}

	public function store() {
		
		if (!Input::get('person_id')) {
			$person = new Person;
			$person->first_name = Input::get('person_fn');
			$person->last_name = Input::get('person_ln');
			$person->save();
		}

		$contact = new CompanyPerson;
        $contact->company_id = Input::get('company_id');
        $contact->person_id = Input::get('person_id') ? Input::get('person_id') : $person->id;
        $contact->title_id = Input::get('title_id');
        $contact->department_id = Input::get('department_id');
        $contact->phone = Input::get('phone');
        $contact->extension = Input::get('extension');
        $contact->cellphone = Input::get('cellphone');
        $contact->email = Input::get('email');
        $contact->save();

		return redirect()->route('companies.show',Input::get('company_id'));

	}	

	public function edit($id) {
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();		
		$data['contact'] = CompanyPerson::find($id);
		return view('company_person/edit', $data);	
	}	

	public function update($id, UpdateCompanyPersonRequest $request) {
		$contact = CompanyPerson::find($id);
		$contact->update($request->all());
		return redirect()->route('company_person.show',$id);
	}

	public function destroy($id) {
		echo 'company person method to be implmented';
		// return redirect()->route('company_person.show',$id);
	}

}

?>