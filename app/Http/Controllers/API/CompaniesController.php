<?php namespace App\Http\Controllers\API;

use App\Models\Company;
use Auth;

class CompaniesController extends BaseController {

    public function all($params)
    { 
        $params['order'] = isset($params['order']) ? $params['order'] : ['name|ASC'];

        $companies = Company::select("companies.*");
        $companies->leftJoin("company_main_contacts","companies.id","=","company_main_contacts.company_id");
        $companies->leftJoin("company_person as company_person_main_contacts","company_person_main_contacts.id","=","company_main_contacts.main_contact_id");
        $companies->leftJoin("company_account_managers","companies.id","=","company_account_managers.company_id");
        $companies->leftJoin("company_person as company_person_account_managers","company_person_account_managers.id","=","company_account_managers.account_manager_id");
        $companies->leftJoin("people as account_managers","company_person_account_managers.person_id","=","account_managers.id");
        $companies->leftJoin("people as main_contacts","company_person_main_contacts.person_id","=","main_contacts.id");

        if (!Auth::user()->active_contact->isE80()) {
            $companies->where("companies.id","=",Auth::user()->active_contact->company_id);            
        }
    
    	$companies = parent::execute($companies, $params);

        return $companies;
    }

}
