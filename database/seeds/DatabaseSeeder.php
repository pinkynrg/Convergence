<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Company;
use App\Models\Person;
use App\Models\CompanyPerson;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Group;
use App\Models\GroupType;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(EmptyDatabase::class);

		$this->call(RBACSeeder::class);
		$this->call(CompanyTableSeeder::class);
		$this->call(PeopleTableSeeder::class);
		$this->call(UserTableSeeder::class);
		$this->call(CompanyPersonTableSeeder::class);

        Model::reguard();
	}
}

class EmptyDatabase extends Seeder {

    public function run() {
    	// to remove sample data 
        $person = DB::table('people')->where('first_name','James')->where('last_name','Sample')->first();

        DB::table('users')->delete();

        if (isset($person)) {
        	DB::table('company_person')->where('person_id',$person->id)->delete();
			DB::table('people')->where('first_name','James')->where('last_name','Sample')->delete();
        }

        DB::table('groups')->delete();
        DB::table('group_types')->delete();
        DB::table('permissions')->delete();
        DB::table('roles')->delete();
        DB::table('permission_role')->delete();
    }
}

class CompanyTableSeeder extends Seeder {
	public function run() {
		Company::create(['name' => 'Sample Company']);
	}
}

class CompanyPersonTableSeeder extends Seeder {
	public function run() {
        
        $person = DB::table('people')->where('first_name','James')->where('last_name','Sample')->first();
        $company = Company::where('name','Sample Company')->first();
        $group_type = GroupType::where('name','employee')->first();
        $group = Group::where('name','e80-helpdesk')->first();

        if (isset($person)) {
			CompanyPerson::create(['person_id' => $person->id, 'company_id' => $company->id, 'group_type_id' => $group_type->id, 'group_id' => $group->id]);
        }
	}
}

class PeopleTableSeeder extends Seeder {
	public function run() {
		Person::create(['first_name' => 'James', 'last_name' => 'Sample']);
	}
}

class UserTableSeeder extends Seeder {
    public function run() {
    	// add a sample employee
    	$person = Person::where('first_name','Francesco')->where('last_name','Meli')->first();
    	if (isset($person)) {
        	User::create(['person_id' => $person->id, 'username' => 'pinkynrg', 'password' => '$2y$10$95CM4BghK9tM0HxsDfAFyO0Ofw7AavS7rC6pArWpEk5ehw12P.U2a']);
    	}

    	// add a sample customer
    	$person = Person::where('first_name','James')->where('last_name','Sample')->first();
    	if (isset($person)) {
        	User::create(['person_id' => $person->id, 'username' => 'sample_customer', 'password' => '$2y$10$95CM4BghK9tM0HxsDfAFyO0Ofw7AavS7rC6pArWpEk5ehw12P.U2a']);
    	}

    }
}

class RBACSeeder extends Seeder {
	public function run() {

		////////////////////////////////////////////////////////////////////////////////
		// creation group types ////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////

		$gt_employee = new GroupType();
		$gt_employee->name = "employee";
		$gt_employee->display_name = "Employee";
		$gt_employee->description = "this is the group type of all employees of E80";
		$gt_employee->save();

		$gt_customer = new GroupType();
		$gt_customer->name = "customer";
		$gt_customer->display_name = "Customer";
		$gt_customer->description = "this is the group type of all E80 customers";
		$gt_customer->save();
		
		////////////////////////////////////////////////////////////////////////////////
		// creation groups /////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////

		$helpdesk = new Group();
		$helpdesk->group_type_id = $gt_employee->id;
		$helpdesk->name = "e80-helpdesk";
		$helpdesk->display_name = "E80 Helpdesk";
		$helpdesk->description = "this is the group of all E80 helpdesk";
		$helpdesk->save();

		$basic_customer = new Group();
		$basic_customer->group_type_id = $gt_customer->id;
		$basic_customer->name = "basic-customer";
		$basic_customer->display_name = "Basic Customer";
		$basic_customer->description = "this is the group of E80 customers";
		$basic_customer->save();

		////////////////////////////////////////////////////////////////////////////////
		// creation permissions ////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////

		$ticket_create = new Permission; 
		$ticket_create->name = "create-tickets";
		$ticket_create->display_name = "Create Tickets";
		$ticket_create->description = "Create Tickets";
		$ticket_create->save();

		$ticket_read = new Permission; 
		$ticket_read->name = "read-tickets";
		$ticket_read->display_name = "Read Tickets";
		$ticket_read->description = "Read Tickets";
		$ticket_read->save();

		$ticket_update = new Permission; 
		$ticket_update->name = "update-tickets";
		$ticket_update->display_name = "Update Tickets";
		$ticket_update->description = "Update Tickets";
		$ticket_update->save();

		$ticket_delete = new Permission; 
		$ticket_delete->name = "delete-tickets";
		$ticket_delete->display_name = "Delete Tickets";
		$ticket_delete->description = "Delete Tickets";
		$ticket_delete->save();

		////////////////////////////////////////////////////////////////////////////////
		// creation roles //////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////

		$ticket_manager = new Role;
		$ticket_manager->name = "ticket-manager";
		$ticket_manager->display_name = "Ticket Manager";
		$ticket_manager->description = "Ticket Manager";
		$ticket_manager->save();

		$ticket_viewer = new Role;
		$ticket_viewer->name = "ticket-viewer";
		$ticket_viewer->display_name = "Ticket Viewer";
		$ticket_viewer->description = "Ticket Viewer";
		$ticket_viewer->save();

		////////////////////////////////////////////////////////////////////////////////
		// creation permission_role ////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////

		DB::table('permission_role')->insert([
			['role_id' => $ticket_manager->id, 'permission_id' => $ticket_create->id],
			['role_id' => $ticket_manager->id, 'permission_id' => $ticket_read->id],
			['role_id' => $ticket_manager->id, 'permission_id' => $ticket_update->id],
			['role_id' => $ticket_manager->id, 'permission_id' => $ticket_delete->id]
		]);

		DB::table('permission_role')->insert([
			['role_id' => $ticket_viewer->id, 'permission_id' => $ticket_read->id]
		]);

		////////////////////////////////////////////////////////////////////////////////
		// creation group_role /////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////

		DB::table('group_role')->insert([
			['group_id' => $helpdesk->id, 'role_id'=> $ticket_manager->id],
			['group_id' => $basic_customer->id, 'role_id'=> $ticket_viewer->id]
		]);
	}
}

