<?php namespace Convergence\Models;

use DB;
use Convergence\Models\CompanyPerson;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function owner()
	{
		return $this->belongsTo('Convergence\Models\Person','person_id');
	}

	public function active_contact()
	{
		return $this->belongsTo('Convergence\Models\CompanyPerson','active_contact_id');
	}

	public function can($action)
	{
		$result = DB::table('users')
		->join('company_person','users.active_contact_id','=','company_person.id')
		->join('groups','groups.id','=','company_person.group_id')
		->join('group_role','group_role.group_id','=','groups.id')
		->join('roles','roles.id','=','group_role.role_id')
		->join('permission_role','permission_role.role_id','=','roles.id')
		->join('permissions','permissions.id','=','permission_role.permission_id')
		->where('permissions.name','=',$action)
		->where('company_person.id','=',$this->active_contact_id)
		->count();

		return $result ? true : false;
	}

	public function inGroup($group)
	{
		$result = DB::table('users')
		->join('company_person','users.active_contact_id','=','company_person.id')
		->join('groups','groups.id','=','company_person.group_id')
		->where('groups.name','=',$group)
		->count();

		return $result ? true : false;
	}

}
