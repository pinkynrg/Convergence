<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('login', array('uses' => 'LoginController@showLogin', 'as' => 'login.index'));
Route::post('login', array('uses' => 'LoginController@doLogin', 'as' => 'login.login'));
Route::get('logout', array('uses' => 'LoginController@doLogout', 'as' => 'login.logout'));

Route::get('import/{target?}', ['uses' => 'ImportController@import', 'as' => 'importer.import']);

// Route::group(array('middleware' => 'auth'), function() {

	Route::get('/', ['uses'=>'TicketsController@index', 'as'=>'root']);

	Route::resource('companies','CompaniesController');
	Route::resource('tickets','TicketsController');
	Route::resource('equipments','EquipmentsController');

	// people routes
	Route::match(['get','head'], 'people/{person_id}',['uses' => 'PeopleController@show', 'as' => 'people.show']);
	Route::match(['get','head'], 'people/{person_id}/edit', ['uses' => 'PeopleController@edit', 'as' => 'people.edit']);
	Route::match(['patch'], 'people/{person_id}', ['uses' => 'PeopleController@update', 'as' => 'people.update']);
	Route::match(['delete'], 'people/{person_id}', ['uses' => 'PeopleController@destroy', 'as' => 'people.destroy']);

	Route::match(['get','head'], 'employees',['uses' => 'CompanyPersonController@employees', 'as' => 'company_person.employees']);
	Route::match(['get','head'], 'contacts',['uses' => 'CompanyPersonController@contacts', 'as' => 'company_person.contacts']);
	Route::match(['get','head'], 'contacts/create/{company_id}', ['uses' => 'CompanyPersonController@create', 'as' => 'company_person.create']);
	Route::match(['post'], 'people', ['uses' => 'CompanyPersonController@store', 'as' => 'company_person.store']);
	Route::match(['get','head'], 'contacts/{company_person_id}',['uses' => 'CompanyPersonController@show', 'as' => 'company_person.show']);
	Route::match(['get','head'], 'contacts/{company_person_id}/edit', ['uses' => 'CompanyPersonController@edit', 'as' => 'company_person.edit']);
	Route::match(['patch'], 'contacts/{company_person_id}', ['uses' => 'CompanyPersonController@update', 'as' => 'company_person.update']);
	Route::match(['delete'], 'contacts/{company_person_id}', ['uses' => 'CompanyPersonController@destroy', 'as' => 'company_person.destroy']);

	// ajax routes
	Route::get('ajax/tickets/{params?}', ['uses' => 'TicketsController@ajaxTicketsRequest', 'as' => 'ajax.tickets']);
	Route::get('ajax/companies/{params?}', ['uses' => 'CompaniesController@ajaxCompanyRequest', 'as' => 'ajax.companies']);
	Route::get('ajax/companies/contacts/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxContactsRequest', 'as' => 'ajax.companies.contacts']);
	Route::get('ajax/companies/tickets/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxTicketsRequest', 'as' => 'ajax.companies.tickets']);
	Route::get('ajax/companies/equipments/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxEquipmentsRequest', 'as' => 'ajax.companies.equipments']);
	Route::get('ajax/employees/{params?}', ['uses' => 'PeopleController@ajaxEmployeesRequest', 'as' => 'ajax.employees']);
	Route::get('ajax/contacts/{params?}', ['uses' => 'PeopleController@ajaxContactsRequest', 'as' => 'ajax.contacts']);
	Route::get('ajax/people', ['uses' => 'PeopleController@ajaxPeopleRequest', 'as' => 'ajax.people']);
	Route::get('ajax/equipments/{params?}', ['uses' => 'EquipmentsController@ajaxEquipmentsRequest', 'as' => 'ajax.equipments']);

// });
