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

Route::get('test_roles', function () {
	return view('test/roles');
});

Route::group(array('middleware' => 'auth'), function() {

	Route::get('/', ['as' => 'root', function () {
		return redirect()->route('tickets.index');
	}]);

	Route::get('sendemail',['uses' => 'EmailsController@sendPost', 'as' => 'emails.post']);

	// group_types routes 
	Route::get('group_types',['uses' => 'GroupTypesController@index', 'as' => 'group_types.index']);
	Route::get('group_types/create',['uses' => 'GroupTypesController@create', 'as' => 'group_types.create']);
	Route::get('group_types/{id}',['uses' => 'GroupTypesController@show', 'as' => 'group_types.show']);
	Route::post('group_types', ['uses' => 'GroupTypesController@store', 'as' => 'group_types.store']);
	Route::delete('group_types/{id}', ['uses' => 'GroupTypesController@destroy', 'as' => 'group_types.destroy']);
	Route::patch('group_types/{id}', ['uses' => 'GroupTypesController@update', 'as' => 'group_types.update']);	
	Route::get('group_types/{id}/edit', ['uses' => 'GroupTypesController@edit', 'as' => 'group_types.edit']);

	// groups routes 
	Route::get('groups',['uses' => 'GroupsController@index', 'as' => 'groups.index']);
	Route::get('groups/create',['uses' => 'GroupsController@create', 'as' => 'groups.create']);
	Route::get('groups/{id}',['uses' => 'GroupsController@show', 'as' => 'groups.show']);
	Route::post('groups', ['uses' => 'GroupsController@store', 'as' => 'groups.store']);
	Route::delete('groups/{id}', ['uses' => 'GroupsController@destroy', 'as' => 'groups.destroy']);
	Route::patch('groups/{id}', ['uses' => 'GroupsController@update', 'as' => 'groups.update']);	
	Route::get('groups/{id}/edit', ['uses' => 'GroupsController@edit', 'as' => 'groups.edit']);
	Route::post('groups/{id}/roles', ['uses' => 'GroupsController@updateGroupRoles', 'as' => 'groups.update_roles']);

	// roles routes 
	Route::get('roles',['uses' => 'RolesController@index', 'as' => 'roles.index']);
	Route::get('roles/create',['uses' => 'RolesController@create', 'as' => 'roles.create']);
	Route::get('roles/{id}',['uses' => 'RolesController@show', 'as' => 'roles.show']);
	Route::post('roles', ['uses' => 'RolesController@store', 'as' => 'roles.store']);
	Route::delete('roles/{id}', ['uses' => 'RolesController@destroy', 'as' => 'roles.destroy']);
	Route::patch('roles/{id}', ['uses' => 'RolesController@update', 'as' => 'roles.update']);	
	Route::get('roles/{id}/edit', ['uses' => 'RolesController@edit', 'as' => 'roles.edit']);
	Route::post('roles/{id}/permissions', ['uses' => 'RolesController@updateRolePermissions', 'as' => 'roles.update_permissions']);

	// permissions routes 
	Route::get('permissions',['uses' => 'PermissionsController@index', 'as' => 'permissions.index']);
	Route::get('permissions/create',['uses' => 'PermissionsController@create', 'as' => 'permissions.create']);
	Route::get('permissions/{id}',['uses' => 'PermissionsController@show', 'as' => 'permissions.show']);
	Route::post('permissions', ['uses' => 'PermissionsController@store', 'as' => 'permissions.store']);
	Route::delete('permissions/{id}', ['uses' => 'PermissionsController@destroy', 'as' => 'permissions.destroy']);
	Route::patch('permissions/{id}', ['uses' => 'PermissionsController@update', 'as' => 'permissions.update']);	
	Route::get('permissions/{id}/edit', ['uses' => 'PermissionsController@edit', 'as' => 'permissions.edit']);

	// companies routes 
	Route::get('companies',['uses' => 'CompaniesController@index', 'as' => 'companies.index']);
	Route::get('companies/create',['uses' => 'CompaniesController@create', 'as' => 'companies.create']);
	Route::get('companies/{id}',['uses' => 'CompaniesController@show', 'as' => 'companies.show']);
	Route::post('companies', ['uses' => 'CompaniesController@store', 'as' => 'companies.store']);
	Route::delete('companies/{id}', ['uses' => 'CompaniesController@destroy', 'as' => 'companies.destroy']);
	Route::patch('companies/{id}', ['uses' => 'CompaniesController@update', 'as' => 'companies.update']);	
	Route::get('companies/{id}/edit', ['uses' => 'CompaniesController@edit', 'as' => 'companies.edit']);

	// tickets routes 
	Route::get('tickets',['uses' => 'TicketsController@index', 'as' => 'tickets.index']);
	Route::get('tickets/create',['uses' => 'TicketsController@create', 'as' => 'tickets.create']);
	Route::get('tickets/{id}',['uses' => 'TicketsController@show', 'as' => 'tickets.show']);
	Route::post('tickets', ['uses' => 'TicketsController@store', 'as' => 'tickets.store']);
	Route::delete('tickets/{id}', ['uses' => 'TicketsController@destroy', 'as' => 'tickets.destroy']);
	Route::patch('tickets/{id}', ['uses' => 'TicketsController@update', 'as' => 'tickets.update']);	
	Route::get('tickets/{id}/edit', ['uses' => 'TicketsController@edit', 'as' => 'tickets.edit']);
		
	// posts routes
	Route::post('posts',['uses' => 'PostsController@store', 'as' => 'posts.store']);
	Route::get('posts/{id}',['uses' => 'PostsController@show', 'as' => 'posts.show']);
	Route::patch('posts/{id}',['uses' => 'PostsController@update', 'as' => 'posts.update']);
	Route::get('posts/{id}/edit', ['uses' => 'PostsController@edit', 'as' => 'posts.edit']);

	// equipment routes 
	Route::get('equipment',['uses' => 'EquipmentController@index', 'as' => 'equipment.index']);
	Route::get('equipment/create/{company_id}',['uses' => 'EquipmentController@create', 'as' => 'equipment.create']);
	Route::get('equipment/{id}',['uses' => 'EquipmentController@show', 'as' => 'equipment.show']);
	Route::post('equipment', ['uses' => 'EquipmentController@store', 'as' => 'equipment.store']);
	Route::delete('equipment/{id}', ['uses' => 'EquipmentController@destroy', 'as' => 'equipment.destroy']);
	Route::patch('equipment/{id}', ['uses' => 'EquipmentController@update', 'as' => 'equipment.update']);	
	Route::get('equipment/{id}/edit', ['uses' => 'EquipmentController@edit', 'as' => 'equipment.edit']);

	// services routes 
	Route::get('services',['uses' => 'ServicesController@index', 'as' => 'services.index']);
	Route::get('services/create/{company_id}/{technician_number?}',['uses' => 'ServicesController@create', 'as' => 'services.create']);
	Route::get('services/{id}',['uses' => 'ServicesController@show', 'as' => 'services.show']);
	Route::post('services', ['uses' => 'ServicesController@store', 'as' => 'services.store']);
	Route::delete('services/{id}', ['uses' => 'ServicesController@destroy', 'as' => 'services.destroy']);
	Route::patch('services/{id}', ['uses' => 'ServicesController@update', 'as' => 'services.update']);	
	Route::get('services/{id}/edit', ['uses' => 'ServicesController@edit', 'as' => 'services.edit']);
	Route::get('services/pdf/{id}',['uses' => 'ServicesController@pdf', 'as' => 'services.pdf']);
	Route::get('services/generate/pdf/{id}', ['uses' => 'ServicesController@generatePdf', 'as' => 'services.generate_pdf']);

	// people routes
	Route::get('people/{id}',['uses' => 'PeopleController@show', 'as' => 'people.show']);
	Route::delete('people/{id}', ['uses' => 'PeopleController@destroy', 'as' => 'people.destroy']);
	Route::patch('people/{id}', ['uses' => 'PeopleController@update', 'as' => 'people.update']);
	Route::get('people/{id}/edit', ['uses' => 'PeopleController@edit', 'as' => 'people.edit']);

	// users routes
	Route::get('users',['uses' => 'UsersController@index', 'as' => 'users.index']);
	Route::get('users/create', ['uses' => 'UsersController@create', 'as' => 'users.create']);
	Route::post('users', ['uses' => 'UsersController@store', 'as' => 'users.store']);
	Route::get('users/{id}',['uses' => 'UsersController@show', 'as' => 'users.show']);
	Route::patch('users/{id}', ['uses' => 'UsersController@update', 'as' => 'users.update']);
	Route::get('users/{id}/edit', ['uses' => 'UsersController@edit', 'as' => 'users.edit']);

	// company_person
	Route::get('contacts',['uses' => 'CompanyPersonController@index', 'as' => 'company_person.index']);
	Route::get('contacts/create', ['uses' => 'CompanyPersonController@create', 'as' => 'company_person.create']);
	Route::post('people', ['uses' => 'CompanyPersonController@store', 'as' => 'company_person.store']);
	Route::get('contacts/{company_person_id}',['uses' => 'CompanyPersonController@show', 'as' => 'company_person.show']);
	Route::get('contacts/{company_person_id}/edit', ['uses' => 'CompanyPersonController@edit', 'as' => 'company_person.edit']);
	Route::patch('contacts/{company_person_id}', ['uses' => 'CompanyPersonController@update', 'as' => 'company_person.update']);
	Route::delete('contacts/{company_person_id}', ['uses' => 'CompanyPersonController@destroy', 'as' => 'company_person.destroy']);

	//charts
	Route::match(['get'], 'dashboard', ['uses' => 'DashboardController@dashboardLoggedContact', 'as' => 'dashboard.logged']);
	Route::match(['get'], 'dashboard/{contact_id}', ['uses' => 'DashboardController@dashboardContact', 'as' => 'dashboard.show']);
	Route::match(['get'], 'statistics', ['uses' => 'StatisticsController@index', 'as' => 'statistics.index']);

	//attachments
	Route::get('files/{id}',['uses' => 'FilesController@show', 'as' => 'files.show']);
	Route::post('files', ['uses' => 'FilesController@upload', 'as' => 'files.upload']);
	Route::delete('files/{id}', ['uses' => 'FilesController@destroy', 'as' => 'files.destroy']);

	// ajax routes
	Route::get('ajax/tickets/{params?}', ['uses' => 'TicketsController@ajaxTicketsRequest', 'as' => 'ajax.tickets']);
	Route::get('ajax/companies/{params?}', ['uses' => 'CompaniesController@ajaxCompanyRequest', 'as' => 'ajax.companies']);
	Route::get('ajax/users/{params?}', ['uses' => 'UsersController@ajaxUsersRequest', 'as' => 'ajax.users']);
	Route::get('ajax/companies/contacts/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxContactsRequest', 'as' => 'ajax.companies.contacts']);
	Route::get('ajax/companies/tickets/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxTicketsRequest', 'as' => 'ajax.companies.tickets']);
	Route::get('ajax/companies/equipment/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxEquipmentRequest', 'as' => 'ajax.companies.equipment']);
	Route::get('ajax/companies/hotels/{company_id}/{params?}', ['uses' => 'CompaniesController@ajaxHotelsRequest', 'as' => 'ajax.companies.hotels']);
	Route::get('ajax/contacts/{params?}', ['uses' => 'CompanyPersonController@ajaxContactsRequest', 'as' => 'ajax.contacts']);
	Route::get('ajax/equipment/{params?}', ['uses' => 'EquipmentController@ajaxEquipmentRequest', 'as' => 'ajax.equipment']);
	Route::get('ajax/services/{params?}', ['uses' => 'ServicesController@ajaxServicesRequest', 'as' => 'ajax.services']);
	Route::get('ajax/permissions/{params?}', ['uses' => 'PermissionsController@ajaxPermissionsRequest', 'as' => 'ajax.permissions']);
	Route::get('ajax/tags', ['uses' => 'TagsController@ajaxTagsRequest', 'as' => 'ajax.tags']);
	Route::get('ajax/people', ['uses' => 'CompanyPersonController@ajaxPeopleRequest', 'as' => 'ajax.people']);
	Route::get('ajax/tickets/contacts/{company_id}', ['uses' => 'TicketsController@ajaxContactsRequest', 'as' => 'json.tickets.contacts']);
	Route::get('ajax/tickets/equipment/{company_id}', ['uses' => 'TicketsController@ajaxEquipmentRequest', 'as' => 'json.tickets.equipment']);
	Route::get('ajax/files/{resource_type}/{id}', ['uses' => 'FilesController@listFiles', 'as' => 'files.list']);

	Route::get('api/tickets/{params?}', ['uses' => 'TicketsController@getTickets', 'as' => 'api.tickets.index']);

});



