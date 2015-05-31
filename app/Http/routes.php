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
	Route::match(['get','head'], 'employees',['uses' => 'PeopleController@employees', 'as' => 'people.employees']);
	Route::match(['get','head'], 'contacts',['uses' => 'PeopleController@contacts', 'as' => 'people.contacts']);
	Route::match(['get','head'], 'people/{person_id}',['uses' => 'PeopleController@show', 'as' => 'people.show']);
	Route::match(['get','head'], 'people/create/{company_id}', ['uses' => 'PeopleController@create', 'as' => 'people.create']);
	Route::match(['post'], 'poeple', ['uses' => 'PeopleController@store', 'as' => 'people.store']);
	Route::match(['get','head'], 'people/{person_id}/edit', ['uses' => 'PeopleController@edit', 'as' => 'people.edit']);
	Route::match(['put'], 'people/{person_id}', ['uses' => 'PeopleController@update', 'as' => 'people.update']);
	Route::match(['patch'], 'people/{person_id}', ['uses' => 'PeopleController@update']);
	Route::match(['delete'], 'people/{person_id}', ['uses' => 'PeopleController@destroy', 'as' => 'people.destroy']);

	// ajax routes
	Route::get('ajax/tickets/{params?}', ['uses' => 'TicketsController@ajaxTicketsRequest', 'as' => 'ajax.tickets']);
	Route::get('ajax/companies/{params?}', ['uses' => 'CompaniesController@ajaxCompanyRequest', 'as' => 'ajax.companies']);
	Route::get('ajax/companies/contacts/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxContactsRequest', 'as' => 'ajax.companies.contacts']);
	Route::get('ajax/companies/tickets/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxTicketsRequest', 'as' => 'ajax.companies.tickets']);
	Route::get('ajax/companies/equipments/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxEquipmentsRequest', 'as' => 'ajax.companies.equipments']);
	Route::get('ajax/employees/{params?}', ['uses' => 'PeopleController@ajaxEmployeesRequest', 'as' => 'ajax.employees']);
	Route::get('ajax/contacts/{params?}', ['uses' => 'PeopleController@ajaxContactsRequest', 'as' => 'ajax.contacts']);
	Route::get('ajax/equipments/{params?}', ['uses' => 'EquipmentsController@ajaxEquipmentsRequest', 'as' => 'ajax.equipments']);

// });
