<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Convergence\Models\User;
use Convergence\Models\Company;
use Convergence\Models\Person;
use Convergence\Models\CompanyPerson;
use Convergence\Models\Role;
use Convergence\Models\Permission;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		$this->call(UserTableSeeder::class);
		$this->call(RoleTableSeeder::class);
		$this->call(PermissionTableSeeder::class);
		$this->call(PermissionRoleTableSeeder::class);
		$this->call(RoleUserTableSeeder::class);
        Model::reguard();
	}
}

class UserTableSeeder extends Seeder {
    public function run() {
    	$person_id = Person::where('first_name','Francesco')->where('last_name','Meli')->first();
    	if (isset($person_id)) {
        	User::create(['id' => $person_id, 'person_id' => '173', 'username' => 'pinkynrg', 'password' => '$2y$10$95CM4BghK9tM0HxsDfAFyO0Ofw7AavS7rC6pArWpEk5ehw12P.U2a']);
    	}
    }
}

class RoleTableSeeder extends Seeder {
	public function run() {
		$role = new Role();
		$role->name         = 'admin';
		$role->display_name = 'Administrator';
		$role->description  = 'Administrator of the project';
		$role->save();

		$role = new Role();
		$role->name         = 'basic_user';
		$role->display_name = 'Basic User';
		$role->description  = 'Basic user';
		$role->save();
	}
}

class PermissionTableSeeder extends Seeder {
	public function run() {
		$permission = new Permission();
		$permission->name         = 'see-all-tickets';
		$permission->display_name = 'See all Tickets';
		$permission->description  = 'Allow a user to see all tickets';
		$permission->save();
	}
}

class PermissionRoleTableSeeder extends Seeder {
	public function run() {
		$adminRole = Role::where('name','admin')->first();
		$allTickets = Permission::where('name','see-all-tickets')->first();
		if ($adminRole && $allTickets) {
			$adminRole->attachPermission($allTickets);
		}
	}
}

class RoleUserTableSeeder extends Seeder {
	public function run() {
		$company = Company::where('name','Elettric80 - Chicago')->first();
		$person = Person::where('first_name','Francesco')->where('last_name','Meli')->first();
		$contact = CompanyPerson::where('person_id',$person->id)->where('company_id',$company->id)->first();
		$adminRole = Role::where('name','admin')->first();

		if ($person && $adminRole) {
			$contact->attachRole($adminRole);
		}
	}
}


