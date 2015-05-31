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
	
	public function employees() {
        $data['menu_actions'] = [Form::addItem(route('people.create',1), 'Add employee')];
		$data['active_search'] = true;
		$data['employees'] = CompanyPerson::where('company_id','=',1)->paginate(50);
		return view('people/index/employees',$data);
	}

	public function contacts() {
        $data['menu_actions'] = [Form::addItem(route('people.create',1), 'Add contact')];
		$data['active_search'] = true;
		$data['contacts'] = CompanyPerson::where('company_id','!=',1)->paginate(50);
		return view('people/index/contacts',$data);
	}

	public function show($id) {
        $data['menu_actions'] = [
        	Form::editItem(route('people.edit',$id), 'Edit this person'),
			Form::deleteItem('people.destroy', $id, 'Remove this person')
        ];
		$data['person'] = Person::find($id);
		return view('people/show', $data);
	}

	public function create($id) {
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		$data['company'] = Company::find($id);
		$data['company']->company_id = $data['company']->id;
		return view('people/create', $data);	
	}

	public function store(CreatePersonRequest $request) {
		$person = Person::create($request->all());
		$company_person = new CompanyPerson;
        $company_person->company_id = Input::get('company_id');
        $company_person->person_id = $person->id;
        $company_person->title_id = Input::get('title_id');
        $company_person->department_id = Input::get('department_id');
        $company_person->save();

		if ($person->isE80())
        	return redirect()->route('people.employees');
        else 
        	return redirect()->route('people.contacts');
	}		

	public function edit($id) {
		$data['employee'] = Person::find($id);
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		return view('people/edit', $data);	
	}

	public function update($id, UpdatePersonRequest $request) {
        $employee = Person::find($id);
        $employee->update($request->all());
        return redirect()->route('people.show',$id);
	}

	public function destroy($id) {
		
		$company_main_contact = CompanyMainContact::where('main_contact_id','=',$id);
		$company_account_manager = CompanyAccountManager::where('account_manager_id','=',$id);
		$company_person = CompanyPerson::where('person_id','=',$id);
		$person = Person::find($id);

		$isE80 = $person->isE80();
		
		$company_main_contact->delete();
		$company_account_manager->delete();
		$company_person->delete();
		$person->delete();

		if ($isE80)
			return redirect()->route('people.employees');
		else
			return redirect()->route('people.contacts');
	}

	public function ajaxEmployeesRequest($params = "") {

        parse_str($params,$params);

		$employees = CompanyPerson::select("company_person.*");
		$employees->leftJoin('people','company_person.person_id','=','people.id');
		$employees->leftJoin('titles','company_person.title_id','=','titles.id');
		$employees->leftJoin('departments','company_person.department_id','=','departments.id');
		
		if (isset($params['search'])) {
			$employees->where(function($query) use ($params) {
	            $query->where('last_name','like','%'.$params['search'].'%');
	            $query->orWhere('first_name','like','%'.$params['search'].'%');
			});
		}

        $employees->where('company_person.company_id','=',1);

        // apply ordering
        if (isset($params['order'])) {
    		$employees->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $employees->orderBy($params['order']['column'],$params['order']['type']);
        }

		//paginate
		$employees = $employees->paginate(50);

		$data['employees'] = $employees;

        return view('people/index/employees',$data);
	}

	public function ajaxContactsRequest($params = "") {

        parse_str($params,$params);

		$contacts = CompanyPerson::select("company_person.*");
		$contacts->leftJoin('people','company_person.person_id','=','people.id');
		$contacts->leftJoin('companies','company_person.title_id','=','companies.id');
		$contacts->leftJoin('titles','company_person.title_id','=','titles.id');
		$contacts->leftJoin('departments','company_person.department_id','=','departments.id');
		
		if (isset($params['search'])) {
			$contacts->where(function($query) use ($params) {
	            $query->where('last_name','like','%'.$params['search'].'%');
	            $query->orWhere('first_name','like','%'.$params['search'].'%');
			});
		}

        $contacts->where('company_person.company_id','!=',1);

        // apply ordering
        if (isset($params['order'])) {
    		$contacts->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $contacts->orderBy($params['order']['column'],$params['order']['type']);
        }

		//paginate
		$contacts = $contacts->paginate(50);

		$data['contacts'] = $contacts;

        return view('people/index/contacts',$data);
	}
}