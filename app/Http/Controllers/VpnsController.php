<?php namespace App\Http\Controllers;

	// equipment routes 
	// Route::get('vpns',['uses' => 'VpnsController@index', 'as' => 'vpns.index']);
	// Route::get('vpns/create',['uses' => 'VpnsController@create', 'as' => 'vpns.create']);
	// Route::get('vpns/{id}',['uses' => 'VpnsController@show', 'as' => 'vpns.show']);
	// Route::post('vpns', ['uses' => 'VpnsController@store', 'as' => 'vpns.store']);
	// Route::delete('vpns/{id}', ['uses' => 'VpnsController@destroy', 'as' => 'vpns.destroy']);
	// Route::patch('vpns/{id}', ['uses' => 'VpnsController@update', 'as' => 'vpns.update']);	
	// Route::get('vpns/{id}/edit', ['uses' => 'VpnsController@edit', 'as' => 'vpns.edit']);


class VpnsController extends BaseController {

	public function index() {}

	public function create() {
		$data['title'] = "Create Vpn";
		$data['company'] = Company::find($id);
		$data['company']->company_id = $data['company']->id;
		return view('equipment/create', $data);		
	}

	public function show($id) {}

	public function store() {}

	public function destroy($id) {}

	public function update($id) {}

	public function edit($id) {}

}