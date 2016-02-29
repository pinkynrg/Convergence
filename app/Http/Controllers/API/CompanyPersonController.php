<?php namespace App\Http\Controllers\API;

use App\Models\CompanyPerson;
use DB;

class CompanyPersonController extends BaseController {

    public function all($params)
    {

        $params['order'] = isset($params['order']) ? $params['order'] : ['last_name|ASC','first_name|ASC'];

        $raw1 = DB::raw("CASE 
                            WHEN company_main_contacts.main_contact_id = company_person.id 
                            THEN 1 
                            ELSE 0 
                         END as 'is_main_contact'");

    	$contacts = CompanyPerson::select("company_person.*","people.first_name","people.last_name",$raw1);
        $contacts->leftJoin('people','company_person.person_id','=','people.id');
        $contacts->leftJoin('companies','company_person.company_id','=','companies.id');
        $contacts->leftJoin('titles','company_person.title_id','=','titles.id');
        $contacts->leftJoin('departments','company_person.department_id','=','departments.id');
        $contacts->leftJoin('company_main_contacts','company_main_contacts.company_id','=','companies.id');
        
    	$contacts = parent::execute($contacts, $params);

        return $contacts;
    }

}
