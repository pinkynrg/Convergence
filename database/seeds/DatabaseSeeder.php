<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Convergence\Models\User;
use Convergence\Models\Person;

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
        Model::reguard();
	}
}

class UserTableSeeder extends Seeder {
    public function run() {
    	$person_id = Person::where('first_name','Francesco')->where('last_name','Meli')->first();
        User::create(['id' => $person_id, 'person_id' => '173', 'username' => 'pinkynrg', 'password' => '$2y$10$95CM4BghK9tM0HxsDfAFyO0Ofw7AavS7rC6pArWpEk5ehw12P.U2a']);
    }
}
