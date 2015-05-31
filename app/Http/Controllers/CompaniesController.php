<?php namespace Convergence\Http\Controllers;

use Convergence\Models\Company;
use Convergence\Models\Person;
use Convergence\Models\Ticket;
use Convergence\Models\Equipment;
use Convergence\Models\CompanyPerson;
use Convergence\Models\CompanyMainContact;
use Convergence\Models\CompanyAccountManager;
use Convergence\Http\Requests\CreateCompanyRequest;
use Convergence\Http\Requests\UpdateCompanyRequest;
use Input;
use Form;
use DB;

class CompaniesController extends Controller {

	public function index() {
        $data['companies'] = Company::paginate(50);
		$data['title'] = "Convergence - Companies";
        $data['menu_actions'] = [Form::addItem(route('companies.create'), 'Add Company')];
        $data['active_search'] = true;
		return view('companies/index', $data);
	}

	public function create() {
		$data['account_managers'] = Person::select('people.*')
                                    ->leftJoin('company_person','company_person.person_id','=','people.id')
                                    ->where('company_person.company_id','=','1')
                                    ->where('title_id','=',7)->get();

		return view('companies/create', $data);
	}

	public function store(CreateCompanyRequest $request) {

        $company = Company::create($request->all());

        $contact = new Person;
        $contact->first_name = Input::get('first_name');
        $contact->last_name = Input::get('last_name');
        $contact->phone = Input::get('phone');
        $contact->cellphone = Input::get('cellphone');
        $contact->email = Input::get('email');
        $contact->save();

        $company_person = new CompanyPerson;
        $company_person->company_id = $company->id;
        $company_person->person_id = $contact->id;
        $company_person->save();

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
            Form::addItem(route('people.create',$id), 'Add Contact to this company')];

        $data['company'] = Company::find($id);
        
        $data['company']->contacts = Person::select('people.*')
                                      ->leftJoin('company_person','company_person.person_id','=','people.id')
                                      ->where('company_person.company_id','=',$id)
                                      ->paginate(10);

        $data['company']->tickets = Ticket::where('company_id','=',$id)->paginate(10);
        $data['company']->equipments = Equipment::where('company_id','=',$id)->paginate(10);
        
        return view('companies/show',$data);
	}

	public function edit($id) {
		$data['company'] = Company::find($id);
		
        $data['account_managers'] = Person::select('people.*')
                                    ->leftJoin('company_person','company_person.person_id','=','people.id')
                                    ->where('company_person.company_id','=','1')
                                    ->where('title_id','=',7)->get();

		return view('companies/edit',$data);
	}

	public function update($id, UpdateCompanyRequest $request) {
		
        $company = Company::find($id);
        $company->update($request->all());

        $company_account_manager = CompanyAccountManager::where('company_id','=',$id);
        $company_account_manager->update(['account_manager_id' => Input::get('account_manager_id')]);

        return redirect()->route('companies.show',$id);
	}

	public function destroy($id) {

        $company_person = CompanyPerson::where('company_id','=',$id)->delete();
        $company_main_contact = CompanyMainContact::where('company_id','=',$id)->delete();
        $company_account_manager = CompanyAccountManager::where('company_id','=',$id)->delete();

        $contacts = Person::leftJoin('company_person','company_person.person_id','=','people.id')
                    ->leftJoin('company_main_contact','company_main_contact.main_contact_id','=','people.id')
                    ->where('company_person.company_id','IS', DB::raw('null'))
                    ->where('company_main_contact.company_id','IS', DB::raw('null'))->delete();
		
        $company = Company::find($id)->delete();

		return redirect()->route('companies.index');
	}

    public function ajaxContactsRequest($company_id, $params = "") {

        parse_str($params,$params);

        $data['company'] = Company::find($company_id);
        
        $contacts = Person::select('people.*')
                                    ->leftJoin('company_person','company_person.person_id','=','people.id')
                                    ->where('company_person.company_id','=',$company_id);

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
        $companies->leftJoin("people","people.id","=","company_main_contact.main_contact_id");
        
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