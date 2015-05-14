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

Route::group(array('middleware' => 'auth'), function() {

	Route::get('import/{target?}', ['uses' => 'ImportController@import', 'as' => 'importer.import']);

	Route::get('/', ['uses'=>'TicketsController@index', 'as'=>'root']);

	Route::resource('customers','CustomersController',['middleware' => 'auth']);
	Route::get('contacts/create/{customer_id}', ['uses' => 'ContactsController@create', 'as' => 'contacts.create']);
	Route::resource('contacts','ContactsController', array('except' => array('create')));
	Route::resource('employees','EmployeesController');
	Route::resource('tickets','TicketsController');
	Route::resource('equipments','EquipmentsController');

	Route::get('ajax/tickets/{params?}', ['uses' => 'TicketsController@ajaxTicketsRequest', 'as' => 'ajax.tickets']);
	Route::get('ajax/contacts/{params?}', ['uses' => 'ContactsController@ajaxContactsRequest', 'as' => 'ajax.contacts']);
	Route::get('ajax/customers/{params?}', ['uses' => 'CustomersController@ajaxCustomersRequest', 'as' => 'ajax.customers']);
	Route::get('ajax/customers/contacts/{customer_id}/{params?}', ['uses' => 'CustomersController@ajaxContactsRequest', 'as' => 'ajax.customers.contacts']);
	Route::get('ajax/customers/tickets/{customer_id}/{params?}', ['uses' => 'CustomersController@ajaxTicketsRequest', 'as' => 'ajax.customers.tickets']);
	Route::get('ajax/customers/equipments/{customer_id}/{params?}', ['uses' => 'CustomersController@ajaxEquipmentsRequest', 'as' => 'ajax.customers.equipments']);
	Route::get('ajax/employees/{params?}', ['uses' => 'EmployeesController@ajaxEmployeesRequest', 'as' => 'ajax.employees']);
	Route::get('ajax/equipments/{params?}', ['uses' => 'EquipmentsController@ajaxEquipmentsRequest', 'as' => 'ajax.equipments']);
});
