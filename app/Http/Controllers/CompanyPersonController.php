<?php namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\CompanyPerson;
use App\Models\Department;
use App\Models\Title;
use App\Models\Group;
use App\Models\GroupType;
use App\Models\Company;
use App\Http\Requests\CreateCompanyPersonRequest;
use App\Http\Requests\UpdateCompanyPersonRequest;
use Input;
use Form;
use DB;
use Auth;

class CompanyPersonController extends Controller {

	public function employees() {
		if (Auth::user()->can('read-all-employee')) {
	        $data['menu_actions'] = [Form::addItem(route('company_person.create',1), 'Add employee')];
			$data['active_search'] = true;
			$data['employees'] = CompanyPerson::where('company_id','=',Auth::user()->active_contact->company_id)->paginate(50);
	        $data['title'] = "Employees";
			return view('company_person/index/employees',$data);
		}
        else return redirect()->back()->withErrors(['Access denied to employees index page']);		
	}

	public function contacts() {
		if (Auth::user()->can('read-all-contact')) {
			$data['active_search'] = true;
			$data['contacts'] = CompanyPerson::where('company_id','!=',1)->paginate(50);
        	$data['title'] = "Customer Contacts";
			return view('company_person/index/contacts',$data);
        }
        else return redirect()->back()->withErrors(['Access denied to contacts index page']);		
	}

	public function show($id) {
		if ((CompanyPerson::find($id)->company_id == 1 && Auth::user()->can('read-employee')) || (CompanyPerson::find($id)->company_id != 1 && Auth::user()->can('read-contact'))) {
			$data['menu_actions'] = [
	        	Form::editItem(route('company_person.edit',$id), 'Edit this contact'),
				Form::deleteItem('company_person.destroy', $id, 'Remove this contact')
	        ];
			$data['company_person'] = CompanyPerson::find($id);

	        $data['title'] = $data['company_person']->person->name() . " @ " . $data['company_person']->company->name;

			return view('company_person/show', $data);
		}
		else return CompanyPerson::find($id)->company_id == 1 ? redirect()->back()->withErrors(['Access denied to employees show page']) : redirect()->back()->withErrors(['Access denied to company contacts show page']);

	}

	public function create($id) {
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		$data['company'] = Company::find($id);
		$data['company']->company_id = $data['company']->id;
		$data['group_types'] = GroupType::all();

		$data['title'] = "Create Contact";

		return view('company_person/create', $data);	
	}

	public function store(CreateCompanyPersonRequest $request) {
		
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
        $contact->group_type_id = Input::get('group_type_id');
        $contact->email = Input::get('email');
        $contact->save();

        $message = Input::get('company_id') == 1 ? 'Employee created successfully' : 'Company Contact created successfully';
        $route = Input::get('company_id') == 1 ? 'company_person.employees' : 'companies.show';

		return redirect()->route($route,Input::get('company_id'))->with('successes',[$message]);

	}	

	public function edit($id) {
		$company_person = CompanyPerson::find($id);
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();		
		$data['contact'] = CompanyPerson::find($id);
		$data['groups'] = Group::where('group_type_id','=',$company_person->group_type_id)->get();

		$data['title'] = "Edit Contact";

		return view('company_person/edit', $data);	
	}	

	public function update($id, UpdateCompanyPersonRequest $request) {
		$contact = CompanyPerson::find($id);
		$contact->update($request->all());

 		$message = $contact->company_id == 1 ? 'Employee updated successfully' : 'Company Contact updated successfully';

		return redirect()->route('company_person.show',$id)->with('successes',[$message]);
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