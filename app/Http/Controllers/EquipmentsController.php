<?php namespace App\Http\Controllers;


use App\Models\Equipment;
use Request;

class EquipmentsController extends Controller {
	
	public function index() {
		$data['equipments'] = Equipment::paginate(50);

        $data['title'] = "Equipments";

		return view('equipments/index',$data);
	}

	public function show() {
		return "equipment show method hasn't been created yet";
	}

	public function create() {
		return "equipment create method hasn't been created yet";
	}

	public function store() {
		return "equipment store method hasn't been created yet";
	}

	public function edit() {
		return "equipment edit method hasn't been created yet";
	}

	public function update() {
		return "equipment update method hasn't been created yet";
	}

	public function destroy() {
		return "equipment destroy method hasn't been created yet";
	}

	public function ajaxEquipmentsRequest($params = "") {
		
        parse_str($params,$params);

		$equipments = Equipment::select("equipments.*");
		$equipments->leftJoin("companies","companies.id","=","equipments.company_id");
		$equipments->leftJoin("equipment_types","equipment_types.id","=","equipments.equipment_type_id");

		// apply search
        if (isset($params['search'])) {
            $equipments->where('name','like','%'.$params['search'].'%');
        }

        // apply ordering
        if (isset($params['order'])) {
    		$equipments->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $equipments->orderBy($params['order']['column'],$params['order']['type']);
        }

		$equipments = $equipments->paginate(50);

		$data['equipments'] = $equipments;

        return view('equipments/equipments',$data);
	}
}