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
use Request;
use Input;
use Form;
use DB;
use Auth;

class CompanyPersonController extends BaseController {

	public function index() {
		if (Auth::user()->can('read-all-contact')) {
        	return parent::index();
        }
        else return redirect()->back()->withErrors(['Access denied to contacts index page']);		
	}

	protected function main() {
		$params = Request::input() != [] ? Request::input() : ['order' => ['people.last_name|ASC']];
    	$data['contacts'] = self::api($params);
		$data['title'] = "Contacts";
	    $data['menu_actions'] = [Form::addItem(route('company_person.create'), 'Add contact')];
		$data['active_search'] = implode(",",['people.first_name','people.last_name','companies.name','email']);
		return view('company_person/index',$data);
	}

	protected function html() {
		$params = Request::input();
		$data['contacts'] = self::api($params);
		return view('company_person/contacts',$data);
	}

	public function show($id) {
		if (Auth::user()->can('read-contact')) {
			$data['menu_actions'] = [
	        	Form::editItem(route('company_person.edit',$id), 'Edit this contact'),
	        ];
			$data['company_person'] = CompanyPerson::find($id);

	        $data['title'] = $data['company_person']->person->name() . " @ " . $data['company_person']->company->name;

			return view('company_person/show', $data);
		}
		else return $redirect()->back()->withErrors(['Access denied to contacts show page']);

	}

	public function create() {
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		$data['companies'] = Company::all();
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
        $contact->department_id = Input::get('department_id');
        $contact->title_id = Input::get('title_id');
        $contact->phone = Input::get('phone');
        $contact->extension = Input::get('extension');
        $contact->cellphone = Input::get('cellphone');
        $contact->email = Input::get('email');
        $contact->group_type_id = Input::get('company_id') == ELETTRIC80_COMPANY_ID ? EMPLOYEE_GROUP_TYPE_ID : CUSTOMER_GROUP_TYPE_ID;
        $contact->save();

		return redirect()->route('company_person.index')->with('successes',['Contact created successfully']);
	}	

	public function edit($id) {
		$data['title'] = "Edit Contact";
		$company_person = CompanyPerson::find($id);
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		$data['companies'] = Company::all();		
		$data['contact'] = CompanyPerson::find($id);
		$data['groups'] = Group::where('group_type_id','=',$company_person->group_type_id)->get();

		return view('company_person/edit', $data);	
	}	

	public function update($id, UpdateCompanyPersonRequest $request) {
		
		$contact = CompanyPerson::find($id);
		
		$contact->department_id = Input::get('department_id');
        $contact->title_id = Input::get('title_id');
        $contact->phone = Input::get('phone');
        $contact->extension = Input::get('extension');
        $contact->cellphone = Input::get('cellphone');
        $contact->email = Input::get('email');
        $contact->group_id = Input::get('group_id');

        $contact->save();

		return redirect()->route('company_person.show',$id)->with('successes',['Contact updated successfully']);
	}

	public function destroy($id) {
		echo 'company person method to be implmented';
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