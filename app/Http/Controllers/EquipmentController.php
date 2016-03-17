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

class EquipmentController extends BaseController {
	
	public function index() {
		if (Auth::user()->can('read-all-equipment')) {
	    	$data['equipment'] = self::API()->all(Request::input());
			$data['title'] = "Equipment";
			$data['active_search'] = implode(",",['cc_number','equipment.name','serial_number','equipment_types.name','companies.name']);
            return Request::ajax() ? view('equipment/equipment',$data) : view('equipment/index',$data);

		}
		else return redirect()->back()->withErrors(['Access denied to equipment index page']);		
	}

	public function show($id) {
		if (Auth::user()->can('read-equipment')) {
			$data['menu_actions'] = [Form::editItem(route('equipment.edit', $id),"Edit This Equipment",Auth::user()->can('update-equipment'))];
			$data['equipment'] = Equipment::find($id);
			$data['title'] = $data['equipment']->company->name." - Equipment ".$data['equipment']->name;
			return view('equipment/show',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to equipment show page']);
	}

	public function create($id) {
		if (Auth::user()->can('create-equipment')) {
	        $data['title'] = "Create Equipment";
	        $data['equipment_types'] = EquipmentType::orderBy("name")->get();
	        $data['company'] = Company::find($id);
			$data['company']->company_id = $data['company']->id;
			return view('equipment/create', $data);	
		}
		else return redirect()->back()->withErrors(['Access denied to equipment create page']);
	}

	public function store(CreateEquipmentRequest $request) {
		$equipment = Equipment::create($request->all());
        $equipment->warranty_expiration = Carbon::createFromFormat('m/d/Y', $request->get('warranty_expiration'));
        $equipment->save();
		return redirect()->route('companies.show',$request->get('company_id'))->with('successes',['equipment created successfully']);
	}

	public function edit($id) {
		if (Auth::user()->can('update-equipment')) {
			$data['equipment'] = Equipment::find($id);
			$data['title'] = $data['equipment']->company->name." - Equipment ".$data['equipment']->name;
	        $data['equipment_types'] = EquipmentType::orderBy("name")->get();
	        $data['company'] = Company::find($data['equipment']->company_id);
			$data['company']->company_id = $data['company']->id;
			return view('equipment/edit',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to equipment edit page']);
	}

	public function update($id, UpdateEquipmentRequest $request) {
		$equipment = Equipment::find($id);
        $equipment->update($request->all());
        $equipment->warranty_expiration = Carbon::createFromFormat('m/d/Y', $request->get('warranty_expiration'));
        $equipment->save();
        return redirect()->route('companies.show',$equipment->company_id)->with('successes',['equipment updated successfully']);
	}
}