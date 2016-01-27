<?php namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Http\Requests\CreateEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use Carbon\Carbon;
use Request;
use Form; 
use Auth;

class EquipmentController extends Controller {
	
	public function index() {
		if (Auth::user()->can('read-all-equipment')) {
        	$data['title'] = "Equipment";
			$data['active_search'] = true;
			$data['equipment'] = Equipment::paginate(50);
			return view('equipment/index',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to equipment index page']);		
	}

	public function show($id) {
		if (Auth::user()->can('read-equipment')) {
			$data['menu_actions'] = [Form::editItem(route('equipment.edit', $id),"Edit this equipment")];
			$data['equipment'] = Equipment::find($id);
			$data['title'] = $data['equipment']->company->name." - Equipment ".$data['equipment']->name;
			return view('equipment/show',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to equipment show page']);
	}

	public function create($id) {
        $data['title'] = "Create Equipment";
        $data['equipment_types'] = EquipmentType::all();
        $data['company'] = Company::find($id);
		$data['company']->company_id = $data['company']->id;
		return view('equipment/create', $data);	
	}

	public function store(CreateEquipmentRequest $request) {
		$equipment = Equipment::create($request->all());
        $equipment->warranty_expiration = Carbon::createFromFormat('m/d/Y', $request->get('warranty_expiration'));
        $equipment->save();
		return redirect()->route('companies.show',$request->get('company_id'))->with('successes',['equipment created successfully']);
	}

	public function edit($id) {
		$data['equipment'] = Equipment::find($id);
		$data['title'] = $data['equipment']->company->name." - Equipment ".$data['equipment']->name;
        $data['equipment_types'] = EquipmentType::all();
        $data['company'] = Company::find($data['equipment']->company_id);
		$data['company']->company_id = $data['company']->id;
		return view('equipment/edit',$data);
	}

	public function update($id, UpdateEquipmentRequest $request) {
		$equipment = Equipment::find($id);
        $equipment->update($request->all());
        $equipment->warranty_expiration = Carbon::createFromFormat('m/d/Y', $request->get('warranty_expiration'));
        $equipment->save();
        return redirect()->route('companies.show',$equipment->company_id)->with('successes',['equipment updated successfully']);
	}

	public function destroy() {
		return "equipment destroy method hasn't been created yet";
	}

	public function ajaxEquipmentRequest($params = "") {
		
        parse_str($params,$params);

		$equipment = Equipment::select("equipment.*");
		$equipment->leftJoin("companies","companies.id","=","equipment.company_id");
		$equipment->leftJoin("equipment_types","equipment_types.id","=","equipment.equipment_type_id");

		// apply search
        if (isset($params['search'])) {
            $equipment->where('equipment.name','like','%'.$params['search'].'%');
            $equipment->orWhere('equipment.cc_number','=',$params['search']);
        }

        // apply ordering
        if (isset($params['order'])) {
    		$equipment->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
            $equipment->orderBy($params['order']['column'],$params['order']['type']);
        }

		$equipment = $equipment->paginate(50);

		$data['equipment'] = $equipment;

        return view('equipment/equipment',$data);
	}
}