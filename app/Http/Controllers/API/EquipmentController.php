<?php namespace App\Http\Controllers\API;

use App\Models\Equipment;

class EquipmentController extends BaseController {

    public static function all($params)
    {
    	$params['order'] = isset($params['order']) ? $params['order'] : ['equipment.cc_number|DESC'];
    	
    	$equipment = Equipment::select("equipment.*");
        $equipment->leftJoin("companies","companies.id","=","equipment.company_id");
        $equipment->leftJoin("equipment_types","equipment_types.id","=","equipment.equipment_type_id");
        
    	$equipment = parent::execute($equipment, $params);

        return $equipment;
    }

}
