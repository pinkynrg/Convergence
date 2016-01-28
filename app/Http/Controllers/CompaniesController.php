<?php namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Hotel;
use App\Models\Title;
use App\Models\Person;
use App\Models\Ticket;
use App\Models\Company;
use App\Models\Service;
use App\Models\Equipment;
use App\Models\GroupType;
use App\Models\Department;
use App\Models\SupportType;
use App\Models\CompanyPerson;
use App\Models\EscalationProfile;
use App\Models\CompanyMainContact;
use App\Models\CompanyAccountManager;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Input;
use Form;
use Auth;
use DB;

class CompaniesController extends Controller {

	public function index() {
        if (Auth::user()->can('read-all-company')) {
            
            $companies = Company::select("companies.*");
            $companies->leftJoin("company_main_contacts","companies.id","=","company_main_contacts.company_id");
            $companies->leftJoin("company_person","company_person.id","=","company_main_contacts.main_contact_id");
            $companies->leftJoin("company_account_managers","companies.id","=","company_account_managers.company_id");
            $companies->leftJoin("company_person as account_managers","account_managers.id","=","company_account_managers.account_manager_id");
            $companies->leftJoin("people","company_person.person_id","=","people.id");

            $data['companies'] = $companies->paginate(50);

            $data['menu_actions'] = [Form::addItem(route('companies.create'), 'Add Company')];
            $data['active_search'] = true;
            $data['title'] = "Companies";
    		return view('companies/index', $data);
        }
        else return redirect()->back()->withErrors(['Access denied to companies index page']);      
	}

	public function create() {
        $data['titles'] = Title::all();
        $data['departments'] = Department::all();
		$data['account_managers'] = CompanyPerson::where('company_person.company_id','=','1')->where('title_id','=',7)->get();
        $data['support_types'] = SupportType::all();
        $data['group_types'] = GroupType::all();
        $data['escalation_profiles'] = EscalationProfile::all();

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
        $contact->group_type_id = Input::get('company_id') == ELETTRIC80_COMPANY_ID ? EMPLOYEE_GROUP_TYPE : CUSTOMER_GROUP_TYPE;
        $contact->save();

        $company_main_contact = new CompanyMainContact;
        $company_main_contact->company_id = $company->id;
        $company_main_contact->main_contact_id = $contact->id;
        $company_main_contact->save();

        $company_account_manager = new CompanyAccountManager;
        $company_account_manager->company_id = $company->id;
        $company_account_manager->account_manager_id = Input::get('account_manager_id');
        $company_account_manager->save();

        return redirect()->route('companies.index')->with('successes',['company created successfully']);
	}

	public function show($id) {
        if (Auth::user()->can('read-company')) {
            
            $data['menu_actions'] = [
                Form::deleteItem('companies.destroy', $id, 'Remove this company'),
                Form::editItem(route('companies.edit',$id), 'Edit this company'),
                Form::addItem(route('company_person.create',$id), 'Add Contact to this company'),
                Form::addItem(route('equipment.create',$id),'Add new Equipment to this company'),
                Form::addItem(route('services.create',$id),'Add new Service Request to this company')
            ];

            $data['company'] = Company::find($id);
            $data['company']->contacts = CompanyPerson::where('company_person.company_id','=',$id)->paginate(10);
            $data['company']->tickets = Ticket::where('company_id','=',$id)->paginate(10);
            $data['company']->equipment = Equipment::where('company_id','=',$id)->paginate(10);
            $data['company']->hotels = Hotel::where('company_id','=',$id)->paginate(10);
            $data['company']->services = Service::where('company_id','=',$id)->paginate(10);
            $data['company']->escalations = EscalationProfile::all();

            $data['title'] = $data['company']->name;
            
            return view('companies/show',$data);
        }
        else return redirect()->back()->withErrors(['Access denied to companies show page']);      
	}

	public function edit($id) {
		$data['company'] = Company::find($id);
        
        $selected_account_manager = CompanyAccountManager::where('company_id','=',$id)->first();
        $data['company']->account_manager_id = isset($selected_account_manager) ? $selected_account_manager->account_manager_id : null;
        
        $selected_main_contact = CompanyMainContact::where('company_id','=',$id)->first();
        $data['company']->main_contact_id = $selected_main_contact->main_contact_id;

        $data['account_managers'] = CompanyPerson::where('company_person.company_id','=','1')->where('title_id','=',7)->get();
        $data['main_contacts'] = CompanyPerson::where('company_person.company_id','=',$id)->get();
        $data['support_types'] = SupportType::all();
        $data['escalation_profiles'] = EscalationProfile::all();

        $data['title'] = "Edit " . $data['company']->name;

		return view('companies/edit',$data);
	}

	public function update($id, UpdateCompanyRequest $request) {

        $company = Company::find($id);
        $company->update($request->all());

        $company_account_manager = CompanyAccountManager::where('company_id','=',$id)->first();

        // update account manager
        if (isset($company_account_manager)) {
            $company_account_manager->account_manager_id = Input::get('account_manager_id');
            $company_account_manager->save();
        }
        else {
            $company_account_manager = new CompanyAccountManager;
            $company_account_manager->company_id = $id;
            $company_account_manager->account_manager_id = Input::get('account_manager_id');
            $company_account_manager->save();        
        }

        // update main contact
        $main_contact = CompanyMainContact::where('company_id','=',$id)->first();

        if (isset($main_contact)) {
            $main_contact->main_contact_id = Input::get('main_contact_id');
            $main_contact->save();
        }
        else {
            $main_contact = new CompanyMainContact;
            $main_contact->company_id = $id;
            $main_contact->main_contact_id = Input::get('account_manager_id');
            $main_contact->save();        
        }

        return redirect()->route('companies.show',$id)->with('successes',['company updated successfully']);
	}

	public function destroy($id) {
        echo "company destroy method to be implement";
	}

    public function ajaxContactsRequest($company_id, $params = "") {

        parse_str($params,$params);

        $data['company'] = Company::find($company_id);
        
        $contacts = CompanyPerson::select('company_person.*');
        $contacts->leftJoin('people','people.id','=','company_person.person_id');
        $contacts->leftJoin('company_main_contacts', 'company_main_contacts.main_contact_id','=','company_person.id');
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
        $companies->leftJoin("company_main_contacts","companies.id","=","company_main_contacts.company_id");
        $companies->leftJoin("company_person","company_person.id","=","company_main_contacts.main_contact_id");
        $companies->leftJoin("people","company_person.person_id","=","people.id");
        $companies->leftJoin("company_account_managers as cam","companies.id","=","cam.company_id");
        $companies->leftJoin("company_person as account_manager_contact","account_manager_contact.id","=","cam.account_manager_id");
        $companies->leftJoin("people as account_managers","account_managers.id","=","account_manager_contact.person_id");
        
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

    public function ajaxEquipmentRequest($company_id, $params = "")
    {
        parse_str($params,$params);

        $equipment = Equipment::select("equipment.*");
        $equipment->leftJoin("equipment_types","equipment_types.id","=","equipment.equipment_type_id");
        $equipment->where('company_id','=',$company_id);

        // apply ordering
        if (isset($params['order'])) {
            $equipment->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $equipment->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $equipment = $equipment->paginate(10);

        $data['equipment'] = $equipment;

        return view('companies/equipment',$data);
    }

    public function ajaxHotelsRequest($company_id, $params = "")
    {
        parse_str($params,$params);

        $hotels = Hotel::select("hotels.*")->where("company_id",$company_id);

        // apply ordering
        if (isset($params['order'])) {
            $hotels->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $hotels->orderBy($params['order']['column'],$params['order']['type']);
        }

        // paginate
        $hotels = $hotels->paginate(10);

        $data['hotels'] = $hotels;

        return view('companies/hotels',$data);
    }
}