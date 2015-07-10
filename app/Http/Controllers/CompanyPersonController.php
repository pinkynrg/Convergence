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
use DB;


class CompanyPersonController extends Controller {

	public function employees() {
        $data['menu_actions'] = [Form::addItem(route('company_person.create',1), 'Add employee')];
		$data['active_search'] = true;
		$data['employees'] = CompanyPerson::where('company_id','=',1)->paginate(50);

        $data['title'] = "Employees";

		return view('company_person/index/employees',$data);
	}

	public function contacts() {
        $data['menu_actions'] = [Form::addItem(route('company_person.create',1), 'Add contact')];
		$data['active_search'] = true;
		$data['contacts'] = CompanyPerson::where('company_id','!=',1)->paginate(50);

        $data['title'] = "Contacts";

		return view('company_person/index/contacts',$data);
	}

	public function show($id) {
        $data['menu_actions'] = [
        	Form::editItem(route('company_person.edit',$id), 'Edit this contact'),
			Form::deleteItem('company_person.destroy', $id, 'Remove this contact')
        ];
		$data['company_person'] = CompanyPerson::find($id);

        $data['title'] = $data['company_person']->person->name() . " @ " . $data['company_person']->company->name;

		return view('company_person/show', $data);
	}

	public function create($id) {
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		$data['company'] = Company::find($id);
		$data['company']->company_id = $data['company']->id;

		$data['title'] = "Create Contact";

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

		$data['title'] = "Edit Contact";

		return view('company_person/edit', $data);	
	}	

	public function update($id, UpdateCompanyPersonRequest $request) {
		$contact = CompanyPerson::find($id);
		$contact->update($request->all());
		return redirect()->route('company_person.show',$id);
	}

	public function destroy($id) {
		echo 'company person method to be implmented';
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

        return view('company_person/employees',$data);
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
		$data['contacts'] = $contacts->paginate(50);

        return view('company_person/contacts',$data);
	}

	public function ajaxPeopleRequest() {

		$query = Input::get('query');

		$people = Person::select(DB::raw('people.first_name as value'), 'people.id', 'people.first_name', 'people.last_name', 'companies.name as company_name')
						->leftJoin('company_person','company_person.person_id','=','people.id')
						->leftJoin('companies','companies.id','=','company_person.company_id')
						->where('people.first_name','LIKE','%'.$query.'%')
						->orWhere('people.last_name','LIKE','%'.$query.'%')
						->get();

		$result['query'] = "Unit";
		$result['suggestions'] = $people;

		$result = (object) $result;

		return json_encode($result);

	}
}

?>