<?php namespace App\Http\Controllers\API;

use App\Models\Service;

class ServicesController extends BaseController {

    public function all($params)
    {
        $params['order'] = isset($params['order']) ? $params['order'] : ['services.id|DESC'];

    	$services = Service::select("services.*");
        $services->leftJoin("companies","companies.id","=","services.company_id");
        $services->leftJoin("company_person as internal_contact","internal_contact.id","=","services.internal_contact_id");
        $services->leftJoin("company_person as external_contact","external_contact.id","=","services.external_contact_id");
        $services->leftJoin('people as internals','internal_contact.person_id','=','internals.id');
        $services->leftJoin('people as externals','external_contact.person_id','=','externals.id');
        $services->leftJoin('hotels','hotels.id','=','services.hotel_id');
        
    	$services = parent::execute($services, $params);

        return $services;
    }

}
