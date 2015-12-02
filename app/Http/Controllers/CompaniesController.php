<?php namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Person;
use App\Models\Ticket;
use App\Models\Post;
use App\Models\Title;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Service;
use App\Models\SupportType;
use App\Models\CompanyPerson;
use App\Models\CompanyMainContact;
use App\Models\CompanyAccountManager;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Input;
use Form;
use DB;

class CompaniesController extends Controller {

	public function index() {
        $data['companies'] = Company::paginate(50);
        $data['menu_actions'] = [Form::addItem(route('companies.create'), 'Add Company')];
        $data['active_search'] = true;

        $data['title'] = "Companies";

		return view('companies/index', $data);
	}

	public function create() {
        $data['titles'] = Title::all();
        $data['departments'] = Department::all();
		$data['account_managers'] = CompanyPerson::where('company_person.company_id','=','1')->where('title_id','=',7)->get();
        $data['support_types'] = SupportType::all();

        $data['title'] = "Create Company";

		return view('companies/create', $data);
	}

	public function store(CreateCompanyRequest $request) {

        $company = Company::create($request->all());

        if (!Input::get('person_id')) {
            $person = new Person;
            $person->first_name = Input::get('person_fn');
            $person->last_name = Input::get('person_ln');
            $person->save();
        }

        $contact = new CompanyPerson;
        $contact->company_id = $company->id;
        $contact->person_id = Input::get('person_id') ? Input::get('person_id') : $person->id;
        $contact->title_id = Input::get('title_id');
        $contact->department_id = Input::get('department_id');
        $contact->phone = Input::get('phone');
        $contact->extension = Input::get('extension');
        $contact->cellphone = Input::get('cellphone');
        $contact->email = Input::get('email');
        $contact->save();

        $company_main_contact = new CompanyMainContact;
        $company_main_contact->company_id = $company->id;
        $company_main_contact->main_contact_id = $contact->id;
        $company_main_contact->save();

        $company_account_manager = new CompanyAccountManager;
        $company_account_manager->company_id = $company->id;
        $company_account_manager->account_manager_id = Input::get('account_manager_id');
        $company_account_manager->save();

        return redirect()->route('companies.index');
	}

	public function show($id) {

        $data['menu_actions'] = [
            Form::deleteItem('companies.destroy', $id, 'Remove this company'),
            Form::editItem(route('companies.edit',$id), 'Edit this company'),
            Form::addItem(route('company_person.create',$id), 'Add Contact to this company')];

        $data['company'] = Company::find($id);
        $data['company']->contacts = CompanyPerson::where('company_person.company_id','=',$id)->paginate(10);
        $data['company']->tickets = Ticket::where('company_id','=',$id)->paginate(10);
        $data['company']->equipments = Equipment::where('company_id','=',$id)->paginate(10);

        $data['title'] = $data['company']->name;

        return view('companies/show',$data);
	}

	public function edit($id) {
		$data['company'] = Company::find($id);
        $data['account_managers'] = CompanyPerson::where('company_person.company_id','=','1')->where('title_id','=',7)->get();
        $data['main_contacts'] = CompanyPerson::where('company_person.company_id','=',$id)->get();
        $data['support_types'] = SupportType::all();

        $data['title'] = "Edit " . $data['company']->name;

		return view('companies/edit',$data);
	}

	public function update($id, UpdateCompanyRequest $request) {
		
        $company = Company::find($id);
        $company->update($request->all());

        $company_account_manager = CompanyAccountManager::where('company_id','=',$id);

        if (isset($company_account_manager->get()[0])) {
            $company_account_manager->update(['account_manager_id' => Input::get('account_manager_id')]);
        }
        else {
            $company_account_manager = new CompanyAccountManager;
            $company_account_manager->company_id = $id;
            $company_account_manager->account_manager_id = Input::get('account_manager_id');
            $company_account_manager->save();        
        }

        $main_contact = CompanyMainContact::where('company_id','=',$id);

        if (isset($main_contact->get()[0])) {
            $main_contact->update(['main_contact_id' => Input::get('main_contact_id')]);
        }
        else {
            $main_contact = new CompanyMainContact;
            $main_contact->company_id = $id;
            $main_contact->main_contact_id = Input::get('account_manager_id');
            $main_contact->save();        
        }

        return redirect()->route('companies.show',$id);
	}

	public function destroy($id) {
        echo "company destroy method to be implement";
	}

    public function ajaxContactsRequest($company_id, $params = "") {

        parse_str($params,$params);

        $data['company'] = Company::find($company_id);
        
        $contacts = CompanyPerson::select('company_person.*');
        $contacts->leftJoin('people','people.id','=','company_person.person_id');
        $contacts->leftJoin('company_main_contact', 'company_main_contact.main_contact_id','=','company_person.id');
        $contacts->where('company_person.company_id','=',$company_id);

        // apply ordering
        if (isset($params['order'])) {
            $contacts->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $contacts->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $contacts = $contacts->paginate(10);

        $data['contacts'] = $contacts;

        return view('companies/contacts',$data);
    }

    public function ajaxCompanyRequest($params = "") 
    {    
        parse_str($params,$params);

        $companies = Company::select("companies.*");
        $companies->leftJoin("company_main_contact","companies.id","=","company_main_contact.company_id");
        $companies->leftJoin("company_person","company_person.id","=","company_main_contact.main_contact_id");
        $companies->leftJoin("people","company_person.person_id","=","people.id");
        
        // apply search
        if (isset($params['search'])) {
            $companies->where('name','like','%'.$params['search'].'%');
        }

        // apply ordering
        if (isset($params['order'])) {
            $companies->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $companies->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $companies = $companies->paginate(50);

        $data['companies'] = $companies;

        return view('companies/companies',$data); 
    }

    public function ajaxTicketsRequest($company_id, $params = "") 
    {
        parse_str($params,$params);

        $tickets = Ticket::select("tickets.*");
        $tickets->leftJoin("statuses","statuses.id","=","tickets.status_id");
        $tickets->leftJoin("people as assignees","assignees.id","=","tickets.assignee_id");
        $tickets->leftJoin("people as creators","creators.id","=","tickets.creator_id");
        $tickets->where('company_id','=',$company_id);

        // apply ordering
        if (isset($params['order'])) {
            $tickets->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $tickets->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $tickets = $tickets->paginate(10);

        $data['tickets'] = $tickets;
        return view('companies/tickets',$data);
    }

    public function ajaxEquipmentsRequest($company_id, $params = "")
    {
        parse_str($params,$params);

        $equipments = Equipment::select("equipments.*");
        $equipments->leftJoin("equipment_types","equipment_types.id","=","equipments.equipment_type_id");
        $equipments->where('company_id','=',$company_id);

        // apply ordering
        if (isset($params['order'])) {
            $equipments->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $equipments->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $equipments = $equipments->paginate(10);

        $data['equipments'] = $equipments;

        return view('companies/equipments',$data);
    }
}