<?php namespace App\Http\Controllers\API;

use App\Models\Company;

class CompaniesController extends BaseController {

    public function all($params)
    { 
        $params['order'] = isset($params['order']) ? $params['order'] : ['name|ASC'];

        $companies = Company::select("companies.*");
        $companies->leftJoin("company_main_contacts","companies.id","=","company_main_contacts.company_id");
        $companies->leftJoin("company_person","company_person.id","=","company_main_contacts.main_contact_id");
        $companies->leftJoin("company_account_managers","companies.id","=","company_account_managers.company_id");
        $companies->leftJoin("company_person as account_managers","account_managers.id","=","company_account_managers.account_manager_id");
        $companies->leftJoin("people","company_person.person_id","=","people.id");
    
    	$companies = parent::execute($companies, $params);

        return $companies;
    }

}
