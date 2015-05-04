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
	Route::get('ajax/tickets/tickets', ['uses' => 'TicketsController@ajaxTicketsRequest', 'as' => 'tickets.tickets.ajax']);
	Route::get('ajax/contacts/contacts', ['uses' => 'ContactsController@ajaxContactsRequest', 'as' => 'contacts.contacts.ajax']);
	Route::get('ajax/customers/customers', ['uses' => 'CustomersController@ajaxCustomersRequest', 'as' => 'customers.customers.ajax']);
	Route::get('ajax/customers/{customer_id}', ['uses' => 'CustomersController@ajaxContactsRequest', 'as' => 'customers.contacts.ajax']);
	Route::get('ajax/customers/tickets/{customer_id}', ['uses' => 'CustomersController@ajaxTicketsRequest', 'as' => 'customers.tickets.ajax']);
	Route::get('ajax/customers/equipments/{customer_id}', ['uses' => 'CustomersController@ajaxEquipmentsRequest', 'as' => 'customers.equipments.ajax']);
	Route::get('ajax/employees/employees', ['uses' => 'EmployeesController@ajaxEmployeesRequest', 'as' => 'employees.employees.ajax']);
	Route::get('ajax/equipments/equipments', ['uses' => 'EquipmentsController@ajaxEquipmentsRequest', 'as' => 'equipments.equipments.ajax']);
});
