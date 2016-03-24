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
use App\Models\ConnectionType;
use App\Models\CompanyPerson;
use App\Models\EscalationProfile;
use App\Models\CompanyMainContact;
use App\Models\CompanyAccountManager;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Controllers\CompanyPersonController;
use App\Http\Controllers\TicketsController;
use Request;
use Input;
use Form;
use Auth;
use DB;

class CompaniesController extends BaseController {

    public function index() {
        if (Auth::user()->can('read-all-company')) {

            $data['companies'] = self::API()->all(Request::input());
            $data['menu_actions'] = [Form::addItem(route('companies.create'), 'Create Company',Auth::user()->can('create-company'))];
            $data['active_search'] = implode(",",['companies.name']);
            $data['title'] = "Companies";
            
            return Request::ajax() ? view('companies/companies',$data) : view('companies/index',$data);
        }
        else return redirect()->back()->withErrors(['Access denied to companies index page']);      
    }

	public function create() {
        if (Auth::user()->can('create-company')) {
            
            $data['titles'] = Title::orderBy("name")->get();
            $data['departments'] = Department::orderBy("name")->get();
            $data['support_types'] = SupportType::orderBy("name")->get();
            $data['connection_types'] = ConnectionType::orderBy("name")->get();
            $data['group_types'] = GroupType::orderBy("name")->get();
            $data['escalation_profiles'] = EscalationProfile::orderBy("name")->get();
            $data['account_managers'] = CompanyPersonController::API()->all([
                "where" => ["company_person.company_id|=|".ELETTRIC80_COMPANY_ID,"company_person.title_id|=|7"], 
                "order" => ["people.last_name|ASC","people.first_name|ASC"], 
                "paginate" => "false"
            ]);

            $data['title'] = "Create Company";

    		return view('companies/create', $data);
        }
        else return redirect()->back()->withErrors(['Access denied to companies create page']);      
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
        $contact->group_type_id = Input::get('company_id') == ELETTRIC80_COMPANY_ID ? EMPLOYEE_GROUP_TYPE_ID : CUSTOMER_GROUP_TYPE_ID;
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
            return $this->company($id);
        }
        else return redirect()->back()->withErrors(['Access denied to companies show page']);      
	}

    public function myCompany() {
        $id = Auth::user()->active_contact->company->id;
        return $this->company($id);
    }

    private function company($id) {
        $data['menu_actions'] = [
            Form::editItem(route('companies.edit',$id),'Edit This Company',Auth::user()->can('update-company')),
            Form::addItem(route('company_person.create',$id),'Create Contact',Auth::user()->can('create-contact')),
            Form::addItem(route('equipment.create',$id),'Create Equipment',Auth::user()->can('create-equipment')),
            Form::addItem(route('services.create',$id),'Create Service',Auth::user()->can('create-service'))
        ];

        $data['company'] = self::API()->find(['id'=>$id]);

        if ($data['company']) {

            $data['company']->contacts = CompanyPersonController::API()->all(['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
            $data['company']->tickets = TicketsController::API()->all(['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
            $data['company']->equipment = EquipmentController::API()->all(['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
            $data['company']->hotels = HotelsController::API()->all(['where' => ['companies.id|=|'.$id], 'paginate' => 10, ]);
            $data['company']->services = ServicesController::API()->all(['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
            $data['company']->escalations = EscalationProfile::all();

            $data['title'] = $data['company']->name;
            
            return view('companies/show',$data);

        }
        else {
            return redirect()->back()->withErrors(['404 The following Company coudn\'t be found']);  
        }
    }

	public function edit($id) {
        if (Auth::user()->can('update-company')) {

    		$data['company'] = Company::find($id);
            
            $selected_account_manager = CompanyAccountManager::where('company_id','=',$id)->first();
            $data['company']->account_manager_id = isset($selected_account_manager) ? $selected_account_manager->account_manager_id : null;
            
            $selected_main_contact = CompanyMainContact::where('company_id','=',$id)->first();
            $data['company']->main_contact_id = isset($selected_main_contact) ? $selected_main_contact->main_contact_id : null;

            $data['account_managers'] = CompanyPersonController::API()->all([
                "where" => ["company_person.company_id|=|".ELETTRIC80_COMPANY_ID,"company_person.title_id|=|7"], 
                "order" => ["people.last_name|ASC","people.first_name|ASC"], 
                "paginate" => "false"
            ]);
            
            $data['main_contacts'] = CompanyPerson::where('company_person.company_id','=',$id)->get();
            $data['support_types'] = SupportType::orderBy("name")->get();
            $data['connection_types'] = ConnectionType::orderBy("name")->get();
            $data['escalation_profiles'] = EscalationProfile::orderBy("name")->get();

            $data['title'] = "Edit " . $data['company']->name;

    		return view('companies/edit',$data);
        }
        else return redirect()->back()->withErrors(['Access denied to companies edit page']);      
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

    public function contacts($id) {
        $params = array_merge(Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['contacts'] = CompanyPersonController::API()->all($params);
        return view('company_person/contacts',$data);
    }

    public function tickets($id) {
        $params = array_merge(Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['tickets'] = TicketsController::API()->all($params);
        return view('tickets/tickets',$data);
    }

    public function equipment($id) {
        $params = array_merge(Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['equipment'] = EquipmentController::API()->all($params);
        return view('equipment/equipment',$data);
    }

    public function hotels($id) {
        $params = array_merge(Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['hotels'] = HotelsController::API()->all($params);
        return view('hotels/hotels',$data);
    }

    public function services($id) {
        $params = array_merge(Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['services'] = ServicesController::API()->all($params);
        return view('services/services',$data);
    }
}