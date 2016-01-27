<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EscalationEvent extends CustomModel {

	protected $table = 'escalation_events';

	protected $fillable = ['target','label'];
}
