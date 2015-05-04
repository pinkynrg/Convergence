<?php namespace Convergence\Http\Controllers;


use Convergence\Models\Equipment;
use Request;

class EquipmentsController extends Controller {
	public function index() {
		$data['equipments'] = Equipment::paginate(50);
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

	public function ajaxEquipmentsRequest() {
		$data['equipments'] = Equipment::paginate(50);
        return view('equipments/equipments',$data);
	}
}