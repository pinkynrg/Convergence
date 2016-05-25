<?php namespace App\Http\Controllers\API;

use App\Models\CompanyPerson;
use Auth;
use DB;

class CompanyPersonController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['last_name|ASC','first_name|ASC'];
        $contacts = $this->query($params);
        $contacts = parent::execute($contacts, $params);
        return $contacts;
    }

    public function find($params) {

        $contacts = $this->query($params);
        $contacts = $contacts->where("company_person.id",$params['id']);
        $contact = $contacts->get()->first() ? $contacts->get()->first() : [];
        return $contact;
    }

    private function query($params = array()) {

        $raw1 = DB::raw("CASE 
                            WHEN company_main_contacts.main_contact_id = company_person.id 
                            THEN 1 
                            ELSE 0 
                         END as 'is_main_contact'");

        $contacts = CompanyPerson::select("company_person.*","people.first_name","people.last_name",$raw1);
        $contacts->leftJoin('people','company_person.person_id','=','people.id');
        $contacts->leftJoin('companies','company_person.company_id','=','companies.id');
        $contacts->leftJoin('titles','company_person.title_id','=','titles.id');
        $contacts->leftJoin('groups','company_person.group_id','=','groups.id');
        $contacts->leftJoin('departments','company_person.department_id','=','departments.id');
        $contacts->leftJoin('company_main_contacts','company_main_contacts.company_id','=','companies.id');

        if (Auth::check() && !Auth::user()->active_contact->isE80() && (!isset($params['debugger_list']) || $params['debugger_list'] != "true" || Auth::user()->owner->id != ADMIN_PERSON_ID)) {
            $contacts->where("company_person.company_id","=",Auth::user()->active_contact->company_id);
        }

        return $contacts;
    }

}
