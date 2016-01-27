<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EscalationProfile extends CustomModel {

	protected $table = 'escalation_profiles';

	protected $fillable = ['name','description'];
}
