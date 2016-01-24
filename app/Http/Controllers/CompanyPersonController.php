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

	public function index() {
		if (Auth::user()->can('read-all-contact')) {
	        $data['menu_actions'] = [Form::addItem(route('company_person.create'), 'Add contact')];
			$data['active_search'] = true;
			$data['contacts'] = CompanyPerson::paginate(50);
        	$data['title'] = "Contacts";
			return view('company_person/index',$data);
        }
        else return redirect()->back()->withErrors(['Access denied to contacts index page']);		
	}

	public function show($id) {
		if (Auth::user()->can('read-contact')) {
			$data['menu_actions'] = [
	        	Form::editItem(route('company_person.edit',$id), 'Edit this contact'),
				Form::deleteItem('company_person.destroy', $id, 'Remove this contact')
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
        $contact->title_id = Input::get('title_id');
        $contact->department_id = Input::get('department_id');
        $contact->phone = Input::get('phone');
        $contact->extension = Input::get('extension');
        $contact->cellphone = Input::get('cellphone');
        $contact->group_type_id = Input::get('group_type_id');
        $contact->email = Input::get('email');
        $contact->save();

		return redirect()->route('company_person.index')->with('successes',['Contact created successfully']);
	}	

	public function edit($id) {
		$company_person = CompanyPerson::find($id);
		$data['titles'] = Title::all();
		$data['departments'] = Department::all();
		$data['companies'] = Company::all();		
		$data['contact'] = CompanyPerson::find($id);
		$data['groups'] = Group::where('group_type_id','=',$company_person->group_type_id)->get();
		$data['title'] = "Edit Contact";

		return view('company_person/edit', $data);	
	}	

	public function update($id, UpdateCompanyPersonRequest $request) {
		$contact = CompanyPerson::find($id);
		$contact->update($request->all());

		return redirect()->route('company_person.show',$id)->with('successes',['Contact updated successfully']);
	}

	public function destroy($id) {
		echo 'company person method to be implmented';
	}

	public function ajaxContactsRequest($params = "") {

        parse_str($params,$params);

		$contacts = CompanyPerson::select("company_person.*");
		$contacts->leftJoin('people','company_person.person_id','=','people.id');
		$contacts->leftJoin('companies','company_person.company_id','=','companies.id');
		$contacts->leftJoin('titles','company_person.title_id','=','titles.id');
		$contacts->leftJoin('departments','company_person.department_id','=','departments.id');
		
		if (isset($params['search'])) {
			$contacts->where(function($query) use ($params) {
	            $query->where('last_name','like','%'.$params['search'].'%');
	            $query->orWhere('first_name','like','%'.$params['search'].'%');
			});
		}

        // apply ordering
        if (isset($params['order'])) {
    		$contacts->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $contacts->orderBy($params['order']['column'],$params['order']['type']);
        }

		//paginate
		$data['contacts'] = $contacts->paginate(50);

        return view('company_person/index',$data);
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