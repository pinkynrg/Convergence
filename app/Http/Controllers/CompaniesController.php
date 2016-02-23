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
            return parent::index();
        }
        else return redirect()->back()->withErrors(['Access denied to companies index page']);      
    }

    protected function main() {
        $params = Request::input() != [] ? Request::input() : ['order' => ['companies.name|ASC']];
        $data['companies'] = self::api($params);
        $data['menu_actions'] = [Form::addItem(route('companies.create'), 'Add Company')];
        $data['active_search'] = implode(",",['companies.name']);
        $data['title'] = "Companies";
        return view('companies/index', $data);
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
            
            $data['menu_actions'] = [
                Form::editItem(route('companies.edit',$id), 'Edit this company'),
                Form::addItem(route('company_person.create',$id), 'Add Contact'),
                Form::addItem(route('equipment.create',$id),'Add new Equipment'),
                Form::addItem(route('services.create',$id),'Add new Service')
            ];

            $data['company'] = Company::find($id);
            $data['company']->contacts = CompanyPersonController::api(['where' => ['companies.id|=|'.$id], 'paginate' => 10, 'order' => ['people.last_name|ASC']]);
            $data['company']->tickets = TicketsController::api(['where' => ['companies.id|=|'.$id], 'paginate' => 10, 'order' => ['tickets.id|DESC']]);
            $data['company']->equipment = EquipmentController::api(['where' => ['companies.id|=|'.$id], 'paginate' => 10, 'order' => ['equipment.id|DESC']]);
            $data['company']->hotels = HotelsController::api(['where' => ['companies.id|=|'.$id], 'paginate' => 10, 'order' => ['hotels.rating|DESC']]);
            $data['company']->services = ServicesController::api(['where' => ['companies.id|=|'.$id], 'paginate' => 10, 'order' => ['services.id|DESC']]);
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

    public function contacts($id) {
        // by merging array i let override the order by the requested values but I do not let override paginate and company id
        $params = array_merge(['order' => ['people.last_name|ASC']], Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['contacts'] = CompanyPersonController::api($params);
        return view('company_person/contacts',$data);
    }

    public function tickets($id) {
        // by merging array i let override the order by the requested values but I do not let override paginate and company id
        $params = array_merge(['order' => ['tickets.id|DESC']], Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['tickets'] = TicketsController::api($params);
        return view('tickets/tickets',$data);
    }

    public function equipment($id) {
        // by merging array i let override the order by the requested values but I do not let override paginate and company id
        $params = array_merge(['order' => ['equipment.id|DESC']], Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['equipment'] = EquipmentController::api($params);
        return view('equipment/equipment',$data);
    }

    public function hotels($id) {
        // by merging array i let override the order by the requested values but I do not let override paginate and company id
        $params = array_merge(['order' => ['hotels.rating|DESC']], Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['hotels'] = HotelsController::api($params);
        return view('hotels/hotels',$data);
    }

    public function services($id) {
        // by merging array i let override the order by the requested values but I do not let override paginate and company id
        $params = array_merge(['order' => ['services.id|DESC']], Request::input(), ['where' => ['companies.id|=|'.$id], 'paginate' => 10]);
        $data['services'] = ServicesController::api($params);
        return view('services/services',$data);
    }

	public function destroy($id) {
        echo "company destroy method to be implement";
	}
}